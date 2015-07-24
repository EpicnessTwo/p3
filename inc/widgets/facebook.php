<?php 

class pipdig_widget_facebook extends WP_Widget {
 
  public function __construct() {
      $widget_ops = array('classname' => 'pipdig_widget_facebook', 'description' => __('Displays a Facebook Like Box.', 'pipdig-textdomain') );
      $this->WP_Widget('pipdig_widget_facebook', 'pipdig - ' . __('Facebook Widget', 'pipdig-textdomain'), $widget_ops);
  }
  
  function widget($args, $instance) {
    // PART 1: Extracting the arguments + getting the values
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
    if (isset($instance['facebook_url'])) { 
		$facebook_url =	$instance['facebook_url'];
	}
	if (isset($instance['hide_cover'])) { 
		$hide_cover = $instance['hide_cover'];
	} else {
		$hide_cover = 'false';
	}
	if (isset($instance['show_faces'])) { 
		$show_faces = $instance['show_faces'];
	} else {
		$show_faces = 'false';
	}
	if (isset($instance['show_posts'])) { 
		$show_posts = $instance['show_posts'];
		$height = '450px';
	} else {
		$show_posts = 'false';
		$height = '320px';
	}

    // Before widget code, if any
    echo (isset($before_widget)?$before_widget:'');
   
    // PART 2: The title and the text output
    if (!empty($title)) {
		echo $before_title . $title . $after_title;
	}

    if (!empty($facebook_url)) {
		echo '<script>!function(e,n,t){var o,c=e.getElementsByTagName(n)[0];e.getElementById(t)||(o=e.createElement(n),o.id=t,o.src="//connect.facebook.net/en/sdk.js#xfbml=1&version=v2.3",c.parentNode.insertBefore(o,c))}(document,"script","facebook-jssdk");</script><div class="fb-page" data-href="' . $facebook_url . '" data-width="500" data-height="' . $height . '" data-hide-cover="' . $hide_cover . '" data-show-facepile="' . $show_faces . '" data-show-posts="' . $show_posts . '"></div>';
	} else {
		_e('Setup not complete. Please add your Facebook Page URL to the Facebook Widget in the dashboard.', 'pipdig-textdomain');
	}
    // After widget code, if any  
    echo (isset($after_widget)?$after_widget:'');
  }
 
  public function form( $instance ) {
   
    // PART 1: Extract the data from the instance variable
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    if (isset($instance['facebook_url'])) { 
		$facebook_url =	$instance['facebook_url'];
	}
	if (isset($instance['hide_cover'])) { 
		$hide_cover = $instance['hide_cover'];
	}
	if (isset($instance['show_faces'])) { 
		$show_faces = $instance['show_faces'];
	}
	if (isset($instance['show_posts'])) { 
		$show_posts = $instance['show_posts'];
	}
	 
   
    // PART 2-3: Display the fields
    ?>
	 
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'pipdig-textdomain'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" type="text" 
		value="<?php echo esc_attr($title); ?>" />
	</p>

	<p><?php _e('Add your Facebook page URL to the box below. For example, https://facebook.com/pipdig', 'pipdig-textdomain'); ?></p>

	<p>
		<label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook Page URL:', 'pipdig-textdomain'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('facebook_url'); ?>" 
		name="<?php echo $this->get_field_name('facebook_url'); ?>" type="text" 
		value="<?php if (isset($instance['facebook_url'])) { echo esc_attr($facebook_url); } ?>" placeholder="https://facebook.com/pipdig" />
	</p>
	
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_cover' ); ?>" name="<?php echo $this->get_field_name( 'hide_cover' ); ?>" <?php checked(isset($instance['hide_cover'])) ?> />
		<label for="<?php echo $this->get_field_id('hide_cover'); ?>"><?php _e('Hide Cover Image', 'pipdig-textdomain'); ?></label>
	</p>
	
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_faces' ); ?>" name="<?php echo $this->get_field_name( 'show_faces' ); ?>" <?php checked(isset($instance['show_faces'])) ?> />
		<label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Show Faces', 'pipdig-textdomain'); ?></label>
	</p>
	
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>" <?php checked(isset($instance['show_posts'])) ?> />
		<label for="<?php echo $this->get_field_id('show_posts'); ?>"><?php _e('Show Posts', 'pipdig-textdomain'); ?></label>
	</p>

     <?php
   
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
    $instance['facebook_url'] = strip_tags( $new_instance['facebook_url'] );
	$instance['hide_cover'] = $new_instance['hide_cover'];
	$instance['show_posts'] = $new_instance['show_posts'];
	$instance['show_faces'] = $new_instance['show_faces'];

    return $instance;
  }
  
}
//add_action( 'widgets_init', create_function('', 'return register_widget("pipdig_widget_facebook");') );