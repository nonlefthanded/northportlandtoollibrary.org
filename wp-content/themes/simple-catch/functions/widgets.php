<?php
/**
 * Register Sidebar and widgets.
 *
 * @since Simple Catch 1.0
 */
function simplecatch_widgets_init() {

	//Register Widgets
	register_widget( 'simplecatch_tagcloud_widget' );
	register_widget( 'simplecatch_ads_widget' );
	register_widget( 'simplecatch_social_widget' );

	/* Register Sidebar */
	//Main Sidebar
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'simple-catch' ),
		'id'            => 'sidebar',
		'description'   => __( 'This is main sideabar', 'simple-catch' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );
}
add_action( 'widgets_init', 'simplecatch_widgets_init' );


/**
 * Simple Catch Custom Tag Cloud Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class simplecatch_tagcloud_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'simplecatch_tag_widget', // Base ID
			__( 'CT: Custom Tag Cloud', 'simple-catch' ), // Name
			array( 'description' => __( 'Displays Custom Tag Cloud', 'simple-catch' ) ) ,
			array('width' => 400, 'height' => 500)
			// Args
		);
	}

	/** Displays the Widget in the front-end.
	 *
	 * $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		$title = !empty( $instance['title'] ) ? $instance[ 'title' ] : '';


		echo $before_widget;

		if('' != $title )
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		if ( function_exists( 'simplecatch_custom_tag_cloud' ) ):
			simplecatch_custom_tag_cloud();
		endif;

		echo $after_widget;
	}

	/**
	 * update the particular instant
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * $new_instance New settings for this instance as input by the user via form()
	 * $old_instance Old settings for this instance
	 * Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Creates the form for the widget in the back-end which includes the Title
	 * $instance Current settings
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );

		$title = esc_attr($instance['title']);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','simple-catch'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<?php
	}

}// end simplecatch_tagcloud_widget class


/**
 * Simple Catch Custom Adspace Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class simplecatch_ads_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'widget_simplecatch_adspace_widget', // Base ID
			__( 'CT: Advertisement', 'simple-catch' ), // Name
			array( 'description' => __( 'Use this widget to add any type of Advertisement as a widget', 'simple-catch' ) )
			// Args
		);
	}

	/**
	 * Displays the Widget in the front-end.
	 *
	 * $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		if ( isset( $instance['enable_hide_404'] ) && $instance['enable_hide_404'] && is_404() ) {
			//Bail Early if the page is 404 error page and the widget is set to be hidden in that page
			return;
		}

		$title = !empty( $instance['title'] ) ? $instance[ 'title' ] : '';
		$adcode = !empty( $instance['adcode'] ) ? $instance[ 'adcode' ] : '';
		$image = !empty( $instance['image'] ) ? $instance[ 'image' ] : '';
		$href = !empty( $instance['href'] ) ? $instance[ 'href' ] : '';
		$target = !empty( $instance[ 'target' ] ) ? 'true' : 'false';
		$alt = !empty( $instance['alt'] ) ? $instance[ 'alt' ] : '';

		$enable_hide_404 = !empty( $instance[ 'enable_hide_404' ] ) ? 'true' : 'false';

		if ( $target == "true" ) :
			$base = '_blank';
		else:
			$base = '_self';
		endif;

		echo $before_widget;

		if ( '' != $title  ) {
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		}

		if ( '' != $adcode  ) {
			echo $adcode;
		}
		elseif ( '' != $image  ) {
        	echo '<a href="'. esc_url( $href ).'" target="'.esc_attr( $base ).'"><img src="'. esc_url( $image ).'" alt="'.esc_attr( $alt ).'" /></a>';
		}
		else {
			_e( 'Add Advertisement Code or Image URL', 'simple-catch' );
		}

		echo $after_widget;

	}

	/**
	 * update the particular instant
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * $new_instance New settings for this instance as input by the user via form()
	 * $old_instance Old settings for this instance
	 * Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['adcode'] = wp_kses_stripslashes($new_instance['adcode']);
		$instance['image'] = esc_url_raw($new_instance['image']);
		$instance['href'] = esc_url_raw($new_instance['href']);
		$instance[ 'target' ] = isset( $new_instance[ 'target' ] ) ? 1 : 0;
		$instance['alt'] = sanitize_text_field($new_instance['alt']);

		$instance[ 'enable_hide_404' ] = isset( $new_instance[ 'enable_hide_404' ] ) ? 1 : 0;

		return $instance;
	}

	/**
	 * Creates the form for the widget in the back-end which includes the Title , adcode, image, alt
	 * $instance Current settings
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'adcode' => '', 'image' => '', 'href' => '', 'target' => '0', 'alt' => '' ) );
		$title = esc_attr($instance['title']);
		$adcode = esc_textarea( $instance[ 'adcode' ] );
		$image = esc_url( $instance[ 'image' ] );
		$href = esc_url( $instance[ 'href' ] );
		$target = $instance['target'] ? 'checked="checked"' : '';
		$alt = esc_attr( $instance[ 'alt' ] );
		if ( isset( $instance['enable_hide_404'] ) ) {
			if ( $instance['enable_hide_404'] ) {
				$enable_hide_404 = 'checked="checked"';
			}
			else {
				$enable_hide_404 = '';
			}
		}
		else {
			$enable_hide_404 = 'checked="checked"';
		}
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','simple-catch'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <?php if ( current_user_can( 'unfiltered_html' ) ) : // Only show it to users who can edit it ?>
            <p>
                <label for="<?php echo $this->get_field_id('adcode'); ?>"><?php _e('Ad Code:','simple-catch'); ?></label>
                <textarea name="<?php echo $this->get_field_name('adcode'); ?>" class="widefat" id="<?php echo $this->get_field_id('adcode'); ?>"><?php echo $adcode; ?></textarea>
            </p>
            <p><strong>or</strong></p>
        <?php endif; ?>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Url:','simple-catch'); ?></label>
       	 	<input type="text" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image; ?>" class="widefat" id="<?php echo $this->get_field_id('image'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('href'); ?>"><?php _e('Link URL:','simple-catch'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('href'); ?>" value="<?php echo esc_url( $href ); ?>" class="widefat" id="<?php echo $this->get_field_id('href'); ?>" />
        </p>
        <p>
			<input class="checkbox" type="checkbox" <?php echo $target; ?> id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" />
            <label for="<?php echo $this->get_field_id('target'); ?>"><?php _e( 'Open Link in New Window', 'simple-catch' ); ?></label>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alt text:','simple-catch'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('alt'); ?>" value="<?php echo $alt; ?>" class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" />
        </p>
        <p>
			<input class="checkbox" type="checkbox" <?php echo $enable_hide_404; ?> id="<?php echo $this->get_field_id('enable_hide_404'); ?>" name="<?php echo $this->get_field_name('enable_hide_404'); ?>" /> <label for="<?php echo $this->get_field_id('enable_hide_404'); ?>"><?php _e( 'Check to Hide Ad on 404 page', 'simple-catch' ); ?></label>
		</p>
        <?php
	}
}


/**
 * Extends class wp_widget
 *
 * Creates a function simplecatch_social_widget
 * $widget_ops option array passed to wp_register_sidebar_widget().
 * $control_ops option array passed to wp_register_widget_control().
 * $name, Name for this widget which appear on widget bar.
 */
