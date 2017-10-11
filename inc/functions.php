<?php

if (!defined('ABSPATH')) die;

// load plugin check function, just in case theme hasn't
if ( !function_exists( 'pipdig_plugin_check' ) ) {
	function pipdig_plugin_check( $plugin_name ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active($plugin_name) ) {
			return true;
		} else {
			return false;
		}
	}
}

// add data rel for lightbox (still in theme functions)
/*
if (!function_exists('p3_lightbox_rel')) {
	function p3_lightbox_rel($content) {
		$content = str_replace('><img',' data-imagelightbox="g"><img', $content);
		return $content;
	}
	add_filter('the_content','p3_lightbox_rel');
}
*/

// remove default gallery shortcode styling
//add_filter( 'use_default_gallery_style', '__return_false' );

if ( !function_exists( 'pipdig_strip' ) ) {
	function pipdig_strip($data, $tags = '') {
		return strip_tags(trim($data), $tags);
	}
}

// check if this feature is enabled for this theme
// any enabled themes are passed in via array
function p3_theme_enabled($enabled_themes) {
	$this_theme = get_option('pipdig_theme');
	foreach($enabled_themes as $enabled_theme) {
		if ($this_theme == $enabled_theme) {
			return 1;
		} 
	}
	return 0;
}

// image catch
function pipdig_p3_catch_that_image() {
	global $post;
	$default_img = 'https://pipdigz.co.uk/p3/img/catch-placeholder.jpg';
	preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $post->post_content, $img);
	if (!empty($img[1])){
		$first_img = $img[1];
		if (($first_img == 'http://assets.rewardstyle.com/images/search/350.gif') || ($first_img == '//assets.rewardstyle.com/images/search/350.gif')) {
			return $default_img;
		} else {
			return esc_url($first_img);
		}
	} else {
		return $default_img;
	}
}

// truncate stuff
function pipdig_p3_truncate($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]).'&hellip;';
	}
	return $text;
}

// dns prefetch
/*
if (!function_exists('pipdig_p3_dns_prefetch')) {
	function pipdig_p3_dns_prefetch() {
		?>
		<link rel="dns-prefetch" href="//ajax.googleapis.com" />
		<link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
		<?php
	}
	add_action('wp_head', 'pipdig_p3_dns_prefetch', 1, 1);
}
*/

// use public CDNs for jquery
/*
if (!class_exists('JCP_UseGoogleLibraries') && !function_exists('pipdig_p3_cdn')) {
	function pipdig_p3_cdn() {
		global $wp_scripts;
		if (!is_admin()) {
			$jquery_ver = $wp_scripts->registered['jquery']->ver;
			$jquery_migrate_ver = $wp_scripts->registered['jquery-migrate']->ver;
			wp_deregister_script('jquery');
			wp_deregister_script('jquery-migrate');
			wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/'.$jquery_ver.'/jquery.min.js', false, null, false);
			wp_enqueue_script('jquery-migrate', '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/'.$jquery_migrate_ver.'/jquery-migrate.min.js', false, null, false);
		}
	}
	add_action('wp_enqueue_scripts', 'pipdig_p3_cdn', 9999);
}
*/


include('functions/api.php');

// Add Featured Image to feed if using excerpt mode, or just add the full content if not
if (!class_exists('Rss_Image_Feed')) {
function pipdig_p3_rss_post_thumbnail($content) {
		
	if (get_option('rss_use_excerpt')) {
		global $post;
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
		if ($thumb) {
			$img = $thumb['0'];
		} else {
			$img = pipdig_p3_catch_that_image();
		}
		$content = '<p><img src="'.esc_url($img).'" alt="'.esc_attr($post->post_title).'" width="320" /></p><p>'.strip_shortcodes(get_the_excerpt()).'</p>';
	}

	return strip_shortcodes($content);
		
}
add_filter('the_excerpt_rss', 'pipdig_p3_rss_post_thumbnail');
add_filter('the_content_feed', 'pipdig_p3_rss_post_thumbnail');
}


function p3_flush_htacess() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function p3_htaccess_edit($rules) {
$p3_rules = "
# Hector
Redirect 301 /feeds/posts/default /feed

<ifmodule mod_deflate.c>
AddOutputFilterByType DEFLATE text/text text/html text/plain text/xml text/css application/x-javascript application/javascript text/javascript
</ifmodule>

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{QUERY_STRING} ^m=1$
RewriteRule ^(.*)$ /$1? [R=301,L]
</IfModule>
# /Hector
";
return $p3_rules . $rules;
}
add_filter('mod_rewrite_rules', 'p3_htaccess_edit');


