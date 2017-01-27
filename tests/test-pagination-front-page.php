<?php
/**
 * Class SampleTest
 *
 * @package Date_Pagination_Unit_Tests
 */

/**
 * Sample test case.
 */
class Test_Date_Pagination_Static_Front_page extends WP_UnitTestCase {

	private $utils;
	private $posts;
	private $blogpage;

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
		$this->posts = $this->utils->create_posts();
		$page = $this->factory->post->create( array( 'post_type' => 'page' ) );
		// Set static front page
		update_option( 'page_on_front', $page );
		update_option( 'show_on_front', 'page' );
	}

	/**
	 * Test static front page.
	 */
	function test_static_front_page() {
		$this->go_to( '/' );
		$this->assertContains( 'home', get_body_class() );
		$this->assertContains( 'page', get_body_class() );
	}

	/**
	 * Test invalid type.
	 */
	function test_static_front_page_pagination_not_set() {
		$this->go_to( '/' );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F Y' ), 'next' );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label( 'F Y' ), 'previous' );
	}

	/**
	 * Test post count for current query.
	 */
	function test_post_count_from_query() {
		$this->go_to( '/' );
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$this->assertTrue( ( 1 === count( $query->posts ) ) );
	}

	/**
	 * Test max_num_pages is set.
	 */
	function test_max_num_pages() {
		$this->go_to( '/' );
		$query = new WP_Query( 'post_type=post&date_pagination_type=daily&posts_per_page=-1' );
		$dates = $query->get( 'date_pagination_dates' );
		$this->assertEquals( count( $dates ), $query->max_num_pages );
		$this->assertEquals( 5, $query->max_num_pages );
	}

	/**
	 * Test first page pagination.
	 */
	function test_monthly_pagination() {
		// Go to a first post page
		$this->go_to( '/' );
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$next = mysql2date( 'F Y', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label( 'F Y', $query ), 'next', $query );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label( 'F Y', $query ), 'previous', $query );
	}

	/**
	 * Test third page pagination with query var paged.
	 */
	function test_monthly_pagination_paged_3() {
		// Go to a middle post page
		$this->go_to( '/?paged=3' );
		global $paged;
		$paged = 3;
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$next = mysql2date( 'F', $this->posts[3]->post_date );
		$prev = mysql2date( 'F', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label( 'F', $query ), 'next', $query );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F', $query ), 'previous', $query );
	}

	/**
	 * Test third page pagination with query var page.
	 */
	function test_monthly_pagination_page_3() {
		// Go to a middle post page
		$this->go_to( '/?page=3' );
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$next = mysql2date( 'F', $this->posts[3]->post_date );
		$prev = mysql2date( 'F', $this->posts[1]->post_date );
		$this->assertEquals( $next, km_dp_get_next_date_label( 'F', $query ) );
		$this->assertEquals( $prev, km_dp_get_previous_date_label( 'F', $query ) );
	}

	/**
	 * Test last page pagination.
	 */
	function test_monthly_pagination_paged_4() {
		// Go to a last post page
		$this->go_to( '/?paged=5' );
		global $paged;
		$paged = 5;
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$prev = mysql2date( 'F', $this->posts[3]->post_date );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F', $query ), 'next', $query );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F', $query ), 'previous', $query );
	}

	/**
	 * Test invalid page pagination.
	 */
	function test_monthly_pagination_paged_999() {
		$this->go_to( '/?paged=999' );
		global $paged;
		$paged = 999;
		$query = new WP_Query( 'post_type=post&date_pagination_type=monthly&posts_per_page=-1' );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F', $query ), 'next', $query );
		$this->assertEmpty( km_dp_get_previous_date_label( 'F', $query ) );
	}
}
