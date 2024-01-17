<?php
/******************/
/**  Single Post
/******************/
get_header();

//PostShow1
$post_meta = rwmb_meta( 'michigan_blogpost_meta' );
if(!empty($post_meta)){
	if($post_meta=="postshow1" && $thumbnail_id = get_post_thumbnail_id()){
		$background = wp_get_attachment_image_src( $thumbnail_id, 'full' ); ?>
		<div class="postshow1" style="background-image: url(<?php echo esc_url($background[0]); ?> );">
			<div class="postshow-overlay"></div>
			<div class="container"><h1 class="post-title-ps1"><?php the_title() ?></h1></div>
		</div>
<?php }
}
?>


<section id="three_bloc2" class="wpb_row  gray full-row">

	<div class="wpb_column vc_column_container vc_col-sm-12">
	<div class="vc_column-inner ">
	<div class="wpb_wrapper">
		<div class="wpb_text_column wpb_content_element ">
			<div class="wpb_wrapper">
				<h1 style="text-align: center; text-transform: uppercase;"><strong><span style="color: #ffffff;">Le blogue de la technologIE du CSF</span></strong></h1>

			</div>
		</div>
		<div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_30 vc_sep_border_width_2 vc_sep_pos_align_center vc_separator_no_text vc_sep_color_orange">
		<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span><span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
		</div>
	</div>
	</div>
	</div>

</section>


<section class="container page-content" >
	<?php if( michigan_webnus_options::michigan_webnus_blog_sinlge_nextprev_enable()){ ?>
	<?php /*
	<div class="col-md-6 col-sm-6 w-prev-article">
		<?php previous_post_link('%link', '<span class="colorf">'.esc_html__('Previous Article','michigan').'</span><strong>%title</strong>'); ?>  
	</div>
	<div class="col-md-6 col-sm-6 w-next-article">
		<?php next_post_link('%link', '<span  class="colorf">'.esc_html__('Next Article','michigan').'</span><strong>%title</strong>'); ?> 
	</div>
	*/ ?>
	<?php } ?>
	<br>
<hr class="vertical-space2">
	<aside class="col-md-3 sidebar leftside">
		<?php 
		get_sidebar('tut');
		//if(is_active_sidebar('Left Sidebar')) dynamic_sidebar( 'Left Sidebar' ); ?>
		</li>
	</aside>

