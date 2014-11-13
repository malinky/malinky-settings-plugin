<?php
/**
 * Plugin Name: Wordpress Settings and Options
 * Plugin URI: https://github.com/malinky/wordpress-settings-and-options
 * Description: Wordpress plugin using the Settings API to create new options pages.
 * Version: 1.0
 * Author: Craig Ramsay
 * Author URI: https://github.com/malinky
 * License: A "Slug" license name e.g. GPL2
 */


/* ------------------------------------------------------------------------ *
 * Properties
 * __construct
 * Run and Setup
 * Add Page Methods
 * Register Setting Methods 
 * Add Section Methods
 * Add Field Methods
 * Validation Methods
 * Other Methods
 * ------------------------------------------------------------------------ */


class Malinky_Settings_Plugin
{
    
    /* ------------------------------------------------------------------------ *
     * Properties
     * ------------------------------------------------------------------------ */

    /**
     * Prefix option names stored in db column option_name, avoids duplicates.
     *
     * @var str
     */
    private static $option_name_prefix = 1;

    /**
     * Settings page title.
     *
     * @var str
     */
    private $page_title;

    /**
     * Settings menu title.
     *
     * @var str
     */    
    private $menu_title;

    /**
     * Slug of settings page. Used as $menu_slug, $page and $option_group
     *
     * @var str
     */
    public $menu_slug;

    /**
     * Slug of parent page to position settings page in the correct admin menu.
     *
     * @var str
     */    
    public $page_parent_slug;

    /**
     * If settings page is to be displayed tabbed with other menu pages then this will contain the menu_slug(s) of all tabs.
     *
     * @var array
     */        
    public $page_tabs = array();

    /**
     * Capability to edit settings (manage_options).
     *
     * @var str
     */    
    private $capability;

    /**
     * Section titles and intros.
     *
     * @var arr
     */    
    private $sections = array();

    /**
     * Field data.
     *
     * @var arr
     */    
    private $fields = array();

    /**
     * Option names stored in db column option_name
     *
     * @var arr
     */    
    public $option_names = array();

    /**
     * Output functions to access all options.
     *
     * @var str
     */        
    public $option_functions;

    /**
     * Validator object.
     *
     * @var obj
     */        
    public $validator;

    /**
     * Field types object.
     *
     * @var obj
     */        
    public $field_types;





    /* ------------------------------------------------------------------------ *
     * __construct
     * ------------------------------------------------------------------------ */

