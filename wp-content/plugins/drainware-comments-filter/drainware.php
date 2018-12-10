<?php
/* @package drainware
 */
/*
Plugin Name: drainware comments filter
Plugin URI: http://drainware.com/
Description: Drainware Comments Filter will keep your blog free of undesirable comments (bad words, pornography, violence, intolerant words) using our Content Filter Engine (CFE) and everything absolutely FREE. 
Version: 1.3
Author: drainware 
Author URI: http://www.drainware.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('drainware_VERSION', '1');
define('drainware_PLUGIN_URL', plugin_dir_url( __FILE__ ));



//Hooking a little

add_action('wp_footer','display_drainware_footer',20);

function display_drainware_footer() {
        global $post;
	global $drainware_api_host, $drainware_api_port, $drainware_last_comment;
   $querystring = '';
   if ( is_home() ) {
    $query_string="&v=1&host=" . urlencode(stripslashes(site_url())) . "&";
   } else {
    $query_string="&v=1&host=" . urlencode(stripslashes(get_permalink($post->ID))) . "&";
   }
   $response = drainware_http_post($query_string, $drainware_api_host, '/index.py/link', $drainware_api_port);
   echo $response[1];
   return true;
}

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

if ( isset($wp_db_version) && $wp_db_version <= 9872 )
	include_once dirname( __FILE__ ) . '/legacy.php';


if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';

function drainware_init() {
	global $drainware_api_key, $drainware_api_host, $drainware_api_port;

	/*if ( $drainware_api_key )
		$drainware_api_host = $drainware_api_key . '.api.drainware.com';
	else
		$drainware_api_host = get_option('drainware_api_key') . '.api.drainware.com';*/
	$drainware_api_host = "http://api.drainware.com";

	$drainware_api_port = 80;
}
add_action('init', 'drainware_init');

function drainware_get_key() {
	global $drainware_api_key;
	if ( !empty($drainware_api_key) )
		return $drainware_api_key;
	return get_option('drainware_api_key');
}

function drainware_verify_key( $key, $ip = null ) {
	global $drainware_api_host, $drainware_api_port, $drainware_api_key;
	$blog = urlencode( get_option('home') );
	if ( $drainware_api_key )
		$key = $drainware_api_key;
	$response = drainware_http_post("key=$key&blog=$blog", 'api.drainware.com', '/', $drainware_api_port, $ip);
	if ( !is_array($response) || !isset($response[1]) || $response[1] != 'valid' && $response[1] != 'invalid' )
		return 'failed';
	return $response[1];
}

// if we're in debug or test modes, use a reduced service level so as not to polute training or stats data
function drainware_test_mode() {
	if ( defined('drainware_TEST_MODE') && drainware_TEST_MODE )
		return true;
	return false;
}

// return a comma-separated list of role names for the given user
function drainware_get_user_roles($user_id ) {
	$roles = false;
	
	if ( !class_exists('WP_User') )
		return false;
	
	if ( $user_id > 0 ) {
		$comment_user = new WP_User($user_id);
		if ( isset($comment_user->roles) )
			$roles = join(',', $comment_user->roles);
	}

	if ( is_multisite() && is_super_admin( $user_id ) ) {
		if ( empty( $roles ) ) {
			$roles = 'super_admin';
		} else {
			$comment_user->roles[] = 'super_admin';
			$roles = join( ',', $comment_user->roles );
		}
	}

	return $roles;
}

