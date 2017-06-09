# Date Pagination #

Paginate your posts by year, month or day.

[![Build Status](https://travis-ci.org/keesiemeijer/date-pagination.svg?branch=master)](http://travis-ci.org/keesiemeijer/date-pagination)

Version:           2.1.0-alpha  
Requires at least: 4.0  
Tested up to:      4.8  

This is the development repository for the WordPress plugin [Date Pagination](https://wordpress.org/plugins/date-pagination/).

This plugin provides an easy way to paginate posts by year, month or day in your theme’s template files. Use the native [WordPress pagination functions](https://keesiemeijer.wordpress.com/date-pagination/functions/#wp-pagination-functions) to display the pagination links.

It’s likely your theme is already using these functions for normal pagination. If not, see if it works with your theme’s pagination or add them yourself.

To tell WordPress a page should be paginated by dates set the `date_pagination_type` query argument to `yearly`, `monthly` or `daily` for a custom query ([WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query)), or in the [pre_get_posts](https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts) action.

See [the plugin documentation](https://keesiemeijer.wordpress.com/date-pagination/) for examples and more information on how to use this plugin in your theme.

***Note***: This plugin intentionally doesn't do pagination for date archives. See [this plugin](https://github.com/keesiemeijer/date-archives-pagination) to do just that.

### Development ###
The `master` branch is where you'll find the most recent, stable release.
The `develop` branch is the current working branch for development. Both branches are required to pass all unit tests. Any pull requests are first merged with the `develop` branch before being merged into the `master` branch. See [Pull Requests](https://github.com/keesiemeijer/date-pagination/tree/master#pull-requests)

## Installation ##

* Clone the GitHub repository: `git clone https://github.com/keesiemeijer/date-pagination.git`
* Or download it directly as a ZIP file: [https://github.com/keesiemeijer/date-pagination/archive/master.zip](https://github.com/keesiemeijer/date-pagination/archive/master.zip)

Add the plugin to the `plugins` folder and activate it.

## Pull Requests ##
When starting work on a new feature, branch off from the `develop` branch.
```bash
# clone the repository
git clone https://github.com/keesiemeijer/date-pagination.git

# cd into the date-pagination directory
cd date-pagination

# switch to the develop branch
git checkout develop

# create new branch newfeature and switch to it
git checkout -b newfeature develop
```

## Creating a new build ##
To compile the plugin without all the development files (as in the [WP repository](https://plugins.trac.wordpress.org/browser/date-pagination/trunk)) use the following commands:
```bash
# Go to the master branch
git checkout master

# Install Grunt tasks
npm install

# Build the production plugin
grunt build
```
The plugin will be compiled in the `build` directory.

## Bugs ##
If you find an issue, let us know [here](https://github.com/keesiemeijer/date-pagination/issues?state=open)!

## Support ##
This is a developer's portal for Date Pagination and should _not_ be used for support. Please visit the [support forums](https://wordpress.org/support/plugin/date-pagination).

## Contributions ##

There are various ways you can contribute:

1. Raise an [Issue](https://github.com/keesiemeijer/date-pagination/issues) on GitHub
2. Send us a Pull Request with your bug fixes and/or new features
4. Provide feedback and suggestions on [enhancements](https://github.com/keesiemeijer/date-pagination/issues?direction=desc&labels=Enhancement&page=1&sort=created&state=open)


