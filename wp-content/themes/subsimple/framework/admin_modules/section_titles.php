<?php //This file ensures each section gets a consistent Section Title
	function subsimple_section_title( $title ) { 
		if ($title != 'subsimple') : ?>
			<div class="section-title">
		    	<span><?php echo $title ?></span>
		    </div>
	<?php endif; 
	} ?>