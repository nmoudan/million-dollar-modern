<?php
get_header();
$not_found_page = michigan_webnus_options::michigan_webnus_404_page();	
echo '<section class="container"><div class="row-wrapper-x"><section id="main-content" class="container">';
if($not_found_page){
	echo apply_filters('the_content', get_post_field('post_content', $not_found_page));
}else { ?>
</div></section>
    <div class="blox dark dark">
      <div class="container alignleft">
        <h1 class="pnf404"><?php esc_html_e('404','michigan'); ?></h1>
        <h2 class="pnf404"><?php esc_html_e('Page Not Found','michigan'); ?></h2>
        <br>
        <div>
         <?php get_search_form(); ?>
       </div>
	   <h3><?php esc_html_e("We're sorry, but the page you were looking for doesn't exist.","michigan"); ?></h3>
      </div>
    </div>
<section class="container"><div class="row-wrapper-x">
 <?php }
 echo '</div></section></section>';
 get_footer(); ?>