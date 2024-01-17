<?php
/******************/
/**  Single Course
/******************/
get_header();

$llms_product = new LLMS_Product( $post->ID );
$course_features = michigan_webnus_options::michigan_webnus_course_features();
?>
<section class="container page-content">
<hr class="vertical-space2">
<?php 
	if(michigan_webnus_options::michigan_webnus_enable_breadcrumbs() ) {
		//Breadcrumb
		$homeLink = esc_url(home_url('/'));
		$post_type = get_post_type_object(get_post_type());
		$slug = $post_type->rewrite;
		echo '<div class="breadcrumbs-w"><div class="container"><div id="crumbs"><a href="'.$homeLink.'">'.esc_html__('Home','michigan').'</a> <i class="fa-angle-right"></i><a href="' . $homeLink .  $post_type->rewrite['slug'] . '/">' . $post_type->labels->name . '</a> <i class="fa-angle-right"></i> <span class="current">'.get_the_title().'</span></div></div></div>';
	} 
?>
<div class="course-main">
<?php if( have_posts() ): while( have_posts() ): the_post();  ?>
<div class="col-md-12 post-trait-w"> 
	<h1 class="post-title-ps1"><?php the_title() ?></h1>
	<?php //Course Price
		if(isset($course_features['price'])){
			echo '<div class="w-course-price">';
			$price_text = $llms_product->get_single_price_html();
			echo($price_text)?$price_text:esc_html__('FREE','michigan');
			echo '</div>';
		}
	?>
</div>
<section class="col-md-9 course-content cntt-w">
<article class="course-single-post">
<?php
	michigan_webnus_setViews(get_the_ID());
	$content = get_the_content(); ?>
<div <?php post_class('post'); ?>>				
				
				
<?php // content
	echo apply_filters('the_content',$content); 
?>
<?php // social share
	if(isset($course_features['sharing'])) { ?>	
	<div class="post-sharing"><div class="blog-social">
		<span><?php esc_html_e('Share','michigan');?>: </span> 
		<a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" target="blank"><i class="fa-facebook"></i></a>
		<a class="google" href="https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=<?php the_permalink(); ?>" target="_blank"><i class="fa-google"></i></a>
		<a class="twitter" href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;tw_p=tweetbutton&amp;url=<?php the_permalink(); ?><?php echo isset( $twitter_user ) ? '&amp;via='.$twitter_user : ''; ?>" target="_blank"><i class="fa-twitter"></i></a>
		<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;source=<?php bloginfo( 'name' ); ?>"><i class="fa-linkedin"></i></a>
		<a class="email" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><i class="fa-envelope"></i></a>
	</div></div>
<?php } ?>

</div>

<?php 
$course_review= new LLMS_Reviews();
add_filter( 'webnus_course_after', array(  $course_review , 'output' ),30 );
do_action( 'webnus_course_after' );
?>

</article>
<?php 
	endwhile;
	endif;
if(isset($course_features['comment'])){
	comments_template();
} ?>
</section>
<div class="col-md-3 sidebar">
	<?php
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	global $post, $product;
	if ( ! $product ) {$product = new LLMS_Product( $post->ID );}
	$single_price = $product->get_single_price();
	$rec_price = $product->get_recurring_price();
	$memberships_required = get_post_meta( $product->id, '_llms_restricted_levels', true );
	?>
	<div class="llms-purchase-link-wrapper">
	<?php
	if ( ! is_user_logged_in() ) {
		$message = apply_filters( 'lifterlms_checkout_message', '' );
		if ( ! empty( $message ) ) {
		}
		//if membership required to take course
		if ($memberships_required) {
			//if there is more than 1 membership that can view the content then redirect to memberships page
			if (count( $memberships_required ) > 1) {
				$membership_url = get_permalink( llms_get_page_id( 'memberships' ) );
			} //if only 1 membership level is assigned take visitor to the membership page
			else {
				$membership_url = get_permalink( $memberships_required[0] );
			}
			?>
			<a href="<?php echo $membership_url; ?>" class="button llms-button llms-purchase-button"><?php echo esc_html_e( 'Sign Up', 'michigan' ); ?></a>	
		<?php
		} else {
			$account_url = get_permalink( llms_get_page_id( 'myaccount' ) );
			$account_redirect = add_query_arg( 'product-id', get_the_ID(), $account_url );
			?>
			<a href="<?php echo $account_redirect; ?>" class="button llms-button llms-purchase-button"><?php echo esc_html_e( 'Sign Up', 'michigan' ); ?></a>
		<?php
		}
	} elseif ( ! llms_is_user_member( get_current_user_id(), $product->id ) ) {
		if ( $single_price > 0 || $rec_price > 0) {
			?>
			<a href="<?php echo $product->get_checkout_url(); ?>" class="button llms-button llms-purchase-button"><?php echo esc_html_e( 'Sign Up', 'michigan' ); ?></a>
			<?php
		} else { ?>
				<form action="" method="post">
					<input type="hidden" name="payment_option" value="none_0" />
					<input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
					<input type="hidden" name="product_price" value="<?php echo $product->get_price(); ?>" />
					<input type="hidden" name="product_sku" value="<?php echo $product->get_sku(); ?>" />
					<input type="hidden" name="product_title" value="<?php echo $post->post_title; ?>" />
					<input id="payment_method_<?php echo 'none' ?>" type="hidden" name="payment_method" value="none" <?php //checked( $gateway->chosen, true ); ?> />
					<p><input type="submit" class="button llms-button llms-purchase-button" name="create_order_details" value="<?php esc_html_e( 'Sign Up', 'michigan' ); ?>" /></p>
					<?php wp_nonce_field( 'create_order_details' ); ?>
					<input type="hidden" name="action" value="create_order_details" />
				</form>
		<?php }
	}
	?>
	</div>

	<aside class="course-bar">
		
		<?php
		if(is_active_sidebar('membership-sidebar')) dynamic_sidebar( 'Membership Sidebar' );
		?>
		
	</aside>
</div>
</div>
<!-- end-main-conten -->

<div class="white-space"></div>
</section>
<?php 
	get_footer();
?>