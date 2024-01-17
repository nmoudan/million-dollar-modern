<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
llms_print_notices();
// Headline
?>
<section id="headline" class="my-courses">
    <div class="container">
      <div class="col-md-10"><h2>
	  <?php esc_html_e('Hello ','michigan');
	  echo $current_user->display_name;
	  echo '</h2>';
	  if (current_user_can('edit_posts')){
		   echo '<span>'.esc_html__('What would you like to teach today?', 'michigan').'</span>';
		   echo '</div><div class="col-md-2"><a class="dashboard-button colorb" href="'.admin_url().'edit.php?post_type=course">'.esc_html__( 'Manage Courses', 'michigan' ).'</a></div>';
	  }else{
		   echo '<span>'.esc_html__('What would you like to learn today?', 'michigan').'</span>';
		   echo '</div><div class="col-md-2"><a class="dashboard-button colorb" href="'.esc_url(home_url('/')).'courses">'.esc_html__( 'Courses', 'michigan' ).'</a></div>';
	  }
?>
    </div>
</section>

<?php //Instructor Dashboard
 global $wpdb;
$user_id = get_current_user_id();
$args = array(
	'post_type' => 'course' ,
	'author' => $user_id,
	'posts_per_page' => -1
);
$custom_posts = new WP_Query( $args );
$instructor_rate_score = $instructor_rate_users = $instructor_rate = $course_count = $student_count = $total_all_orders = $total_orders = 0;
while ( $custom_posts->have_posts() ) : $custom_posts->the_post();
	$post_id = get_the_ID();
	$students = $wpdb->get_results($wpdb->prepare(
		'SELECT
		user_id,
		meta_value,
		post_id
		FROM '.$wpdb->prefix . 'lifterlms_user_postmeta
		WHERE meta_key = "_status"
		AND meta_value = "Enrolled"
		AND post_id = %d
		AND EXISTS(SELECT 1 FROM ' . $wpdb->prefix . 'users WHERE ID = user_id)
		group by user_id'
	,$post_id));
	$orders = $wpdb->get_results($wpdb->prepare(
		'SELECT
		order_post_id
		FROM '.$wpdb->prefix . 'lifterlms_order
		WHERE product_id = %d
		AND order_completed = "yes"
		group by order_post_id'
	,$post_id));					
	foreach ($orders as $order) {
		$total_orders += get_post_meta( $order->order_post_id, '_llms_order_total', true );
	}
	if(function_exists('the_ratings')) {
		$instructor_rate_score = $instructor_rate_score + get_post_meta($post_id, 'ratings_score' , true);
		$instructor_rate_users = $instructor_rate_users + get_post_meta($post_id, 'ratings_users' , true);
	}
	$course_count++;
	$student_count = $student_count + count($students);
	$total_all_orders = $total_all_orders + $total_orders;
endwhile;
$instructor_rate = ($instructor_rate_users)?($instructor_rate_score/$instructor_rate_users):0;
wp_reset_postdata();
delete_user_meta( $user_id, 'instructor_is');
delete_user_meta( $user_id, 'instructor_courses');
delete_user_meta( $user_id, 'instructor_students');
delete_user_meta( $user_id, 'instructor_rate');
add_user_meta( $user_id, 'instructor_is', true);
add_user_meta( $user_id, 'instructor_courses', $course_count);
add_user_meta( $user_id, 'instructor_students', $student_count);
add_user_meta( $user_id, 'instructor_rate', $instructor_rate);


if (current_user_can('edit_posts')){ ?>
	<div class="instructor-dashboard">
		<div class="row">
			<div class="col-md-3">
				<div class="hcolorb colorr inst-cell">
					<div class="inst-cell-icon">
						<i class="colorf fa-money"></i>
					</div>
					<div class="inst-cell-desc">
						<h4 class="inst-cell-title"><?php esc_html_e( 'Total Revenue', 'michigan' );?></h4>
						<span><?php echo get_lifterlms_currency_symbol().$total_all_orders; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="hcolorb colorr inst-cell">
					<div class="inst-cell-icon">
						<i class="colorf sl-book-open"></i>
					</div>
					<div class="inst-cell-desc">
						<h4 class="inst-cell-title"><?php esc_html_e( 'Total Courses', 'michigan' );?></h4>
						<span><?php echo get_the_author_meta( 'instructor_courses',$user_id); ?></span>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="hcolorb colorr inst-cell">
					<div class="inst-cell-icon">
						<i class="colorf sl-people"></i>
					</div>
					<div class="inst-cell-desc">
						<h4 class="inst-cell-title"><?php esc_html_e( 'Total Students', 'michigan' );?></h4>
						<span><?php echo get_the_author_meta( 'instructor_students',$user_id); ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="hcolorb colorr inst-cell">
					<div class="inst-cell-icon">
						<i class="colorf sl-star"></i>
					</div>
					<div class="inst-cell-desc">
						<h4 class="inst-cell-title"><?php esc_html_e( 'Average Rates', 'michigan' );?></h4>
						<span><?php echo get_the_author_meta( 'instructor_rate',$user_id); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } 

