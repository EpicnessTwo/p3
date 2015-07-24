<?php
class pipdig_widget_random_posts extends WP_Widget {
	
  public function __construct() {
      $widget_ops = array('classname' => 'pipdig_widget_random_posts', 'description' => __('Displays a selection of random posts.', 'pipdig-textdomain') );
      $this->WP_Widget('pipdig_widget_random_posts', 'pipdig - ' . __('Random Posts', 'pipdig-textdomain'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	if (isset($instance['title'])) { 
		$title = $instance['title'];
	}
	if (isset($instance['date_range_posts'])) { 
		$date_range_posts = $instance['date_range_posts'];
	}
	if (isset($instance['number_posts'])) { 
		$number_posts = $instance['number_posts'];
	} else {
		$number_posts = 3;
	}
	
?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'pipdig-textdomain'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" type="text" 
		value="<?php echo esc_attr($title); ?>" />
	</p>
    <p>
		<label for="<?php echo $this->get_field_id('date_range_posts'); ?>"><?php _e('Date range for posts:', 'pipdig-textdomain'); ?></label>
        <select id="<?php echo $this->get_field_id( 'date_range_posts' ); ?>" name="<?php echo $this->get_field_name( 'date_range_posts' ); ?>">
            <option <?php if (isset($instance['date_range_posts'])) { if ( '1 week ago' == $date_range_posts ) echo 'selected="selected"'; } ?> value="1 week ago"><?php _e('1 Week', 'pipdig-textdomain') ?></option>
			<option <?php if (isset($instance['date_range_posts'])) { if ( '1 month ago' == $date_range_posts ) echo 'selected="selected"'; } ?> value="1 month ago"><?php _e('1 Month', 'pipdig-textdomain') ?></option>
			<option <?php if (isset($instance['date_range_posts'])) { if ( '1 year ago' == $date_range_posts ) echo 'selected="selected"'; } ?> value="1 year ago"><?php _e('1 Year', 'pipdig-textdomain') ?></option>
			<option <?php if (isset($instance['date_range_posts'])) { if ( '' == $date_range_posts ) echo 'selected="selected"'; } ?> value=""><?php _e('All Time', 'pipdig-textdomain') ?></option>
        </select>
    </p>
	<p>
		<label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Number of posts to show:', 'pipdig-textdomain'); ?></label>
		<input type="number" min="1" max="6" id="<?php echo $this->get_field_id( 'number_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_posts' ); ?>" value="<?php if ($number_posts) { echo $number_posts; } else { echo '3'; } ?>" />
	</p>
	<p><em><?php _e('Posts will be refreshed every 30 minutes', 'pipdig-textdomain'); ?></em></p>
<?php
  }
 
  function update($new_instance, $old_instance) {
	delete_transient('pipdig_random_posts_widget'); // delete transient on widget save
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
	$instance['date_range_posts'] =  $new_instance['date_range_posts'];
	$instance['number_posts'] = absint( $new_instance['number_posts'] );
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
	if (isset($instance['title'])) { 
		$title = $instance['title'];
	}
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE


query_posts('');
?>
<ul id="pipdig-widget-popular-posts" class="nopin">

<?php
if ( false === ( $popular = get_transient( 'pipdig_random_posts_widget' ) ) ) { // check for transient value
	if (isset($instance['number_posts'])) { 
		$number_posts = $instance['number_posts'];
	} else {
		$number_posts = 3;
	}
	if (isset($instance['date_range_posts'])) {
		$date_range_posts = $instance['date_range_posts'];
	} else {
		$date_range_posts = '1 month ago';
	}
    $popular = new WP_Query( array(
        'post_type'             => array( 'post' ),
		'meta_key'              => '_thumbnail_id',
        'showposts'             => $number_posts,
        'ignore_sticky_posts'   => true,
        'orderby'               => 'comment_count',
        'order'                 => 'dsc',
        'date_query' => array(
            array(
                'after' => $date_range_posts,
            ),
        ),
    ) );
	set_transient( 'pipdig_random_posts_widget', $popular, 30 * MINUTE_IN_SECONDS ); // set transient value
} ?>
<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
<li>
<a href="<?php the_permalink() ?>">
<?php the_post_thumbnail( 'pipdig-widget-popular-posts' );?>
<h4><?php $title = get_the_title(); echo pipdig_truncate($title, 11); ?></h4>
</a>
</li>
<?php endwhile;?>
<?php
wp_reset_query();
?>
</ul>
 
<?php
  echo $after_widget;
  }
 
}
//add_action( 'widgets_init', create_function('', 'return register_widget("pipdig_widget_random_posts");') );