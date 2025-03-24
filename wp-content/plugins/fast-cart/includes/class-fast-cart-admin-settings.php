<?php

class Fast_Cart_Admin_Settings{
	
	protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){
        add_action( 'admin_menu', array( __CLASS__, 'fast_cart_submenu' ), 99 );
        add_action( 'admin_init', array( __CLASS__, 'register_fcw_setting' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_assets' ) );
        // Tab Sections
        add_action('fcw_setting_tab_content', array( __CLASS__, 'tab_contents' ), 10, 2);
        // Settings Link
        add_filter( 'plugin_action_links_fast-cart/fast-cart.php', array( $this, 'settings_link') );
        // Plugin row meta link
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
        // Clear Settings
        add_action('fcw_layout_start', array( $this, 'reset_setting' ) );

        // Update Settings
        add_action( 'admin_init', array( $this, 'upgrade_option' ), 99 );

        do_action( 'fast_cart_admin_settings_loaded', $this );
    }

    public static function fast_cart_submenu( ){

    	add_submenu_page(
            apply_filters( 'fc_admin_settings_menu_parent', 'wpxtension' ), 
            'Fast Cart', 
            'Fast Cart', 
            'manage_options', 
            'fast-cart', 
            array( __CLASS__, 'menu_page' ) 
        );
    }

    public static function menu_page(){
        if ( is_file( plugin_dir_path( FAST_CART_PLUGIN_FILE ) . 'includes/wpxtension/wpx-sidebar.php' ) ) {
            include_once plugin_dir_path( FAST_CART_PLUGIN_FILE ) . 'includes/wpxtension/wpx-sidebar.php';
        }
        if ( is_file( plugin_dir_path( FAST_CART_PLUGIN_FILE ) . 'includes/layout.php' ) ) {
            include_once plugin_dir_path( FAST_CART_PLUGIN_FILE ) . 'includes/layout.php';
        }
    }

    public static function get_setting(){
    	return get_option( 'fast_cart_option' );
    }

    public static function tab_contents( $plugin_name, $curTab ){

        if( 'fast-cart' !==  $plugin_name ){
            return;
        }

        if( 'styling' === $curTab ){
            settings_fields( 'fast-cart-group_adavanced' );
            do_settings_sections( 'fast-cart-group_adavanced' );
            require_once dirname( __FILE__ ) . '/setting-tab/styling.php';
        }
        if( '' === $curTab || null === $curTab ){
            settings_fields( 'fast-cart-group' );
            do_settings_sections( 'fast-cart-group' );
            require_once dirname( __FILE__ ) . '/setting-tab/general.php';
        }
    }

    public static function register_fcw_setting(){
        // phpcs:disable PluginCheck.CodeAnalysis.SettingSanitization.register_settingDynamic
        // Sanitized the option inside the `sanitize_array` method
    	register_setting( 'fast-cart-group', 'fast_cart_option', array( __CLASS__, 'sanitize_array' ) );
        register_setting( 'fast-cart-group_adavanced', 'fast_cart_option_styling', array( __CLASS__, 'sanitize_array' ) ); 
        register_setting( 'fast-cart-group_license', 'fast_cart_license', 'sanitize_text_field' );
    }

    /**
     * Sanitize the array
     *
     * @param      array  $options           The address input.
     *
     * @return     array  $santized_options  The sanitized input.
     */
    public function sanitize_array( $options ) : array{

        // Initialize the new array that will hold the sanitize values
        $santized_options = array();

        // Loop through the options and sanitize each of the values
        foreach ( $options as $key => $value ) {
            $santized_options[ $key ] = ( isset( $options[ $key ] ) ) ?
            sanitize_text_field( $value ) :
            '';
        }

        return $santized_options;
    }

    public static function admin_assets() {

        // @Note: Checking if `SCRIPT_DEBUG` is defined and `true`
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        $current_screen = get_current_screen();

        $admin_settings_nonce = wp_create_nonce( 'fc-admin-settings-nonce' );

        if( wp_verify_nonce( $admin_settings_nonce, 'fc-admin-settings-nonce' ) ){

            if ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) && 'fast-cart' === $_GET['page'] ) {

                // Load font
                wp_enqueue_style('fcw-fontello', plugins_url('fonts/fontello.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');
                
                // Style Start
                wp_enqueue_style('fcw-admin', plugins_url('admin/css/backend.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');
                wp_style_add_data( 'fcw-admin', 'rtl', 'replace' );

                wp_enqueue_style('wpxtension-admin', plugins_url('includes/wpxtension/wpxtension-admin'. $suffix .'.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');
                wp_style_add_data( 'wpxtension-admin', 'rtl', 'replace' );

                wp_enqueue_style( 'wp-color-picker' ); 

                // Scripts Start

                wp_enqueue_script('jquery-ui-sortable');

                // Select2 Style & Script
                wp_enqueue_style('wpxtension-select2', plugins_url('admin/css/select2.min.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');
                wp_enqueue_script('wpxtension-select2', plugins_url('admin/js/select2.min.js', FAST_CART_PLUGIN_FILE), array('jquery'), fast_cart()->version(), true);

                // Plugin script
                wp_enqueue_script('fcw-admin', plugins_url('admin/js/backend.js', FAST_CART_PLUGIN_FILE), array('jquery','wp-color-picker', 'wpxtension-select2'), fast_cart()->version(), true);
                wp_localize_script( 'fcw-admin', 'fcw_settings', array(
                    'mode' => fast_cart()->get_options()->mode,
                    'overlay_layer' => fast_cart()->get_options()->overlay_layer,
                    'qty_field' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->qty_field : 'yes',
                    'float_icon' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->float_icon : 'yes',
                    'empty_cart' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->empty_cart : 'yes',
                ));
            }

        }
    }

    public function settings_link($links) { 
        // Build and escape the URL.
        $url = esc_url( add_query_arg(
            'page',
            'fast-cart',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . __( 'Settings', 'fast-cart' ) . '</a>';
        
        // Adds the link to the begining of the array.
        array_unshift( $links, $settings_link );

        return $links; 
    }

    /**
    * ====================================================
    * Plugin row link for plugin listing page
    * ====================================================
    **/

    public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

        if ( strpos( $plugin_file, 'fast-cart.php' ) !== false ) {

            $new_links = array(
                'ticket' => '<a href="https://wordpress.org/support/plugin/fast-cart/" target="_blank" style="font-weight: bold; color: #8012f9;">Help & Support</a>',
                'doc' => '<a href="https://wordpress.org/plugins/fast-cart/" target="_blank">Documentation</a>'
            );
             
            $plugin_meta = array_merge( $plugin_meta, $new_links );

        }
         
        return $plugin_meta;
    }

    /**
    * ====================================================
    * Reset Conditions for settings
    * ====================================================
    **/
    public function reset_setting(){

        // Condition starts from here

        if( isset( $_GET['action'] ) && 'reset' === $_GET['action'] ){

            //In our file that handles the request, verify the nonce.
            if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'fcw-settings' ) ) {
                if( isset( $_GET['tab'] ) && 'styling' === $_GET['tab'] ){
                    delete_option('fast_cart_option_styling');
                    wp_safe_redirect( admin_url( 'admin.php?page=fast-cart&tab=' . sanitize_key( wp_unslash( $_GET['tab'] ) ) ) );
                    exit();
                }
                elseif( isset( $_GET['tab'] ) && 'license' === $_GET['tab'] ){
                    delete_option('fast_cart_license');
                    wp_safe_redirect( admin_url( 'admin.php?page=fast-cart&tab=' . sanitize_key( wp_unslash( $_GET['tab'] ) ) ) );
                    exit();
                }
                else{
                    delete_option('fast_cart_option');
                    wp_safe_redirect( admin_url( 'admin.php?page=fast-cart' ) );
                    exit();
                }
                
            } else {
                
                die( esc_attr__( 'Security check', 'fast-cart' ) ); 

            }

        }
        
    }

    /**
     * Upgrade options from version 1.0 to 1.1
     */
    public function upgrade_option(){

        // Initialize an array to keep the previous value
        $styling_options = array();

        if( 
            false !== get_option('fast_cart_option') &&
            false === get_option('fast_cart_option_styling')
        ){
            // Getting the whole settings array
            $previous_options = get_option('fast_cart_option');

            // Adding the specific setting to the array
            $styling_options['mode'] = isset( $previous_options['mode'] ) ?? $previous_options['mode'];
            $styling_options['color'] = isset( $previous_options['color'] ) ?? $previous_options['color'];
            $styling_options['buttons_style'] = isset( $previous_options['buttons_style'] ) ?? $previous_options['buttons_style'];

            // print_r($styling_options);


            // Updating the option with the previous value
            add_option('fast_cart_option_styling', $styling_options );

            // Removing the settings that already in advanved
            $refreshed_general_option = array_diff( $previous_options, $styling_options );

            // print_r($refreshed_general_option);

            // Updating the option for the general tab
            update_option('fast_cart_option', $refreshed_general_option );
        }

    }

}