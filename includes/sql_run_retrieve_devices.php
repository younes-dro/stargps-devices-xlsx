<?php

/**
 * 
 */

function get_gpswow_devices( $url, $api_hash = '', $app ){
    
	global $wpdb;
	$table_devices = $wpdb->prefix.'stargps_devices_management';
    
		$w_stargps_ma_api = $url . '?user_api_hash='. $api_hash .'&lang=fr';
                
//                echo $w_stargps_ma_api;
                
//                exit();
                
		$response_arr  = wp_remote_get( $w_stargps_ma_api );
		$body = json_decode( wp_remote_retrieve_body ( $response_arr ), true );
                
		if( stargps_device_management_check_api_errors( $response_arr ) ){
                    echo '<pre>';
                    var_dump( $response_arr );
                    echo '</pre>';
                    
                    exit();
		}
                
                $c = count( $body[0]['items'] );
//                var_dump($c);
                $html = "";
                $number_devices_changed_sim = 0;
                $number_new_devices = 0;
                $number_update_devices = 0;
		for ( $i = 0; $i < $c ; $i++  ) {

			/**
			*  Verification sim number
			*/ 
                        $result_devices_changed_sim_no = stargps_device_management_check_sim_no( $body[0]['items'][$i]['device_data']['imei'], $body[0]['items'][$i]['device_data']['sim_number'], 'gpswox' );
                        if ( $result_devices_changed_sim_no ){
                            $number_devices_changed_sim++;
                            $html .= stargps_device_management_devices_change_sim_table( $result_devices_changed_sim_no, $body[0]['items'][$i]['device_data']['sim_number'] );
                           
                        }
                        
                        /**
                         * New Device
                         */                       
                        if( stargps_device_management_check_is_new_device( $body[0]['items'][$i]['device_data']['imei'] ) ){
                            $date_recharge = date( "Y-m-d", strtotime ( $body[0]['items'][$i]['device_data']['updated_at'] ) );
                            $effectiveDate = strtotime( "+3 months", strtotime( $date_recharge ) );
                            $next_recharge = date( 'Y-m-d', $effectiveDate );                             
                            $data=array(
                            'customer_name' => $body[0]['items'][$i]['device_data']['users'][1]['email'], 
                            'b' => '',
                            'login' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'num_tel_ctl' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'target_name' => $body[0]['items'][$i]['device_data']['name'],
                            'imei' => $body[0]['items'][$i]['device_data']['imei'],
                            'sim_no' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'type_device' => $body[0]['items'][$i]['device_data']['device_model'],
                            'expiry' => $body[0]['items'][$i]['device_data']['expiration_date'], 
                            'sim_operator' => '',
                            'date_recharge' => $date_recharge,
                            'next_recharge' => $next_recharge,
                            'application' => "gpswox",
                            'url' => $app,
                            'remarks' => "" );  
                            if( $wpdb->insert( $table_devices, $data) ){
                                $number_new_devices++;
                            }
                            
                        }
                        
                        
                        /**
                         * Update device : Device exists AND sim no has NOT benn changed
                         * 
                         * Il faut pas ecraser la date recharge !!!!!!
                         */
                        $result_devices_update = stargps_device_management_update_devies( $body[0]['items'][$i]['device_data']['imei'], $body[0]['items'][$i]['device_data']['sim_number'] );
                        if ( $result_devices_update ){

                            //$date_recharge = date( "Y-m-d", strtotime ( $body[0]['items'][$i]['device_data']['updated_at'] ) );
                            //$effectiveDate = strtotime( "+3 months", strtotime( $date_recharge ) );
                            //$next_recharge = date( 'Y-m-d', $effectiveDate );                             
                            $data=array(
                            'customer_name' => $body[0]['items'][$i]['device_data']['users'][1]['email'], 
                            'b' => '',
                            'login' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'num_tel_ctl' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'target_name' => $body[0]['items'][$i]['device_data']['name'],
                            'imei' => $body[0]['items'][$i]['device_data']['imei'],
                            'sim_no' => $body[0]['items'][$i]['device_data']['sim_number'],
                            'type_device' => $body[0]['items'][$i]['device_data']['device_model'],
                            'expiry' => $body[0]['items'][$i]['device_data']['expiration_date'], 
                            'sim_operator' => ''


                            );  

                            $wpdb->update( $table_devices, $data , array( 'imei' => $body[0]['items'][$i]['device_data']['imei'] ) );
                            $number_update_devices++;
                            
                           
                        }                        
                        
		}
                echo '<h2>' . $url . '</h2>';
                if( ! empty( $html ) ){
                    $table = stargps_device_management_head_table( );
                    $tbody = '<tbody id="the-list">';
                    $close_tbody = '</tbody></table>';
                    echo '<h2>SIM NO à mettre à jour : ( '.$number_devices_changed_sim.' )</h2>'.$table . $tbody . $html .$close_tbody  ; 
                }
                
                echo '<h2>Journal: </h2>';
                echo '<p>SIM Number modifié: ' . $number_devices_changed_sim . '</p>';
                echo '<p>Nouveaux Devices: ' . $number_new_devices . '</p>';
                echo '<p>Updated Devices: ' . $number_update_devices . '</p>';
                
                echo 'done';    
    
    
}

