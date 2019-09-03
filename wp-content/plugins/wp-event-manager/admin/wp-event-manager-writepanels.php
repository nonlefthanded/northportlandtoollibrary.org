<?php
				$datepicker_date_format = WP_Event_Manager_Date_Time::get_datepicker_format();
				$php_date_format = WP_Event_Manager_Date_Time::get_view_date_format_from_datepicker_date_format($datepicker_date_format );
				$expiry_date = date($php_date_format,strtotime($expiry_date));
			
			}
			unset($fields['_organizer_logo']);
					$field['value'] = array_shift($field['value']);
	 * input_wp_editor function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 * @since 2.8
	 */
	public static function input_wp_editor( $key, $field ) {
		global $thepostid;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $thepostid, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
			}?>
			<p class="form-field">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			
	
			<?php
			wp_editor( $field['value'], $name, array("media_buttons" => false) );
			?>
			</p>
			<?php
		}
				$php_date_format = WP_Event_Manager_Date_Time::get_view_date_format_from_datepicker_date_format($datepicker_date_format );
				$date = date($php_date_format,strtotime($date));
				$field['value'] = $date;
	 * input_time function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public static function input_time( $key, $field ) {
		global $thepostid;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $thepostid, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>
		<p class="form-field">
			<?php
		}
		 * input_timezone function.
		 *
		 * @param mixed $key
		 * @param mixed $field
		 */
		public static function input_timezone( $key, $field ) {
			global $thepostid;
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = get_post_meta( $thepostid, $key, true );
			}
			if ( ! empty( $field['name'] ) ) {
				$name = $field['name'];
			} else {
				$name = $key;
			}
			?>
				<p class="form-field">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
					 <select name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo isset( $field['id'] ) ? esc_attr( $field['id'] ) :  esc_attr( $key ); ?>" class="input-select <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : $key ); ?>">
		 			<?php 
		 			$value = isset($field['value']) ? $field['value'] : $field['default'];	
		 			echo WP_Event_Manager_Date_Time::wp_event_manager_timezone_choice($value);
		 			?>
		 			</select>
				</p>
		<?php
		}
		 * input_number function.
		 *
		 * @param mixed $key
		 * @param mixed $field
		 */
		public static function input_number( $key, $field ) {
			global $thepostid;
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = get_post_meta( $thepostid, $key, true );
			}
			if ( ! empty( $field['name'] ) ) {
				$name = $field['name'];
			} else {
				$name = $key;
			}
			?>
				<p class="form-field">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
					<input type="number" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
				</p>
				<?php
			}
			 * input_button function.
			 *
			 * @param mixed $key
			 * @param mixed $field
			 */
			public static function input_button( $key, $field ) {
				global $thepostid;
				if ( ! isset( $field['value'] ) ) {
					$field['value'] = $field['placeholder'];
				}
			
				if ( ! empty( $field['name'] ) ) {
					$name = $field['name'];
				} else {
					$name = $key;
				}
				?>
						<p class="form-field">
							<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
							<input type="button" class="button button-small" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
						</p>
						<?php
		}	
		// for eg. if each event selected then Berlin timezone will be different then current site timezone.
		if( WP_Event_Manager_Date_Time::get_event_manager_timezone_setting() == 'each_event'  )
			$current_timestamp = WP_Event_Manager_Date_Time::current_timestamp_from_event_timezone( $event_timezone );
		else
			$current_timestamp = current_time( 'timestamp' ); // If site wise timezone selected