<?php
add_action( 'admin_menu', 'drainware_config_page' );
add_action( 'admin_menu', 'drainware_stats_page' );
drainware_admin_warnings();

function drainware_admin_init() {
    global $wp_version;
    
    // all admin functions are disabled in old versions
    if ( !function_exists('is_multisite') && version_compare( $wp_version, '3.0', '<' ) ) {
        
        function drainware_version_warning() {
            echo "
            <div id='drainware-warning' class='updated fade'><p><strong>".sprintf(__('drainware %s requires WordPress 3.0 or higher.'), drainware_VERSION) ."</strong> ".sprintf(__('Please <a href="%s">upgrade WordPress</a> to a current version, or <a href="%s">downgrade to version 2.4 of the drainware plugin</a>.'), 'http://codex.wordpress.org/Upgrading_WordPress', 'http://wordpress.org/extend/plugins/drainware/download/'). "</p></div>
            ";
        }
        add_action('admin_notices', 'drainware_version_warning'); 
        
        return; 
    }

    if ( function_exists( 'get_plugin_page_hook' ) )
        $hook = get_plugin_page_hook( 'drainware-stats-display', 'index.php' );
    else
        $hook = 'dashboard_page_drainware-stats-display';
    add_action('admin_head-'.$hook, 'drainware_stats_script');
    add_meta_box('drainware-status', __('Comment History'), 'drainware_comment_status_meta_box', 'comment', 'normal');
	wp_register_style('drainware.css', drainware_PLUGIN_URL . 'drainware.css');
	wp_enqueue_style('drainware.css');
	wp_register_script('drainware.js', drainware_PLUGIN_URL . 'drainware.js', array('jquery'));
	wp_enqueue_script('drainware.js');
}
add_action('admin_init', 'drainware_admin_init');

function drainware_nonce_field($action = -1) { return wp_nonce_field($action); }
$drainware_nonce = 'drainware-update-key';

function drainware_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('plugins.php', __('drainware Configuration'), __('drainware Configuration'), 'manage_options', 'drainware-key-config', 'drainware_conf');
}

function drainware_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/drainware.php' ) ) {
		$links[] = '<a href="plugins.php?page=drainware-key-config">'.__('Settings').'</a>';
	}

	return $links;
}

add_filter( 'plugin_action_links', 'drainware_plugin_action_links', 10, 2 );