/* Taught Courses */
		$args = array(
			'post_type' => 'course' ,
			'author' => $user_id,
			'showposts' => 10
		);
		$custom_posts = new WP_Query( $args );
		if ( $custom_posts->have_posts() ){ ?>
		<div class="clearfix author-courses w-llms-my-courses">
		<?php
			echo '<h3><i class="fa-chevron-right"></i> ' . apply_filters( 'lifterlms_my_courses_title', esc_html__( 'Taught Courses', 'michigan' ) ) . '</h3>';
			echo '<div class="author-carousel">';
			while ( $custom_posts->have_posts() ) : $custom_posts->the_post();
				$post_id = get_the_ID();
				$students = $wpdb->get_results($wpdb->prepare(
					'SELECT
						user_id,
						meta_value,
						post_id
					FROM '.$wpdb->prefix . 'lifterlms_user_postmeta
					WHERE meta_key = "_status"
					AND meta_value = "Enrolled"
					AND post_id = %d
					AND EXISTS(SELECT 1 FROM ' . $wpdb->prefix . 'users WHERE ID = user_id)
					group by user_id'
				,$post_id));
				$course_students = rwmb_meta( 'michigan_course_students_meta' ) ? rwmb_meta( 'michigan_course_students_meta' ):count($students);
				$image_m = get_the_image( array( 'meta_key' => array( 'thumbnail', 'thumbnail' ), 'size' => 'michigan_webnus_blog2_img','echo'=>false, ) );
				$no_img_m = get_template_directory_uri().'/images/course_no_image.png';
				$llms_product = new LLMS_Product( $post_id );
				$price_text = $llms_product->get_single_price_html();
				$course_price = ($price_text)?$price_text:esc_html__('FREE','michigan');
				$course_duration = get_post_meta( $post_id, '_lesson_length', true );
				echo '<article class="modern-grid llms-course-list"><div class="llms-course-link">';
				echo '<div class="modern-feature"><a class="" href="'.get_the_permalink().'">';
				echo ($image_m)? $image_m :'<img src="' . $no_img_m . '" alt="Placeholder" class="w-no-img" />';
				echo ($course_duration)?'<span class="modern-duration">'.$course_duration.'<i class="fa-clock-o"></i></span>':'';
				echo '</a></div>';
				echo '<div class="modern-content">';
				echo '<h3 class="llms-title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
				if(function_exists('the_ratings')) { 
					echo expand_ratings_template('<div class="modern-rating"><span class="rating">%RATINGS_IMAGES%</span> <strong>(%RATINGS_USERS% '.esc_html__('Votes','michigan').')</strong></div>', get_the_ID());
				}
				echo ($course_price)?'<div class="llms-price-wrapper"><h4 class="llms-price"><span>'.$course_price.'</span></h4></div>':'<div class="llms-price-wrapper"><h4 class="llms-price"><span>'.esc_html__('Free','michigan').'</span></h4></div>';
				echo '</div>';
				echo '<div class="clearfix modern-meta">';
				echo '<div class="col-md-8 col-sm-8 col-xs-8">';
				if ( ! isset( $lesson )) {$lesson = new LLMS_Lesson( $post_id );}
				$course_id = $lesson->parent_course;
				$my_post = get_post( $course_id );
				$author_id = $my_post->post_author;
				$total_orders ='0';
					$orders = $wpdb->get_results($wpdb->prepare(
					'SELECT
					order_post_id
					FROM '.$wpdb->prefix . 'lifterlms_order
					WHERE product_id = %d
					AND order_completed = "yes"
					group by order_post_id'
				,$post_id));					
				foreach ($orders as $order) {
					$total_orders += get_post_meta( $order->order_post_id, '_llms_order_total', true );
				}
				echo '<div title="'.esc_attr('Total Sold','michigan').'" class="modern-viewers"><i class="fa-money"></i> '. get_lifterlms_currency_symbol().$total_orders .'</div>'; 
				echo '</div>';
				echo '<div class="col-md-4 col-sm-4 col-xs-4">';
				echo ($course_students)?'<span class="modern-students" title="'.esc_attr('Enrolled Students','michigan').'"><i class="sl-people"></i>'.$course_students.'</span>':'<span class="modern-viewers" title="'.esc_attr('Viewers','michigan').'"><i class="fa-eye"></i>'.michigan_webnus_getViews(get_the_ID()).'</span>';
				echo '</div>';
				echo'</div>';
				echo '</div></article>';
			endwhile;
		echo '</div>';
		echo '</div>';
		}else{
			// nothing found
		}
		wp_reset_postdata();
		
		
		
		
