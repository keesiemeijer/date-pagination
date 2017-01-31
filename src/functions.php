<?php
/**
 * Functions
 * @package Date_Pagination
 */

/**
 * Returns next date label from query.
 *
 * @since 0.1
 *
 * @param string        $format Date format.
 * @param WP_Query|bool $query  WP_Query object or false. Default false.
 *                              The global $wp_query object is used when false.  
 * @return string Formatted date or empty string.
 */
function km_dp_get_next_date_label( $format = '', $query = 0  ) {
	return  km_dp_get_date_label( $format, $query );
}

/**
 * Returns previous date label from query.
 *
 * @since 0.1
 *
 * @param string        $format Date format.
 * @param WP_Query|bool $query  A WP_Query object or false. The global $wp_query object is
 *                              used when false. Default false.
 * @return string Formatted date or empty string.
 */
function km_dp_get_previous_date_label( $format = '', $query = false ) {
	return  km_dp_get_date_label( $format, $query, true );
}

/**
 * Returns current date label from query.
 *
 * @since 0.1
 *
 * @param string  $format Date format.
 * @param WP_Query|bool $query  A WP_Query object or false. The global $wp_query object is
 *                              used when false. Default false.
 * @return string Formatted date or empty string.
 */
function km_dp_get_current_date_label( $format = '', $query = false ) {
	return km_dp_get_date_label( $format, $query, 'current' );
}

/**
 * Returns a formatted date from a query.
 *
 * @since 0.1
 *
 * @global WP_Query $wp_query
 *
 * @param WP_Query|bool $query  A WP_Query object or false. The global $wp_query object is
 *                              used when false. Default false.
 * @param string  $format    Date format.
 * @param string  $previous. Previous or next date.
 * @return string Formatted date or empty string.
 */
function km_dp_get_date_label( $format = '', $query = false, $previous = false ) {

	$date = '';

	if ( is_date() ) {
		return '';
	}

	if ( ! $query ) {
		global $wp_query;
		$query = $wp_query;
	}

	// Check if it's a WP_Query object
	if ( ! $query instanceof WP_Query ) {
		return '';
	}

	$type = $query->get( 'date_pagination_type' );
	if ( ! km_dp_date_pagination_is_valid_type( $type ) ) {
		return '';
	}

	$next_prev = 'date_pagination_next';
	if ( $previous ) {
		$next_prev = 'date_pagination_prev';

		if ( 'current' === $previous ) {
			$next_prev = 'date_pagination_current';
		}
	}

	$date = $query->get( $next_prev );
	if ( isset( $date['date'] ) && $date['date'] ) {
		return km_dp_get_date_label_formmatted( $date['date'], $format, $type );
	}

	return '';
}

/**
 * Formats a date.
 *
 * @since 2.0.0
 * 
 * @param  string $date   Date to format.
 * @param  string $format Date format.
 * @param  string $type   Type of date pagination. Accepts 'yearly', 'monthly' and 'daily'.
 * @return string         Formatted date.
 */
function km_dp_get_date_label_formmatted( $date, $format = '', $type = ''  ) {
	if ( ! $date || ! km_dp_date_pagination_is_valid_type( $type ) ) {
		return $date;
	}

	$defaults = array( 'yearly' => 'Y', 'monthly' => 'F Y', 'daily' => 'F j, Y' );
	if ( ! $format && isset( $defaults[ $type ] ) ) {
		$format = $defaults[ $type ];
	}

	return $format ? mysql2date( $format, $date . ' 00:00:00' ) : $date;
}

/**
 * Validates Date Pagination types.
 *
 * @since 0.1
 *
 * @param string  $type Type. Returns false if not 'yearly', 'monthly' or 'daily'.
 * @return bool True if type is valid.
 */
function km_dp_date_pagination_is_valid_type( $type ) {

	if ( in_array( (string) $type, array( 'yearly', 'monthly', 'daily' ) ) ) {
		return true;
	}

	return false;
}

/**
 * Adds defaults and the date to a date array returned by the Date Pagination query.
 *
 * @since 2.0
 *
 * @param array  $date Date array.
 * @return array       Date array with date added.
 */
function km_dp_date_pagination_set_date( $date ) {
	if ( ! ( isset( $date[0] ) && $date[0] ) ) {
		return array();
	}

	$defaults     = array( 'year' => '', 'month' => '', 'dayofmonth' => '' );
	$date         = wp_parse_args( $date[0], $defaults );
	$date['date'] = (string) km_dp_date_pagination_convert_date( $date );

	return $date;
}

/**
 * Return date as a string from a date array.
 *
 * @since 2.0
 *
 * @param array $date Date array.
 * @return string Date or empty string if it could not be converted.
 */
function km_dp_date_pagination_convert_date( $date ) {
	if ( empty( $date ) || ! is_array( $date ) ) {
		return '';
	}

	$defaults = array( 'year' => '', 'month' => '', 'dayofmonth' => '' );
	$date     = wp_parse_args( $date, $defaults );

	if ( ! $date['year'] ) {
		return '';
	}

	$date_str = $date['year'];
	$date_str .= $date['month'] ? '-' . zeroise( $date['month'], 2 ) : '-01';
	$date_str .= $date['dayofmonth'] ? '-' . zeroise( $date['dayofmonth'], 2 ) : '-01';

	return ( 10 === strlen( $date_str ) ) ? $date_str : '';
}