function drainware_conf() {
	global $drainware_nonce, $wpcom_api_key;

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		check_admin_referer( $drainware_nonce );
		$key = preg_replace( '/[^a-h0-9]/i', '', $_POST['key'] );
		$home_url = parse_url( get_bloginfo('url') );

		if ( empty($key) ) {
			$key_status = 'empty';
			$ms[] = 'new_key_empty';
			delete_option('wordpress_api_key');
		} elseif ( empty($home_url['host']) ) {
			$key_status = 'empty';
			$ms[] = 'bad_home_url';
		} else {
			$key_status = drainware_verify_key( $key );
		}

		if ( $key_status == 'valid' ) {
			update_option('wordpress_api_key', $key);
			$ms[] = 'new_key_valid';
		} else if ( $key_status == 'invalid' ) {
			$ms[] = 'new_key_invalid';
		} else if ( $key_status == 'failed' ) {
			$ms[] = 'new_key_failed';
		}

		if ( isset( $_POST['drainware_discard_month'] ) )
			update_option( 'drainware_discard_month', 'true' );
		else
			update_option( 'drainware_discard_month', 'false' );

		if ( isset( $_POST['drainware_show_user_comments_approved'] ) )
			update_option( 'drainware_show_user_comments_approved', 'true' );
		else
			update_option( 'drainware_show_user_comments_approved', 'false' );

	} elseif ( isset($_POST['check']) ) {
		drainware_get_server_connectivity(0);
	}

	if ( empty( $key_status) ||  $key_status != 'valid' ) {
		$key = get_option('wordpress_api_key');
		if ( empty( $key ) ) {
			if ( empty( $key_status ) || $key_status != 'failed' ) {
				if ( drainware_verify_key( '1234567890ab' ) == 'failed' )
					$ms[] = 'no_connection';
				else
					$ms[] = 'key_empty';
			}
			$key_status = 'empty';
		} else {
			$key_status = drainware_verify_key( $key );
		}
		if ( $key_status == 'valid' ) {
			$ms[] = 'key_valid';
		} else if ( $key_status == 'invalid' ) {
			delete_option('wordpress_api_key');
			$ms[] = 'key_empty';
		} else if ( !empty($key) && $key_status == 'failed' ) {
			$ms[] = 'key_failed';
		}
	}

	$messages = array(
		'new_key_empty' => array('color' => 'aa0', 'text' => __('Your key has been cleared.')),
		'new_key_valid' => array('color' => '4AB915', 'text' => __('Your key has been verified. Happy blogging!')),
		'new_key_invalid' => array('color' => '888', 'text' => __('The key you entered is invalid. Please double-check it.')),
		'new_key_failed' => array('color' => '888', 'text' => __('The key you entered could not be verified because a connection to drainware.com could not be established. Please check your server configuration.')),
		'no_connection' => array('color' => '888', 'text' => __('There was a problem connecting to the drainware server. Please check your server configuration.')),
		'key_empty' => array('color' => 'aa0', 'text' => sprintf(__('Please enter an API key. (<a href="%s" style="color:#fff">Get your key.</a>)'), 'http://drainware.com/get/')),
		'key_valid' => array('color' => '4AB915', 'text' => __('This key is valid.')),
		'key_failed' => array('color' => 'aa0', 'text' => __('The key below was previously validated but a connection to drainware.com can not be established at this time. Please check your server configuration.')),
		'bad_home_url' => array('color' => '888', 'text' => sprintf( __('Your WordPress home URL %s is invalid.  Please fix the <a href="%s">home option</a>.'), esc_html( get_bloginfo('url') ), admin_url('options.php#home') ) ),
	);
?>
<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<h2><?php _e('drainware Configuration'); ?></h2>
<?php if (isset($_GET['message']) && $_GET['message'] == 'success') { ?>
	<div class="updated below-h2" id="message"><p><?php _e( '<strong>Sign up success!</strong> Please check your email for your drainware API Key and enter it below.' ); ?></p></div>
<?php } ?>
<div class="narrow">
<div style="margin: auto; width: 400px; ">

	<p><?php printf(__('<a href="%1$s">Drainware</a> is company focused on content filter systems. We have created Drainware WP to help you keeping your Wordpress free of comments with bad words, pornography and violence. Drainware will save you many time moderating your comments with no effort. More information in <a href="%2$s">Drainware</a>.'), 'http://drainware.com/', 'http://www.drainware.com'); ?></p>
</div>

<form action="" method="post" id="drainware-connectivity" style="margin: auto; width: 400px; ">

