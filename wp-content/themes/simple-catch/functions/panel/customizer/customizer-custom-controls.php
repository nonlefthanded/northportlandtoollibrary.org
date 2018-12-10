<?php
/**
* @package Catch Themes
* @subpackage Simple Catch
* @since Simple Catch 3.0
*/


//Custom control for category multiple select
class Simple_Catch_Customize_Dropdown_Categories_Control extends WP_Customize_Control {
	public $type = 'dropdown-categories';

	public $name;

	public $descripton;

	public function render_content() {
		$dropdown = wp_dropdown_categories(
			array(
				'name'             => $this->name,
				'echo'             => 0,
				'hide_empty'       => false,
				'show_option_none' => false,
				'hide_if_empty'    => false,
				'show_option_all'  => esc_html__( 'All Categories', 'simple-catch' )
			)
		);

		$dropdown = str_replace('<select', '<select multiple = "multiple" style = "height:95px;" ' . $this->get_link(), $dropdown );

		echo '<p class="description">'.  $this->description . '</p>';

		printf(
			'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
			$this->label,
			$dropdown
		);

		echo '<p class="description">'. esc_html__( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'simple-catch' ) . '</p>';
	}
}

//Custom control for important link
class Simple_Catch_Important_Links extends WP_Customize_Control {
    public $type = 'important-links';

    public function render_content() {
    	//Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links
        $important_links = array(
						'theme_instructions' => array(
							'link'	=> esc_url( 'https://catchthemes.com/theme-instructions/simple-catch/' ),
							'text' 	=> esc_html__( 'Theme Instructions', 'simple-catch' ),
							),
						'support' => array(
							'link'	=> esc_url( 'https://catchthemes.com/support/' ),
							'text' 	=> esc_html__( 'Support', 'simple-catch' ),
							),
						'changelog' => array(
							'link'	=> esc_url( 'https://catchthemes.com/changelogs/simple-catch-theme//' ),
							'text' 	=> esc_html__( 'Changelog', 'simple-catch' ),
							),
						'donate' => array(
							'link'	=> esc_url( 'https://catchthemes.com/donate/' ),
							'text' 	=> esc_html__( 'Donate Now', 'simple-catch' ),
							),
						'review' => array(
							'link'	=> esc_url( 'https://wordpress.org/support/view/theme-reviews/simple-catch' ),
							'text' 	=> esc_html__( 'Review', 'simple-catch' ),
							),
						);
		foreach ( $important_links as $important_link) {
			echo '<p><a target="_blank" href="' . $important_link['link'] .'" >' . esc_attr( $important_link['text'] ) .' </a></p>';
		}
    }
}


/**
  * Custom control for checkbox
  * This class adds a custom-checkbox. The value is stored in the hidden field. This is due to the fact that
  * our theme previously stored 1 and 0 strings as checkbox values
  */
class Simple_Catch_Customize_Checkbox extends WP_Customize_Control {
	public $type = 'simplecatch_custom_checkbox';

	public $name;

	public $descripton;

	public $settings;

	public $default;

	public function render_content() {
		$this->value();
		$this->default;
		?>
		<label>
	        <input type="checkbox" <?php checked( '1', $this->value() ); ?>  /> <?php echo esc_html ( $this->label ); ?>

	        <input type="hidden" <?php $this->link(); ?> value="1" />
        </label>
         <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>
    <?php }
}

/**
  * Custom control for multiple inputs such as font size with font units
  * The Custom control helps to set two different field's value creating by adding a single control
  */
class Simplecatch_Customize_Multiple_Input_Control extends WP_Customize_Control {

    public $html = array();

    public function build_field_html( $key, $setting ) {
        $value = '';
        if ( isset( $this->settings[ $key ] ) )
            $value = $this->settings[ $key ]->value();
        //0 for font size and 1 for font size unit
        if( 0 === $key){
        	$this->html[] = '<div style="display: inline-block; width: 50%;"><input type="number" value="'.$value.'" '.$this->get_link( $key ).' /></div>';
        }else{
        	//Unit dropdown options
        	$options = array(
        		'px'	=> 'px',
        		'pt'	=> 'pt',
        		'em'	=> 'em',
        		'%'		=> '%'
        	);
        	$this->html[] = '<div style="display: inline-block; width: 50%;">
        	<select '.$this->get_link( $key ).'>';
        	foreach($options as $opt){
        		//show the selected options in the dropdown list
        		if($opt === $value){
        			$selected = ' selected="selected" ';
        		}else{
        			$selected = '';
        		}

        		$this->html[] .='<option value="'. $opt .'"'. $selected .'>'.$opt.'</option>';
        	}
        	$this->html[] .='</select>
        	</div>';
        }

    }

    public function render_content() {
        $output =  '<label for="simplecatch_options['.$this->id.']">
						<span class="customize-control-title">'. $this->label .'</span>
					</label>';
        echo $output;
        foreach( $this->settings as $key => $value ) {
            $this->build_field_html( $key, $value );
        }
        echo implode( '', $this->html );
    }
}