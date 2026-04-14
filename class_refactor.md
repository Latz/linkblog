# Plan: Refactor all PHP into a `LinkBlog` class

## Approach: Static class + PHP traits

- **Static methods** — no instance to manage; hooks use `[LinkBlog::class, 'method']`
- **PHP traits** — one trait per existing module file; `LinkBlog` class `use`s all traits
- **Centralised hook registration** — all `add_action`/`add_filter` calls moved into `LinkBlog::register()`
- **camelCase method names** — consistent OOP convention

## New file layout

```
src/php/
├── traits/
│   ├── PostType.php          trait LinkBlog_PostType
│   ├── MetaBoxes.php         trait LinkBlog_MetaBoxes
│   ├── Publishing.php        trait LinkBlog_Publishing
│   ├── Batch.php             trait LinkBlog_Batch
│   ├── Queries.php           trait LinkBlog_Queries
│   ├── RestApi.php           trait LinkBlog_RestApi
│   └── Admin/
│       ├── Menu.php          trait LinkBlog_Admin_Menu
│       ├── Dashboard.php     trait LinkBlog_Admin_Dashboard
│       ├── LinksPage.php     trait LinkBlog_Admin_LinksPage
│       └── AddLink.php       trait LinkBlog_Admin_AddLink
└── class-linkblog.php        class LinkBlog { use all traits; register() }
```

Old files deleted: `src/php/{post-type,meta-boxes,publishing,batch,queries,rest-api}.php`
and `src/php/admin/{menu,dashboard,links-page,add-link}.php`

## Method name mapping

| Old function | New static method |
|---|---|
| `linkblogRegisterPostType()` | `registerPostType()` |
| `linkblogRegisterTaxonomies()` | `registerTaxonomies()` |
| `linkblogAddMetaBoxes()` | `addMetaBoxes()` |
| `linkblogUrlCallback($post)` | `urlMetaBoxCallback($post)` |
| `linkblogSaveUrl($post_id)` | `saveUrl($post_id)` |
| `linkblogValidateLinkForPublish($id)` | `validateLinkForPublish($id)` |
| `linkblogBuildPostContent(...)` | `buildPostContent(...)` |
| `linkblogMapTaxonomies($post_id, $link_id)` | `mapTaxonomies($post_id, $link_id)` |
| `linkblogCreateBlogPost($id, $as_draft)` | `createBlogPost($id, $as_draft)` |
| `linkblogBatchPublishLinks($ids, $as_draft)` | `batchPublishLinks($ids, $as_draft)` |
| `linkblog_create_roundup_post(...)` | `createRoundupPost(...)` |
| `linkblog_get_publish_statistics()` | `getPublishStatistics()` |
| `linkblog_get_links_grouped_by_category()` | `getLinksGroupedByCategory()` |
| `linkblog_unpublish_link($id)` | `unpublishLink($id)` |
| `linkblog_register_rest_routes()` | `registerRestRoutes()` |
| `linkblog_rest_delete_link($req)` | `restDeleteLink($req)` |
| `linkblog_get_schedule()` | `getSchedule()` |
| `linkblog_save_schedule($req)` | `saveSchedule($req)` |
| `linkblog_rest_permission_check($req)` | `restPermissionCheck($req)` |
| `linkblog_rest_add_link($req)` | `restAddLink($req)` |
| `linkblog_rest_get_categories($req)` | `restGetCategories($req)` |
| `linkblog_invalidate_categories_cache()` | `invalidateCategoriesCache()` |
| `linkblog_add_cors_headers()` | `addCorsHeaders()` |
| `linkblog_handle_preflight()` | `handlePreflight()` |
| `linkblog_admin_menu()` | `adminMenu()` |
| `linkblog_settings_page()` | `settingsPage()` |
| `linkblog_schedule_page()` | `schedulePage()` |
| `linkblog_enqueue_admin_assets($hook)` | `enqueueAdminAssets($hook)` |
| `linkblog_add_dashboard_widget()` | `addDashboardWidget()` |
| `linkblog_dashboard_widget_content()` | `dashboardWidgetContent()` |
| `linkblog_get_unpublished_link_ids()` | `getUnpublishedLinkIds()` |
| `linkblog_handle_batch_publish_request()` | `handleBatchPublishRequest()` |
| `linkblog_handle_roundup_request()` | `handleRoundupRequest()` |
| `linkblog_handle_quick_add_request()` | `handleQuickAddRequest()` |
| `linkblog_render_dashboard_notices(...)` | `renderDashboardNotices(...)` |
| `linkblog_render_unpublished_links_box(...)` | `renderUnpublishedLinksBox(...)` |
| `linkblog_render_recently_published_box(...)` | `renderRecentlyPublishedBox(...)` |
| `linkblog_render_publish_box(...)` | `renderPublishBox(...)` |
| `linkblog_render_quick_add_box(...)` | `renderQuickAddBox(...)` |
| `linkblog_render_dashboard_js()` | `renderDashboardJs()` |
| `linkblog_dashboard_page()` | `dashboardPage()` |
| `linkblog_show_links_page()` | `showLinksPage()` |
| `linkblog_add_link_page()` | `addLinkPage()` |

## `class-linkblog.php` register() hook list

```php
add_action('init', [self::class, 'registerPostType'], 0);
add_action('init', [self::class, 'registerTaxonomies'], 0);
add_action('init', [self::class, 'handlePreflight'], 1);
add_action('add_meta_boxes', [self::class, 'addMetaBoxes']);
add_action('save_post_linkblog', [self::class, 'saveUrl']);
add_action('rest_api_init', [self::class, 'registerRestRoutes']);
add_action('rest_api_init', [self::class, 'addCorsHeaders']);
add_action('created_linkblog_category', [self::class, 'invalidateCategoriesCache']);
add_action('edited_linkblog_category',  [self::class, 'invalidateCategoriesCache']);
add_action('delete_linkblog_category',  [self::class, 'invalidateCategoriesCache']);
add_action('admin_menu', [self::class, 'adminMenu']);
add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
add_action('wp_dashboard_setup', [self::class, 'addDashboardWidget']);
// parent_file / submenu_file filters stay inside adminMenu()
```

## Test file updates

| Test file | Old call → new |
|---|---|
| `ValidateLinkTest.php` | `linkblogValidateLinkForPublish()` → `LinkBlog::validateLinkForPublish()` |
| `CreateBlogPostTest.php` | `linkblogCreateBlogPost()` → `LinkBlog::createBlogPost()` |
| `BatchPublishTest.php` | `linkblogBatchPublishLinks()` → `LinkBlog::batchPublishLinks()` |
| `BuildPostContentTest.php` | `linkblogBuildPostContent()` → `LinkBlog::buildPostContent()` |
| `RestAddLinkTest.php` | `linkblog_rest_add_link()` → `LinkBlog::restAddLink()` |
| `UnpublishLinkTest.php` | `linkblog_unpublish_link()` → `LinkBlog::unpublishLink()` |
| `CategoriesTest.php` | `linkblog_rest_get_categories()` → `LinkBlog::restGetCategories()`; `linkblog_invalidate_categories_cache()` → `LinkBlog::invalidateCategoriesCache()` |
| `RestPermissionTest.php` | `linkblog_rest_permission_check()` → `LinkBlog::restPermissionCheck()` |
| `ScheduleTest.php` | `linkblog_get_schedule()` → `LinkBlog::getSchedule()`; `linkblog_save_schedule()` → `LinkBlog::saveSchedule()` |

## Verification

```bash
composer test    # 58 unit tests — all must pass
```
