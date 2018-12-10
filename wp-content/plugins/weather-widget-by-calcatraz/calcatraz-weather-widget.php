<?php
/*
Plugin Name: Weather Widget by Calcatraz
Plugin URI: http://www.calcatraz.com/blog/free-wordpress-weather-widget-1995
Description: A simple, compact weather widget which displays current conditions and 3-day forecast for the selected location.
Author: Dan Mossop
Version: 1.3.7
Author URI: http://www.danmossop.com
*/

$calcatrazweatherwidget_version = '1.3.7';

class calcatrazWeatherWidget extends WP_Widget {
	private $wid = 'calcatrazWeatherWidget'; // should be same as class name
	private $wname = "Weather Widget by Calcatraz";
	private $wdescr = 'A simple, compact weather widget which displays current conditions and 3-day forecast for selected location.';
	
	
	function calcatrazWeatherWidget() {
		$this->WP_Widget($this->wid, $this->wname, array('classname'=>$this->wid, 'description'=>$this->wdescr));
		wp_enqueue_script('jquery');
	}
 
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title'=>'','city'=>''));
		
		// error message
		$error = get_option("trazww_error");
		if (!empty($error)) { 
			echo '<p style="color:red;font-weight:bold">Error: '.htmlentities($error).'</p>';
		}
		
		// title
		$id = $this->get_field_id('title');
		$name = $this->get_field_name('title');
		$val = attribute_escape($instance['title']);
		echo <<<END
		<p><label for="$id">Title: <input class="widefat" id="$id" name="$name" type="text" value="$val" /></label></p> 
END;
		// city
		$cid = $this->get_field_id('city');
		$cname = $this->get_field_name('city');
		$cval = attribute_escape($instance['city']);
		echo <<<END
		<p><label for="$cid">City: <input class="widefat" id="$cid" name="$cname" type="text" value="$cval" /></label></p>
		<p style="font-size:smaller;font-style:italic">Must be one of: US Zipcode, UK Postcode, Canada Postalcode, IP address, city name, or Latitude/Longitude (as decimal)</p>	
END;
		// api key
		$kid = $this->get_field_id('key');
		$kname = $this->get_field_name('key');
		$kval = attribute_escape($instance['key']);
		echo <<<END
		<p><label for="$kid">API Key: <input class="widefat" id="$kid" name="$kname" type="text" value="$kval" /></label></p> 
		<p style="font-size:smaller;font-style:italic">This widget requires an API key from World Weather Online. You can get it free <a href="http://developer.worldweatheronline.com/">here</a></p>
END;
		// layout
		$crid = $this->get_field_id('layout');
		$crname = $this->get_field_name('layout');
		$select = esc_attr($instance['layout']);
		$selected_default = ($select=='default')?' selected':'';
		$selected_square = ($select=='square')?' selected':'';
		echo <<<END
		<p><label for="$crid">Size: <select class="widefat" id="$crid" name="$crname"><option value="default"$selected_default>Default (260 x 58px)</option><option value="square" $selected_square>Square (133 x 116px)</option></select></label></p> 
END;
		// scale
		$crid = $this->get_field_id('scale');
		$crname = $this->get_field_name('scale');
		$select = esc_attr($instance['scale']);
		$selected_auto = ($select=='auto')?' selected':'';
		$selected_c = ($select=='c')?' selected':'';
		$selected_f = ($select=='f')?' selected':'';
		echo <<<END
		<p><label for="$crid">Scale: <select class="widefat" id="$crid" name="$crname"><option value="auto"$selected_auto>Auto</option><option value="c" $selected_c>Celcius</option><option value="f" $selected_f>Fahrenheit</option></select></label></p> 
		<p style="font-size:smaller;font-style:italic">"Auto" selects the scale based on the user's browser language setting.</p>
END;
		// credits
		$crid = $this->get_field_id('credits');
		$crname = $this->get_field_name('credits');
		$checkbox = esc_attr($instance['credits']);
		$checked = checked('1', $checkbox, false);
		echo <<<END
		<p><label for="$crid">Show Credits: <input id="$crid" name="$crname" type="checkbox" value="1" $checked/></label></p> 
		<p style="font-size:smaller;font-style:italic">The World Weather Online free API requires a link crediting them to be shown. Their premium API does not.</p>
END;
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach(array('title', 'city', 'key', 'credits', 'scale', 'layout') as $k) {
			$instance[$k] = $new_instance[$k];
		}
		return $instance;
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title'])?' ':apply_filters('widget_title', $instance['title']);
		if (!empty($title)) { echo $before_title.$title.$after_title; }
    