<h3><?php _e('Server Connectivity'); ?></h3>
<?php
	if ( !function_exists('fsockopen') || !function_exists('gethostbynamel') ) {
		?>
			<p style="padding: .5em; background-color: #888; color: #fff; font-weight:bold;"><?php _e('Network functions are disabled.'); ?></p>
			<p><?php echo sprintf( __('Your web host or server administrator has disabled PHP\'s <code>fsockopen</code> or <code>gethostbynamel</code> functions.  <strong>drainware cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about drainware\'s system requirements</a>.'), 'http://blog.drainware.com/drainware-hosting-faq/'); ?></p>
		<?php
	} else {
		$servers = drainware_get_server_connectivity();
		print_r($server);
		
		$fail_count = count($servers) - count( array_filter($servers) );
		if ( is_array($servers) && count($servers) > 0 ) {
			// some connections work, some fail
			if ( $fail_count > 0 && $fail_count < count($servers) ) { ?>
				<p style="padding: .5em; background-color: #aa0; color: #fff; font-weight:bold;"><?php _e('Unable to reach some drainware servers.'); ?></p>
				<p><?php echo sprintf( __('A network problem or firewall is blocking some connections from your web server to drainware.com.  drainware is working but this may cause problems during times of network congestion.  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about drainware and firewalls</a>.'), 'http://blog.drainware.com/drainware-hosting-faq/'); ?></p>
			<?php
			// all connections fail
			} elseif ( $fail_count > 0 ) { ?>
				<p style="padding: .5em; background-color: #888; color: #fff; font-weight:bold;"><?php _e('Unable to reach any drainware servers.'); ?></p>
				<p><?php echo sprintf( __('A network problem or firewall is blocking all connections from your web server to drainware.com.  <strong>drainware cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about drainware and firewalls</a>.'), 'http://blog.drainware.com/drainware-hosting-faq/'); ?></p>
			<?php
			// all connections work
			} else { ?>
				<p style="padding: .5em; background-color: #4AB915; color: #fff; font-weight:bold;"><?php  _e('All drainware servers are available.'); ?></p>
				<p><?php _e('drainware is working correctly.  All servers are accessible.'); ?></p>
			<?php
			}
		} else {
			?>
				<p style="padding: .5em; background-color: #888; color: #fff; font-weight:bold;"><?php _e('Unable to find drainware servers.'); ?></p>
				<p><?php echo sprintf( __('A DNS problem or firewall is preventing all access from your web server to api.drainware.com.  <strong>drainware cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about drainware and firewalls</a>.'), 'http://blog.drainware.com/drainware-hosting-faq/'); ?></p>
			<?php
		}
	}
	
	if ( !empty($servers) ) {
?>
<table style="width: 100%;">
<thead><th><?php _e('drainware server'); ?></th><th><?php _e('Network Status'); ?></th></thead>
<tbody>
<?php
		asort($servers);
		foreach ( $servers as $ip => $status ) {
			$color = ( $status ? '#4AB915' : '#888');
	?>
		<tr>
		<td><?php echo htmlspecialchars($ip); ?></td>
		<td style="padding: 0 .5em; font-weight:bold; color: #fff; background-color: <?php echo $color; ?>"><?php echo ($status ? __('Accessible') : __('Re-trying') ); ?></td>
		
	<?php
		}
	}
?>
</tbody>
</table>
	<p><?php if ( get_option('drainware_connectivity_time') ) echo sprintf( __('Last checked %s ago.'), human_time_diff( get_option('drainware_connectivity_time') ) ); ?></p>
	<p class="submit"><input type="submit" name="check" value="<?php _e('Check network status &raquo;'); ?>" /></p>
</form>

</div>
</div>
<?php
}

function drainware_stats_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('index.php', __('drainware Stats'), __('drainware Stats'), 'manage_options', 'drainware-stats-display', 'drainware_stats_display');

}

function drainware_stats_script() {
	?>
<script type="text/javascript">
function resizeIframe() {
  
    document.getElementById('drainware-stats-frame').style.height = "2500px";
    
};
function resizeIframeInit() {
	document.getElementById('drainware-stats-frame').onload = resizeIframe;
	window.onresize = resizeIframe;
}
addLoadEvent(resizeIframeInit);
</script><?php
}


function drainware_stats_display() {
	global $drainware_api_host, $drainware_api_port, $wpcom_api_key;
	$blog = urlencode( get_bloginfo('url') );

	$url = 'http://';
	if ( is_ssl() )
		$url = 'https://';

	$url .= 'api.drainware.com/user-stats.php';
	$url .= "?blog={$blog}&api_key=" . drainware_get_key();
	?>
	<div class="wrap">
	<iframe src="<?php echo $url; ?>" width="100%" height="100%" frameborder="0" id="drainware-stats-frame"></iframe>
	</div>
	<?php
}

function drainware_stats() {
	if ( !function_exists('did_action') || did_action( 'rightnow_end' ) ) // We already displayed this info in the "Right Now" section
		return;
	if ( !$count = get_option('drainware_spam_count') )
		return;
	$path = plugin_basename(__FILE__);
	echo '<h3>' . _x( 'Spam', 'comments' ) . '</h3>';
	global $submenu;
	if ( isset( $submenu['edit-comments.php'] ) )
		$link = 'edit-comments.php';
	else
		$link = 'edit.php';
	echo '<p>'.sprintf( _n( '<a href="%1$s">drainware</a> has protected your site from <a href="%2$s">%3$s spam comments</a>.', '<a href="%1$s">drainware</a> has protected your site from <a href="%2$s">%3$s spam comments</a>.', $count ), 'http://drainware.com/', clean_url("$link?page=drainware-admin"), number_format_i18n($count) ).'</p>';
}
add_action('activity_box_end', 'drainware_stats');