<section class="<?php echo ( 'none' == michigan_webnus_options::michigan_webnus_blog_singlepost_sidebar()  )?'col-md-12':'col-md-9 cntt-w'?>">
<?php if( have_posts() ): while( have_posts() ): the_post();  ?>
<article class="blog-single-post">
	<?php
	michigan_webnus_setViews(get_the_ID());
	$post_format = get_post_format(get_the_ID());
	$content = get_the_content(); ?>
	<div class="post-trait-w"> <?php
	if(!isset($background)) { ?>
		<div class="blgt1-top-sec">
			<?php
			if(function_exists('wp_review_show_total')){wp_review_show_total(true, 'review-total-only small-thumb');}
				if(  michigan_webnus_options::michigan_webnus_blog_posttitle_enable() ) { 
					if( ('aside' != $post_format ) && ('quote' != $post_format)  ) { 	
						if( 'link' == $post_format ) {
							preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $content,$matches);
							$content = preg_replace('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i','', $content,1);
							$link ='';
							if(isset($matches) && is_array($matches)) $link = $matches[0]; ?>
							<h1 class="post-title-ps1"><?php the_title() ?></h1> <?php
						} else { ?>
							<h1 class="post-title-ps1"><?php the_title() ?></h1> <?php
			}}}?>
		</div>

		<?php /*
		<div class="postmetadata">
		<?php if(michigan_webnus_options::michigan_webnus_blog_meta_gravatar_enable()){ ?>
		<div class="au-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 90 ); ?></div>
		<?php } ?>
		<h6 class="blog-author"><?php esc_html_e('rédigé par','michigan'); ?> <?php the_author_posts_link(); ?></h6>
		<h6 class="blog-date"><a href="<?php the_permalink(); ?>"><?php the_time(get_option('date_format')) ?></a></h6>
		<h6 class="blog-cat"><?php the_category(', ') ?></h6>
		</div>
		*/ ?>

		<?php /*
		<div class="postmetadata" style="display: inline-block !important;float: right;width: auto;padding: auto !important;padding: 7px;">
		    <?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
			<?php if(michigan_webnus_options::michigan_webnus_blog_meta_comments_enable()){ ?>
				<h6 style="display:inline;" class="blog-comments"><a class="hcolorf" href="<?php the_permalink(); ?>#comments"> <?php comments_number(  ); ?></a></h6>
			<?php } ?>
			<?php if(michigan_webnus_options::michigan_webnus_blog_meta_views_enable()){ ?>
				<h6 style="display:inline;" class="blog-views"> <i class="fa-eye"></i><span class="colorb"><?php echo michigan_webnus_getViews(get_the_ID()); ?></span> </h6>
			<?php } ?>
		</div>
		*/ ?>

 <?php }
 /*	if(  michigan_webnus_options::michigan_webnus_blog_sinlge_featuredimage_enable() && !isset($background) ){
	$meta_video = rwmb_meta( 'michigan_featured_video_meta' );
	if( 'video'  == $post_format || 'audio'  == $post_format){
	$pattern = '\\[' . '(\\[?)' . "(video|audio)" . '(?![\\w-])' . '(' . '[^\\]\\/]*' . '(?:' . '\\/(?!\\])' . '[^\\]\\/]*' . ')*?' . ')' . '(?:' . '(\\/)' . '\\]' . '|' . '\\]' . '(?:' . '(' . '[^\\[]*+' . '(?:' . '\\[(?!\\/\\2\\])' . '[^\\[]*+' . ')*+' . ')' . '\\[\\/\\2\\]' . ')?' . ')' . '(\\]?)';
	preg_match('/'.$pattern.'/s', $post->post_content, $matches);
	if( (is_array($matches)) && (isset($matches[3])) && ( ($matches[2] == 'video') || ('audio'  == $post_format)) && (isset($matches[2]))){
	$video = $matches[0];
	echo do_shortcode($video);	
	$content = preg_replace('/'.$pattern.'/s', '', $content);
	}else				
	if( (!empty( $meta_video )) ){
	echo do_shortcode($meta_video);}
	}else
	if( 'gallery'  == $post_format)	{		
	$pattern = '\\[' . '(\\[?)' . "(gallery)" . '(?![\\w-])' . '(' . '[^\\]\\/]*' . '(?:' . '\\/(?!\\])' . '[^\\]\\/]*' . ')*?' . ')' . '(?:' . '(\\/)' . '\\]' . '|' . '\\]' . '(?:' . '(' . '[^\\[]*+' . '(?:' . '\\[(?!\\/\\2\\])' . '[^\\[]*+' . ')*+' . ')' . '\\[\\/\\2\\]' . ')?' . ')' . '(\\]?)';
	preg_match('/'.$pattern.'/s', $post->post_content, $matches);
	if( (is_array($matches)) && (isset($matches[3])) && ($matches[2] == 'gallery') && (isset($matches[2]))){
	$atts = shortcode_parse_atts($matches[3]);
	$ids = $gallery_type = '';
	if(isset($atts['ids'])){
	$ids = $atts['ids'];}
	if(isset($atts['michigan_webnus_gallery_type'])){
	$gallery_type = $atts['michigan_webnus_gallery_type'];}
	echo do_shortcode('[vc_gallery img_size= "full" type="flexslider_fade" interval="3" images="'.$ids.'" onclick="link_image" custom_links_target="_self"]');
	$content = preg_replace('/'.$pattern.'/s', '', $content);}
	}else
	if( (!empty( $meta_video )) ){
	echo do_shortcode($meta_video);
	}else
	get_the_image( array( 'meta_key' => array( 'Full', 'Full' ), 'size' => 'Full', 'link_to_post' => false, ) ); } */	
	?>
</div>

	<!--  ACF CUSTOM FIELDS POUR LA SECTION TUTORIELS  -->	

	<?php 
	if( have_rows('contenu_du_tutoriel') ):
	    while( have_rows('contenu_du_tutoriel') ) : the_row();         
	        
	        $intro = get_sub_field('introduction_de_larticle');
	        if (strlen($intro) > 0) {
	        	echo ('<h4>' . $intro . '</h4>');
   			} 
	        
	        $soustitre = get_sub_field('sous-titre');
	        if (strlen($soustitre) > 0) {
	        	echo ('<h3>' . $soustitre . '</h3>');
   			}

   			$paragraph = get_sub_field('paragraph_du_tutoriel');
   			if (strlen($paragraph) > 0) {
	        	echo ('<p>' . $paragraph . '</p>');
   			}

   			$image = get_sub_field('image_du_tutoriel');
   			if (strlen($image) > 0) {
				echo ('<img src="' . $image .'" style="width: auto;max-height: 100%;">');	
			}

			$video = get_sub_field('video_du_tutoriel');
  			if (strlen($video) > 0) {
	        	echo ('<div class="embed-container">' . $video . '</div>');
			}	

	    endwhile;
	endif; 
	?>
	<!--  END// ACF CUSTOM FIELDS POUR LA SECTION TUTORIELS  -->