function pipdig_p3_emmmm_heeey() {
	?>
	<script>	
	jQuery(document).ready(function($) {
		$(window).scroll(function() {
			 if($(window).scrollTop() + $(window).height() == $(document).height()) {
				$(".scrollbox-bottom-right,.widget_eu_cookie_law_widget,#adhesion_desktop_wrapper,#cookie-law-bar,#cookie-law-info-bar,.cc_container,#catapult-cookie-bar,.mailmunch-scrollbox,#barritaloca,#upprev_box,#at4-whatsnext,#cookie-notice,.mailmunch-topbar,#cookieChoiceInfo, #eu-cookie-law,.sumome-scrollbox-popup,.tplis-cl-cookies,#eu-cookie,.pea_cook_wrapper,#milotree_box,#cookie-law-info-again,#jquery-cookie-law-script").css('opacity', '0').css('visibility', 'hidden');
			 } else {
				$(".scrollbox-bottom-right,.widget_eu_cookie_law_widget,#adhesion_desktop_wrapper,#cookie-law-bar,#cookie-law-info-bar,.cc_container,#catapult-cookie-bar,.mailmunch-scrollbox,#barritaloca,#upprev_box,#at4-whatsnext,#cookie-notice,.mailmunch-topbar,#cookieChoiceInfo, #eu-cookie-law,.sumome-scrollbox-popup,.tplis-cl-cookies,#eu-cookie,.pea_cook_wrapper,#milotree_box,#cookie-law-info-again,#jquery-cookie-law-script").css('opacity', '1').css('visibility', 'visible');
			 }
		});
	});
	</script>
	<!-- p3 v<?php echo PIPDIG_P3_V; ?> | <?php echo wp_get_theme()->get('Name'); ?> v<?php echo wp_get_theme()->get('Version'); ?> | <?php echo PHP_VERSION; ?> -->
	<?php
}
add_action('wp_footer','pipdig_p3_emmmm_heeey', 9999);

// comments count
function pipdig_p3_comment_count() {
	if (!post_password_required()) {
		$comment_count = get_comments_number();
		if ($comment_count == 0) {
			$comments_text = __('Leave a comment', 'p3');
		} elseif ($comment_count == 1) {
			$comments_text = __('1 Comment', 'p3');
		} else {
			$comments_text = number_format_i18n($comment_count).' '.__('Comments', 'p3');
			if (get_locale() == 'pl_PL') {
				$comments_text = 'Komentarzy: '.number_format_i18n($comment_count);
			}
		}
		echo $comments_text;
	}
}

// comments nav
function pipdig_p3_comment_nav() {
	echo '<div class="nav-previous">'.previous_comments_link('<i class="fa fa-arrow-left"></i> '.__('Older Comments', 'p3')).'</div>';
	echo '<div class="nav-next">'.next_comments_link(__('Newer Comments', 'p3').' <i class="fa fa-arrow-right"></i>').'</div>';
}

// allow 'text-transform' in wp_kses http://wordpress.stackexchange.com/questions/173526/why-is-wp-kses-not-keeping-style-attributes-as-expected
function p3_safe_styles($styles) {
	array_push($styles, 'text-transform');
	return $styles;
}
add_filter('safe_style_css','p3_safe_styles');


// get image ID from url - https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
// seems to confuse the same filenames. need to check.
function pipdig_get_attachment_id( $url ) {

	$attachment_id = 0;
	$dir = wp_upload_dir();

	if ( false !== strpos($url, $dir['baseurl'] . '/') ) { // Is URL in uploads directory?

		$file = basename($url);

		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);

		$query = new WP_Query($query_args);

		if ( $query->have_posts() ) {

			foreach ( $query->posts as $post_id ) {
				$meta = wp_get_attachment_metadata( $post_id );
				$original_file = basename($meta['file']);
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array($file, $cropped_image_files) ) {
					$attachment_id = $post_id;
					break;
				}
			}

		}

	}

	return $attachment_id;
}

