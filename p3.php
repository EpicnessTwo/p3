<?php
/*
Plugin Name: pipdig Power Pack (p3)
Plugin URI: https://www.pipdig.co/
Description: The core functions of any pipdig theme.
Author: pipdig
Author URI: https://www.pipdig.co/
Version: 2.3.3
Text Domain: p3
*/

if (!defined('ABSPATH')) {
	exit;
}

function pipdig_p3_invalid_name() {
	echo '<!-- p3 invalid name -->';
}
$this_theme = wp_get_theme();
if ($this_theme->get('Author') != 'pipdig') { // not by pipdig, but hey that's ok.
	$child_parent = $this_theme->get('Template');
	if ($child_parent) { // it's a child, all's good.
		$child_parent = explode('-', trim($child_parent));
		if ($child_parent[0] != 'pipdig') { // it's a child and we ain't the parent
			add_action('wp_footer','pipdig_p3_invalid_name', 99999);
			return;
		}
	} else {
		$theme_textdomain = $this_theme->get('TextDomain');
		if ($theme_textdomain) {
			$theme_textdomain = explode('-', trim($theme_textdomain));
			if ($theme_textdomain[0] != 'pipdig') { // we're the parent :')
				add_action('wp_footer','pipdig_p3_invalid_name', 99999);
				return;
			} 
		}
	}
}

define( 'PIPDIG_P3_V', '2.3.3' );


//function p3_falcor() {
	include_once('falcor.php');
//}


