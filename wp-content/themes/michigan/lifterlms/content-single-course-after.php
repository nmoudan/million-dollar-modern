<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
global $post, $course;
$course_features = michigan_webnus_options::michigan_webnus_course_features();
if ( get_option( 'lifterlms_course_display_outline' ) === 'no' ) {
	return;
}

if ( ! $course || ! is_object( $course ) ) {
	$course = new LLMS_Course( $post->ID );
}

$sections = $course->get_children_sections();
?>
</div> 
<!-- close main content from before -->

<?php
// Audio
$course_not_class = get_post_custom( $post->ID );
if ($course->get_audio() ) { ?>
	<div class="llms-audio-wrapper"><div class="center-audio"><?php echo $course->get_audio(); ?></div></div>
<?php }

//Video
if ( $course->get_video() ) { ?>
	<div class="llms-video-wrapper"><div class="center-video"><?php echo $course->get_video(); ?></div></div>
<?php }
//Syllabus
?>
<div class="clear"></div>
<div class="llms-lesson-tooltip" id="lockedTooltip"></div>
<h4 class="course-titles"><?php esc_html_e('Curriculum','michigan'); ?></h4>
<div class="llms-syllabus-wrapper">
	<?php if ( ! $sections ) : ?>
		<?php echo esc_html__( 'This course does not have any sections.','michigan' ); ?>
	<?php else : ?>
		<?php foreach ( $sections as $section_child ) : ?>
			<?php $section = new LLMS_Section( $section_child->ID ); ?>
			<?php if ( get_option( 'lifterlms_course_display_outline_titles', 'yes' ) === 'yes' ) : ?>
			<?php echo '[accordion title="'.$section->post->post_title.'"]'; ?>
			<?php endif; ?>
			<?php $lessons = $section->get_children_lessons(); ?>
			<?php if ( ! $lessons ) : ?>
				<?php echo esc_html__( 'This section does not have any lessons.','michigan' ); ?>
			<?php else : ?>
				<?php foreach ( $lessons as $lesson_child ) : ?>
					<?php
					$lesson = new LLMS_Lesson( $lesson_child->ID );
					/**
					 * @todo  refactor
					 */
					//determine if lesson is complete to show complete icon
							
					if ( $lesson->is_complete() ) {
						$check = '<span class="llms-lesson-complete"><i class="fa fa-check"></i></span>';
						$complete = ' is-complete has-icon';
					} elseif ( $course->is_user_enrolled( get_current_user_id() ) && get_option( 'lifterlms_display_lesson_complete_placeholders' ) === 'yes') {
						$complete = ' has-icon';
						$check = '<span class="llms-lesson-complete-placeholder"><i class="fa fa-check"></i></span>';
					} elseif ( $lesson->get_is_free() ) {
						$check = '<span class="llms-lesson-complete-placeholder free"><i>'.esc_html__('FREE','michigan').'</i></span>';
						$complete = ' has-icon';
					} else {
						if (get_option( 'lifterlms_display_lesson_complete_placeholders' ) === 'yes'){
							$complete = ' has-icon';
							$check = '<span class="llms-lesson-complete-placeholder"><i class="fa fa-lock"></i></span>';
						}else{
							$complete = ' has-icon';
							$check = '<span class="llms-lesson-complete-placeholder"></span>';
						}
					}
					//set permalink
					$permalink = 'javascript:void(0)';
					$page_restricted = llms_page_restricted( $course->id );
					$title = '';
					$linkclass = '';

					if ( ! $page_restricted['is_restricted'] || $lesson->get_is_free()) {
						$permalink = get_permalink( $lesson->id );
						$linkclass = 'llms-lesson-link';
					} else {
						$title = esc_html__( 'Take this course to unlock this lesson','michigan' );
						$linkclass = 'llms-lesson-link';
					}
					?>
					<div class="llms-lesson-preview<?php echo $complete; ?>">
					<?php 
					$background_image_url= "";
					if ( get_option( 'lifterlms_course_display_outline_lesson_thumbnails', 'no' ) === 'yes' && get_the_post_thumbnail( $lesson->id ) ){
					$background_image_url = wp_get_attachment_url( get_post_thumbnail_id( $lesson->id ) );
					}
					$background_style = !empty($background_image_url)?"background: url('{$background_image_url}') no-repeat center center;background-size: cover;":'';
					$free_class = ($lesson->get_is_free())? 'free':'';
					?>
						<a style="<?php echo $background_style;?>" class="<?php echo $free_class .' '. $linkclass;  ?>" title = "<?php echo $title; ?>" href="<?php echo $permalink; ?>">
							<div class="lesson-overlay"></div>
							<div class="llms-lesson-information">
							<div class="container">
							<div class="col-sm-1">
								<?php echo $check; ?>
							</div>
							<div class="col-sm-6">
								<h5 class="llms-h5 llms-lesson-title"><?php echo $lesson->post->post_title; ?></h5>
							</div>
							<div class="col-sm-4">
								<span class="llms-lesson-icons">
								<?php
								if($lesson->has_content()){
									echo '<span class="lesson-tip" title="Lesson has content"><i title="" class="fa-book"></i></span>';									
								}
								if(get_post_meta( $lesson_child->ID, '_video_embed', true )){
									echo '<span class="lesson-tip" title="Lesson has video"><i title="" class="fa-play-circle"></i></span>';	
								}
								if(get_post_meta( $lesson_child->ID, '_audio_embed', true )){
									echo '<span class="lesson-tip" title="Lesson has audio"><i title="" class="fa-microphone"></i></span>';	
								}
								if($lesson->get_assigned_quiz( )){
									echo '<span class="lesson-tip" title="Assigned Quiz: '.get_the_title($lesson->get_assigned_quiz()).'"><i title="" class="fa-question-circle"></i></span>';
								}
								if($lesson->get_prerequisite()){
									echo '<span class="lesson-tip" title="Prerequisite: '.get_the_title($lesson->get_prerequisite()).'"><i title="" class="fa-lock"></i></span>';
								}
								if($lesson->get_drip_days()){
									echo '<span class="lesson-tip" title="Drip Delay: '. $lesson->get_drip_days() .' days"><i title="" class="fa-calendar"></i></span>';
								}
								?>
								</span>
							</div>
							<div class="col-sm-1">
								<span class="llms-lesson-counter"><?php echo $lesson->get_order(); ?> <?php esc_html_e( 'of' , 'michigan' ); ?> <?php echo count( $lessons ); ?></span>
							</div>
							</div>
							<div class="col-sm-12">
								<p class="llms-lesson-excerpt"><?php echo llms_get_excerpt( $lesson->id ); ?></p>
							</div>
							</div>
							
							<div class="clear"></div>
						</a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( get_option( 'lifterlms_course_display_outline_titles', 'yes' ) === 'yes' ) : ?>
			<?php echo '[/accordion]'; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="clear"></div>
</div>

</div>