// no pages in search
function p3_no_pages_search($query) {
	if (is_admin()) {
		return;
	}
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts', 'p3_no_pages_search');

// Yoast breadcrumbs
function p3_yoast_seo_breadcrumbs() {
	if (!function_exists('yoast_breadcrumb') || !is_singular()) {
		return;
	}
	yoast_breadcrumb('<div id="p3_yoast_breadcrumbs">','</div>');
}
add_action('p3_top_site_main_container', 'p3_yoast_seo_breadcrumbs');

function p3_slicknav_brand() {
	$links = get_option('pipdig_links');
	$brand = '';
	$count = 0;

	if (class_exists('Woocommerce') && $count < 6) {
		global $woocommerce;
		$brand .= '<a href="'.$woocommerce->cart->get_cart_url().'" rel="nofollow"><i class="fa fa-shopping-cart"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['twitter']) && get_theme_mod('p3_navbar_twitter', 1)) {
		$brand .= '<a href="'.esc_url($links['twitter']).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['instagram']) && get_theme_mod('p3_navbar_instagram', 1)) {
		$brand .= '<a href="'.esc_url($links['instagram']).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['facebook']) && get_theme_mod('p3_navbar_facebook', 1)) {
		$brand .= '<a href="'.esc_url($links['facebook']).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['bloglovin']) && get_theme_mod('p3_navbar_bloglovin', 1)) {
		$brand .= '<a href="'.esc_url($links['bloglovin']).'" target="_blank" rel="nofollow"><i class="fa fa-plus"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['pinterest']) && get_theme_mod('p3_navbar_pinterest', 1)) {
		$brand .= '<a href="'.esc_url($links['pinterest']).'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['snapchat']) && get_theme_mod('p3_navbar_snapchat', 1)) {
		$brand .= '<a href="'.esc_url($links['snapchat']).'" target="_blank" rel="nofollow"><i class="fa fa-snapchat-ghost"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['youtube']) && get_theme_mod('p3_navbar_youtube', 1)) {
		$brand .= '<a href="'.esc_url($links['youtube']).'" target="_blank" rel="nofollow"><i class="fa fa-youtube-play"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['email']) && get_theme_mod('p3_navbar_email', 1)) {
		$brand .= '<a href="mailto:'.sanitize_email($links['email']).'" target="_blank" rel="nofollow"><i class="fa fa-envelope"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['tumblr']) && get_theme_mod('p3_navbar_tumblr', 1)) {
		$brand .= '<a href="'.esc_url($links['tumblr']).'" target="_blank" rel="nofollow"><i class="fa fa-tumblr"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['linkedin']) && get_theme_mod('p3_navbar_linkedin', 1)) {
		$brand .= '<a href="'.esc_url($links['linkedin']).'" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['soundcloud']) && get_theme_mod('p3_navbar_soundcloud', 1)) {
		$brand .= '<a href="'.esc_url($links['soundcloud']).'" target="_blank" rel="nofollow"><i class="fa fa-soundcloud"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['spotify']) && get_theme_mod('p3_navbar_spotify', 1)) {
		$brand .= '<a href="'.esc_url($links['spotify']).'" target="_blank" rel="nofollow"><i class="fa fa-spotify"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['itunes']) && get_theme_mod('p3_navbar_itunes', 1)) {
		$brand .= '<a href="'.esc_url($links['itunes']).'" target="_blank" rel="nofollow"><i class="fa fa-apple"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['flickr']) && get_theme_mod('p3_navbar_flickr', 1)) {
		$brand .= '<a href="'.esc_url($links['flickr']).'" target="_blank" rel="nofollow"><i class="fa fa-flickr"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['vk']) && get_theme_mod('p3_navbar_vk', 1)) {
		$brand .= '<a href="'.esc_url($links['vk']).'" target="_blank" rel="nofollow"><i class="fa fa-vk"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['google_plus']) && get_theme_mod('p3_navbar_google_plus', 1)) {
		$brand .= '<a href="'.esc_url($links['google_plus']).'" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['twitch']) && get_theme_mod('p3_navbar_twitch', 1)) {
		$brand .= '<a href="'.esc_url($links['twitch']).'" target="_blank" rel="nofollow"><i class="fa fa-twitch"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['stumbleupon']) && get_theme_mod('p3_navbar_stumbleupon', 1)) {
		$brand .= '<a href="'.esc_url($links['stumbleupon']).'" target="_blank" rel="nofollow"><i class="fa fa-stumbleupon"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['etsy']) && get_theme_mod('p3_navbar_etsy', 1)) {
		$brand .= '<a href="'.esc_url($links['etsy']).'" target="_blank" rel="nofollow"><i class="fa fa-etsy"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['reddit']) && get_theme_mod('p3_navbar_reddit', 1)) {
		$brand .= '<a href="'.esc_url($links['reddit']).'" target="_blank" rel="nofollow"><i class="fa fa-reddit"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['digg']) && get_theme_mod('p3_navbar_digg', 1)) {
		$brand .= '<a href="'.esc_url($links['digg']).'" target="_blank" rel="nofollow"><i class="fa fa-digg"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['houzz']) && get_theme_mod('p3_navbar_houzz', 1)) {
		$brand .= '<a href="'.esc_url($links['houzz']).'" target="_blank" rel="nofollow"><i class="fa fa-houzz"></i></a>';
		$count++;
	}
	if (($count < 6) && !empty($links['rss']) && get_theme_mod('p3_navbar_rss', 1)) {
		$brand .= '<a href="'.esc_attr($links['rss']).'" target="_blank" rel="nofollow"><i class="fa fa-rss"></i></a>';
		$count++;
	}

	if (empty($brand)) {
		$brand = esc_attr(get_bloginfo());
	}
	
	return $brand;
}

