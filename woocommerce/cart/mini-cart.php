<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="content-mini-cart">
    <span class="text-content-mini-cart"><?=__('Cart', 'yootheme-child'); ?></span>
    <img class="icon-content-mini-cart" src="/wp-content/uploads/2025/10/paramatma-close.svg">
</div>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && 
                 apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?= esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					
                    <div class="background-block"></div>
                    <div class="block-1"><?= $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

                    <div class="block-2">
                        <div class="sub-block-1">
                            <span><?= wp_kses_post( $product_name ); ?></span>
                            <button class="remove-from-cart" data-product_id="<?= esc_attr( $product_id ); ?>" data-key="<?= esc_attr( $cart_item_key ); ?>">
                                <img src="/wp-content/uploads/2025/10/paramatma-delete.svg">
                            </button>
                        </div>
                        <div class="sub-block-2">
                            <div class="mini-cart-price">
                                <span class="price"><?= $product_price; ?></span>
                                <span><?= '1 ' . __('item', 'yootheme-child'); ?></span>
                            </div>
                             <div class="mini-cart-quantity">
                                <button class="qty-minus" data-key="<?= esc_attr( $cart_item_key ); ?>">
                                    <img src="/wp-content/uploads/2025/10/paramatma-minus.svg">
                                </button>
                                <input name="quantity-product" 
                                       type="number" 
                                       class="qty-input" 
                                       value="<?= esc_attr( $cart_item['quantity'] ); ?>" 
                                       min="1" 
                                       data-key="<?= esc_attr( $cart_item_key ); ?>"/>
                                <button class="qty-plus" data-key="<?= esc_attr( $cart_item_key ); ?>">
                                    <img src="/wp-content/uploads/2025/10/paramatma-plus.svg">
                                </button>
                            </div>
                        </div>
                    </div>
				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );

		?>
	</ul>
    
    <div class="mini-cart-total">
        <div class="woocommerce-mini-cart__total total">
            <?php
            /**
             * Hook: woocommerce_widget_shopping_cart_total.
             *
             * @hooked woocommerce_widget_shopping_cart_subtotal - 10
             */
            ?> 

            <?php do_action( 'woocommerce_widget_shopping_cart_total' ); ?>
            
        </div>

        <a class="mini-cart-content-checkout uk-button uk-button-primary"
           href="<?= esc_url( wc_get_checkout_url() ); ?>">
            <?= __('Place an order', 'yootheme-child'); ?>				
            <span uk-icon="arrow-right" class="uk-icon">
        </a>
    </div>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
