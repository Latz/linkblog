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
    public function executeSchedule(): void {
        $config  = get_option('linkdigest_schedule', []);
        $mode    = $config['mode']    ?? 'daily';
        $trigger = $config['trigger'] ?? [];

        $link_ids = $this->getUnpublishedLinkIds();

        // Cap per-run to prevent max_execution_time / OOM on large queues.
        // Remaining links are handled by an immediate reschedule below.
        $max_per_run = 200;
        $has_more = count($link_ids) > $max_per_run;
        if ($has_more) {
            $link_ids = array_slice($link_ids, 0, $max_per_run);
        }

        $should_publish = match ($mode) {
            'count' => count($link_ids) >= (int) ($trigger['count'] ?? 10),
            'age'   => $this->hasUnpublishedLinkOlderThan((int) ($trigger['days'] ?? 7)),
            default => !empty($link_ids), // daily/weekly/monthly: publish if any links exist
        };

        if ($should_publish && !empty($link_ids)) {
            /* translators: %s is the formatted date (e.g. "April 15, 2026") */
            $title = sprintf(__('Links: %s', 'LinkDigest'), wp_date('F j, Y'));

            // WP-Cron runs unauthenticated; elevate to an admin so createRoundupPost()
            // passes its current_user_can('publish_posts') guard.
            $prev_user_id = get_current_user_id();
            if (empty($prev_user_id)) {
                $admin_ids = get_users(array('role' => 'administrator', 'number' => 1, 'fields' => 'ids'));
                if (!empty($admin_ids)) {
                    wp_set_current_user((int) $admin_ids[0]);
                }
            }

            $this->createRoundupPost($link_ids, $title);

            // Restore previous user context.
            if (empty($prev_user_id)) {
                wp_set_current_user(0);
            }

            if ($has_more) {
                wp_schedule_single_event(time() + 60, 'linkdigest_execute_schedule');
            }
        }

        $this->scheduleNextEvent();
    }

    // Returns next UNIX timestamp (UTC) based on schedule config, or null for 'manual'
    public function getNextScheduleTimestamp(): ?int {
        $config     = get_option('linkdigest_schedule', []);
        $mode       = $config['mode']       ?? 'daily';
        $times      = $config['times']      ?? ['09:00'];
        $recurrence = $config['recurrence'] ?? [];

        if ($mode === 'manual') {
            return null;
        }

        $tz  = wp_timezone();
        $now = new \DateTime('now', $tz);
        sort($times);

        // 367-day window handles monthly schedules where no day matches in the current
        // month (e.g., 31st in a 30-day month) and leap-year edge cases.
        for ($i = 0; $i <= 366; $i++) {
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
        foreach ($rec['monthDays'] ?? [] as $md) {
            if (($md['type'] ?? '') === 'day' && (int) ($md['value'] ?? 0) === $dom) {
                return true;
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
            'date_query'     => [['before' => $cutoff]],
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                ['key' => '_linkdigest_publish_status', 'compare' => self::META_COMPARE_NOT_EXISTS],
                ['key' => '_linkdigest_publish_status', 'value' => ['published', 'draft'], 'compare' => self::META_COMPARE_NOT_IN],
            ],
        ]);
        return !empty($found);
    }
}
