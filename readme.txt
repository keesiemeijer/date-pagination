=== Date Pagination ===
Contributors: keesiemeijer
Tags: pagination, paginate, yearly pagination, monthly pagination, daily pagination
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Paginate your posts by year, month or day.

== Description ==

This plugin provides an easy way to paginate posts by year, month or day in your theme’s template files. Use the native [WordPress pagination function](https://developer.wordpress.org/themes/functionality/pagination/#methods-for-displaying-pagination-links) for display of the pagination links. 

It’s likely your theme is already using these functions for normal pagination. If not you’ll have to add them yourself to display pagination by dates.

To tell WordPress a page should be paginated by dates use the `date_pagination_type` query argument with a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)), or with the [pre_get_posts](https://developer.wordpress.org/reference/hooks/pre_get_posts/) action, and this plugin will do the rest.

For more information on how to use this plugin, and how to incorporate this plugin with your theme, visit [the documentation](https://keesiemeijer.wordpress.com/date-pagination/).

== Installation ==

Follow these instructions to install the plugin.

* Unzip the <code>date-pagination.zip</code> folder.
* Upload the <code>date-pagination</code> folder to your <code>/wp-content/plugins</code> directory.
* Activate *date-pagination* through the 'Plugins' menu in WordPress.
* That's it, now you are ready to use the plugin in your theme template files.
