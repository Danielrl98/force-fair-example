<?php

if (!defined('ABSPATH')) exit;

class FF_WooCommerce_Hooks {

    public static function init() {
        add_action('woocommerce_checkout_order_processed', [self::class, 'handle_order'], 10, 1);
    }

    public static function handle_order($order_id) {
        global $wpdb;

        $doctor_id = get_post_meta($order_id, '_ff_doctor_id', true);

        if (!$doctor_id) return;

        $commission = 10.00;

        $wpdb->insert(
            $wpdb->prefix . 'ff_commissions',
            [
                'doctor_id' => $doctor_id,
                'order_id' => $order_id,
                'amount' => $commission,
                'percentage' => 10,
                'status' => 'pending'
            ]
        );
    }
}

FF_WooCommerce_Hooks::init();
