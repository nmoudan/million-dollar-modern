<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php llms_print_notices(); ?>
</section>
<section id="headline" class="my-courses" style="">
    <div class="container">
    <h2 style="">
	<?php 
	if ( 'lost_password' == $args['form'] ) {
	  echo esc_html__( 'Lost your password?', 'michigan' );
	  echo '</h2>';
	  echo '<span>'.esc_html_e('Enter your email address and we will send you a link to reset it.','michigan').'</span>';
	}else{ 
	  echo esc_html__( 'Enter a new password below.', 'michigan' );
	  echo '</h2>';
	} 
	?>
    </div>
 </section>
<section id="main-content" class="container">
<form method="post" class="lost_reset_password">
	<?php if ( 'lost_password' == $args['form'] ) { ?>
        <label for="user_login"><?php esc_html_e( 'Username or email', 'michigan' ); ?></label> 
        <input class="input-text llms-input-text" type="text" name="user_login" id="user_login" />
	<?php }else{ ?>
            <label for="password_1"><?php esc_html_e( 'New password', 'michigan' ); ?> <span class="required">*</span></label>
            <input type="password" class="input-text llms-input-text" name="password_1" id="password_1" />
			
            <label for="password_2"><?php esc_html_e( 'Re-enter new password', 'michigan' ); ?> <span class="required">*</span></label>
            <input type="password" class="input-text llms-input-text" name="password_2" id="password_2" />
        <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
        <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />
    <?php } ?>
    <div class="clear"></div>
    <input type="submit" class="button colorb" name="llms_reset_password" value="<?php echo 'lost_password' == $args['form'] ? esc_html__( 'Reset Password', 'michigan' ) : esc_html__( 'Save', 'michigan' ); ?>" />
	<?php wp_nonce_field( $args['form'] ); ?>

</form>
