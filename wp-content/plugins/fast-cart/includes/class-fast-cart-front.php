<?php 

class Fast_Cart_Front{

	protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){


        $this->includes();

        $this->hooks();
    }

    public function hooks(){
        // Initialize Markup

        if ( has_action( 'wp_body_open' ) ){
            add_action( 'wp_body_open', array( $this, 'init_markup' ) );
            add_action( 'template_redirect', array( $this, 'init_markup_reload' ) );
            add_action( 'wp_body_open', array( $this, 'init_floating_cart' ) );
        }

        // Fragment refresh filters
        add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_fragment' ), 20, 1 );
        add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'cart_fragment' ), 20, 1 );

        // Asset Load
        add_action( 'wp_enqueue_scripts', array( $this, 'front_asset' ) );

        // Add quantity for items in cart tray
        add_filter( 'fc_woocommerce_widget_cart_item_quantity', array( $this,'add_quantity_fields' ), 10, 3 );

        // Add button in cart tray
        add_action( 'fc_woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
        add_action( 'fc_woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 10 );

        // Display Subtotal & Total in Fast Cart Tray
        add_action( 'fc_woocommerce_widget_shopping_cart_total', array( $this, 'woocommerce_widget_shopping_cart_subtotal' ), 10 );
        add_action( 'fc_woocommerce_widget_shopping_cart_total', array( $this, 'woocommerce_widget_shopping_cart_total' ), 11 );

        // Update price based on quantity
        add_action( 'wp_ajax_fc_update_qty', array( $this,'fc_update_qty' ) );
        add_action( 'wp_ajax_nopriv_fc_update_qty', array( $this,'fc_update_qty' ) );

        // Empty cart
        add_action( 'wp_ajax_fc_empty_cart', array( $this, 'fc_empty_cart' ) );
        add_action( 'wp_ajax_nopriv_fc_empty_cart', array( $this, 'fc_empty_cart' ) );

        // Remove item from cart
        add_action( 'wp_ajax_fc_remove_item', array( $this,'fc_remove_item' ) );
        add_action( 'wp_ajax_nopriv_fc_remove_item', array( $this,'fc_remove_item' ) );

        // Reverse current cart items at the top of cart tray
        add_filter( 'fc_cart_items_array', array( $this, 'fc_cart_items_reverse' ) );

        // Adding body class
        add_action( 'body_class', array( $this, 'fc_body_class' ) );

        // Adding menu
        add_filter( 'wp_nav_menu_items', array( $this, 'add_menu_item_content' ), 99, 2);
        
    }

    public function add_menu_item_content( $items, $args ){
        $selected    = false;
        $saved_menus = fast_cart()->get_options()->menus;

        if ( ! is_array( $saved_menus ) || empty( $saved_menus ) || ! property_exists( $args, 'menu' ) ) {
            return $items;
        }

        if ( $args->menu instanceof WP_Term ) {
            // menu object
            if ( in_array( $args->menu->term_id, $saved_menus, false ) ) {
                $selected = true;
            }
        } elseif ( is_numeric( $args->menu ) ) {
            // menu id
            if ( in_array( $args->menu, $saved_menus, false ) ) {
                $selected = true;
            }
        } elseif ( is_string( $args->menu ) ) {
            // menu slug or name
            $menu = get_term_by( 'name', $args->menu, 'nav_menu' );

            if ( ! $menu ) {
                $menu = get_term_by( 'slug', $args->menu, 'nav_menu' );
            }

            if ( $menu && in_array( $menu->term_id, $saved_menus ) ) {
                $selected = true;
            }
        }

        if ( $selected ) {
            $items .= $this->get_cart_menu();
        }

        return $items;
    }


    public function get_cart_menu() {
        if ( ! isset( WC()->cart ) ) {
            return '';
        }

        $count     = WC()->cart->get_cart_contents_count();
        $subtotal  = WC()->cart->get_cart_subtotal();
        $icon      = Fast_Cart::get_options()->icon;
        $cart_menu = '<li class="' . esc_attr( apply_filters( 'fc_cart_menu_class', 'menu-item fc-menu-item menu-item-type-fc' ) ) . '"><a class="fc-cart-menu-item-link" href="' . esc_url( wc_get_cart_url() ) . '"><span class="fc-menu-item-inner" data-count="' . esc_attr( $count ) . '"><span class="' . esc_attr( $icon ) . '"></span> <span class="fc-menu-item-inner-subtotal">' . $subtotal . '</span></span></a></li>';

        return apply_filters( 'fast_cart_cart_menu_icon', $cart_menu, $count, $subtotal, $icon );
    }

    public function fc_body_class( $classes ){

        $classes[] = 'fc_'.get_option('stylesheet');
        return $classes;

    }

    public function includes(){

        require_once dirname( FAST_CART_PLUGIN_FILE ) . '/includes/style.php';

    }


    // Initialize the basic core markup

    public function init_markup(){

        // Title, by default
        $fast_cart_title = sprintf(

            "<div class='fc-title no-close'>%s</div>",
            apply_filters( 'fast_cart_block_title', __('Shopping Cart', 'fast-cart') )

        );

        // Title, If close button on tray is on
        if( 
            // Overlay is hidden or
            fast_cart()->get_options()->overlay_layer === 'yes' || 
            // Close button on the tray is enabled and
            fast_cart()->get_options()->close_behavior === 'close_on_cart_tray' &&
            // Overlay is not hidden
            fast_cart()->get_options()->overlay_layer === 'no'
        ){

            $fast_cart_title = sprintf(

                "<div class='fc-title'>
                <div class='fc-title-text'>
                %s
                </div>
                %s
                </div>",
                apply_filters( 'fast_cart_block_title', __('Shopping Cart', 'fast-cart') ),
                apply_filters( 'fast_cart_block_title_close', sprintf("<div class='fc-title-close'>&times;</div>") )

            );

        }


        $fast_cart_container = sprintf(

            "<div class='fc-container'><div class='fc-content %s'><div class='fc-content-inner'>%s%s</div></div></div>",
            'fc-content_'.fast_cart()->get_options()->position,
            $fast_cart_title,
            $this->load_cart_content()

        );

        echo wp_kses_post( apply_filters( 'fast_cart_init_markup', $fast_cart_container ) );

    }

    // Initialize the basic core markup [if page reload]

    public function init_markup_reload(){

        $open_on_normal_cart = !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->open_on_normal_cart : 'yes';

        if( $open_on_normal_cart === 'no' ){
            return;
        }

        // Title, by default
        $fast_cart_title = sprintf(

            "<div class='fc-title no-close'>%s</div>",
            apply_filters( 'fast_cart_block_title', __('Shopping Cart', 'fast-cart') )

        );

            // Title, If close button on tray is on
        if( fast_cart()->get_options()->close_behavior === 'close_on_cart_tray' ){

            $fast_cart_title = sprintf(

                "<div class='fc-title'>
                <div class='fc-title-text'>
                %s
                </div>
                %s
                </div>",
                apply_filters( 'fast_cart_block_title', __('Shopping Cart', 'fast-cart') ),
                apply_filters( 'fast_cart_block_title_close', sprintf("<div class='fc-title-close'>&times;</div>") )

            );

        }

        $fast_cart_container = sprintf(

            "<div class='fc-container loaded'><div class='fc-content %s %s'><div class='fc-content-inner'>%s%s</div></div></div>",
            'fc-content_'.fast_cart()->get_options()->position,
            fast_cart()->get_options()->position,
            $fast_cart_title,
            $this->load_cart_content()

        );

        if( did_action('woocommerce_add_to_cart') ){

            echo wp_kses_post( apply_filters( 'fast_cart_init_markup_reload', $fast_cart_container ) );

        }

    }


    // Fast Cart Inner Content

    public function load_cart_content(){

        ob_start();

        
        $this->load_cart_content_template_load();
        

        return ob_get_clean();
    }

    // Cart Inner Content 

    public function load_cart_content_template_load(){

        echo '<div class="fc-item-wrapper">';

        if ( $overridden_template = locate_template( '/fast-cart/fast-mini-cart.php' ) ) {

            load_template( $overridden_template );

        }

        else{

            $defaults = array(
                'list_class' => 'fc-ul-container'
            );

            load_template( dirname( FAST_CART_PLUGIN_FILE ) . '/includes/templates/fast-mini-cart.php', true, $defaults );

        }

        echo '</div>';

    }

    // Adding scripts and styles

    public function front_asset(){

        // @Note: Checking if `SCRIPT_DEBUG` is defined and `true`
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        // Load font
        wp_enqueue_style('fcw-fontello', plugins_url('fonts/fontello.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');

        wp_enqueue_style('fcw-public', plugins_url('public/css/public'. $suffix .'.css', FAST_CART_PLUGIN_FILE), array(), fast_cart()->version(), 'all');
        wp_style_add_data( 'fcw-public', 'rtl', 'replace' );

        wp_enqueue_script('fcw-public', plugins_url('public/js/public'. $suffix .'.js', FAST_CART_PLUGIN_FILE), array('jquery'), fast_cart()->version(), true);

        wp_localize_script( 'fcw-public', 'fc_public_object',
            array( 
                'open_on_ajax_cart' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->open_on_ajax_cart : 'yes',
                'open_on_normal_cart' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->open_on_normal_cart : 'yes',
                'refresh_fragment_on_page_load' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->refresh_fragment_on_page_load : 'yes',
                'overlay_layer' => fast_cart()->get_options()->overlay_layer,
                'position' => fast_cart()->get_options()->position,
                'close_behavior' => fast_cart()->get_options()->close_behavior,
                'confirm_empty' => fast_cart()->get_options()->confirm_empty,
                'confirm_empty_msg' => apply_filters('fc_cart_cofirm_empty_msg', esc_html__( 'Are you sure? It will remove all the carted items.', 'fast-cart')),
                'hide_on_empty_cart' => fast_cart()->get_options()->hide_on_empty_cart,
                'shake_effect' => fast_cart()->get_options()->shake_effect,
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'fc-security' ),
            )
        );

        /* WC Fragment support in WooCommerce 7.8.0
         *
         * @Note: Checking if already enqueued. If not then enqueue the script
         * 
         * @Note: wp_script_is( $handle = 'js handle', $status = 'enqueued' ); return: bool (true/false)
         */
        if (!wp_script_is( 'wc-cart-fragments', 'enqueued' )) {
            wp_enqueue_script( 'wc-cart-fragments' );
        }

    }

    // Cart Fragments Refresh

    public function cart_fragment( $fragments ) {
        
        ob_start();

        $this->load_cart_content_template_load();

        $fragments['.fc-item-wrapper'] = ob_get_clean();

        ob_start();

        $fragments['fc_cart_count'] = WC()->cart->get_cart_contents_count();

        ob_start();
        $fragments['.fc-icon-quantity-wrapper span'] = "<span class='fc-quantity-holder'>". WC()->cart->get_cart_contents_count() . "</span>";

        ob_start();
        $fragments['.fc-menu-item'] = $this->get_cart_menu();

        return $fragments;
    }


    // Initialize Floating Cart 

    public function init_floating_cart(){

        $shake = '';
        $hide = '';

        $float_icon = !empty( get_option('fast_cart_option') ) ? fast_cart()->get_options()->float_icon : 'yes';

        if( $float_icon === 'no' ){
            return;
        }

        if( fast_cart()->get_options()->hide_on_empty_cart === 'yes' ){
            $hide = ( WC()->cart->get_cart_contents_count() == 0 ) ? 'fc-floating-cart-hide' : '';
        }

        if( fast_cart()->get_options()->shake_effect === 'yes' ){
            $shake = ( WC()->cart->get_cart_contents_count() > 0 ) ? 'fc-shake' : '';
        }

        echo sprintf(

            '<div class="fc-floating-cart-icon %s">%s</div>',
            esc_attr( fast_cart()->get_options()->float_icon_position ),
            wp_kses_post( $this->floating_cart( esc_attr( $shake ), esc_attr( $hide ) ) )

        );

    }

    // Floating Cart 

    public function floating_cart( $shake = '', $hide = '' ){

        return sprintf(

            '<div class="fc-icon-wrapper %s %s">
                <div class="fc-icon-quantity-wrapper">
                    <span class="fc-icon-quantity">%s</span>
                </div>
                <i class="%s"></i>
            </div>',
            $shake,
            $hide,
            WC()->cart->get_cart_contents_count(),
            esc_attr( Fast_Cart::get_options()->icon )

        );
    }

    // Input field for quantity

    public function add_quantity_fields( $html, $cart_item, $cart_item_key ) {

        $qty_field = !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->qty_field : 'yes';
        $qty_control = !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->qty_control : 'yes';

        if( $qty_field === 'no' ){
            return $html;
        }
        $plus_btn = '<button type="button" class="plus">+</button>';;
        $minus_btn = '<button type="button" class="minus">-</button>';
        return sprintf(
            '<div class="fc-qty-wrap %5$s" data-key="%1$s">%2$s<div class="fc-qty-control">%4$s%3$s</div></div>',
            // 1- Cart Item Key
            $cart_item_key,
            // 2- WooCommerce Quantity Input Function
            woocommerce_quantity_input( array('input_value' => $cart_item['quantity']), $cart_item['data'], false ),
            // 3- Minus Button
            $qty_control === 'yes' ? $minus_btn : '',
            // 4- Plus Button
            $qty_control === 'yes' ? $plus_btn : '',
            // 5- Additional class to determine that plus-minus option is enabled
            $qty_control === 'yes' ? esc_attr('fc-plus-minus-enabled') : ''
        );
    }

    // Update price based on quantity
    public function fc_update_qty() {

        check_ajax_referer( 'fc-security', 'security' );

        if ( isset( $_POST['cart_item_key'], $_POST['cart_item_qty'] ) && ! empty( $_POST['cart_item_key'] ) ) {
            if ( WC()->cart->get_cart_item( sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) ) ) {
                if ( (float) sanitize_text_field( wp_unslash( $_POST['cart_item_qty'] ) ) > 0 ) {
                    WC()->cart->set_quantity( sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ), (float) sanitize_text_field( wp_unslash( $_POST['cart_item_qty'] ) ) );
                } else {
                    WC()->cart->remove_cart_item( sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) );
                }
            }

            echo wp_json_encode( array( 'action' => 'fc_update_qty' ) );

            die();
        }
    }

    // Remove item from cart
    public function fc_remove_item() {
        check_ajax_referer( 'fc-security', 'security' );

        if ( isset( $_POST['cart_item_key'] ) ) {
            WC()->cart->remove_cart_item( sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) );
            WC_AJAX::get_refreshed_fragments();
        }

        wp_die();
    }

    // Empty cart
    public function fc_empty_cart(){
        check_ajax_referer( 'fc-security', 'security' );
        if ( isset( $_POST['empty_cart'] ) ) {
            WC()->cart->empty_cart();
            WC_AJAX::get_refreshed_fragments();
        }
        wp_die();
    }

    // Display Total Price in floating cart tray
    public function woocommerce_widget_shopping_cart_total() {
        $total_check = !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->total : 'yes';
        if( $total_check === 'no' ){
            return;
        }
        $value = '<div class="fc-bottom-total-wrapper">';
        $value .= '<span class="fc-bottom-total-title">' . esc_html__( 'Total:', 'fast-cart' ) . '</span> ';
        $value .= '<span class="fc-bottom-total-price">'.WC()->cart->get_total();

        // If prices are tax inclusive, show taxes here.
        // @note: see `wc_cart_totals_order_total_html` to understand it in deep
        if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ) {
            $tax_string_array = array();
            $cart_tax_totals  = WC()->cart->get_tax_totals();

            if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) {
                foreach ( $cart_tax_totals as $code => $tax ) {
                    $tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
                }
            } elseif ( ! empty( $cart_tax_totals ) ) {
                $tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
            }

            if ( ! empty( $tax_string_array ) ) {
                $taxable_address = WC()->customer->get_taxable_address();
                if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                    $country = WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ];
                    /* translators: 1: tax amount 2: country name */
                    $tax_text = wp_kses_post( sprintf( __( '(includes %1$s estimated for %2$s)', 'fast-cart' ), implode( ', ', $tax_string_array ), $country ) );
                } else {
                    /* translators: %s: tax amount */
                    $tax_text = wp_kses_post( sprintf( __( '(includes %s)', 'fast-cart' ), implode( ', ', $tax_string_array ) ) );
                }

                $value .= '<small class="includes_tax">' . $tax_text . '</small>';
            }
        }
        $value .= '</span></div>';

        echo wp_kses_post( apply_filters( 'fc_woocommerce_cart_totals_order_total_html', $value ) );
    }


    // Display Subtotal Price in floating cart tray
    public function woocommerce_widget_shopping_cart_subtotal(){
        
        $value = '<div class="fc-bottom-subtotal-wrapper">';

        $value .= '<span class="fc-bottom-subtotal-title">' . esc_html__( 'Subtotal:', 'fast-cart' ) . '</span> ';

        $value .= '<span class="fc-bottom-subtotal-price">'.WC()->cart->get_cart_subtotal();

        $value .= '</span></div>';

        echo wp_kses_post( apply_filters( 'fc_woocommerce_cart_totals_order_subtotal_html', $value ) );
    }

    // Display Buttons under the subtotal and total [Cart Tray]
    public function woocommerce_widget_shopping_cart_button_view_cart() {
        $wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button wc-forward' . esc_attr( $wp_button_class ) . '">' . esc_html__( 'View cart', 'fast-cart' ) . '</a>';
    }

    /**
     * 
     * Display recent cart items at the top of the cart tray
     * @since 1.0.5
     * 
     */
    public function fc_cart_items_reverse( $items ){
        if( Fast_Cart::get_options()->reverse_items === 'yes' ){
            return array_reverse( $items );
        }
        return $items;
    }

}