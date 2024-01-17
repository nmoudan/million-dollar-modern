<?php
    global $post, $post_id;
    // get post by post id
    $post = &get_post($post->ID);
    // get post type by post
    $post_type = $post->post_type;
    // get post type taxonomies
    $taxonomies = get_object_taxonomies($post_type);
    //$queried_object = get_queried_object()->taxonomy;
   foreach( $taxonomies as $taxonomy ) :

    // Gets every "category" (term) in this taxonomy to get the respective posts
    $termsone = get_the_terms( $post->ID, $taxonomy );
   // var_dump($queried_object);
    
    foreach( $termsone as $termone ) : ?>
  <li id="text-9" class="widget widget_text">
  <h4 class="subtitle">
 <? echo $termone->taxonomy; ?>
 </h4>
 <? $terms = get_terms($termone->taxonomy);
 foreach( $terms as $term ){ 
    ?>
     <ul>
     <li>
     <?php echo $term->name; ?>
     <ul>

     <li>
        <?php
        $args = array(
                'post_type' => $post_type,
                'posts_per_page' => -1,  //show all posts
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug,
                    )
                )
 
            );
        query_posts($args);
        if( have_posts() ): while( have_posts() ) : the_post(); ?>
    <a href="<?php echo the_permalink(); ?>">
            <?php  echo get_the_title(); ?>
    </a>
    </li>
  </ul>
  </li>
  </ul>
        <?php 
            endwhile; 
            endif;
            }
            endforeach;
            endforeach;
             ?>