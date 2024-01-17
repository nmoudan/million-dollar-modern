<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$product_id = get_query_var( 'product-id' );
?>
</div></div></section>
<?php llms_print_notices(); ?>
<article class="container w-course-login-register">
<?php do_action( 'lifterlms_before_person_login_form' ); ?>
<div class="col-md-6 llms-person-login" id="person_login">
<div class="w-login-content">
	<h2><?php esc_html_e( 'Login', 'lifterlms' ); ?></h2>
	<form method="post" class="llms-person-login-form login">
		<?php do_action( 'lifterlms_login_form_start' ); ?>
		<input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>" />
		<p>
			<label for="username"><?php esc_html_e( 'Username or email address', 'lifterlms' ); ?> <span class="required">*</span></label>
			<input type="text" class="input-text llms-input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) { echo esc_attr( $_POST['username'] ); } ?>" />
		</p>
		<p>
			<label for="password"><?php esc_html_e( 'Password', 'lifterlms' ); ?> <span class="required">*</span></label>
			<input class="input-text llms-input-text" type="password" name="password" id="password" />
		</p>
		<?php do_action( 'lifterlms_login_form' ); ?>
		<p>
			<?php wp_nonce_field( 'lifterlms-login' ); ?>
			<input type="submit" class="button colorb" name="login" value="<?php esc_html_e( 'Login', 'lifterlms' ); ?>" />
			<label for="rememberme" class="inline llms-rememberme-link">
				<input class="llms-rememberme-link" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'lifterlms' ); ?>
			</label>
		</p>
		<div class="llms-lost-password-link">
			<a class="colorf" href="<?php echo esc_url( llms_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'lifterlms' ); ?></a>
		</div>
		<?php do_action( 'lifterlms_login_form_end' ); ?>
	</form>
</div>
</div>
<?php do_action( 'lifterlms_after_person_login_form' ); ?>