# Date Pagination #

Paginate your posts by year, month or day.

[![Build Status](https://travis-ci.org/keesiemeijer/date-pagination.svg?branch=master)](http://travis-ci.org/keesiemeijer/date-pagination)

Version:           2.0.0  
Requires at least: 4.0  
Tested up to:      4.7  

## Description ##

This plugin provides an easy way to paginate posts by year, month or day in your theme’s template files. Use the native [WordPress pagination functions](https://keesiemeijer.wordpress.com/date-pagination/functions/#wp-pagination-functions) to display the pagination links.

It’s likely your theme is already using these functions for normal pagination. If not, see if it works with your theme’s pagination or add them yourself.

To tell WordPress a page should be paginated by dates set the `date_pagination_type` query argument to `yearly`, `monthly` or `daily` for a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)), or in the [pre_get_posts](https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts) action.

See [the plugin documentation](https://keesiemeijer.wordpress.com/date-pagination/) for examples and more information on how to use this plugin in your theme.

***Note***: This plugin intentionally doesn't do pagination for date archives. See [this plugin](https://github.com/keesiemeijer/date-archives-pagination) to do just that.

## Installation ##

* Clone the GitHub repository: `git clone https://github.com/keesiemeijer/date-pagination.git`
* Or download it directly as a ZIP file: [https://github.com/keesiemeijer/date-pagination/archive/master.zip](https://github.com/keesiemeijer/related-posts-by-taxonomy/archive/master.zip)

* Unzip the <code>date-pagination.zip</code> folder.
* Upload the <code>date-pagination</code> folder to your <code>/wp-content/plugins</code> directory.
* Activate *date-pagination* through the 'Plugins' menu in WordPress.
* That's it, now you are ready to use the plugin in your theme to paginate by date.

For more information on how to use this plugin, and how to incorporate it with your theme visit [the documentation](https://keesiemeijer.wordpress.com/date-pagination/)
