<?php 

// echo "************ DEBUG ************** > current page : " . get_queried_object()->post_title;

// Init data
if ( 'static' === $this->content_type ) {
	// @TODO first check in the theme
	include( W3D_ROOT_PATH . '/public/views/sliders/static-content/sample-content.php' );
}
elseif ( 'post' === $this->content_type ) {
	// @TODO create WP Query and generate $data array
	$data = array();

	// WP_Query arguments
	$args = array (
		'post_type'             => 'post',
		'posts_per_page'        => '4', // @TODO ajouter ceci comme attribut du shortcode
		//'order'					=> $this->order,
	);

	//// Cannot be both
	//if (! empty($this->include) ) {
	//	$args['post__in'] = explode(',', $this->include);
	//} elseif (! empty($this->exclude) ) {
	//	$args['post__not_in'] = explode(',', $this->exclude);
	//}

	// DEBUG
	// print_r($args);

	// The Query
	$post_query = new WP_Query( $args );

	// The Loop
	if ( $post_query->have_posts() ) {
		while ( $post_query->have_posts() ) {
			$post_query->the_post();
			// Add to grid data
			$data[] = array( 
							'title' => get_the_title(),
							'url' => get_permalink(),
							'content' => get_the_excerpt(),
							'image_url' => wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ),
						);
		}
	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();
} else{ // page | subpage
	// @TODO create WP Query and generate $data array for pages and subpages
	$data = array();
}

?>