class simplecatch_social_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'simplecatch_social_widget', // Base ID
			__( 'CT: Social Icons', 'simple-catch' ), // Name
			array( 'description' => __( 'Displays Social Icons added from Customizer / Theme Options Panel', 'simple-catch' ) )
			// Args
		);
	}

	/**
	 * Displays the Widget in the front-end.
	 *
	 * $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		$title = !empty( $instance['title'] ) ? $instance[ 'title' ] : '';

		echo $before_widget;

		if ( '' != $title  ) {
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		}

		if ( function_exists( 'simplecatch_headersocialnetworks' ) ):
			simplecatch_headersocialnetworks();
		endif;

		echo $after_widget;
	}

	/**
	 * update the particular instant
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * $new_instance New settings for this instance as input by the user via form()
	 * $old_instance Old settings for this instance
	 * Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	/**
	 * Creates the form for the widget in the back-end which includes the Title , adcode, image, alt
	 * $instance Current settings
	 */
	function form($instance) {
		$instance = wp_parse_args( ( array ) $instance, array( 'title'=>'' ) );
		$title = esc_attr( $instance[ 'title' ] );

		/**
		 * Constructs title attributes  for use in form() field
		 * @return string Name attribute for $field_name
		 */
		echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . 'Title:' . '</label><input class="widefat" id="' .
		$this->get_field_id( 'title' ) . '" name="' .       $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" /> </p>';

	}
}// end simplecatch_social_widget class