		// WIDGET CODE GOES HERE
		$city = empty($instance['city'])?' ':apply_filters('widget_title', $instance['city']);
		$key = empty($instance['key'])?' ':apply_filters('widget_title', $instance['key']);
		$scale = empty($instance['scale'])?' ':apply_filters('widget_title', $instance['scale']);
		$layout = empty($instance['layout'])?' ':apply_filters('widget_title', $instance['layout']);
		$credits = empty($instance['credits'])?false:$instance['credits'];
		$weatherdata = calcatrazweatherwidget_loadData($city, $key);
		calcatrazweatherwidget_showWidget($weatherdata, $credits, $scale, $layout);
		
		// END WIDGET CODE
		
		echo $after_widget;
	}
}

function calcatrazweatherwidget_loadData($city, $key) {
	$cityslug = md5($city);
	$file = dirname(__FILE__)."/data/$cityslug.json";
	if (!file_exists($file) or filemtime($file)<time()-60*60 or filesize($file)==0 or get_option('trazww_error')!='') { // refresh if > 1 hr old, empty, or an error occurred last time
		$url = 'http://api.worldweatheronline.com/free/v1/weather.ashx?q='.urlencode(strtolower($city)).'&format=json&num_of_days=4&includelocation=yes&key='.urlencode(strtolower(trim($key)));
		if (function_exists('curl_exec')) {
			try { 		
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$weatherdata = curl_exec($curl);
				curl_close($curl);
			} catch (Exception $e) {  
				$curlfailed=true;
			}
			if ($weatherdata=='') { $curlfailed=true; }
		} 
		if (function_exists('file_get_contents')) { 
			if (!function_exists('curl_exec') or (isset($curlfailed) and $curlfailed==true)) {
				try { 
					$weatherdata = @file_get_contents($url);
				} catch (Exception $e) { 
					update_option("trazww_error", 'Weather data request failed'); 
					$weatherdata = '';
				}
			}
		}
		file_put_contents($file, $weatherdata);
	} else { 
		$weatherdata = file_get_contents($file);
	}
	
	// === check for errors
	if (!function_exists('file_get_contents') and !function_exists('curl_exec')) { 
		update_option("trazww_error", 'Your host is blocking remote file requests'); return ''; 
	} elseif ($weatherdata=='<h1>Developer Inactive</h1>') { 
		update_option("trazww_error", 'Invalid API key'); return ''; 
	} elseif (strpos($weatherdata, 'Unable to find any matching weather location')!==false) { 
		update_option("trazww_error", 'Unknown City'); return ''; 
	} elseif (trim($weatherdata)=='') { 
		update_option("trazww_error", 'No weather data received'); return ''; 
	} else {
		update_option("trazww_error", ''); // no errors
	}
	
	return $weatherdata;
}

