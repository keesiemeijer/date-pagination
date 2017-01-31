<?php
class Date_Pagination_Utils extends WP_UnitTestCase {

	public $type;

	/**
	 * Creates posts with decreasing timestamps.
	 *
	 * @param string  $post_type      Post type.
	 * @param integer $posts_per_page How may posts to create.
	 * @return array                  Array with posts.
	 */
	function create_posts( $post_type = 'post', $posts_per_page = 5 ) {

		_delete_all_posts();

		if ( ! ( isset( $this->factory ) && is_object( $this->factory ) ) ) {
			$factory = new WP_UnitTest_Factory();
		} else {
			$factory = $this->factory;
		}

		if ( ! defined( 'MONTH_IN_SECONDS' ) ) {
			define( 'MONTH_IN_SECONDS',  30 * DAY_IN_SECONDS    );
		}

		// create posts with 4 months decreasing timestamp
		$posts = array();
		$now = time();
		foreach ( range( 0, ( ( $posts_per_page -1 ) * 4 ), 4 ) as $i ) {
			$factory->post->create(
				array(
					'post_date' => date( 'Y-m-d H:i:s', $now - ( $i * MONTH_IN_SECONDS ) ),
					'post_type' => $post_type,
				) );
		}

		// Return posts by desc date.
		$posts = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				//'fields'         => 'ids',
				'order'          => 'DESC',
				'orderby'        => 'date',
			) );

		return $posts;
	}

	function assertLabelEmpty( $expected, $type, $query = false ) {
		$this->assertEmpty( $expected );

		$link = $this->posts_link( $expected, $type, $query );
		$this->assertEmpty( $link );
	}

	function assertLabelEquals( $expected, $value, $type, $query = false ) {
		$this->assertEquals( $expected, $value );

		$link = $this->posts_link( $expected, $type, $query );
		$this->assertContains( $value, $link );
	}

	function posts_link( $expected, $type , $query = false ) {
		if ( $type === 'next' ) {
			if ( isset( $query->max_num_pages ) ) {
				$link = get_next_posts_link(  $expected, $query->max_num_pages );
			} else {
				$link =  get_next_posts_link(  $expected );
			}
		}

		if ( $type === 'previous' ) {
			$link = get_previous_posts_link(  $expected );
		}

		return $link;
	}

	function paginate_by_date( $type ) {
		$this->type = $type;
		add_action( 'pre_get_posts', array( $this, 'paginate_by_date_action' ) );
	}

	function paginate_by_date_action( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			$query->set( 'date_pagination_type', $this->type );
		}
	}
}
