=== Date Pagination ===
Contributors: keesiemeijer
Tags: pagination, paginate, yearly pagination, monthly pagination, daily pagination
Requires at least: 3.6
Tested up to: 3.6
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lets you paginate your posts by year, month or day.

== Description ==

This plugin is aimed at theme developers and provides an easy way to paginate posts by year, month or day.

Use the 'date_pagination_type' query argument with the '[pre_get_post](http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts)' action, or in a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)), and this plugin will do the rest. See the examples below. To link to the next and previous pages you can use WordPress [pagination functions](http://codex.wordpress.org/Pagination#Function_Reference) or a pagination plugin in your theme.

**Plugin support** for this plugin from the plugin author will be minimal as this is a plugin made for developers. 
<!-- Use the dedicated WordPress forum if you have a support request: -->
Bug reports, feature requests and other issues can be reported through the GitHub [issues system](https://github.com/keesiemeijer/date-pagination/issues).

[plugin documentation](http://keesiemeijer.wordpress.com/date-pagination/)

[github](https://github.com/keesiemeijer/date-pagination/)

= Example pre_get_posts =

`
add_action( 'pre_get_posts', 'yearly_paginated_home_query' );
function yearly_paginated_home_query( $query ) {

	// not a wp-admin page and the query is for the main query
	if ( !is_admin() && $query->is_main_query() ) {

		//  on the home page only
		if ( is_home() ) {
			$query->set('date_pagination_type', 'yearly'); // 'yearly', 'monthly', 'daily'

			// set other parameters here

		}
	}
}
`

= Example WP_Query =

`
<?php
// example args
$args = array(
	'date_pagination_type' => 'yearly', // 'yearly', 'monthly', 'daily'
	'cat' => 25,
);

// the custom query
$the_query = new WP_Query( $args );
?>

<!-- the loop -->
`

== Installation ==

* Unzip the <code>date-pagination.zip</code> folder.
* Upload the <code>date-pagination</code> folder to your <code>/wp-content/plugins</code> directory.
* Activate *date-pagination* through the 'Plugins' menu in WordPress.
* That's it, now you are ready to use the plugin in your theme template files.
