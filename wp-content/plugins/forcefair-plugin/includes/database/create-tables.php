<?php

if (!defined('ABSPATH')) exit;

class FF_Create_Tables {

    public static function install() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $tables = [];

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_doctors (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT NOT NULL,
            crm VARCHAR(50),
            crm_state VARCHAR(10),
            specialty VARCHAR(100),
            commission_percentage DECIMAL(5,2),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_patients (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT NOT NULL,
            cpf VARCHAR(20),
            birth_date DATE,
            phone VARCHAR(20),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_prescriptions (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            doctor_id BIGINT NOT NULL,
            patient_id BIGINT NOT NULL,
            prescription_code VARCHAR(100) UNIQUE,
            file_url TEXT,
            status VARCHAR(20),
            expires_at DATETIME,
            validated_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_prescription_items (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            prescription_id BIGINT NOT NULL,
            product_id BIGINT NOT NULL,
            quantity INT,
            dosage VARCHAR(255)
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_pharmacies (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            cnpj VARCHAR(20),
            email VARCHAR(255),
            phone VARCHAR(20),
            address TEXT,
            active BOOLEAN DEFAULT TRUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_order_prescriptions (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT NOT NULL,
            prescription_id BIGINT NOT NULL,
            validated BOOLEAN DEFAULT FALSE,
            validated_at DATETIME
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_order_pharmacy (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT NOT NULL,
            pharmacy_id BIGINT NOT NULL,
            status VARCHAR(20),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}ff_commissions (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            doctor_id BIGINT NOT NULL,
            order_id BIGINT NOT NULL,
            amount DECIMAL(10,2),
            percentage DECIMAL(5,2),
            status VARCHAR(20),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        foreach ($tables as $sql) {
            dbDelta($sql);
        }
    }
}
