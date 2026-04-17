# Plugin Check Report

**Plugin:** LinkBlog
**Generated at:** 2026-04-14 21:36:39


## `src/php/traits/Queries.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 16 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 31 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 68 | 21 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_tax_query | Detected usage of tax_query, possible slow query. |  |
| 89 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_tax_query | Detected usage of tax_query, possible slow query. |  |
| 98 | 48 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 111 | 70 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 121 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 132 | 63 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/MetaBoxes.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 10 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 23 | 50 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 23 | 61 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 31 | 70 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_url_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 31 | 70 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_url_nonce&#039;] |  |
| 47 | 32 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_url&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |

## `src/php/traits/Scheduler.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |
| 36 | 30 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 36 | 46 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 107 | 19 | ERROR | WordPress.DateTime.RestrictedFunctions.date_date | date() is affected by runtime timezone changes which can cause date/time to be incorrectly displayed. Use gmdate() instead. |  |
| 113 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |

## `src/php/traits/Admin/AddLink.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 17 | 23 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 17 | 42 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 37 | 63 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 37 | 75 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 40 | 135 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 40 | 172 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 40 | 172 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_title&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 40 | 172 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_title&#039;] |  |
| 46 | 61 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 46 | 71 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 49 | 130 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 49 | 165 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 49 | 165 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_url&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 49 | 165 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_url&#039;] |  |
| 55 | 65 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 55 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 59 | 46 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 59 | 76 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 59 | 76 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_content&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 59 | 76 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_content&#039;] |  |
| 71 | 41 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 71 | 58 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 78 | 166 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 78 | 229 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 84 | 42 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 84 | 123 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 91 | 62 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 91 | 73 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 94 | 133 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 94 | 169 | WARNING | WordPress.Security.NonceVerification.Missing | Processing form data without nonce verification. |  |
| 94 | 169 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_tags&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 94 | 169 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_tags&#039;] |  |
| 94 | 223 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 94 | 255 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 95 | 58 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 95 | 124 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 101 | 124 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 101 | 139 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 102 | 41 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'admin_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 102 | 110 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 102 | 123 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 110 | 71 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_add_nonce&#039;]. Check that the array index exists before using it. |  |
| 110 | 71 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_add_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 110 | 71 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_add_nonce&#039;] |  |
| 114 | 43 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_title&#039;]. Check that the array index exists before using it. |  |
| 114 | 43 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_title&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 115 | 35 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_url&#039;]. Check that the array index exists before using it. |  |
| 115 | 35 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_url&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 116 | 36 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_content&#039;]. Check that the array index exists before using it. |  |
| 116 | 36 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_content&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 118 | 43 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_tags&#039;]. Check that the array index exists before using it. |  |
| 118 | 43 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_tags&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 121 | 50 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 132 | 51 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 146 | 48 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/Admin/Menu.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 9 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 10 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 20 | 29 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 21 | 29 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 29 | 30 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 30 | 29 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 38 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 39 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 47 | 30 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 48 | 30 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 55 | 24 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 56 | 24 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 63 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 64 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 72 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 73 | 28 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 83 | 25 | WARNING | WordPress.Security.NonceVerification.Recommended | Processing form data without nonce verification. |  |
| 83 | 25 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_GET[&#039;taxonomy&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 83 | 25 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_GET[&#039;taxonomy&#039;] |  |
| 94 | 25 | WARNING | WordPress.Security.NonceVerification.Recommended | Processing form data without nonce verification. |  |
| 94 | 25 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_GET[&#039;taxonomy&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 94 | 25 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_GET[&#039;taxonomy&#039;] |  |
| 107 | 75 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_settings_nonce&#039;]. Check that the array index exists before using it. |  |
| 107 | 75 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_settings_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 107 | 75 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_settings_nonce&#039;] |  |
| 110 | 76 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 110 | 118 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 117 | 23 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 117 | 47 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 120 | 27 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 120 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 121 | 26 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 121 | 119 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 125 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 125 | 51 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 141 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 141 | 84 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 143 | 35 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 143 | 55 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 151 | 35 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 151 | 50 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 167 | 35 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 167 | 92 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 175 | 47 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 175 | 74 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 175 | 88 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 175 | 111 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 179 | 35 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 179 | 100 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 186 | 27 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 186 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 188 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 188 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 189 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 189 | 81 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 190 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 190 | 84 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 191 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 191 | 48 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 192 | 31 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 192 | 87 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 230 | 23 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 230 | 52 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 277 | 36 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/Admin/LinksPage.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 19 | 49 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 19 | 76 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 20 | 33 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'admin_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 20 | 111 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 20 | 125 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 32 | 26 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 32 | 69 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 41 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 41 | 79 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 42 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 42 | 77 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 43 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 43 | 80 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 44 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 44 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 45 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 45 | 78 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 46 | 67 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 46 | 81 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 71 | 107 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 71 | 123 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 73 | 103 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 73 | 115 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 75 | 107 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 75 | 125 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 88 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 88 | 207 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 88 | 221 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 89 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 89 | 203 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 89 | 223 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 91 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_permalink'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 91 | 130 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 91 | 146 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 92 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 92 | 236 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 92 | 288 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 92 | 314 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 92 | 330 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 94 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 94 | 207 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 94 | 221 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 95 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_edit_post_link'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 95 | 135 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 95 | 152 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 96 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 96 | 236 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 96 | 288 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 96 | 314 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 96 | 330 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 98 | 65 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_edit_post_link'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 98 | 106 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 98 | 117 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 99 | 65 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'wp_nonce_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 99 | 221 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 99 | 270 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 99 | 296 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 99 | 309 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 120 | 20 | WARNING | WordPress.Security.NonceVerification.Recommended | Processing form data without nonce verification. |  |
| 120 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_GET[&#039;action&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 120 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_GET[&#039;action&#039;] |  |
| 121 | 20 | WARNING | WordPress.Security.NonceVerification.Recommended | Processing form data without nonce verification. |  |
| 121 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_GET[&#039;link_id&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 121 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_GET[&#039;link_id&#039;] |  |
| 122 | 20 | WARNING | WordPress.Security.NonceVerification.Recommended | Processing form data without nonce verification. |  |
| 122 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_GET[&#039;_wpnonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 122 | 20 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_GET[&#039;_wpnonce&#039;] |  |
| 127 | 139 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 134 | 145 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 147 | 57 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/Admin/Dashboard.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 17 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 35 | 113 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 35 | 125 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 39 | 113 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 39 | 129 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 43 | 113 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 43 | 131 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 49 | 87 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 49 | 112 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 70 | 33 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'admin_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 71 | 23 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 71 | 44 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 82 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 91 | 80 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_batch_nonce&#039;]. Check that the array index exists before using it. |  |
| 91 | 80 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_batch_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 91 | 80 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_batch_nonce&#039;] |  |
| 99 | 81 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_roundup_nonce&#039;]. Check that the array index exists before using it. |  |
| 99 | 81 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_roundup_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 99 | 81 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_roundup_nonce&#039;] |  |
| 102 | 47 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;roundup_title&#039;]. Check that the array index exists before using it. |  |
| 102 | 47 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;roundup_title&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 108 | 76 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;linkblog_quick_nonce&#039;]. Check that the array index exists before using it. |  |
| 108 | 76 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;linkblog_quick_nonce&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 108 | 76 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotSanitized | Detected usage of a non-sanitized input variable: $_POST[&#039;linkblog_quick_nonce&#039;] |  |
| 111 | 39 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;quick_title&#039;]. Check that the array index exists before using it. |  |
| 111 | 39 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;quick_title&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 112 | 31 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_POST[&#039;quick_url&#039;]. Check that the array index exists before using it. |  |
| 112 | 31 | WARNING | WordPress.Security.ValidatedSanitizedInput.MissingUnslash | $_POST[&#039;quick_url&#039;] not unslashed before sanitization. Use wp_unslash() or similar |  |
| 130 | 70 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 130 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 132 | 25 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 132 | 25 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 132 | 29 | ERROR | WordPress.WP.I18n.UnorderedPlaceholdersText | Multiple placeholders in translatable strings should be ordered. Expected "%1$d, %2$s", but got "%d, %s" in 'Successfully processed %d link(s). %s'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#variables) |
| 132 | 70 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 132 | 84 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$batch_result['success']'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 132 | 110 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$failed_msg'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 142 | 37 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_permalink'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 142 | 106 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 142 | 123 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 153 | 41 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 153 | 73 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 157 | 80 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 157 | 123 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 168 | 107 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 172 | 62 | ERROR | WordPress.WP.AlternativeFunctions.parse_url_parse_url | parse_url() is discouraged because of inconsistency in the output across PHP versions; use wp_parse_url() instead. |  |
| 179 | 91 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_the_time'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 180 | 103 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_the_time'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 187 | 45 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'admin_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 188 | 35 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 188 | 57 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 201 | 41 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 201 | 67 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 205 | 80 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 205 | 111 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 220 | 97 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 220 | 114 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 222 | 93 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 222 | 106 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 226 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_edit_post_link'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 226 | 112 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'get_permalink'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 227 | 64 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 227 | 82 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 227 | 97 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 227 | 114 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 235 | 58 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found 'mysql2date'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 251 | 41 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 251 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 255 | 38 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 255 | 38 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '__'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 255 | 112 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 255 | 126 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$unpublished_count'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 259 | 70 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 259 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 261 | 70 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 261 | 96 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 261 | 110 | ERROR | WordPress.DateTime.RestrictedFunctions.date_date | date() is affected by runtime timezone changes which can cause date/time to be incorrectly displayed. Use gmdate() instead. |  |
| 265 | 118 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 265 | 133 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 267 | 173 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 267 | 194 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 271 | 53 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 271 | 96 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 282 | 41 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 282 | 58 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 286 | 72 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 286 | 104 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 291 | 64 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 291 | 77 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 293 | 80 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 296 | 62 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 296 | 73 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 301 | 109 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 301 | 125 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 312 | 48 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 313 | 44 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 314 | 47 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 343 | 57 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$rest_url'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 346 | 62 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$nonce'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 368 | 79 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$lbl_delete'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 369 | 69 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$lbl_yes'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 370 | 64 | ERROR | WordPress.Security.EscapeOutput.OutputNotEscaped | All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '$lbl_cancel'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-functions) |
| 393 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 405 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_key | Detected usage of meta_key, possible slow query. |  |
| 406 | 13 | WARNING | WordPress.DB.SlowDBQuery.slow_db_query_meta_query | Detected usage of meta_query, possible slow query. |  |
| 413 | 23 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 413 | 39 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 422 | 55 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 422 | 74 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 427 | 55 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 427 | 73 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 432 | 55 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 432 | 72 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 437 | 55 | ERROR | WordPress.Security.EscapeOutput.UnsafePrintingFunction | All output should be run through an escaping function (like esc_html_e() or esc_attr_e()), found '_e'. | [Docs](https://developer.wordpress.org/apis/security/escaping/#escaping-with-localization) |
| 437 | 74 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/Publishing.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 9 | 126 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 13 | 98 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 16 | 116 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 20 | 119 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 83 | 64 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 100 | 59 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 101 | 54 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/PostType.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 9 | 78 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 10 | 78 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 11 | 55 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 12 | 51 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 13 | 60 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 14 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 15 | 59 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 16 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 17 | 59 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 18 | 54 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 19 | 55 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 20 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 21 | 58 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 22 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 23 | 57 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 24 | 58 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 25 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 26 | 65 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 27 | 61 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 28 | 65 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 29 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 30 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 31 | 63 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 32 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 33 | 57 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 34 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 35 | 64 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 39 | 51 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 40 | 71 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 66 | 92 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 67 | 91 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 68 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 69 | 66 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 70 | 67 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 71 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 72 | 69 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 73 | 68 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 74 | 65 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 75 | 67 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 76 | 65 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 77 | 83 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 78 | 76 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 79 | 77 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 80 | 70 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 81 | 69 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 82 | 61 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 83 | 65 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 84 | 67 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 85 | 78 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 103 | 86 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 104 | 86 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 105 | 56 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 106 | 60 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 107 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 108 | 63 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 109 | 64 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 110 | 63 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 111 | 60 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 112 | 62 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 113 | 60 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 114 | 77 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 115 | 70 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 116 | 77 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 117 | 64 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 118 | 63 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 119 | 61 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 120 | 59 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 121 | 61 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 122 | 72 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `src/php/traits/RestApi.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 102 | 78 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 135 | 76 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 149 | 80 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 162 | 57 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 206 | 88 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 240 | 13 | WARNING | WordPress.Security.ValidatedSanitizedInput.InputNotValidated | Detected usage of a possibly undefined superglobal array index: $_SERVER[&#039;REQUEST_METHOD&#039;]. Check that the array index exists before using it. |  |

## `src/php/traits/Batch.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 16 | 64 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 29 | 21 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 29 | 24 | ERROR | WordPress.WP.I18n.UnorderedPlaceholdersText | Multiple placeholders in translatable strings should be ordered. Expected "%1$s, %2$s", but got "%s, %s" in 'Failed to publish "%s": %s'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#variables) |
| 29 | 54 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 48 | 35 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 48 | 60 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 48 | 73 | ERROR | WordPress.DateTime.RestrictedFunctions.date_date | date() is affected by runtime timezone changes which can cause date/time to be incorrectly displayed. Use gmdate() instead. |  |
| 54 | 108 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 62 | 126 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 65 | 102 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 79 | 112 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 90 | 37 | ERROR | WordPress.WP.I18n.MissingTranslatorsComment | A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#descriptions) |
| 90 | 94 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 147 | 46 | ERROR | WordPress.WP.I18n.TextDomainMismatch | Mismatched text domain. Expected 'LinkBlog' but got 'linkblog'. | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |

## `.wp-env.json`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | hidden_files | Hidden files are not permitted. |  |

## `.scannerwork/.sonar_lock`

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

## `linkblog.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | plugin_header_invalid_plugin_uri_domain | The "Plugin URI" header in the plugin file is not valid. Discouraged domain "example.com" found. This is the homepage of the plugin, which should be a unique URL, preferably on your own website. | [Docs](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/#header-fields) |
| 0 | 0 | ERROR | plugin_header_invalid_author_uri_domain | The "Author URI" header in the plugin file is not valid. Discouraged domain "example.com" found. This is the author's website or profile on another website. | [Docs](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/#header-fields) |
| 0 | 0 | WARNING | textdomain_mismatch | The "Text Domain" header in the plugin file does not match the slug. Found "linkblog", expected "LinkBlog". | [Docs](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/) |
| 23 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound | Global variables defined by a theme/plugin should start with the theme/plugin prefix. Found: &quot;$_linkblog_constants&quot;. |  |

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

## `readme.txt`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | no_plugin_readme | The plugin readme.txt does not exist. |  |

## `tests/bootstrap-unit.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `tests/Integration/ExampleIntegrationTest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

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
| 15 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;makeRequest&quot;. |  |
| 26 | 1 | WARNING | WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound | Functions declared in the global namespace by a theme/plugin should start with the theme/plugin prefix. Found: &quot;makePost&quot;. |  |

## `pest.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

## `src/php/class-linkblog.php`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | ERROR | missing_direct_file_access_protection | PHP file should prevent direct access. Add a check like: if ( ! defined( 'ABSPATH' ) ) exit; | [Docs](https://developer.wordpress.org/plugins/wordpress-org/common-issues/#direct-file-access) |

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

## `CLAUDE.local.md`

| Line | Column | Type | Code | Message | Docs |
| --- | --- | --- | --- | --- | --- |
| 0 | 0 | WARNING | unexpected_markdown_file | Unexpected markdown file "CLAUDE.local.md" detected in plugin root. Only specific markdown files are expected in production plugins. |  |

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