function calcatrazweatherwidget_showWidget($weatherdata, $showcredits, $scale, $layout) {
	$theme_layout = $layout;
	$theme_style = 'default';
	
	$w = json_decode($weatherdata);
	
	if ($scale!='c' and $scale!='f') { // set to auto-detect
		$scale = (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],'en-us')!==0)?'f':'c';
	} // otherwise, uses setting specified in widget config
	
	// current data
	$now = $w->data->current_condition[0];
	$now_icon = htmlentities($now->weatherIconUrl[0]->value);
	$now_temp_c = htmlentities($now->temp_C).'&deg;C';
	$now_temp_f = htmlentities($now->temp_F).'&deg;F';
	$now_desc = htmlentities($now->weatherDesc[0]->value);
	$now_temp = '<span class="temp" c="'.$now_temp_c.'" f="'.$now_temp_f.'">'.($scale=='c'?$now_temp_c:$now_temp_f).'</span>';
	
	// today forecast
	$today = $w->data->weather[0];
	$today_icon = htmlentities($today->weatherIconUrl[0]->value);
	$today_desc = htmlentities($today->weatherDesc[0]->value);
	$today_temp_max_c = htmlentities($today->tempMaxC).'&deg;C';
	$today_temp_min_c = htmlentities($today->tempMinC).'&deg;C';
	$today_temp_max_f = htmlentities($today->tempMaxF).'&deg;F';
	$today_temp_min_f = htmlentities($today->tempMinF).'&deg;F';
	
	$today_max = '<span class="temp" c="'.$today_temp_max_c.'" f="'.$today_temp_max_f.'">'.($scale=='c'?$today_temp_max_c:$today_temp_max_f).'</span>';
	$today_min = '<span class="temp" c="'.$today_temp_min_c.'" f="'.$today_temp_min_f.'">'.($scale=='c'?$today_temp_min_c:$today_temp_min_f).'</span>';
	
	// tomorrow forecast
	$tomorrow = $w->data->weather[1];
	$tomorrow_icon = htmlentities($tomorrow->weatherIconUrl[0]->value);
	$tomorrow_desc = htmlentities($tomorrow->weatherDesc[0]->value);
	$tomorrow_temp_max_c = htmlentities($tomorrow->tempMaxC).'&deg;C';
	$tomorrow_temp_min_c = htmlentities($tomorrow->tempMinC).'&deg;C';
	$tomorrow_temp_max_f = htmlentities($tomorrow->tempMaxF).'&deg;F';
	$tomorrow_temp_min_f = htmlentities($tomorrow->tempMinF).'&deg;F';
	$tomorrow_name = date("D",strtotime('tomorrow'));
	
	$tomorrow_max = '<span class="temp" c="'.$tomorrow_temp_max_c.'" f="'.$tomorrow_temp_max_f.'">'.($scale=='c'?$tomorrow_temp_max_c:$tomorrow_temp_max_f).'</span>';
	$tomorrow_min = '<span class="temp" c="'.$tomorrow_temp_min_c.'" f="'.$tomorrow_temp_min_f.'">'.($scale=='c'?$tomorrow_temp_min_c:$tomorrow_temp_min_f).'</span>';
	
	// day after forecast
	$dayafter = $w->data->weather[2];
	$dayafter_icon = htmlentities($dayafter->weatherIconUrl[0]->value);
	$dayafter_desc = htmlentities($dayafter->weatherDesc[0]->value);
	$dayafter_temp_max_c = htmlentities($dayafter->tempMaxC).'&deg;C';
	$dayafter_temp_min_c = htmlentities($dayafter->tempMinC).'&deg;C';
	$dayafter_temp_max_f = htmlentities($dayafter->tempMaxF).'&deg;F';
	$dayafter_temp_min_f = htmlentities($dayafter->tempMinF).'&deg;F';
	$dayafter_name = date("D",strtotime('tomorrow + 1 day'));
	
	
	$dayafter_max = '<span class="temp" c="'.$dayafter_temp_max_c.'" f="'.$dayafter_temp_max_f.'">'.($scale=='c'?$dayafter_temp_max_c:$dayafter_temp_max_f).'</span>';
	$dayafter_min = '<span class="temp" c="'.$dayafter_temp_min_c.'" f="'.$dayafter_temp_min_f.'">'.($scale=='c'?$dayafter_temp_min_c:$dayafter_temp_min_f).'</span>';
	
	// switch scale link
	$switch_scale_symbol = ($scale=='c'?'F':'C');
	$switch_scale_link =<<<END
		<a id="trazww-switch" href="javascript:;" onclick="trazww_switch()" title="Switch between Celcius and Fahrenheit">$switch_scale_symbol</a>