function drainware_admin_warnings() {
	global $wpcom_api_key;
	if ( !get_option('wordpress_api_key') && !$wpcom_api_key && !isset($_POST['submit']) ) {
		function drainware_warning() {
			echo "
			<div id='drainware-warning' class='updated fade'><p><strong>".__('drainware is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your drainware API key</a> for it to work.'), "plugins.php?page=drainware-key-config")."</p></div>
			";
		}
		//add_action('admin_notices', 'drainware_warning');
		return;
	} elseif ( ( empty($_SERVER['SCRIPT_FILENAME']) || basename($_SERVER['SCRIPT_FILENAME']) == 'edit-comments.php' ) &&  wp_next_scheduled('drainware_schedule_cron_recheck') ) {
		function drainware_warning() {
			global $wpdb;
				$waiting = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE meta_key = 'drainware_error'" ) );
				$next_check = human_time_diff( wp_next_scheduled('drainware_schedule_cron_recheck') );
				if ( $waiting > 0 )
					echo "
			<div id='drainware-warning' class='updated fade'><p><strong>".__('drainware has detected a problem.')."</strong> ".sprintf(_n('A server or network problem prevented drainware from checking %d comment. It has been temporarily held for moderation and will be automatically re-checked in %s.', 'A server or network problem prevented drainware from checking %d comments. They have been temporarily held for moderation and will be automatically re-checked in %s.', $waiting), number_format_i18n( $waiting ), $next_check)."</p></div>
			";
		}
		add_action('admin_notices', 'drainware_warning');
		return;
	}
}

// FIXME placeholder

function drainware_comment_row_action( $a, $comment ) {

	// failsafe for old WP versions
	if ( !function_exists('add_comment_meta') )
		return $a;

	$drainware_result = get_comment_meta( $comment->comment_ID, 'drainware_result', true );
	$user_result = get_comment_meta( $comment->comment_ID, 'drainware_user_result', true);
	$comment_status = wp_get_comment_status( $comment->comment_ID );
	$desc = null;
	if ( !$user_result || $user_result == $drainware_result ) {
		// Show the original drainware result if the user hasn't overridden it, or if their decision was the same
		if ( $drainware_result == 'true' && $comment_status != 'spam' && $comment_status != 'trash' )
			$desc = __( 'Flagged as spam by drainware' );
		elseif ( $drainware_result == 'false' && $comment_status == 'spam' )
			$desc = __( 'Cleared by drainware' );
	} else {
		$who = get_comment_meta( $comment->comment_ID, 'drainware_user', true );
		if ( $user_result == 'true' )
			$desc = sprintf( __('Flagged as spam by %s'), $who );
		else
			$desc = sprintf( __('Un-spammed by %s'), $who );
	}

	// add a History item to the hover links, just after Edit
	if ( $drainware_result ) {
		$b = array();
		foreach ( $a as $k => $item ) {
			$b[ $k ] = $item;
			if ( $k == 'edit' )
				$b['history'] = '<a href="comment.php?action=editcomment&amp;c='.$comment->comment_ID.'#drainware-status" title="'. esc_attr__( 'View comment history' ) . '"> '. __('History') . '</a>';
		}
		
		$a = $b;
	}
		
	if ( $desc )
		echo '<span class="drainware-status" commentid="'.$comment->comment_ID.'"><a href="comment.php?action=editcomment&amp;c='.$comment->comment_ID.'#drainware-status" title="' . esc_attr__( 'View comment history' ) . '">'.htmlspecialchars($desc).'</a></span>';
		
	if ( apply_filters( 'drainware_show_user_comments_approved', get_option('drainware_show_user_comments_approved') ) == 'true' ) {
		$comment_count = drainware_get_user_comments_approved( $comment->user_id, $comment->comment_author_email, $comment->comment_author, $comment->comment_author_url );
		$comment_count = intval( $comment_count );
		echo '<span class="drainware-user-comment-count" commentid="'.$comment->comment_ID.'" style="display:none;"><br><span class="drainware-user-comment-counts">'.sprintf( _n( '%s approved', '%s approved', $comment_count ), number_format_i18n( $comment_count ) ) . '</span></span>';
	}
	
	return $a;
}