// Courses In-Progress
$person = new LLMS_Person;
$my_courses = $person->get_user_postmetas_by_key( get_current_user_id(), '_status' );
?>
<div class="container w-llms-my-courses">
<?php echo  '<h3><i class="fa-chevron-right"></i> ' . apply_filters( 'lifterlms_my_courses_title', esc_html__( 'Courses In-Progress', 'michigan' ) ) . '</h3>';
if ( $my_courses ) {?>
	<div class="author-carousel">
	<?php
	foreach ( $my_courses as $course_item ) {
		if ( 'Enrolled' == $course_item->meta_value ) {
			$course = new LLMS_Course_Basic( $course_item->post_id );
			if ( is_object( $course->post ) && 'course' == $course->post->post_type ) {
				$course_progress = $course->get_percent_complete();
				$author = get_userdata( $course->post->post_author );
				$author_name = $author->display_name;
				$permalink = get_permalink( $course->id );
				$date_formatted = date( 'M d, Y', strtotime( $course_item->updated_date ) );
				$course_status = $course_item->meta_value;
				$course_author = '';
				if (get_option( 'lifterlms_course_display_author' ) == 'yes') {
					$course_author = sprintf( esc_html__( '<p class="author">Author: <span>%s</span></p>', 'michigan' ), $author_name );
				} ?>
					<article class="modern-grid llms-course-list">
						<div class="llms-course-link">
							<div class="modern-feature">
								<a href="<?php echo esc_url($permalink); ?>">
									<?php global $post;
										if ( has_post_thumbnail( $course->id ) ) {
											echo llms_featured_img( $course->id, 'michigan_webnus_blog2_img' );
										} elseif ( llms_placeholder_img_src() ) {
											echo llms_placeholder_img();
										}?>
									<span class="modern-duration"><?php echo esc_html__( 'Status: ','michigan' ) . apply_filters('lifterlms_my_courses_enrollment_status_html', $course_status); ?> <i class="fa-info"></i></span>
								</a>
							</div>
							<div class="modern-content">
								<h3 class="llms-title">
									<a href="<?php echo $permalink; ?>"><?php echo $course->post->post_title; ?></a>
								</h3>
								<?php 
								if(function_exists('the_ratings')) { 
								 echo expand_ratings_template('<div class="modern-rating"><span class="rating">%RATINGS_IMAGES%</span> <strong>(%RATINGS_USERS% '.esc_html__('Votes','michigan').')</strong></div>', $course_item->post_id ); } 
								echo apply_filters('lifterlms_my_courses_start_date_html',
								'<div class="llms-start-date"><i class="fa-calendar"></i> ' .  esc_html__( 'Course Started','michigan' ) . ' - ' . $date_formatted . '</div>'); ?>
								<div class="llms-progress">
									<div class="progress__indicator"><?php printf( esc_html__( '%s%%', 'michigan' ), $course_progress ); ?></div>
									<div class="llms-progress-bar">
										<div class="progress-bar-complete" style="width:<?php echo $course_progress ?>%"></div>
									</div>
								</div>
								<div class="course-link">
								<?php echo '<a href="' . $permalink  . '" class="button llms-button colorb">' . apply_filters( 'lifterlms_my_courses_course_button_text', esc_html__( 'View Course', 'michigan' ) ) . '</a>'; ?>
								</div>
							</div>
						</div>
					</article>
			<?php
			}
		}
	}; ?>
	</div>
	<?php
} else {
	echo  '<p>' .esc_html__( 'You are not enrolled in any courses.', 'michigan' ) . '</p>';
}
	?>
</div>

