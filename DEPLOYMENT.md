# LinkBlog Plugin — Deployment Guide

## Package Contents

The deployable plugin package (`dist/linkblog.zip`) includes:

- **linkblog.php** — Main plugin file with headers and initialization
- **readme.txt** — WordPress.org-compatible plugin metadata and changelog
- **src/php/** — All PHP class and trait files
- **src/js/** — Browser extension JavaScript utilities
- **src/schedule/** — React admin scheduling interface
- **assets/** — Plugin icons (20px, 128px, 256px)
- **dashboard.css** — Admin dashboard styles

## Building the Package

To build a fresh deployment package:

```bash
cd /path/to/plugins/LinkBlog
rm dist/linkblog.zip

mkdir -p /tmp/linkblog-build
cp -r . /tmp/linkblog-build/
cd /tmp/linkblog-build

# Remove files listed in .distignore
while IFS= read -r line; do
  [[ -z "$line" || "$line" =~ ^# ]] && continue
  rm -rf "$line"
done < .distignore

cd /tmp
zip -r /path/to/plugins/LinkBlog/dist/linkblog.zip linkblog-build/
rm -rf /tmp/linkblog-build
```

**The package is ~1.4 MB** (includes node_modules by default; update .distignore if needed).

## Installation

1. Download `dist/linkblog.zip`
2. Go to WordPress Admin → Plugins → Add New
3. Upload the ZIP file
4. Click "Install Now"
5. Activate the plugin

## WordPress.org Submission

For submission to the official WordPress.org plugin directory:

1. Ensure readme.txt is valid:
   ```bash
   wp plugin validate-readme path/to/readme.txt
   ```

2. Run [Plugin Check](https://github.com/WordPress/plugin-check):
   ```bash
   wp plugin list
   wp plugin-check linkblog --plugins=linkblog
   ```

3. Address any issues and rebuild the package

4. Submit via: https://wordpress.org/plugins/submit/

## Configuration

### REST API Access

Settings stored in `option('linkblog_schedule')`:
- `mode`: 'daily', 'weekly', 'monthly', 'count', 'age', or 'manual'
- `trigger`: Trigger conditions (count threshold, age in days)
- `times`: Array of HH:MM times to publish
- `recurrence`: Weekly/monthly configuration

### WP-Cron Setup

The plugin uses WordPress's WP-Cron system:

- Events are scheduled via `wp_schedule_single_event()`
- Triggered by `wp-cron.php` on each page load (if `DISABLE_WP_CRON` is not set)
- For production, set up a system cron:

  ```bash
  # Add to crontab -e
  */5 * * * * curl -s 'http://yoursite.com/wp/wp-cron.php?doing_wp_cron' > /dev/null 2>&1
  
  # Add to wp-config.php
  define('DISABLE_WP_CRON', true);
  ```

## Compatibility

- **WordPress**: 6.0+
- **PHP**: 8.0+
- **Tested up to**: WordPress 6.9

## Chrome Extension

The plugin includes API endpoints for the companion Chrome extension:

- GET `/linkblog/v1/auth/key` — Get API key
- POST `/linkblog/v1/links` — Add a link
- DELETE `/linkblog/v1/links/{id}` — Delete a link
- GET `/linkblog/v1/categories` — List categories

Generate an API key in the plugin settings dashboard.

---

**Version**: 1.0.0  
**License**: GPLv2 or later