add_filter( 'comment_row_actions', 'drainware_comment_row_action', 10, 2 );

function drainware_comment_status_meta_box($comment) {
	$history = drainware_get_comment_history( $comment->comment_ID );

	if ( $history ) {
		echo '<div class="drainware-history" style="margin: 13px;">';
		foreach ( $history as $row ) {
			$time = date( 'D d M Y @ h:i:m a', $row['time'] ) . ' GMT';
			echo '<div style="margin-bottom: 13px;"><span style="color: #999;" alt="' . $time . '" title="' . $time . '">' . sprintf( __('%s ago'), human_time_diff( $row['time'] ) ) . '</span> - ';
			echo htmlspecialchars( $row['message'] ) . '</div>';
		}
		
		echo '</div>';

	}
}


// add an extra column header to the comments screen
function drainware_comments_columns( $columns ) {
	$columns[ 'drainware' ] = __( 'drainware' );
	return $columns;
}

#add_filter( 'manage_edit-comments_columns', 'drainware_comments_columns' );

// Show stuff in the extra column
function drainware_comment_column_row( $column, $comment_id ) {
	if ( $column != 'drainware' )
		return;
		
	$history = drainware_get_comment_history( $comment_id );
	
	if ( $history ) {
		echo '<dl class="drainware-history">';
		foreach ( $history as $row ) {
			echo '<dt>' . sprintf( __('%s ago'), human_time_diff( $row['time'] ) ) . '</dt>';
			echo '<dd>' . htmlspecialchars( $row['message'] ) . '</dd>';
		}
		
		echo '</dl>';
	}
}

#add_action( 'manage_comments_custom_column', 'drainware_comment_column_row', 10, 2 );

// END FIXME

// call out URLS in comments
function drainware_text_add_link_callback( $m ) {
	
		// bare link?
        if ( $m[4] == $m[2] )
                return '<a '.$m[1].' href="'.$m[2].'" '.$m[3].' class="comment-link">'.$m[4].'</a>';
        else
                return '<span title="'.$m[2].'" class="comment-link"><a '.$m[1].' href="'.$m[2].'" '.$m[3].' class="comment-link">'.$m[4].'</a></span>';
}

function drainware_text_add_link_class( $comment_text ) {

        return preg_replace_callback( '#<a ([^>]*)href="([^"]+)"([^>]*)>(.*?)</a>#i', 'drainware_text_add_link_callback', $comment_text );
}

add_filter('comment_text', 'drainware_text_add_link_class');


// WP 2.5+
function drainware_rightnow() {
	global $submenu, $wp_db_version;

	// clean_url was deprecated in WP 3.0
	$esc_url = 'clean_url';
	if ( function_exists( 'esc_url' ) )
		$esc_url = 'esc_url';

	if ( 8645 < $wp_db_version  ) // 2.7
		$link = 'edit-comments.php?comment_status=spam';
	elseif ( isset( $submenu['edit-comments.php'] ) )
		$link = 'edit-comments.php?page=drainware-admin';
	else
		$link = 'edit.php?page=drainware-admin';

	if ( $count = get_option('drainware_spam_count') ) {
		$intro = sprintf( _n(
			'<a href="%1$s">drainware</a> has protected your site from %2$s spam comment already. ',
			'<a href="%1$s">drainware</a> has protected your site from %2$s spam comments already. ',
			$count
		), 'http://drainware.com/', number_format_i18n( $count ) );
	} else {
		$intro = sprintf( __('<a href="%1$s">drainware</a> blocks spam from getting to your blog. '), 'http://drainware.com/' );
	}

	if ( $queue_count = drainware_spam_count() ) {
		$queue_text = sprintf( _n(
			'There\'s <a href="%2$s">%1$s comment</a> in your spam queue right now.',
			'There are <a href="%2$s">%1$s comments</a> in your spam queue right now.',
			$queue_count
		), number_format_i18n( $queue_count ), $esc_url($link) );
	} else {
		$queue_text = sprintf( __( "There's nothing in your <a href='%1\$s'>spam queue</a> at the moment." ), $esc_url($link) );
	}

	$text = $intro . '<br />' . $queue_text;
	echo "<p class='drainware-right-now'>$text</p>\n";
}
	
