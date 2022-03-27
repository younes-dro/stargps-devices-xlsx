<?php
/**
 * 
 */

class Stargps_Devices_Management_Activator{
    
    
	public static function activate() {
            
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
                
		$table_name = $wpdb->prefix . 'stargps_devices_management';
		$table_api = $wpdb->prefix . 'stargps_devices_management_api';
                
		$sql_devices = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        customer_name varchar(50),                        
                        b varchar(100),
                        login varchar(100),
                        num_tel_ctl varchar(100),
                        target_name varchar(100),
                        imei bigint(20),
                        sim_no varchar(50),
                        type_device varchar(20),
                        expiry date,
                        sim_operator varchar(20),
                        date_recharge date,
                        next_recharge date,
                        application varchar(20),
                        url varchar(100),
                        remarks text,
			UNIQUE KEY id (id)
		) $charset_collate;";
                
		$sql_api = "CREATE TABLE IF NOT EXISTS $table_api (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        email varchar(50),                        
                        password varchar(100),
                        app varchar(100),
                        url varchar(255),
                        type varchar(100),
                        token text,
                        status varchar(100),
			UNIQUE KEY id (id)
		) $charset_collate;";                

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_devices );
                dbDelta( $sql_api );
                update_option( 'stargps-devices-management_uuid_file_name', wp_generate_uuid4() );
	}    
    
}
