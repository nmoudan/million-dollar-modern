<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post blgtyp2'); ?>>
<?php
	$featured_enable = michigan_webnus_options::michigan_webnus_blog_featuredimage_enable();
	$post_format = get_post_format(get_the_ID());
	if(!$post_format) $post_format = 'standard';
	$content = get_the_content();
	$meta_video = rwmb_meta( 'michigan_featured_video_meta' );

// Post Thumbnail
if( !empty($featured_enable) && $post_format != 'aside' && $post_format != 'quote' && $post_format != 'link' && (has_post_thumbnail() || !empty($meta_video))) { ?>
	 <div class="col-md-5 alpha">
		<?php if($post_format  == 'video' || $post_format == 'audio') {
					$pattern = '\\[' . '(\\[?)' . "(video|audio)" . '(?![\\w-])' . '(' . '[^\\]\\/]*' . '(?:' . '\\/(?!\\])' . '[^\\]\\/]*' . ')*?' . ')' . '(?:' . '(\\/)' . '\\]' . '|' . '\\]' . '(?:' . '(' . '[^\\[]*+' . '(?:' . '\\[(?!\\/\\2\\])' . '[^\\[]*+' . ')*+' . ')' . '\\[\\/\\2\\]' . ')?' . ')' . '(\\]?)';
					preg_match('/'.$pattern.'/s', $post->post_content, $matches);
					if( (is_array($matches)) && (isset($matches[3])) && ( ($matches[2] == 'video') || ('audio'  == $post_format)) && (isset($matches[2]))) {
					$video = $matches[0];
					echo do_shortcode($video);
					$content = preg_replace('/'.$pattern.'/s', '', $content);
					} elseif( (!empty( $meta_video )) ) {
					echo do_shortcode($meta_video);
					}
			} elseif( 'gallery'  == $post_format) {
					$pattern = '\\[' . '(\\[?)' . "(gallery)" . '(?![\\w-])' . '(' . '[^\\]\\/]*' . '(?:' . '\\/(?!\\])' . '[^\\]\\/]*' . ')*?' . ')' . '(?:' . '(\\/)' . '\\]' . '|' . '\\]' . '(?:' . '(' . '[^\\[]*+' . '(?:' . '\\[(?!\\/\\2\\])' . '[^\\[]*+' . ')*+' . ')' . '\\[\\/\\2\\]' . ')?' . ')' . '(\\]?)';
					preg_match('/'.$pattern.'/s', $post->post_content, $matches);
					if( (is_array($matches)) && (isset($matches[3])) && ($matches[2] == 'gallery') && (isset($matches[2]))) {
					$ids = (shortcode_parse_atts($matches[3]));				
					if(is_array($ids) && isset($ids['ids'])) { $ids = $ids['ids']; }
					echo do_shortcode('[vc_gallery img_size= "420x330" type="flexslider_slide" interval="3" images="'.$ids.'" onclick="link_no" custom_links_target="_self"]');
					$content = preg_replace('/'.$pattern.'/s', '', $content);}
			} else {
					get_the_image( array( 'meta_key' => array( 'thumbnail', 'thumbnail' ), 'size' => 'michigan_webnus_blog2_img' ) ); }
		?>
	</div>
	<div class="col-md-7 omega">
<?php } else { ?>
	<div class="col-md-12 omega">
	
<?php } ?>
<?php /*
<div class="postmetadata">
<h6 class="blog-cat"><?php the_category(', ') ?> | </h6><h6 class="blog-date"><a href="<?php the_permalink(); ?>"><?php the_time(get_option('date_format')) ?></a></h6>
</div>
*/ ?>
<?php

// Post Title
if( michigan_webnus_options::michigan_webnus_blog_posttitle_enable() && $post_format !='aside' && $post_format !='quote') { 	
	if( 'link' == $post_format ) {
		preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $content,$matches);
		$content = preg_replace('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i','', $content,1);
		$link ='';
		if(isset($matches) && is_array($matches)) $link = $matches[0]; ?>
			<h3 class="post-title-ps1"><a href="<?php echo esc_url($link); ?>"><?php the_title() ?></a></h3>
	<?php }	else { ?>
		<h3 class="post-title-ps1"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
        
        <?php /* Post Meta Custom */ ?>
		<div class="postmetadata">
		<?php if(michigan_webnus_options::michigan_webnus_blog_meta_gravatar_enable()){ ?>
		<div class="au-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 90 ); ?></div>
		<?php } ?>
		<h6 class="blog-author"><?php esc_html_e('rédigé par','michigan'); ?> <?php the_author_posts_link(); ?></h6>
		<h6 class="blog-date"><a href="<?php the_permalink(); ?>"> <?php the_time(get_option('date_format')) ?></a> </h6>&nbsp;
		<h6 class="blog-cat"><?php the_category(', ') ?></h6>
		</div>

	<?php }
}

// Post Content
		if($post_format == 'quote' ) echo '<blockquote>';

			echo '<p>'.michigan_webnus_excerpt((michigan_webnus_options::michigan_webnus_blog_excerpt_list())?michigan_webnus_options::michigan_webnus_blog_excerpt_list():35).'</p>';
			echo '<a class="readmore colorf colorr hcolorr" href="' . get_permalink($post->ID) . '">' . esc_html(michigan_webnus_options::michigan_webnus_blog_readmore_text()) . '</a>';

		if($post_format == 'quote') echo '</blockquote>';
		if($post_format == ('quote') || $post_format == 'aside' )
			echo '<a class="readmore colorf colorr hcolorr" href="'. get_permalink( get_the_ID() ) . '">' . esc_html__('View Post', 'michigan') . '</a>';
	?>	&nbsp;
	<div class="postmetadata" style="display:inline-block;">
	    <?php if( function_exists('zilla_likes') ) zilla_likes(); ?>&nbsp;&nbsp;
		<?php if(michigan_webnus_options::michigan_webnus_blog_meta_comments_enable()){ ?>
			<h6 class="blog-comments"><a class="hcolorf" href="<?php the_permalink(); ?>#comments"> <?php comments_number(  ); ?></a></h6>
		<?php } ?>
	</div>


	    <div class="postmetadata">

	    <?php /*
	    <?php if( 1 == michigan_webnus_options::michigan_webnus_blog_social_share() ) { ?>	
		<div class="blog-social">
			<a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" target="blank"><i class="fa-facebook"></i></a>
			<a class="google" href="https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=<?php the_permalink(); ?>" target="_blank"><i class="fa-google"></i></a>
			<a class="twitter" href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;tw_p=tweetbutton&amp;url=<?php the_permalink(); ?><?php echo isset( $twitter_user ) ? '&amp;via='.$twitter_user : ''; ?>" target="_blank"><i class="fa-twitter"></i></a>
			<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;source=<?php bloginfo( 'name' ); ?>"><i class="fa-linkedin"></i></a>
			<a class="email" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><i class="fa-envelope"></i></a>
		</div>
		<?php } ?>
		*/ ?>
		</div>

	</div>
<hr class="vertical-space1">
</article>