<section class="advanced" id="fc-advanced-section">

    <h3><?php echo esc_attr__( 'Tray Style', 'fast-cart' ); ?></h3>
    <table class="widefat wpx-table">

        <?php 

            // Cart Tray Mode
            WPXtension_Setting_Fields_Basic::select(
                $options = array(
                    'tr_class' => 'alternate parent',
                    'label' => esc_attr__('Mode', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->mode,
                    'name' => 'fast_cart_option_styling[mode]',
                    'option' => apply_filters('fcw_mode_option', array(
                        'option_1' => array(
                            'name' => 'Light',
                            'value' => 'light',
            
                        ),
                        'option_2' => array(
                            'name' => 'Dark',
                            'value' => 'dark',
            
                        ),
                    )),
                    'note' => __('You can set your desired light/dark color from below. Initially, #ffffff for light and #222222 for dark.', 'fast-cart'),
    
                )
            ); 
            // Light Color
            WPXtension_Setting_Fields_Basic::color(
                $options = array(
                    'tr_class' => 'child light new',
                    'label' => esc_attr__('Light Mode Color', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->light_mode_color,
                    'name' => 'fast_cart_option_styling[light_mode_color]',
                    'default_value' => Fast_Cart::get_options()->light_mode_color,
                    'note' => '',
                    'tag' => 'New'
    
                )
            ); 

            // Dark Color
            WPXtension_Setting_Fields_Basic::color(
                $options = array(
                    'tr_class' => 'child dark new',
                    'label' => esc_attr__('Dark Mode Color', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->dark_mode_color,
                    'name' => 'fast_cart_option_styling[dark_mode_color]',
                    'default_value' => Fast_Cart::get_options()->dark_mode_color,
                    'note' => '',
                    'tag' => 'New'
    
                )
            );

            // Color Inside tray
            WPXtension_Setting_Fields_Basic::color(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Color', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->color,
                    'name' => 'fast_cart_option_styling[color]',
                    'default_value' => Fast_Cart::get_options()->color,
                    'note' => '',
    
                )
            ); 

            // Tray bottom color
            WPXtension_Setting_Fields_Basic::color(
                $options = array(
                    'tr_class' => 'new',
                    'label' => esc_attr__('Tray Bottom Color', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->tray_bottom_color,
                    'name' => 'fast_cart_option_styling[tray_bottom_color]',
                    'default_value' => Fast_Cart::get_options()->tray_bottom_color,
                    'note' => '',
                    'tag' => 'New'
    
                )
            ); 

            // Tray bottom border color
            WPXtension_Setting_Fields_Basic::color(
                $options = array(
                    'tr_class' => 'alternate new',
                    'label' => esc_attr__('Tray Bottom Border Color', 'fast-cart'),
                    'value' => Fast_Cart::get_options()->tray_bottom_border_color,
                    'name' => 'fast_cart_option_styling[tray_bottom_border_color]',
                    'default_value' => Fast_Cart::get_options()->tray_bottom_border_color,
                    'note' => '',
                    'tag' => 'New'
    
                )
            );

        ?>
        <tr class="" valign="top" data-new-tag="New">

            <td class="row-title" scope="row">
                <label for="tablecell">
                    <?php echo esc_attr__('Buttons Style', 'fast-cart'); ?>
                </label>   
            </td>
            <td>
                <div class="buttons-style">
                    <label>
                        <input type="radio" name="fast_cart_option_styling[buttons_style]" value="inline" <?php echo ( Fast_Cart::get_options()->buttons_style === 'inline' ) ? "checked" : ""; ?> />
                        <span>
                            <img src="<?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
                            echo esc_url( plugins_url('admin/images/inline.png', FAST_CART_PLUGIN_FILE) ); ?>" alt="Inline Button" width="40px" />
                        </span>
                    </label>
                    <label>
                        <input type="radio" name="fast_cart_option_styling[buttons_style]" value="full" <?php echo ( Fast_Cart::get_options()->buttons_style === 'full' ) ? "checked" : ""; ?> />
                        <span>
                            <img src="<?php  // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
                            echo esc_url( plugins_url('admin/images/full.png', FAST_CART_PLUGIN_FILE) ); ?>" alt="Full Button" width="40px" />
                        </span>
                    </label>
                </div>
            </td>

        </tr>



    </table>

</section>