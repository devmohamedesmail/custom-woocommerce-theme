<?php 

defined( 'ABSPATH' ) or die( 'Keep Quit' );

class Fast_Cart{

    /*
     * Version of Plugin.
     *
     */

    protected $_plugin = 'fast-cart';
    
    protected $_version = '1.1.0';

    protected static $_instance = null;


    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /*
     * Construct of the Class.
     *
     */

    public function __construct(){

        $this->includes();
        $this->init();
        $this->get_wpx_menu();
        $this->get_wpx_setting_fields();

    }


    /*
     * Version function.
     *
     */
    public function version() {
        return esc_attr( $this->_version );
    }

    /*
     * Name function.
     *
     */
    public function plugin() {
        return esc_attr( $this->_plugin.'-pro' );
    }

    /*
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public function init() {

        // Load TextDomain
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        $this->get_backend();
        $this->get_frontend();
    } 


    /**
     *
     * Load Text Domain Folder
     *
     */
    public function load_textdomain() {
        load_plugin_textdomain( "fast-cart", false, basename( dirname( __FILE__ ) )."/languages" );
    }

    /*
     * Includes files.
     *
     */

    public function includes() {
        require_once dirname( __FILE__ ) . '/wpxtension/wpx-menu.php';
        require_once dirname( __FILE__ ) . '/wpxtension/wpx-setting-fields-basic.php';
        require_once dirname( __FILE__ ) . '/class-fast-cart-admin-settings.php';
        require_once dirname( __FILE__ ) . '/class-fast-cart-front.php';
    }

    /**
     *
     * WPX Menu
     *
     */

    public function get_wpx_menu(){
        return WPXtension_Menu::instance();
    }


    /**
     *
     * WPX Setting Fields
     *
     */

    public function get_wpx_setting_fields(){
        return new WPXtension_Setting_Fields_Basic(self::check_plugin_state($this->plugin()));
    }

    /**
     *
     * Admin Settings
     *
     */

    public function get_backend(){
        return Fast_Cart_Admin_Settings::instance();
    }


    /**
     *
     * Frontend 
     *
     */

    public function get_frontend(){
        return Fast_Cart_Front::instance();
    }


    /**
     *
     * Return all options of Fast Cart Plugin
     *
     */
    public static function get_options(){

        $get_option = array_merge( (array) get_option('fast_cart_option'), (array) get_option('fast_cart_option_styling') );

        $options = array(

            'open_on_ajax_cart' => ( empty( $get_option['open_on_ajax_cart'] ) ) ? 'no' : $get_option['open_on_ajax_cart'],

            'open_on_normal_cart' => ( empty( $get_option['open_on_normal_cart'] ) ) ? 'no' : $get_option['open_on_normal_cart'],

            'overlay_layer' => ( empty( $get_option['overlay_layer'] ) ) ? 'no' : $get_option['overlay_layer'],

            'position' => ( !empty( $get_option['position'] ) ) ? $get_option['position'] : 'tray_right',

            'close_behavior' => ( !empty( $get_option['close_behavior'] ) ) ? $get_option['close_behavior'] : 'close_on_outside_tray',
            
            'refresh_fragment_on_page_load' => ( empty( $get_option['refresh_fragment_on_page_load'] ) ) ? 'no' : $get_option['refresh_fragment_on_page_load'],

            'menus' => ( !empty( $get_option['menus'] ) ) ? $get_option['menus'] : array(),

            // ### Item Settings

            'link_behavior' => ( !empty( $get_option['link_behavior'] ) ) ? $get_option['link_behavior'] : 'open_in_new_tab',

            'reverse_items' => ( empty( $get_option['reverse_items'] ) ) ? 'no' : $get_option['reverse_items'],

            'empty_cart' => ( empty( $get_option['empty_cart'] ) ) ? 'no' : $get_option['empty_cart'],

            'confirm_empty' => ( empty( $get_option['confirm_empty'] ) ) ? 'no' : $get_option['confirm_empty'],

            'regular_price' => ( empty( $get_option['regular_price'] ) ) ? 'no' : $get_option['regular_price'],

            'remove_btn' => ( empty( $get_option['remove_btn'] ) ) ? 'no' : $get_option['remove_btn'],

            'qty_field' => ( empty( $get_option['qty_field'] ) ) ? 'no' : $get_option['qty_field'],

            'qty_control' => ( empty( $get_option['qty_control'] ) ) ? 'no' : $get_option['qty_control'],

            'total' => ( empty( $get_option['total'] ) ) ? 'no' : $get_option['total'],


            // ### Floating Icon Settings

            'float_icon' => ( empty( $get_option['float_icon'] ) ) ? 'no' : $get_option['float_icon'],

            'icon' => ( empty( $get_option['icon'] ) ) ? 'fc-icon-shopping-basket' : $get_option['icon'],

            'float_icon_position' => ( !empty( $get_option['float_icon_position'] ) ) ? $get_option['float_icon_position'] : 'bottom_left',

            'shake_effect' => ( empty( $get_option['shake_effect'] ) ) ? 'no' : $get_option['shake_effect'],
            
            'hide_on_empty_cart' => ( empty( $get_option['hide_on_empty_cart'] ) ) ? 'no' : $get_option['hide_on_empty_cart'],


            // ### Styling

            'mode' => ( !empty( $get_option['mode'] ) ) ? $get_option['mode'] : 'light',

            'light_mode_color' => ( empty( $get_option['light_mode_color'] ) ) ? '#ffffff' : $get_option['light_mode_color'],

            'dark_mode_color' => ( empty( $get_option['dark_mode_color'] ) ) ? '#222222' : $get_option['dark_mode_color'],

            'color' => ( empty( $get_option['color'] ) ) ? '#8012f9' : $get_option['color'],

            'tray_bottom_color' => ( empty( $get_option['tray_bottom_color'] ) ) ? '#f4f4f4' : $get_option['tray_bottom_color'],

            'tray_bottom_border_color' => ( empty( $get_option['tray_bottom_border_color'] ) ) ? '#c4c4c4' : $get_option['tray_bottom_border_color'],

            'buttons_style' => ( empty( $get_option['buttons_style'] ) ) ? 'inline' : $get_option['buttons_style'],


        );

        return (object) apply_filters( 'fast_cart_options', $options );
    }

    /**
     *
     * Check plugin exits or not
     *
     */

    public static function check_plugin_state( $plugin_name ){

        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (is_plugin_active( $plugin_name.'/'.$plugin_name.'.php' ) ){

            return true;

        }
        else{

            return false;

        }

    }

    /**
     *
     * All Icons list
     *
     */

    public static function all_icons(){

        $icons = array(

            'fc-icon-grocery-store',
            'fc-icon-shopping-bag',
            'fc-icon-shopping-basket',
            'fc-icon-basket',
            'fc-icon-basket-1',
            'fc-icon-bag',
            'fc-icon-basket-2',
            'fc-icon-basket-3',
            'fc-icon-cart',
            'fc-icon-basket-4',
            'fc-icon-shop',
            'fc-icon-bitbucket'

        );

        return apply_filters( 'fc_all_cart_icons', $icons );
    }


}