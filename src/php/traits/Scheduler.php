<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait LinkDigest_Scheduler {

    // Called from register() — binds the cron callback
    public function registerSchedulerHooks(): void {
        add_action('linkdigest_execute_schedule', [$this, 'executeSchedule']);
    }

    // Calculates next timestamp, cancels any pending event, schedules a new one
    public function scheduleNextEvent(): void {
        wp_clear_scheduled_hook('linkdigest_execute_schedule');
        $ts = $this->getNextScheduleTimestamp();
        if ($ts !== null) {
            wp_schedule_single_event($ts, 'linkdigest_execute_schedule');
        }
    }

    // Cron callback: check trigger condition → publish → re-schedule
    public function executeSchedule(bool $reschedule = true): array {
        if (get_transient('linkdigest_run_lock')) {
            return ['published' => false, 'post_id' => null, 'link_count' => 0, 'reason' => 'locked'];
        }
        set_transient('linkdigest_run_lock', 1, 5 * MINUTE_IN_SECONDS);
        try {
            return $this->doExecuteSchedule($reschedule);
        } finally {
            delete_transient('linkdigest_run_lock');
        }
    }

    private function doExecuteSchedule(bool $reschedule): array {
        $config  = get_option('linkdigest_schedule', []);
        $mode    = $config['mode']    ?? 'daily';
        $trigger = $config['trigger'] ?? [];

        $link_ids    = $this->getUnpublishedLinkIds();
        $total_count = count($link_ids);

        // Cap per-run to prevent max_execution_time / OOM on large queues.
        // Remaining links are handled by an immediate reschedule below.
        $max_per_run = (int) apply_filters('linkdigest_max_per_run', self::MAX_PER_RUN);
        $has_more    = $total_count > $max_per_run;
        if ($has_more) {
            $link_ids = array_slice($link_ids, 0, $max_per_run);
        }

        $should_publish = match ($mode) {
            'count' => $total_count >= (int) ($trigger['count'] ?? 10),
            'age'   => $this->hasUnpublishedLinkOlderThan((int) ($trigger['days'] ?? 7)),
            default => !empty($link_ids), // daily/weekly/monthly: publish if any links exist
        };
        $should_publish = (bool) apply_filters('linkdigest_should_publish', $should_publish, $link_ids, $mode, $trigger);

        $scheduled_catchup = false;
        $result            = ['published' => false, 'post_id' => null, 'link_count' => 0, 'reason' => 'condition_not_met'];

        if ($should_publish && !empty($link_ids)) {
            /* translators: %s is the formatted date (e.g. "April 15, 2026") */
            $title = sprintf(__('Links: %s', 'linkdigest'), wp_date('F j, Y'));
            $title = (string) apply_filters('linkdigest_roundup_title', $title, $link_ids, $mode);

            // Use the stored publishAs user; fall back to first administrator.
            // WP-Cron runs unauthenticated, so we must elevate before calling
            // createRoundupPost() which guards on current_user_can('publish_posts').
            $publish_as   = (int) ($config['publishAs'] ?? 0);
            $prev_user_id = get_current_user_id();
            if ($publish_as === 0) {
                $admin_ids  = get_users(['role' => 'administrator', 'number' => 1, 'fields' => 'ids']);
                $publish_as = !empty($admin_ids) ? (int) $admin_ids[0] : 0;
            }
            if ($publish_as > 0 && get_current_user_id() !== $publish_as) {
                wp_set_current_user($publish_as);
            }

            do_action('linkdigest_before_run', $link_ids, $mode);
            $roundup = $this->createRoundupPost($link_ids, $title, false, $mode);

            // Restore previous user context.
            if (get_current_user_id() !== $prev_user_id) {
                wp_set_current_user($prev_user_id);
            }

            $post_id = ($roundup['post_id'] ?? 0) ?: null;
            do_action('linkdigest_after_run', $post_id, $link_ids, $mode);

            if ($has_more) {
                wp_schedule_single_event(time() + self::RESCHEDULE_DELAY, 'linkdigest_execute_schedule');
                $scheduled_catchup = true;
            }

            if (!$scheduled_catchup && $reschedule) {
                $this->scheduleNextEvent();
            }

            $result = [
                'published'  => $roundup['success'] ?? false,
                'post_id'    => $post_id,
                'link_count' => count($link_ids),
                'reason'     => $roundup['message'] ?? null,
            ];
        } else {
            if ($reschedule) {
                $this->scheduleNextEvent();
            }
        }

        update_option('linkdigest_last_run', [
            'ts'         => time(),
            'mode'       => $mode,
            'link_count' => $result['link_count'],
            'post_id'    => $result['post_id'],
            'status'     => $result['published'] ? 'success' : 'skipped',
            'reason'     => $result['reason'],
        ]);

        return $result;
    }

    // Returns next UNIX timestamp (UTC) based on schedule config, or null for 'manual'
    public function getNextScheduleTimestamp(): ?int {
        $config     = get_option('linkdigest_schedule', []);
        $mode       = $config['mode']       ?? 'daily';
        $times      = $config['times']      ?? [];
        if (empty($times)) {
            $times = [self::DEFAULT_TIME];
        }
        $recurrence = $config['recurrence'] ?? [];

        if ($mode === 'manual') {
            return null;
        }

        $tz  = wp_timezone();
        $now = new \DateTime('now', $tz);
        sort($times);

        // 367-day window handles monthly schedules where no day matches in the current
        // month (e.g., 31st in a 30-day month) and leap-year edge cases.
        for ($i = 0; $i <= self::SEARCH_HORIZON_DAYS; $i++) {
            $day = (clone $now)->modify("+{$i} days");

            if (!$this->dayMatchesSchedule($day, $mode, $recurrence)) {
                continue;
            }

            foreach ($times as $t) {
                [$h, $m] = explode(':', $t);
                $candidate = (clone $day)->setTime((int) $h, (int) $m, 0);
                if ($candidate > $now) {
                    return $candidate->getTimestamp();
                }
            }
        }

        return null;
    }

    private function dayMatchesSchedule(\DateTime $date, string $mode, array $rec): bool {
        if (in_array($mode, ['daily', 'count', 'age'], true)) {
            return true;
        }
        return match ($mode) {
            'weekly'  => $this->matchesWeeklySchedule($date, $rec),
            'monthly' => $this->matchesMonthlySchedule($date, $rec),
            default   => true,
        };
    }

    private function matchesWeeklySchedule(\DateTime $date, array $rec): bool {
        $map = ['MO' => 1, 'TU' => 2, 'WE' => 3, 'TH' => 4, 'FR' => 5, 'SA' => 6, 'SU' => 7];
        $dow = (int) $date->format('N');
        foreach ($rec['weekdays'] ?? [] as $wd) {
            if (($map[$wd] ?? 0) === $dow) {
                return true;
            }
        }
        return false;
    }

    private function matchesMonthlySchedule(\DateTime $date, array $rec): bool {
        $dom = (int) $date->format('j');
        $map = ['MO' => 1, 'TU' => 2, 'WE' => 3, 'TH' => 4, 'FR' => 5, 'SA' => 6, 'SU' => 7];
        foreach ($rec['monthDays'] ?? [] as $md) {
            $type = $md['type'] ?? '';
            if ($type === 'day' && (int) ($md['value'] ?? 0) === $dom) {
                return true;
            }
            if ($type === 'weekday') {
                $target_dow = $map[$md['weekday'] ?? ''] ?? 0;
                $nth        = (int) ($md['nth'] ?? 0);
                if ($target_dow === 0 || $nth === 0) {
                    continue;
                }
                $first      = (clone $date)->modify('first day of this month');
                $offset     = ($target_dow - (int) $first->format('N') + 7) % 7;
                $target_dom = 1 + $offset + ($nth - 1) * 7;
                if ($dom === $target_dom) {
                    return true;
                }
            }
        }
        return false;
    }

    private function hasUnpublishedLinkOlderThan(int $days): bool {
        $cutoff = gmdate('Y-m-d H:i:s', strtotime("-{$days} days"));
        $found  = get_posts([
            'post_type'      => 'linkdigest',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'date_query'     => [['before' => $cutoff, 'column' => 'post_date_gmt']],
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                ['key' => '_linkdigest_publish_status', 'compare' => self::META_COMPARE_NOT_EXISTS],
                ['key' => '_linkdigest_publish_status', 'value' => ['published', 'draft'], 'compare' => self::META_COMPARE_NOT_IN],
            ],
        ]);
        return !empty($found);
    }
}