// Returns array with headers in $response[0] and body in $response[1]
function drainware_http_post($request, $host, $path, $port = 80, $ip=null) {
	global $wp_version;

	$drainware_ua = "WordPress/{$wp_version} | ";
	$drainware_ua .= 'drainware/' . constant( 'drainware_VERSION' );

	$content_length = strlen( $request );

	$http_host = $host;
	// use a specific IP if provided
	// needed by drainware_check_server_connectivity()
	if ( $ip && long2ip( ip2long( $ip ) ) ) {
		$http_host = $ip;
	} else {
		$http_host = $host;
	}
	
	// use the WP HTTP class if it is available
	if ( function_exists( 'wp_remote_post' ) ) {
     $http_args = array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $request,
		'cookies' => array()
    	);

		/*$http_args = array(
			'body'			=> $request,
			'headers'		=> array(
				'Content-Type'	=> 'application/x-www-form-urlencoded; ' .
									'charset=' . get_option( 'blog_charset' ),
				'Host'			=> $host,
				'User-Agent'	=> $drainware_ua
			),
			'httpversion'	=> '1.0',
			'timeout'		=> 15
		);*/
		$drainware_url = "{$http_host}{$path}";
		$response = wp_remote_post( $drainware_url, $http_args );


		if ( is_wp_error( $response ) )
			return '';

		return array( $response['headers'], $response['body'] );
	} else {
		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= 'Content-Type: application/x-www-form-urlencoded; charset=' . get_option('blog_charset') . "\r\n";
		$http_request .= "Content-Length: {$content_length}\r\n";
		$http_request .= "User-Agent: {$drainware_ua}\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		
		$response = '';
		if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
			fwrite( $fs, $http_request );

			while ( !feof( $fs ) )
				$response .= fgets( $fs, 1160 ); // One TCP-IP packet
			fclose( $fs );
			$response = explode( "\r\n\r\n", $response, 2 );
		}
		return $response;
	}
}

// filter handler used to return a spam result to pre_comment_approved
function drainware_result_spam( $approved ) {
	// bump the counter here instead of when the filter is added to reduce the possibility of overcounting
	if ( $incr = apply_filters('drainware_spam_count_incr', 1) )
		update_option( 'drainware_spam_count', get_option('drainware_spam_count') + $incr );
	// this is a one-shot deal
	remove_filter( 'pre_comment_approved', 'drainware_result_spam' );
	return 'spam';
}

function drainware_result_hold( $approved ) {
	// once only
	remove_filter( 'pre_comment_approved', 'drainware_result_hold' );
	return '0';
}

// how many approved comments does this author have?
function drainware_get_user_comments_approved( $user_id, $comment_author_email, $comment_author, $comment_author_url ) {
	global $wpdb;
	
	if ( !empty($user_id) )
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE user_id = %d AND comment_approved = 1", $user_id ) );
		
	if ( !empty($comment_author_email) )
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_author_email = %s AND comment_author = %s AND comment_author_url = %s AND comment_approved = 1", $comment_author_email, $comment_author, $comment_author_url ) );
		
	return 0;
}

function drainware_microtime() {
	$mtime = explode( ' ', microtime() );
	return $mtime[1] + $mtime[0];
}

// log an event for a given comment, storing it in comment_meta
function drainware_update_comment_history( $comment_id, $message, $event=null ) {
	global $current_user;

	// failsafe for old WP versions
	if ( !function_exists('add_comment_meta') )
		return false;
	
	$user = '';
	if ( is_object($current_user) && isset($current_user->user_login) )
		$user = $current_user->user_login;

	$event = array(
		'time' => drainware_microtime(),
		'message' => $message,
		'event' => $event,
		'user' => $user,
	);

	// $unique = false so as to allow multiple values per comment
	$r = add_comment_meta( $comment_id, 'drainware_history', $event, false );
}

// get the full comment history for a given comment, as an array in reverse chronological order
function drainware_get_comment_history( $comment_id ) {
	
	// failsafe for old WP versions
	if ( !function_exists('add_comment_meta') )
		return false;

	$history = get_comment_meta( $comment_id, 'drainware_history', false );
	usort( $history, 'drainware_cmp_time' );
	return $history;
}

function drainware_cmp_time( $a, $b ) {
	return $a['time'] > $b['time'] ? -1 : 1;
}

