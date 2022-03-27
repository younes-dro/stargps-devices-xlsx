<?php
/**
 * 
 */

class Stargps_Devices_Management_Deactivator{
    
    
	public static function deactivate() {
            
         global $wpdb;
         $table_name = $wpdb->prefix . 'stargps_devices_management';
	 $table_api = $wpdb->prefix . 'stargps_devices_management_api';
	  //$wpdb->query( 'DROP TABLE ' . $table_name  );
	  //$wpdb->query( 'DROP TABLE ' . $table_api );
	}    
    
}