add_action('rightnow_end', 'drainware_rightnow');


// For WP >= 2.5
function drainware_check_for_spam_button($comment_status) {
	if ( 'approved' == $comment_status )
		return;
	if ( function_exists('plugins_url') )
		$link = 'admin.php?action=drainware_recheck_queue';
	else
		$link = 'edit-comments.php?page=drainware-admin&amp;recheckqueue=true&amp;noheader=true';
	echo "</div><div class='alignleft'><a class='button-secondary checkforspam' href='$link'>" . __('Check for undesirable comments') . "</a>";
}
add_action('manage_comments_nav', 'drainware_check_for_spam_button');

function drainware_submit_nonspam_comment ( $comment_id ) {
	global $wpdb, $drainware_api_host, $drainware_api_port, $current_user, $current_site;
	$comment_id = (int) $comment_id;

	$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = '$comment_id'");
	if ( !$comment ) // it was deleted
		return;
		
	// use the original version stored in comment_meta if available	
	$as_submitted = get_comment_meta( $comment_id, 'drainware_as_submitted', true);
	if ( $as_submitted && is_array($as_submitted) && isset($as_submitted['comment_content']) ) {
		$comment = (object) array_merge( (array)$comment, $as_submitted );
	}
	
	$comment->blog = get_bloginfo('url');
	$comment->blog_lang = get_locale();
	$comment->blog_charset = get_option('blog_charset');
	$comment->permalink = get_permalink($comment->comment_post_ID);
	$comment->reporter_ip = $_SERVER['REMOTE_ADDR'];
	if ( is_object($current_user) ) {
	    $comment->reporter = $current_user->user_login;
	}
	if ( is_object($current_site) ) {
		$comment->site_domain = $current_site->domain;
	}

	$comment->user_role = '';
	if ( isset( $comment->user_ID ) )
		$comment->user_role = drainware_get_user_roles($comment->user_ID);

	if ( drainware_test_mode() )
		$comment->is_test = 'true';

	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

	$response = drainware_http_post($query_string, $drainware_api_host, "/1.1/submit-ham", $drainware_api_port);
	if ( $comment->reporter ) {
		drainware_update_comment_history( $comment_id, sprintf( __('%s reported this comment as not spam'), $comment->reporter ), 'report-ham' );
		update_comment_meta( $comment_id, 'drainware_user_result', 'false' );
		update_comment_meta( $comment_id, 'drainware_user', $comment->reporter );
	}
	
	do_action('drainware_submit_nonspam_comment', $comment_id, $response[1]);
}

function drainware_submit_spam_comment ( $comment_id ) {
	global $wpdb, $drainware_api_host, $drainware_api_port, $current_user, $current_site;
	$comment_id = (int) $comment_id;

	$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = '$comment_id'");
	if ( !$comment ) // it was deleted
		return;
	if ( 'spam' != $comment->comment_approved )
		return;
	
	// use the original version stored in comment_meta if available	
	$as_submitted = get_comment_meta( $comment_id, 'drainware_as_submitted', true);
	if ( $as_submitted && is_array($as_submitted) && isset($as_submitted['comment_content']) ) {
		$comment = (object) array_merge( (array)$comment, $as_submitted );
	}
	
	$comment->blog = get_bloginfo('url');
	$comment->blog_lang = get_locale();
	$comment->blog_charset = get_option('blog_charset');
	$comment->permalink = get_permalink($comment->comment_post_ID);
	$comment->reporter_ip = $_SERVER['REMOTE_ADDR'];
	if ( is_object($current_user) ) {
	    $comment->reporter = $current_user->user_login;
	}
	if ( is_object($current_site) ) {
		$comment->site_domain = $current_site->domain;
	}

	$comment->user_role = '';
	if ( isset( $comment->user_ID ) )
		$comment->user_role = drainware_get_user_roles($comment->user_ID);

	if ( drainware_test_mode() )
		$comment->is_test = 'true';

	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

	$response = drainware_http_post($query_string, $drainware_api_host, "/1.1/submit-spam", $drainware_api_port);
	if ( $comment->reporter ) {
		drainware_update_comment_history( $comment_id, sprintf( __('%s reported this comment as spam'), $comment->reporter ), 'report-spam' );
		update_comment_meta( $comment_id, 'drainware_user_result', 'true' );
		update_comment_meta( $comment_id, 'drainware_user', $comment->reporter );
	}
	do_action('drainware_submit_spam_comment', $comment_id, $response[1]);
}

