<?php
function subsimple_customize_register_misc( $wp_customize ) {
	$wp_customize->add_section(
	    'subsimple_sec_upgrade',
	    array(
	        'title'     => __('SubSimple - Help & Support','subsimple'),
	        'priority'  => 45,
	    )
	);
	
	$wp_customize->add_setting(
			'subsimple_upgrade',
			array( 'sanitize_callback' => 'esc_textarea' )
		);
			
	$wp_customize->add_control(
	    new Hanne_WP_Customize_Upgrade_Control(
	        $wp_customize,
	        'subsimple_upgrade',
	        array(
	            'label' => __('Free Email Support','subsimple'),
	            'description' => __('Currently We are Offering Free Email Support with our theme. If you have any queries or require help please <a href="https://inkhive.com/product/subsimple/">Read our FAQs</a> and if your problem is still not solved then contact us. <br><br>','subsimple'),
	            'section' => 'subsimple_sec_upgrade',
	            'settings' => 'subsimple_upgrade',			       
	        )
		)
	);
}
add_action( 'customize_register', 'subsimple_customize_register_misc' );