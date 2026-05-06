<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait LinkDigest_ScheduleValidator {

    /**
     * Validate and sanitize schedule configuration data.
     *
     * @since 1.0.0
     * @param array $data The schedule configuration to validate.
     * @return array|\WP_Error Validated configuration or WP_Error.
     */
    public function validateScheduleConfig(array $data): array|\WP_Error {
        $allowed_keys = ['mode', 'times', 'recurrence', 'trigger', 'publishAs', 'notify', 'post_status'];
        $unknown      = array_diff(array_keys($data), $allowed_keys);
        if (!empty($unknown)) {
            return new \WP_Error(
                'unknown_keys',
                /* translators: %s: comma-separated list of unrecognized field names */
                sprintf(__('Unknown schedule fields: %s', 'linkdigest'), implode(', ', $unknown)),
                ['status' => 400]
            );
        }

        $valid_modes = array_column(ScheduleMode::cases(), 'value');
        if (!isset($data['mode']) || !in_array($data['mode'], $valid_modes, true)) {
            return new \WP_Error('invalid_mode', __('Invalid schedule mode', 'linkdigest'), ['status' => 400]);
        }

        if (isset($data['times'])) {
            if (!is_array($data['times'])) {
                return new \WP_Error('invalid_times', __('times must be an array', 'linkdigest'), ['status' => 400]);
            }
            foreach ($data['times'] as $t) {
                if (!is_string($t) || !preg_match('/^\d{2}:\d{2}$/', $t)) {
                    return new \WP_Error('invalid_times', __('times entries must be HH:MM strings', 'linkdigest'), ['status' => 400]);
                }
            }
            $data['times'] = array_values(array_unique($data['times']));
            sort($data['times']);
        }

        if (isset($data['recurrence']) && !is_array($data['recurrence'])) {
            return new \WP_Error('invalid_recurrence', __('recurrence must be an object', 'linkdigest'), ['status' => 400]);
        }

        if (isset($data['trigger'])) {
            if (!is_array($data['trigger'])) {
                return new \WP_Error('invalid_trigger', __('trigger must be an object', 'linkdigest'), ['status' => 400]);
            }
            if (isset($data['trigger']['count'])) {
                $data['trigger']['count'] = (int) $data['trigger']['count'];
                if ($data['trigger']['count'] <= 0) {
                    return new \WP_Error('invalid_trigger', __('trigger.count must be a positive integer', 'linkdigest'), ['status' => 400]);
                }
            }
            if (isset($data['trigger']['days'])) {
                $data['trigger']['days'] = (int) $data['trigger']['days'];
                if ($data['trigger']['days'] <= 0) {
                    return new \WP_Error('invalid_trigger', __('trigger.days must be a positive integer', 'linkdigest'), ['status' => 400]);
                }
            }
        }

        if (isset($data['publishAs'])) {
            $data['publishAs'] = (int) $data['publishAs'];
            if ($data['publishAs'] < 0) {
                return new \WP_Error('invalid_publish_as', __('publishAs must be a non-negative integer', 'linkdigest'), ['status' => 400]);
            }
        }

        if (isset($data['post_status'])) {
            if (!in_array($data['post_status'], ['publish', 'draft'], true)) {
                return new \WP_Error('invalid_post_status', __('post_status must be "publish" or "draft"', 'linkdigest'), ['status' => 400]);
            }
        }

        if (isset($data['notify'])) {
            if (!is_array($data['notify'])) {
                return new \WP_Error('invalid_notify', __('notify must be an object', 'linkdigest'), ['status' => 400]);
            }
            $data['notify']['enabled'] = (bool) ($data['notify']['enabled'] ?? false);
            if (!empty($data['notify']['email'])) {
                $data['notify']['email'] = sanitize_email($data['notify']['email']);
                if (!is_email($data['notify']['email'])) {
                    return new \WP_Error('invalid_notify_email', __('notify.email is not a valid email address', 'linkdigest'), ['status' => 400]);
                }
            }
        }

        return $data;
    }
}
