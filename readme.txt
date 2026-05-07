=== LinkDigest ===
Contributors: latz
Tags: links, blogging, roundup, curation
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.0
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Save and publish curated link roundups to your blog.

== Description ==

LinkDigest is a WordPress plugin for managing and publishing curated link roundups. Save interesting links, organise them by category, and publish them as blog post roundups — individually or as a grouped collection.

**Features:**

* Save links with title, URL, description, categories, and tags
* Publish links individually or as a grouped roundup post
* Organise links by category (inspired by frankysnotes.com)
* REST API for integration with browser extensions
* Schedule automatic roundup publishing (daily, weekly, monthly, or by count/age)
* Chrome extension support

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/linkdigest` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the LinkDigest menu in the admin dashboard to start adding links.

== Usage ==

=== Dashboard ===

The LinkDigest dashboard (LinkDigest › Dashboard) gives you an at-a-glance overview:

* **Stats bar** — total links, categories, published, and unpublished counts
* **Quick Add** — enter a title and URL to save a link in seconds without leaving the dashboard
* **Recent Unpublished** — the last five unsaved links; delete any of them directly from this list
* **Recently Published** — the last five published roundup posts with their status

=== Adding Links ===

**Manually (full form):** Go to LinkDigest › Add Link. Fill in:

* Title (required)
* URL
* Description (rich text)
* Categories — assign to one or more existing categories
* Tags — comma-separated keywords

**Quick add:** Use the Quick Add box on the Dashboard for a bare-minimum title + URL entry.

**Via Chrome extension:** Browse to any page and click the extension icon. The title and URL are pre-filled; add a description, pick a category, and click Save Link.

=== Managing Links ===

LinkDigest › All Links shows every saved link in a table:

* **Status badges** — Unpublished, Draft, or Published
* **Publish** — creates a WordPress post immediately for that single link
* **Delete** — removes the link permanently (shows an inline confirmation first)

=== Publishing ===

**Individual post:** Click Publish on any link in All Links. A new WordPress post is created with the link's title, description, and a "Read more" link to the source URL.

**Roundup post:** Click Publish on the Dashboard. All unpublished links are bundled into one post, grouped by category. Enter a custom title or leave the default ("Links Roundup – [date]"). Choose to publish immediately or save as draft.

Both flows support draft mode — use the Draft toggle before confirming.

=== Scheduling ===

LinkDigest › Schedule lets automatic roundup publishing run without manual action:

* **Daily / Weekly / Monthly** — pick a day and time
* **Count-based** — publish when a set number of unpublished links accumulates
* **Age-based** — publish when the oldest unpublished link reaches a set number of days
* **Manual** — disable automatic publishing entirely

The "Run Schedule Now" button triggers the next scheduled publish immediately, regardless of the configured interval.

=== Chrome Extension ===

1. Open Chrome and navigate to `chrome://extensions`
2. Enable **Developer mode** (top-right toggle)
3. Click **Load unpacked** and select the `chrome-extension` folder inside the plugin directory
4. In WordPress, go to LinkDigest › Settings, generate an API key, and copy the API Endpoint URL and key
5. Click the extension icon › **Settings**, paste both values, and save
6. From now on, click the extension icon on any page to save the current URL directly to your WordPress site

=== Settings ===

LinkDigest › Settings contains:

* **API Key** — generate or regenerate the key used by the Chrome extension
* **Post defaults** — default publish status, author, excerpt generation, source URL in content
* **UI options** — date format, compact view, links per page, category badges, accent color
* **Schedule** — see the dedicated Schedule page (above)
* **Advanced** — public API access, CORS headers, cache duration, debug logging

== Frequently Asked Questions ==

= How do I add links? =

Navigate to LinkDigest > Add New Link in the WordPress admin dashboard.

= Can I use this with the Chrome extension? =

Yes. Generate an API key in LinkDigest > Settings and configure it in the Chrome extension.

= How do I install the Chrome extension? =

The Chrome extension is not currently available in the Chrome Web Store. To install it, open Chrome and go to chrome://extensions, enable Developer mode, click "Load unpacked", and select the `chrome-extension` folder from the plugin directory.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
