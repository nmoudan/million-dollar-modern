<?php 

/**
 * Pre-traitement du nom de fichier avant stockage dans la librairie medias
 * 
 * @param string $text : nom du fichier en entree
 * @return string : nom de fichier sans accent
 * 
 */


function localfunc_replace_accents ($text) {
	$text = remove_accents ( $text );
	$text = str_replace ( ' ', '_', $text );
	
	// replace all non letters or digits by -
	$text = preg_replace('/\W+/', '-', $text);

	return trim( $text, '-' );
}

/**
 * On force les tailles d'images intermediaires a null afin d'invalider la creation des
 * images supplementaires
 *
 * @param  $sizes, tableau des differentes tailles d'images intermediaires
 * @return $sizes, le tableau avec les tailles mises a jour
 */
function my_no_intermediate_image_sizes( $sizes) {
	foreach ( get_intermediate_image_sizes() as $s ) {
		$sizes[$s] = array( 'width' => null, 'height' => null, 'crop' => FALSE );
	}
	return $sizes;
}

/**
 * Selectionne le repertoire de stockage des fichiers uploades en fonction du type de fichier
 *
 * @param  $upload: objet referencant les parametres de l'upload
 * @return $upload: le meme objet avec le repertoire d'upload mis a jour
 */
function my_choose_upload_dir($upload) {
	if (count ($_FILES) == 0)
		return $upload;

    $file_type = $_FILES['async-upload']['type'];
    $file_name = $_FILES['async-upload']['name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Selectionne le repertoire de sortie selon le type de fichier
    switch($file_type) {
        case 'image/bmp':
        case 'image/gif':
        case 'image/jpeg':
        case 'image/png':
            $type_dir = '/images';
        break;

	case 'application/pdf':
	    $type_dir = '/ressources';
        break;
		
	case 'video/quicktime':
	case 'video/mp4':
            $type_dir = '/videos';
        break;
		
        default:
	    // Si on ne peut pas determiner le mime type, on essaye avec l'extension du fichier
	    switch($file_ext) {
		case 'bmp':
		case 'gif':
		case 'jpeg':
		case 'jpg':
		case 'png':
		    $type_dir = '/images';
		break;
	
		case 'pdf':
		    $type_dir = '/ressources';
		break;
			
		case 'mov':
		case 'mp4':
		    $type_dir = '/videos';
		break;
	
		default:
		$type_dir = '';
	    }
    }

	// Met a jour le repertoire d'upload du fichier avant la copie
    $upload['subdir'] = $upload['subdir'].$type_dir;
    $upload['path']   = $upload['basedir'].$upload['subdir'];
    $upload['url']    = $upload['baseurl'].$upload['subdir'];

    return $upload;
}

/**
 * Adapte le panneau de controle de l'admin
 *
 */
function my_customize_admin() {
	/* Removes meta boxes from Posts */
	remove_meta_box ( 'formatdiv',                'post', 'normal' );
	remove_meta_box ( 'trackbacksdiv',            'post', 'normal' );
	remove_meta_box ( 'commentstatusdiv',         'post', 'normal' );
	remove_meta_box ( 'commentsdiv',              'post', 'normal' );
	remove_meta_box ( 'tagsdiv-post_tag',         'post', 'normal' );
	remove_meta_box ( 'postexcerpt',              'post', 'normal' );
	
	/* Removes meta boxes from pages */
	remove_meta_box ( 'formatdiv',                'page', 'normal' );
	remove_meta_box ( 'trackbacksdiv',            'page', 'normal' );
	remove_meta_box ( 'commentstatusdiv',         'page', 'normal' );
	remove_meta_box ( 'commentsdiv',              'page', 'normal' );

	// Non admin actions
	if (! current_user_can ( 'manage_options' )) {
		remove_meta_box ('postcustom',			  'post', 'normal');
		remove_meta_box ('postcustom',			  'page', 'normal');
		remove_meta_box ( 'authordiv',            'post', 'normal' );
		remove_meta_box ( 'authordiv',            'page', 'normal' );
		remove_action   ( 'add_meta_boxes',       'graphene_add_meta_box' );
	}
	
	/* Disable auto-draft */
	remove_action   ('pre_post_update',      'wp_save_post_revision');
}

/**
 * Gere les noms de fichiers avec espaces et accents
 */
function my_handle_upload_prefilter ($file) {
	global $blog_id;
	$filename  =  $file['name'];
	$extension = strrchr($filename, '.');
	$filename  =  basename($filename, $extension);
	$filename  = localfunc_replace_accents($filename);
	$filename  = $filename . $extension;
	$file['name'] = $filename;
	return $file;
}

/**
 * Pre-traitement du media avant insertion dans un post / page
 * 
 * @param string $html : code HTML du media a inserer
 * @param string $send_id
 * @return string : code HTML du media a inserer apres modification
 * 
 */
function my_parse_media_before_insert ( $html, $send_id, $attachment ) {
	// Retirer la racine du site pour ne garder que l'URL relative a la racine
	$html = str_replace( get_bloginfo( 'url' ), '', $html );
	
	// Retirer les parametres width et height initiaux (on prend l'image a sa taille reelle)
	$html = preg_replace( '/ width="[0-9][0-9]*"/',  '', $html );
	$html = preg_replace( '/ height="[0-9][0-9]*"/', '', $html );
	
	// Retirer les parametres size-* et wp-image-* initiaux (on garde l'alignement si il est different de alignnone)
	$html = preg_replace( '/ size-[a-z]*/',       '', $html );
	$html = preg_replace( '/ wp-image-[0-9]*/',   '', $html );
	$html = preg_replace( '/ class="alignnone"/', '', $html );
	
	return $html;
}





/**
 * On traite les classes des elements de menu apres le chargement de la page
 */
function my_final_treatment() {
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		// On supprime des liens pour les sous-menus dont lla classe contient 'menu-item-no-link'
		jQuery('.menu-item-no-link').find('a:first').removeAttr('href').css('cursor', 'default');
		// On change aussi l'attribut target si la classe contient 'menu-item-new-window';
		jQuery('.menu-item-new-window').find('a:first').attr('target', '_blank');
		// Et enfin on cache les elements de menu si la classe contient 'menu-item-no-display';
		jQuery('.menu-item-no-display').hide();
	});
	
