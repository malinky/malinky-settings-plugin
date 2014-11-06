<?php

class Malinky_Settings_Plugin_Field_Types
{

	/**
	 * Output a TEXT INPUT.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_text_field_output($args)
	{

		if (isset($args['grouped_option'])) {

			$options = get_option($args['option_name']);

			echo '<input type="text" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset($options[$args['option_id']]) ? esc_attr( $options[$args['option_id']] ) : '' )  . '" />';

		} else {

			$option = get_option($args['option_name']);

			echo '<input type="text" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $option ) . '" />';

		}
		
	
	}


	/**
	 * Output a TEXT AREA.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_textarea_field_output($args)
	{

		if (isset($args['grouped_option'])) {

			$options = get_option($args['option_name']);

			echo '<textarea rows="4" cols="50" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']"/>' . ( isset($options[$args['option_id']]) ? esc_attr( $options[$args['option_id']] ) : '' ) . '</textarea>';

		} else {

			$option = get_option($args['option_name']);

			echo '<textarea rows="4" cols="50" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '"/>' . esc_attr( $option ) . '</textarea>';

		}
		
	
	}	


	/**
	 * Output a RADIO BUTTON.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_radio_field_output($args)
	{

		if ( ! $args['option_field_type_options'] )
			return;

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if (isset($args['grouped_option'])) {

				$options = get_option($args['option_name']);

				echo '<input type="radio" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . esc_attr( $value ) . '"' . ( isset($options[$args['option_id']]) && $options[$args['option_id']] == $value ? checked( $options[$args['option_id']], $value, false ) : '' ) . '/>' . esc_html( $value );

			} else {

				$option = get_option($args['option_name']);

				echo '<input type="radio" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $value ) . '"' . checked( $option, $value, false ) . '/>' . esc_html( $value );

			}

		}
	
	}


	/**
	 * Output a SINGLE CHECKBOX.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_checkbox_field_output($args)
	{

		if (isset($args['grouped_option'])) {

			$options = get_option($args['option_name']);

			echo '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="1"' . ( isset( $options[$args['option_id']] ) ? checked( $options[$args['option_id']], 1, false ) : '' ) . '/>';

		} else {

			$option = get_option($args['option_name']);

			echo '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="1"' . checked( $option, 1, false ) . '/>';

		}
	
	}


	/**
	 * Output MULITPLE CHECKBOXES.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_checkboxes_field_output($args)
	{

		if ( ! $args['option_field_type_options'] )
			return;

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if (isset($args['grouped_option'])) {

				$options = get_option($args['option_name']);

				echo '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . '][]" value="' . esc_attr( $value ) . '"' . ( isset( $options[$args['option_id']] ) && in_array( $value , $options[$args['option_id']] ) ? 'checked' : '' ) . '/>' . esc_html( $value );

			} else {

				//Need to evaluate whether $option exists particular on initial load with setting saved.
				$option = get_option($args['option_name']);

				//Extra !empty check for first page load of plugin where a single optopn multiple check would be blank not an array.
				echo '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[]" value="' . esc_attr( $value ) . '"' . ( !empty( $option ) && in_array( $value, $option ) ? 'checked' : '' ) . '/>' . esc_html( $value );

			}

		}
	
	}


	/**
	 * Output SELECT OPTIONS.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_select_field_output($args)
	{

		if ( ! $args['option_field_type_options'] )
			return;

		if (isset($args['grouped_option'])) {
			
			echo '<select name="' . $args['option_name'] . '[' . $args['option_id'] . ']">';
		
		} else {

			echo '<select name="' . $args['option_name'] . '">';

		}

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if (isset($args['grouped_option'])) {

				$options = get_option($args['option_name']);

				echo '<option id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . esc_attr( $value ) . '"' . ( isset($options[$args['option_id']]) && $options[$args['option_id']] == $value ? 'selected' : '' ) . '/>' . esc_html( $value ) . '</option>';

			} else {

				$option = get_option($args['option_name']);

				echo '<option id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $value ) . '"' . ( $option == $value ? 'selected' : '' ) . '/>' . esc_html( $value ) . '</option>';

			}

		}
		
		echo '</select>';
	
	}	


	/**
	 * Output a COLOUR INPUT.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_color_field_output($args)
	{

		if (isset($args['grouped_option'])) {

			$options = get_option($args['option_name']);

			echo '<input type="color" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset($options[$args['option_id']]) ? esc_attr( $options[$args['option_id']] ) : '' )  . '" />';

		} else {

			$option = get_option($args['option_name']);

			echo '<input type="color" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $option ) . '" />';

		}
		
	
	}	


	/**
	 * Output a DATE INPUT.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_date_field_output($args)
	{

		if (isset($args['grouped_option'])) {

			$options = get_option($args['option_name']);

			echo '<input type="date" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset($options[$args['option_id']]) ? esc_attr( $options[$args['option_id']] ) : '' )  . '" />';

		} else {

			$option = get_option($args['option_name']);

			echo '<input type="date" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $option ) . '" min="' . date('Y-m-d')  . '"/>';

		}
		
	
	}			

}