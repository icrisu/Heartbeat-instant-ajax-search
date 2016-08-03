<?php

/**
* DB Helper
*/
class HeartBeatDBInterface
{
	/**
	 * find all posts
	 * @param  [array] $args 
	 * @return [array] posts
	 */
	public static function findAll($args) {
		$queryObj = new WP_Query($args);

		$result = array();

		if ($queryObj->have_posts()) {
			while ( $queryObj->have_posts() ) {
				$queryObj->the_post();
				array_push($result, array(
					'id' => get_the_ID()
				));
			}
			wp_reset_postdata();
		}
		return $result;
	}
}
?>