<?php // My Certificates
$s = new LLMS_Student();
$certificates = $s->get_certificates();
?>
<div class="w-llms-my-certificates">
	<?php echo '<h3><i class="fa-chevron-right"></i> ' . apply_filters( 'lifterlms_my_certificates_title', esc_html__( 'My Certificates', 'michigan' ) ) . '</h3>'; ?>
	<?php if ( $certificates ) { ?>
		<ul class="listing-certificates clearfix">
		<?php foreach ( $certificates as $c ) : ?>
			<li class="certificate-item">
				<div class="col-md-6 col-sm-6">
					<h4><?php echo get_the_title( $c->certificate_id ); ?></h4>
				</div>
				<div class="col-md-3 col-sm-3 alignright">
					<p><i class="fa-calendar-check-o"></i> <?php echo date( 'F d, Y', strtotime( $c->earned_date ) ); ?></p>
				</div>
				<div class="col-md-3 col-sm-3 alignright">
					<span><a class="hcolorf" href="<?php echo get_permalink( $c->certificate_id ); ?>" target="_blank"><i class="fa-certificate"></i><?php esc_html_e( 'View Certificate','michigan' );?></a></span>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php }else{ ?>
		<?php echo  '<p>' .esc_html__( 'Complete courses and lessons to earn certificates.', 'michigan' ) . '</p>'; ?>
	<?php } ?>
</div>

<?php // My Achievements
$user = new LLMS_Person;
$count = ( empty( $count ) ) ? 1000 : $count; // shortcodes will define $count and load the contents of this template
$user_id = ( empty( $user_id ) ) ? get_current_user_id() : $user_id;
$achievements = $user->get_user_achievements( $count, $user_id );
?>
<div class="w-llms-my-achievements">
	<h3 class="llms-my-achievements-title"><i class="fa-chevron-right"></i> <?php echo apply_filters( 'lifterlms_my_achievements_title', esc_html__( 'My Achievements', 'michigan' ) ); ?></h3>
	<?php if ($achievements){ ?>
	<?php do_action( 'lifterlms_before_achievements' ); ?>
		<div class="listing-achievements">
			<div class="row">
			<?php foreach ( $achievements as $achievement ) : ?>
				<div class="col-md-4 achievement-item">
					<div class="achievement-content">
						<?php do_action( 'lifterlms_before_achievement', $achievement ); ?>
						<div class="llms-achievement-image"><img alt="<?php echo esc_attr( $achievement['title'] ); ?>" src="<?php echo $achievement['image']; ?>"></div>
						<h4 class="llms-achievement-title"><?php echo $achievement['title']; ?></h4>
						<?php if ( $achievement['content'] ) : ?>
							<div class="llms-achievement-content"><p><?php echo $achievement['content']; ?></p></div>
						<?php endif; ?>
						<div class="llms-achievement-date"><p><i class="fa-calendar-check-o"></i> <?php echo $achievement['date']; ?></p></div>
						<?php do_action( 'lifterlms_after_achievement', $achievement ); ?>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	<?php }else{ ?>
		<p><?php echo apply_filters( 'lifterlms_no_achievements_text', esc_html__( 'Complete courses and lessons to earn achievements.', 'michigan' ) ); ?></p>
	<?php } ?>
	<?php do_action( 'lifterlms_after_achievements' ); ?>
</div>

<?php if ( get_option( 'lifterlms_enable_myaccount_memberships_list', 'no' ) === 'yes' ) : ?>
	<?php // My Memberships
	$person = new LLMS_Person;
	$memberships = $person->get_user_memberships_data( get_current_user_id(), '_status' );
	?>
	<div class="w-llms-my-memberships">
		<h3><i class="fa-chevron-right"></i> <?php echo apply_filters( 'lifterlms_my_memberships_title', esc_html__( 'My Memberships', 'michigan' ) ); ?></h3>
		<?php do_action( 'lifterlms_before_my_memberships' ); ?>
		<?php if ( $memberships ) : ?>
			<ul class="listing-memberships clearfix">
			<?php foreach ( $memberships as $mid => $data ) : ?>
				<?php $m = get_post( $mid ); ?>
				<li class="membership-item col-md-3">
					<div class="w-membership-item">
						<strong><?php echo $m->post_title; ?></strong><br>
						<?php echo sprintf('<i class="fa-calendar-plus-o"></i> '.esc_html__( 'Enrolled: %s', 'lifterlms' ), LLMS_Date::pretty_date( $data['_start_date']->updated_date ) ); ?><br>
						<?php echo sprintf('<i class="fa-info"></i> '.esc_html__('Status: %s', 'lifterlms' ), $data['_status']->meta_value ); ?>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php echo esc_html__( 'You are not currently enrolled in any memberships.', 'michigan' ); ?></p>
		<?php endif; ?>
		<?php do_action( 'lifterlms_after_my_memberships' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'lifterlms_after_my_account' ); ?>