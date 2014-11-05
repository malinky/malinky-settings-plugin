<?php

class Malinky_Settings_Plugin_Validation
{

	/**
	 * Add settings error for the validated input.
	 *
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @param 	str $error_code
	 * @param 	str $error_message
	 * @return 	void
	 */
	public function malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message)
	{
		
		return add_settings_error(
			$option_name,
			$error_code,
			$option_title . ' ' . $error_message,
			'error'
		);

	}

	/**
	 * Required field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_required($input, $saved_input, $option_name, $option_title)
	{	

		if ( empty($input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_required', 'is required.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}


	/**
	 * Required checkbox field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_required_checkbox($input, $saved_input, $option_name, $option_title)
	{

		if ( !($input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_required', 'is required.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}	


	/**
	 * Letters field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_letters($input, $saved_input, $option_name, $option_title)
	{

		if ( ! preg_match('/^[A-Za-z]*$/', $input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_letters', 'accepts letters only.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}


	/**
	 * Letters and numbers field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_letters_numbers($input, $saved_input, $option_name, $option_title)
	{

		if ( ! preg_match('/^[A-Za-z0-9]*$/', $input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_letters_numbers', 'accepts letters and numbers only.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}	


	/**
	 * Numbers field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_numbers($input, $saved_input, $option_name, $option_title)
	{

		if ( ! preg_match('/^[0-9]*$/', $input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_numbers', 'accepts numbers only.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}


	/**
	 * Email field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_email($input, $saved_input, $option_name, $option_title)
	{

		if ( ! preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $input) ) {

			$error_code 	= $option_name . '_' . str_replace('malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters('malinky_settings_validation_error_message_email', 'a valid email address is required.', $error_code);
			$this->malinky_settings_add_settings_error($option_name, $option_title, $error_code, $error_message);

			return $saved_input;

		}

		return $input;

	}	

}