<div <?php post_class('post'); ?>>

<?php /*

<div class="au-avatar-box">
<?php if(michigan_webnus_options::michigan_webnus_blog_meta_gravatar_enable()){ ?>	
<div class="au-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 90 ); ?></div>
<?php } ?>
<?php if(michigan_webnus_options::michigan_webnus_blog_meta_author_enable()){ ?>	
<h6 class="blog-author"><strong><?php esc_html_e('by','michigan'); ?></strong> <?php the_author_posts_link(); ?> </h6>
<?php } ?>
</div>


<div class="postmetadata">
	<?php if(michigan_webnus_options::michigan_webnus_blog_meta_date_enable()){ ?>
	<h6 class="blog-date"> <?php the_time(get_option('date_format')) ?></h6>
	<?php } ?>
	<?php if(michigan_webnus_options::michigan_webnus_blog_meta_category_enable()){ ?>
		<h6 class="blog-cat"><strong><?php esc_html_e('in','michigan'); ?></strong> <?php the_category(', ') ?> </h6>
	<?php } ?>
	<?php if(michigan_webnus_options::michigan_webnus_blog_meta_comments_enable()){ ?>
		<h6 class="blog-comments"> <?php comments_number(  ); ?> </h6>
	<?php } ?>
	<?php if(michigan_webnus_options::michigan_webnus_blog_meta_views_enable()){ ?>
		<h6 class="blog-views"> <i class="fa-eye"></i><span class="colorb"><?php echo michigan_webnus_getViews(get_the_ID()); ?></span> </h6>
	<?php } ?>
</div>
*/ ?>

<?php 		
if( 'quote' == $post_format  ) echo '<blockquote>';
echo apply_filters('the_content',$content); 
if( 'quote' == $post_format  ) echo '</blockquote>';
?>	

<?php /*
<?php if(michigan_webnus_options::michigan_webnus_blog_social_share()) { ?>	
	<div class="post-sharing"><div class="blog-social">
		<span>Share</span> 
		<a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" target="blank"><i class="fa-facebook"></i></a>
		<a class="google" href="https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=<?php the_permalink(); ?>" target="_blank"><i class="fa-google"></i></a>
		<a class="twitter" href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;tw_p=tweetbutton&amp;url=<?php the_permalink(); ?><?php echo isset( $twitter_user ) ? '&amp;via='.$twitter_user : ''; ?>" target="_blank"><i class="fa-twitter"></i></a>
		<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;source=<?php bloginfo( 'name' ); ?>"><i class="fa-linkedin"></i></a>
		<a class="email" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><i class="fa-envelope"></i></a>
	</div></div>
<?php } ?>
*/ ?>
<?php 
comments_template(); ?>
<br class="clear"> 
<?php the_tags( '<div class="post-tags"><i class="fa-tags"></i>', '', '</div>' ); ?><!-- End Tags --> 
<div class="next-prev-posts">
<?php $args = array(
'before'           => '',
'after'            => '',
'link_before'      => '',
'link_after'       => '',
'next_or_number'   => 'next',
'nextpagelink'     => '&nbsp;&nbsp; '.esc_html__('Next Page','michigan'),
'previouspagelink' => esc_html__('Previous Page','michigan').'&nbsp;&nbsp;',
'pagelink'         => '%',
'echo'             => 1
); 
wp_link_pages($args);
?>	  

</div><!-- End next-prev post -->

<?php if( michigan_webnus_options::michigan_webnus_blog_single_authorbox_enable() ) { ?>
	<div class="about-author-sec">		  
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), 90 ); ?>
		<h5><?php the_author_posts_link(); ?></h5>
		<p><?php echo get_the_author_meta( 'description' ); ?></p>
	</div>
<?php  } 


endwhile;
endif;

?>
	

</div>
</article>

<?php /*
<?php 
comments_template(); ?>
*/ ?>

</section>
<!-- end-main-conten -->
<div class="white-space"></div>
</section>
<?php 
get_footer();
?>

<style type="text/css">
	.about-author-sec {
	    display: none;
	}
	li#text-9 {
	    max-width: 300px;
	}
	img.landscape.full {
	    display: none;
	}
	.embed-container, .fluid-width-video-wrapper iframe {
	    max-height: 475px;
	}
	.blog-single-post img {
	    max-height: 100% !important;
	    width: auto !important;
	}
</style>
