<?php
get_header(); ?>
  <?php /*
  <section id="headline">
    <div class="container">
      <h2><?php printf( '<small>'.esc_html__( 'Search Results for', 'michigan' ).':</small> %s', get_search_query() ); ?></h2>
    </div>
  </section>
  */ ?>
    <section class="container search-results" >
    <hr class="vertical-space2">
	
	<!-- begin | main-content -->
    <section class="col-md-8">
     <br><br>
     <h3>Résultats de recherche :</h3>
     <br>
     <?php
     // Get all sites in the network
        $sites = get_sites();
        // Set search query variable
        //$search_query = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
        $search_query = get_query_var( 's' );
        // Loop through all sites in the network and perform search
        $results = array();
        foreach ( $sites as $site ) {
            switch_to_blog( $site->blog_id );
            $query = new WP_Query( array(
                's'             => $search_query,
                'post_type'     => 'any',
                'post_status'   => 'publish',
                'orderby'       => 'relevance',
                'exact'         => false,
                'sentence'      => true,
            ) );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $title = get_the_title();
                    // Check if the title contains the search query
                    if ( stripos( $title, $search_query ) !== false ) {
                        $results[] = array(
                            'title'     => $title,
                            'permalink' => get_permalink(),
                            'excerpt'   => get_the_excerpt(),
                        );
                    }
                }
            }
            restore_current_blog();
        }
        
        // Output search results
        if ( count( $results ) > 0 ) {
          
            foreach ( $results as $result ) {
              echo '<div class="blog-post">';
                $excerpt = wp_trim_words( $result['excerpt'], 35 );
                echo '<h3 class="post-title-ps1"><a href="' . $result['permalink'] . '">' . $result['title'] . '</a></h3>';
                echo '<p>' . $excerpt . '</p>';
                echo '</div>';
            }
          
        } else {
            echo '<br><br><h3>désolé, aucun résultat n\'a été trouvé pour votre requête de recherche.</h3>';
        }
    //echo '<h2>Similar Search Results</h2>';
// 	 if(have_posts()):
// 		while( have_posts() ): the_post();
// 			get_template_part('parts/blogloop','search');
// 		endwhile;
// 	 else:
// 		get_template_part('parts/blogloop-none');
// 	 endif;
	 
	 ?>
       
      <br class="clear">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else {
	echo '<div class="wp-pagenavi">';
	next_posts_link(esc_html__('&larr; Previous page', 'michigan'));
	previous_posts_link(esc_html__('Next page &rarr;', 'michigan'));
} ?> 
      <div class="white-space"></div>
    </section>
	<aside class="col-md-3 sidebar">
		<?php if(is_active_sidebar('Right Sidebar')) dynamic_sidebar( 'Right Sidebar' ); ?>
	</aside>
    <br class="clear">
  </section>
<?php get_footer(); ?>