
<?php
	$cparam = 'orderby=name&depth=-1&title_li=&show_up=true'; 
?>
<!-- Navigation -->
 <ul class="sf-menu">  

 <li >
			<a href="<?php bloginfo('url'); ?>/about-us/" title="<?php _e('About Us'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('About Us'); } else { echo $pages_title; } ?></a>
		</li>
 <li>
			<a href="<?php bloginfo('url'); ?>/membership/" title="<?php _e('Membership'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Membership'); } else { echo $pages_title; } ?></a>
		</li>

       
          <li >
			<a href="<?php bloginfo('url'); ?>/tools/" title="<?php _e('Tools'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Tools'); } else { echo $pages_title; } ?></a>
		</li>
    
    
	<li>
		<a href="<?php bloginfo('url'); ?>/workshops/" title="<?php _e('Workshops'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Workshops'); } else { echo $pages_title; } ?></a>
    </li>
  
 
        <li><a href="<?php bloginfo('url'); ?>/calendar/" title="<?php _e('Calendar'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Calendar'); } else { echo $pages_title; } ?></a></li>
        
        <li><a href="<?php bloginfo('url'); ?>/forum/" title="<?php _e('Forum'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Forum'); } else { echo $pages_title; } ?></a></li>
        
        <li><a href="<?php bloginfo('url'); ?>/partners/" title="<?php _e('Partners'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Partners'); } else { echo $pages_title; } ?></a></li>
        
        <li><a href="<?php bloginfo('url'); ?>/sponsors/" title="<?php _e('Sponsors'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Sponsors'); } else { echo $pages_title; } ?></a></li>
        
        <li><a href="<?php bloginfo('url'); ?>/get-involved/" title="<?php _e('Get Involved'); ?>"><?php if($pages_title==="" || !$pages_title) { _e('Get Involved'); } else { echo $pages_title; } ?></a></li>
        
               
        
</ul>
        <div class="left">
<p class="top">This design uses a defined body height of 100% which allows setting the
contained left and right divs at 100% height.</p>

</div>