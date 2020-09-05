<?php
/**
 * Filters for WP_Query.
 *
 * @package Date_Pagination
 */

/** Add the 'date_pagination_type' query var */
add_filter( 'query_vars', 'km_dp_date_pagination_query_var' );

/**
 * Adds the query var 'date_pagination_type' to the public query vars
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

/** Reset date query vars */
add_action( 'pre_get_posts', 'km_dp_date_pagination_pre_get_posts', 99 );

/**
 * Resets date query vars if 'date_pagination_type' query var is used.
 *
 * @since 0.1
 *
 * @param WP_Query $query WP_Query object.
 * @return void
 */
function km_dp_date_pagination_pre_get_posts( $query ) {

	$type = $query->get( 'date_pagination_type' );
	if ( is_date() || ! km_dp_date_pagination_is_valid_type( $type ) ) {
		return;
	}

	/**
	 * Filter to reset WP_Query date query vars before a Date Pagination query.
	 *
	 * @since 2.0.0
	 *
	 * @param bool   $reset Whether to reset query vars. Default true.
	 * @param string $type  Date pagination type. Accepts 'yearly', 'monthly', 'daily'.
	 */
	$reset = apply_filters( 'date_pagination_reset_date_query_vars', true, $type );

	if ( ! $reset ) {
		return;
	}

	$query_vars =  array(
		'second' , 'minute', 'hour',
		'day', 'monthnum', 'year',
		'w', 'm',
	);

	// Reset date query vars.
	foreach ( $query_vars as $var ) {
		$query->set( $var, '' );
	}

	$query->set( 'date_query', false );

	// Disable paging just for good measure.
	// Limit clause is also reset by km_dp_date_pagination_posts_clauses().
	$query->set( 'nopaging', true );
}


add_filter( 'posts_clauses', 'km_dp_date_pagination_posts_clauses', 99, 2 );

/**
 * Sets the sql clauses if query var 'date_pagination_type' is used.
 *
 * @since 0.1
 *
 * @global $wpdb   Database object.
 *
 * @param array   $clauses Post clauses.
 * @param object  $query   Query Object.
 * @return array Post clauses
 */
function km_dp_date_pagination_posts_clauses( $clauses, $query ) {
	global $wpdb;

	$type = (string) $query->get( 'date_pagination_type' );
	if ( is_date() || ! km_dp_date_pagination_is_valid_type( $type ) ) {
		return $clauses;
	}

	$select = "YEAR($wpdb->posts.post_date) AS `year`";
	$groupby = "YEAR($wpdb->posts.post_date)";

	if ( 'monthly' === $type || 'daily' === $type ) {
		$select .= ", MONTH($wpdb->posts.post_date) AS `month`";
		$groupby .= ", MONTH($wpdb->posts.post_date)";
	}

	if ( 'daily' === $type ) {
		$select .= ", DAYOFMONTH($wpdb->posts.post_date) AS `dayofmonth`";
		$groupby .= ", DAYOFMONTH($wpdb->posts.post_date)";
	}

	$date_query = "SELECT $select, count($wpdb->posts.ID) as posts FROM $wpdb->posts " . $clauses['join'] . " WHERE 1=1" . $clauses['where'] . " GROUP BY $groupby ORDER BY " . $clauses['orderby'];

	// Get all date pages for this query.
	$dates = $wpdb->get_results( $date_query, ARRAY_A );
	if ( ! $dates ) {
		return $clauses;
	}

	$page_count = count( $dates );

	// Add Date Pagination max_num_pages to the query object.
	$query->set( 'date_pagination_max_num_pages', $page_count );

	/** Replace max_num_pages with date_pagination_max_num_pages */
	add_filter( 'the_posts', 'km_dp_date_pagination_max_num_pages', 99, 2 );

	$all_dates = array();
	foreach ( $dates as $d ) {
		$all_dates[] = km_dp_date_pagination_convert_date( $d );
	}

	// Add all dates to the query object.
	$query->set( 'date_pagination_dates', $all_dates );

    // Get the current page.
    if ( !empty($query->query['paged']) && ( $query->query['paged'] > 0 ) ) {
        $paged = $query->query['paged'];
    } else {
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } elseif ( get_query_var( 'page' ) ) {
            $paged = get_query_var( 'page' );
        } else {
            $paged = 1;
        }
    }

	// Don't return posts if a paginated page is over the max_num_pages.
	if ( $paged > $page_count ) {
		$clauses['where'] .= " AND 1=0";
	}

	// Get the date for the current page.
	$start = (int) $paged-1;
	$date  = array_slice( (array) $dates, $start, 1 );

	// Add current date to the query object.
	$query->set( 'date_pagination_current', km_dp_date_pagination_set_date( $date ) );

	// Add next date to the query object.
	$next = array_slice( (array) $dates, $start+1, 1 );
	$query->set( 'date_pagination_next', km_dp_date_pagination_set_date( $next ) );

	// Add previous date to the query object.
	if ( 0 < $start ) {
		$prev = array_slice( (array) $dates, $start-1, 1 );
		$query->set( 'date_pagination_prev', km_dp_date_pagination_set_date( $prev ) );
	} else {
		$query->set( 'date_pagination_prev', array() );
	}

	if ( $date ) {
		// No pagination needed for this query.
		$clauses['limits'] = '';

		// Year sql.
		if ( isset( $date[0]['year'] ) ) {
			$clauses['where'] .= " AND YEAR($wpdb->posts.post_date)='" . $date[0]['year'] . "'";
		}

		// Month sql.
		if ( isset( $date[0]['month'] ) ) {
			$clauses['where'] .= " AND MONTH($wpdb->posts.post_date)='" . $date[0]['month'] . "'";
		}

		// Day sql.
		if ( isset( $date[0]['dayofmonth'] ) ) {
			$clauses['where'] .= " AND DAYOFMONTH($wpdb->posts.post_date)='" . $date[0]['dayofmonth'] . "'";
		}
	}

	return $clauses;
}

/**
 * Sets the max_num_pages.
 *
 * @since 0.1
 *
 * @param array    $posts Array with post objects.
 * @param WP_Query $query WP_Query object.
 * @return array Array with post objects
 */
function km_dp_date_pagination_max_num_pages( $posts, $query ) {
	$max_num_pages = $query->get( 'date_pagination_max_num_pages' );

	if ( ! empty( $max_num_pages ) ) {
		$query->max_num_pages = $max_num_pages;
	}

	return $posts;
}
