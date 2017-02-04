<?php
/*
Plugin Name: Date Pagination
Version: 2.0.0
Plugin URI: http://keesiemeijer.wordpress.com/date-pagination
Description: WordPress theme template functions to paginate posts by year, month or day.
Author: keesiemijer
Author URI:
License: GPL v2

Date Pagination
Copyright 2017  Kees Meijer  (email : keesie.meijer@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version. You may NOT assume that you can use any other version of the GPL.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! is_admin() ) {
	// Date pagination functions
	require_once plugin_dir_path( __FILE__ ) . 'src/functions.php';

	// Pagination functions copied from WordPress core.
	require_once plugin_dir_path( __FILE__ ) . 'src/wp-functions.php';

	// WP_Query filters.
	require_once plugin_dir_path( __FILE__ ) . 'src/query.php';
} 