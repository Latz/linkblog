<?php

declare(strict_types=1);

if (!defined("ABSPATH")) {
    exit;
}

use Brain\Monkey\Functions;

beforeEach(function (): void {
    Functions\when('__')->returnArg();
    $this->plugin = Mockery::mock(LinkDigest::class)->makePartial();
});

describe('LinkDigest::validateScheduleConfig()', function (): void {

    it('returns 400 unknown_keys when an unrecognized top-level key is present', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'daily', 'foo' => 'bar']);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('unknown_keys');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns 400 invalid_mode when mode key is missing', function (): void {
        $result = $this->plugin->validateScheduleConfig(['times' => ['09:00']]);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_mode');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns 400 invalid_mode when mode is not in the whitelist', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'invalid']);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_mode');
    });

    it('returns 400 invalid_times when times is not an array', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'daily', 'times' => '09:00']);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_times');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns 400 invalid_times when a times entry is not an HH:MM string', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'daily', 'times' => ['9am']]);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_times');
    });

    it('returns 400 invalid_recurrence when recurrence is not an array', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'weekly', 'recurrence' => 'bad']);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_recurrence');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns 400 invalid_trigger when trigger is not an array', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'count', 'trigger' => 'bad']);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_trigger');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns 400 invalid_trigger when trigger.count is zero', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'count', 'trigger' => ['count' => 0]]);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_trigger');
    });

    it('returns 400 invalid_trigger when trigger.days is negative', function (): void {
        $result = $this->plugin->validateScheduleConfig(['mode' => 'age', 'trigger' => ['days' => -3]]);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_trigger');
    });

    it('returns the sanitized payload for valid input', function (): void {
        $data   = ['mode' => 'daily', 'times' => ['09:00'], 'trigger' => ['count' => '5']];
        $result = $this->plugin->validateScheduleConfig($data);

        expect($result)->toBeArray();
        expect($result['mode'])->toBe('daily');
    });

    it('normalizes times by deduplicating and sorting', function (): void {
        $data   = ['mode' => 'daily', 'times' => ['09:00', '08:00', '09:00']];
        $result = $this->plugin->validateScheduleConfig($data);

        expect($result['times'])->toBe(['08:00', '09:00']);
    });

    it('coerces trigger.count and trigger.days to integers', function (): void {
        $data   = ['mode' => 'count', 'trigger' => ['count' => '10', 'days' => '7']];
        $result = $this->plugin->validateScheduleConfig($data);

        expect($result['trigger']['count'])->toBe(10);
        expect($result['trigger']['days'])->toBe(7);
    });
});
