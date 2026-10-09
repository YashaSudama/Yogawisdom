<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="heading-order">
    <h2><?= __('<span class="first-letter">T</span>hank you! <span class="first-letter">Y</span>our order has been successfully placed</span>', 'yootheme-child'); ?></h2>
</div>
<div class="content-order">
    <p><?= __('We are already processing your order and will contact you shortly to confirm the details.', 'yootheme-child'); ?></p>
</div>
<div class="button-order">
    <a class="uk-button uk-button-primary" href="/">
        <?= __('Return to home page', 'yootheme-child'); ?>
        <span uk-icon="arrow-right" 
                class="uk-icon">
        </span>
    </a>
</div>
