<?php
/*
Plugin Name: WordPress API
Plugin URI: https://www.nonlefthanded.com
Description: Allow all parts of a WP site to be available to the API
Version: 0.1
Author: CJ Stritzel
Author URI: https://www.nonlefthanded.com
*/


$api_base_url = 'wp/v2';

class API_Routes_Controller extends WP_REST_Controller {

  /*
   *
   * Meta Information
   *
   */

  public function register_meta() {
    global $api_base_url;
    $path = '/meta(?:/(?P<post_id>[0-9]+))?';

    register_rest_route( $api_base_url , $path, array(
        'methods'             => 'GET',
        'callback'            => array($this, 'get_meta')
      )
    );     
  }

  public function get_meta($request){
    $_ = (object)array();
    $post_id = (isset($request['post_id'])) ? $request['post_id'] : false ;

    if (!$post_id):
      $_->code = "rest_no_route";
      $_->message = "No route was found matching the URL and request method (missing <post_id>)";
      $_->data = (object)array('status' => 404);
      return new WP_REST_Response($_, 404); 
    endif;

    $_->message = sprintf( 'Meta info for post_id:<%s>.', $post_id ) ;
    $_->meta    = get_post_meta($post_id);
    foreach ( $_->meta as $k => $v ):
      if (strpos( $k, '_') === 0):
        unset($_->meta[$k]);
      endif;
    endforeach;
  
    return new WP_REST_Response($_, 200); 
  }

  /*
   *
   * Sidebars
   *
   */

  public function register_sidebars() {
    global $api_base_url;
    $path = '/sidebars(?:/(?P<sidebar_id>[a-zA-Z0-9-]+))?';

    register_rest_route( $api_base_url , $path, array(
        'methods'             => 'GET',
        'callback'            => array($this, 'get_sidebars')
      )
    );     
  }

  public function get_sidebars($request) {
    $sidebar_id = (isset($request['sidebar_id'])) ? $request['sidebar_id'] : false ;
    $sidebars   = get_option('sidebars_widgets');
    $_          = (object)array();

    switch ($sidebar_id):
    case false:
        // Whoh, no sidebar_id, so show an index
        $_->message = "Index of sidebars.";
        global $wp_registered_sidebars;
        $_->data = (object)array('status'=>200);
        
        $_->wp_registered_sidebars = $wp_registered_sidebars;
        foreach ($_->wp_registered_sidebars as $k => $v):
          $_->wp_registered_sidebars[$k]['contents'] = $sidebars[$k];
        endforeach;
        break;

    default:
        // We got a sidebar_id
        $_->data = (object)array('status'=>200);
        $_->message = sprintf("Contents (html) of sidebar '%s.'", $sidebar_id);
        ob_start();
          dynamic_sidebar($sidebar_id);
          $_->sidebar_html = ob_get_contents();
        ob_end_clean();
    endswitch;
    // return rest_ensure_response( 'Hello World! This is my first REST API' );
    return new WP_REST_Response($_, 200);
  }

  /*
   *
   * Widgets (just redirect to sidebars)
   *
   */

  public function register_widgets() {
    global $api_base_url;
    $path = '/widgets(?:/(?P<widget_id>[a-zA-Z0-9-]+))?';

    register_rest_route( $api_base_url , $path, array(
        'methods'             => 'GET',
        'callback'            => array($this, 'get_widgets')
      )
    );
  }

  public function get_widgets($request){
    global $api_base_url;
    $sidebar_id = (isset($request['widget_id'])) ? $request['widget_id'] : '' ;
    $new_url = '/wp-json/' . $api_base_url . '/sidebars/' . $sidebar_id;
    $_ = (object)array(
      'message' => "Use namespace '/sidebar' to access this data",
      'sidebar_url' => $new_url
    );
    return new WP_REST_Response($_, 200);
    // wp_redirect( $new_url );
    // exit;
  }

}

add_action('rest_api_init', function() {           
    $api_controller = new API_Routes_Controller();
    $api_controller->register_sidebars();
    $api_controller->register_widgets();
    $api_controller->register_meta();
  } 
);



