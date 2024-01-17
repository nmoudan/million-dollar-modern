<div class="col-md-6 blg-typ3">
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post blgtyp3'); ?>>
<?php
	$featured_enable = michigan_webnus_options::michigan_webnus_blog_featuredimage_enable();
	$post_format = get_post_format(get_the_ID());
	if(!$post_format) $post_format = 'standard';
	$content = get_the_content();
	$meta_video = rwmb_meta( 'michigan_featured_video_meta' );

// Post Thumbnail
if( !empty($featured_enable) && $post_format != 'aside' && $post_format != 'quote' && $post_format != 'link' && (has_post_thumbnail() || !empty($meta_video))) { ?>
	 <div class="blg-typ3-thumb">
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
					echo do_shortcode('[vc_gallery img_size= "420x280" type="flexslider_slide" interval="3" images="'.$ids.'" onclick="link_no" custom_links_target="_self"]');
					$content = preg_replace('/'.$pattern.'/s', '', $content);}
			} else {
					get_the_image( array( 'meta_key' => array( 'thumbnail', 'thumbnail' ), 'size' => 'michigan_webnus_blog3_img' ) ); }
		?>

		<!--   -->
		<div class="blg-typ3-content">
		<?php } else { ?>
			<div class="blg-typ3-inner">
		<?php } ?>
			<?php
			if(function_exists('wp_review_show_total')){wp_review_show_total(true, 'review-total-only small-thumb');}
			    if(  michigan_webnus_options::michigan_webnus_blog_posttitle_enable() ) { 
					if( ('aside' != $post_format ) && ('quote' != $post_format)  ) { 	
						if( 'link' == $post_format ) {
							preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $content,$matches);
							$content = preg_replace('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i','', $content,1);
							$link ='';
							if(isset($matches) && is_array($matches)) $link = $matches[0]; ?>
							<h3 class="post-title-ps1"><a href="<?php echo esc_url($link); ?>"><?php the_title() ?></a></h3> <?php
				
						} else { ?>
							<h3 class="post-title-ps1"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3> <?php
						}
					}
				}	
			?>	
			<h6 class="blog-date"><a href="<?php the_permalink(); ?>"><?php the_time(get_option('date_format')) ?></a></h6>		
		</div>
		<!-- -->

	</div>
	
</article></div>