// this fires on wp_insert_comment.  we can't update comment_meta when drainware_auto_check_comment() runs
// because we don't know the comment ID at that point.
function drainware_auto_check_update_meta( $id, $comment ) {
	global $drainware_last_comment;

	// failsafe for old WP versions
	if ( !function_exists('add_comment_meta') )
		return false;

	// wp_insert_comment() might be called in other contexts, so make sure this is the same comment
	// as was checked by drainware_auto_check_comment
	if ( is_object($comment) && !empty($drainware_last_comment) && is_array($drainware_last_comment) ) {
		if ( intval($drainware_last_comment['comment_post_ID']) == intval($comment->comment_post_ID)
			&& $drainware_last_comment['comment_author'] == $comment->comment_author
			&& $drainware_last_comment['comment_author_email'] == $comment->comment_author_email ) {
				// normal result: true or false
				if ( $drainware_last_comment['drainware_result'] == 'true' ) {
					update_comment_meta( $comment->comment_ID, 'drainware_result', 'true' );
					drainware_update_comment_history( $comment->comment_ID, __('drainware caught this comment as spam'), 'check-spam' );
					if ( $comment->comment_approved != 'spam' )
						drainware_update_comment_history( $comment->comment_ID, sprintf( __('Comment status was changed to %s'), $comment->comment_approved), 'status-changed'.$comment->comment_approved );
				} elseif ( $drainware_last_comment['drainware_result'] == 'false' ) {
					update_comment_meta( $comment->comment_ID, 'drainware_result', 'false' );
					drainware_update_comment_history( $comment->comment_ID, __('drainware cleared this comment'), 'check-ham' );
					if ( $comment->comment_approved == 'spam' ) {
						if ( wp_blacklist_check($comment->comment_author, $comment->comment_author_email, $comment->comment_author_url, $comment->comment_content, $comment->comment_author_IP, $comment->comment_agent) )
							drainware_update_comment_history( $comment->comment_ID, __('Comment was caught by wp_blacklist_check'), 'wp-blacklisted' );
						else
							drainware_update_comment_history( $comment->comment_ID, sprintf( __('Comment status was changed to %s'), $comment->comment_approved), 'status-changed-'.$comment->comment_approved );
					}
				// abnormal result: error
				} else {
					update_comment_meta( $comment->comment_ID, 'drainware_error', time() );
					drainware_update_comment_history( $comment->comment_ID, sprintf( __('drainware was unable to check this comment (response: %s), will automatically retry again later.'), $drainware_last_comment['drainware_result']), 'check-error' );
				}
				
				// record the complete original data as submitted for checking
				if ( isset($drainware_last_comment['comment_as_submitted']) )
					update_comment_meta( $comment->comment_ID, 'drainware_as_submitted', $drainware_last_comment['comment_as_submitted'] );
		}
	}
}

add_action( 'wp_insert_comment', 'drainware_auto_check_update_meta', 10, 2 );


