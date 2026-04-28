<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait LinkDigest_ScheduleValidator {

    public function validateScheduleConfig(array $data): array|\WP_Error {
        $allowed_keys = ['mode', 'times', 'recurrence', 'trigger'];
        $unknown      = array_diff(array_keys($data), $allowed_keys);
        if (!empty($unknown)) {
            return new \WP_Error(
                'unknown_keys',
                /* translators: %s: comma-separated list of unrecognized field names */
                sprintf(__('Unknown schedule fields: %s', 'linkdigest'), implode(', ', $unknown)),
                ['status' => 400]
            );
        }

        $valid_modes = ['daily', 'weekly', 'monthly', 'count', 'age', 'manual'];
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

        return $data;
    }
}
