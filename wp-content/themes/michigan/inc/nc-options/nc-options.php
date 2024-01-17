<?php
if (!class_exists('NHP_Options')) {
    require_once get_template_directory() . '/inc/nc-options/options/noptions.php';
}
defined('michigan') or define('michigan', 'michigan');
function add_another_section($sections) {
    $sections[] = array(
        'title' => esc_html__('A Section added by hook', 'michigan'),
        'desc' => wp_kses( __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'icon' => trailingslashit(get_template_directory_uri()) . 'options/img/glyphicons/glyphicons_062_attach.png',
        'fields' => array()
    );
    return $sections;
}
function change_framework_args($args) {
    return $args;
}
function michigan_webnus_setup_framework_options() {
    $theme_dir = get_template_directory_uri() . '/';
    $args = array();
    $theme_img_dir = $theme_dir . 'images/';
    $theme_img_bg_dir = $theme_img_dir . 'bgs/';
    $args['dev_mode'] = false;
    $args['intro_text'] = wp_kses( __('<p>webnus theme options. all about theme option which can be edited is here.</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) );
    $args['share_icons']['twitter'] = null;
    $args['share_icons']['linked_in'] = null;
    $args['show_import_export'] = true;
    $args['opt_name'] = 'michigan_webnus_options';
    $args['menu_title'] = esc_html__('Theme Options', 'michigan');
    $args['page_title'] = esc_html__('Theme Options', 'michigan');
    $args['page_slug'] = 'michigan_webnus_theme_options';
    $args['page_parent'] = 'themes.php';
    $args['page_type'] = 'submenu';
    $args['page_position'] = 250;
    $categories = array();
    $categories = get_categories();
    $category_slug_array = array('');
    foreach($categories as $category){$category_slug_array[] = $category->slug;}
    
    $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
    $contact_forms = array();
    if ($cf7) {
        foreach ( $cf7 as $cform ) {
            $contact_forms[ $cform->ID ] = $cform->post_title;
        }
    } else {
        $contact_forms[ esc_html__( 'No contact forms found', 'michigan' ) ] = 0;
    }
    
    $args['show_theme_info'] = false;
    $sections = array();
    $sections[] = array(
        'title' => esc_html__('General', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Here are general settings of the theme:</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'icon' => NHP_OPTIONS_URL . 'img/admin-general.png',
        'fields' => array(
            array(
                'id' => 'michigan_webnus_maintenance_mode',
                'type' => 'button_set',
                'title' => esc_html__('Maintenance Mode', 'michigan'),
                'desc'=> esc_html__('Status of Maintenance Mode', 'michigan'),
                'options' => array('1' => 'Enable', '0' => 'Disable'),
                'std' => '0'
            ),
            array(
                'id' => 'michigan_webnus_maintenance_page',
                'type' => 'text',
                'title' => esc_html__('Maintenance Page ID', 'michigan'),
                'desc'=> esc_html__('ID of Maintenance Page', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_404_page',
                'type' => 'text',
                'title' => esc_html__('Custom 404 Page ID', 'michigan'),
                'desc'=> esc_html__('Leave bank for default', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_template_select',
                'type' => 'select',
                'title' => esc_html__('Template', 'michigan'),
                'desc'=> wp_kses( __('<br>Select your desired template from the the list.','michigan'), array( 'br' => array() ) ),
                'options' => array('online' => 'Online Learning','kids' => 'Kids', 'school' => 'High School', 'college' => 'College' ),
                'std' => 'online'
            ),
            array(
                'id' => 'michigan_webnus_enable_responsive',
                'type' => 'button_set',
                'title' => esc_html__('Responsive', 'michigan'),
                'desc'=> wp_kses( __('Disable this option in case you don\'t need a responsive website.','michigan'), array( 'br' => array() ) ),
                'options' => array('1' => 'Enable', '0' => 'Disable'),
                'std' => '1'
            ),          
            array(
                'id'      => 'michigan_webnus_css_minifier',
                'type'    => 'button_set',
                'title'   => esc_html__('CSS Minifyer', 'michigan'),
                'options' => array('1' => 'Enable', '0' => 'Disable'),
                'desc'=> wp_kses( __('Enable this option to minify your style-sheets. It\'ll decrease size of your style-sheet files to speed up your website.','michigan'), array( 'br' => array() ) ),
                'std'     => '0'
            ),
            array(
                'id' => 'michigan_webnus_enable_smoothscroll',
                'type' => 'button_set',
                'title' => esc_html__('Smooth Scroll', 'michigan'),
                'desc'=>  wp_kses( __('By enabling this option, your page will have smoth scrolling effect.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => 'Disable', '1' => 'Enable'),
                'std' => '0'
            ),
            array(
                'id' => 'michigan_webnus_background_layout',
                'type' => 'button_set',
                'title' => esc_html__('Layout', 'michigan'),
                'options' => array('' => 'Wide', 'boxed-wrap' => 'Boxed'),
                'desc'=> wp_kses( __('Select boxed or wide layout.','michigan'), array( 'br' => array() ) ),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_container_width',
                'type' => 'text',
                'title' => esc_html__('Container max-width', 'michigan'),
                'desc'=> wp_kses( __('You can define width of your website. ( Max width: 100% or 1170px )','michigan'), array( 'br' => array() ) ),
            ),
            array(
                'id' => 'michigan_webnus_favicon',
                'type' => 'upload',
                'title' => esc_html__('Custom Favicon', 'michigan'),
                'desc'=> wp_kses( __('An icon that will show in your browser tab near to your websites title, icon size is : 6 x 16 px','michigan'), array( 'br' => array() ) ),
            ),
            array(
                'id' => 'michigan_webnus_apple_iphone_icon',
                'type' => 'upload',
                'title' => esc_html__('Apple iPhone Icon', 'michigan'),
                'desc' => esc_html__('Icon for Apple iPhone (57px x 57px)', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_apple_ipad_icon',
                'type' => 'upload',
                'title' => esc_html__('Apple iPad Icon', 'michigan'),
                'desc' => esc_html__('Icon for Apple iPad (72px x 72px)', 'michigan'),
            ),
           array(
                'id' => 'michigan_webnus_admin_login_logo',
                'type' => 'upload',
                'title' => esc_html__('Admin Login Logo', 'michigan'),
                'desc'=> wp_kses( __('It belongs to the back-end of your website to log-in to admin panel.','michigan'), array( 'br' => array() ) ),
            ),   
            array(
                'id' => 'michigan_webnus_toggle_toparea_enable',
                'type' => 'button_set',
                'title' => esc_html__('Toggle Top Area', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'desc'=> wp_kses( __('It loads a small plus icon to the top right corner of your website.By clicking on it, it opens and shows your content that you set before.','michigan'), array( 'br' => array() ) ),
                'std' => '0'
            ),

            array(
                'id' => 'michigan_webnus_enable_livesearch',
                'type' => 'button_set',
                'title' => esc_html__('Live Search', 'michigan'),
                'options' => array('0' => 'Disable', '1' => 'Enable'),
                'std' => '1'
            ),
            
            array(
                'id' => 'michigan_webnus_scrollup',
                'type' => 'button_set',
                'title' => esc_html__('Scroll To Top', 'michigan'),
                'options' => array('0' => 'Disable', '1' => 'Enable'),
                'std' => '1'
            ),
            
            array(
                'id' => 'michigan_webnus_footer_contact_info',
                'type' => 'button_set',
                'title' => esc_html__('Footer Contact Info', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '0'
            ),  

            array(
                'id' => 'michigan_webnus_footer_contact_address',
                'type' => 'textarea',
                'title' => esc_html__('Address Information', 'michigan'),
                'std' => 'info@yourdomain.com'
            ),  
            array(
                'id' => 'michigan_webnus_footer_contact_email',
                'type' => 'textarea',
                'title' => esc_html__('Email Information', 'michigan'),
                'std' => 'info@yourdomain.com'
            ),
            array(
                'id' => 'michigan_webnus_footer_contact_phone',
                'type' => 'textarea',
                'title' => esc_html__('Phone Number Information', 'michigan'),
                'std' => '+1 234 56789'
            ),

            array(
                'id' => 'michigan_webnus_space_before_head',
                'type' => 'textarea',
                'title' => esc_html__('Space Before &lt;/head&gt;', 'michigan'),
                'desc' => esc_html__('Add code before the &lt;/head&gt; tag.', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_space_before_body',
                'type' => 'textarea',
                'title' => esc_html__('Space Before &lt;/body&gt;', 'michigan'),
                'desc' => esc_html__('Add code before the &lt;/body&gt; tag.', 'michigan'),
            ),
        )
    );
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-header.png',
        'title' => esc_html__('Header Options', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Everything about headers, Logo, Menus and contact information are here:</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            array(
                'id' => 'michigan_webnus_logo',
                'type' => 'upload',
                'title' => esc_html__('Logo', 'michigan'),
                'desc' => esc_html__('Choose an image file for your logo. For Retina displays please add Image in large size and set custom width.', 'michigan'),
                'std' => '',
            ),
            array(
                'id' => 'michigan_webnus_logo_width',
                'type' => 'text',
                'title' => esc_html__('Logo width', 'michigan'),
                'std' => '280',
            ),
            array(
                'id' => 'michigan_webnus_transparent_logo',
                'type' => 'upload',
                'title' => esc_html__('Transparent header logo', 'michigan'),
                'std' => '',
            ),
             array(
                'id' => 'michigan_webnus_transparent_logo_width',
                'type' => 'text',
                'title' => esc_html__('Transparent header logo width', 'michigan'),
                'std' => '280'
            ),
            array(
                'id' => 'michigan_webnus_trendy_logo',
                'type' => 'upload',
                'title' => esc_html__('Trendy header logo', 'michigan'),
                'std' => '',
            ),
            array(
                'id' => 'michigan_webnus_trendy_logo_width',
                'type' => 'text',
                'title' => esc_html__('Trendy header logo width', 'michigan'),
                'std' => '280'
            ),
            array(
                'id' => 'michigan_webnus_header_padding_top',
                'type' => 'text',
                'title' => esc_html__('Header padding-top', 'michigan'),
                'desc'=> wp_kses( __('This option controls the space between header top with content or elements that is in top of the header.','michigan'), array( 'br' => array() ) ),
            ),
            array(
                'id' => 'michigan_webnus_header_padding_bottom',
                'type' => 'text',
                'title' => esc_html__('Header padding-bottom', 'michigan'),
                'desc'=> wp_kses( __('This option controls the space between header bottom with content or elements that is in bottom of the header.','michigan'), array( 'br' => array() ) ),
            ),
            array(
                'id' => 'michigan_webnus_slogan',
                'type' => 'text',
                'title' => esc_html__('Slogan text', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_sticky_menu_sp',
                'type' => 'seperator',
                'desc' => esc_html__('Sticky Menu', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_header_sticky',
                'type' => 'button_set',
                'title' => esc_html__('Sticky Menu', 'michigan'),
                'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
                'desc'=> wp_kses( __('Sticky menu is a fixed header when scrolling the page. By enabling this option when you are scrolling, the header menu will scroll too.','michigan'), array( 'br' => array() ) ),
                'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_sticky_logo',
				'type' => 'upload',
				'title' => esc_html__('Sticky header logo', 'michigan'),
				'desc'=> wp_kses( __('Use this option to upload a logo which will be used when header is on sticky state.Sticky state is a fixed header when scrolling.','michigan'), array( 'br' => array() ) ),
				'std' => ''
            ),
             array(
				'id' => 'michigan_webnus_sticky_logo_width',
				'type' => 'text',
				'title' => esc_html__('Sticky header logo width', 'michigan'),
				'std' => '60'
            ),
            array(
				'id' => 'michigan_webnus_header_sticky_scrolls',
				'type' => 'text',
				'title' => esc_html__('Scrolls value to sticky the header', 'michigan'),
				'desc'=> wp_kses( __('Fill your desired amount which by scrolling that amount, sticky menu will appear.','michigan'), array( 'br' => array() ) ),
				'std' => '260',
            ),
            array(
				'id' => 'michigan_webnus_page_menu_sp',
				'type' => 'seperator',
				'desc' => esc_html__('Header Types', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_header_menu_type',
				'type' => 'radio_img',
				'title' => esc_html__('Select Header Layout', 'michigan'),
				'options' => array(
                    '0' => array('title' => esc_html__('No Menu', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu0.png'),
                    '1' => array('title' => esc_html__('Classic - Normal', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu1.png'),
                    '10' => array('title' => esc_html__('Classic - Boxed', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu10.png'),
                    '11' => array('title' => esc_html__('Colorful', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu11.png'),
                    '8' => array('title' => esc_html__('Duplex', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu8.png'),
                    '2' => array('title' => esc_html__('Modern - Light', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu2.png'),
                    '4' => array('title' => esc_html__('Modern - Light with Details', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu4.png'),
                    '3' => array('title' => esc_html__('Modern - Dark', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu3.png'),
                    '5' => array('title' => esc_html__('Modern - Dark with Details', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu5.png'),
                    '9' => array('title' => esc_html__('Modern - Boxed Menu', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu9.png'),
					'12'=> array('title' => esc_html__('Trendy', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu12.png'),
                    '6' => array('title' => esc_html__('Vertical - Normal', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu6.png'),
                    '7' => array('title' => esc_html__('Vertical - Toggle', 'michigan'), 'img' => $theme_img_dir . 'menutype/menu7.png'),
                ),
				'std' => '1'
            ),
            
            array(
				'id' => 'michigan_webnus_header_logo_alignment',
				'type' => 'button_set',
				'title' => esc_html__('Logo Alignment', 'michigan'),
				'desc'=> wp_kses( __('This option changes the position of the logo on top of the header.<br>For Modern Headers','michigan'), array( 'br' => array() ) ),
				'options' => array('1' => 'Left', '2' => 'Center', '3' => 'Right'),
				'std' => '2',
            ),
            array(
				'id' => 'michigan_webnus_header_logo_rightside',
				'type' => 'select',
				'title' => esc_html__('Header Right side', 'michigan'),
				'desc'=> wp_kses( __('For Modern Headers<br><br>Contact information: you can put phone number and email address by fill the information boxes in the next part.','michigan'), array( 'br' => array() ) ),
				'options' => array(0 => esc_html__('None','michigan'), 1 => esc_html__('Search Box','michigan'), 2 => esc_html__('Contact Information','michigan'), 3 => esc_html__('Header Sidebar','michigan')),
				'std' => '0'
            ),
            array(
				'id' => 'michigan_webnus_header_phone',
				'type' => 'text',
				'title' => esc_html__('Header Phone Number', 'michigan'),
				'std' => '+1 234 56789'
            ),
            array(
				'id' => 'michigan_webnus_header_email',
				'type' => 'text',
				'title' => esc_html__('Header Email Address', 'michigan'),
				'std' => 'info@yourdomain.com'
            ),
            array(
				'id' => 'michigan_webnus_header_background',
				'type' => 'upload',
				'title' => esc_html__('Header Background Image', 'michigan'),
				'desc' => esc_html__('For Vertical - Toggle Header', 'michigan'),
            ),

			
			array(
				'id' => 'michigan_webnus_header_button_enable',
				'type' => 'button_set',
				'title' => esc_html__('Button in Header', 'michigan'),
				'desc'=> wp_kses( __('This option shows a button at the end of the header menu for Classic Headers','michigan'), array( 'br' => array() ) ),
				'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
				'std' => '0'
            ),
			
			array(
				'id' => 'michigan_webnus_header_button_label',
				'type' => 'text',
				'title' => esc_html__('Button in Header Label', 'michigan'),
				'desc' => esc_html__('Button in Header Label','michigan'),
				'std' => 'Apply Today'
            ),
			
			array(
				'id' => 'michigan_webnus_header_button_url',
				'type' => 'text',
				'title' => esc_html__('Button in Header URL', 'michigan'),
				'desc' => esc_html__('Button in Header URL','michigan'),
				'std' => 'Apply Today'
            ),			
			
            array(
				'id' => 'michigan_webnus_header_search_enable',
				'type' => 'button_set',
				'title' => esc_html__('Search in Header', 'michigan'),
				'desc'=> wp_kses( __('This option shows a search icon at the end of the header menu for Classic Headers','michigan'), array( 'br' => array() ) ),
				'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
				'std' => '0'
            ),
						
            array(
				'id' => 'michigan_webnus_dark_submenu',
				'type' => 'button_set',
				'title' => esc_html__('Submenu Background', 'michigan'),
				'desc' => esc_html__('For Header Menu and Topbar Menu','michigan'),
				'options' => array('0' => esc_html__('Light', 'michigan'), '1' => esc_html__('Dark', 'michigan')),
				'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_header_bottom',
				'type' => 'button_set',
				'title' => esc_html__('Header Bottom', 'michigan'),
				'desc' => esc_html__('Show menu and search bar under header (For Classic - Boxed Header)', 'michigan'),
				'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
				'std' => '0'
            ),
    ));
        /** TOPBAR **/  
		$sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-topbar.png',
        'title' => esc_html__('Topbar Options', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Top bar is the topmost location in your website that you can place special elements in such as Login Modal, Donate Modal, Menu, Social Icons, Cantact Informations, TagLine and WPML Language bar.</p><p>Note: when you choose menu, you should create Topbar Menu from apearance > menus.</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            array(
				'id' => 'michigan_webnus_header_topbar_enable',
				'type' => 'button_set',
				'title' => esc_html__('Show/Hide TopBar', 'michigan'),
				'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
				'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_topbar_background_color',
				'type' => 'color',
				'title' => esc_html__('Background Color', 'michigan'),
				'desc'=> wp_kses( __('This option changes the background color of Topbar.','michigan'), array( 'br' => array() ) ),
				'std' => ''
            ),
            array(
				'id' => 'michigan_webnus_topbar_fixed',
				'type' => 'button_set',
				'title' => esc_html__('Fixed Topbar', 'michigan'),
				'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
				'std' => '0'
            ),  
            
            array(
				'id' => 'michigan_webnus_topbar_search',
				'type' => 'button_set',
				'title' => esc_html__('Search Bar', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
				'std' => 'left'
            ),

            array(
				'id' => 'michigan_webnus_topbar_login',
				'type' => 'button_set',
				'title' => esc_html__('Login Modal', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
				'desc' => wp_kses( __('Login Modal Link in Topbar','michigan'), array( 'br' => array() ) ),
            ),

            array(
				'id' => 'michigan_webnus_topbar_login_text',
				'type' => 'text',
				'title' => esc_html__('Login Modal Text', 'michigan'),

				'desc' => wp_kses( __('Login Modal Link Text','michigan'), array( 'br' => array() ) ),
				'std' => 'LOGIN'
            ),

            array(
				'id' => 'michigan_webnus_topbar_contact',
				'type' => 'button_set',
				'title' => esc_html__('Contact Modal', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
				'desc' => wp_kses( __('Contact Modal Link in Topbar','michigan'), array( 'br' => array() ) ),
				'std' => 'right'
            ),
            
            array(
				'id' => 'michigan_webnus_topbar_contact_text',
				'type' => 'text',
				'title' => esc_html__('Contact Modal Text', 'michigan'),
				'desc' => wp_kses( __('Contact Modal Link Text','michigan'), array( 'br' => array() ) ),
				'std' => 'CONTACT'
            ),

            
            array(
				'id' => 'michigan_webnus_topbar_form',
				'type' => 'select',
				'title' => esc_html__('Select Contact Form', 'michigan'),
				'options' => $contact_forms,
				'desc' => wp_kses( __('Choose previously created contact form from the drop down list.', 'michigan'), array( 'br' => array() ) ),
            ),
            

            array(
				'id' => 'michigan_webnus_topbar_info',
				'type' => 'button_set',
				'title' => esc_html__('Contact Information', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
            ),

            array(
				'id' => 'michigan_webnus_topbar_phone',
				'type' => 'text',
				'title' => esc_html__('Topbar Phone Number', 'michigan'),
				'std' => '+1 234 56789'
            ),
            array(
				'id' => 'michigan_webnus_topbar_email',
				'type' => 'text',
				'title' => esc_html__('Topbar Email Address', 'michigan'),
				'std' => 'info@yourdomain.com'
            ),
            
            array(
				'id' => 'michigan_webnus_topbar_menu',
				'type' => 'button_set',
				'title' => esc_html__('Topbar Menu', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
            ),

            
            array(
				'id' => 'michigan_webnus_topbar_custom',
				'type' => 'button_set',
				'title' => esc_html__('Custom Text', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
            ),
            
            array(
				'id' => 'michigan_webnus_topbar_text',
				'type' => 'text',
				'title' => esc_html__('Topbar Custom Text', 'michigan'),
				'desc' => wp_kses( __('Insert Any Text You Want Here', 'michigan'), array( 'br' => array() ) ),
            ),

            array(
				'id' => 'michigan_webnus_topbar_language',
				'type' => 'button_set',
				'title' => esc_html__('Language Bar', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
				'desc' => wp_kses( __('WPML Language Bar in Topbar','michigan'), array( 'br' => array() ) ),
            ),
            
            array(
				'id' => 'michigan_webnus_topbar_social',
				'type' => 'button_set',
				'title' => esc_html__('Social Icons', 'michigan'),
				'options' => array('' => esc_html__('None', 'michigan'), 'left' => esc_html__('Left', 'michigan'), 'right' => esc_html__('Right', 'michigan')),
'desc'=> wp_kses( __('Set in Social Networks Tab.','michigan'), array( 'br' => array() ) ),
				'std' => 'right'
            ),
            

    ));
    
      /** Learning Options **/ 
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-learnoptions.png',
        'title' => esc_html__('Learning Options', 'michigan'),
        'fields' => array(
            array(
				'id' => 'michigan_webnus_course_options',
				'type' => 'seperator',
				'desc' => esc_html__('Course Options', 'michigan'),
            ),          
             array(
				'id' => 'michigan_webnus_course_features',
				'type' => 'multi_checkbox',
				'title' => esc_html__('Course Features', 'michigan'),
				'options' => array(		
				'date'			=> esc_html__('Course Date', 'michigan'),
				'duration'		=> esc_html__('Course Duration', 'michigan'),
				'capacity'	 	=> esc_html__('Course Capacity', 'michigan'),
				'difficulty'	=> esc_html__('Course Difficulty', 'michigan'),
				'code'	 		=> esc_html__('Course Code', 'michigan'),
				'category'	 	=> esc_html__('Course Category', 'michigan'),
				'views' 		=> esc_html__('Course View', 'michigan'),
				'rating' 		=> esc_html__('Course Rating', 'michigan'),
				'students' 		=> esc_html__('Course Students', 'michigan'),
				'instructors' 	=> esc_html__('Course Instructors', 'michigan'),
				'tags' 			=> esc_html__('Tags Bar', 'michigan'),
				'comment' 		=> esc_html__('Comments Section', 'michigan'),
				'price' 		=> esc_html__('Price Widget', 'michigan'),
				'enrolled' 		=> esc_html__('Enrolled Widget', 'michigan'),
				'instructor' 	=> esc_html__('Instructor Widget', 'michigan'),
				'sharing' 		=> esc_html__('Sharing Widget', 'michigan'),
				),
				'class' => 'two-col',
            ),
            array(
				'id' => 'michigan_webnus_course_taking',
				'type' => 'button_set',
				'title' => esc_html__('Taking Course', 'michigan'),
				'options' => array('0' => esc_html__('None', 'michigan'), '1' => esc_html__('LifterLMS', 'michigan'), '2' => esc_html__('Custom', 'michigan')),
				'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_course_taking_custom',
				'type' => 'text',
				'title' => esc_html__('Taking Course Custom URL', 'michigan'),
            ),
             array(
				'id' => 'michigan_webnus_enable_breadcrumbs',
				'type' => 'button_set',
				'title' => esc_html__('Course Breadcrumb', 'michigan'),
				'options' => array('0' => 'Hide', '1' => 'Show'),
				'desc'=> wp_kses( __('Show Breadcrumb in Course, Lesson and Quiz.','michigan'), array( 'br' => array() ) ),
				'std' => '0'
            ),
            
            array(
				'id' => 'michigan_webnus_course_no_image',
				'type' => 'button_set',
				'title' => esc_html__('Default Blank Featured Image', 'michigan'),
				'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
				'std' => '0'
            ),
            array(
				'id' => 'michigan_webnus_course_no_image_src',
				'type' => 'upload',
				'title' => esc_html__('Custom Default Blank Featured Image', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_custom_sep',
				'type' => 'seperator',
				'desc' => esc_html__('Lesson Options', 'michigan'),
            ),  
             array(
				'id' => 'michigan_webnus_lesson_features',
				'type' => 'multi_checkbox',
				'title' => esc_html__('Lesson Features', 'michigan'),
				'options' => array(
				'image'         => esc_html__('Lesson Image', 'michigan'),
				'date'          => esc_html__('Lesson Date', 'michigan'),
				'comment'       => esc_html__('Lesson Comment', 'michigan'),
				'instructor'    => esc_html__('Lesson Instructor', 'michigan'),
				),
				'class' => 'two-col',
            ),  
    ));
    

 /** Extra Options **/ 
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-extraoptions.png',
        'title' => esc_html__('Extra Options', 'michigan'),
        'fields' => array(
            array(
				'id' => 'michigan_webnus_booking_options',
				'type' => 'seperator',
				'desc' => esc_html__('Booking Options', 'michigan'),
            ),          
            array(
				'id' => 'michigan_webnus_booking_enable',
				'type' => 'button_set',
				'title' => esc_html__('Event Booking', 'michigan'),
				'options' => array('0' => esc_html__('Disable', 'michigan'), '1' => esc_html__('Enable', 'michigan')),
				'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_booking_form',
				'type' => 'select',
				'title' => esc_html__('Booking Form', 'michigan'),
				'options' => $contact_forms,
				'desc' => wp_kses( __('Choose previously created contact form from the drop down list.', 'michigan'), array( 'br' => array() ) ),
            ),
			 array(
				'id' => 'michigan_webnus_profile_options',
				'type' => 'seperator',
				'desc' => esc_html__('Author Permalink', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_author_permalink',
				'type' => 'text',
				'title' => esc_html__('Author Base', 'michigan'),
				'std' => 'profile'
            ),			
			
             array(
				'id' => 'michigan_webnus_goal_options',
				'type' => 'seperator',
				'desc' => esc_html__('Goal Options', 'michigan'),
            ),

            array(
				'id' => 'michigan_webnus_singlegoal_sidebar',
				'type' => 'button_set',
				'title' => esc_html__('Single Goal Sidebar', 'michigan'),
				'options' => array('none'=>'None','left' => 'Left', 'right' => 'Right'),
				'std' => 'none',
            ),
            array(
				'id' => 'michigan_webnus_donate_form',
				'type' => 'select',
				'title' => esc_html__('Donate Form', 'michigan'),
				'options' => $contact_forms,
				'desc' => wp_kses( __('Choose previously created contact form from the drop down list.', 'michigan'), array( 'br' => array() ) ),
            ),
            
            array(
				'id' => 'michigan_webnus_currency',
				'type' => 'text',
				'title' => esc_html__('Currency', 'michigan'),
				'std' => '$'
            ),
            array(
				'id' => 'michigan_webnus_goal_features',
				'type' => 'multi_checkbox',
				'title' => esc_html__('Goal Features', 'michigan'),
				'options' => array(
				'image'     => esc_html__('Goal Image', 'michigan'),
				'date'      => esc_html__('Goal Date', 'michigan'),
				'category'  => esc_html__('Goal Cagetory', 'michigan'),
				'views'     => esc_html__('Goal Views', 'michigan'),
				'sharing'   => esc_html__('Goal Sharing', 'michigan'),
				'comment'   => esc_html__('Goal Comment', 'michigan'),
				),
				'class' => 'two-col',
            ),      
    ));

	
    //background options
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-background.png',
        'title' => esc_html__('Background', 'michigan'),
        'desc' => wp_kses( __('<p class="description">This section is about the background of your whole website.', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            /* Enable Disable Header Social */
            array(
				'id' => 'michigan_webnus_background',
				'type' => 'upload',
				'title' => esc_html__('Background Image', 'michigan'),
				'desc' => esc_html__('Please choose an image or insert an image url to use for the backgroud.', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_background_100',
				'type' => 'checkbox',
				'title' => esc_html__('100% Background Image', 'michigan'),
				'desc' => esc_html__('Check the box to have the background image always at 100% in width and height and scale according to the browser size.', 'michigan'),
				'std' => '0'
            ),
            array(
				'id' => 'michigan_webnus_background_repeat',
				'type' => 'select',
				'title' => esc_html__('Background Repeat', 'michigan'),
				'options' => array('1' => esc_html__('repeat', 'michigan'), '2' => esc_html__('repeat-x', 'michigan'), '3' => esc_html__('repeat-y', 'michigan'), '0' => esc_html__('no-repeat', 'michigan')),
				'std' => '1'
            ),
            array(
				'id' => 'michigan_webnus_background_color',
				'type' => 'color',
				'title' => esc_html__('Background Color', 'michigan'),
				'sub_desc' => esc_html__('Pick a background color', 'michigan'),
				'std' => ''
            ),
            array(
				'id' => 'michigan_webnus_background_pattern', //must be unique
				'type' => 'radio_img', //the field type
				'title' => esc_html__('Background Pattern', 'michigan'),
				'options' => array('none' => array('title' => esc_html__('None', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/none.jpg'),
					$theme_img_dir . 'bdbg1.png' => array('title' => esc_html__('Default BG', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/bdbg1.png'), $theme_img_bg_dir . 'gray-jean.png' => array('title' => esc_html__('Gray Jean', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/gray-jean.png'), $theme_img_bg_dir . 'light-wool.png' => array('title' => esc_html__('Light Wool', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/light-wool.png'),
					$theme_img_bg_dir . 'subtle_freckles.png' => array('title' => esc_html__('Subtle Freckles', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/subtle_freckles.png'),
					$theme_img_bg_dir . 'subtle_freckles2.png' => array('title' => esc_html__('Subtle Freckles 2', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/subtle_freckles2.png'),
					$theme_img_bg_dir . 'green-fibers.png' => array('title' => esc_html__('Green Fibers', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/green-fibers.png'),
					$theme_img_bg_dir . 'dust.png' => array('title' => esc_html__('Dust', 'michigan'), 'img' => $theme_img_bg_dir . 'bg-pattern/dust.png')),
				'std' => $theme_img_dir . 'bdbg1.png'//this should be the key as defined above
            )
    ));
    /* custom fonts */
    include_once get_template_directory() . '/inc/nc-options/gfonts/gfonts.php';
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-typography.png',
        'title' => esc_html__('Typography', 'michigan'),
        'fields' => array(
            array(
				'id' => 'sep1',
				'type' => 'seperator',
				'desc' => esc_html__('Custom font 1', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_custom_font1_woff',
				'type' => 'upload',
				'title' => esc_html__('Custom font 1 .woff', 'michigan'),
				'desc' => esc_html__('Upload the .woff font file for custom font 1', 'michigan'),
				'options' => $fontArray
            ),
            array(
				'id' => 'michigan_webnus_custom_font1_ttf',
				'type' => 'upload',
				'title' => esc_html__('Custom font 1 .ttf', 'michigan'),
				'desc' => esc_html__('Upload the .ttf font file for custom font 1', 'michigan'),
				'options' => $fontArray
            ),         
            array(
				'id' => 'michigan_webnus_custom_font1_eot',
				'type' => 'upload',
				'title' => esc_html__('custom font 1 .eot', 'michigan'),
				'desc' => esc_html__('Upload the .eot font file for custom font 1', 'michigan'),
				'options' => $fontArray
            ),
            /* custom font 2*/ 
            array(
				'id' => 'sep1',
				'type' => 'seperator',
				'desc' => esc_html__('Custom font 2', 'michigan'),
            ),            
            array(
				'id' => 'michigan_webnus_custom_font2_woff',
				'type' => 'upload',
				'title' => esc_html__('Custom font 2 .woff', 'michigan'),
				'desc' => esc_html__('Upload the .woff font file for custom font 2', 'michigan'),
				'options' => $fontArray
            ),
            array(
				'id' => 'michigan_webnus_custom_font2_ttf',
				'type' => 'upload',
				'title' => esc_html__('Custom font 2 .ttf', 'michigan'),
				'desc' => esc_html__('Upload the .ttf font file for custom font 2', 'michigan'),
				'options' => $fontArray
            ),  
            array(
				'id' => 'michigan_webnus_custom_font2_eot',
				'type' => 'upload',
				'title' => esc_html__('custom font 2 .eot', 'michigan'),
				'desc' => esc_html__('Upload the .eot font file for custom font 2', 'michigan'),
				'options' => $fontArray
            ),
            /* custom font 3*/ 
            array(
				'id' => 'sep1',
				'type' => 'seperator',
				'desc' => esc_html__('Custom font 3', 'michigan'),
            ),            
            array(
				'id' => 'michigan_webnus_custom_font3_woff',
				'type' => 'upload',
				'title' => esc_html__('Custom font 3 .woff', 'michigan'),
				'desc' => esc_html__('Upload the .woff font file for custom font 3', 'michigan'),
				'options' => $fontArray
            ),
            array(
				'id' => 'michigan_webnus_custom_font3_ttf',
				'type' => 'upload',
				'title' => esc_html__('Custom font 3 .ttf', 'michigan'),
				'desc' => esc_html__('Upload the .ttf font file for custom font 3', 'michigan'),
				'options' => $fontArray
            ),          
            array(
				'id' => 'michigan_webnus_custom_font3_eot',
				'type' => 'upload',
				'title' => esc_html__('custom font 3 .eot', 'michigan'),
				'desc' => esc_html__('Upload the .eot font file for custom font 3', 'michigan'),
				'options' => $fontArray
            ),
            /* Adobe Typekit*/ 
            array(
				'id' => 'sep4',
				'type' => 'seperator',
				'desc' => esc_html__('Adobe Typekit', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_typekit_id',
				'type' => 'text',
				'title' => esc_html__('Typekit Kit ID', 'michigan'),
				'desc' => __('<p class="description">Copy "Typekit Kid ID" from <a href="https://typekit.com/fonts" target="_blank">here</a>.</p>', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_typekit_font1',
				'type' => 'text',
				'title' => esc_html__('Typekit Font Family 1', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_typekit_font2',
				'type' => 'text',
				'title' => esc_html__('Typekit Font Family 2', 'michigan'),
            ),
            array(
				'id' => 'michigan_webnus_typekit_font3',
				'type' => 'text',
				'title' => esc_html__('Typekit Font Family 3', 'michigan'),
            ),
             /* select font*/ 
            array(
				'id' => 'sep5',
				'type' => 'seperator',
				'desc' =>  esc_html__( 'Select Font Family', 'michigan'),
            ),
             array(
				'id' => 'michigan_webnus_body_font',
				'type' => 'select',
				'title' => esc_html__('Select Body Font Family', 'michigan'),
				'desc' => esc_html__('Select a font family for body text', 'michigan'),
				'options' => $fontArray
            ),
            array(
				'id' => 'michigan_webnus_heading_font',
				'type' => 'select',
				'title' => esc_html__('Select Headings Font', 'michigan'),
				'desc' => esc_html__('Select a font family for headings', 'michigan'),
                'options' => $fontArray
            ),
            array(
                'id' => 'michigan_webnus_p_font',
                'type' => 'select',
                'title' => esc_html__('Select Paragraph Font', 'michigan'),
                'desc' => esc_html__('Select a font family for paragraphs', 'michigan'),
                'options' => $fontArray
            ),  
              array(
                'id' => 'michigan_webnus_menu_font',
                'type' => 'select',
                'title' => esc_html__('Select Menu Font', 'michigan'),
                'desc' => esc_html__('Select a font family for menu', 'michigan'),
                'options' => $fontArray
            ),  
            array(
                'id' => 'sep1',
                'type' => 'seperator',
                'desc' => esc_html__('Header Menu Links Typography', 'michigan'),
            ),
            /* NAV */    
            array(
                'id' => 'michigan_webnus_topnav_font_size',
                'type' => 'slider',
                'title' => esc_html__('Header Menu font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_topnav_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('Header Menu letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_topnav_line_height',
                'type' => 'slider',
                'title' => esc_html__('Header Menu line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            /* END Menu */
            array(
                'id' => 'sep1',
                'type' => 'seperator',
                'desc' => esc_html__('Paragraph and Headings Typography', 'michigan'),
            ),
             /* P */   
            array(
                'id' => 'michigan_webnus_p_font_size',
                'type' => 'slider',
                'title' => esc_html__('P font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_p_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('P letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_p_line_height',
                'type' => 'slider',
                'title' => esc_html__('P line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_p_font_color',
                'type' => 'color',
                'title' => esc_html__('P font-color', 'michigan'),
            ),
             /* END P */
            /* H1 */   
            array(
                'id' => 'michigan_webnus_h1_font_size',
                'type' => 'slider',
                'title' => esc_html__('H1 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h1_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H1 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h1_line_height',
                'type' => 'slider',
                'title' => esc_html__('H1 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h1_font_color',
                'type' => 'color',
                'title' => esc_html__('H1 font-color', 'michigan'),
            ),
             /* END H1 */
              /* H2 */  
            array(
                'id' => 'michigan_webnus_h2_font_size',
                'type' => 'slider',
                'title' => esc_html__('H2 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h2_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H2 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h2_line_height',
                'type' => 'slider',
                'title' => esc_html__('H2 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h2_font_color',
                'type' => 'color',
                'title' => esc_html__('H2 font-color', 'michigan'),
            ),
             /* END H2 */
              /* H3 */  
            array(
                'id' => 'michigan_webnus_h3_font_size',
                'type' => 'slider',
                'title' => esc_html__('H3 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h3_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H3 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h3_line_height',
                'type' => 'slider',
                'title' => esc_html__('H3 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h3_font_color',
                'type' => 'color',
                'title' => esc_html__('H3 font-color', 'michigan'),
            ),
            /* END H3 */
            /* H4 */ 
            array(
                'id' => 'michigan_webnus_h4_font_size',
                'type' => 'slider',
                'title' => esc_html__('H4 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h4_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H4 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h4_line_height',
                'type' => 'slider',
                'title' => esc_html__('H4 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h4_font_color',
                'type' => 'color',
                'title' => esc_html__('H4 font-color', 'michigan'),
            ),
            /* END H4 */
            /* H5 */ 
            array(
                'id' => 'michigan_webnus_h5_font_size',
                'type' => 'slider',
                'title' => esc_html__('H5 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h5_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H5 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h5_line_height',
                'type' => 'slider',
                'title' => esc_html__('H5 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h5_font_color',
                'type' => 'color',
                'title' => esc_html__('H5 font-color', 'michigan'),
            ),
            /* END H5 */
            /* H6 */ 
            array(
                'id' => 'michigan_webnus_h6_font_size',
                'type' => 'slider',
                'title' => esc_html__('H6 font-size', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h6_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('H6 letter-spacing', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h6_line_height',
                'type' => 'slider',
                'title' => esc_html__('H6 line-height', 'michigan'),
                'value' => array('min'=>1,'max'=>100),
            ),
            array(
                'id' => 'michigan_webnus_h6_font_color',
                'type' => 'color',
                'title' => esc_html__('H6 font-color', 'michigan'),
            ),
        )
    );
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-style.png',
        'title' => esc_html__('Styling Options', 'michigan'),
        'desc' => wp_kses( __('<p class="description">You can manage every style that you see in the theme from here.</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(      
        array(
                'id' => 'michigan_webnus_custom_color_sep',
                'type' => 'seperator',
                'desc' => esc_html__('Color Skin', 'michigan'),
        ),
        
        array(
            'id' => 'michigan_webnus_custom_color_skin_enable',
            'type' => 'button_set',
            'title' => esc_html__('Color Skin', 'michigan'),
            'options' => array(0 => esc_html__('Predefined','michigan'), 1 => esc_html__('Custom','michigan')),
            'std' => '0',
        ),
            
        array(
                'id' => 'michigan_webnus_color_skin', //must be unique
                'type' => 'radio_img', //the field type
                'title' => esc_html__('Predefined Color Skin', 'michigan'),
                'options' => array(
                 'none' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color3-ss.png')
				,'#4ccfad' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color8-ss.png')
                ,'#3ed1e7' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color9-ss.png')
				,'#ffb300' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color2-ss.png')
                ,'#e53f51' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color4-ss.png')
                ,'#0093d0' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color1-ss.png')
                ,'#e64883' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color5-ss.png')
                ,'#45ab48' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color6-ss.png')
                ,'#9661ab' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color7-ss.png')
                ,'#ff9934' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color10-ss.png')
                ,'#c3512f' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color11-ss.png')
                ,'#55606e' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color12-ss.png')
                ,'#fe8178' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color13-ss.png')
                ,'#7c6853' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color14-ss.png')
                ,'#bed431' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color15-ss.png')
                ,'#2d5c88' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color16-ss.png')
                ,'#77da55' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color17-ss.png')
                ,'#2997ab' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color18-ss.png')
                ,'#734854' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color19-ss.png')
                ,'#a81010' => array('title' =>'','img' => NHP_OPTIONS_URL . 'img/color20-ss.png')
                ),
                'desc' => esc_html__('This option changes the default color scheme of your theme such as links, titles & etc. It will automatically change to the defined color.', 'michigan'),
                'std' => 'none'
            ),

            array(
                'id' => 'michigan_webnus_custom_color_skin',
                'type' => 'color',
                'title' => esc_html__('Custom Color Skin', 'michigan'),
                'desc' => esc_html__('Choose your desire color scheme.', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'mainstyle-sep1',
                'type' => 'seperator',
                'desc' => esc_html__('Link Base Color', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_link_color',
                'type' => 'color',
                'title' => esc_html__('Unvisited Link Color', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_hover_link_color',
                'type' => 'color',
                'title' => esc_html__('Mouse Over Link Color', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_visited_link_color',
                'type' => 'color',
                'title' => esc_html__('Visited Link Color ', 'michigan'),
            ),
            array(
                'id' => 'mainstyle-sep1',
                'type' => 'seperator',
                'desc' => esc_html__('Header Menu Colors', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_menu_link_color',
                'type' => 'color',
                'title' => esc_html__('Header Menu Link Color', 'michigan'),
            ),
            array(
                'id'=>'michigan_webnus_menu_hover_link_color',
                'type'=>'color',
                'title'=> esc_html__('Header Menu Link Hover Color','michigan'),            
            ),
            array(
                'id'=>'michigan_webnus_menu_selected_link_color',
                'type'=>'color',
                'title'=> esc_html__('Header Menu Link Selected Color','michigan'),         
            ),
            array(
                'id'=>'michigan_webnus_menu_selected_border_color',
                'type'=>'color',
                'title'=> esc_html__('Header Menu Selected Border Color','michigan'),           
            ),
            array(
                'id'=>'michigan_webnus_resoponsive_menu_icon_color',
                'type'=>'color',
                'title'=> esc_html__('Responsive Menu Icon Color','michigan'),
                'desc'=> esc_html__('This menu icon appears in mobile & tablet view','michigan'),
            ),
            //Icon Box Colors
            array(
                'id' => 'mainstyle-sep2',
                'type' => 'seperator',
                'desc' => esc_html__('Icon Box Colors', 'michigan'),
            ),
            array(
                'id'=>'michigan_webnus_iconbox_base_color',
                'type'=>'color',
                'title'=>esc_html__('Iconbox base color', 'michigan'),      
            ),
            array(
                'id'=>'michigan_webnus_learnmore_link_color',
                'type'=>'color',
                'title'=>esc_html__('Learn more link color', 'michigan'),       
            ),
            array(
                'id'=>'michigan_webnus_learnmore_hover_link_color',
                'type'=>'color',
                'title'=>esc_html__('Learn more hover link color', 'michigan'),     
            ),
            /*
             * Scroll to top
             */
            array(
                'id' => 'mainstyle-sep11',
                'type' => 'seperator',
                'desc' => esc_html__('Scroll to top', 'michigan'),
            ),
            array(
                'id'=>'michigan_webnus_scroll_to_top_background_color',
                'type'=>'color',
                'title'=>esc_html__('Scroll to top background color ','michigan'),  
            ),
            
            array(
                'id'=>'michigan_webnus_scroll_to_top_hover_background_color',
                'type'=>'color',
                'title'=>esc_html__('Scroll to top hover background color ', 'michigan'),   
            ),
        )
    );
    /*
     *
     *
     * BLOG Options
     *
     *
     */
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-blog.png',
        'title' => esc_html__('Blog Options', 'michigan'),
        'desc' => wp_kses( __('<p class="description">This section is about everything belong to blog page and blog posts.', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
             array(
                'id' => 'michigan_webnus_blog_template',
                'type' => 'select',
                'title' => esc_html__('BlogTemplate', 'michigan'),
                'desc'=> wp_kses( __('For styling your blog page you can choose among these template layouts.','michigan'), array( 'br' => array() ) ),
                'options' => array(
				'1' => esc_html__('Large Posts', 'michigan'),
				'2' => esc_html__('List Posts', 'michigan'),
				'3' => esc_html__('Grid Posts', 'michigan'),
				'4' => esc_html__('First Large then List', 'michigan'),
				'5' => esc_html__('First Large then Grid', 'michigan'),
				'6' => esc_html__('Masonry', 'michigan'),
				'7' => esc_html__('Timeline', 'michigan')
                ),
                'std' => '2'
            ),
             array(
                'id' => 'michigan_webnus_blog_page_title_enable',
                'type' => 'button_set',
                'title' => esc_html__('Blog Page Title Show/Hide', 'michigan'),
                'desc'=> wp_kses( __('By hiding this option, blog Page title will be disappearing.','michigan'), array( 'br' => array() ) ),
                'std' => '1',
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
            ),
             array(
                'id' => 'michigan_webnus_blog_page_title',
                'type' => 'text',
                'title' => esc_html__('Blog Page Title', 'michigan'),
                'std' => 'Blog',
            ),
            array(
                'id' => 'michigan_webnus_blog_sidebar',
                'type' => 'button_set',
                'title' => esc_html__('Blog Sidebar Position', 'michigan'),
                'options' => array('none'=>'None','left' => 'Left', 'right' => 'Right'),
                'std' => 'right',
            ),
            array(
                'id' => 'michigan_webnus_blog_featuredimage_enable',
                'type' => 'button_set',
                'title' => esc_html__('Featured Image on Blog', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'desc'=>  esc_html__('By disabling this option, all blog featured images will be disappearing.', 'michigan'),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_no_image',
                'type' => 'button_set',
                'title' => esc_html__('Default Blank Featured Image', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '0'
            ),
            array(
                'id' => 'michigan_webnus_no_image_src',
                'type' => 'upload',
                'title' => esc_html__('Custom Default Blank Featured Image', 'michigan'),
            ),
             array(
                'id' => 'michigan_webnus_blog_posttitle_enable',
                'type' => 'button_set',
                'title' => esc_html__('Post Title on Blog', 'michigan'),
                'desc'=> wp_kses( __('By disabling this option, all post title images will be disappearing.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_blog_excerptfull_enable',
                'type' => 'button_set',
                'title' => esc_html__('Excerpt Or Full Blog Content', 'michigan'),
                'desc'=> wp_kses( __('You can show all text of your posts in blog page or a fixed amount of characters to show for each post.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Excerpt', 'michigan'), '1' => esc_html__('&nbsp;&nbsp;&nbsp;Full&nbsp;&nbsp;&nbsp;', 'michigan')),
                'std' => '0'
            ),
            array(
                'id' => 'michigan_webnus_blog_excerpt_large',
                'type' => 'text',
                'title' => esc_html__('Excerpt Length for Large Posts', 'michigan'),
                'desc'=> wp_kses( __('Type the number of characters you want to show in the blog page for each post.','michigan'), array( 'br' => array() ) ),
                'std' => '93',
            ),
            array(
                'id' => 'michigan_webnus_blog_excerpt_list',
                'type' => 'text',
                'title' => esc_html__('Excerpt Length for List Posts', 'michigan'),
                'desc'=> wp_kses( __('Type the number of characters you want to show in the blog page for each post.','michigan'), array( 'br' => array() ) ),
                'std' => '17',
            ),
            array(
                'id' => 'michigan_webnus_blog_readmore_text',
                'type' => 'text',
                'title' => esc_html__('Read More Text', 'michigan'),
                'desc'=> wp_kses( __('You can set another name instead of read more link.','michigan'), array( 'br' => array() ) ),
                'std' => 'Continue Reading',
            ),
         array(
                'id' => 'michigan_webnus_custom_color_sep',
                'type' => 'seperator',
                'desc' => esc_html__('Metadata Options', 'michigan'),
                'sub_desc' => esc_html__('on Single Post', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_blog_meta_gravatar_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Gravatar', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_blog_meta_author_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Author', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_blog_meta_date_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Date', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_blog_meta_category_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Category', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_blog_meta_comments_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Comments', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_blog_meta_views_enable',
                'type' => 'button_set',
                'title' => esc_html__('Metadata Views', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_custom_color_sep',
                'type' => 'seperator',
                'desc' => esc_html__('Single Post Options', 'michigan'),
            ),
             array(
                'id' => 'michigan_webnus_blog_singlepost_sidebar',
                'type' => 'button_set',
                'title' => esc_html__('Single Post Sidebar Position', 'michigan'),
                'options' => array('none'=>'None','left' => 'Left', 'right' => 'Right'),
                'std' => 'right',
            ),
            array(
                'id' => 'michigan_webnus_blog_sinlge_featuredimage_enable',
                'type' => 'button_set',
                'title' => esc_html__('Featured Image', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_blog_sinlge_nextprev_enable',
                'type' => 'button_set',
                'title' => esc_html__('Next/Previous', 'michigan'),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_blog_social_share',
                'type' => 'button_set',
                'title' => esc_html__('Social Share Links', 'michigan'),
                'desc'=> wp_kses( __('By enabling this feature your visitors can share the post to social networks such as Facebook, Twitter and...','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_blog_single_authorbox_enable',
                'type' => 'button_set',
                'title' => esc_html__('Single post Authorbox', 'michigan'),
                'desc'=> wp_kses( __('This feature shows a picture of post author and some info about author.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
             array(
                'id' => 'michigan_webnus_recommended_posts',
                'type' => 'button_set',
                'title' => esc_html__('Recommended Posts', 'michigan'),
                'desc'=> wp_kses( __('This feature recommends related post to visitors.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Off', 'michigan'), '1' => esc_html__('On', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'blog_font_options',
                'type' => 'seperator',
                'desc' => esc_html__('Post Title Font Options', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_blog_title_font_family',
                'type' => 'select',
                'title' => esc_html__('Post Title Font Family', 'michigan'),
                'options' =>$fontArray, 
            ),
            array(
                'id' => 'michigan_webnus_blog_loop_title_font_size',
                'type' => 'slider',
                'title' => esc_html__('Post Title font-size on Blog', 'michigan'),
                'value' =>array('min'=>0, 'max'=>100),
                'suffix'=>'px' 
            ),
           array(
                'id' => 'michigan_webnus_blog_loop_title_line_height',
                'type' => 'slider',
                'title' => esc_html__('Post Title line-height on Blog', 'michigan'),
                'value' =>array('min'=>0, 'max'=>100) ,
                'suffix'=>'px' 
            ),
           array(
                'id' => 'michigan_webnus_blog_loop_title_font_weight',
                'type' => 'slider',
                'title' => esc_html__('Post Title font-weight on Blog', 'michigan'),
                'value' =>array('min'=>1, 'max'=>900), 
                'suffix'=>'' ,
                'step'=>100
            ),
           array(
                'id' => 'michigan_webnus_blog_loop_title_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('Post Title letter-spacing on Blog', 'michigan'),
                'value' =>array('min'=>0, 'max'=>100) ,
                'suffix'=>'px' 
            ),
            array(
                'id' => 'michigan_webnus_blog_loop_title_color',
                'type' => 'color',
                'title' => esc_html__('Post Title Color on Blog', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_blog_loop_title_hover_color',
                'type' => 'color',
                'title' => esc_html__('Post Title Hover Color on Blog', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_blog_single_post_title_font_size',
                'type' => 'slider',
                'title' => esc_html__('Post Title font-size on Single Post', 'michigan'),
                'value' =>array('min'=>0, 'max'=>100)  ,
                'suffix'=>'px' 
            ),
            array(
                'id' => 'michigan_webnus_blog_single_title_line_height',
                'type' => 'slider',
                'title' => esc_html__('Post Title line-height on Single Post', 'michigan'),
                'value' =>array('min'=>0, 'max'=>100) ,
                'suffix'=>'px' 
            ),
            array(
                'id' => 'michigan_webnus_blog_single_title_font_weight',
                'type' => 'slider',
                'title' => esc_html__('Post Title font-weight on Single Post', 'michigan'),
                'value' =>array('min'=>1, 'max'=>900) ,
                'suffix'=>'' ,
                'step'=>100
            ),
           array(
                'id' => 'michigan_webnus_blog_single_title_letter_spacing',
                'type' => 'slider',
                'title' => esc_html__('Post Title letter-spacing on Single Post', 'michigan'),
                'value' =>array('min'=>1, 'max'=>100) ,
                'suffix'=>'px' 
            ),
            array(
                'id' => 'michigan_webnus_blog_single_title_color',
                'type' => 'color',
                'title' => esc_html__('Post Title color on Single Post', 'michigan'),
            ),
        )
    );
   //Social Network Accounts
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-social.png',
        'title' => esc_html__('Social Networks', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Customize The Social Network Accounts</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            array(
                'id' => 'michigan_webnus_social_first',
                'type' => 'text',
                'title' => esc_html__('1st Social Name', 'michigan'),
                'std' => 'facebook',
            ),
            array(
                'id' => 'michigan_webnus_social_first_url',
                'type' => 'text',
                'title' => esc_html__('1st Social URL', 'michigan'),
                'std' => '#',
            ),
            array(
                'id' => 'michigan_webnus_social_second',
                'type' => 'text',
                'title' => esc_html__('2nd Social Name', 'michigan'),
                'std' => 'twitter'
            ),
            array(
                'id' => 'michigan_webnus_social_second_url',
                'type' => 'text',
                'title' => esc_html__('2nd Social URL', 'michigan'),
                'std' => '#',
            ),
            array(
                'id' => 'michigan_webnus_social_third',
                'type' => 'text',
                'title' => esc_html__('3rd Social Name', 'michigan'),
                'std' => 'linkedin'
            ),
            array(
                'id' => 'michigan_webnus_social_third_url',
                'type' => 'text',
                'title' => esc_html__('3rd Social URL', 'michigan'),
                'std' => '#',
            ),
            array(
                'id' => 'michigan_webnus_social_fourth',
                'type' => 'text',
                'title' => esc_html__('4th Social Name', 'michigan'),
                'std' => 'google-plus'
            ),
            array(
                'id' => 'michigan_webnus_social_fourth_url',
                'type' => 'text',
                'title' => esc_html__('4th Social URL', 'michigan'),
                'std' => '#',
            ),
            array(
                'id' => 'michigan_webnus_social_fifth',
                'type' => 'text',
                'title' => esc_html__('5th Social Name', 'michigan'),
                'std' => 'youtube',

            ),
            array(
                'id' => 'michigan_webnus_social_fifth_url',
                'type' => 'text',
                'title' => esc_html__('5th Social URL', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_social_sixth',
                'type' => 'text',
                'title' => esc_html__('6th Social Name', 'michigan'),
                'std' => 'pinterest',
            ),
            array(
                'id' => 'michigan_webnus_social_sixth_url',
                'type' => 'text',
                'title' => esc_html__('6th Social URL', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_social_seventh',
                'type' => 'text',
                'title' => esc_html__('7th Social Name', 'michigan'),
                'std' => 'instagram',
            ),
            array(
                'id' => 'michigan_webnus_social_seventh_url',
                'type' => 'text',
                'title' => esc_html__('7th Social URL', 'michigan'),
            ),
        )
    );
   /* Footer  */
   $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-footer.png',
        'title' => esc_html__('Footer Options', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Customize Footer</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            array(
                'id' => 'michigan_webnus_footer_instagram_bar',
                'type' => 'button_set',
                'title' => esc_html__('Footer Instagram Bar', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '0'
            ),  
             array(
                'id' => 'michigan_webnus_footer_instagram_username',
                'type' => 'text',
                'title' => esc_html__('Instagram Username', 'michigan'),
                'std' => ''
            ),
             array(
                'id' => 'michigan_webnus_footer_instagram_access',
                'type' => 'text',
                'title' => esc_html__('Instagram Access Token', 'michigan'),
                'sub_desc' => wp_kses( __('Get the this information <a target="_blank" href="https://smashballoon.com/instagram-feed/token/">here</a>.', 'michigan'), array( 'p' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) ),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_social_bar',
                'type' => 'button_set',
                'title' => esc_html__('Footer Social Bar', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'sub_desc' => esc_html__('Set in Social Networks Tab.', 'michigan'),
                'std' => '0'
            ),
             array(
                'id' => 'michigan_webnus_footer_twitter_bar',
                'type' => 'button_set',
                'title' => esc_html__('Footer Twitter Bar', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '0'
            ), 
             array(
                'id' => 'michigan_webnus_footer_twitter_username',
                'type' => 'text',
                'title' => esc_html__('Twitter Username', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_access_token',
                'type' => 'text',
                'title' => esc_html__('Twitter Access Token', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_access_token_secret',
                'type' => 'text',
                'title' => esc_html__('Twitter Access Token Secret', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_consumer_key',
                'type' => 'text',
                'title' => esc_html__('Twitter consumer key', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_consumer_secret',
                'type' => 'text',
                'title' => esc_html__('Twitter consumer secret', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_count',
                'type' => 'text',
                'title' => esc_html__('Twitter count', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_twitter_background_image',
                'type' => 'upload',
                'title' => esc_html__('Twitter Background Image', 'michigan'),
                'desc' => esc_html__('Please choose an image or insert an image url to use for the twitter backgroud.', 'michigan'),
            ),

            array(
                'id' => 'michigan_webnus_footer_subscribe_bar',
                'type' => 'button_set',
                'title' => esc_html__('Footer Subscribe Bar', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '0'
            ),
            array(
                'id' => 'michigan_webnus_footer_subscribe_text',
                'type' => 'text',
                'title' => esc_html__('Footer Subscribe Text', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => ''
            ),
             array(
                'id' => 'michigan_webnus_footer_subscribe_type',
                'type' => 'select',
                'title' => esc_html__('Subscribe Service', 'michigan'),
                'options' => array('FeedBurner' => esc_html__('FeedBurner', 'michigan'), 'MailChimp' => esc_html__('MailChimp', 'michigan')),
                'std' => 'FeedBurner'
            ),              
             array(
                'id' => 'michigan_webnus_footer_feedburner_id',
                'type' => 'text',
                'title' => esc_html__('Feedburner ID', 'michigan'),
                'std' => ''
            ),  
             array(
                'id' => 'michigan_webnus_footer_mailchimp_url',
                'type' => 'text',
                'title' => esc_html__('Mailchimp URL', 'michigan'),
                'sub_desc' => esc_html__('Mailchimp form action URL', 'michigan'),
                'std' => ''
            ),
             array(
                'id' => 'michigan_webnus_footer_type',
                'type' => 'radio_img',
                'title' => esc_html__('Footer Type', 'michigan'),
                'desc'=> wp_kses( __('Choose among these structures for your footer section. To fill these column sections you should go to Appearance > Widgets.','michigan'), array( 'br' => array() ) ),
                'options' => array('1' => array('title' => esc_html__('Footer Layout 1', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer1.png'),
                    '2' => array('title' => esc_html__('Footer Layout 2', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer2.png'),
                    '3' => array('title' => esc_html__('Footer Layout 3', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer3.png'),
                    '4' => array('title' => esc_html__('Footer Layout 4', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer4.png'),
                    '5' => array('title' => esc_html__('Footer Layout 5', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer5.png'),
                    '6' => array('title' => esc_html__('Footer Layout 6', 'michigan'), 'img' => $theme_img_dir . 'footertype/footer6.png'),
                ),
                'std' => '1'
            ),
           array(
                'id' => 'michigan_webnus_footer_color',
                'type' => 'button_set',
                'title' => esc_html__('Footer Color Style', 'michigan'),
                'desc'=> wp_kses( __('When you choose dark the text color will be white and when you choose light the text color will be dark.','michigan'), array( 'br' => array() ) ),
                'options' => array('1' => esc_html__('Dark', 'michigan'), '2' => esc_html__('Light', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_footer_background_color',
                'type' => 'color',
                'title' => esc_html__('Footer background color', 'michigan'),
                'sub_desc' => esc_html__('Pick a background color', 'michigan'),
                'std' => ''
            ),
            array(
                'id' => 'michigan_webnus_footer_background_image',
                'type' => 'upload',
                'title' => esc_html__('Footer Background Image', 'michigan'),
                'desc' => esc_html__('Please choose an image or insert an image url to use for the footer backgroud.', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_footer_bottom_enable',
                'type' => 'button_set',
                'title' => esc_html__('Footer Bottom', 'michigan'),
                'desc'=> wp_kses( __('This option shows a section below the footer that you can put copyright menu and logo in it.','michigan'), array( 'br' => array() ) ),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '0'
            ),      
            array(
                'id' => 'michigan_webnus_footer_bottom_background_color',
                'type' => 'color',
                'title' => esc_html__('Footer bottom background color', 'michigan'),
                'sub_desc' => esc_html__('Pick a background color', 'michigan'),
                'std' => ''
            ),

            array(
                'id' => 'michigan_webnus_footer_bottom_left',
                'type' => 'select',
                'title' => esc_html__('Footer Bottom Left', 'michigan'),
                'options' => array('1' => esc_html__('Logo', 'michigan'), '2' => esc_html__('Menu', 'michigan'),'3' => esc_html__('Custom Text', 'michigan'),'4' => esc_html__('Social Icons', 'michigan')),
                'std' => '3'
            ),
            array(
                'id' => 'michigan_webnus_footer_bottom_right',
                'type' => 'select',
                'title' => esc_html__('Footer Bottom Right', 'michigan'),
                'options' => array('1' => esc_html__('Logo', 'michigan'), '2' => esc_html__('Menu', 'michigan'),'3' => esc_html__('Custom Text', 'michigan'),'4' => esc_html__('Social Icons', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_footer_logo',
                'type' => 'upload',
                'title' => esc_html__('Footer Logo', 'michigan'),
                'desc' => esc_html__('Please choose an image file for footer logo.', 'michigan'),
            ),
            array(
                'id' => 'michigan_webnus_footer_logo_width',
                'type' => 'text',
                'title' => esc_html__('Footer Logo width', 'michigan'),
                'std' => '65'
            ),
            array(
                'id' => 'michigan_webnus_footer_copyright',
                'type' => 'text',
                'title' => esc_html__('Footer Custom Text', 'michigan'),
            ),
    ));

/*
        Custom css
*/
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-css.png',
        'title' => esc_html__('Custom CSS', 'michigan'),
        'desc' => wp_kses( __('<p class="description">Any custom CSS from the user should go in this field, it will override the theme CSS.</p>', 'michigan'), array( 'p' => array( 'class' => array() ) ) ),
        'fields' => array(
            array(
                'id' => 'michigan_webnus_custom_css',
                'type' => 'textarea',
                'title' => esc_html__('Your CSS Code', 'michigan'),
                'rows' => '24',
            ),
        )
    );
    /*
        Woocommerce 
*/
    $sections[] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-woo.png',
        'title' => esc_html__('Woocommerce', 'michigan'),
        'fields' => array(
            array(
                'id' => 'michigan_webnus_woo_shop_title_enable',
                'type' => 'button_set',
                'title' => esc_html__('Shop title Show/Hide', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_woo_shop_title',
                'type' => 'text',
                'title' => esc_html__('Shop page title', 'michigan'),
                'std'=>'Shop'
            ),
            array(
                'id' => 'michigan_webnus_woo_product_title_enable',
                'type' => 'button_set',
                'title' => esc_html__('Product page title Show/Hide', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std' => '1'
            ),
            array(
                'id' => 'michigan_webnus_woo_product_title',
                'type' => 'text',
                'title' => esc_html__('Product page title', 'michigan'),
                'std'=>'Product'
            ),
            array(
                'id' => 'michigan_webnus_woo_sidebar_enable',
                'type' => 'button_set',
                'title' => esc_html__('Show/Hide Sidebar', 'michigan'),
                'options' => array('0' => esc_html__('Hide', 'michigan'), '1' => esc_html__('Show', 'michigan')),
                'std'=>'1'
            ),
        )
    );
    $tabs = array();
    if (function_exists('wp_get_theme')) {
        $theme_data = wp_get_theme();
        $theme_uri = $theme_data->get('ThemeURI');
        $description = $theme_data->get('Description');
        $author = $theme_data->get('Author');
        $version = $theme_data->get('Version');
        $tags = $theme_data->get('Tags');
    } else {
        $theme_data = wp_get_theme(get_template_directory());
        $theme_uri = $theme_data['URI'];
        $description = $theme_data['Description'];
        $author = $theme_data['Author'];
        $version = $theme_data['Version'];
        $tags = $theme_data['Tags'];
    }
    $theme_info = '<div class="nhp-opts-section-desc">';
    $theme_info .= '<p class="nhp-opts-theme-data description theme-uri">' . wp_kses( __('<strong>Theme URL:</strong> ', 'michigan'), array( 'strong' => array() ) ) . '<a href="' . $theme_uri . '" target="_blank">' . $theme_uri . '</a></p>';
    $theme_info .= '<p class="nhp-opts-theme-data description theme-author">' . wp_kses( __('<strong>Author:</strong> ', 'michigan'), array( 'strong' => array() ) ) . $author . '</p>';
    $theme_info .= '<p class="nhp-opts-theme-data description theme-version">' . wp_kses( __('<strong>Version:</strong> ', 'michigan'), array( 'strong' => array() ) ) . $version . '</p>';
    $theme_info .= '<p class="nhp-opts-theme-data description theme-description">' . $description . '</p>';
    $theme_info .= '<p class="nhp-opts-theme-data description theme-tags">' . wp_kses( __('<strong>Tags:</strong> ', 'michigan'), array( 'strong' => array() ) ) . implode(', ', $tags) . '</p>';
    $theme_info .= '</div>';
    $tabs['theme_info'] = array(
        'icon' => NHP_OPTIONS_URL . 'img/admin-info.png',
        'title' => esc_html__('Theme Information', 'michigan'),
        'content' => $theme_info
    );
    global $NHP_Options;
    $NHP_Options = new NHP_Options($sections, $args, $tabs);
}
add_action('init', 'michigan_webnus_setup_framework_options', 0);
/*
 *
 * Custom function for the callback referenced above
 *
 */
function michigan_webnus_custom_field($field, $value) {
    print_r($field);
    print_r($value);
}
/*
 *
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value) {
    $error = false;
    $value = 'just testing';
    $return['value'] = $value;
    if ($error == true) {
        $return['error'] = $field;
    }
    return $return;
}
?>