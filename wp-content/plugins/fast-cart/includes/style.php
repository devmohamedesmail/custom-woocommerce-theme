<?php

if( !function_exists( 'fast_cart_css_variables' ) ){

	add_action( 'wp_head',  'fast_cart_css_variables');

	function fast_cart_css_variables(){

		?>

		<style type="text/css">

			:root {
				--fs-mode: <?php echo ( fast_cart()->get_options()->mode === 'light' ) ? esc_attr( fast_cart()->get_options()->light_mode_color ) : esc_attr( fast_cart()->get_options()->dark_mode_color ); ?>;
			  	--fs-color: <?php echo esc_attr( fast_cart()->get_options()->color ); ?>;
			  	--fs-tray-bottom-color: <?php echo esc_attr( fast_cart()->get_options()->tray_bottom_color ); ?>;
			  	--fs-tray-bottom-border-color: <?php echo esc_attr( fast_cart()->get_options()->tray_bottom_border_color ); ?>;
			  	--fs-content-tray-width: 460px;
			  	--fs-content-tray-height: <?php echo ( fast_cart()->get_options()->position === "tray_left" || fast_cart()->get_options()->position === "tray_right" ) ? "100%" : "560px"; ?>;;
			}

		</style>

		<?php


	}

}