function get_traccar_devices( $url, $email, $password , $app ='' ){
    
	global $wpdb;
	$table_devices = $wpdb->prefix.'stargps_devices_management';

	$args = array(
                    'headers' => array(

                        'Authorization' => 'Basic ' . base64_encode( $email . ':' . $password )
                        )
	);
                
	$response_arr  = wp_remote_get( $url , $args );
	$body = json_decode( wp_remote_retrieve_body ( $response_arr ), true );
    
	if( stargps_device_management_check_api_errors( $response_arr ) ){
            
		echo '<pre>';
		var_dump( $response_arr );
		echo '</pre>';
                    
		exit();
	}
        
	$c = count( $body );
                
	$html = "";
	$number_devices_changed_sim = 0;
	$number_new_devices = 0;
	$number_update_devices = 0;        
        
		for ( $i = 0; $i < $c ; $i++  ) {

			/**
			*  Verification sim number
			*/ 
                        $result_devices_changed_sim_no = stargps_device_management_check_sim_no( $body[$i]['uniqueId'], $body[$i]['phone'], 'traccar' );
                        if ( $result_devices_changed_sim_no ){
                            $number_devices_changed_sim++;
                            $html .= stargps_device_management_devices_change_sim_table( $result_devices_changed_sim_no, $body[$i]['phone'] );
                           
                        }
                        
                        /**
                         * New Device
                         */                       
                        if( stargps_device_management_check_is_new_device( $body[$i]['uniqueId'] ) ){
                            $date_recharge = date( "Y-m-d", strtotime ( $body[$i]['lastUpdate'] ) );
                            $effectiveDate = strtotime( "+3 months", strtotime( $date_recharge ) );
                            $next_recharge = date( 'Y-m-d', $effectiveDate );                             
                            $data=array(
                            'customer_name' => $body[$i]['contact'], 
                            'b' => '',
                            'login' => '',
                            'num_tel_ctl' => $body[$i]['phone'],
                            'target_name' => $body[$i]['name'],
                            'imei' => $body[$i]['uniqueId'],
                            'sim_no' => $body[$i]['phone'],
                            'type_device' => $body[$i]['model'],
                            'expiry' => '', 
                            'sim_operator' => '',
                            'date_recharge' => $date_recharge,
                            'next_recharge' => $next_recharge,
                            'application' => "Traccar",
                            'url' => $app,
                            'remarks' => "" );  
                            
                            if( $wpdb->insert( $table_devices, $data ) ){
                                $number_new_devices++;
                            }

                            
                        }
                        
                        
                        /**
                         * Update device : Device exists AND sim no has NOT benn changed
                         * 
                         * Il faut pas ecraser la date recharge !!!!!!
                         */
                        $result_devices_update = stargps_device_management_update_devies( $body[$i]['uniqueId'], $body[$i]['phone'] );
                        if ( $result_devices_update ){

                            //$date_recharge = date( "Y-m-d", strtotime ( $body[0]['items'][$i]['device_data']['updated_at'] ) );
                            //$effectiveDate = strtotime( "+3 months", strtotime( $date_recharge ) );
                            //$next_recharge = date( 'Y-m-d', $effectiveDate );                             
                            $data=array(
                            'customer_name' => $body[$i]['contact'], 
                            'b' => '',
                            'login' => '',
                            'num_tel_ctl' => $body[$i]['phone'],
                            'target_name' => $body[$i]['name'],
                            'imei' => $body[$i]['uniqueId'],
                            'sim_no' => $body[$i]['phone'],
                            'type_device' => $body[$i]['model'],
                            'expiry' => '', 
                            'sim_operator' => ''


                            );  

                            $wpdb->update( $table_devices, $data , array( 'imei' => $body[$i]['uniqueId'] ) );
                            $number_update_devices++;
                            
                           
                        }                        
                        
		}
                echo '<h2>' . $url . '</h2>';
                if( ! empty( $html ) ){
                    $table = stargps_device_management_head_table( );
                    $tbody = '<tbody id="the-list">';
                    $close_tbody = '</tbody></table>';
                    echo '<h2>SIM NO à mettre à jour : ( '.$number_devices_changed_sim.' )</h2>'.$table . $tbody . $html .$close_tbody  ; 
                }
                
                echo '<h2>Journal: </h2>';
                echo '<p>SIM Number modifié: ' . $number_devices_changed_sim . '</p>';
                echo '<p>Nouveaux Devices: ' . $number_new_devices . '</p>';
                echo '<p>Updated Devices: ' . $number_update_devices . '</p>';
                
                echo 'done';
    
    
}