// For WP 2.7+
function drainware_transition_comment_status( $new_status, $old_status, $comment ) {
	if ( $new_status == $old_status )
		return;

	# we don't need to record a history item for deleted comments
	if ( $new_status == 'delete' )
		return;
		
	if ( !is_admin() )
		return;
		
	if ( !current_user_can( 'edit_post', $comment->comment_post_ID ) && !current_user_can( 'moderate_comments' ) )
		return;

	if ( defined('WP_IMPORTING') && WP_IMPORTING == true )
		return;
		
	global $current_user;
	$reporter = '';
	if ( is_object( $current_user ) )
		$reporter = $current_user->user_login;
	
	// Assumption alert:
	// We want to submit comments to drainware only when a moderator explicitly spams or approves it - not if the status
	// is changed automatically by another plugin.  Unfortunately WordPress doesn't provide an unambiguous way to
	// determine why the transition_comment_status action was triggered.  And there are several different ways by which
	// to spam and unspam comments: bulk actions, ajax, links in moderation emails, the dashboard, and perhaps others.
	// We'll assume that this is an explicit user action if POST or GET has an 'action' key.
	if ( isset($_POST['action']) || isset($_GET['action']) ) {
		if ( $new_status == 'spam' && ( $old_status == 'approved' || $old_status == 'unapproved' || !$old_status ) ) {
				return drainware_submit_spam_comment( $comment->comment_ID );
		} elseif ( $old_status == 'spam' && ( $new_status == 'approved' || $new_status == 'unapproved' ) ) {
				return drainware_submit_nonspam_comment( $comment->comment_ID );
		}
	}
	
	if ( !get_comment_meta( $comment->comment_ID, 'drainware_rechecking' ) )
		drainware_update_comment_history( $comment->comment_ID, sprintf( __('%s changed the comment status to %s'), $reporter, $new_status ), 'status-' . $new_status );
}

add_action( 'transition_comment_status', 'drainware_transition_comment_status', 10, 3 );

// Total spam in queue
// get_option( 'drainware_spam_count' ) is the total caught ever
function drainware_spam_count( $type = false ) {
	global $wpdb;

	if ( !$type ) { // total
		$count = wp_cache_get( 'drainware_spam_count', 'widget' );
		if ( false === $count ) {
			if ( function_exists('wp_count_comments') ) {
				$count = wp_count_comments();
				$count = $count->spam;
			} else {
				$count = (int) $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'spam'");
			}
			wp_cache_set( 'drainware_spam_count', $count, 'widget', 3600 );
		}
		return $count;
	} elseif ( 'comments' == $type || 'comment' == $type ) { // comments
		$type = '';
	} else { // pingback, trackback, ...
		$type  = $wpdb->escape( $type );
	}

	return (int) $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'spam' AND comment_type='$type'");
}