</script>
<?php
}

/**
 * Ajout des css customs
 */
function my_custom_stylesheets() {
	// On charge la feuille de style commune
	wp_enqueue_style ( 'style-commun-css', get_stylesheet_directory_uri () . '/style.css' );
	// On charge la feuille de style specifique au blog
	// wp_enqueue_style ( 'style-custom-css', get_stylesheet_directory_uri () . '/custom/' . get_option( 'blogdescription' ) . '/style.css' );
}

/**
 * Ajout des css customs - admin
 */
function my_custom_stylesheets_admin() {
	// Créer une feuille de styles CSS pour le back office (admin) de WordPress
	wp_enqueue_style ( 'admin_css',        get_stylesheet_directory_uri () . '/admin.css' );
}

function my_custom_setup() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
	// Add translation
	load_child_theme_textdomain ('michigan', get_stylesheet_directory() . '/lang' );
	
}

/**
 * Affiche la liste des sous-sites (pour tout utilisateur connecte)
 */
function my_list_of_subsites() {
	if (is_user_logged_in()) {
?>
		<div class="scrollbox form-control">
			<ul>
<?php
		$args = array(
				'network_id' => null,
				'public'     => null,
				'archived'   => null,
				'mature'     => null,
				'spam'       => null,
				'deleted'    => null,
				'limit'      => 0,
				'offset'     => 0,
		);
		$list_sites = wp_get_sites( $args );
		foreach ($list_sites as $i => $site) {
			if ( ($site[ 'public' ]  == '1') &&
				 ($site[ 'deleted' ] == '0') &&
				 ($i != 0) ) {
			 	
			 	switch_to_blog( $site[ 'blog_id' ] );
?>
	 			<li><a href="//<?=$site[ 'domain' ]?><?=$site[ 'path' ]?>"><?=get_bloginfo()?></a><hr/></li>
<?php
				restore_current_blog();
			 }
		}
?>
			</ul>
		</div>
<?php
	}
}

