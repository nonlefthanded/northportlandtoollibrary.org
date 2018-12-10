<div id="top-bar">
	<div class="container top-bar-inner">
		<div id="contact-icons">
			<?php if (get_theme_mod('subsimple_mailid')) : ?>
			<div class="icon">
				<span class="fa fa-envelope"></span>
				<span class="value"><?php echo get_theme_mod('subsimple_mailid'); ?></span>
			</div>
			<?php endif; ?>
			<?php if (get_theme_mod('subsimple_phone')) : ?>
			<div class="icon">
				<span class="fa fa-phone"></span>
				<span class="value"><?php echo get_theme_mod('subsimple_phone'); ?></span>
			</div>
			<?php endif; ?>
		</div>
		

	</div>
</div>