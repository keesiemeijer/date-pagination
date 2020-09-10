<?php
/**
 * Test custom queries
 *
 * @package Date_Pagination_Unit_Tests
 */

/**
 * Class testing WP_Query.
 */
class Test_WP_Query extends WP_UnitTestCase {

	private $utils;
	private $posts;

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
		$this->posts = $this->utils->create_posts();
	}

	/**
	 * Test query first page.
	 */
	function test_first_page_without_paged_argument() {
		// Go to a first post page
		$this->go_to( '/' );
		$the_query = new WP_Query( 'date_pagination_type=monthly' );

		$this->assertSame( 1, $the_query->query_vars['date_pagination_paged'] );
	}

	/**
	 * Test query second page.
	 */
	function test_second_page_without_paged_argument() {
		// Go to a second post page
		$this->go_to( '/?paged=2' );
		$the_query = new WP_Query( 'date_pagination_type=monthly' );

		$this->assertSame( 2, $the_query->query_vars['date_pagination_paged'] );
	}

	/**
	 * Test query first page.
	 */
	function test_first_page_query_with_paged_argument() {
		// Go to a first post page
		$this->go_to( '/' );
		$the_query = new WP_Query( 'date_pagination_type=monthly&paged=1' );

		$this->assertSame( 1, $the_query->query_vars['date_pagination_paged'] );
	}

	/**
	 * Test query second page.
	 */
	function test_second_page_with_paged_argument() {
		// Go to a second post page
		$this->go_to( '/?paged=2' );
		$the_query = new WP_Query( 'date_pagination_type=monthly&paged=2' );

		$this->assertSame( 2, $the_query->query_vars['date_pagination_paged'] );
	}


	/**
	 * Test custom query first page.
	 */
	function test_first_page_with_custom_paged_argument() {
		// Go to a first post page
		$this->go_to( '/' );
		$the_query = new WP_Query( 'date_pagination_type=monthly&paged=3' );

		$this->assertSame( 3, $the_query->query_vars['date_pagination_paged'] );
	}

	/**
	 * Test custom query second page.
	 */
	function test_second_page_with_custom_paged_argument() {
		// Go to a second post page
		$this->go_to( '/?paged=2' );
		$the_query = new WP_Query( 'date_pagination_type=monthly&paged=3' );

		$this->assertSame( 3, $the_query->query_vars['date_pagination_paged'] );
	}

}