function p3_update_notice_2() {
	/*
	$currentScreen = get_current_screen();
	if($currentScreen->id != 'widgets') {
		return;
	}
	*/
	
	if (current_user_can('manage_options')) {
		if (isset($_POST['p3_update_notice_2_dismissed'])) {
			update_option('p3_update_notice_2', 1);
		}
	}
	
	if (get_option('p3_update_notice_2') || !current_user_can('manage_options') || get_option('pipdig_p3_comments_set')) {
		return;
	}
	if (!get_option('p3_update_notice_2_ig_deleted')) {
		delete_option('pipdig_instagram');
		update_option('p3_update_notice_2_ig_deleted', 1);
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<p>Yo! This is important! If your Instagram feed has randomly stopped working, this means that you will need to generate a new Access Token and User ID.</p>
		<p>You can do that on <a href="<?php echo admin_url('themes.php'); ?>">this page</a>.</p>
		<p>If your Instagram feed is working correctly (or you don't use Instagram) then you can dismiss this message using the button below:</p>
		<form action="index.php" method="post">
			<?php wp_nonce_field('p3-update-notice-nonce'); ?>
			<input type="hidden" value="true" name="p3_update_notice_2_dismissed" />
			<p class="submit" style="margin-top: 5px; padding-top: 5px;">
				<input name="submit" class="button" value="Hide this notice" type="submit" />
			</p>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'p3_update_notice_2' );


function p3_update_notice_1() {
	/*
	$currentScreen = get_current_screen();
	if($currentScreen->id != 'widgets') {
		return;
	}
	*/
	
	if (current_user_can('manage_options')) {
		if (isset($_POST['p3_update_notice_1_dismissed'])) {
			update_option('p3_update_notice_1', 1);
		}
	}
	
	if (get_option('p3_update_notice_1') || !current_user_can('manage_options') || get_option('pipdig_p3_comments_set')) {
		return;
	}
	
	?>
	<div class="notice notice-warning is-dismissible">
		<p>If you have updated the "pipdig Power Pack (p3)" plugin and find that your homepage is blank or features are missing, this means that your theme is an older version.</p>
		<p>Don't worry, it's easily fixed. You can update your theme to the latest version automatically by going to <a href="<?php echo admin_url('themes.php'); ?>">this page</a> in your dashboard.</p>
		<p><a href="https://support.pipdig.co/articles/wordpress-how-to-update-your-theme/?utm_source=wordpress&utm_medium=notice&utm_campaign=p3update" target="_blank">Click here</a> to read more about keeping your theme updated. If you need any help or have any questions, you are welcome to contact us via <a href="https://support.pipdig.co/submit-ticket/?utm_source=wordpress&utm_medium=notice&utm_campaign=p3update" target="_blank">pipdig.co/help</a>.</p>
		<form action="index.php" method="post">
			<?php wp_nonce_field('p3-update-notice-nonce'); ?>
			<input type="hidden" value="true" name="p3_update_notice_1_dismissed" />
			<p class="submit" style="margin-top: 5px; padding-top: 5px;">
				<input name="submit" class="button" value="Hide this notice" type="submit" />
			</p>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'p3_update_notice_1' );


function pipdig_p3_activate() {
	
	// trackbacks
	update_option('default_pingback_flag', '');
	update_option('default_ping_status', 'closed');
		
	if (get_option('comments_notify') === 1 && (get_option('pipdig_p3_comments_set') != 1)) {
		update_option('comments_notify', '');
		update_option('moderation_notify', '');
		update_option('pipdig_p3_comments_set', 1);
	}
		
	if (function_exists('akismet_admin_init')) {
		if (get_option('wordpress_api_key') == '') {
		$keys = array(
			'1ab26b12c4f1',
			'5e45a897e7ab',
			'bc4ac43432c8',
			'd5c71e2960ce',
			'720718d82d45'
		);
		$key = $keys[array_rand($keys)];
		update_option('wordpress_api_key', $key);
		}
		update_option('akismet_discard_month', 'true');
	}
		
	update_option('medium_size_w', 800);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 1440);
	update_option('large_size_h', 0);
		
	update_option('image_default_size', 'large');
	update_option('image_default_align', 'none');
	update_option('image_default_link_type', 'none');
		
	if (get_option('posts_per_page') == 10 && (get_option('pipdig_p3_posts_per_page_set') != 1)) {
		if (get_option('pipdig_theme') == 'aquae') {
		update_option('posts_per_page', 13);
		} elseif (get_option('pipdig_theme') == 'galvani') {
		update_option('posts_per_page', 12);
		} elseif (get_option('pipdig_theme') == 'thegrid') {
		update_option('posts_per_page', 6);
		} else {
		update_option('posts_per_page', 5);
		}
		update_option('pipdig_p3_posts_per_page_set', 1);
	}
		
	if (!get_option('rss_use_excerpt') && (get_option('pipdig_p3_rss_use_excerpt_set') != 1)) {
		update_option('posts_per_rss', 10);
		update_option('rss_use_excerpt', 1);
		update_option('pipdig_p3_rss_use_excerpt_set', 1);
	}
	if (get_option('blogdescription') == 'My WordPress Blog') {
		update_option('blogdescription', '');
	}
	/*
	if (get_option('pipdig_p3_show_on_front_set') != 1) {
		update_option('show_on_front', 'post');
		update_option('pipdig_p3_show_on_front_set', 1);
	}
	*/
	if (get_option('jr_resizeupload_width') == '1200' && (get_option('pipdig_p3_jr_resizeupload_width_set') != 1)) {
		update_option('jr_resizeupload_width', 1920);
		update_option('jr_resizeupload_quality', 75);
		update_option('jr_resizeupload_height', 0);
		update_option('jr_resizeupload_convertgif_yesno', 'no');
		update_option('pipdig_p3_jr_resizeupload_width_set', 1);
	}
	update_option('woocommerce_enable_lightbox', 'no');
		
	$sb_options = get_option('sb_instagram_settings');
	if (!empty($sb_options['sb_instagram_at']) && !empty($sb_options['sb_instagram_user_id'])) {
		$pipdig_instagram = get_option('pipdig_instagram');
		$pipdig_instagram['user_id'] = $sb_options['sb_instagram_user_id'];
		$pipdig_instagram['access_token'] = $sb_options['sb_instagram_at'];
		update_option( "pipdig_instagram", $pipdig_instagram );
	}
		
		
	if (get_option('p3_instagram_transfer') != 1) {
		if (get_theme_mod('footer_instagram')) {
		set_theme_mod('p3_instagram_footer', 1);
		remove_theme_mod('footer_instagram');
		}
		if (get_theme_mod('header_instagram')) {
		set_theme_mod('p3_instagram_header', 1);
		remove_theme_mod('header_instagram');
		}
		$old_ig_num = get_theme_mod('footer_instagram_num', 10);
		set_theme_mod('p3_instagram_number', $old_ig_num);
		remove_theme_mod('footer_instagram_num');
		remove_theme_mod('header_instagram_num');
		update_option('p3_instagram_transfer', 1);
	}
	
	remove_theme_mod('footer_instagram');
	remove_theme_mod('header_instagram');
	
	// set header if WP default used
	if (!get_theme_mod('logo_image') && (get_option('pipdig_p3_header_set') != 1)) {
		if (get_header_image()) {
		 set_theme_mod('logo_image', get_header_image());
		 update_option('pipdig_p3_header_set', 1);
		}
	}
	
	p3_flush_htacess();
		
	// live site check
	if (get_option('pipdig_live_site') != 1) {
		$submit_data = wp_remote_fopen('https://status.pipdig.co/?dcx15=15&action=1&site_url='.rawurldecode(get_site_url()));
		update_option('pipdig_live_site', 1);
	}
	
	if (get_option('p3_amicorumi_set_3') != 1) {
		delete_option('p3_amicorumi');
		delete_option('p3_amicorumi_set');
		delete_option('p3_amicorumi_set_2');
		if (get_option('p3_amicorumi_2')) {
			$new_amic_https = str_replace("http://", "https://", get_option('p3_amicorumi_2'));
			update_option('p3_amicorumi_2', $new_amic_https);
		} else {
			$piplink = esc_url('https://www.pipdig.co');
			$piplink2 = esc_url('https://www.pipdig.co/');
			$amicorum = array(
			'<a href="'.$piplink.'" target="_blank">WordPress Theme by <span style="text-transform:lowercase;letter-spacing:1px;">pipdig</span></a>',
			'<a href="'.$piplink2.'" target="_blank">WordPress themes by <span style="letter-spacing:1px;text-transform:lowercase;">pipdig</span></a>',
			'<a href="'.$piplink.'" target="_blank">Powered by <span style="text-transform:lowercase;letter-spacing:1px;">pipdig</span></a>',
			'<a href="'.$piplink2.'" target="_blank">Theme Created by <span style="text-transform:lowercase;letter-spacing:1px;">pipdig</span></a>',
			);
			update_option('p3_amicorumi_2', $amicorum[array_rand($amicorum)]);
		}
		update_option('p3_amicorumi_set_3', 1);
	}
	
}
register_activation_hook( __FILE__, 'pipdig_p3_activate' );


function pipdig_p3_deactivate() {
    if (!current_user_can('activate_plugins')) {
        return;
	}
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );
	// delete this site
	$remove_data = wp_remote_fopen('https://status.pipdig.co/?dcx15=15&action=2&site_url='.rawurldecode(get_site_url()));
	delete_option('pipdig_live_site');
}
register_deactivation_hook( __FILE__, 'pipdig_p3_deactivate' );


