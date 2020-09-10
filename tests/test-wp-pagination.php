<?php
/**
 * Test wordpress numerical pagination with date labels.
 *
 * @package Date_Pagination_Unit_Tests
 */

/**
 * Class testing date labels and WordPress pagination.
 */
class Test_Date_WP_Pagination extends WP_UnitTestCase {

	private $utils;
	private $posts;

	function setUp() {
		parent::setUp();
		$this->utils = new Date_Pagination_Utils();
		$this->posts = $this->utils->create_posts();
	}

	/**
	 * Test km_dp_paginate_links is the same as paginate_links.
	 */
	function test_km_dp_paginate_links() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a first post page
		$this->go_to( '/' );
		$pagination = km_dp_paginate_links( array( 'type' => 'array' ) );
		$expected   = paginate_links( array( 'type' => 'array'  ) );
		$this->assertEquals( 6, count( $pagination ) );
		$this->assertEquals( $expected, $pagination );
	}

	/**
	 * Test km_dp_paginate_links with formatted date labels.
	 */
	function test_km_dp_paginate_link_labels() {
		$this->utils->paginate_by_date( 'monthly' );
		// Go to a first post page
		$this->go_to( '/' );
		$args = array( 'type' => 'array', 'date_format' => 'M' );
		$pagination = km_dp_paginate_links( $args );
		$expected = '>' . mysql2date( 'M', $this->posts[1]->post_date ) . '<';
		$this->assertContains( $expected, $pagination[1] );
	}

	/**
	 * Test km_dp_paginate_links with formatted date labels.
	 */
	function test_km_dp_paginate_link_labels_custom_query() {
		// Go to a first post page
		$this->go_to( '/' );
		$the_query = new WP_Query( 'date_pagination_type=monthly' );

		// Pagination arguments.
		$args = array(
			'type'        => 'array',
			'date_format' => 'M',
			'date_query'  => $the_query );

		$pagination = km_dp_paginate_links( $args, $the_query );
		$expected = '>' . mysql2date( 'M', $this->posts[1]->post_date ) . '<';
		$this->assertContains( $expected, $pagination[1] );
	}

	/**
	 * Test if page numbers are used with a mismatched total.
	 */
	function test_km_dp_paginate_links_page_number_fallback() {

		$this->utils->paginate_by_date( 'monthly' );

		// Go to a first post page
		$this->go_to( '/' );
		$args = array( 'type' => 'array', 'date_format' => 'M' );

		// remove last value of 'date_pagination_dates'
		global $wp_query;
		$dates = $wp_query->get( 'date_pagination_dates' );
		array_pop( $dates );
		$wp_query->set( 'date_pagination_dates', $dates );

		// Should show page numbers instead of date labels
		$pagination = km_dp_paginate_links( $args );
		$this->assertContains( '2', $pagination[1] );
	}

	function test_defaults() {
		$this->utils->paginate_by_date( 'monthly' );

		// Go to a first post page
		$this->go_to( '/' );

		$date1 = mysql2date( 'M', $this->posts[0]->post_date );
		$page2 = get_pagenum_link( 2 );
		$date2 = mysql2date( 'M', $this->posts[1]->post_date );
		$page3 = get_pagenum_link( 3 );
		$date3 = mysql2date( 'M', $this->posts[2]->post_date );
		$page4 = get_pagenum_link( 5 );
		$date4 = mysql2date( 'M', $this->posts[4]->post_date );

		$expected =<<<EXPECTED
<span aria-current="page" class="page-numbers current">$date1</span>
<a class="page-numbers" href="$page2">$date2</a>
<a class="page-numbers" href="$page3">$date3</a>
<span class="page-numbers dots">&hellip;</span>
<a class="page-numbers" href="$page4">$date4</a>
<a class="next page-numbers" href="$page2">Next &raquo;</a>
EXPECTED;

		$args = array( 'date_format' => 'M' );
		$links = km_dp_paginate_links( $args );
		$this->assertEquals( $expected, $links );
	}


	function test_format() {
		$this->utils->paginate_by_date( 'monthly' );

		// Go to a first post page
		$this->go_to( '/' );

		$date1 = mysql2date( 'M', $this->posts[0]->post_date );
		$page2 = home_url( '/page/2/' );
		$date2 = mysql2date( 'M', $this->posts[1]->post_date );
		$page3 = home_url( '/page/3/' );
		$date3 = mysql2date( 'M', $this->posts[2]->post_date );
		$page4 = home_url( '/page/5/' );
		$date4 = mysql2date( 'M', $this->posts[4]->post_date );

		$expected =<<<EXPECTED
<span aria-current="page" class="page-numbers current">$date1</span>
<a class="page-numbers" href="$page2">$date2</a>
<a class="page-numbers" href="$page3">$date3</a>
<span class="page-numbers dots">&hellip;</span>
<a class="page-numbers" href="$page4">$date4</a>
<a class="next page-numbers" href="$page2">Next &raquo;</a>
EXPECTED;

		$args =array( 'date_format' => 'M', 'format' => 'page/%#%/' );
		$links = km_dp_paginate_links( $args );
		$this->assertEquals( $expected, $links );
	}

	function test_prev_next_false() {
		$this->utils->paginate_by_date( 'monthly' );
		$this->posts = $this->utils->create_posts( 'post', 6 );

		// Go to a first post page
		$this->go_to( '/' );

		$home  = home_url( '/' );
		$date1 = mysql2date( 'M', $this->posts[0]->post_date );
		$date2 = mysql2date( 'M', $this->posts[1]->post_date );
		$page3 = get_pagenum_link( 3 );
		$date3 = mysql2date( 'M', $this->posts[2]->post_date );
		$page4 = get_pagenum_link( 4 );
		$date4 = mysql2date( 'M', $this->posts[3]->post_date );
		$page6 = get_pagenum_link( 6 );
		$date6 = mysql2date( 'M', $this->posts[5]->post_date );

		$page2= '';

		$expected =<<<EXPECTED
<a class="page-numbers" href="$home">$date1</a>
<span aria-current="page" class="page-numbers current">$date2</span>
<a class="page-numbers" href="$page3">$date3</a>
<a class="page-numbers" href="$page4">$date4</a>
<span class="page-numbers dots">&hellip;</span>
<a class="page-numbers" href="$page6">$date6</a>
EXPECTED;

		$args =array(
			'date_format' => 'M',
			'prev_next' => false,
			'current' => 2,
		);
		$links = km_dp_paginate_links( $args );
		$this->assertEquals( $expected, $links );
	}

	function test_prev_next_true() {
		$this->utils->paginate_by_date( 'monthly' );
		$this->posts = $this->utils->create_posts( 'post', 20 );

		// Go to a first post page
		$this->go_to( '/' );

		$home  = home_url( '/' );
		$date1 = mysql2date( 'M', $this->posts[0]->post_date );
		$date2 = mysql2date( 'M', $this->posts[1]->post_date );
		$page3 = get_pagenum_link( 3 );
		$date3 = mysql2date( 'M', $this->posts[2]->post_date );
		$page4 = get_pagenum_link( 4 );
		$date4 = mysql2date( 'M', $this->posts[3]->post_date );
		$page20 = get_pagenum_link( 20 );
		$date20 = mysql2date( 'M', $this->posts[19]->post_date );

		$expected =<<<EXPECTED
<a class="prev page-numbers" href="$home">&laquo; Previous</a>
<a class="page-numbers" href="$home">$date1</a>
<span aria-current="page" class="page-numbers current">$date2</span>
<a class="page-numbers" href="$page3">$date3</a>
<a class="page-numbers" href="$page4">$date4</a>
<span class="page-numbers dots">&hellip;</span>
<a class="page-numbers" href="$page20">$date20</a>
<a class="next page-numbers" href="$page3">Next &raquo;</a>
EXPECTED;

		$args =array(
			'date_format' => 'M',
			'prev_next' => true,
			'current' => 2,
		);
		$links = km_dp_paginate_links( $args );
		$this->assertEquals( $expected, $links );
	}

}