function my_link_to_preauth_zimbra () {
	if (is_user_logged_in()) {
		/**
		 * 
		 * Globals. Can be stored in external config.inc.php or retreived from a DB.
		 */
		$PREAUTH_KEY="002dd25877f41c99954970e7a38d476187456e0f42b4c7090684976c8b125eee";
		$WEB_MAIL_PREAUTH_URL="https://courriel.csf.bc.ca/service/preauth";
		
		/**
		 * User's email address. In this example obtained from a WP query.
		 */
		$user  = wp_get_current_user();
		$email = $user->user_email;
		
		/**
		 * Create preauth token and preauth URL
		 */
		$timestamp=time()*1000;
		$preauthToken=hash_hmac("sha1",$email."|name|0|".$timestamp,$PREAUTH_KEY);
		$preauthURL = $WEB_MAIL_PREAUTH_URL."?account=".$email."&by=name&timestamp=".$timestamp."&expires=0&preauth=".$preauthToken;
		
		/**
		 * Output Zimbra preauth URL
		 */
		echo ($preauthURL);
	}
}

/**
 * Determine si le user_email passe en parametre peut etre utilise sur l'intranet ou pas
 *
 * @param string $user_email
 * @return boolean : autorise ou pas
 */
function isAuthorizedLogin ($user_email) {
	$authorizedLogin = true;
	$user_email = strtolower($user_email);
	if ( ( (strlen ($user_email) === 19) &&
			(preg_match ('/^[a-z][a-z][a-z][0-9][0-9][a-z][a-z][a-z][a-z]$/', substr($user_email, 0, 9))) ) ||
		 (preg_match ('/^[a-z][a-z][a-z][\-]/', $user_email)) ) {
	 	switch (substr ($user_email, 0, 3)) {
	 		case 'aas':
	 		case 'aig':
	 		case 'brk':
	 		case 'bro':
	 		case 'car':
	 		case 'cdi':
	 		case 'cdo':
	 		case 'cha':
	 		case 'csf':
	 		case 'duc':
	 		case 'eah':
	 		case 'eap':
	 		case 'ebj':
	 		case 'ecs':
	 		case 'edg':
	 		case 'edp':
	 		case 'edr':
	 		case 'eel':
	 		case 'efn':
	 		case 'egc':
	 		case 'egr':
	 		case 'ejc':
	 		case 'ejv':
	 		case 'emm':
	 		case 'epa':
	 		case 'esa':
	 		case 'esm':
	 		case 'ess':
	 		case 'fda':
	 		case 'fdp':
	 		case 'jce':
	 		case 'kam':
	 		case 'nav':
	 		case 'nds':
	 		case 'oce':
	 		case 'pas':
	 		case 'pem':
	 		case 'pen':
	 		case 'phx':
	 		case 'pio':
	 		case 'rdv':
	 		case 'ver':
	 		case 'vir':
	 		case 'voy':

				if (substr ($user_email, 4, 9) != 'stagiaire') {
					$authorizedLogin = false;
				}
	 			break;

	 		default:
	 			break;
	 	}
	}

	if (  (strpos ($user_email, 'student@') !== false) ||
		  (strpos ($user_email, 'eleve@')   !== false)
	) {
		$authorizedLogin = false;
	}
	return $authorizedLogin;
}



/**
 * Supprime certains elements de la barre admin
 */
function my_tweaked_admin_bar() {
	global $wp_admin_bar;
	// Remove the WordPress logo...
	if (! current_user_can ( 'ure_manage_options' )) {
		// Not Admin ? No domain access to other schools admin...
		$wp_admin_bar->remove_menu ( 'my-sites' );
		$wp_admin_bar->remove_menu ( 'wpseo-menu' );
		$wp_admin_bar->remove_menu ( 'tribe-events' );
		$wp_admin_bar->remove_menu ( 'comments' );
	}
	else {
			$wp_admin_bar->remove_menu ( 'wpseo-menu' );
			$wp_admin_bar->remove_menu ( 'tribe-events' );
			$wp_admin_bar->remove_menu ( 'comments' );
		 }
}


/**
 * Supprime certains menus admin
 */