function is_pipdig_lazy() {
	if (get_theme_mod('pipdig_lazy')) {
		return true;
	} else {
		return false;
	}
}

function p3_lazy_script() {
	if (!get_theme_mod('pipdig_lazy')) {
		return;
	}
	?>
	<script>
	jQuery(document).ready(function($) {
		$(".pipdig_lazy").Lazy({
			effect: 'fadeIn',
			effectTime: 500,
		});
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'p3_lazy_script', 99999 );

function p3_remove_ellipses_script() {
	if (get_theme_mod('excerpt_length_num') === 0) {
	?>
	<script>
	jQuery(document).ready(function($) {
		jQuery.fn.highlight = function (str, className) {    
				var regex = new RegExp(str, "gi");
				return this.each(function () {
					this.innerHTML = this.innerHTML.replace(regex, function(matched) {return "";});
				});
		};
		$(".entry-summary, .pipdig_3_col_grid_item").highlight("…","highlight");
	});
	</script>
	<?php
	}
}
add_action( 'wp_footer', 'p3_remove_ellipses_script', 999 );



include(plugin_dir_path(__FILE__).'functions/social-sidebar.php');
include(plugin_dir_path(__FILE__).'functions/full_screen_landing_image.php');
include(plugin_dir_path(__FILE__).'functions/top_menu_bar.php');
include(plugin_dir_path(__FILE__).'functions/post-options.php');
include(plugin_dir_path(__FILE__).'functions/shares.php');
include(plugin_dir_path(__FILE__).'functions/related-posts.php');
include(plugin_dir_path(__FILE__).'functions/instagram.php');
include(plugin_dir_path(__FILE__).'functions/youtube.php');
include(plugin_dir_path(__FILE__).'functions/pinterest.php');
include(plugin_dir_path(__FILE__).'functions/pinterest_hover.php');
include(plugin_dir_path(__FILE__).'functions/social_footer.php');
include(plugin_dir_path(__FILE__).'functions/navbar_icons.php');
include(plugin_dir_path(__FILE__).'functions/feature_header.php');
include(plugin_dir_path(__FILE__).'functions/trending.php');
include(plugin_dir_path(__FILE__).'functions/post_slider_site_main_width.php');
//include(plugin_dir_path(__FILE__).'functions/post_slider_site_main_width_sq.php');
include(plugin_dir_path(__FILE__).'functions/post_slider_posts_column.php');
include(plugin_dir_path(__FILE__).'functions/width_customizer.php');
//include(plugin_dir_path(__FILE__).'functions/popup.php');
include(plugin_dir_path(__FILE__).'functions/featured_cats.php');
include(plugin_dir_path(__FILE__).'functions/featured_panels.php');
include(plugin_dir_path(__FILE__).'functions/rewardstyle.php');
include(plugin_dir_path(__FILE__).'functions/schema.php');
include(plugin_dir_path(__FILE__).'functions/header_image.php');

// bundled
if (class_exists('RW_Meta_Box') && function_exists('rwmb_get_registry')) {
	include_once(plugin_dir_path(__FILE__).'bundled/mb-settings-page/mb-settings-page.php');
	include_once(plugin_dir_path(__FILE__).'bundled/meta-box-include-exclude/meta-box-include-exclude.php');
	include_once(plugin_dir_path(__FILE__).'bundled/mb-term-meta/mb-term-meta.php');
}