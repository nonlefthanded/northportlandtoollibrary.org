<?php
			wp_localize_script( 'wp-event-manager-admin-js', 'wp_event_manager_admin_js', array(
			
				'i18n_datepicker_format' => WP_Event_Manager_Date_Time::get_datepicker_format(),
				
				'i18n_timepicker_format' => WP_Event_Manager_Date_Time::get_timepicker_format(),
				
				'i18n_timepicker_step' => WP_Event_Manager_Date_Time::get_timepicker_step(),
				
				) );