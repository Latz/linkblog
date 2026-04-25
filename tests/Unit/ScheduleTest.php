<?php

declare(strict_types=1);

if (!defined("ABSPATH")) {
    exit;
}

use Brain\Monkey\Functions;

/**
 * Tests for linkdigest_get_schedule() and linkdigest_save_schedule()
 */

beforeEach(function (): void {
    Functions\when('rest_ensure_response')->returnArg();
    $this->plugin = Mockery::mock(LinkBlog::class)->makePartial();
});

describe('LinkBlog::getSchedule()', function (): void {

    it('returns the stored option when one exists', function (): void {
        $stored = ['mode' => 'weekly', 'times' => ['08:00']];
        Functions\when('get_option')->justReturn($stored);

        $result = $this->plugin->getSchedule();

        expect($result['mode'])->toBe('weekly');
        expect($result['times'])->toBe(['08:00']);
    });

    it('returns the default schedule when no option is stored', function (): void {
        Functions\when('get_option')
            ->alias(fn($key, $default) => $default);

        $result = $this->plugin->getSchedule();

        expect($result)->toHaveKey('mode');
        expect($result['mode'])->toBe('daily');
        expect($result)->toHaveKey('times');
        expect($result)->toHaveKey('trigger');
    });
});

describe('LinkBlog::saveSchedule()', function (): void {

    beforeEach(function (): void {
        Functions\when('__')->returnArg();
    });

    it('returns a 400 WP_Error when the request body is empty', function (): void {
        $request = linkdigest_make_request(); // no JSON body

        $result = $this->plugin->saveSchedule($request);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_data');
        expect($result->get_error_data()['status'])->toBe(400);
    });

    it('returns a 400 WP_Error when mode key is missing', function (): void {
        $request = linkdigest_make_request(['recurrence' => []]);

        $result = $this->plugin->saveSchedule($request);

        expect($result)->toBeInstanceOf(WP_Error::class);
        expect($result->get_error_code())->toBe('invalid_data');
    });

    it('saves valid schedule data and returns success', function (): void {
        $data    = ['mode' => 'daily', 'times' => ['09:00']];
        $request = linkdigest_make_request($data);

        $savedKey = null;
        $savedVal = null;
        Functions\when('update_option')->alias(
            function (string $key, mixed $val) use (&$savedKey, &$savedVal): bool {
                $savedKey = $key;
                $savedVal = $val;
                return true;
            }
        );

        $result = $this->plugin->saveSchedule($request);

        expect($result['success'])->toBeTrue();
        expect($savedKey)->toBe('linkdigest_schedule');
        expect($savedVal)->toBe($data);
    });
});
