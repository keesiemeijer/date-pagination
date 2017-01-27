<?php
/**
 * Class Test_Functions
 */

/**
 * Test Functions.
 */
class Test_Date_Pagination_Functions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
	}


	/**
	 * Test invalid type.
	 */
	function test_type_invalid() {
		$this->assertFalse( km_dp_date_pagination_is_valid_type( '' ) );
	}

	/**
	 * Test valid type.
	 */
	function test_type_valid() {
		$this->assertTrue( km_dp_date_pagination_is_valid_type( 'daily' ) );
	}

	/**
	 * Test invalid date parameter.
	 */
	function test_convert_date_invalid() {
		$this->assertEmpty( km_dp_date_pagination_convert_date( '' ) );
		$this->assertEmpty( km_dp_date_pagination_convert_date( array( 'month' => 6 ) ) );
		$this->assertEmpty( km_dp_date_pagination_convert_date( array( 'year' => 20178 ) ) );
	}

	/**
	 * Test year date created from array.
	 */
	function test_convert_date_year() {
		$date = array( 'year' => 2017 );
		$this->assertEquals( '2017-01-01', km_dp_date_pagination_convert_date( $date ) );
	}

	/**
	 * Test month date created from array.
	 */
	function test_convert_date_month() {
		$date = array( 'year' => 2017, 'month' => 5 );
		$this->assertEquals( '2017-05-01', km_dp_date_pagination_convert_date( $date ) );
	}

	/**
	 * Test day date created from array.
	 */
	function test_convert_date_day() {
		$date = array( 'year' => 2017, 'month' => 5, 'dayofmonth' => 14 );
		$this->assertEquals( '2017-05-14', km_dp_date_pagination_convert_date( $date ) );
	}

	/**
	 * Test default values are added.
	 */
	function test_set_date() {
		$date = array( array( 'year' => 2017 ) );
		$expected = array(
			'year' => 2017,
			'month' => '',
			'dayofmonth' => '',
			'date' => '2017-01-01'
		);

		$this->assertEquals( $expected, km_dp_date_pagination_set_date( $date ) );
	}

	/**
	 * Test if any function produces output.
	 * Bassically testing if there's debuginng output
	 */
	function test_output() {
		$date = array( array( 'year' => 2017 ) );
		$posts = $this->utils->create_posts();

		ob_start();
		$this->utils->paginate_by_date( 'monthly' );
		$this->go_to( '/' );
		$valid    = km_dp_date_pagination_is_valid_type('');
		$next     = km_dp_get_next_date_label();
		$previous = km_dp_get_previous_date_label();
		$current  = km_dp_get_current_date_label();
		$date     = km_dp_date_pagination_set_date( $date );
		$out      = ob_get_clean();

		//$this->assertEmpty( $out );
	}
}
