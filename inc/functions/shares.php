<?php

if (!defined('ABSPATH')) {
	exit;
}

function pipdig_p3_social_shares() {
	
	if (get_theme_mod('hide_social_sharing')) {
		return;
	}
		
	if (get_the_post_thumbnail() != '') {
		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id());
		$img = $thumb['0'];
	} else {
		$img = pipdig_p3_catch_that_image();
	}
	$link = rawurlencode(get_the_permalink());
	$title = rawurlencode(get_the_title());
	$summary = rawurlencode(get_the_excerpt());
		
	$twitter_handle = get_option('p3_twitter_handle');
	$via_handle = '';
	if (!empty($twitter_handle)) {
		$via_handle = '&via='.$twitter_handle;
	}
		
	$output = '';
	
	if (get_theme_mod('p3_share_facebook', 1)) {
		$output .= '<a href="//www.facebook.com/sharer.php?u='.$link.'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a>';
	}
	if (get_theme_mod('p3_share_twitter', 1)) {
		$output .= '<a href="//twitter.com/share?url='.$link.'&text='.$title.$via_handle.'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a>';
	}
	if (get_theme_mod('p3_share_pinterest', 1)) {
		$output .= '<a href="//pinterest.com/pin/create/link/?url='.$link.'&media='.$img.'&description='.$title.'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a>';
	}
	if (get_theme_mod('p3_share_tumblr', 1)) {
		$output .= '<a href="//www.tumblr.com/widgets/share/tool?canonicalUrl='.$link.'&title='.$title.'" target="_blank" rel="nofollow"><i class="fa fa-tumblr"></i></a>';
	}
	if (get_theme_mod('p3_share_google_plus')) {
		$output .= '<a href="//plus.google.com/share?url='.$link.'" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i></a>';
	}
	if (get_theme_mod('p3_share_stumbleupon')) {
		$output .= '<a href="//www.stumbleupon.com/submit?url='.$link.'&title='.$title.'" target="_blank" rel="nofollow"><i class="fa fa-stumbleupon"></i></a>';
	}
	
	echo '<div class="addthis_toolbox">'.__('Share:', 'p3').' '.$output.'</div>';
}




// customiser
if (!class_exists('pipdig_p3_social_shares_Customiser')) {
	class pipdig_p3_social_shares_Customiser {
		public static function register ( $wp_customize ) {
			
			$wp_customize->add_section( 'pipdig_p3_shares_section', 
				array(
					'title' => __( 'Sharing Icons', 'p3' ),
					'description'=> __( 'Use these options to control which social sharing icons should be displayed.', 'p3' ),
					'capability' => 'edit_theme_options',
					'priority' => 116,
				) 
			);

			// Hide social sharing icons
			$wp_customize->add_setting('hide_social_sharing',
				array(
					'default' => 0,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('hide_social_sharing',
				array(
					'type' => 'checkbox',
					'label' => __( 'Disable all icons', 'p3' ),
					'description' => __( 'Select this option to completely remove this feature.', 'p3' ),
					'section' => 'pipdig_p3_shares_section',
				)
			);
			
			// Facebook
			$wp_customize->add_setting('p3_share_facebook',
				array(
					'default' => 1,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_facebook',
				array(
					'type' => 'checkbox',
					'label' => 'Facebook',
					'section' => 'pipdig_p3_shares_section',
				)
			);

			// twitter
			$wp_customize->add_setting('p3_share_twitter',
				array(
					'default' => 1,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_twitter',
				array(
					'type' => 'checkbox',
					'label' => 'Twitter',
					'section' => 'pipdig_p3_shares_section',
				)
			);

			// tumblr
			
			$wp_customize->add_setting('p3_share_tumblr',
				array(
					'default' =>  1,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_tumblr',
				array(
					'type' => 'checkbox',
					'label' => 'Tumblr',
					'section' => 'pipdig_p3_shares_section',
				)
			);

			// pinterest
			$wp_customize->add_setting('p3_share_pinterest',
				array(
					'default' => 1,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_pinterest',
				array(
					'type' => 'checkbox',
					'label' => 'Pinterest',
					'section' => 'pipdig_p3_shares_section',
				)
			);

			// google_plus
			$wp_customize->add_setting('p3_share_google_plus',
				array(
					'default' => 0,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_google_plus',
				array(
					'type' => 'checkbox',
					'label' => 'Google Plus',
					'section' => 'pipdig_p3_shares_section',
				)
			);

			// linkedin
			/*
			$wp_customize->add_setting('p3_share_linkedin',
				array(
					'default' => 0,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_linkedin',
				array(
					'type' => 'checkbox',
					'label' => 'Linkedin',
					'section' => 'pipdig_p3_shares_section',
				)
			);
			*/
			// stumbleupon
			$wp_customize->add_setting('p3_share_stumbleupon',
				array(
					'default' => 0,
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control('p3_share_stumbleupon',
				array(
					'type' => 'checkbox',
					'label' => 'Stumbleupon',
					'section' => 'pipdig_p3_shares_section',
				)
			);

		}
	}
	add_action( 'customize_register' , array( 'pipdig_p3_social_shares_Customiser' , 'register' ) );
}