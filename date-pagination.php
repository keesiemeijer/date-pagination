<?php
/*
Plugin Name: Date Pagination
Version: 1.0
Plugin URI: http://keesiemeijer.wordpress.com/date-pagination
Description: This plugin lets WordPress theme developers paginate posts by year, month or day in theme template files.
Author: keesiemijer
Author URI:
License: GPL v2
*/

// load functions on the front end.
if ( !is_admin() ) {

	add_filter( 'query_vars', 'km_dp_date_pagination_query_var' );

	/**
	 * Adds query var 'date_pagination_type' to the public query vars
	 *
	 * @since 0.1
	 *
	 * @param array   $query_vars
	 * @return array
	 */
	function km_dp_date_pagination_query_var( $query_vars ) {

		$query_vars[] = 'date_pagination_type';

		return $query_vars;
	}


	add_action( 'pre_get_posts', 'km_dp_date_pagination_pre_get_posts', 99 );

	/**
	 * Resets date query vars if 'date_pagination_type' query var is used.
	 *
	 * @since 0.1
	 *
	 * @param object  $query Query object.
	 * @return void
	 */
	function km_dp_date_pagination_pre_get_posts( $query ) {

		$type = $query->get( 'date_pagination_type' );
		
		if ( is_date() || !km_dp_date_pagination_is_valid_type( $type ) ) {
			return;
		}

		$reset_query_vars =  array(
			'second' , 'minute', 'hour',
			'day', 'monthnum', 'year',
			'w', 'm',
		);

		// reset date query vars
		foreach ( $reset_query_vars as $var ) {
			$query->set( $var, '' );
		}

		// disable paging just for good measure
		$query->set( 'nopaging', true );
	}



add_filter( 'posts_clauses', 'km_dp_date_pagination_posts_clauses', 99, 2 );

/**
 * Sets the sql clauses if query var 'date_pagination_type' is used.
 *
 * @since 0.1
 *
 * @param array   $clauses Post clauses.
 * @param object  $query   Query Object.
 * @return array Post clauses
 */
function km_dp_date_pagination_posts_clauses( $clauses, $query ) {
	global $wpdb;

	$type = $query->get( 'date_pagination_type' );
		
	if ( is_date() || !km_dp_date_pagination_is_valid_type( $type ) ) {
		return $clauses;
	}

	$select = "YEAR($wpdb->posts.post_date) AS `year`";
	$groupby = "YEAR($wpdb->posts.post_date)";

	if ( $type == 'monthly' || $type == 'daily' ) {
		$select .= ", MONTH($wpdb->posts.post_date) AS `month`";
		$groupby .= ", MONTH($wpdb->posts.post_date)";
	}

	if ( $type == 'daily' ) {
		$select .= ", DAYOFMONTH($wpdb->posts.post_date) AS `dayofmonth`";
		$groupby .= ", DAYOFMONTH($wpdb->posts.post_date)";
	}

	$date_query = "SELECT $select, count($wpdb->posts.ID) as posts FROM $wpdb->posts " . $clauses['join'] . " WHERE 1=1". $clauses['where'] ." GROUP BY $groupby ORDER BY " . $clauses['orderby'];

	// get all (paginated) date pages for this query
	$dates = $wpdb->get_results( $date_query );

	if ( $dates ) {
		// count of dates gets set as max_num_pages for the query
		$page_count = count( $dates );

		// add new max_num_pages to the query object
		$query->set( 'date_pagination_max_num_pages', $page_count );

		// set max_num_pages to date pagination max_num_pages
		add_filter( 'the_posts', 'km_dp_date_pagination_max_num_pages', 99, 2 );

		// current page
		if ( get_query_var( 'paged' ) ) { 
			$paged = get_query_var( 'paged' ); 
		} elseif ( get_query_var( 'page' ) ) { 
			$paged = get_query_var( 'page' ); 
		} else { 
			$paged = 1; 
		}

		// don't return posts if a paginated page is over the max_num_pages
		if ( $paged > $page_count ) {
			$clauses['where'] .= " AND 1=0";
		}

		// get the date for the current (paginated) page.
		$start = (int) $paged-1;
		$date = array_slice( (array) $dates, $start, 1 );

		// add current object to query
		$query->set( 'date_pagination_current', $date );

		// add next object to query
		$next = array_slice( (array) $dates, $start+1, 1 );
		$query->set( 'date_pagination_next', $next );

		// add previous object to query
		if ( $start > 0 ) {
			$prev = array_slice( (array) $dates, $start-1, 1 );
			$query->set( 'date_pagination_prev', $prev );
		} else {
			$query->set( 'date_pagination_prev', array() );
		}

		if ( $date ) {

			// no pagination needed for this query
			$clauses['limits'] = '';

			// year sql
			if ( isset( $date[0]->year ) ) {
				$clauses['where'] .= " AND YEAR($wpdb->posts.post_date)='" . $date[0]->year . "'";
			}

			// month sql
			if ( isset( $date[0]->month ) ) {
				$clauses['where'] .= " AND MONTH($wpdb->posts.post_date)='" . $date[0]->month . "'";
			}

			// day sql
			if ( isset( $date[0]->dayofmonth ) ) {
				$clauses['where'] .= " AND DAYOFMONTH($wpdb->posts.post_date)='" . $date[0]->dayofmonth . "'";
			}
		}
	}

	return $clauses;
}


/**
 * Sets the max_num_pages.
 *
 * @since 0.1
 *
 * @param object  $query Query Object.
 * @return array Array with post objects
 */
function km_dp_date_pagination_max_num_pages( $posts, $query ) {
	$max_num_pages = $query->get( 'date_pagination_max_num_pages' );

	if ( !empty( $max_num_pages ) ) {
		$query->max_num_pages = $max_num_pages;
	}

	return $posts;
}


/**
 * Validates date types
 *
 * @since 0.1
 *
 * @param string  $type
 * @return bool True if type is valid.
 */
function km_dp_date_pagination_is_valid_type( $type ) {

	if ( in_array( (string) $type, array( 'yearly', 'monthly', 'daily' ) ) ) {
		return true;
	}

	return false;
}


/**
 * Returns next date label from query.
 *
 * @since 0.1
 *
 * @param object  $query  WP_Query Object.
 * @param string  $format Date format.
 * @return string Formatted date or empty string.
 */
function km_dp_get_next_date_label( $format = '', $query = 0  ) {
	return km_dp_date_pagination_get_date( $format, $query );
}


/**
 * Returns previous date label from query.
 *
 * @since 0.1
 *
 * @param object  $query  WP_Query Object.
 * @param string  $format Date format.
 * @return string Formatted date or empty string.
 */
function km_dp_get_previous_date_label( $format = '', $query = 0 ) {
	return km_dp_date_pagination_get_date( $format, $query, true );
}


/**
 * Returns current date label from query.
 *
 * @since 0.1
 *
 * @param object  $query  WP_Query Object.
 * @param string  $format Date format.
 * @return string Formatted date or empty string.
 */
function km_dp_get_current_date_label( $format = '', $query = 0 ) {
	return km_dp_date_pagination_get_date( $format, $query, 'current' );
}


/**
 * Returns a formatted date from a query.
 *
 * @since 0.1
 *
 * @param object  $query     WP_Query Object.
 * @param string  $format    Date format.
 * @param string  $previous. Previous or next date.
 * @return string Formatted date or empty string.
 */
function km_dp_date_pagination_get_date( $format = '', $query = 0, $previous = 0 ) {

	$date = '';

	if ( is_date() ) {
		return $date;
	}

	if ( !$query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( !is_a( (object) $query, 'WP_Query' ) ) {
		return $date;
	}

	$type = $query->get( 'date_pagination_type' );

	if ( !empty( $type ) && km_dp_date_pagination_is_valid_type( $type ) ) {

		$next_prev = 'date_pagination_next';

		if ( $previous ) {
			$next_prev = 'date_pagination_prev';

			if ( $previous === 'current' ) {
				$next_prev = 'date_pagination_current';
			}
		}

		$date_obj = $query->get( $next_prev );

		if ( !empty( $date_obj ) ) {

			$year = ( isset( $date_obj[0]->year ) ) ? absint( $date_obj[0]->year ): 0;
			$month = ( isset( $date_obj[0]->month ) ) ? absint( $date_obj[0]->month ): 0;
			$day = ( isset( $date_obj[0]->dayofmonth ) ) ? absint( $date_obj[0]->dayofmonth ): 0;

			if ( ( $type == 'yearly' ) && $year ) {
				$date = $year . '-01-01';
				$format = ( '' != $format ) ? (string) $format : 'Y';
			}

			if ( ( $type == 'monthly' ) && $year && $month ) {
				$date = $year . '-' . zeroise( $month, 2 ) . '-01';
				$format = ( '' != $format ) ? (string) $format : 'F Y';
			}

			if ( ( $type == 'daily' )  && $year && $month && $day ) {
				$date = $year . '-' . zeroise( $month, 2 ) . zeroise( $day, 2 );
				$format = ( '' != $format ) ? (string) $format : ' F j, Y';
			}

		}
	}

	return ( '' != $date ) ? mysql2date( $format, $date . ' 00:00:00' ) : '';
}


} // if( !is_admin() )
