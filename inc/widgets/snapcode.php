<?php
if (!defined('ABSPATH')) die;

// https://feelinsonice-hrd.appspot.com/web/deeplink/snapcode?username=pipdig.co&type=PNG

class pipdig_p3_snapchat_snapcode extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = array(
			'title' => '',
			'snapcode' => '',
			'snapchat_account' => '',
		);

		$widget_ops = array(
			'classname' => 'pipdig_p3_snapchat_snapcode',
			'description' => __('Display your Snapchat Snapcode.', 'p3'),
		);

		$control_ops = array(
			'id_base' => 'pipdig_p3_snapchat_snapcode',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct('pipdig_p3_snapchat_snapcode', 'pipdig - Snapchat Snapcode', $widget_ops, $control_ops);

	}

	// The widget content.
	function widget($args, $instance) {

		//* Merge with defaults
		$instance = wp_parse_args((array) $instance, $this->defaults);

		echo $args['before_widget'];
		
			$greyscale = '';
			if (!empty($instance['greyscale'])) {
				$greyscale = '-webkit-filter: grayscale(100%); filter: grayscale(100%);';
			}

			if (! empty($instance['title']))
				echo $args['before_title'] . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $args['after_title'];
			
			$link_open = $link_close = '';
			if (!empty($instance['snapchat_account'])) {
				$link_open = '<a href="'.esc_url('https://www.snapchat.com/add/'.trim($instance['snapchat_account'])).'" target="_blank" rel="nofollow noopener">';
				$link_close = '</a>';
			}

			if (!empty($instance['snapcode'])) {
				$image_link = str_replace('http:', '', $instance['snapcode']);
				
				$lazy_class = '';
				if (is_pipdig_lazy()) {
					$lazy_class = 'class="pipdig_lazy"';
					$image_src = 'data-src="'.esc_url($image_link).'"';
				} else {
					$image_src = 'src="'.esc_url($image_link).'"';
				}
				
				echo $link_open.'<img '.$image_src.' alt="Snapchat" '.$lazy_class.' data-pin-nopin="true" style="min-width: 1.2in; max-width: 1.6in; height: auto;'.$greyscale.'" />'.$link_close;
				if (!empty($instance['snapchat_account'])) {
					echo '<p>'.sprintf( __('Follow <strong>%s</strong> on Snapchat!', 'p3'), strip_tags($instance['snapchat_account']) ).'</p>';
				}
			}
			
		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update($new_instance, $old_instance) {

		$new_instance['title'] = strip_tags($new_instance['title']);
		$new_instance['snapcode'] = strip_tags($new_instance['snapcode']);
		$new_instance['snapchat_account'] = strip_tags($new_instance['snapchat_account']);
		$new_instance['greyscale'] = strip_tags($new_instance['greyscale']);

		return $new_instance;

	}

	// The settings update form.
	function form($instance) {

		// Merge with defaults
		$instance = wp_parse_args((array) $instance, $this->defaults);
		
		$greyscale = '';
		if (!empty($instance['greyscale'])) {
			$greyscale = '-webkit-filter: grayscale(100%); filter: grayscale(100%);';
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset($instance['title'])) echo esc_attr($instance['title']); ?>" class="widefat" />
		</p>
		
		<p>1. Download your Snapcode PNG image from <a href="https://accounts.snapchat.com/accounts/snapcodes?type=png" target="_blank">this link</a></p>
		<p>2. Upload your Snapcode PNG image using the button below.</p>

		<p>
			<div class="pipdig-media-container">
				<div class="pipdig-media-inner">
					<?php $img_style = ($instance[ 'snapcode' ] != '') ? '' : 'display:none;'; ?>
					<img id="<?php echo $this->get_field_id('snapcode'); ?>-preview" src="<?php echo esc_attr($instance['snapcode']); ?>" style="margin:5px 0;padding:0;max-width:180px;height:auto;<?php echo $img_style.$greyscale; ?>" />
					<?php $no_img_style = ($instance[ 'snapcode' ] != '') ? 'style="display:none;"' : ''; ?>
				</div>
			
				<input type="text" id="<?php echo $this->get_field_id('snapcode'); ?>" name="<?php echo $this->get_field_name('snapcode'); ?>" value="<?php echo esc_attr($instance['snapcode']); ?>" class="pipdig-media-url" style="display: none" />

				<input type="button" value="<?php echo esc_attr(__('Remove', 'p3')); ?>" class="button pipdig-media-remove" id="<?php echo $this->get_field_id('snapcode'); ?>-remove" style="<?php echo $img_style; ?>" />

				<input type="button" value="<?php echo esc_attr(__('Select Image', 'p3')); ?>" class="button pipdig-media-upload" id="<?php echo $this->get_field_id('snapcode'); ?>-button" />
				<br class="clear">
			</div>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('snapchat_account'); ?>"><?php _e('Snapchat Account Name:', 'p3'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('snapchat_account'); ?>" name="<?php echo $this->get_field_name('snapchat_account'); ?>" value="<?php if (isset($instance['snapchat_account'])) echo esc_attr($instance['snapchat_account']); ?>" class="widefat" placeholder="<?php _e("For example:", 'p3'); ?> mileyxxcyrus" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('greyscale'); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id('greyscale'); ?>" name="<?php echo $this->get_field_name('greyscale'); ?>" <?php if (isset($instance['greyscale'])) { checked( (bool) $instance['greyscale'], true ); } ?> /><?php _e('Display in grayscale', 'p3'); ?></label>
			<br />
		</p>

		<?php

	}

}
