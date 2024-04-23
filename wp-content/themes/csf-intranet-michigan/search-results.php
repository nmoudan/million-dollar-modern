<?php



/* Template Name: SearchWP Results */



get_header();



global $post;



// Retrieve applicable query parameters.

$search_query = isset( $_GET['searchwpcs'] ) ? sanitize_text_field( $_GET['searchwpcs'] ) : null;

$search_page  = isset( $_GET['swppg'] ) ? absint( $_GET['swppg'] ) : 1;



// Perform the search.

$search_results    = [];

$search_pagination = '';

if ( ! empty( $search_query ) && class_exists( '\SearchWP\Query' ) ) {

	$searchwp_query = new \SearchWP\Query( $search_query, [

	'engine' => 'supplemental', // The Engine name.

	'fields' => 'all',      // Retain site ID info with results.

	'site'   => 'all',          // Search all sites.

	'page'   => $search_page,

] );



	$search_results = $searchwp_query->get_results();



	$search_pagination = paginate_links( array(

		'format'  => '?swppg=%#%',

		'current' => $search_page,

		'total'   => $searchwp_query->max_num_pages,

	) );

}



?>

<div id="primary" class="content-area">

	<main id="main" class="site-main search-result-main" role="main">

<div class="container">

		<header class="page-header">

			<?php if ( ! empty( $search_query ) ) : ?>

			    <h1 class="page-title">Résultats de recherche pour: <span class="searchresultquery"><?php printf( __( '%s' ), esc_html( $search_query ) ); ?></span> </h1>

			<?php else : ?>

			    <h1 class="page-title">Précisez votre recherche: </h1>

				 <!-- BEGIN Supplemental Engine Search form -->

        		<form role="search" method="get" class="search-form multisitesearchform" action="<?php echo site_url( 'rechercher/' ); ?>">

        			<label>

        				<span class="screen-reader-text">

        				<?php echo _x( 'Search for:', 'label' ) ?>

        				</span>

        				<input type="search" class="search-field" data-swplive="true" data-swpconfig="supplemental_config"

        				name="searchwpcs"

        				placeholder="<?php echo esc_attr_x( 'Rechercher...', 'placeholder' ) ?>"

        				value="<?php echo isset( $_GET['searchwpcs'] ) ? esc_attr( $_GET['searchwpcs'] ) : '' ?>"

        				title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />

        			</label>

        			<input type="submit" class="search-submit"

        				value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />

        		</form>

        		<!-- END Supplemental Engine Search form -->

			<?php endif; ?>

			

		</header>

		<?php 

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

			<?php  foreach ( $filtered_results as $search_result ) : ?>

				<article class="page hentry search-result">

					<?php

					$switched_site = false;

                    if ( $current_blog_id !== $search_result->site ) {

                        switch_to_blog( $result->site );

                        $switched_site = true;

                    }

                    //var_dump($search_result);

					switch( get_class( $search_result ) ) {

						case 'WP_Post':

							$post = $search_result;

							$PostTitle = str_replace("_", " ", $search_result->post_title);

							$content = $search_result->post_excerpt;

                            $excerpt = wp_trim_words($content, 10);

							?>

							<header class="entry-header">

                                <h2 class="entry-title posts searchtitle">

                                    <a href="<?php echo $search_result->guid; ?>" target=""><?php echo $PostTitle; ?></a>

                                </h2>

                                <div class="entry-meta">

                                    <span class="posted-on"><?php echo $search_result->post_date; ?></span>

                                </div>

                            </header>

							<div class="entry-summary searchexcerpt"><?php echo $excerpt ?></div>

							<?php

							wp_reset_postdata();

							break;

						case 'WP_User':

							?>

							<header class="entry-header"><h2 class="entry-title searchtitle">

								<a href="<?php echo get_author_posts_url( $search_result->data->ID ); ?>">

									<?php echo esc_html( $search_result->data->display_name ); ?>

								</a>

							</h2></header>

							<div class="entry-summary searchexcerpt">

								<?php echo wp_kses_post( get_the_author_meta( 'description',

												$search_result->data->ID ) ); ?>

							</div>

							<?php

							break;

						case 'WP_Term':

						?>

						<header class="entry-header">

							<h2 class="entry-title searchtitle">

								<a href="<?php echo get_term_link( $search_result->term_id, $search_result->taxonomy ); ?>">

									<?php echo esc_html( $search_result->name ); ?>

								</a>

							</h2>

							<div class="entry-summary searchexcerpt">

								<?php //echo esc_html( $search_result->description ); ?>

							</div>

						</header>

						<?php

						break;

					}

					?>

				</article>

				<?php

                    if ( $switched_site ) {

                        restore_current_blog();

                    }

                ?>

			<?php endforeach; ?>



			<?php if ( $searchwp_query->max_num_pages > 1 ) : ?>

				<div class="navigation pagination" role="navigation">

					<h2 class="screen-reader-text">Results navigation</h2>

					<div class="nav-links"><?php echo wp_kses_post( $search_pagination ); ?></div>

				</div>

			<?php endif; ?>

		<?php elseif ( ! empty( $search_query ) ) : ?>

			<p>No results found, please search again.</p>

		<?php endif; ?>

</div>

<script>

    jQuery('.search-field').on('click', function(){

    	    jQuery('.searchwp-live-search-results').insertAfter(this);

    	});

</script>

	</main> <!-- .site-main -->

</div> <!-- .content-area -->



<?php get_footer(); ?>