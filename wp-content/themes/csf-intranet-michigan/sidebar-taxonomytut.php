<?php
$title = get_the_title();
$taxonomy2 = strtolower($title);
$taxonomy = str_replace(' ','_',$taxonomy2);
    // Gets every "category" (term) in this taxonomy to get the respective posts
    $terms = get_terms($taxonomy);
   //var_dump($taxonomy);
?>
<style type="text/css">
    button.accordion {
    background-color: #fff;
    color: #1a1a1a;
    cursor: pointer;
    padding: 0px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active{
    color: #002d69;
}

button.accordion:before {
    content: '>';
    color: #1a1a1a;
    font-weight: bold;
    float: left;
    margin-right: 5px;
}

button.accordion.active:before {
    content: ">";
    transform: rotate(90deg);
    color: #002d69;
}

ul.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
</style>
  <li id="text-9" class="widget widget_text">
<h4 class="subtitle">
 <? echo $title; ?></h4> 
    <? foreach( $terms as $term ) : ?>
     <ul><li><button class="accordion"><?php echo $term->name; ?></button><ul class="panel">
 </button>
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
        //$posts = new WP_Query($args);
        if( have_posts() ): while( have_posts() ) : the_post(); ?>
    <a href="<?php echo the_permalink(); ?>"><?php  echo get_the_title(); ?></a>
</li></ul></li></ul>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
</script>
        <?php 
            endwhile; 
            wp_reset_query();
            endif;
            endforeach;
        ?>
        </li>