function my_remove_menu_items() {
	global $menu;
	global $submenu;
	
	unset ( $submenu ['index.php'] [5] ); // My-sites
	
	if (is_super_admin ()) {
		$menu_to_remove = array (
				/* __ ( 'Links' ),
				__ ( 'Comments' ) */
		);
	} else {
		if (! current_user_can ( 'add_users' )) {
			// No user management access...
			$menu_to_remove = array (
					__ ( 'post_types' ),
					__ ( 'Comments' ),
					__ ( 'Courses' ),
					__ ( 'Plugins' ),
					__ ( 'Tools' ),
					__ ( 'Links' ),
					/* __ ( 'Links' ),
					__ ( 'Settings' ),
					__ ( 'Plugins' ),
					__ ( 'Profile' ),
					__ ( 'Tools' ),
					__ ( 'Users' ) */
			);
			remove_menu_page('vc-welcome');
    		remove_menu_page('edit.php?post_type=essential_grid');
    		remove_menu_page('edit.php?post_type=tribe_events');
    		remove_menu_page('edit.php?post_type=wb-tt');
		} else {
			$menu_to_remove = array (
					__ ( 'tribe_events' ),
					__ ( 'Comments' )
					/* __ ( 'Links' ),
					__ ( 'Comments' ),
					__ ( 'Settings' ),
					__ ( 'Plugins' ),
					__ ( 'Profile' ),
					__ ( 'Tools' ) */
			);
		}
	}
	end ( $menu );
	while ( prev ( $menu ) ) {
		$value = explode ( ' ', $menu [key ( $menu )] [0] );
		if (in_array ( $value [0] != NULL ? $value [0] : "", $menu_to_remove )) {
			unset ( $menu [key ( $menu )] );
		}
	}
}



/**
 * Filtre d'ajouter du php a visual composer en forme de php
 */

function shortcode_getip( $atts ){
    $ip = getenv("HTTP_X_FORWARDED_FOR"); 
    return ('<h6 style="font-size: 13px;margin-bottom: 0px;color: #faa61a;font-family: Hind, Montserrat, serif;font-weight: bold;">'.$ip.'</h6>');
 }
 add_shortcode( 'getip', 'shortcode_getip' );


/**
 * Filtre afin d'empecher les eleves de se connecter
 */
function my_check_login($user, $username, $password) {
	if (!empty($username)) {
		$user_data = $user->data;
	
		if(! isAuthorizedLogin ($user_data->user_email)) {
			wp_delete_user( $user->ID );
			// stop login
			return new WP_Error( 'Erreur', "Cette cat&eacute;gorie d'utilisateur n'est pas autoris&eacute;e" );
		} else {
			return $user;
		}
	}
	return $user;
}

add_filter ('authenticate',                      'my_check_login', 30, 3);

// La barre d'admin n'est visible que pour les administrateurs
add_action ('after_setup_theme',                 'my_custom_setup');

// Ajout des feuilles de styles de chaque blog
add_action ('wp_print_styles',                   'my_custom_stylesheets' );

// Ajout de feuille de styles du admin
add_action ('admin_print_styles',                'my_custom_stylesheets_admin', 11 );

// Ajout de traitement final apres le chargement de la page
add_action ('wp_footer',                         'my_final_treatment', 100 );

/**
* @ conditional js (only loads on homepage)
*/
function my_enqueue_stuff() {
  if ( is_page( 'technologie' ) ) {
    wp_enqueue_script( 'technologie', get_stylesheet_directory_uri() . '/js/homepage.js', array ( 'jquery' ));
  } else {
    /** Call regular enqueue */
  }
}

// Tout ce qui concerne les medias
add_filter ('intermediate_image_sizes_advanced', 'my_no_intermediate_image_sizes');
add_filter ('upload_dir',                        'my_choose_upload_dir');
// Gestion avant upload des fichiers
add_filter ('wp_handle_upload_prefilter',        'my_handle_upload_prefilter');
// Pre-traitement du media a inserer dans un post / page
add_filter ('media_send_to_editor',              'my_parse_media_before_insert', 10, 3 );

add_action ('wp_print_scripts',                  'my_enqueue_stuff');

add_action ( 'wp_before_admin_bar_render',        'my_tweaked_admin_bar' );

add_action ( 'admin_menu',                        'my_remove_menu_items' );

// Ajout des menus customs
if (function_exists("my_bp_custom_menu_item")) {
	add_filter ('wp_nav_menu_items',             'my_bp_custom_menu_item', 10, 2);
}


add_action('admin_head-nav-menus.php', 'wpclean_add_metabox_menu_posttype_archive');

