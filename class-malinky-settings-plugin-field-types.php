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
	public function malinky_settings_text_field_output( $args )
	{

		$html = '';

		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option( $args['option_name'] );

			$html .= '<input type="text" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset( $options[$args['option_id']]) ? esc_attr( $options[ $args['option_id'] ] ) : $args['option_default'][0] )  . '" placeholder="' . $args['option_placeholder'] . '" />';

		} else {

			$option = get_option( $args['option_name'] );

			$html .= '<input type="text" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . ( ! empty( $option ) ? esc_attr( $option ) : $args['option_default'][0] ) . '" placeholder="' . $args['option_placeholder'] . '" />';

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output a TEXT AREA.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_textarea_field_output( $args )
	{

		$html = '';

		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option( $args['option_name'] );

			$html .= '<textarea rows="4" cols="50" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']"/>' . ( isset( $options[ $args['option_id'] ] ) ? esc_textarea( $options[ $args['option_id'] ] ) : $args['option_default'][0] ) . '</textarea>';

		} else {

			$option = get_option( $args['option_name'] );

			$html .= '<textarea rows="4" cols="50" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '"/>' . ( ! empty( $option ) ? esc_textarea( $option ) : $args['option_default'][0] ) . '</textarea>';

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output an EDITOR.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_editor_field_output( $args )
	{

		$html = '';

		$editor = array(
			'media_buttons' => false,
			'textarea_rows' => 8,
			'quicktags' 	=> false,
			'tinymce' 		=> array(
				'plugins' 						=> 'paste',
				'paste_auto_cleanup_on_paste' 	=> true,
				'paste_remove_styles' 			=> true,
				'paste_text_sticky' 			=> true,
				'paste_text_sticky_default' 	=> true,				
				'paste_retain_style_properties' => "none",
				'paste_strip_class_attributes' 	=> true,
				'theme_advanced_buttons1' 		=> 'bold,italic,|,bullist,numlist,|,link,|,undo,redo,|,|,code',
				'theme_advanced_buttons2' 		=> '',
				'theme_advanced_buttons3' 		=> '',
				'theme_advanced_buttons4' 		=> ''
			),
		);


		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option( $args['option_name'] );

			/*
			 * Also set textarea_name which is used to save the data in this case as it's being stored as a group / array
			 * Otherise wp_editor, $editor_id parameter is used but this can't use the [] in the array.
			 * Then just use option_id as $editor_id so it's unique.
			 * wp_editor( $content, $editor_id, $settings = array() );
			 * http://codex.wordpress.org/Function_Reference/wp_editor
			 */
			$editor['textarea_name'] = $args['option_name'] . '[' . $args['option_id'] . ']';

			$html .= wp_editor( ( isset( $options[ $args['option_id'] ] ) ? esc_textarea( $options[ $args['option_id'] ] ) : $args['option_default'][0] ), $args['option_id'], $editor );

		} else {

			$option = get_option( $args['option_name'] );

			$html .= wp_editor( ( ! empty( $option ) ? esc_textarea( $option ) : $args['option_default'][0] ), $args['option_name'], $editor );

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output a RADIO BUTTON.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_radio_field_output( $args )
	{

		$html = '';

		if ( ! $args['option_field_type_options'] )
			return;

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if ( isset( $args['grouped_option'] ) ) {

				$options = get_option($args['option_name']);

				//Set checked, if value is already set
				if ( isset( $options[ $args['option_id'] ] ) && $options[ $args['option_id'] ] == $value ) {
					$checked = 'checked';
				} else {
					$checked = '';
				}

				//Set default if value is not set
				if ( !isset( $options[ $args['option_id'] ] ) ) {
					if ( !empty( $args['option_default'] ) ) {
						$checked = $args['option_default'][0] == $value ? 'checked' : '';
					}
				}

				$html .= '<input type="radio" id="' . $value . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . esc_attr( $value ) . '"' . $checked . '/><label for="' . $value . '">' . esc_html( $value ) . '</label>';

			} else {

				$option = get_option( $args['option_name'] );

				//Set checked, if value is already set
				if ( ! empty ( $option ) && $option == $value ) {
					$checked = 'checked';
				} else {
					$checked = '';
				}

				//Set default if value is not set
				if ( empty ( $option ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						$checked = $args['option_default'][0] == $value ? 'checked' : '';
					}
				}

				$html .= '<input type="radio" id="' . $value . '" name="' . $args['option_name'] . '" value="' . esc_attr( $value ) . '"' . $checked . '/><label for="' . $value . '">' . esc_html( $value ) . '</label>';

			}

		}

		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output a SINGLE CHECKBOX.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_checkbox_field_output( $args )
	{

		$html = '';

		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option($args['option_name']);

			//Set checked, if value is already set
			if ( isset( $options[ $args['option_id'] ] ) && $options[ $args['option_id'] ] == 1 ) {
				$checked = 'checked';
			} else {
				$checked = '';
			}

			//Set default if value is not set
			if ( !isset( $options[ $args['option_id'] ] ) ) {
				if ( !empty( $args['option_default'] ) ) {
					$checked = $args['option_default'][0] == 1 ? 'checked' : '';
				}
			}

			$html .= '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="1"' . $checked . '/>';

		} else {

			$option = get_option( $args['option_name'] );

			//Set checked, if value is already set
			if ( ! empty ($option) && $option == 1 ) {
				$checked = 'checked';
			} else {
				$checked = '';
			}

			//Set default if value is not set
			if ( empty ($option) ) {
				if ( ! empty( $args['option_default'] ) ) {
					$checked = $args['option_default'][0] == 1 ? 'checked' : '';
				}
			}

			$html .= '<input type="checkbox" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="1"' . $checked . '/>';

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output MULTIPLE CHECKBOXES.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_checkboxes_field_output( $args )
	{

		$html = '';

		if ( ! $args['option_field_type_options'] )
			return;

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if ( isset( $args['grouped_option'] ) ) {

				$options = get_option( $args['option_name'] );

				//Set checked, if value is already set
				if ( isset( $options[ $args['option_id'] ] ) && in_array( $value , $options[ $args['option_id'] ] ) ) {
					$checked = 'checked';
				} else {
					$checked = '';
				}

				//Set default if value is not set
				if ( ! isset( $options[ $args['option_id'] ] ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						foreach ( $args['option_default'] as $key2 => $default_value ) {
							if ( $default_value == $value ) {
								$checked = 'checked';
								break;
							}
						}
					}
				}

				$html .= '<input type="checkbox" id="' . $value . '" name="' . $args['option_name'] . '[' . $args['option_id'] . '][]" value="' . esc_attr( $value ) . '"' . $checked . '/><label for="' . $value . '">' . esc_html( $value ) . '</label>';

			} else {

				//Need to evaluate whether $option exists particular on initial load with setting saved.
				$option = get_option( $args['option_name'] );

				//Set checked, if value is already set
				if ( ! empty ( $option ) && in_array( $value, $option ) ) {
					$checked = 'checked';
				} else {
					$checked = '';
				}

				//Set default if value is not set
				if ( empty ( $option ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						foreach ( $args['option_default'] as $key2 => $default_value ) {
							if ( $default_value == $value ) {
								$checked = 'checked';
								break;
							}
						}
					}
				}

				//Extra !empty check for first page load of plugin where a single optopn multiple check would be blank not an array.
				$html .= '<input type="checkbox" id="' . $value . '" name="' . $args['option_name'] . '[]" value="' . esc_attr( $value ) . '"' . $checked . '/><label for="' . $value . '">' . esc_html( $value ) . '</label>';

			}

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output SELECT OPTIONS.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_select_field_output( $args )
	{

		$html = '';

		if ( ! $args['option_field_type_options'] )
			return;

		if ( isset( $args['grouped_option'] ) ) {
			
			$html .= '<select id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']">';
		
		} else {

			$html .= '<select id="' . $args['option_id'] . '" name="' . $args['option_name'] . '">';

		}

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if ( isset( $args['grouped_option'] ) ) {

				$options = get_option( $args['option_name'] );

				//Set selected, if value is already set
				if ( isset( $options[ $args['option_id'] ] ) && $options[ $args['option_id'] ] == $value ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				//Set default if value is not set
				if ( ! isset( $options[ $args['option_id'] ] ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						$selected = $args['option_default'][0] == $value ? 'selected' : '';
					}
				}

				$html .= '<option id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . esc_attr( $value ) . '"' . $selected . '/>' . esc_html( $value ) . '</option>';

			} else {

				$option = get_option( $args['option_name'] );

				//Set selected, if value is already set
				if ( ! empty ( $option ) && $option == $value ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				//Set default if value is not set
				if ( empty ($option) ) {
					if ( ! empty( $args['option_default'] ) ) {
						$selected = $args['option_default'][0] == $value ? 'selected' : '';
					}
				}

				$html .= '<option id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $value ) . '"' . $selected . '/>' . esc_html( $value ) . '</option>';

			}

		}
		
		$html .= '</select>';
		
		if ($args['option_description'])
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output MULTIPLE SELECT OPTIONS.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_select_multiple_field_output( $args )
	{

		$html = '';

		if ( ! $args['option_field_type_options'] )
			return;

		if ( isset( $args['grouped_option'] ) ) {
			
			$html .= '<select id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . '][]" multiple>';
		
		} else {

			$html .= '<select id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[]" multiple>';

		}

		foreach ( $args['option_field_type_options'] as $key => $value ) {

			if ( isset( $args['grouped_option'] ) ) {

				$options = get_option( $args['option_name'] );

				//Set selected, if value is already set
				if ( isset( $options[ $args['option_id'] ] ) && in_array( $value , $options[ $args['option_id'] ] ) ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				//Set default if value is not set
				if ( ! isset( $options[ $args['option_id'] ] ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						foreach ( $args['option_default'] as $key2 => $default_value ) {
							if ( $default_value == $value ) {
								$selected = 'selected';
								break;
							}
						}
					}
				}

				$html .= '<option id="' . $value . '" name="' . $args['option_name'] . '[' . $args['option_id'] . '][]" value="' . esc_attr( $value ) . '"' . $selected . '/>' . esc_html( $value ) . '</option>';

			} else {

				//Need to evaluate whether $option exists particular on initial load with setting saved.
				$option = get_option( $args['option_name'] );

				//Set selected, if value is already set
				if ( ! empty ( $option ) && in_array( $value, $option ) ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				//Set default if value is not set
				if ( empty ( $option ) ) {
					if ( ! empty( $args['option_default'] ) ) {
						foreach ( $args['option_default'] as $key2 => $default_value ) {
							if ( $default_value == $value ) {
								$selected = 'selected';
								break;
							}
						}
					}
				}

				$html .= '<option id="' . $value . '" name="' . $args['option_name'] . '[]" value="' . esc_attr( $value ) . '"' . $selected . '/>' . esc_html( $value ) . '</option>';

			}

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output a COLOUR INPUT.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_color_field_output( $args )
	{

		$html = '';

		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option( $args['option_name'] );

			$html .= '<input type="color" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset( $options[ $args['option_id'] ] ) ? esc_attr( $options[ $args['option_id'] ] ) : $args['option_default'][0] )  . '" />';

		} else {

			$option = get_option( $args['option_name'] );

			$html .= '<input type="color" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . ( ! empty( $option ) ? esc_attr( $option ) : $args['option_default'][0] ) . '" />';

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}


	/**
	 * Output a DATE INPUT.
	 * If $args['grouped_option']) is true then option_values will be an array.
	 *
	 * @param 	arr $args See malinky_settings_add_fields() method.
	 * @return 	void   
	 */
	public function malinky_settings_date_field_output( $args )
	{

		$html = '';

		if ( isset( $args['grouped_option'] ) ) {

			$options = get_option( $args['option_name'] );

			$html .= '<input type="date" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '[' . $args['option_id'] . ']" value="' . ( isset( $options[ $args['option_id'] ]) ? esc_attr( $options[ $args['option_id'] ] ) : '' )  . '" />';

		} else {

			$option = get_option( $args['option_name'] );

			$html .= '<input type="date" id="' . $args['option_id'] . '" name="' . $args['option_name'] . '" value="' . esc_attr( $option ) . '" min="' . date( 'Y-m-d' )  . '"/>';

		}
		
		if ( $args['option_description'] )
			$html .= '<p><small>' . $args['option_description'] . '</small></p>';

		echo $html;
	
	}		

}