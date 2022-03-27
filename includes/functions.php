<?php

/*
* common functions file.
*/

function  stargps_device_management_head_table( $from ='' ){
    $select_all_recharge= '';
	( $from === 'devices') ? $select_all_recharge = '<span class="select-all-recharge dashicons dashicons-saved"></span><span class="spinner-small"></span>' : '';
    
    
	$table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
	$table .= '<thead>';
	$table .= '<tr>';

	$table .= '<th scope="col" class="manage-column ">ID</th>';
	$table .= '<th scope="col"  class="manage-column ">Customer name</th>';
//	$table .= '<th scope="col"  class="manage-column ">B</th>';
	$table .= '<th scope="col"  class="manage-column ">Login</th>';
	$table .= '<th scope="col"  class="manage-column ">Num tel</th>';
	$table .= '<th scope="col"  class="manage-column ">Target name</th>';
	$table .= '<th scope="col"  class="manage-column ">IMEI</th>';
	$table .= '<th scope="col"  class="manage-column column-sim-no ">SIM no' . $select_all_recharge. '</th>';
	$table .= '<th scope="col"  class="manage-column ">Type device</th>';
	$table .= '<th scope="col"  class="manage-column ">Expiry</th>';
	$table .= '<th scope="col"  class="manage-column ">SIM operator</th>';
	$table .= '<th scope="col"  class="manage-column ">Date recharge</th>';
	$table .= '<th scope="col"  class="manage-column ">Next recharge</th>'; 
	$table .= '<th scope="col"  class="manage-column ">App</th>'; 
	$table .= '<th scope="col"  class="manage-column ">Remarks</th>'; 
	$table .= '</tr>';        
	$table .= '</thead>';
	
        return $table;
}

/**
 * To check if device exists and sim  has been changed
 *
 * @param INT $imei Device IMEI
 * @param INT $sim_no Device sim number
 * @return BOOL|ARRAY $device
 */
function stargps_device_management_check_sim_no ( $imei ,$sim_no, $app){
    
	global $wpdb;
	$table_devices = $wpdb->prefix.'stargps_devices_management';
	$where_clause = "  WHERE imei =" . $imei ." AND application='" . strtolower( $app ) . "'  AND sim_no !='" . $sim_no ."'"; 
	$sql_query = "SELECT * FROM {$table_devices} " . $where_clause . " ;";
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( is_array( $result ) ){
		$device = $result;
	}else{
		$device = false;
	}
        
	return $device;
}
/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response
 * @param BOOLEAN
 */
function stargps_device_management_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

}
/**
 * Built htm table devices changed sim no
 *
 * @param ARRAY $device Device data in database
 * @param INT $new_sim_no tde new sim nubmer
 * @param STRING <tr> table element
 * 
 * @return STRING Html table
 */
function stargps_device_management_devices_change_sim_table( $devices, $new_sim_no ) {
    
    
    
    $c = count( $devices );
    $table = "";
for ( $i = 0; $i < $c ; $i++  ) {
                                    
	$table .= '<tr>';
					
	$table .= '<td>'.$devices[0]['id'].'</td>';
	$table .= '<td>'.$devices[0]['customer_name'].'</td>';
	$table .= '<td>'.$devices[0]['b'].'</td>';
	$table .= '<td>'.$devices[0]['login'].'</td>';
	$table .= '<td>'.$devices[0]['num_tel_ctl'].'</td>';
	$table .= '<td>'.$devices[0]['target_name'].'</td>';
	$table .= '<td>'.$devices[0]['imei'].'</td>';
	$table .= '<td>'.
                '<span class="old_sim_no">'.$devices[0]['sim_no'].'</span><span class="new_sim_no">' . $new_sim_no .'</span>'
                .'<button  type="button" title="Update device SIM no" data-new-sim-no="'. $new_sim_no .'" data-id="' . $devices[0]['id'] . '" class="update-sim-no cpanelbutton dashicons dashicons-saved"></button>'
                .'<span class="stargps-spinner spinner-small"></span>'
                .'</td>';
	$table .= '<td>'.$devices[0]['type_device'].'</td>';
	$table .= '<td>'.$devices[0]['expiry'].'</td>';
	$table .= '<td>'.$devices[0]['sim_operator'].'</td>';
	$table .= '<td>'.$devices[0]['date_recharge'].'</td>';
	$table .= '<td>'.$devices[0]['next_recharge'].'</td>'; 
	$table .= '<td>'.$devices[0]['application'].'</td>'; 
	$table .= '<td>'.$devices[0]['remarks'].'</td>';                                         
	$table .= '</tr>';   
}

       
       
       return $table;
       
}

function stargps_device_management_check_is_new_device( $device_imei ){
	global $wpdb;
	$table_devices = $wpdb->prefix.'stargps_devices_management';
	$where_clause = "  WHERE imei ='" . $device_imei ."'" ;
	$sql_query = "SELECT id FROM {$table_devices} " . $where_clause . " ;";
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	
        if( empty( $result ) ){
		$new = true;
	}else{
		$new = false;
	}
        
	return $new;    
}

/**
 * To check if device exists and sim_no has NOT been changed
 *
 * @param INT $imei Device IMEI
 * @param INT $sim_no Device sim number
 * @return BOOL|ARRAY $device
 */
