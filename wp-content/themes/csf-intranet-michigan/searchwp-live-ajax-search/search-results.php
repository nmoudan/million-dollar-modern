<?php

// Execute a search using our supplemental SearchWP Engine.

// @link https://searchwp.com/documentation/knowledge-base/custom-source-results-live-search/

$search_query = isset( $_REQUEST['searchwpcs'] ) ? sanitize_text_field( $_REQUEST['searchwpcs'] ) : null;



$search_results = [];

if ( ! empty( $search_query ) && class_exists( '\\SearchWP\\Query' ) ) {

	$searchwp_query = new \SearchWP\Query( $search_query, [

		'engine' => 'supplemental', // The Engine name.

		'fields' => 'all',          // Load proper native objects of each result.

		'site'   => 'all',          // Search all sites.

	    'page'   => $search_page,

	] );



	$search_results = $searchwp_query->get_results();

}

$current_blog_id = get_current_blog_id();

 if ( ! empty( $search_query ) && ! empty( $search_results ) ) : 

 	$filtered_results = [];
		    foreach ($search_results as $search_result) {
                $post_parent = $search_result->post_parent;
    
                if (empty($post_parent) && $search_result->post_mime_type != 'application/pdf') {
                    $filtered_results[] = $search_result;
                }
                
            }

 	?>

	<?php foreach ( $filtered_results as $search_result ) : ?>

		<?php

		$switched_site = false;

        if ( $current_blog_id !== $search_result->site ) {

            switch_to_blog( $result->site );

            $switched_site = true;

        }

		switch( get_class( $search_result ) ) {

			case 'WP_Post':

			    $post = $search_result;

				$PostTitle = str_replace("_", " ", $search_result->post_title);

				?>

				<div class="searchwp-live-search-result" role="option" id="" aria-selected="false">

					<p><a href="<?php echo $search_result->guid; ?>" target=""><?php echo $PostTitle; ?></a></p>

				</div>

				<?php

			wp_reset_postdata();

			break;



			case 'WP_User':

				?>

				<div class="searchwp-live-search-result" role="option" id="" aria-selected="false">

					<p><a href="<?php echo get_author_posts_url( $search_result->data->ID ); ?>">

						<?php echo esc_html( $search_result->data->display_name ); ?> &raquo;

					</a></p>

				</div>

				<?php

			break;

		}

            if ( $switched_site ) {

                restore_current_blog();

            }

		?>

	<?php endforeach; ?>

<?php else : ?>

	<p class="searchwp-live-search-no-results" role="option">

		<em><?php esc_html_e( 'No results found.', 'swplas' ); ?></em>

	</p>

<?php endif; ?>