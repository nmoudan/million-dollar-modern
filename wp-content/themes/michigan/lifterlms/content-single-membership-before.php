<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
llms_print_notices();
global $post;
?>
<div class="post-thumbnail">
	<h1 class="llms-featured-image">
		<?php
		if ( has_post_thumbnail( $post->ID ) ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'michigan_webnus_latest_img' );
			echo apply_filters( 'lifterlms_featured_img', '<img src="' . $img[0] . '" alt="Placeholder" class="llms-course-image llms-featured-imaged wp-post-image" />' );
		} elseif ( llms_placeholder_img_src() ) {
			$no_img = get_template_directory_uri().'/images/course_no_image.png';
			echo apply_filters( 'lifterlms_placeholder_img', '<img src="' . $no_img . '" alt="Placeholder" class="llms-course-image llms-placeholder wp-post-image" />' );
		}
		?>
	</h1>
</div>
<?php $llms_product = new LLMS_Product( $post->ID ); ?>
<div class="llms-price-wrapper">
<?php
foreach ( $llms_product->get_payment_options() as $option ) :
	do_action( 'lifterlms_product_payment_option_'.$option, $llms_product );
endforeach;
?>
</div>