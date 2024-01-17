<?php
/*
 * Template name: Global Search
 */
 
get_header();



?>

<section class="global-search" style="margin: 65px 0px;">
    <div class="container">
        <h1>Search Anything</h1>

<!-- Display the search form -->
<form method="post" action="">
    <input type="text" name="search_query" value="<?php echo esc_attr( $search_query ); ?>">
    <button type="submit" name="submit">Search</button>
</form>

<?php
// Check if the form has been submitted
if ( isset( $_POST['submit'] ) ) {
    // Get the search query from the form input
    $search_query = isset( $_POST['search_query'] ) ? sanitize_text_field( $_POST['search_query'] ) : '';

    // Get all sites in the network
    $sites = get_sites();

    // Loop through all sites in the network and perform search
    $results = array();
    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id );
        $query = new WP_Query( array(
            's'             => $search_query,
            'post_type'     => 'post',
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
} else {
    // Set search query variable to empty string
    $search_query = '';
    $results = array();
}
// Display the search results
if ( count( $results ) > 0 ) { 
    echo '<h2>Search Result for:'.$search_query.'</h2>'; 
    foreach ( $results as $result ) { 
        $excerpt = wp_trim_words( $result['excerpt'], 35 );
        $title = esc_html( $result['title'] );
        $permalink = esc_url( $result['permalink'] );
        ?>
        <h3><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h3>
        <p><?php echo $excerpt; ?></p>
    <?php } 
    } else { ?>
    <p>No results found.</p>
<?php } ?>

    </div>
</section>

<?php
get_footer();