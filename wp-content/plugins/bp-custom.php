<?php 

/**
 * Ajustement des menus et sous-menus Buddypress
 */
function my_buddypress_hide_tabs() {

	if ( bp_is_user() ) {
		bp_core_remove_nav_item    ( 'notifications' );
		// bp_core_remove_subnav_item ( 'profile', 'base' );
	}
}

add_action ('bp_setup_nav','my_buddypress_hide_tabs', 15);

?>
