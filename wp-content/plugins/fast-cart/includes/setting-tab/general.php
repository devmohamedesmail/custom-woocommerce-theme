<section class="general" id="fc-general-section">

    <h3><?php echo esc_attr__( 'Basic Settings', 'fast-cart' ); ?></h3>
    <table class="widefat wpx-table">
        <?php 

            // Open on AJAX Add to cart
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Open on AJAX Add to Cart', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->open_on_ajax_cart : 'yes',
                    'name' => 'fast_cart_option[open_on_ajax_cart]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Fast cart will be opened immediately after whenever click to AJAX Add to cart buttons', 'fast-cart'),
                    'note' => '',
    
                )
            ); 

            // Open on normal Add to cart
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Open on Normal Add to Cart', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->open_on_normal_cart : 'yes',
                    'name' => 'fast_cart_option[open_on_normal_cart]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Fast cart will be opened immediately after whenever click to normal Add to cart buttons (AJAX is not enable) or Add to cart button in single product page', 'fast-cart'),
                    'note' => '',
    
                )
            ); 

            // Hide Overlay layer
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate parent-overlay',
                    'label' => esc_attr__('Hide Overlay layer', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->overlay_layer,
                    'name' => 'fast_cart_option[overlay_layer]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('If you hide the overlay layer, the user still can work on your site even the cart tray is open.', 'fast-cart'),
                    'note' => esc_attr__('If you enable this option, by default, a close icon will appear on the cart tray.', 'fast-cart'),
    
                )
            );

            // Closing Behavior
            WPXtension_Setting_Fields_Basic::select(
                $options = array(
                    'tr_class' => 'child-overlay',
                    'label' => esc_attr__('How to Close Floating Tray', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->close_behavior,
                    'name' => 'fast_cart_option[close_behavior]',
                    'option' => apply_filters('fcw_close_behavior_option', array(
                        'option_1' => array(
                            'name' => 'Clicking on Close Button of Cart Tray',
                            'value' => 'close_on_cart_tray',
            
                        ),
                        'option_2' => array(
                            'name' => 'Clicking on outside of Cart Tray',
                            'value' => 'close_on_outside_tray',
            
                        ),
                    )),
                    'note' => '',
    
                )
            ); 

            // Position of fast cart
            WPXtension_Setting_Fields_Basic::select(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Floating Tray Position', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->position,
                    'name' => 'fast_cart_option[position]',
                    'option' => apply_filters('fcw_position_option', array(
                        'option_1' => array(
                            'name' => 'Left',
                            'value' => 'tray_left',
            
                        ),
                        'option_2' => array(
                            'name' => 'Right',
                            'value' => 'tray_right',
            
                        ),
                        'option_3' => array(
                            'name' => 'Top',
                            'value' => 'tray_top',
            
                        ),
                        'option_4' => array(
                            'name' => 'Bottom',
                            'value' => 'tray_bottom',
            
                        ),
                        'option_5' => array(
                            'name' => 'Center',
                            'value' => 'tray_center',
            
                        ),
                    )),
                    'note' => '',
    
                )
            ); 

            // Refresh fragment on page load
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Refresh Cart on Page Load', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->refresh_fragment_on_page_load : 'yes',
                    'name' => 'fast_cart_option[refresh_fragment_on_page_load]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Will refresh the fragment and display the effects of settings immediately on the frontend after reloading page.', 'fast-cart'),
                    'note' => 'Note: Recommeneded if you want to check the update of settings immediately on the frontend after reloading.'
    
                )
            ); 

            // Add cart icons to menu
            $saved_menus = get_terms( 
                array( 
                    'taxonomy' => 'nav_menu', 
                    'hide_empty' => false 
                ) 
            );
            $menus = array();
            $i = 0;

            // print_r($saved_menus);

            foreach( $saved_menus as $menu ){ $i++;
                $menus += array(
                    'option_'.$i => array(
                        'name' => $menu->name,
                        'value' => $menu->term_id,
                    )
                );
            }

            WPXtension_Setting_Fields_Basic::multiselect(
                $options = array(
                    'tr_class' => 'new alternate',
                    'label' => esc_attr__('Place Cart Icon in Menus', 'fast-cart'),
                    'ele_class' => ' menus wpx-multiselect',
                    'value' => Fast_Cart::get_options()->menus,
                    'name' => 'fast_cart_option[menus][]',
                    'option' => apply_filters('psfw_menus', $menus),
                    'note' => __('Choose the menu(s) you want to add toggle cart icon and total at the end, to trigger the cart tray.', 'fast-cart'),
                    'tag' => 'New'
                )
            ); 


        ?>
    </table>

    <h3><?php echo esc_attr__( 'Item Settings', 'fast-cart' ); ?></h3>
    <table class="widefat wpx-table">

        <?php 

            // Link to individual item
            WPXtension_Setting_Fields_Basic::select(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Link Behaviour of Item', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->link_behavior,
                    'name' => 'fast_cart_option[link_behavior]',
                    'option' => apply_filters('fcw_close_behavior_option', array(
                        'option_1' => array(
                            'name' => 'Open in New Tab',
                            'value' => 'open_in_new_tab',
            
                        ),
                        'option_2' => array(
                            'name' => 'Open in same Tab',
                            'value' => 'open_in_same_tab',
            
                        ),
                        'option_3' => array(
                            'name' => 'No Link',
                            'value' => 'no_link',
            
                        ),
                    )),
                    'note' => '',
    
                )
            ); 

            // Reverse Items
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Reverse Item', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->reverse_items,
                    'name' => 'fast_cart_option[reverse_items]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display current cart items at the top of the cart tray.', 'fast-cart'),
                    'note' => '',
                    'tag' => 'New'
                )
            );

            // Empty Cart
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'new alternate parent-empty_cart',
                    'label' => esc_attr__('Empty Cart', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->empty_cart : 'yes',
                    'name' => 'fast_cart_option[empty_cart]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display a link to remove all the carted items.', 'fast-cart'),
                    'note' => '',
                    'tag' => 'New'
                )
            );
            // Empty Cart Confirmation
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'new child-empty_cart',
                    'label' => esc_attr__('Confirm Empty', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->confirm_empty,
                    'name' => 'fast_cart_option[confirm_empty]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display a confirmation alert before emptying cart.', 'fast-cart'),
                    'note' => '',
                    'tag' => 'New'
                )
            );

            // Display Regular Price
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'new alternate',
                    'label' => esc_attr__('Enable regular price', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->regular_price : 'yes',
                    'name' => 'fast_cart_option[regular_price]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display the strikethrough regular price.', 'fast-cart'),
                    'note' => '',
                    'tag' => 'New'
                )
            );

            // Disable Remove Button
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Disable Remove Button', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->remove_btn,
                    'name' => 'fast_cart_option[remove_btn]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Hide the remove button for the item.', 'fast-cart'),
                    'note' => '',
    
                )
            );


            // Enable quantity field
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate parent-qty_field',
                    'label' => esc_attr__('Enable Quantity Field', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->qty_field : 'yes',
                    'name' => 'fast_cart_option[qty_field]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display Quantity Field to increase the carted product from cart tray.', 'fast-cart'),
                    'note' => '',
    
                )
            );

            // Enable quantity field control
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'new child-qty_field',
                    'label' => esc_attr__('Enable +/- Button', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->qty_control : 'yes',
                    'name' => 'fast_cart_option[qty_control]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display +/- button with Quantity Field to increase the carted product from cart tray.', 'fast-cart'),
                    'note' => '',
                    'tag' => 'New'
                )
            );

            // Total
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Display Total', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->total : 'yes',
                    'name' => 'fast_cart_option[total]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display Total bottom of the cart tray.', 'fast-cart'),
                    'note' => '',
    
                )
            );


        ?>

    </table>

    <h3><?php echo esc_attr__( 'Icon Settings', 'fast-cart'); ?></h3>
    <table class="widefat wpx-table">
        <tr class="alternate" valign="top">

            <td class="row-title" scope="row">
                <label for="tablecell">
                    <?php echo esc_attr__('Icons to Display', 'fast-cart'); ?>
                </label>   
            </td>
            <td>
                <ul id="radiolist" style="margin-top: 5px;" class="radiolist">
                   
                   <li>
                       <i class="<?php echo esc_attr(Fast_Cart::get_options()->icon); ?>"></i>
                   </li>

                </ul>

                <button class="more-icons"><i class="dashicons dashicons-plus"></i> <?php echo esc_attr__('More Icons', 'fast-cart'); ?></button>

                <div class="all-icons">

                    <ul id="base-list" class="sortable-list">
                        <?php
                            $all_icons = Fast_Cart::all_icons();

                            foreach( $all_icons as $key => $icon ): 
                        ?>

                            <li id="<?php echo esc_attr( $key ); ?>">
                                <label>
                                    <input type="radio" name="fast_cart_option[icon]" value="<?php echo esc_attr( $icon ); ?>" <?php echo ( Fast_Cart::get_options()->icon === $icon ) ? "checked" : ""; ?> />
                                    <span>
                                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                    </span>
                                </label>
                            </li>

                        <?php 
                            endforeach;  
                        ?>
                    </ul>

                </div>

            </td>
        </tr>
    </table>

    <h3><?php echo esc_attr__( 'Floating Cart Settings', 'fast-cart'); ?></h3>
    <table class="widefat wpx-table">
        <?php 

            // Enable Floating Icon
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate parent-floating_icon',
                    'label' => esc_attr__('Enable Floating Icon', 'fast-cart'),
                    'value' => !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->float_icon : 'yes',
                    'name' => 'fast_cart_option[float_icon]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Enable Foating Icon.', 'fast-cart'),
                    'note' => '',
    
                )
            ); 

            // Floating Icon Position
            WPXtension_Setting_Fields_Basic::select(
                $options = array(
                    'tr_class' => 'child-floating_icon',
                    'label' => esc_attr__('Position', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->float_icon_position,
                    'name' => 'fast_cart_option[float_icon_position]',
                    'option' => apply_filters('fc_float_icon_position_option', array(
                        'option_1' => array(
                            'name' => 'Bottom Left',
                            'value' => 'bottom_left',
            
                        ),
                        'option_2' => array(
                            'name' => 'Bottom Right',
                            'value' => 'bottom_right',
            
                        ),
                        'option_3' => array(
                            'name' => 'Top Left',
                            'value' => 'top_left',
            
                        ),
                        'option_4' => array(
                            'name' => 'Top Right',
                            'value' => 'top_right',
            
                        ),
                    )),
                    'note' => '',
    
                )
            ); 


            // Enable Shake Effect
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'alternate child-floating_icon',
                    'label' => esc_attr__('Enable Shake Effect', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->shake_effect,
                    'name' => 'fast_cart_option[shake_effect]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Shake the icon when some items added into the cart. It is a reminder for your user to finish the checkout process.', 'fast-cart'),
                    'note' => '',
    
                )
            );

            // hide floating icon on empty cart
            WPXtension_Setting_Fields_Basic::checkbox(
                $options = array(
                    'tr_class' => 'child-floating_icon',
                    'label' => esc_attr__('Hide if empty', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->hide_on_empty_cart,
                    'name' => 'fast_cart_option[hide_on_empty_cart]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Hide the icon if cart is empty.', 'fast-cart'),
                    'note' => '',
    
                )
            );

        ?>
    </table>

</section>