function stargps_device_management_update_devies ( $imei ,$sim_no ){
    
	global $wpdb;
	$table_devices = $wpdb->prefix.'stargps_devices_management';
	$where_clause = "  WHERE imei =" . $imei ." AND sim_no ='" . $sim_no ."'"; 
	$sql_query = "SELECT id FROM {$table_devices} " . $where_clause . " ;";
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( ! empty( $result ) ){
		$device = true;
	}else{
		$device = false;
	}
        
	return $device;
}
/**
 * To check API connection
 */
function stargps_device_management_check_api_connection ( $type, $url , $email, $password ){
    
    if ( $type === 'traccar' ){
        
                $args = array(
                    'headers' => array(

                        'Authorization' => 'Basic ' . base64_encode( $email .':' . $password )
                        )
                    );
                $response_arr  = wp_remote_get( $url .'/api/devices' , $args );
               // $body = json_decode(wp_remote_retrieve_body($response_arr)); 
               if ( is_array( $response_arr ) ){
                return $response_arr['response']['code'] ;   
               }
               
    }
    if ( $type === 'gpswox' ){
        
        $args = array(
            'headers' => array(
		'method'  => 'GET',
		'Authorization' => 'Basic' ,
		'Content-Type'  => 'multipart/form-data'
		),
            'body'    => array(
		'email' => $email,
 		'password' => $password
		)	
            );
      
        $response_arr  = wp_remote_get($url . '/api/login', $args);
        if ( is_array( $response_arr ) ){
        if( $response_arr['response']['code'] === 200 ){
            $body = json_decode(wp_remote_retrieve_body($response_arr));
            return array ('status'=>'ok', 'token'=>$body->user_api_hash);   
         
        }else{
            return $response_arr['response'];
        }
        }
    }    
    
   
}

/**
 * Save API , User
 *  
 */
function stargps_device_management_check_save_api ( $type, $app, $url , $email, $password, $token='' ){

	global $wpdb;
        $table_api = $wpdb->prefix . 'stargps_devices_management_api';  
        
        $where_clause = "  WHERE `email` = '" . $email . "'";
        $sql_query = "SELECT email FROM {$table_api} " . $where_clause . " ;";
        
        $result = $wpdb->get_results( $sql_query , ARRAY_A ); 
        
        if( is_array( $result ) && count( $result ) ){
            
            return false;
        }else{
        
        $data=array(
            'email' => $email, 
            'password' => $password,
            'app' => $app,
            'url' => $url,
            'type' => $type,
            'token' => $token );  

        $wpdb->insert( $table_api, $data);    
        
        return true;
        }
}

/**
 * Get List API
 */
function stargps_device_management_get_api(){
	global $wpdb;
        
	$table_api = $wpdb->prefix . 'stargps_devices_management_api';
	
	$sql_query = "SELECT * FROM {$table_api} ;";
	$apis = $wpdb->get_results( $sql_query , ARRAY_A ); 
        
        
	if( is_array( $apis ) && count( $apis ) ){
            
		foreach ( $apis as $key => $value ) {?>
		<div class="api_links">
		<div class="api_column">
            	<input name="campaign_apis[1]" type="text" disabled="disabled" value="<?php echo $value['app'] ?>" class="large-text feedinput">
		</div>
		<div class="api_actions">
            	<button data-type="<?php echo $value['type'] ?>" data-app="<?php echo $value['app'] ?>" data-url="<?php echo $value['url'] ?>" data-password="<?php echo $value['password'] ?>" data-email="<?php echo $value['email'] ?>" data-api-hash="<?php echo $value['token'] ?>"  type="button" title="Lancer"  class="run_wstargpsma dashicons dashicons-controls-play"></button>   
            	<span>User: <b><?php echo $value['email'] ?></b></span>
		</div>
		</div>
		<?php 
		}
            
	}else{
		echo 'Pas de API ';
	}
}

/**
 * Get List API to search 
 */
function stargps_device_management_get_api_select_menu(){
	global $wpdb;
        
        $sql_database = "show databases;";
$databes = $wpdb->get_results( $sql_database , ARRAY_A ); 
//echo '<pre>';
//var_dump($databes);
//echo '</pre>';
        
        $table_api = $wpdb->prefix . 'stargps_devices_management_api';
	
	$sql_query = "SELECT * FROM {$table_api}  ;";
	$apis = $wpdb->get_results( $sql_query , ARRAY_A ); 
        
        
        if ( is_array( $apis ) && count( $apis ) ) {
?>
            <label>Application: </label>
            <select name="app" id="app">
                <option id="" value=""> - </option>
<?php
            foreach ( $apis as $key => $value ) {
?>

                <option id="" value="<?php echo $value['app'] ?>"><?php echo $value['app'] ?></option>                
<?php
               
            }
            ?>
       </select>         
  <?php              
            
        }else{
            echo 'Pas de API ';
        }
        
}
/**
 * Get List API enregistré
 */
function stargps_device_management_get__saved_api_list(){
	global $wpdb;
        
	$table_api = $wpdb->prefix . 'stargps_devices_management_api';
	
	$sql_query = "SELECT * FROM {$table_api} ;";
	$apis = $wpdb->get_results( $sql_query , ARRAY_A ); 
        
        
	if( is_array( $apis ) && count( $apis ) ){
            
		foreach ( $apis as $key => $value ) {?>
		<div class="api_links_saved">
                    <h4><?php echo $value['app'] ?><span> (  <b><?php echo $value['email'] ?></b> )</span></h4>
		</div>
		<?php 
		}
            
	}else{
		echo 'Pas de API ';
	}
}