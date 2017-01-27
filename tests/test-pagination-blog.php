<?php
/**
 * Test pagination on a date, posts and static front page.
 *
 */

/**
 * Test pages.
 */
class Test_Date_Pagination_For_Pages extends WP_UnitTestCase {

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
		$this->blogpage =  $this->factory->post->create( array( 'post_type' => 'page' ) );
		// Set blog posts pate.
		update_option( 'page_for_posts', $this->blogpage );
	}

	/**
	 * Test blog page.
	 */
	function test_blog_page() {
		$this->go_to( get_permalink( $this->blogpage ) );
		$this->assertTrue( is_home() );
		$this->assertEquals( array( 'blog' ), get_body_class() );
	}

	/**
	 * Test first blog page pagination.
	 */
	function test_blog_monthly_pagination() {
		$this->utils->paginate_by_date( 'monthly' );
		$this->go_to( get_permalink( $this->blogpage ) );
		$next = mysql2date( 'F Y', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label(), 'next' );
		$this->utils->assertLabelEmpty( km_dp_get_previous_date_label(), 'previous' );
	}

	/**
	 * Test third blog page pagination.
	 */
	function test_blog_monthly_pagination_paged_3() {
		$this->utils->paginate_by_date( 'monthly' );
		$blog = add_query_arg( 'paged', 3, get_permalink( $this->blogpage ) );
		$this->go_to( $blog );
		$next = mysql2date( 'F', $this->posts[3]->post_date );
		$prev = mysql2date( 'F', $this->posts[1]->post_date );
		$this->utils->assertLabelEquals( $next, km_dp_get_next_date_label( 'F' ), 'next' );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F' ), 'previous' );
	}

	/**
	 * Test last blog page pagination.
	 */
	function test_blog_monthly_pagination_paged_4() {
		$this->utils->paginate_by_date( 'monthly' );
		$blog = add_query_arg( 'paged', 5, get_permalink( $this->blogpage ) );
		$this->go_to( $blog );
		$prev = mysql2date( 'F', $this->posts[3]->post_date );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F' ), 'next' );
		$this->utils->assertLabelEquals( $prev, km_dp_get_previous_date_label( 'F' ), 'previous' );
	}

	/**
	 * Test invalid blog page pagination.
	 */
	function test_blog_monthly_pagination_paged_999() {
		$this->utils->paginate_by_date( 'monthly' );
		$blog = add_query_arg( 'paged', 999, get_permalink( $this->blogpage ) );
		$this->go_to( $blog );
		$this->utils->assertLabelEmpty( km_dp_get_next_date_label( 'F' ), 'next' );
		$this->assertEmpty( km_dp_get_previous_date_label( 'F' ) );
	}

	function paginate_by_date( $query ) {
		if ( !is_admin() && $query->is_main_query() ) {
			$query->set( 'date_pagination_type', $this->type );
		}
	}
}