    public function __construct( $master_args )
    {

        //No Trailing Slash.
        if ( ! defined( 'MALINKY_SETTINGS_PLUGIN_DIR' ) )
            define( 'MALINKY_SETTINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            
        //No Trailing Slash.
        if ( ! defined( 'MALINKY_SETTINGS_PLUGIN_URL' ) )
            define( 'MALINKY_SETTINGS_PLUGIN_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ) ) );

        //include('malinky-settings-plugin-setup.php');
        include_once( 'class-malinky-settings-plugin-validation.php' );
        require_once( 'class-malinky-settings-plugin-field-types.php' );

        //Instantiate validation object.
        $this->validator    = new Malinky_Settings_Plugin_Validation();

        //Instantiate field object.
        $this->field_types  = new Malinky_Settings_Plugin_Field_Types();        

        //Lets go!
        $this->malinky_settings_run( $master_args );

    }





    /* ------------------------------------------------------------------------ *
     * Run and Setup
     * ------------------------------------------------------------------------ */

    /**
     * Set up properties, add setting page and create setting fields.
     *
     * @param     arr $master_args Array of arguments for each settings page.
     * @return    void    
     */
    public function malinky_settings_run( $master_args )
    {

        //Set the option_name(s) to be saved and prefix them.
        $master_args = $this->malinky_settings_set_options( $master_args );

        //Set up properties.
        $this->page_title               = $master_args['malinky_settings_page_title'];
        $this->menu_title               = $master_args['malinky_settings_menu_title'];
        $this->menu_slug                = Malinky_Settings_Plugin::malinky_settings_set_slug( $master_args['malinky_settings_page_title'], '-' );
        $this->page_parent_slug         = $master_args['malinky_settings_page_parent_slug'];
        $this->page_tabs                = $master_args['malinky_settings_page_tabs'];
        $this->page_title               = $master_args['malinky_settings_page_title'];
        $this->capability               = $master_args['malinky_settings_capability'];
        $this->sections                 = $master_args['malinky_settings_sections'];
        $this->fields                   = $master_args['malinky_settings_fields'];

        $this->option_names             = $this->malinky_settings_get_option_names( $master_args['malinky_settings_fields'] );

        $this->all_option_names         = $this->malinky_settings_get_all_option_names( $master_args['malinky_settings_fields'] );
        $this->get_option_wp_functions  = $this->malinky_settings_get_option_functions( $this->all_option_names );

        //Add page action.
        add_action( 'admin_menu', array( $this, 'malinky_settings_add_page' ) );

        //Set up sections and fields action.
        add_action( 'admin_init', array( $this, 'malinky_settings_section_field_setup' ) );

        //Add empty values to db options table. This stops double validation error on first load. See Notes section in link below.
        //http://codex.wordpress.org/Function_Reference/register_setting
        foreach ( $this->all_option_names as $option_name => $option_value ) {

            if ( is_array( $option_value ) ) {

                add_option( $option_name, $option_value );

            } else {

                add_option( $option_name, '' );

            }

        }


    }


    /**
     * Add option_name, option_id and grouped_option (bool) to each malinky_settings_fields in $master_args.
     * option_name(s) are also prefixed to ensure they are always unique in the db.
     *
     * @param     arr $master_args Array of arguments for each settings page
     * @return    arr    
     */
    public function malinky_settings_set_options( $master_args )
    {

        $unique_option_names     = array();
        $prefixed_option_names   = array();

        //Returns either unique option_group_name or option_title in slug format.
        $unique_option_names = $this->malinky_settings_get_option_names( $master_args['malinky_settings_fields'] );

        foreach ( $unique_option_names as $key => $option_name ) {
            
            //Set $option_name as the key.
            //And prefixed (with self::$option_name_prefix) padded $option_name as value.
            $prefixed_option_names[ $option_name ] = '_' . zeroise( self::$option_name_prefix, 6 ) . '_' . $option_name;
            self::$option_name_prefix++;

        }

        //Add option_name, option_id and grouped_option (bool) to each malinky_settings_fields in $master_args.
        //option_name. Prefixed, slugged and as saved in db column option_name.
        //option_id. Non prefixed, slugged option_title from malinky_settings_fields.
        //grouped_option. Boolean as to whether options are saved as singles or arrays.
        foreach ( $master_args['malinky_settings_fields'] as $key => $value ) {
            
            if ( ! empty ( $master_args['malinky_settings_fields'][ $key ]['option_group_name'] ) ) {

                $master_args['malinky_settings_fields'][ $key ]['option_name'] = $prefixed_option_names[Malinky_Settings_Plugin::malinky_settings_set_slug( $master_args['malinky_settings_fields'][ $key ]['option_group_name'] )];

                $master_args['malinky_settings_fields'][ $key ]['option_id'] = Malinky_Settings_Plugin::malinky_settings_set_slug( $master_args['malinky_settings_fields'][ $key ]['option_title'] );

                $master_args['malinky_settings_fields'][ $key ]['grouped_option'] = true;
                
            } else {
                
                $master_args['malinky_settings_fields'][ $key ]['option_name'] = $prefixed_option_names[Malinky_Settings_Plugin::malinky_settings_set_slug( $master_args['malinky_settings_fields'][ $key ]['option_title'] )];

                $master_args['malinky_settings_fields'][ $key ]['option_id'] = Malinky_Settings_Plugin::malinky_settings_set_slug( $master_args['malinky_settings_fields'][ $key ]['option_title'] );

                $master_args['malinky_settings_fields'][ $key ]['grouped_option'] = null;                

            }

        }

        return $master_args;

    }





    /* ------------------------------------------------------------------------ *
     * Add Page Methods
     * ------------------------------------------------------------------------ */

    /**
     * Add an options page. Called from admin_menu action in __construct.
     *
     * @return void   
     */
    public function malinky_settings_add_page()
    {

        //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
        //http://codex.wordpress.org/Function_Reference/add_submenu_page
        add_submenu_page(

            $this->page_parent_slug,
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            array( $this, 'malinky_settings_add_page_output_callback' )

        );

    }


    /**
     * Callback which outputs the option pages content.
     *
     * @return void   
     */
    public function malinky_settings_add_page_output_callback()
    {

        if ( ! current_user_can( 'manage_options' ) )
            wp_die( 'You do not have sufficient permissions to access this page.' );

        if ( $this->page_tabs ) {

            echo '<h2 class="nav-tab-wrapper">';

            foreach ( $this->page_tabs as $tab_slug => $tab_title ) {

                echo '<a href=' . $this->page_parent_slug . '?page=' . $tab_slug . ' class="nav-tab' . ( $_GET[ 'page' ] == $tab_slug ? ' nav-tab-active' : '' ) . '">' . $tab_title . '</a>';

            }

            echo '</h2>';

        }  else {

            echo '<h2>' . esc_html( $this->page_title ) . '</h2>';

        } ?>

        <div class="wrap">
            <form action="options.php" method="post">
                <?php do_action( 'malinky_settings_page_top' ); ?>
                <?php settings_fields( $this->menu_slug ); ?>
                <?php do_settings_sections( $this->menu_slug ); ?>
                <?php submit_button(); ?>
                <?php do_action( 'malinky_settings_page_bottom' ); ?>
            </form>
        </div>

    <?php }





    /* ------------------------------------------------------------------------ *
     * General Section, Field Setup
     * ------------------------------------------------------------------------ */

    /**
     * Set up sections, fields and register settings. Called from admin_init action in __construct.
     * Methods called from this method will call the following WP functions.
     *
     * register_setting()
     * add_settings_section()
     * add_settings_field()
     *
     * And their various callbacks if applicable.
     *
     * @return void   
     */
    public function malinky_settings_section_field_setup()
    {

        $this->malinky_settings_register_settings( $this->option_names );
        $this->malinky_settings_add_sections( $this->sections );
        $this->malinky_settings_add_fields( $this->fields );

    }





    /* ------------------------------------------------------------------------ *
     * Register Setting Methods
     * ------------------------------------------------------------------------ */

    /**
     * Register a setting.
     *
     * Existing $option_group (option pages) are general, discussion, media, reading, writing
     * The $option_group is the same as $menu_slug, $page.
     *
     * @param     arr $option_names To be stored in db column option_name.
     * @return    void    
     */
    public function malinky_settings_register_settings( $option_names )
    {

        if ( ! $option_names )
            return; //set up error

        if ( ! is_array( $option_names ) )
            return; //set up error

        foreach ( $option_names as $key => $option_name ) {

            //register_setting( $option_group, $option_name, $sanitize_callback );
            //http://codex.wordpress.org/Function_Reference/register_setting
            register_setting(

                $this->menu_slug,                
                $option_name,
                array( $this, 'malinky_settings_validation_callback' )

            );

        }

    }  





    /* ------------------------------------------------------------------------ *
     * Add Section Methods
     * ------------------------------------------------------------------------ */

    /**
     * Add setting sections.
     *
     * @param     arr $sections Array of sections (section_title, section_intro)
     * @return    void   
     */
    public function malinky_settings_add_sections( $sections )
    {

        if ( ! $sections )
            return; //set up error

        if ( ! is_array( $sections ) )
            return; //set up error

        foreach ( $this->sections as $key => $value ) {

            //add_settings_section( $id, $title, $callback, $page );
            //http://codex.wordpress.org/Function_Reference/add_settings_section
            add_settings_section(

                Malinky_Settings_Plugin::malinky_settings_set_slug( $sections[ $key ]['section_title'] ),
                $sections[ $key ]['section_title'],
                array( $this, 'malinky_settings_add_sections_output_callback' ),
                $this->menu_slug

            );

        }

    }


    /**
     * Callback which outputs the setting sections.
     *
     * The $add_settings_section_args parameter contains the args passed to add_settings_section.
     * $add_settings_section_args is needed because the add_settings_section() callback can't be passed any parameters.
     *
     * @param     arr $add_settings_section_args
     * @return    void   
     */
    public function malinky_settings_add_sections_output_callback( $add_settings_section_args )
    {

        //Looping through all sections created from $master_args.
        foreach ( $this->sections as $key => $value ) {

            //If a section title matches title from $add_settings_section_args then output the section intro.
            if ( $this->sections[ $key ]['section_title'] == $add_settings_section_args['title'] ) {

                echo apply_filters( 'malinky_settings_section_intro_' . Malinky_Settings_Plugin::malinky_settings_set_slug( $this->sections[ $key ]['section_title']), $this->sections[ $key ]['section_intro'] );

            }

        }

    }





    /* ------------------------------------------------------------------------ *
     * Add Field Methods
     * ------------------------------------------------------------------------ */

    /**
     * Add setting fields.
     *
     * @param     arr $fields Array of fields [option_group_name],
     *                                        option_title,
     *                                        option_field_type,
     *                                        option_section,
     *                                        option_validation
     *                                        option_name,
     *                                        option_id
     *                                        grouped_option
     * @return    void
     */
    public function malinky_settings_add_fields( $fields )
    {

        if ( ! $fields )
            return; //set up error

        if ( ! is_array( $fields ) )
            return; //set up error

        foreach ( $fields as $key => $value ) {

            $fields[ $key ]['label_for'] = $fields[ $key ]['option_id'];

            //add_settings_field( $id, $title, $callback, $page, $section, $args );
            //http://codex.wordpress.org/Function_Reference/add_settings_field
            add_settings_field(

                $fields[ $key ]['option_id'],
                $fields[ $key ]['option_title'],
                array( $this, 'malinky_settings_add_fields_output_callback' ),
                $this->menu_slug,
                Malinky_Settings_Plugin::malinky_settings_set_slug( $fields[ $key ]['option_section'] ),
                $fields[ $key ]

            );

        }

    }


    /**
     * Callback which outputs the setting fields.
     *
     * The $args parameter is from the callback $args set in add_settings_field().
     *
     * @param     arr $args See malinky_settings_add_fields() method.
     * @return    void   
     */
    public function malinky_settings_add_fields_output_callback( $args )
    {
        
        switch ( $args['option_field_type']) {

            case 'text_field':
                $this->field_types->malinky_settings_text_field_output( $args );
                break;
            case 'textarea_field':
                $this->field_types->malinky_settings_textarea_field_output( $args );
                break;
            case 'radio_field':
                $this->field_types->malinky_settings_radio_field_output( $args );
                break;
            case 'checkbox_field':
                $this->field_types->malinky_settings_checkbox_field_output( $args );
                break;                
            case 'checkboxes_field':
                $this->field_types->malinky_settings_checkboxes_field_output( $args );
                break;
            case 'select_field':
                $this->field_types->malinky_settings_select_field_output( $args );
                break;
            case 'select_multiple_field':
                $this->field_types->malinky_settings_select_multiple_field_output( $args );
                break;                
            case 'color_field':
                $this->field_types->malinky_settings_color_field_output( $args );
                break;    
            case 'colour_field':
                $this->field_types->malinky_settings_color_field_output( $args );
                break;    
            case 'date_field':
                $this->field_types->malinky_settings_date_field_output( $args );
                break;                                                                

        }
        

    }





    /* ------------------------------------------------------------------------ *
     * Validation Methods
     * ------------------------------------------------------------------------ */

    /**
     * Input is either a string if single saved option or array if grouped saved option.
     *
     * $option_name is used to find the applicable validation from $master_args
     *
     * @param 	str|arr $input String or Array of form values
     * @return 	str|arr   
     */
    public function malinky_settings_validation_callback( $input )
    {

        //Get the option_name as saved in DB that is being validated.
        //bug in wp-admin/includes/plugin.php add_filter as should pass 10, 2
        //to make option_name available in this callback as a parameter.
        $option_name = str_replace( 'sanitize_option_', '', current_filter() );

        //echo $option_name; exit;
        //Get the option_value as saved in DB. Could be an array.
        $saved_value = get_option( $option_name );

        //Check correct inputs are set when dealing with an array of inputs. Adds missing checkboxes and radio buttons.
        $input = $this->malinky_settings_input_whitelist( $option_name, $this->all_option_names, $input );

        //---------------------------------------------------------------------
        //If working with a single saved option.
        //Also an empty single saved option of multiple checkboxes.
        //---------------------------------------------------------------------

        if ( !is_array ( $input ) ) {

            foreach ( $this->fields as $key => $value ) {

                //Find the correct option in $master_args by comparing option_name key with $option_name from current_filter().
                if ( $this->fields[ $key ]['option_name'] == $option_name ) {

                    $option_validation = array();
                    $option_validation = $this->fields[ $key ]['option_validation'];

                    //Get validation methods and call one by one.
                    //If successful each will pass the current $input back otherwise the old $saved_value.
                    foreach ( $option_validation as $key2 => $validation_method ) {

                        //If using a second validation_method the newly validated input is used.
                        $input = call_user_func(
                            array( $this->validator, 'malinky_settings_validation_' . $validation_method ),
                            $input,
                            $saved_value,
                            $this->fields[ $key ]['option_id'],
                            $this->fields[ $key ]['option_title']
                        );

                    }

                }

            }

            return $input;

        }

        //---------------------------------------------------------------------
        //If working with a grouped saved option.
        //$option_id from the $input will be the same as option_id in $master_args.
        //This $option_id represents option_name for the sake of validation.
        //BUG HERE AS A SINGLE SAVED OPTION OF MULTIPLE CHECKBOXES WILL ENTER THIS VALIDATION AS IT IS AN ARRAY.
        //HOWEVER $OPTION_ID WILL BE SET NUMERICALLY NOT AS option_id. THEREFORE === BELOW STOPS VALIDATION IN THIS CASE.
        //---------------------------------------------------------------------

        foreach ( $input as $option_id => $option_value ) {

            foreach ( $this->fields as $key => $value ) {
                
                //Find the correct option by comparing option_name key in $master_args with $option_name from current_filter()
                //and option_id key in $master_args with $option_id from $input.
                if ( ( $this->fields[ $key ]['option_name'] == $option_name) && ( $this->fields[ $key ]['option_id'] === $option_id ) ) {
                    
                    $option_validation = array();
                    $option_validation = $this->fields[ $key ]['option_validation'];

                    //Get validation methods and call one by one.
                    //If successful each will pass the current $input back otherwise the old $saved_value.
                    foreach ( $option_validation as $key2 => $validation_method ) {

                        //If using a second validation_method the newly validated input is used.
                        $input[ $option_id ] = call_user_func(
                            array( $this->validator, 'malinky_settings_validation_' . $validation_method ),
                            $input[ $option_id ],
                            $saved_value[ $option_id ],
                            $option_id,
                            $this->fields[ $key ]['option_title']
                        );

                    }

                }

            }

        }

        return $input;

    }





    /* ------------------------------------------------------------------------ *
     * Other Methods
     * ------------------------------------------------------------------------ */

    /**
     * Get option_names that are saved in db column option_name.
     * If option_name key of $fields is set use this. Will always be the case after object is instantiated.
     * Otherwise check for an option_group_name, failing this use a slugged option_title from $fields.
     *
     * @param     arr $fields See malinky_settings_add_fields() method.
     * @return    arr Slugged prefixed option_name(s).
     */
    public function malinky_settings_get_option_names( $fields )
    {

        $option_names = array();

        foreach ( $fields as $key => $value ) {

            if ( isset( $fields[ $key ]['option_name'] ) ) {

                $option_names[] = $fields[ $key ]['option_name'];

            } elseif ( ! empty ( $fields[ $key ]['option_group_name'] ) ) {

                //If option_group_name has been set we don't need this again
                //if ( !in_array( Malinky_Settings_Plugin::malinky_settings_set_slug( $fields[ $key ]['option_group_name']), $option_names ) )
                $option_names[] = Malinky_Settings_Plugin::malinky_settings_set_slug( $fields[ $key ]['option_group_name'] );

            } else {

                //Remember could have two values the same of to option_titles are the same for two single saved options.
                $option_names[] = Malinky_Settings_Plugin::malinky_settings_set_slug( $fields[ $key ]['option_title'] );

            }

        }

        $option_names = array_unique( $option_names );
        
        return array_values( $option_names );

    }


    /**
     * Get all option_name(s) from db column option_name and those stored in db column option_value
     * If grouped saved option use option_name as key and option_id as an array of values.
     *
     * @param 	arr fields See malinky_settings_add_fields() method.
     * @return 	arr   
     */
    public function malinky_settings_get_all_option_names( $fields )
    {

        $option_names = array();

        foreach ( $fields as $key => $value ) {

            if ( ! empty ( $fields[ $key ]['option_group_name'] ) ) {

                $option_names[ $fields[ $key ]['option_name'] ][] = $fields[ $key ]['option_id'];

            } else {

                $option_names[ $fields[ $key ]['option_name'] ] = $fields[ $key ]['option_id'];

            }

        }
        
        return $option_names;

    }    


    /**
     * Output functions that can be used in templates to access any option from settings page.
     *
     * @param     arr $all_option_names
     * @return    str
     */
    public function malinky_settings_get_option_functions( $all_option_names )
    {

        $wp_functions = '';

        foreach ( $all_option_names as $option_name => $option_id ) {

            if ( is_array( $all_option_names[ $option_name] ) ) {

                foreach ( $option_id as $key => $option_name_2 ) {

                    $wp_functions .= 'malinky_settings_get_option(\'' . $option_name . '-' . $option_name_2 . '\');<br />';

                }

            } else {

                $wp_functions .= 'malinky_settings_get_option(\'' . $option_name . '\');<br />';
                
            }

        }

        return $wp_functions;

    }


    /**
     * Get single saved option or option from array of a grouped saved option.
     * $option_name will be generated from malinky_settings_get_option_functions.
     * $option_name will either contain option_name or 'option_name - option_id'.
     *
     * @param     str $option_name
     * @return    str  
     */
    public function malinky_settings_get_option( $option_name )
    {

        //Option stored as an array.
        if ( strpos( $option_name, '-' ) ) {

            $option_name = explode( '-', $option_name );

            $db_option_name = get_option( $option_name[0] );
            
            if ( $db_option_name) {
            
                if ( !array_key_exists( $option_name[1], $db_option_name ) )
                    return;

            }

            return $db_option_name[ $option_name[1] ];

        //Single option.
        } else {

            return get_option( $option_name );

        }

    }


    /**
     * Check all inputs exist in an array of inputs from a grouped saved option prior to validation.
     * Used to add empty checkboxes and radio buttons as they aren't passed in $_POST.
     * When working with a group saved option this causes a problem as they are missing from $input.
     * Not a problem for single saved options as empty checkboxes and radio buttons don't exist in $_POST at all and are set as a NULL $input.
     * Also remove any additional inputs that may have been maliciously added.
     *
     * @param     str       $option_name
     * @param     arr       $all_option_names
     * @param     arr|str   $option_name
     * @return    arr  
     */
    public function malinky_settings_input_whitelist( $option_name, $all_option_names, $input )
    {

        $option_ids = $all_option_names[ $option_name ];

        //If $option_ids is an array then we have grouped saved options.
        //Not just an option_name saved as an array as this could refer to a single option using multiple checkboxes.
        if ( is_array( $option_ids ) ) {

        //if ( is_array( $input) ) {

            //Add missing inputs
            foreach ( $option_ids as $key => $option_id ) {
            
                if ( !array_key_exists( $option_id, $input ) ) {

                    $input[ $option_id] = '';

                }

            }

            //Remove malicious inputs
            foreach ( $input as $key => $option_id ) {
            
                if ( !in_array( $key, $option_ids ) ) {

                    unset( $input[ $key ] );

                }

            }            

        //}

        }

        return $input;

    }


    /**
     * Convert user friendly text to a lowercase slug with a chosen seperator.
     *
     * @param     str $value
     * @param     str $seperator
     * @return    str   
     */
    public static function malinky_settings_set_slug( $value, $seperator = '_' )
    {

        $value = trim( $value );

        if ( ! $value )
            return; //set up error

        if ( ! preg_match( '/^[A-Za-z0-9 ]+$/', $value ) )
            return; //set up error

        return strtolower( str_replace( ' ', $seperator, $value ) );

    }


    /**
     * Convert slug to user user friendly text.
     *
     * @param     str $value
     * @return    str   
     */
    public static function malinky_settings_unset_slug( $value )
    {

        $value = trim( $value );

        if ( ! $value )
            return; //set up error

        return ucwords( preg_replace( '/[^A-Za-z0-9]/', ' ', $value ) );

    }

}