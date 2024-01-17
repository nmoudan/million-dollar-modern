<li id="text-9" class="widget widget_text sidebar-wrapper">
<?php
  global $post, $post_id, $wp_query;
    // get post by post id
    $post = get_post($post->ID);
    $IDOutsideLoop = $post->ID;
    // get post type by post
    $post_type = $post->post_type;
    $taxonomies = get_object_taxonomies($post_type);
   // $taxonomies = array_reverse($taxonomies1, true);
    foreach( $taxonomies as $taxonomy ) :
    $taxonomy2 = str_replace('_',' ',$taxonomy);
    // Gets every "category" (term) in this taxonomy to get the respective posts
    $termsonemainorder = get_the_terms( $post->ID, $taxonomy );
    $termsone = array_reverse($termsonemainorder, true);
   // var_dump($queried_object);
    foreach( $termsone as $termone ) : ?>
    <style type="text/css">
    .leftside{
      position: relative;
    }
    #active_class{
        display: block !important;
        overflow: visible !important;
    }
    #active_class a{
        background: #e8e8e8 !important;
    }
    .active_drop{
         display: block !important;
       
    }
    li a{
        cursor: pointer;
    }
    @media(max-width: 960px){
      .sidebar-wrapper{
        position: absolute !important;
        top: 0px !important;
      }
    }
    @media only screen and (min-width: 960px) and (max-width: 1200px){
      .sidebar-wrapper{
        width: 200px !important;
      }
    }
    .sidebarfixed {
      position: fixed !important;
      top: 0;
    }

    /*.cntt-w{
      height: 100%;
    }*/
</style>

 <h4 class="subtitle"><?php echo str_replace('_',' ',$termone->taxonomy); ?></h4>
 <?php 
 $args = array(
  'orderby' => 'title',
  'order' => 'ASC'
  );
 $terms = get_terms($termone->taxonomy, $args);
  //$terms = array_reverse($termsmain, true);
 foreach( $terms as $term ){ ?>
     <section id='eg1' class="ladder tree">
            <a class="flip"><?php echo $term->name; ?></a>
             <ul class="drop_out" style="display: none;">
       <?php     
                        $argspost = array(
                            'post_type' => $post_type,
                            'orderby' => 'title',
                            'order'   => 'ASC',
                            'posts_per_page' => -1,  //show all posts
                            'tax_query' => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field' => 'slug',
                                    'terms' => $term->slug,
                                )
                            )
             
                        );
        query_posts($argspost);
        //$posts = new WP_Query($args);
        if( have_posts() ): while( have_posts() ) : the_post(); 
        if( $IDOutsideLoop == get_the_ID() ) { 
            ?>
         <li class="panel active_p" data-id="12345" id="active_class">
         <?php  
        }
        else { ?>
        <li class="panel">
        <?php
        }
        ?>           
        <a class="panal"  href="<?php echo the_permalink(); ?>"><?php  echo get_the_title(); ?></a>
        </li>

        <?php 
            endwhile; 
             endif;
             wp_reset_query();
            ?>
        </ul>
        </section>
           <?php 
            }
            endforeach;
            endforeach;
            ?>
<script type="text/javascript">
      //jQuery("#eg1 ul").slideToggle();
      jQuery(".active_p").parent().addClass("active_drop");
       jQuery(".active_p").parent().prev("a").addClass("active_flip");
      jQuery(".flip").click(function(e){ 
      jQuery(this).next("ul").slideToggle('slow',function(){
      e.preventDefault();
      jQuery("ul").toggleClass('active_panel');
      jQuery(this).prev("a").toggleClass('active_flip');
      jQuery(this).removeClass("active_drop");
     // jQuery(this).removeAttr('class');
    });
    return false; 
});
// fixed sidebar
/*
jQuery(function () {
  
  var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
  
 if (jQuery('.sidebar-wrapper').height() > 768) {
   if (!msie6 && jQuery('.sidebar-wrapper').offset()!=null) {
    var top = jQuery('.sidebar-wrapper').offset().top - parseFloat(jQuery('.sidebar-wrapper').css('margin-top').replace(/auto/, 0));
  var height = jQuery('.sidebar-wrapper').height();
  var winHeight = jQuery(window).height(); 
  var footerTop = jQuery('#pre-footer').offset().top - parseFloat(jQuery('#pre-footer').css('margin-top').replace(/auto/, 0));
  
  var gap = 30;
    jQuery(window).scroll(function (event) {
      // what the y position of the scroll is
      var y = jQuery(this).scrollTop();
      
      // whether that's below the form
      if (y+winHeight >= top+ height+gap && y+winHeight<=footerTop) {
        // if so, ad the fixed class
        jQuery('.sidebar-wrapper').addClass('sidebarfixed').css('top',winHeight-height-gap +'px');
      } 
    else if (y+winHeight>footerTop) {
        // if so, ad the fixed class
       jQuery('.sidebar-wrapper').addClass('sidebarfixed').css('top',footerTop-height-y-gap+'px');
      } 
    else    
    {
        // otherwise remove it
        jQuery('.sidebar-wrapper').removeClass('sidebarfixed').css('top','0px');
      }
    });
  }  
 }
 else{
  jQuery( document ).ready(function() {
  console.log( "document ready!" );

  var jQuerysticky = jQuery('.sidebar-wrapper');
  var jQuerystickyrStopper = jQuery('#pre-footer');
  if (!!jQuerysticky.offset()) { // make sure ".sidebar-wrapper" element exists

    var generalSidebarHeight = jQuerysticky.innerHeight();
    var stickyTop = jQuerysticky.offset().top;
    var stickOffset = 50;

    var stickyStopperPosition = jQuerystickyrStopper.offset().top;
    var stopPoint = stickyStopperPosition - generalSidebarHeight - stickOffset;
    var diff = stopPoint + stickOffset;

    jQuery(window).scroll(function(){ // scroll event
      var windowTop = jQuery(window).scrollTop(); // returns number

      if (stopPoint < windowTop) {
          jQuerysticky.css({ position: 'static', top: diff });
      } else if (stickyTop < windowTop+stickOffset) {
          jQuerysticky.css({ position: 'fixed', top: stickOffset });
          jQuery('#pre-footer').css('margin-top','20px');
      } else {
          jQuerysticky.css({position: 'static', top: 'initial', width: '300px',});
      }
    });

  }
});
 }
});
jQuery('.list-items').click(function(){
  jQuery(this).alert("hello");
})
*/
</script>