END;
	
	// inline css
	echo '<style>';
	include(dirname(__FILE__).'/css/core.min.css');
	if ($theme_layout=='square') {
		include(dirname(__FILE__).'/css/layout_square.min.css');
	} else { // make sure default is at end
		include(dirname(__FILE__).'/css/layout_default.min.css');
	}
	if ($theme_style=='default') {
		include(dirname(__FILE__).'/css/style_default.min.css');
	} else { 
		include(dirname(__FILE__).'/css/style_metro.min.css');
	}

	echo '</style>';
	
	echo <<<END
<div id="trazww">
<div id="trazww-l">
  <img id="trazww-now-i" src="$now_icon"/>
  <div id="trazww-scale">$switch_scale_link</div>
  <div id="trazww-now"><p id="trazww-now-p">$now_temp<br><span id="trazww-now-desc">$now_desc</span></p></div>
</div>
<div id="trazww-r">
<img class="trazww-r-i" src="$today_icon" title="$today_desc"/>
<span class="trazww-r-f trazww-r-r1">Today:</span>
<span class="trazww-r-f trazww-r-r1">$today_max / $today_min</span>
<img class="trazww-r-i trazww-r-i2" src="$tomorrow_icon" title="$tomorrow_desc"/>
<span class="trazww-r-f">$tomorrow_name:</span>
<span class="trazww-r-f">$tomorrow_max / $tomorrow_min</span>
<img class="trazww-r-i trazww-r-i2" src="$dayafter_icon" title="$dayafter_desc"/>
<span class="trazww-r-f">$dayafter_name:</span>
<span class="trazww-r-f">$dayafter_max / $dayafter_min</span>
</div>
</div>
END;
	if ($showcredits) { 
		echo<<<END
<div id="trazwwc">
Data by <a class="trazwwc-a" href="http://www.worldweatheronline.com/">World Weather Online</a>.
<span id="trazwwc-c">Widget by <a class="trazwwc-a"  href="http://www.calcatraz.com/blog/free-wordpress-weather-widget-1995?utm_source=wp_widget&utm_medium=link&utm_content=weather_widget&utm_campaign=wp_widget" title="About the weather widget by Calcatraz">Calcatraz</a></span></div>
END;
	}  
}

add_action( 'widgets_init', create_function('', 'return register_widget("calcatrazWeatherWidget");') );

// add js to footer
function calcatrazweatherwidget_js(){ 
$site = site_url();?>
<script>
function trazww_scale(e){ trazww_setCookie('trazww-scale', e); }
function trazww_setCookie(name, value, days) { document.cookie = escape(name)+"="+escape(value)+"; path=/"; }
function trazww_getCookie(name) {
    var nameEQ=escape(name)+"=";
    var ca=document.cookie.split(';');
    for (var i=0;i<ca.length;i++) {
        var c=ca[i];
        while(c.charAt(0)===' ') c=c.substring(1,c.length);
        if (c.indexOf(nameEQ)===0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}
function trazww_switch() {
	var celcius = (jQuery('#trazww-switch').text()=='C');
	jQuery('#trazww span.temp').text(function(i){
		var elem=jQuery('#trazww span.temp').get(i);
		return elem.getAttribute(celcius?'c':'f'); 
	});
	trazww_scale(celcius?'C':'F');
	jQuery('#trazww-switch').text(celcius?'F':'C');
}
if (jQuery('#trazww-switch').text()==trazww_getCookie('trazww-scale')) { trazww_switch(); }
</script>
<?php } 
add_action('wp_footer', 'calcatrazweatherwidget_js');
?>