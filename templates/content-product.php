<?php
/**
 * Glassmorphic Product Card Template
 * Overrides WooCommerce content-product.php
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Get product data
$product_id    = $product->get_id();
$product_link  = $product->get_permalink();
$product_title = $product->get_title();
$product_price = $product->get_price_html();
$product_image = $product->get_image( 'woocommerce_thumbnail' );
$in_stock      = $product->is_in_stock();
$add_to_cart_url = $product->add_to_cart_url();

// Get vendor info if marketplace plugin exists
$vendor_name = '';
$vendor_rating = '';
if ( function_exists( 'wcfm_get_vendor_id_by_post' ) ) {
    $vendor_id = wcfm_get_vendor_id_by_post( $product_id );
    if ( $vendor_id ) {
        $vendor_data = get_userdata( $vendor_id );
        $vendor_name = $vendor_data ? $vendor_data->display_name : '';
    }
} elseif ( function_exists( 'dokan_get_vendor_by_product' ) ) {
    $vendor = dokan_get_vendor_by_product( $product );
    if ( $vendor ) {
        $vendor_name = $vendor->get_shop_name();
    }
}
?>

<li <?php wc_product_class( 'glass-prod-card', $product ); ?>>
    <div class="prod-inner">

        <div class="prod-image">
            <a href="<?php echo esc_url( $product_link ); ?>">
                <?php echo $product_image; ?>
            </a>

            <?php if ( $product->is_on_sale() ) : ?>
                <span class="prod-sale-badge">Sale</span>
            <?php endif; ?>

            <?php
            // Wishlist button - WCBoost Wishlist
            if ( function_exists( 'wcboost_wishlist' ) ) : ?>
                <div class="prod-wishlist">
                    <?php echo do_shortcode( '[wcboost_wishlist_button]' ); ?>
                </div>
            <?php elseif ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) : ?>
                <div class="prod-wishlist">
                    <?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="prod-info">
            <h3 class="prod-title">
                <a href="<?php echo esc_url( $product_link ); ?>">
                    <?php echo esc_html( $product_title ); ?>
                </a>
            </h3>

            <div class="prod-safety-center">
                <span class="safety-text">Safety Center</span>
                <span class="safety-rating">(4.9 <span class="star">⭐</span>)</span>
            </div>

            <div class="prod-stock">
                <?php if ( $in_stock ) : ?>
                    <span class="stock-status in-stock">In Stock</span>
                <?php else : ?>
                    <span class="stock-status out-of-stock">Out of Stock</span>
                <?php endif; ?>
            </div>

            <div class="prod-price">
                <span class="price-label">Price :</span>
                <?php echo $product_price; ?>
            </div>

            <div class="prod-actions">
                <?php if ( $product->is_purchasable() && $in_stock ) : ?>
                    <a href="<?php echo esc_url( $add_to_cart_url ); ?>"
                       data-product_id="<?php echo esc_attr( $product_id ); ?>"
                       data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
                       data-quantity="1"
                       class="rmt-btn rmt-btn-cart add_to_cart_button ajax_add_to_cart"
                       aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'woocommerce' ), $product_title ) ); ?>">
                        <span class="btn-icon"><img src="<?php echo esc_url( RMT_URL . 'assets/img/add-to-cart.svg' ); ?>" alt=""></span>
                        <span class="btn-text">Add to Cart</span>
                        <span class="btn-loading"></span>
                    </a>

                    <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product_id, wc_get_checkout_url() ) ); ?>"
                       class="rmt-btn rmt-btn-buy"
                       data-product_id="<?php echo esc_attr( $product_id ); ?>">
                        Buy Now
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url( $product_link ); ?>" class="rmt-btn rmt-btn-view">
                        View Product
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</li>
