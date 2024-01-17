<?php
/******************/
/**  Single Course
/******************/
get_header();
$course_features = michigan_webnus_options::michigan_webnus_course_features();
?>
<section class="container page-content">
<hr class="vertical-space2">
<?php //Breadcrumb
if(michigan_webnus_options::michigan_webnus_enable_breadcrumbs()){ 
	$homeLink = esc_url(home_url('/'));
	$post_type = get_post_type_object(get_post_type());
	$slug = $post_type->rewrite;
	$cat = (get_the_term_list(get_the_id(), 'course_cat','',', ' ))? '<i class="fa-angle-right"></i> '.get_the_term_list(get_the_id(), 'course_cat','',', ' ) : '';
	echo '<div class="breadcrumbs-w"><div class="container"><div id="crumbs"><a href="'.$homeLink.'">'.esc_html__('Home','michigan').'</a> <i class="fa-angle-right"></i> <a href="' . $homeLink .  $post_type->rewrite['slug'] . '/">' . $post_type->labels->name . '</a> '. $cat .' <i class="fa-angle-right"></i> <span class="current">'.get_the_title().'</span></div></div></div>';
}
?>
<div class="course-main">
	<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
	<div class="col-md-12 post-trait-w"> 
		<h1 class="post-title-ps1"><?php the_title(); ?></h1>
		<?php
		if(class_exists('LifterLMS')){
			global $course;
		}
		$course_price = michigan_webnus_course_price();
		if(isset($course_features['price']) && $course_price )	echo '<div class="w-course-price">'.$course_price.'</div>'; ?>
	</div>
	<section class="col-md-9 course-content cntt-w">
		<article class="course-single-post">
		<?php michigan_webnus_setViews(get_the_ID());
		$content = get_the_content(); ?>
		<div <?php post_class('post'); ?>>
		<div class="container">
		<?php
		global $post;
		$course_features = michigan_webnus_options::michigan_webnus_course_features();
		$course_no_img = michigan_webnus_options::michigan_webnus_course_no_image();
		$start_date = $end_date = $course_duration = $lesson_max_user = $difficulty = $students ='';
		$display_thubmnail = 'yes';
		if(class_exists('LifterLMS')){
			llms_print_notices();
			$start_date = get_post_meta($post->ID, '_course_dates_from', true);
			$end_date = get_post_meta($post->ID, '_course_dates_to', true);
			$course_duration = get_post_meta($post->ID, '_lesson_length', true);
			$lesson_max_user = get_post_meta($post->ID, '_lesson_max_user', true);
			$difficulty = (is_object($course))? $course->get_difficulty():'';	
			$students = $wpdb->get_results($wpdb->prepare('SELECT user_id, meta_value, post_id FROM '.$wpdb->prefix . 'lifterlms_user_postmeta WHERE meta_key = "_status" AND meta_value = "Enrolled" AND post_id = %d AND EXISTS(SELECT 1 FROM ' . $wpdb->prefix . 'users WHERE ID = user_id)',$post->ID)); 
			$display_thubmnail = get_option('lifterlms_course_display_banner');
		}
		$course_assessments = rwmb_meta('michigan_course_assessments_meta');
		$course_certificate = rwmb_meta('michigan_course_certificate_meta');
		$course_code 		= rwmb_meta('michigan_course_code_meta');
		$course_language 	= rwmb_meta('michigan_course_language_meta');
		$course_lessons 	= rwmb_meta('michigan_course_lessons_meta');
		$course_prequisite 	= rwmb_meta('michigan_course_prequisite_meta');
		$course_students 	= rwmb_meta('michigan_course_students_meta') ? rwmb_meta( 'michigan_course_students_meta' ):count($students);
		//Course Thumbnail
		if($display_thubmnail){
			echo '<div class="post-thumbnail">';
			if(has_post_thumbnail()){
				get_the_image(array('meta_key' => array( 'thumbnail', 'thumbnail' ), 'size' => 'michigan_webnus_latest_img', 'link_to_post' => false));
			}elseif($course_no_img){
				$no_image_src = (michigan_webnus_options::michigan_webnus_course_no_image_src())?michigan_webnus_options::michigan_webnus_course_no_image_src(): get_template_directory_uri().'/images/course_no_image.png';
				echo '<img alt="'.get_the_title().'" width="420" height="330" src="'.$no_image_src.'">';
			}
			echo '</div>';
		} ?>
	<h4 class="course-titles"><?php esc_html_e('Course Features','michigan');?></h4>
	<div class="course-features clearfix">
		<div class="col-md-6"><div class="course-postmeta">
		<?php
		if(isset($course_features['date'])){ //Course Date - by LifterLMS
			echo($start_date)?'<div class="w-cal"><i class="fa-calendar"></i>'.esc_html__('Start Course: ','michigan').'<span>'.$start_date.'</span></div>':'';
			echo($end_date)?'<div class="w-cal"><i class="fa-calendar"></i>'.esc_html__('End Course: ','michigan').'<span>'.$end_date.'</span></div>':'';
		}

		if(isset($course_features['duration']) && $course_duration){ //Course Duration - by LifterLMS
			echo '<div class="w-duration"><i class="fa-clock-o"></i>'.esc_html__('Course Duration: ','michigan').'<span>'.$course_duration.'</span></div>';
		}
		if(isset($course_features['instructors'])){ //Course Instructors
			echo '<div class="w-instructors"><i class="sl-user"></i> ';
			if(function_exists('coauthors_posts_links')){
				esc_html__('Course Instructors: ','michigan');
				coauthors_posts_links();
			}else{
				esc_html_e('Course Instructor: ','michigan');
				echo '<span>'.get_the_author().'</span>';
			}
			echo '</div>';
		}
		if(isset($course_features['category'])){ //Course Category
			echo '<div class="w-category"><i class="fa-bookmark"></i> ';
			esc_html_e('Category: ','michigan');
			the_terms(get_the_id(), 'course_cat' );
			echo '</div>';
		}
		if(isset($course_features['views'])){ //Course View
			echo '<div class="w-view"><i class="fa-eye"></i>';
			esc_html_e('Viewers: ','michigan');
			echo '<span>'.michigan_webnus_getViews(get_the_ID()).'</span>';
			echo '</div>';
		}
		if(isset($course_features['students'])){
			echo '<div class="w-students"><i class="fa-group"></i>';
			esc_html_e('Students: ','michigan');
			echo '<span>'.$course_students.'</span></div>';
		}
		if($course_prequisite){
			echo '<div class="w-preq"><i class="fa-lock"></i>';
			esc_html_e('Prequisite: ','michigan');
			echo '<span>'.$course_prequisite.'</span></div>';
		}
		?>
	</div></div>
	<div class="col-md-6"><div class="course-postmeta">
		<?php
		if($course_assessments){ //Course Assessments
			echo '<div class="w-assess"><i class="fa-check-square-o"></i>';
			esc_html_e('Assessment: ','michigan');
			echo '<span>'.$course_assessments.'</span></div>';
		}
		if($course_certificate){ //Course Certificate
			echo '<div class="w-cert"><i class="fa-certificate"></i>';
			esc_html_e('Certificate: ','michigan');
			echo '<span>'.$course_certificate.'</span></div>';
		}
		if(isset($course_features['code']) && $course_code){  //Course Code
			echo '<div class="w-code"><i class="fa-hashtag"></i>';
			esc_html_e('Code: ','michigan');
			echo '<span>'.$course_code.'</span></div>';
		}
		if($course_language){  //Course Language
			echo '<div class="w-language"><i class="fa-font"></i>';
			esc_html_e('Language: ','michigan');
			echo '<span>'.$course_language.'</span></div>';
		}
		if($course_lessons){ //Course Lessons
			echo'<div class="w-lessons"><i class="fa-clipboard "></i>';
			esc_html_e('Lessons: ','michigan');
			echo '<span>'.$course_lessons.'</span></div>';
		}
		if(isset($course_features['difficulty']) && $difficulty){ //Course Difficulty - by LifterLMS
			echo '<div class="w-difficulty"><i class="fa-signal"></i>';
			printf( esc_html__( 'Difficulty: <span class="difficulty">%s</span>', 'michigan' ), $difficulty );
			echo '</div>';
		}
		if(isset($course_features['capacity']) && $lesson_max_user){ //Course Capacity - by LifterLMS
			echo '<div class="w-capacity"><i class="fa-briefcase"></i> ';
			esc_html_e('Course Capacity: ','michigan');
			echo '<span>'.$lesson_max_user.'</span>';
			echo '</div>';
		}
		?>
	</div></div>
