# Plugin Check Report

**Plugin:** LinkBlog
**Generated at:** 2026-04-17 21:39:58


## `src/php/traits/Batch.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 29 | 21 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 29 | 24 | ERROR | WordPress.WP.I18n.UnorderedPlaceholdersText | Multiple placeholders in translatable strings should be ordered. Expected "%1$s, %2$s", but got "%s, %s" in 'Failed to publish "%s": %s'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#variables) |
| 93 | 37 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |

## `dist/linkblog.zip`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | compressed_files | Compressed files are not permitted. |  |

## `test-results/.last-run.json`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | hidden_files | Hidden files are not permitted. |  |

## `.wp-env.json`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | hidden_files | Hidden files are not permitted. |  |

## `.scannerwork/.sonar_lock`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | hidden_files | Hidden files are not permitted. |  |

## `.sync_8cb9c76aa208.db`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | hidden_files | Hidden files are not permitted. |  |

## `bin/install-wp-tests.sh`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | application_detected | Application files are not permitted. |  |

## `bin/sonar-scanner.sh`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | application_detected | Application files are not permitted. |  |

## `tests/bootstrap-integration.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |
| 21 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound | Global variables defined by a theme/plugin should start with the theme/plugin prefix. Found: &quot;$wpTestsDir&quot;. |  |
| 24 | 10 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '"\nERROR: WP test suite not found at {$wpTestsDir}.\n"'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |

## `tests/stubs/wp-stubs.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |
| 117 | 40 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;add_action&quot;. |  |
| 118 | 40 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;add_filter&quot;. |  |
| 119 | 40 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;do_action&quot;. |  |
| 120 | 42 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;remove_action&quot;. |  |
| 125 | 39 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;__&quot;. |  |
| 126 | 39 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;_e&quot;. |  |
| 126 | 100 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$t'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 127 | 39 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;_x&quot;. |  |
| 128 | 39 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_html__&quot;. |  |
| 134 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_html&quot;. |  |
| 135 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_attr&quot;. |  |
| 136 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_url&quot;. |  |
| 137 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_url_raw&quot;. |  |
| 138 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;esc_js&quot;. |  |
| 139 | 41 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_kses_post&quot;. |  |
| 141 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;sanitize_text_field&quot;. |  |
| 141 | 67 | ERROR | WordPress.WP.AlternativeFunctions.strip_tags_strip_tags | strip_tags() is discouraged. Use the more comprehensive wp_strip_all_tags() instead. |  |
| 148 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;apply_filters&quot;. |  |
| 155 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_post&quot;. |  |
| 158 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_post_type&quot;. |  |
| 161 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_post_meta&quot;. |  |
| 164 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;update_post_meta&quot;. |  |
| 167 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;delete_post_meta&quot;. |  |
| 170 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_insert_post&quot;. |  |
| 173 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_delete_post&quot;. |  |
| 176 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_trash_post&quot;. |  |
| 179 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_posts&quot;. |  |
| 182 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_count_posts&quot;. |  |
| 195 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_terms&quot;. |  |
| 198 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_term_by&quot;. |  |
| 201 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_insert_term&quot;. |  |
| 206 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_set_object_terms&quot;. |  |
| 209 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_set_post_categories&quot;. |  |
| 212 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_set_post_tags&quot;. |  |
| 215 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_list_pluck&quot;. |  |
| 220 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_the_terms&quot;. |  |
| 223 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_category_by_slug&quot;. |  |
| 230 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_option&quot;. |  |
| 233 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;update_option&quot;. |  |
| 236 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_transient&quot;. |  |
| 239 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;set_transient&quot;. |  |
| 242 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;delete_transient&quot;. |  |
| 249 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;current_user_can&quot;. |  |
| 252 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;is_wp_error&quot;. |  |
| 255 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;rest_ensure_response&quot;. |  |
| 258 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;get_http_origin&quot;. |  |
| 261 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;plugins_url&quot;. |  |
| 266 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;rest_url&quot;. |  |
| 271 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;current_time&quot;. |  |
| 272 | 49 | ERROR | WordPress.DateTime.RestrictedFunctions.date_date | date() is affected by runtime timezone changes which can cause date/time to be incorrectly displayed. Use gmdate() instead. |  |
| 276 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_timezone&quot;. |  |
| 279 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_date&quot;. |  |
| 280 | 16 | ERROR | WordPress.DateTime.RestrictedFunctions.date_date | date() is affected by runtime timezone changes which can cause date/time to be incorrectly displayed. Use gmdate() instead. |  |
| 284 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_schedule_single_event&quot;. |  |
| 287 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;register_deactivation_hook&quot;. |  |
| 290 | 5 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;wp_clear_scheduled_hook&quot;. |  |