function drainware_auto_check_comment( $commentdata ) {
	global $drainware_api_host, $drainware_api_port, $drainware_last_comment;

	$comment = $commentdata;
	$comment['user_ip']    = $_SERVER['REMOTE_ADDR'];
	$comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$comment['referrer']   = $_SERVER['HTTP_REFERER'];
	$comment['blog']       = get_option('home');
	$comment['blog_lang']  = get_locale();
	$comment['blog_charset'] = get_option('blog_charset');
	$comment['permalink']  = get_permalink($comment['comment_post_ID']);
	
	$comment['user_role'] = drainware_get_user_roles($comment['user_ID']);

	$drainware_nonce_option = apply_filters( 'drainware_comment_nonce', get_option( 'drainware_comment_nonce' ) );
	$comment['drainware_comment_nonce'] = 'inactive';
	if ( $drainware_nonce_option == 'true' || $drainware_nonce_option == '' ) {
		$comment['drainware_comment_nonce'] = 'failed';
		if ( isset( $_POST['drainware_comment_nonce'] ) && wp_verify_nonce( $_POST['drainware_comment_nonce'], 'drainware_comment_nonce_' . $comment['comment_post_ID'] ) )
			$comment['drainware_comment_nonce'] = 'passed';

		// comment reply in wp-admin
		if ( isset( $_POST['_ajax_nonce-replyto-comment'] ) && check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment' ) )
			$comment['drainware_comment_nonce'] = 'passed';

	}

	if ( drainware_test_mode() )
		$comment['is_test'] = 'true';
		
	foreach ($_POST as $key => $value ) {
		if ( is_string($value) )
			$comment["POST_{$key}"] = $value;
	}

	$ignore = array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' );

	foreach ( $_SERVER as $key => $value ) {
		if ( !in_array( $key, $ignore ) && is_string($value) )
			$comment["$key"] = $value;
		else
			$comment["$key"] = '';
	}

	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
    

	$commentdata['comment_as_submitted'] = $comment;

	$response = drainware_http_post($query_string, $drainware_api_host, '/index.py/check', $drainware_api_port);
	$commentdata['drainware_result'] = $response[1];
	if ( 'true' == $response[1] ) {
		// drainware_spam_count will be incremented later by drainware_result_spam()
		add_filter('pre_comment_approved', 'drainware_result_spam');

		do_action( 'drainware_spam_caught' );

		$post = get_post( $comment['comment_post_ID'] );
		$last_updated = strtotime( $post->post_modified_gmt );
		$diff = time() - $last_updated;
		$diff = $diff / 86400;
		
		if ( $post->post_type == 'post' && $diff > 30 && get_option( 'drainware_discard_month' ) == 'true' && empty($comment['user_ID']) ) {
			// drainware_result_spam() won't be called so bump the counter here
			if ( $incr = apply_filters('drainware_spam_count_incr', 1) )
				update_option( 'drainware_spam_count', get_option('drainware_spam_count') + $incr );
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			wp_die( __('Sorry, this comment contains undesirable words. Try again. <br>More information in <a href="http://www.drainware.com">Drainware Content Filtering Solutions</a>') );
		}
		wp_die( __('Sorry, this comment contains undesirable words. Try again. <br>More information in <a href="http://www.drainware.com">Drainware Content Filtering Solutions</a>') );

	}
	
	// if the response is neither true nor false, hold the comment for moderation and schedule a recheck
	if ( 'true' != $response[1] && 'false' != $response[1] ) {
		add_filter('pre_comment_approved', 'drainware_result_hold');
		wp_schedule_single_event( time() + 1200, 'drainware_schedule_cron_recheck' );
	}
	
	if ( function_exists('wp_next_scheduled') && function_exists('wp_schedule_event') ) {
		// WP 2.1+: delete old comments daily
		if ( !wp_next_scheduled('drainware_scheduled_delete') )
			wp_schedule_event(time(), 'daily', 'drainware_scheduled_delete');
	} elseif ( (mt_rand(1, 10) == 3) ) {
		// WP 2.0: run this one time in ten
		drainware_delete_old();
	}
	$drainware_last_comment = $commentdata;
	return $commentdata;
}

add_action('preprocess_comment', 'drainware_auto_check_comment', 1);

function drainware_delete_old() {
	global $wpdb;
	$now_gmt = current_time('mysql', 1);
	$comment_ids = $wpdb->get_col("SELECT comment_id FROM $wpdb->comments WHERE DATE_SUB('$now_gmt', INTERVAL 15 DAY) > comment_date_gmt AND comment_approved = 'spam'");
	if ( empty( $comment_ids ) )
		return;
		
	$comma_comment_ids = implode( ', ', array_map('intval', $comment_ids) );

	do_action( 'delete_comment', $comment_ids );
	$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_id IN ( $comma_comment_ids )");
	$wpdb->query("DELETE FROM $wpdb->commentmeta WHERE comment_id IN ( $comma_comment_ids )");
	clean_comment_cache( $comment_ids );
	$n = mt_rand(1, 5000);
	if ( apply_filters('drainware_optimize_table', ($n == 11)) ) // lucky number
		$wpdb->query("OPTIMIZE TABLE $wpdb->comments");

}

add_action('drainware_scheduled_delete', 'drainware_delete_old');

function drainware_check_db_comment( $id, $recheck_reason = 'recheck_queue' ) {
    global $wpdb, $drainware_api_host, $drainware_api_port;

    $id = (int) $id;
    $c = $wpdb->get_row( "SELECT * FROM $wpdb->comments WHERE comment_ID = '$id'", ARRAY_A );
    if ( !$c )
        return;

    $c['user_ip']    = $c['comment_author_IP'];
    $c['user_agent'] = $c['comment_agent'];
    $c['referrer']   = '';
    $c['blog']       = get_option('home');
    $c['blog_lang']  = get_locale();
    $c['blog_charset'] = get_option('blog_charset');
    $c['permalink']  = get_permalink($c['comment_post_ID']);
    $id = $c['comment_ID'];
	if ( drainware_test_mode() )
		$c['is_test'] = 'true';
	$c['recheck_reason'] = $recheck_reason;

    $query_string = '';
    foreach ( $c as $key => $data )
    $query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

    $response = drainware_http_post($query_string, $drainware_api_host, '/index.py/check', $drainware_api_port);
    return $response[1];
}

function drainware_cron_recheck() {
	global $wpdb;

	delete_option('drainware_available_servers');

	$comment_errors = $wpdb->get_col( "
		SELECT comment_id
		FROM {$wpdb->prefix}commentmeta
		WHERE meta_key = 'drainware_error'
		LIMIT 100
	" );
	
	foreach ( (array) $comment_errors as $comment_id ) {
		// if the comment no longer exists, remove the meta entry from the queue to avoid getting stuck
		if ( !get_comment( $comment_id ) ) {
			delete_comment_meta( $comment_id, 'drainware_error' );
			continue;
		}
		
		add_comment_meta( $comment_id, 'drainware_rechecking', true );
		$status = drainware_check_db_comment( $comment_id, 'retry' );

		$msg = '';
		if ( $status == 'true' ) {
			$msg = __( 'drainware caught this comment as spam during an automatic retry.' );
		} elseif ( $status == 'false' ) {
			$msg = __( 'drainware cleared this comment during an automatic retry.' );
		}
		
		// If we got back a legit response then update the comment history
		// other wise just bail now and try again later.  No point in
		// re-trying all the comments once we hit one failure.
		if ( !empty( $msg ) ) {
			delete_comment_meta( $comment_id, 'drainware_error' );
			drainware_update_comment_history( $comment_id, $msg, 'cron-retry' );
			update_comment_meta( $comment_id, 'drainware_result', $status );
			// make sure the comment status is still pending.  if it isn't, that means the user has already moved it elsewhere.
			$comment = get_comment( $comment_id );
			if ( $comment && 'unapproved' == wp_get_comment_status( $comment_id ) ) {
				if ( $status == 'true' ) {
					wp_spam_comment( $comment_id );
				} elseif ( $status == 'false' ) {
					// comment is good, but it's still in the pending queue.  depending on the moderation settings
					// we may need to change it to approved.
					if ( check_comment($comment->comment_author, $comment->comment_author_email, $comment->comment_author_url, $comment->comment_content, $comment->comment_author_IP, $comment->comment_agent, $comment->comment_type) )
						wp_set_comment_status( $comment_id, 1 );
				}
			}
		} else {
			delete_comment_meta( $comment_id, 'drainware_rechecking' );
			wp_schedule_single_event( time() + 1200, 'drainware_schedule_cron_recheck' );
			return;
		}
	}
	
	$remaining = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE meta_key = 'drainware_error'" ) );
	if ( $remaining && !wp_next_scheduled('drainware_schedule_cron_recheck') ) {
		wp_schedule_single_event( time() + 1200, 'drainware_schedule_cron_recheck' );
	}
}
add_action( 'drainware_schedule_cron_recheck', 'drainware_cron_recheck' );

function drainware_add_comment_nonce( $post_id ) {
	echo '<p style="display: none;">';
	wp_nonce_field( 'drainware_comment_nonce_' . $post_id, 'drainware_comment_nonce', FALSE );
	echo '</p>';
}

$drainware_comment_nonce_option = apply_filters( 'drainware_comment_nonce', get_option( 'drainware_comment_nonce' ) );

if ( $drainware_comment_nonce_option == 'true' || $drainware_comment_nonce_option == '' )
	add_action( 'comment_form', 'drainware_add_comment_nonce' );

if ( '3.0.5' == $wp_version ) { 
	remove_filter( 'comment_text', 'wp_kses_data' ); 
	if ( is_admin() ) 
		add_filter( 'comment_text', 'wp_kses_post' ); 
}
