<?php
/**
 * Class SampleTest
 *
 * @package Date_Pagination_Unit_Tests
 */

/**
 * Sample test case.
 */
class Test_Date_Pagination_Date_Archive extends WP_UnitTestCase {

	private $utils;
	private $posts;

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
		$this->posts = $this->utils->create_posts();
	}

	/**
	 * Test date page.
	 */
	function test_date_archive() {
		$year = explode( '-', $this->posts[0]->post_date );
		$year = $year[0];
		$this->utils->paginate_by_date( 'monthly' );
		$this->go_to( '/?year=' . $year );
		$this->assertTrue( is_date() );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F Y' ), 'next' );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label( 'F Y' ), 'previous' );
	}
}