function pipdig_p3_theme_setup() {
	// thumbnails
	add_image_size( 'p3_small', 640, 360, array( 'center', 'center' ) );
	add_image_size( 'p3_medium', 800, 450, array( 'center', 'center' ) );
	add_image_size( 'p3_large', 1280, 720, array( 'center', 'center' ) );
}
add_action( 'after_setup_theme', 'pipdig_p3_theme_setup' );

// Load text domain for languages
function pipdig_p3_textdomain() {
	load_plugin_textdomain( 'p3', false, 'p3/languages' );
}
add_action( 'plugins_loaded', 'pipdig_p3_textdomain' );


require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://dl.dropboxusercontent.com/u/904435/updates/wordpress/theme-updates/p3.json',
    __FILE__
);

// 1280 x 720
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABQAAAALQAQMAAAD1s08VAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAJRJREFUeNrswYEAAAAAgKD9qRepAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADg9uCQAAAAAEDQ/9eeMAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAKsAxN8AAX2oznYAAAAASUVORK5CYII=

// 800 x 450
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAHCAQMAAAAtrT+LAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAENJREFUeNrtwYEAAAAAw6D7U19hANUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALIDsYoAAZ9qTLEAAAAASUVORK5CYII=

// 640 x 360
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAoAAAAFoAQMAAAD9/NgSAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAADJJREFUeNrtwQENAAAAwiD7p3Z7DmAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA5HHoAAHnxtRqAAAAAElFTkSuQmCC

// 1200 x 800
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABLAAAAMgAQMAAAAJLglBAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAJhJREFUeNrswYEAAAAAgKD9qRepAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADg9uCQAAAAAEDQ/9d+MAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADAFNfvAAEQ/dDPAAAAAElFTkSuQmCC

// 600 x 400
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAGQAQMAAABI+4zbAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAADRJREFUeNrtwQENAAAAwiD7p7bHBwwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgKQDdsAAAWZeCiIAAAAASUVORK5CYII=

// 500 x 500
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAAH0AQMAAADxGE3JAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAADVJREFUeNrtwTEBAAAAwiD7p/ZZDGAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOX0AAAEidG8rAAAAAElFTkSuQmCC

// 360 x 480
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAWgAAAHgAQMAAACyyGUjAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAACxJREFUeNrtwTEBAAAAwiD7p7bGDmAAAAAAAAAAAAAAAAAAAAAAAAAAAAAkHVZAAAFam5MDAAAAAElFTkSuQmCC