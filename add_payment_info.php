add_action( 'woocommerce_review_order_before_payment', 'push_add_payment_info_to_datalayer' );
function push_add_payment_info_to_datalayer() {
    ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'add_payment_info',
            'ecommerce': {
                'currencyCode': '<?php echo get_woocommerce_currency(); ?>',
                'checkout': {
                    'actionField': {'step': 2}
                }
            }
        });
    </script>
    <?php
}
