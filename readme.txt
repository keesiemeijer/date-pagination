=== Date Pagination ===
Contributors: keesiemeijer
Tags: pagination,paginate,date,yearly,monthly,daily 
Requires at least: 4.5
Tested up to: 5.5
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Paginate your posts by year, month or day.

== Description ==

This plugin provides an easy way to paginate posts by year, month or day in your theme's template files. Use the native [WordPress pagination functions](https://keesiemeijer.wordpress.com/date-pagination/functions/#wp-pagination-functions) to display the pagination links.

It's likely your theme is already using these functions for normal pagination. If not, see if it works with your theme's pagination or add them yourself.

To tell WordPress a page should be paginated by dates set the `date_pagination_type` query argument to `yearly`, `monthly` or `daily` for a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)), or in the [pre_get_posts](https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts) action.

See [the plugin documentation](https://keesiemeijer.wordpress.com/date-pagination/) for examples and more information on how to use this plugin in your theme.

Follow this plugin on [GitHub](https://github.com/keesiemeijer/date-pagination)

== Installation ==

Follow these instructions to install the plugin.

1. In your WordPress admin panel, go to Plugins > New Plugin, search for "date pagination" and click "Install now".
2. Alternatively, download the plugin and upload the contents of custom-post-type-date-archives.zip to your plugins directory, which usually is /wp-content/plugins/.
3. Activate the plugin.
4. That's it, now you are ready to use the plugin in your theme template files.

== Changelog ==
= 2.1.0 =
* Fix bug for manually setting the paged variable. Props: @hlaporthein

= 2.0.0
* Initial commit

== Upgrade Notice ==
= 2.1.0 =
This upgrade fixes a minor bug.