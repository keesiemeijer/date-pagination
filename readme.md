# Date Pagination #

Paginate your posts by year, month or day.

## Description ##

This WordPress plugin is aimed at developers and provides an easy way to paginate posts by year, month or day in theme templates. It uses the native WordPress [pagination functions](http://codex.wordpress.org/Pagination#Function_Reference) for display of the pagination links.

Use the new date_pagination_type query argument with a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)) or with the [pre_get_posts](http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts) action, and this plugin will do the rest. Use three new functions in your (child) theme template files to get the current, next and previous date. 

For more information on how to use this plugin with your theme template files visit [the documentation](https://keesiemeijer.wordpress.com/date-pagination/)

## Installation ##

* Unzip the <code>date-pagination.zip</code> folder.
* Upload the <code>date-pagination</code> folder to your <code>/wp-content/plugins</code> directory.
* Activate *date-pagination* through the 'Plugins' menu in WordPress.
* That's it, now you are ready to use the plugin in your theme template files.
