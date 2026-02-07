<?php
/**
 * Order Tracking Template
 *
 * Overrides WooCommerce's default order/tracking.php
 * Shows a redesigned order tracking page with progress tracker,
 * product items, payment/delivery info, and order summary.
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

use RakmyatCore\Core\Order_Tracking;

$order_status   = $order->get_status();
$current_step   = Order_Tracking::get_order_progress_step( $order_status );
$order_date     = $order->get_date_created();
$order_id       = $order->get_order_number();

// Estimated delivery
$delivery_enabled = get_option( 'rmt_estimated_delivery_enabled', 'yes' );
$delivery_days    = (int) get_option( 'rmt_estimated_delivery_days', 14 );
$estimated_date   = null;
if ( $delivery_enabled === 'yes' && $order_date ) {
    $estimated_date = clone $order_date;
    $estimated_date->modify( '+' . $delivery_days . ' days' );
}

// Progress steps definition
$steps = [
    1 => [
        'label' => __( 'Order Confirmed', 'rakmyat-core' ),
        'icon'  => 'confirmed',
    ],
    2 => [
        'label' => __( 'Shipped', 'rakmyat-core' ),
        'icon'  => 'shipped',
    ],
    3 => [
        'label' => __( 'Out For Delivery', 'rakmyat-core' ),
        'icon'  => 'out-for-delivery',
    ],
    4 => [
        'label' => __( 'Delivered', 'rakmyat-core' ),
        'icon'  => 'delivered',
    ],
];

// Status date mapping — show date for completed steps
$status_dates = [];
$order_notes = wc_get_order_notes( [ 'order_id' => $order->get_id(), 'type' => 'internal' ] );
foreach ( $order_notes as $note ) {
    if ( strpos( $note->content, 'Order status changed' ) !== false || strpos( $note->content, 'order status changed' ) !== false ) {
        if ( strpos( $note->content, 'processing' ) !== false || strpos( $note->content, 'Processing' ) !== false ) {
            $status_dates[1] = $note->date_created;
        }
        if ( strpos( $note->content, 'shipped' ) !== false || strpos( $note->content, 'Shipped' ) !== false ) {
            $status_dates[2] = $note->date_created;
        }
        if ( strpos( $note->content, 'out-for-delivery' ) !== false || strpos( $note->content, 'Out For Delivery' ) !== false ) {
            $status_dates[3] = $note->date_created;
        }
        if ( strpos( $note->content, 'completed' ) !== false || strpos( $note->content, 'Completed' ) !== false ) {
            $status_dates[4] = $note->date_created;
        }
    }
}
// Step 1 fallback: use order date
if ( ! isset( $status_dates[1] ) && $current_step >= 1 ) {
    $status_dates[1] = $order_date;
}
?>

<div class="rmt-order-tracking">

    <?php if ( $current_step === -1 ) : ?>
        <!-- Cancelled / Refunded / Failed Badge -->
        <div class="rmt-ot-status-badge rmt-ot-status-<?php echo esc_attr( $order_status ); ?>">
            <?php echo esc_html( wc_get_order_status_name( $order_status ) ); ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="rmt-ot-header">
        <h1 class="rmt-ot-title">
            <?php printf( esc_html__( 'Order ID: #%s', 'rakmyat-core' ), esc_html( $order_id ) ); ?>
        </h1>
        <div class="rmt-ot-header-actions">
            <a href="#" class="rmt-ot-btn rmt-ot-btn-outline">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 1.333v2.667M12 1.333v2.667M1.333 6h13.334M2.667 2.667h10.666c.737 0 1.334.597 1.334 1.333v9.333c0 .737-.597 1.334-1.334 1.334H2.667c-.737 0-1.334-.597-1.334-1.334V4c0-.736.597-1.333 1.334-1.333z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php esc_html_e( 'Invoice', 'rakmyat-core' ); ?>
            </a>
            <a href="#" class="rmt-ot-btn rmt-ot-btn-filled">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 7.267A6.667 6.667 0 1 1 8.733 2L14 7.267z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2v5.333H8.667" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php esc_html_e( 'Track Order', 'rakmyat-core' ); ?>
            </a>
        </div>
    </div>

    <!-- Meta Row -->
    <div class="rmt-ot-meta">
        <div class="rmt-ot-meta-item">
            <span class="rmt-ot-meta-label"><?php esc_html_e( 'Order date:', 'rakmyat-core' ); ?></span>
            <span class="rmt-ot-meta-value">
                <?php echo $order_date ? esc_html( $order_date->date_i18n( get_option( 'date_format' ) ) ) : '—'; ?>
            </span>
        </div>
        <?php if ( $estimated_date && $current_step >= 0 && $current_step < 4 ) : ?>
            <div class="rmt-ot-meta-item">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 5.333V8l1.667 1.667M14 8A6 6 0 1 1 2 8a6 6 0 0 1 12 0z" stroke="#22C55E" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="rmt-ot-meta-label"><?php esc_html_e( 'Estimated delivery:', 'rakmyat-core' ); ?></span>
                <span class="rmt-ot-meta-value rmt-ot-meta-delivery">
                    <?php echo esc_html( $estimated_date->date_i18n( get_option( 'date_format' ) ) ); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <?php if ( $current_step >= 0 ) : ?>
    <!-- Progress Tracker -->
    <div class="rmt-ot-progress">
        <?php foreach ( $steps as $step_num => $step ) :
            $is_completed = $current_step >= $step_num;
            $is_active    = $current_step === $step_num;
            $step_class   = $is_completed ? 'completed' : 'pending';
            if ( $is_active ) $step_class .= ' active';
        ?>
            <div class="rmt-ot-step rmt-ot-step-<?php echo esc_attr( $step_class ); ?>">
                <div class="rmt-ot-step-dot">
                    <?php if ( $is_completed ) : ?>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 7l2.5 2.5L11 4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <?php endif; ?>
                </div>
                <span class="rmt-ot-step-label"><?php echo esc_html( $step['label'] ); ?></span>
                <?php if ( isset( $status_dates[ $step_num ] ) ) : ?>
                    <span class="rmt-ot-step-date">
                        <?php echo esc_html( $status_dates[ $step_num ]->date_i18n( 'M d, Y' ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Product Items -->
    <div class="rmt-ot-products">
        <h2 class="rmt-ot-section-title"><?php esc_html_e( 'Product', 'rakmyat-core' ); ?></h2>
        <?php foreach ( $order->get_items() as $item_id => $item ) :
            $product   = $item->get_product();
            $image_url = '';
            $sku       = '';

            if ( $product ) {
                $image_id  = $product->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
                $sku       = $product->get_sku();
            }
        ?>
            <div class="rmt-ot-product-item">
                <div class="rmt-ot-product-image">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $item->get_name() ); ?>" />
                </div>
                <div class="rmt-ot-product-info">
                    <div class="rmt-ot-product-name"><?php echo esc_html( $item->get_name() ); ?></div>
                    <?php if ( $sku ) : ?>
                        <div class="rmt-ot-product-sku"><?php printf( esc_html__( 'SKU: %s', 'rakmyat-core' ), esc_html( $sku ) ); ?></div>
                    <?php endif; ?>
                    <?php
                    // Display item meta (variation attributes, etc.)
                    $item_meta = $item->get_formatted_meta_data( '' );
                    if ( $item_meta ) : ?>
                        <div class="rmt-ot-product-meta">
                            <?php foreach ( $item_meta as $meta ) : ?>
                                <span class="rmt-ot-product-meta-item">
                                    <?php echo wp_kses_post( $meta->display_key ); ?>: <?php echo wp_kses_post( $meta->display_value ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="rmt-ot-product-price-qty">
                    <div class="rmt-ot-product-price"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></div>
                    <div class="rmt-ot-product-qty"><?php printf( esc_html__( 'Qty: %s', 'rakmyat-core' ), esc_html( $item->get_quantity() ) ); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Payment & Delivery -->
    <div class="rmt-ot-info-row">
        <div class="rmt-ot-info-col">
            <h2 class="rmt-ot-section-title"><?php esc_html_e( 'Payment', 'rakmyat-core' ); ?></h2>
            <div class="rmt-ot-payment-method">
                <?php echo esc_html( $order->get_payment_method_title() ); ?>
            </div>
        </div>
        <div class="rmt-ot-info-col">
            <h2 class="rmt-ot-section-title"><?php esc_html_e( 'Delivery', 'rakmyat-core' ); ?></h2>
            <div class="rmt-ot-delivery-address">
                <?php
                $shipping_address = $order->get_formatted_shipping_address();
                if ( $shipping_address ) {
                    echo wp_kses_post( $shipping_address );
                } else {
                    echo wp_kses_post( $order->get_formatted_billing_address() );
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Need Help & Order Summary -->
    <div class="rmt-ot-info-row">
        <div class="rmt-ot-info-col">
            <h2 class="rmt-ot-section-title"><?php esc_html_e( 'Need Help?', 'rakmyat-core' ); ?></h2>
            <ul class="rmt-ot-help-links">
                <li>
                    <a href="#">
                        <span class="rmt-ot-help-icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.5a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15z" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.817 6.75a2.25 2.25 0 0 1 4.373.75c0 1.5-2.25 2.25-2.25 2.25M9 12.75h.008" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><?php esc_html_e( 'Order Issues', 'rakmyat-core' ); ?></span>
                        <span class="rmt-ot-help-arrow">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12l4-4-4-4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="rmt-ot-help-icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.25 6.75l6.75 4.5 6.75-4.5M3.75 3h10.5c.825 0 1.5.675 1.5 1.5v9c0 .825-.675 1.5-1.5 1.5H3.75c-.825 0-1.5-.675-1.5-1.5v-9c0-.825.675-1.5 1.5-1.5z" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><?php esc_html_e( 'Delivery Info', 'rakmyat-core' ); ?></span>
                        <span class="rmt-ot-help-arrow">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12l4-4-4-4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="rmt-ot-help-icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.25 1.5H6.75l-5.25 6L9 16.5l7.5-9-5.25-6z" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><?php esc_html_e( 'Returns & Refunds', 'rakmyat-core' ); ?></span>
                        <span class="rmt-ot-help-arrow">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12l4-4-4-4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="rmt-ot-info-col">
            <h2 class="rmt-ot-section-title"><?php esc_html_e( 'Order Summary', 'rakmyat-core' ); ?></h2>
            <table class="rmt-ot-summary-table">
                <?php
                $totals = $order->get_order_item_totals();
                if ( $totals ) :
                    foreach ( $totals as $key => $total ) :
                ?>
                    <tr class="rmt-ot-summary-row <?php echo esc_attr( $key ); ?>">
                        <th><?php echo esc_html( $total['label'] ); ?></th>
                        <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                    </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </table>
        </div>
    </div>

</div>
