<?php

if (!defined('ABSPATH')) exit;

class FF_Prescriptions_Controller {

    public static function register_routes() {
        register_rest_route('forcefair/v1', '/prescriptions', [
            'methods' => 'POST',
            'callback' => [self::class, 'create'],
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('forcefair/v1', '/prescriptions/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'get'],
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ]);
    }

    public static function create($request) {
        global $wpdb;

        $table = $wpdb->prefix . 'ff_prescriptions';

        $data = [
            'doctor_id' => get_current_user_id(),
            'patient_id' => $request['patient_id'],
            'prescription_code' => uniqid('rx_'),
            'file_url' => $request['file_url'],
            'status' => 'pending',
            'created_at' => current_time('mysql')
        ];

        $wpdb->insert($table, $data);

        return [
            'success' => True,
            'id' => $wpdb->insert_id
        ];
    }

    public static function get($request) {
        global $wpdb;
        $table = $wpdb->prefix . 'ff_prescriptions';

        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $request['id'])
        );

        return $result;
    }
}
