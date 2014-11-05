<?php
$master_args = array(
	'malinky_settings_page_title' 		=> 'My New Page',
	'malinky_settings_menu_title' 		=> 'My New Page Menu',
	'malinky_settings_capability' 		=> 'manage_options',
	'malinky_settings_sections' => array(
		array(
			'section_title' 	=> 'Section 1',
			'section_intro' 	=> 'Section 1 intro',
		),
		array(
			'section_title' 	=> 'Section 2',
			'section_intro' 	=> 'Section 2 intro',
		)
	),
	'malinky_settings_fields' 	=> array(
		array(
			'option_group_name' 		=> 'social_media',
			'option_title' 				=> 'Twitter Setting',
			'option_field_type' 		=> 'textarea_field',
			'option_field_type_options' => array(
			),
			'option_section' 			=> 'Section 1',
			'option_validation' 		=> array(
				'required'
			)
		),
		array(
			'option_group_name' 		=> 'social_media',
			'option_title' 				=> 'Facebook Setting',
			'option_field_type' 		=> 'date_field',
			'option_field_type_options' => array(
				'Yes',
				'No'
			),
			'option_section' 			=> 'Section 1',
			'option_validation' 		=> array(
				'required'
			)
		),
		array(
			'option_group_name' 		=> '',
			'option_title' 				=> 'Random One Off Setting',
			'option_field_type' 		=> 'date_field',
			'option_field_type_options' => array(
				'Yes',
				'No'
			),
			'option_section' 			=> 'Section 2',
			'option_validation' 		=> array(
				'required'
			)
		),
		array(
			'option_group_name' 		=> '',
			'option_title' 				=> 'Random Two Off Setting',
			'option_field_type' 		=> 'text_field',
			'option_field_type_options' => array(
			),			
			'option_section' 			=> 'Section 1',
			'option_validation' 		=> array(
				'email'
			)
		)		
	)
);