</div>
<div class="course-take-rate clearfix">
	<div class="col-md-6">
	<?php if (isset($course_features['rating']) && function_exists('the_ratings')){ //Course Rating
		the_ratings();
	} ?>
	</div>
	<div class="col-md-6">
	<?php
	if(michigan_webnus_options::michigan_webnus_course_taking()==1 && class_exists('LifterLMS')){ //Take Course
		llms_get_template( 'course/purchase-link.php' );
	}elseif(michigan_webnus_options::michigan_webnus_course_taking()==2){
		echo '<br><a href="'.michigan_webnus_options::michigan_webnus_course_taking_custom().'" class="llms-button" target="_self">'.esc_html__('Take This Course','michigan').'</a>';
	}
	?>
	</div>
</div>
<h4 class="course-titles"><?php esc_html_e('Course Details','michigan'); ?></h4>
<div class="course-details">		
			<?php echo apply_filters('the_content',$content);?>
</div>
			<?php
			 if(isset($course_features['tags'])) {
				the_terms(get_the_id(), 'course_tag' ,'<div class="post-tags"><i class="fa-tags"></i>', '', '</div>');
			}?>
		
		<?php if(class_exists('LLMS_Reviews')){
			$course_review= new LLMS_Reviews();
			add_filter( 'webnus_course_after', array(  $course_review , 'output' ),30 );
			do_action( 'webnus_course_after' );
		} ?>
		</article>
	<?php
	endwhile;
	endif;
	$post_ids[] = $post->ID;
	$author_id = get_the_author_meta('ID');
	$args = array('post__not_in' => $post_ids,'showposts' => 3,'orderby'=>'date','order'=>'desc','post_type'=>'course','author' => $author_id,);
	$rec_query = new wp_query($args);
	if($rec_query->have_posts()){
	echo '<hr class="vertical-space2"><h4 class="course-titles">'.esc_html__('More Courses by this Instructor','michigan').'</h4><hr class="vertical-space1">';
	echo '<div class="row recent-course">';
	while ($rec_query->have_posts()){
	$rec_query->the_post();
	global $wpdb;
	$students = $wpdb->get_results($wpdb->prepare('SELECT	user_id, meta_value, post_id FROM '.$wpdb->prefix . 'lifterlms_user_postmeta WHERE meta_key = "_status"	AND meta_value = "Enrolled"	AND post_id = %d AND EXISTS(SELECT 1 FROM ' . $wpdb->prefix . 'users WHERE ID = user_id)	group by user_id',$post->ID));
	$course_students = rwmb_meta( 'michigan_course_students_meta' ) ? rwmb_meta( 'michigan_course_students_meta' ):count($students);
	?>
	<div class="col-md-4 col-sm-4">
	<article class="modern-grid llms-course-list"><div class="llms-course-link">
	<?php 
	if (get_the_terms($post->ID, 'course_cat' )) {
		echo '<div class="modern-cat">';
		$categories = get_the_terms($post->ID, 'course_cat' );
		$typeName = array();
		foreach ( $categories as $category ){
			$cat_icon = (function_exists('tax_icons_output_term_icon'))?tax_icons_output_term_icon( $category->term_id ):'';
			$typeName[] = '<a class="hcolorf" href="' . esc_url( get_category_link( $category ) ) . '" title="' . esc_attr( sprintf( esc_html__( 'View all courses under %s', 'michigan' ), $category->name ) ) . '">'. $cat_icon . esc_html( $category->name ). '</a>';
		}
		echo implode(', ', $typeName);
		echo '</div>';
	} ?>
	<div class="modern-feature"><a class="" href="<?php the_permalink(); ?>">
	<?php if ( has_post_thumbnail( $post->ID ) ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'michigan_webnus_blog2_img' );
		echo apply_filters( 'lifterlms_featured_img', '<img src="' . $img[0] . '" alt="Placeholder" class="llms-course-image llms-featured-imaged wp-post-image" />' );
	}elseif(function_exists('llms_placeholder_img_src')){
		if(llms_placeholder_img_src()){
			$no_img = get_template_directory_uri().'/images/course_no_image.png';
			echo apply_filters( 'lifterlms_placeholder_img', '<img src="' . $no_img . '" alt="Placeholder" class="llms-course-image llms-placeholder wp-post-image" />' );
		}
	}
	echo '</a>';
	if(class_exists('LLMS_Product')){
		global $course;
		echo ($length_html = $course->get_lesson_length())?'<span class="modern-duration">'.$length_html.'<i class="fa-clock-o"></i></span>':'';
	}
	?>
	</div>	
	<div class="modern-content">
	<h3 class="llms-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<?php
	if(function_exists('the_ratings')) { 
		echo expand_ratings_template('<div class="modern-rating"><span class="rating">%RATINGS_IMAGES%</span> <strong>(%RATINGS_USERS% '.esc_html__('Votes','michigan').')</strong></div>', $post->ID);
	}
	echo ($course_price)?'<div class="llms-price-wrapper"><h4 class="llms-price"><span>'.$course_price.'</span></h4></div>':'';
	?>
	</div>
	<div class="clearfix modern-meta">
	<div class="col-md-8 col-sm-8 col-xs-8">
	<?php
	$instructor_title = '<a href="'.get_author_posts_url( $author_id ).'">'. get_avatar( get_the_author_meta( 'user_email',$author_id ), 20 ).get_the_author_meta( 'display_name',$author_id ).'</a>';
	echo '<div class="modern-instructor">'.$instructor_title.'</div>'; 
	?>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-4">
	<?php
	echo ($course_students)?'<span class="modern-students" title="'.esc_attr('Enrolled Students','michigan').'"><i class="sl-people"></i>'.$course_students.'</span>':'<span class="modern-viewers" title="'.esc_attr('Viewers','michigan').'"><i class="fa-eye"></i>'.michigan_webnus_getViews($post->ID).'</span>';
	?>
	</div>
	</div>
	</div></article>
	</div>
	<?php }
	echo '</div>'; //close row
	}
	wp_reset_postdata();
	if(isset($course_features['comment']))		comments_template();
	?>
	</section>
	<div class="col-md-3 sidebar">
		<aside class="course-bar">
			<?php
				if(isset($course_features['instructor']))				get_template_part('parts/instructor-box');
				if(isset($course_features['enrolled']))					get_template_part('parts/students');
				if(isset($course_features['sharing']))					get_template_part('parts/sharing');
				if(is_active_sidebar('llms_course_widgets_side'))		dynamic_sidebar( 'Course Sidebar');
			?>
		</aside>
	</div>
</div>
<!-- end-main-content -->
<div class="white-space"></div>
</section>
<?php get_footer(); ?>