## `tests/bootstrap-unit.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Integration/ExampleIntegrationTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/mu-plugins/basic-auth.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |
| 20 | 17 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_SERVER[&#039;PHP_AUTH_USER&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 20 | 17 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_SERVER[&#039;PHP_AUTH_USER&#039;] |  |
| 21 | 17 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_SERVER[&#039;PHP_AUTH_PW&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 21 | 17 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_SERVER[&#039;PHP_AUTH_PW&#039;] |  |

## `tests/Unit/RestPermissionTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/UnpublishLinkTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/ScheduleTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/ValidateLinkTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/ExampleUnitTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/CreateBlogPostTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/BatchPublishTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/CategoriesTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/BuildPostContentTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/RestAddLinkTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Unit/PublishingTraitTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/helpers.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |
| 12 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound | Global constants defined by a theme/plugin should start with the theme/plugin prefix. Found: &quot;URL_EXAMPLE&quot;. |  |
| 13 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound | Global constants defined by a theme/plugin should start with the theme/plugin prefix. Found: &quot;TITLE_MY_LINK&quot;. |  |
| 18 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;makeRequest&quot;. |  |
| 29 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;makePost&quot;. |  |

## `pest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `.distignore`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | hidden_files | Hidden files are not permitted. |  |

## `.gitignore`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | hidden_files | Hidden files are not permitted. |  |

## `.claude`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | ai_instruction_directory | AI instruction directory ".claude" detected. These directories should not be included in production plugins. |  |

## `QWEN.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "QWEN.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

## `QUICKSTART.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "QUICKSTART.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

## `DEPLOYMENT.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "DEPLOYMENT.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

## `linkblog-linkblog-php-20260414-213639.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "linkblog-linkblog-php-20260414-213639.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

## `CLAUDE.local.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "CLAUDE.local.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

## `linkblog.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | textdomain_mismatch | The "Text Domain" header in the plugin file does not match the slug. Found "linkblog", expected "LinkBlog". | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `docs/plans/single-event-scheduling.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 35 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;update_custom_post_schedule&quot;. |  |
| 62 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.DirectQuery | Use of a direct database call is discouraged. |  |
| 62 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.NoCaching | Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete(). |  |
| 85 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;my_plugin_execute_scheduled_post&quot;. |  |
| 91 | 12 | WARNING | WordPress.DB.DirectDatabaseQuery.DirectQuery | Use of a direct database call is discouraged. |  |
| 91 | 12 | WARNING | WordPress.DB.DirectDatabaseQuery.NoCaching | Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete(). |  |
| 91 | 19 | WARNING | PluginCheck.Security.DirectDB.UnescapedDBParameter | Unescaped parameter $table used in $wpdb-&gt;get_row()\n$table assigned unsafely at line 88. |  |
| 93 | 13 | WARNING | WordPress.DB.PreparedSQL.InterpolatedNotPrepared | Use placeholders and $wpdb-&gt;prepare(); found interpolated variable {$table} at &quot;SELECT * FROM {$table} WHERE id = %d&quot; |  |
| 121 | 9 | WARNING | WordPress.PHP.DevelopmentFunctions.error_log_error_log | error_log() found. Debug code should not normally be used in production. |  |
| 130 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.DirectQuery | Use of a direct database call is discouraged. |  |
| 130 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.NoCaching | Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete(). |  |
| 155 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;my_plugin_handle_save&quot;. |  |
| 181 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;my_plugin_cleanup_schedule&quot;. |  |
| 194 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.DirectQuery | Use of a direct database call is discouraged. |  |
| 194 | 5 | WARNING | WordPress.DB.DirectDatabaseQuery.NoCaching | Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete(). |  |
| 212 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;my_plugin_deactivate&quot;. |  |
| 221 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;_my_plugin_clear_all_scheduled&quot;. |  |
| 225 | 16 | WARNING | WordPress.DB.DirectDatabaseQuery.DirectQuery | Use of a direct database call is discouraged. |  |
| 225 | 16 | WARNING | WordPress.DB.DirectDatabaseQuery.NoCaching | Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete(). |  |
| 225 | 23 | WARNING | PluginCheck.Security.DirectDB.UnescapedDBParameter | Unescaped parameter $table used in $wpdb-&gt;get_col()\n$table assigned unsafely at line 224. |  |
| 225 | 31 | WARNING | WordPress.DB.PreparedSQL.InterpolatedNotPrepared | Use placeholders and $wpdb-&gt;prepare(); found interpolated variable {$table} at &quot;SELECT id FROM {$table} WHERE status = &#039;pending&#039;&quot; |  |

## `src/php/traits/Admin/AddLink.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 121 | 20 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |

## `src/php/traits/RestApi.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 240 | 13 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_SERVER[&#039;REQUEST_METHOD&#039;]. Check that the array index exists before using it. |  |

## `src/php/traits/Admin/Dashboard.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 427 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_key | Detected usage of meta_key, possible slow query. |  |