function wpclean_add_metabox_menu_posttype_archive() {
add_meta_box('wpclean-metabox-nav-menu-posttype', 'Custom Post Type Archives', 'wpclean_metabox_menu_posttype_archive', 'nav-menus', 'side', 'default');
}
// First we create a function
function list_terms_custom_taxonomy( $atts ) {

// Inside the function we extract custom taxonomy parameter of our shortcode

 extract( shortcode_atts( array(
  'custom_taxonomy' => '',
 ), $atts ) );

// arguments for function wp_list_categories
$args = array( 
taxonomy => $custom_taxonomy,
title_li => ''
);

// We wrap it in unordered list 
echo '<ul>'; 
echo wp_list_categories($args);
echo '</ul>';
}

// Add a shortcode that executes our function
add_shortcode( 'ct_terms', 'list_terms_custom_taxonomy' );

//Allow Text widgets to execute shortcodes

add_filter('widget_text', 'do_shortcode');

function project_dequeue_unnecessary_scripts() {
     wp_register_script( 'jquery', 'https://intranet.csf.bc.ca/wp-includes/js/jquery/jquery.js?ver=1.12.4', '1.2');
     wp_enqueue_script( 'jquery' );
}
add_action('init', 'project_dequeue_unnecessary_scripts');
add_action( 'wp_print_scripts', 'project_dequeue_unnecessary_scripts' );


function michigan_webnus_topbar($pos){
	$class=($pos=='left')?'lftflot':'rgtflot';
	echo '<div class="top-links '.$class.'">';
	if(michigan_webnus_options::michigan_webnus_topbar_search()==$pos){
		echo '<form id="topbar-search" role="search" action="'.esc_url(home_url( '/' )).'" method="get" ><input name="s" type="text" class="search-text-box" ><i class="search-icon fa-search"></i></form>';
	}
	if (michigan_webnus_options::michigan_webnus_topbar_social()==$pos){
		echo '<div class="socialfollow">';
		get_template_part('parts/social' );
		echo '</div>';
	}
	if (michigan_webnus_options::michigan_webnus_topbar_login()==$pos){
		if(is_user_logged_in()) { //show user menu
			global $user_identity;
			global $user_ID;
			$user_info = get_userdata($user_ID);
			echo '<div class="hcolorf wuser-menu">'.esc_html__('welcome ','michigan') . ' ' . esc_html($user_identity).'<span class="wuser-avatar">'.get_avatar( $user_ID, $size = '36').'</span><div class="wuser-smenu">';
			if(current_user_can('manage_options')){ //admin
				echo '<a href="'.admin_url().'">'.esc_html__( 'WP Admin', 'michigan' ).'</a>';
			}/* elseif(current_user_can('edit_posts')){ //instructor
				echo '<a href="'.admin_url().'edit.php?post_type=course">'.esc_html__( 'Manage Courses', 'michigan' ).'</a>';
			}
			echo '<a href="'.get_author_posts_url($user_ID).'">'.esc_html__( 'My Profile', 'michigan' ).'</a>'; */
			if (is_plugin_active('lifterlms/lifterlms.php')) { //LifterLMS
				echo '<a href="'.get_permalink( llms_get_page_id('myaccount')).'">'.esc_html__( 'My Dashboard', 'michigan' ).'</a>';
				
			}elseif (is_plugin_active('buddypress/bp-loader.php')){ //Buddypress
				echo '<a href="'. bp_loggedin_user_domain().'">'.esc_html__( 'My Dashboard', 'michigan' ).'</a>';
			}
			
			if (is_plugin_active('buddypress/bp-loader.php')) { //Buddypress
				echo '<a href="'. bp_loggedin_user_domain().'profile/account-setting/">'.esc_html__( 'Edit Profile', 'michigan' ).'</a>';
			}elseif(is_plugin_active('lifterlms/lifterlms.php')) { //LifterLMS
				echo '<a href="'.get_permalink( llms_get_page_id('myaccount')).get_option( 'lifterlms_myaccount_edit_account_endpoint', 'edit-account' ).'">'.esc_html__( 'Edit Profile', 'michigan' ).'</a>';
			}
			
			if (is_plugin_active('lifterlms/lifterlms.php')) { //LifterLMS
				echo '<a href="'.llms_person_redeem_voucher_url().'">'.esc_html__( 'Redeem a Voucher', 'michigan' ).'</a>';
			}
			
			echo	'<a href="'.wp_logout_url(get_permalink()).'">'.esc_html__( 'Sign out', 'michigan' ).'</a></div></div>';	
		}else{ //show login modal
			
		/* login button */
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$login_class =(is_plugin_active('userpro/index.php'))? 'popup-login':'inlinelb';
		$login_text = michigan_webnus_options::michigan_webnus_topbar_login_text();
		echo '<a href="#w-login" class="'.$login_class.' topbar-login" target="_self">'.esc_html($login_text).'</a>';
		
		/* modal form colorskin */
		$colorskin=(get_theme_mod( 'enable_custom_colorskin' ) OR get_theme_mod( 'predefined_colorskin' ) OR michigan_webnus_options::michigan_webnus_color_skin() != 'none' )?' colorskin-custom ':'';
		
		/* modal form */
		$form_class=(michigan_webnus_options::michigan_webnus_template_select())? ' '.michigan_webnus_options::michigan_webnus_template_select().'-t ':'';
		echo '<div style="display:none"><div id="w-login" class="w-login w-modal'.$colorskin.$form_class.'">';
		echo '<h3 class="modal-title">'.esc_html__('LOGIN','michigan').'</h3>';
		if ( function_exists( 'michigan_webnus_login' ) ) :
			michigan_webnus_login();
		endif;
		echo '</div></div>';
		}
	}
	
	if(michigan_webnus_options::michigan_webnus_topbar_contact()==$pos){ 
		$contact_text = michigan_webnus_options::michigan_webnus_topbar_contact_text();
		echo'<a class="inlinelb topbar-contact" href="#w-contact" target="_self">'.esc_html($contact_text).'</a>';
	}
	
	if (michigan_webnus_options::michigan_webnus_topbar_info()==$pos){	
		echo (michigan_webnus_options::michigan_webnus_topbar_email())?'<h6><i class="fa-envelope-o"></i>'. esc_html(michigan_webnus_options::michigan_webnus_topbar_email()) .'</h6>':'';
		echo (michigan_webnus_options::michigan_webnus_topbar_phone())?'<h6><i class="fa-phone"></i>'. esc_html(michigan_webnus_options::michigan_webnus_topbar_phone()).'</h6>':'';
	}
	
	if (michigan_webnus_options::michigan_webnus_topbar_menu()==$pos && has_nav_menu('header-top-menu')){
		if(michigan_webnus_options::michigan_webnus_header_menu_type()==0){
			$menuParameters = array('theme_location' => 'header-top-menu','container' => 'false','menu_id' => 'nav','depth' => '5','items_wrap' => '<ul id="%1$s">%3$s</ul>',  'walker' => new michigan_webnus_description_walker(),);
		}else{
			$menuParameters = array('theme_location' => 'header-top-menu','container' => 'false', 'depth' => '1', 'echo'  => false,  'walker' => new michigan_webnus_description_walker(),);
		}
		echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
	}

	if (michigan_webnus_options::michigan_webnus_topbar_custom()==$pos){	
		echo esc_html(michigan_webnus_options::michigan_webnus_topbar_text());
	}
	
	if (michigan_webnus_options::michigan_webnus_topbar_language()==$pos){				
		do_action('icl_language_selector');
	}
	echo'</div>';
}

