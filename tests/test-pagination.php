<?php
/**
 * Test pagination functions
 *
 * @package Date_Pagination_Unit_Tests
 */

/**
 * Class testing date labels and WordPress pagination.
 */
class Test_Date_Pagination extends WP_UnitTestCase {

	private $utils;
	private $posts;

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
		$this->posts = $this->utils->create_posts();
	}

	/**
	 * Test pagination label is empty when date_pagination_type query var is not set.
	 */
	function test_pagination_not_set() {
		$this->go_to( '/' );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F Y' ), 'next' );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label( 'F Y' ), 'previous' );
	}

	/**
	 * Test pagination label is empty when date_pagination_type query var is not set.
	 */
	function test_query_var() {
		$this->utils->paginate_by_date( '' );
		$this->go_to( '/' );
		global $wp_query;
		$this->assertTrue( array_key_exists( 'date_pagination_type', $wp_query->query_vars ) );
	}

	/**
	 * Test max_num_pages is set.
	 */
	function test_max_num_pages() {
		$this->utils->paginate_by_date( 'monthly' );
		$this->go_to( '/' );
		global $wp_query;
		$dates = $wp_query->get( 'date_pagination_dates' );
		$this->assertEquals( count( $dates ), $wp_query->max_num_pages );
		$this->assertEquals( 5, $wp_query->max_num_pages );
	}

	/**
	 * Test current date label.
	 */
	function test_current_label() {
		$this->utils->paginate_by_date( 'daily' );
		// Go to a first post page
		$this->go_to( '/' );
		$expected = mysql2date( 'F j, Y', $this->posts[0]->post_date );
		$this->assertEquals( $expected, km_dp_get_current_date_label( 'F j, Y' ) );
	}


	/**
	 * Test first page pagination.
	 */
	function test_monthly_pagination() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a first post page
		$this->go_to( '/' );
		$next = mysql2date( 'F Y', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label(), 'next' );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label(), 'previous' );
	}

	/**
	 * Test third page pagination.
	 */
	function test_monthly_pagination_paged_3() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a middle post page
		$this->go_to( '/?paged=3' );
		$next = mysql2date( 'F', $this->posts[3]->post_date );
		$prev = mysql2date( 'F', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label( 'F' ), 'next' );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F' ), 'previous' );
	}

	/**
	 * Test last page pagination
	 */
	function test_monthly_pagination_paged_4() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a last post page
		$this->go_to( '/?paged=5' );
		$prev = mysql2date( 'F', $this->posts[3]->post_date );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F' ), 'next' );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F' ), 'previous' );
	}

	/**
	 * Test invalid page pagination.
	 */
	function test_monthly_pagination_paged_999() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a non existing page
		$this->go_to( '/?paged=999' );
		$this->assertTrue( is_404() );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F' ), 'next' );
		$this->assertEmpty( km_dp_get_previous_date_label( 'F' ), 'previous' );
	}

	/**
	 * Query var 'date_pagination_dates' is set.
	 */
	function test_yearly_pagination() {
		$this->utils->paginate_by_date( 'yearly' );
		$this->go_to( '/' );
		global $wp_query;
		$years = array();
		foreach ( $this->posts as $p ) {
			$date = explode( '-', $p->post_date );
			// Create year dates
			$years[] = $date[0] . '-01-01';
		}
		$years = array_values( array_unique( $years ) );
		$this->assertEquals( $years, $wp_query->get( 'date_pagination_dates' ) );
	}
}
