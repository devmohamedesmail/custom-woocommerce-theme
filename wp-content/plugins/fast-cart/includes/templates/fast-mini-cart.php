<?php
/**
 * Fast-Mini-cart
 *
 *
 * This template can be overridden by copying it to yourtheme/fast-cart/fast-mini-cart.php.
 *
 * HOWEVER, on occasion Fast Cart will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.5
 */

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'list_class' => '',
);


$args = wp_parse_args( $args, $defaults );


do_action( 'fc_woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<?php do_action( 'fc_before_ul' ); ?>

	<ul class="woocommerce-fast-cart <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		$cart_items = apply_filters( 'fc_cart_items_array', WC()->cart->get_cart() );

		$empty_cart = !empty( get_option('fast_cart_option') ) ? Fast_Cart::get_options()->empty_cart : 'yes';

		if( $empty_cart === 'yes' ){
		?>
			<li class="fc-woocommerce-cart-item fc-cart-item-empty-cart"><a href="#"><?php echo esc_html__( 'Empty Cart', 'fast-cart' ) ?></a></li>

		<?php
		}

		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 80, 80 ) ), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

				// Link Behavior
				$link_behavior = ( fast_cart()->get_options()->link_behavior === 'open_in_new_tab' ) ? 'target="_blank"' : '';

				?>
				<li class="fc-woocommerce-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'fc_mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="fc-cart-item">
						<div class="thumbnail">
							<?php if ( empty( $product_permalink ) || fast_cart()->get_options()->link_behavior === 'no_link' ) : ?>
								<?php echo wp_kses_post( $thumbnail ); ?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>" <?php echo esc_attr($link_behavior); ?> >
									<?php echo wp_kses_post( $thumbnail ); ?>
								</a>
							<?php endif; ?>
						</div>
						<div class="product">
							<span class="name">
								<?php if ( empty( $product_permalink ) || fast_cart()->get_options()->link_behavior === 'no_link' ) : ?>
									<?php echo wp_kses_post( $product_name ) ?>
								<?php else : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>" <?php echo esc_attr($link_behavior); ?>>
										<?php echo wp_kses_post( $product_name ); ?>
									</a>
								<?php endif; ?>
							</span>
							<?php echo wp_kses_post( wc_get_formatted_cart_item_data( $cart_item ) ); // Formatted cart item to display size if any variation combination ?>
							<span class="price">
								<?php echo ( !empty( get_option('fast_cart_option') ) && fast_cart()->get_options()->regular_price === 'no' ) ? wp_kses_post($product_price) : wp_kses_post($_product->get_price_html()); ?>
							</span>
						</div>
						<div class="fc-quantity">
							<?php 
							// echo apply_filters( 'fc_woocommerce_widget_cart_item_quantity', '
							// 		<span class="fc-quantity-inner">' . sprintf( '%s &times; %s', esc_attr($cart_item['quantity']), 
							// 		wp_kses_post($product_price) ) . '</span>', 
							// 		$cart_item, 
							// 		esc_attr($cart_item_key) 
							// 	);
							echo wp_kses( 
								apply_filters( 'fc_woocommerce_widget_cart_item_quantity', '
									<span class="fc-quantity-inner">' . sprintf( '%s &times; %s', esc_attr($cart_item['quantity']), 
									wp_kses_post($product_price) ) . '</span>', 
									$cart_item, 
									esc_attr($cart_item_key) 
								),
								array(
									'span' => array( 
										'class' => array( 'fc-quantity-inner' ) 
									),
									'div' => array( 
										'class' => array(),
										'data-key' => array()
									),
									'label' => array( 
										'class' => array(),
										'for' => array()
									),
									'input' => array(
										'type' => array(),
										'id' => array(),
										'class' => array(),
										'name' => array(),
										'value' => array(),
										'aria-label' => array(),
										'size' => array(),
										'min' => array(),
										'max' => array(),
										'step' => array(),
										'placeholder' => array(),
										'inputmode' => array(),
										'autocomplete' => array()
									),
									'button' => array( 
										'class' => array(),
										'type' => array()
									)
								)
							); 
							?>
						</div>
					</div>
					<?php if( fast_cart()->get_options()->remove_btn === 'no' ): ?>
						<div class="fc-cart-li-remove">
							<?php
							echo wp_kses_post( apply_filters(
								'fc_woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="fc_remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr__( 'Remove this item', 'fast-cart' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $_product->get_sku() ),
									apply_filters( 'fc_cart_remove_item_icon', '<span class="fc-icon-trash"></span>' )
								),
								$cart_item_key
							) );
							?>
						</div>
					<?php endif; ?>
					
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<?php do_action( 'fc_after_ul' ); ?>

	<div class="fc-bottom-part">
		<div class="woocommerce-mini-cart__total total">
			<?php
			/**
			 * Hook: woocommerce_widget_shopping_cart_total.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action( 'fc_woocommerce_widget_shopping_cart_total' );
			// do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>

		<?php do_action( 'fc_woocommerce_widget_shopping_cart_before_buttons' ); ?>

		<p class="fc-woocommerce-mini-cart__buttons buttons <?php echo "fc_buttons_".esc_html( fast_cart()->get_options()->buttons_style ); ?> "><?php do_action( 'fc_woocommerce_widget_shopping_cart_buttons' ); ?></p>

		<?php do_action( 'fc_woocommerce_widget_shopping_cart_after_buttons' ); ?>
	</div>

<?php else : ?>

	<p class="fc-woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'fast-cart' ); ?></p>

<?php endif; ?>

<?php do_action( 'fc_woocommerce_after_mini_cart' ); ?>