// function create a dropdown list of tags widget (applied to course sidebar widget)

function dropdown_tag_cloud( $args = '' ) {
	$defaults = array(
		'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
		'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => ''
	);
	$args = wp_parse_args( $args, $defaults );

	$tags = get_tags( array_merge($args, array('orderby' => 'count', 'order' => 'DESC')) ); // Always query top tags

	if ( empty($tags) )
		return;

	$return = dropdown_generate_tag_cloud( $tags, $args ); // Here's where those top tags get sorted according to $args
	if ( is_wp_error( $return ) )
		return false;
	else
		echo apply_filters( 'dropdown_tag_cloud', $return, $args );
}

function dropdown_generate_tag_cloud( $tags, $args = '' ) {
	global $wp_rewrite;
	$defaults = array(
		'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
		'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC'
	);
	$args = wp_parse_args( $args, $defaults );
	extract($args);

	if ( !$tags )
		return;
	$counts = $tag_links = array();
	foreach ( (array) $tags as $tag ) {
		$counts[$tag->name] = $tag->count;
		$tag_links[$tag->name] = get_tag_link( $tag->term_id );
		if ( is_wp_error( $tag_links[$tag->name] ) )
			return $tag_links[$tag->name];
		$tag_ids[$tag->name] = $tag->term_id;
	}

	$min_count = min($counts);
	$spread = max($counts) - $min_count;
	if ( $spread <= 0 )
		$spread = 1;
	$font_spread = $largest - $smallest;
	if ( $font_spread <= 0 )
		$font_spread = 1;
	$font_step = $font_spread / $spread;

	// SQL cannot save you; this is a second (potentially different) sort on a subset of data.
	if ( 'name' == $orderby )
		uksort($counts, 'strnatcasecmp');
	else
		asort($counts);

	if ( 'DESC' == $order )
		$counts = array_reverse( $counts, true );

	$a = array();

	$rel = ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) ? ' rel="tag"' : '';

	foreach ( $counts as $tag => $count ) {
		$tag_id = $tag_ids[$tag];
		$tag_link = clean_url($tag_links[$tag]);
		$tag = str_replace(' ', '&nbsp;', wp_specialchars( $tag ));
		$a[] = "\t<option value='$tag_link'>$tag ($count)</option>";
	}

	switch ( $format ) :
	case 'array' :
		$return =& $a;
		break;
	case 'list' :
		$return = "<ul class='wp-tag-cloud'>\n\t<li>";
		$return .= join("</li>\n\t<li>", $a);
		$return .= "</li>\n</ul>\n";
		break;
	default :
		$return = join("\n", $a);
		break;
	endswitch;

	return apply_filters( 'dropdown_generate_tag_cloud', $return, $tags, $args );
}


