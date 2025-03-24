/*!
 * Fast Cart for WooCommerce
 *
 * Author: WPXTension ( wpxtension@gmail.com )
 * Released under the GPLv3 license.
 */
// 'use strict';
( function ( $ ) {

    $(document).ready(function(){
      fc_cart_reload();
    });

    $(window).on('load', function(){
      if( fc_public_object.refresh_fragment_on_page_load === 'yes' ){
        $(document.body).trigger('wc_fragment_refresh');
      }
    });


    var container = '.fc-container', 
        content = '.fc-content',
        title_close_btn = '.fc-title-close',
        cart_icon_toogle = '.fc-floating-cart-icon, .fc-menu-item a',
        clicks = 0,
        fc_to_do = function (fragments, cart_hash, $thisbutton){

          if( fc_public_object.open_on_ajax_cart !== 'yes' ) return;
          // console.log(fc_public_object);

          fc_cart_reload();
          fc_init();
          $(container).trigger('fc_after_loaded', [ fragments, cart_hash, $thisbutton ]);
        };
      /*
       * Block theme `archive added to cart` support added
       * @version 1.0.16
       */
      $(document.body).on('added_to_cart wc-blocks_added_to_cart', function( fragments, cart_hash, $thisbutton ){
        fc_to_do( fragments, cart_hash, $thisbutton );
        fc_cart_floating_icon_reload( fragments, cart_hash );

        /* It is added to sync "clicks variable" when "Hide Overlay" option is enabled
         * If you remove it, the cart tray will not open on clicking the floating cart icon
         * when a new product is added to cart and clicked on the close button of tray
         * 
         * @note: initially, clicks = 0; so opening the tray means clicks = 1;
         * otherwise the clicks will -1 after clicking on close icon of cart tray
         */
        clicks++;
      });
      
      $(document.body).on('removed_from_cart', function( fragments, cart_hash, $thisbutton ){
        fc_to_do( fragments, cart_hash, $thisbutton );
        fc_cart_floating_icon_reload( fragments, cart_hash );

        /* It is added to sync "clicks variable" when "Hide Overlay" option is enabled
         * If you remove it, the cart tray will not open on clicking the floating cart icon
         * when a product will be removed from mini-cart
         * 
         * @note: initially, clicks = 0; so opening the tray means clicks = 1;
         * otherwise the clicks will -1 after clicking on close icon of cart tray
         */
        clicks++;
      });

      // Closing tray if clicking on inside of the tray close button
      $(title_close_btn).on('click', function( e ){
          fc_close();
          clicks--;
      });


      // Closing tray if clicking on outside of the tray
      $(container).on('click', function( e ){

        if( fc_public_object.close_behavior !== 'close_on_outside_tray' ){
          return;
        }
        

        var target = $(e.target);
        if(target.is(container) && !target.is(content)) {
          fc_close();
        }
        
      });


      // Click event for floating cart

      $(document).on('click touch', cart_icon_toogle, function(e){

        e.preventDefault();

        if( fc_public_object.overlay_layer !== 'yes' ){
          fc_init();
        }
        else{

          if (clicks == 0) {
            fc_init();
            clicks++;
          }
          else{
            fc_close();
            clicks--;
          }

        }
        
      });

    function fc_cart_reload() {
      $(document.body).trigger('wc_fragment_refresh');
      $(document.body).trigger('fc_cart_reload');
    }

    // Reinitialize floating icon

    function fc_cart_floating_icon_reload( fragments, cart_hash ){
      
      /**
       * Initially `fc-floating-cart-hide` is added in the markup
       * `class-fast-cart-front.php` and method `floating_cart`
       * So, remove this class once reinitialize this function
       * using the `added_to_cart` or `removed_from_cart` trigger
       */
      if( $('.fc-icon-wrapper').hasClass('fc-floating-cart-hide') ){
        $('.fc-icon-wrapper').removeClass('fc-floating-cart-hide');
      }
      /*
       * console.log(cart_hash.fc_cart_count);
       * 
       * Passed `fc_cart_count` as a value to get the number of items in the cart
       * It will be found inside `class-fast-cart.php` and method is `cart_fragments`
       */
      if( fc_public_object.hide_on_empty_cart === 'yes' && cart_hash.fc_cart_count === 0 ){
        $('.fc-icon-wrapper').addClass('fc-floating-cart-hide');
      }

      /**
       * Shake effect based on carted item
       */
      if( fc_public_object.shake_effect === 'yes' && cart_hash.fc_cart_count > 0 ){
        $('.fc-icon-wrapper').addClass('fc-shake');
      }
      else{
        $('.fc-icon-wrapper').removeClass('fc-shake');
      }

      $(document.body).trigger('fc_cart_floating_icon_reload');
    }

    // Initialize tray and tray background
    function fc_init(){

        if( fc_public_object.overlay_layer !== 'yes' ){
          $(container).addClass('loaded');
        }
        else{
          $(container).css({'opacity':'1'});
        }
        $(content).addClass(fc_public_object.position);
    }


    // Remove tray and tray background
    function fc_close(){    
      setTimeout( function(){
        if( $(container).hasClass('loaded') ){
          $(container).removeClass('loaded');
        }
      }, 500 );
      $(content).removeClass(fc_public_object.position);
    }

    // Action on clicking item remove button 
    $(document).on('click touch', '.fc_remove', function(e){
      e.preventDefault();
      var cart_item_key = $(this).attr('data-cart_item_key');
      fc_remove_item(cart_item_key)
      
    });

    function fc_remove_item(cart_item_key){
      fc_cart_loading();
      var data = {
        action: 'fc_remove_item',
        cart_item_key: cart_item_key,
        security: fc_public_object.nonce,
      };
      jQuery.post(fc_public_object.ajax_url, data, function(response) {
        jQuery(document.body).
          trigger('removed_from_cart', [response.fragments, response.cart_hash]);
        jQuery(document.body).
          trigger('fc_remove_item', [cart_item_key, response]);
      });
    }

    // Action on clicking empty cart button 
    $(document).on('click touch', '.fc-cart-item-empty-cart', function(e){
      e.preventDefault();
      if( fc_public_object.confirm_empty === 'yes' ){
        if(confirm(fc_public_object.confirm_empty_msg)){
            fc_empty_cart();
        }
        else{
            return false;
        }
      }
      else{
        fc_empty_cart();
      }
    });

    function fc_empty_cart(){
      fc_cart_loading();
      var data = {
        action: 'fc_empty_cart',
        empty_cart: true,
        security: fc_public_object.nonce,
      };
      jQuery.post(fc_public_object.ajax_url, data, function(response) {
        jQuery(document.body).
          trigger('removed_from_cart', [response.fragments, response.cart_hash]);
      });
    }

    // Action on changing quantity
    $(document).on('change', '.fc-woocommerce-cart-item .qty', function() {
        var item_key = $(this).closest('.fc-qty-wrap').attr('data-key');
        var item_qty = $(this).val();
        // console.log(item_key);

        fc_update_qty(item_key, item_qty);
    });

    // Updating the cart quantity
    function fc_update_qty(cart_item_key, cart_item_qty) {
      fc_cart_loading();

      var data = {
        action: 'fc_update_qty',
        cart_item_key: cart_item_key,
        cart_item_qty: cart_item_qty,
        security: fc_public_object.nonce,
      };
      // console.log(fc_public_object);

      jQuery.post(fc_public_object.ajax_url, data, function(response) {
        fc_cart_reload();
        jQuery(document.body).
            trigger('fc_update_qty', [cart_item_key, cart_item_qty]);
      });
    }

    // Fire this on changing anything in fast cart tray

    function fc_cart_loading(){
      $( '.fc-item-wrapper' ).addClass('fc-item-wrapper-blur blockUI blockOverlay');
    }

    // Plus/Minus button events

    $(document).on( 'click', 'button.plus, button.minus', function() {
        var qty = $( this ).closest( '.fc-qty-wrap' ).find( '.qty' );
        var val   = parseInt(qty.val());
        var max = parseInt(qty.attr( 'max' ));
        var min = parseInt(qty.attr( 'min' ));
        var step = parseInt(qty.attr( 'step' ));
        if ( $( this ).is( '.plus' ) ) {
           if ( max && ( max <= val ) ) {
              qty.val( max );
           } 
           else {
              qty.val( val + step );
           }
        } else {
           if ( min && ( min >= val ) ) {
              qty.val( min );
           } 
           else if ( val > 0 ) {
              qty.val( val - step );
           }
        }
        qty.trigger('change');
    });

} )( jQuery );
