<li id="text-9" class="widget widget_text sidebar-wrapper">
<?php
 global $post, $post_id, $wp_query;
$queried_object = get_queried_object();
$id_received = $queried_object->ID;
$id_cat = $queried_object->term_id;
if(is_category()){
// switch_to_blog(1);
$args = array('child_of' => $id_cat, 'orderby'=>'title','order'=>'ASC');
$categories = get_categories( $args );
// var_dump($categories);
// restore_current_blog();
 ?>
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
<h4 class="subtitle category"><?php echo $queried_object->name; ?></h4>
 <?php 
  //$terms = array_reverse($termsmain, true);
$query_main = new WP_Query('cat='.$categories[0]->term_id.'&posts_per_page=1');

wp_reset_query();
 foreach( $categories as $term ){ ?>
     <section id='eg1' class="ladder tree">
            <a class="flip"><?php echo $term->name; ?></a>
             <ul class="drop_out" style="display: none;">
       <?php     
        $query = new WP_Query('cat='.$term->term_id.'&posts_per_page=-1&orderby=title&order=ASC');
        if( $query->have_posts() ): while( $query->have_posts() ) : $query->the_post(); 
        if( $query_main->posts[0]->ID == get_the_ID() ) { 
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
    // End logic if user comes from cateory link
      }
if(is_single()){
    $post = get_post($post->ID);
    $current_id = $post->ID;
   $categories = get_the_category($current_id); 
  
   $args = array('child_of' => $categories[0]->parent, 'orderby'=>'title','order'=>'ASC');
   // switch_to_blog(1);
   $all_categories_child = get_categories( $args );
   // restore_current_blog();
   $parentCatName = get_cat_name($categories[0]->parent);
   ?>
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

 <h4 class="subtitle post"><?php echo $parentCatName; ?></h4>
 <?php 
  //$terms = array_reverse($termsmain, true);
 foreach( $all_categories_child as $term ){ ?>
     <section id='eg1' class="ladder tree">
            <a class="flip"><?php echo $term->name; ?></a>
             <ul class="drop_out" style="display: none;">
       <?php     
        $query = new WP_Query('cat='.$term->term_id.'&posts_per_page=-1&orderby=title&order=ASC');
        //$posts = new WP_Query($args);
        if( $query->have_posts() ): while( $query->have_posts() ) : $query->the_post(); 
        if( $current_id == get_the_ID() ) { 
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
            ?>

<?php
// End else statement to check if its post link or cateory link that user comes from 
}
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
/**
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
**/
</script>