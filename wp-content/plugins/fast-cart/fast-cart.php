<?php
/**
 * Plugin Name: Fast Cart for WooCommerce
 * Plugin URI: https://wpxtension.com/
 * Description: Beautiful & Responsive floating cart to ensure the best shopping experience and more sales. 🛒️
 * Author: WPXtension
 * Version: 1.1.0
 * Domain Path: /languages
 * Requires at least: 5.5
 * Tested up to: 6.7
 * Requires PHP: 7.2
 * WC requires at least: 5.5
 * WC tested up to: 9.7
 * Text Domain: fast-cart
 * Author URI: https://wpxtension.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! defined( 'FAST_CART_PLUGIN_FILE' ) ) {
    define( 'FAST_CART_PLUGIN_FILE', __FILE__ );
}

// Include the main class.
if ( ! class_exists( 'Fast_Cart', false ) ) {
    require_once dirname( __FILE__ ) . '/includes/class-fast-cart.php';
}

// Require woocommerce admin message
function fast_cart_wc_requirement_notice() {

    if ( ! class_exists( 'WooCommerce' ) ) {
        printf( 
            '<div class="%1$s">
            <p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p>
            </div>', 'notice notice-error', 
            wp_kses( __( "<strong>Fast Cart for WooCommerce</strong> is an add-on of ", 'fast-cart' ), array( 'strong' => array() ) ), 
            esc_url( add_query_arg( array(
                'tab'       => 'plugin-information',
                'plugin'    => 'woocommerce',
                'TB_iframe' => 'true',
                'width'     => '640',
                'height'    => '500',
            ), admin_url( 'plugin-install.php' ) ) ), 
            esc_html__( 'WooCommerce', 'fast-cart' )
        );
    }
}

add_action( 'admin_notices', 'fast_cart_wc_requirement_notice' );

/**
 * Returns the main instance.
 */

function fast_cart() {

    if ( ! class_exists( 'WooCommerce', false ) ) {
        return false;
    }

    return Fast_Cart::instance();
}

add_action( 'plugins_loaded', 'fast_cart' );

// HPOS compatibility for Fast Cart
function fast_cart_hpos_compatibility() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
}

add_action( 'before_woocommerce_init', 'fast_cart_hpos_compatibility' );