// Link directly to Media files instead of Attachment pages in search results
function my_search_media_direct_link( $permalink, $post = null ) {
	if ( ( is_search() || doing_action( 'wp_ajax_searchwp_live_search' )
	      || doing_action( 'wp_ajax_nopriv_searchwp_live_search' ) )
	    && 'attachment' === get_post_type( $post ) ) {
		$permalink = wp_get_attachment_url( $post );
	}

	return esc_url( $permalink );
}

add_filter( 'the_permalink',   'my_search_media_direct_link', 99, 2 );
add_filter( 'attachment_link', 'my_search_media_direct_link', 99, 2 );

add_filter('doing_it_wrong_trigger_error', '__return_false');


/**
 * Enable unfiltered_html capability for Editors.
 *
 * @param  array  $caps    The user's capabilities.
 * @param  string $cap     Capability name.
 * @param  int    $user_id The user ID.
 * @return array  $caps    The user's capabilities, with 'unfiltered_html' potentially added.
 */
function km_add_unfiltered_html_capability_to_editors( $caps, $cap, $user_id ) {

	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'shop_manager' ) ) {
		$caps = [ 'unfiltered_html' ];
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'km_add_unfiltered_html_capability_to_editors', 1, 3 );

// function handle_wp_nav_menu($arg) {
//     return wp_multisite_nav_menu($arg);
// }

/*function wpa_alter_cat_links( $termlink, $term, $taxonomy ){
	
    if( 'category' != $taxonomy ) return $termlink;
    return str_replace( '/category', '', $termlink );
}
add_filter( 'term_link', 'wpa_alter_cat_links', 10, 3 );*/
/*function wpa_alter_cat_links( $termlink, $term, $taxonomy ){
	if(is_main_site()) {}else{
    if( 'category' == $taxonomy ){
    	$info_blog = get_bloginfo();
    	echo $info_blog;
        return str_replace( '/category', '', $termlink );
    }
}
    return $termlink;
}
add_filter( 'term_link', 'wpa_alter_cat_links', 10, 3 );*/
/*function wpa_alter_cat_links( $termlink, $term, $taxonomy ){
	restore_current_blog();
	global $blog_id;
    if( 'category' != $taxonomy ) return $termlink;
   $info_blog = get_blog_details();
   var_dump($info_blog);
  $path_to_do = $info_blog->path;
  $name = $info_blog->name;
  echo $name;
  $path_attach_cateory = '/category' .$path_to_do;
    return str_replace( '/category', $path_attach_cateory, $termlink );
}
add_filter( 'term_link', 'wpa_alter_cat_links', 10, 3 );*/
/*switch_to_blog(1);
$cptui_taxonomies = cptui_get_taxonomy_data();
cptui_get_taxonomy_code( $cptui_taxonomies ); 
restore_current_blog();*/