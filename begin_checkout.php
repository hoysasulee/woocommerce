add_action( 'woocommerce_checkout_init', 'push_begin_checkout_to_datalayer' );
function push_begin_checkout_to_datalayer() {
    $products = array_map(function($cart_item) {
        $product = $cart_item['data'];
        return [
            'name' => $product->get_name(),
            'id' => $product->get_id(),
            'price' => $product->get_price(),
            'quantity' => $cart_item['quantity'],
        ];
    }, WC()->cart->get_cart());
    
    ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'begin_checkout',
            'ecommerce': {
                'currencyCode': '<?php echo get_woocommerce_currency(); ?>',
                'checkout': {
                    'actionField': {'step': 1},
                    'products': <?php echo json_encode( $products ); ?>
                }
            }
        });
    </script>
    <?php
}
