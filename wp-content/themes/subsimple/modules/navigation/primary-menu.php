	<nav id="site-navigation" class="main-navigation title-font" role="navigation">
		<?php
			// Get the Appropriate Walker First.
			$walker = has_nav_menu('primary') ? new Ih_Business_Pro_Menu_With_Icon : '';
			    //Display the Menu.							
		    wp_nav_menu( array( 'theme_location' => 'primary', 'walker' => $walker ) ); ?>
	</nav><!-- #site-navigation -->
	<div id="slickmenu"></div>