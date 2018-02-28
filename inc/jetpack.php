<?php
if (!defined('ABSPATH')) die;

if (get_option('p3_jetpack_override')) {
	return;
}

if (is_admin() && isset($_GET['p3_jetpack_override'])) { // If peeps want to use Vanilla Jetpack
	update_option('p3_jetpack_override', 1);
	return;
}


function p3_add_link_to_jp_modules() {
    add_submenu_page(
        'jetpack',
        'Modules',
        'Modules',
        'manage_options',
        'p3_jp_mods',
        'p3_add_link_to_jp_modules_content' );
}
add_action('admin_menu', 'p3_add_link_to_jp_modules', 9999999);

function p3_add_link_to_jp_modules_content() {
	echo '<script>window.location.replace("'.admin_url('admin.php?page=jetpack_modules').'");</script>';
}


// SSO not default
add_filter( 'jetpack_sso_default_to_sso_login', '__return_false' );

function pipdig_p3_hide_jetpack_modules( $modules, $min_version, $max_version ) {
	if (!class_exists('Jetpack')) {
		return;
	}
	$jp_mods_to_disable = array(
	'custom-css',
	'post-by-email',
	'minileven',
	'latex',
	'gravatar-hovercards',
	'search',
	'seo-tools',
	'omnisearch',
	//'photon',
	'markdown',
	'related-posts',
	//'lazy-images',
	);
	foreach ( $jp_mods_to_disable as $mod ) {
		if ( isset( $modules[$mod] ) ) {
			unset( $modules[$mod] );
		}
	}
	return $modules;
}
add_filter( 'jetpack_get_available_modules', 'pipdig_p3_hide_jetpack_modules', 20, 3 );

function pipdig_p3_disable_jetpack_modules() {
	if (!class_exists('Jetpack')) {
		return;
	}
	if (Jetpack::is_module_active('custom-css')) {
		Jetpack::deactivate_module( 'custom-css' );
	}
	if (Jetpack::is_module_active('minileven')) {
		Jetpack::deactivate_module( 'minileven' );
	}
	if (get_theme_mod('p3_pinterest_hover_enable_posts') && Jetpack::is_module_active('lazy-images')) {
		Jetpack::deactivate_module( 'lazy-images' );
	}
	if (Jetpack::is_module_active('related-posts')) {
		Jetpack::deactivate_module( 'related-posts' );
	}
	/*
	if (!Jetpack::is_module_active('tiled-gallery') && get_option('p3_tiled_galleries_set') != 1) {
		Jetpack::activate_module( 'tiled-gallery' );
		update_option('tiled_galleries', 1);
		update_option('p3_tiled_galleries_set', 1);
	}
	*/
	
	// plugin not compatible with Jetpack comments - https://wordpress.org/plugins/anti-spam/
	if (Jetpack::is_module_active('comments')) {
		$plugins = array(
			'anti-spam/anti-spam.php',
		);
		deactivate_plugins($plugins);
	}
	
}
add_action( 'admin_init', 'pipdig_p3_disable_jetpack_modules' );

// quit nagging
function p3_jp_styles() {
	?>
	<style>
	.jp-jitm, .jitm-banner, .jp-wpcom-connect__container {display:none!important}
	</style>
	<?php
}
add_action('admin_head', 'p3_jp_styles', 9999);

/*
function p3_jp_wpcom_check() {
	
	$wp_com = false;

	$site_url = get_site_url();
	$domain = parse_url($site_url, PHP_URL_HOST);
	$dns = dns_get_record($domain, DNS_NS);
	if (isset($dns[0]['target']) && (strpos($dns[0]['target'], 'wordpress.com') !== false)) {
		$wp_com = true;
	} elseif (isset($dns[1]['target']) && (strpos($dns[1]['target'], 'wordpress.com') !== false)) {
		$wp_com = true;
	}

	if ($wp_com) {
		?>
		<p>test</p>
		<?php
	}
	
}
add_action( 'admin_notices', 'p3_jp_wpcom_check' );
*/