function drainware_recheck_queue() {
	global $wpdb, $drainware_api_host, $drainware_api_port;

	if ( ! ( isset( $_GET['recheckqueue'] ) || ( isset( $_REQUEST['action'] ) && 'drainware_recheck_queue' == $_REQUEST['action'] ) ) )
		return;
		
	$moderation = $wpdb->get_results( "SELECT * FROM $wpdb->comments WHERE comment_approved = '0'", ARRAY_A );
	foreach ( (array) $moderation as $c ) {
		$c['user_ip']    = $c['comment_author_IP'];
		$c['user_agent'] = $c['comment_agent'];
		$c['referrer']   = '';
		$c['blog']       = get_bloginfo('url');
		$c['blog_lang']  = get_locale();
		$c['blog_charset'] = get_option('blog_charset');
		$c['permalink']  = get_permalink($c['comment_post_ID']);

		$c['user_role'] = '';
		if ( isset( $c['user_ID'] ) )
			$c['user_role']  = drainware_get_user_roles($c['user_ID']);

		if ( drainware_test_mode() )
			$c['is_test'] = 'true';

		$id = (int) $c['comment_ID'];

		$query_string = '';
		foreach ( $c as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

		$response = drainware_http_post($query_string, $drainware_api_host, '/1.1/comment-check', $drainware_api_port);
		if ( 'true' == $response[1] ) {
			wp_set_comment_status($c['comment_ID'], 'spam');
			update_comment_meta( $c['comment_ID'], 'drainware_result', 'true' );
			drainware_update_comment_history( $c['comment_ID'], __('drainware re-checked and caught this comment as spam'), 'check-spam' );
		
		} elseif ( 'false' == $response[1] ) {
			update_comment_meta( $c['comment_ID'], 'drainware_result', 'false' );
			drainware_update_comment_history( $c['comment_ID'], __('drainware re-checked and cleared this comment'), 'check-ham' );
		// abnormal result: error
		} else {
			update_comment_meta( $c['comment_ID'], 'drainware_result', 'error' );
			drainware_update_comment_history( $c['comment_ID'], sprintf( __('drainware was unable to re-check this comment (response: %s)'), $response[1]), 'check-error' );
		}

	}
	wp_redirect( $_SERVER['HTTP_REFERER'] );
	exit;
}

add_action('admin_action_drainware_recheck_queue', 'drainware_recheck_queue');

// Check connectivity between the WordPress blog and drainware's servers.
// Returns an associative array of server IP addresses, where the key is the IP address, and value is true (available) or false (unable to connect).
function drainware_check_server_connectivity() {
	global $drainware_api_host, $drainware_api_port, $wpcom_api_key;

	
	$test_host = 'drainware.com';
	
	// Some web hosts may disable one or both functions
	if ( !function_exists('fsockopen') || !function_exists('gethostbynamel') )
		return array();
    
	$ips = gethostbynamel($test_host);
	if ( !$ips || !is_array($ips) || !count($ips) )
		return array();
		
	$servers = array();
	foreach ( $ips as $ip ) {
		//$response = drainware_verify_key( drainware_get_key(), $ip );
		$response = 'valid';
		// even if the key is invalid, at least we know we have connectivity
		if ( $response == 'valid' || $response == 'invalid' )
			$servers[$ip] = true;
		else
			$servers[$ip] = false;
	}
	return $servers;
}

// Check the server connectivity and store the results in an option.
// Cached results will be used if not older than the specified timeout in seconds; use $cache_timeout = 0 to force an update.
// Returns the same associative array as drainware_check_server_connectivity()
function drainware_get_server_connectivity( $cache_timeout = 86400 ) {
	$servers = get_option('drainware_available_servers');
	if ( (time() - get_option('drainware_connectivity_time') < $cache_timeout) && $servers !== false )
	
	// There's a race condition here but the effect is harmless.
	$servers = drainware_check_server_connectivity();
	update_option('drainware_available_servers', $servers);
	update_option('drainware_connectivity_time', time());
	//return $servers;
	
	$servers["api.drainware.com"] = true;
	return $servers;
	
}

// Returns true if server connectivity was OK at the last check, false if there was a problem that needs to be fixed.
function drainware_server_connectivity_ok() {
	// skip the check on WPMU because the status page is hidden
	global $wpcom_api_key;
	if ( $wpcom_api_key )
		return true;
	$servers = drainware_get_server_connectivity();
	return !( empty($servers) || !count($servers) || count( array_filter($servers) ) < count($servers) );
}

