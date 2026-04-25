<?php

declare(strict_types=1);

if (!defined("ABSPATH")) {
    exit;
}

use Brain\Monkey\Functions;

beforeEach(function (): void {
    Functions\when('get_post_meta')->justReturn('');
    Functions\when('mysql2date')->returnArg(2);
    Functions\when('get_the_date')->justReturn('2026-04-22');
    Functions\when('admin_url')->justReturn('https://example.com/wp-admin/admin.php?page=linkdigest');
    Functions\when('wp_nonce_url')->returnArg();
    Functions\when('get_permalink')->justReturn('https://example.com/post/1');
    Functions\when('get_edit_post_link')->justReturn('https://example.com/wp-admin/edit?p=1');
    $this->plugin = Mockery::mock(LinkBlog::class)->makePartial();
});

describe('LinkBlog::showLinksPage() rendering', function (): void { // NOSONAR

    it('shows no-links message when there are no links', function (): void {
        $this->plugin->shouldReceive('getLinksGroupedByCategory')->andReturn([]);

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('No links found');
    });

    it('shows a category section for each category', function (): void {
        $link = linkdigest_make_post(1, 'Test Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['Tech' => [$link]]);

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('lb-category-section');
        expect($html)->toContain('Tech');
    });

    it('renders the link title in the table row', function (): void {
        $link = linkdigest_make_post(1, 'My Test Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('My Test Link');
    });

    it('renders a URL anchor when a URL is set', function (): void {
        $link = linkdigest_make_post(1, 'Link With URL');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);
        Functions\when('get_post_meta')->alias(
            fn($id, $key, $single) => $key === '_linkdigest_url' ? 'https://example.com' : ''
        );

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('https://example.com');
        expect($html)->toContain('target="_blank"');
    });

    it('renders a dash when no URL is set', function (): void {
        $link = linkdigest_make_post(1, 'Link Without URL');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('lb-status-unpublished');
        expect($html)->not->toContain('target="_blank"');
    });

    it('defaults publish_status to unpublished when empty', function (): void {
        $link = linkdigest_make_post(1, 'New Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('lb-status-unpublished');
    });

    it('shows published status badge for a published link', function (): void {
        $link = linkdigest_make_post(1, 'Published Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);
        Functions\when('get_post_meta')->alias(
            fn($id, $key, $single) => $key === '_linkdigest_publish_status' ? 'published' : ''
        );

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('lb-status-published');
    });

    it('shows the published date when set', function (): void {
        $link = linkdigest_make_post(1, 'Dated Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);
        Functions\when('get_post_meta')->alias(
            fn($id, $key, $single) => $key === '_linkdigest_published_date' ? '2026-01-15' : ''
        );

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('2026-01-15');
    });

    it('truncates long URLs at 50 characters', function (): void {
        $longUrl = 'https://example.com/' . str_repeat('a', 40);
        $link = linkdigest_make_post(1, 'Long URL Link');
        $this->plugin->shouldReceive('getLinksGroupedByCategory')
            ->andReturn(['General' => [$link]]);
        Functions\when('get_post_meta')->alias(
            fn($id, $key, $single) => $key === '_linkdigest_url' ? $longUrl : ''
        );

        ob_start();
        $this->plugin->showLinksPage();
        $html = ob_get_clean();

        expect($html)->toContain('...');
    });
});
