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
	public function malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message )
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
	public function malinky_settings_validation_required( $input, $saved_input, $option_name, $option_title )
	{	
		
		if ( empty( $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_required', 'is required.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

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
	public function malinky_settings_validation_required_checkbox( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! $input ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_required_checkbox', 'is required.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

			return $saved_input;

		}

		return $input;

	}	


	/**
	 * Text field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_text( $input, $saved_input, $option_name, $option_title )
	{

		//Returns sanitized text. Used in instances where accepted data can be quite liberal.
		return sanitize_text_field( $input );

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
	public function malinky_settings_validation_letters( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! preg_match( '/^[A-Za-z]*$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_letters', 'accepts letters only.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

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
	public function malinky_settings_validation_letters_numbers( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! preg_match( '/^[A-Za-z0-9]*$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_letters_numbers', 'accepts letters and numbers only.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

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
	public function malinky_settings_validation_numbers( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! preg_match( '/^[0-9]*$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_numbers', 'accepts numbers only.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

			return $saved_input;

		}

		return $input;

	}


	/**
	 * Phone field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_phone( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! preg_match( '/^[0-9 ]*$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_phone', 'accepts numbers and spaces only.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

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
	public function malinky_settings_validation_email( $input, $saved_input, $option_name, $option_title )
	{

		//Strip dodgy charcters then validate against sanitize_email.
		if ( $input && ! is_email( sanitize_email( $input ) ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_email', 'a valid email address is required.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

			return $saved_input;

		}

		//Return sanitized email for display in form and saving.
		return sanitize_email( $input );

	}	


	/**
	 * URL field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_url( $input, $saved_input, $option_name, $option_title )
	{

		if ( $input && ! preg_match( '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_url', 'a valid URL is required.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

			return $saved_input;

		}

		return $input;

	}


	/**
	 * Google Analytics UA field.
	 *
	 * @param 	str $input
	 * @param 	str $saved_input
	 * @param 	str $option_name
	 * @param 	str $option_title
	 * @return 	str   
	 */
	public function malinky_settings_validation_googleua( $input, $saved_input, $option_name, $option_title )
	{

		if ( ! preg_match( '/^(UA-)([0-9]{8})-([0-9]{1})$/', $input ) ) {

			$error_code 	= $option_name . '_' . str_replace( 'malinky_settings_validation_', '', __FUNCTION__ ) . '_error';
			$error_message 	= apply_filters( 'malinky_settings_validation_error_message_googleua', 'a valid Google Analyitcs UA tracking number is required.', $error_code );
			$this->malinky_settings_add_settings_error( $option_name, $option_title, $error_code, $error_message );

			return $saved_input;

		}

		return $input;

	}			

}