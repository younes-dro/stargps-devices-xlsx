<?php

/**
 * The admin-specific functionality of the plugin.
 * 
 */
class Stargps_Devices_Management_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
       

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;             
                $this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
            
		wp_register_style( $this->plugin_name, STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/css/stargps-devices-management.css', array(), time());
		wp_register_style( $this->plugin_name . 'devices-management_tabs_css', STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/css/skeletabs.css', array(), $this->version, 'all' );                
               

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
                             
		wp_register_script( $this->plugin_name, STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/js/stargps-devices-management.js', array('jquery'), time());
		wp_register_script( $this->plugin_name . '-xlsx', STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/js/stargps-devices-management-xlsx.js', array('jquery'), time());                
		wp_register_script( $this->plugin_name . '-tabs-js', STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/js/skeletabs.js', array( 'jquery' ), $this->version, false );                

                
		$script_params = array(
			'admin_ajax'                       => admin_url( 'admin-ajax.php' ),
			'is_admin'                         => is_admin(),
			'stargps_device_management_nonce' => wp_create_nonce( 'stargps-device-management-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'starGPSDevicesManagementParams', $script_params );  
                
		$script_params_xlsx = array(
			'admin_ajax'                       => admin_url( 'admin-ajax.php' ),
			'is_admin'                         => is_admin(),
			'security' => wp_create_nonce( 'stargps-device-management-ajax-nonce-xlsx' ),
		);
		wp_localize_script( $this->plugin_name . '-xlsx', 'starGPSDevicesManagementXlsxParams', $script_params );                 
	}
        
	/**
	 * Add Menu items
         * 
	 * stargps-devices-management
	 * @since    1.0.0
	 */
	public  function stargps_devices_management_add_settings_menu() {
		add_menu_page( __( 'Stargps Devices Management', 'stargps-devices-management' ) , 'Device Management', 'publish_pages' , 'devices-management', array ( $this, 'display_settings_page' ), STARGPSDEVICESMANAGEMENT_PLUGIN_URL . '/assets/images/logo.ico' );
		add_submenu_page( 'devices-management', 'Xlsx', 'Xlsx','publish_pages', 'devices-management-xlsx' ,  array ( $this, 'display_xlsxl' ) );
	}
        
	/**
	 * Callback to Display settings page
	 *
	 * @since    1.0.0
	 */
	public  function display_settings_page() {
            	wp_enqueue_script( $this->plugin_name . '-tabs-js' );  
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( $this->plugin_name ); 
                
		wp_enqueue_style( $this->plugin_name . 'devices-management_tabs_css' );
		wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );                
                wp_enqueue_script( 'postbox' );
		wp_enqueue_style( $this->plugin_name );                  
                                          
		require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/stargps-devices-management-admin-display.php';
                               
	}
        
        /**
         * Sub-menu item
         * 
         * @since    1.0.0
         */
	public function display_xlsxl() {
		wp_enqueue_script( $this->plugin_name . '-tabs-js' );  
		wp_enqueue_script( 'jquery-ui-datepicker' );               
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script( 'jquery-ui-dialog' );                
		wp_enqueue_script( $this->plugin_name . '-xlsx' );

                wp_enqueue_style( 'wp-jquery-ui-dialog' );                
		wp_enqueue_style( $this->plugin_name . 'devices-management_tabs_css' );
		wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );                
		wp_enqueue_script( 'postbox' );
		wp_enqueue_style( $this->plugin_name );
                
		require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/stargps-devices-management-admin-display-xlsx.php';
	}
        
        /**
         * 
         * @global OBJECT $wpdb
         */
	public function stargps_devices_management_query_date_recharge() {
            
		global $wpdb;


		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		
		if ( ! wp_verify_nonce( $_POST['stargps_device_management_nonce'], 'stargps-device-management-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
                
//                echo '<pre>';
//                
//                var_dump( $_POST );
//                
//                echo '</pre>';
//                
//                exit();
//                
		$where = ' WHERE 1=1';
                if( ! empty( $_POST['date_recharge'] ) ){
                   $where.= " AND date_recharge = '". $_POST['date_recharge'] ."'";
                }
                if( ! empty( $_POST['target_name'] ) ){
                   $where.= " AND target_name LIKE '%". $_POST['target_name'] ."%'"; 
                }
                if( ! empty( $_POST['app'] ) ){
                    $where.= " AND url = '". $_POST['app'] ."'";
                }
                if( ! empty( $_POST['imei'] ) ){
                    $where.= " AND imei LIKE '%". $_POST['imei'] ."%'"; 
                }
                if( ! empty( $_POST['type_device'] ) ){
                    $where.= " AND type_device LIKE '%". $_POST['type_device'] ."%'"; 
                }                
                
//		if( ! empty( $_POST['date_recharge'] ) && ! empty( $_POST['target_name'] ) && ! empty( $_POST['app'] ) && ! empty( $_POST['imei'] ) ){
//			$where .= "  WHERE date_recharge = '". $_POST['date_recharge'] ."' AND url ='" . $_POST['app'] ."' AND imei ='" . $_POST['imei'] ."' AND target_name LIKE '%" .$_POST['target_name'] ."%'" ;
//                        $title  =  __( sprintf( 'Application: <b>%1$s</b> - Date de reacharge: <b>%2$s</b>',$_POST['app'], $_POST['date_recharge'] ), 'stargps-devices-management' );
//		}elseif ( ! empty( $_POST['date_recharge'] ) ){
//			$where .= "  WHERE date_recharge = '". $_POST['date_recharge'] ."' " ;
//                        $title = __( sprintf( 'Date de reacharge: <b>%s</b>', $_POST['date_recharge'] ), 'stargps-devices-management' );
//		}elseif( ! empty( $_POST['app'] ) ){
//			$where .= "  WHERE url ='" . $_POST['app'] ."' ";
//                        $title = __( sprintf( 'Application: <b>%s</b>', $_POST['app'] ), 'stargps-devices-management' ); 
//		}
                
                 
		$table_devices = $wpdb->prefix.'stargps_devices_management';
		$sql = "SELECT * FROM {$table_devices} " . $where . " ;";
		$devices = $wpdb->get_results( $sql );
                
//                echo $sql;
//                
//                exit();
                   
		if ( is_array( $devices ) && count( $devices ) ){
				//echo $title . '<br><br>';
                                echo stargps_device_management_head_table( 'devices' );
				echo '<tbody id="the-list">'; 
				foreach ( $devices as $device ) {
                                    
					echo '<tr>';
					
				echo '<td>'.$device->id.'</td>';
				echo '<td>'.$device->customer_name.'</td>';
				echo '<td>'.$device->b.'</td>';
				echo '<td>'.$device->login.'</td>';
				echo '<td>'.$device->num_tel_ctl.'</td>';
				echo '<td>'.$device->target_name.'</td>';
				echo '<td>'.$device->imei.'</td>';
				echo '<td>'.
                                        $device->sim_no.
                                        '<br><button  type="button" title="Send recharge " data-sim-no="'. $device->sim_no .'" data-id="' . $device->id . '" class="send-sim-recharge cpanelbutton dashicons dashicons-saved"></button> '
                                        .'<span class="stargps-spinner spinner-small"></span>'
                                        .'</td>';
				echo '<td>'.$device->type_device.'</td>';
				echo '<td>'.$device->expiry.'</td>';
				echo '<td>'.$device->sim_operator.'</td>';
				echo '<td>'.$device->date_recharge.'</td>';
				echo '<td>'.$device->next_recharge.'</td>'; 
				echo '<td>'.$device->url.'</td>'; 
				echo '<td>'.$device->remarks.'</td>';                                         
				echo '</tr>';   
                                }
                                echo '</tbody>'; 
				echo '</table>';                                
		}else{
                    echo 'Pas de devices ! ';
                }
                                    
                
                exit();
	}
        
	public function stargps_devices_management_run_wstargpsma(){
            
		
                
                
                if( $_POST['type'] == 'traccar' ){
                    
                    get_traccar_devices( $_POST['url'], $_POST['email'], $_POST['password'], $_POST['app']  );
                }                
                
                if( $_POST['type'] == 'gpswox' ){
                    
                    get_gpswow_devices( $_POST['url'] , $_POST['api-hash'] , $_POST['app']);                    
                }
           

        exit();
	}
        public function stargps_device_management_update_sim_no(){
            
            global $wpdb;
            
            $table_devices = $wpdb->prefix.'stargps_devices_management';
            
            $devie_id = $_POST['device_id'];
            $new_sim_no = $_POST['new_sim_no'];
            
            $where_clause = "  WHERE id =" . $devie_id;
            
            $sql_query = "UPDATE {$table_devices} SET sim_no='" . $new_sim_no . "'" . $where_clause . " ;";
            
            $wpdb->query( $sql_query );
            
            exit();
        }
	public function stargps_device_management_send_recharge_sim(){
		global $wpdb;
		$table_devices = $wpdb->prefix.'stargps_devices_management';
                
                $device_id = $_POST['device_id'];
		//$nmsms = $_POST[""];
		$_POST["ddlar"]="ok";
                
//                var_dump($_POST);
//                exit();

		if( isset( $_POST["ddlar"] ) && isset( $_POST["nmsms"] ) ){
                    
                    define( 'API_ACCESS_KEY', 'AAAA1VrkBqE:APA91bE3OYDhgZPVqNTl4oWnMh2NLUgiyDL4yB-KvAtcgl8q-A632IBuDnNCGjKr72kQkkODH8PZ1aPgiT26sOjJ9bFtyTBhhFFwv7NlCaUYxoy6wnDpc2IwZaKljqaLQswFAd96lESt');
 
		$msg = array(
			'body' 	=> $_POST["nmsms"] ,
			'Title'	=> "ok",
			'matr'	=> " aan aan aan"  , 
			'id'	=> rand(1,10000) ,       	
			'agance' => "najm",         	
			'tyype'	=> 248,
			'androidNotificationChannel'	=> "foreground_najm"
		);
		$fields = array(
			'to' => 'fCaIZj7pBSI:APA91bFwHPYmL6QeGnNC_ERs2xTvnXwx4ZSM3tzPvuSUOWcDakSK3pLOeiAqdZwQWQLZ_6U6j0_dRUL8LaeIjLgpO7NmcdzQ8ZejXmwhHkqCJEnXjFpaSJq0KcBSf1ethNtTycFRzlTA',
			'data'	=> $msg
		);
	
	
		$headers = array(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
			);
        
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
                if( curl_errno( $ch ) ){
                   $error_msg = curl_error($ch);
                   
                }
		curl_close( $ch );
		
                if (isset($error_msg)) {
                    
                    echo 'Erreur cUrl';
                    exit();
                }
                
            $where_clause = "  WHERE id =" . $device_id;
            
            $sql_query = "UPDATE {$table_devices} SET date_recharge='" . date( "Y-m-d") . "'" . $where_clause . " ;";
            
            $wpdb->query( $sql_query );	
 
		echo "OK!";

		exit();
		}            
	}
        
        public function stargps_device_management_devices_connect_api(){
            
            
            
            if( $_POST['type_gps'] === 'traccar' ){
               // echo '<pre>';
                //var_dump(stargps_device_management_check_api_connection($_POST['type_gps'], $_POST['url'], $_POST['email'], $_POST['password']));
                //echo '</pre>';
                
                $response_status = stargps_device_management_check_api_connection($_POST['type_gps'], $_POST['url'], $_POST['email'], $_POST['password']);
                if( $response_status == 200 ){
                   
                    if ( stargps_device_management_check_save_api($_POST['type_gps'], $_POST['url'], $_POST['url'].'/api/devices', $_POST['email'], $_POST['password']) ){
                        echo '<span class="">L\'API: <b>'.$_POST['url'].'</b> est ajouté </span>';                        
                    }else{
                        echo '<span class="">L\'email: <b>' . $_POST['email'] . '</b> existe deja ! </span>';
                    }
                   
                }else{
                    echo '<span class="error">Connexion failed</span>';
                    
                }
               
                exit();
            }
             if( $_POST['type_gps'] === 'gpswox' ){
                 
                 // code 400 Bad Request
                 // 422 Unprocessable Entity
                 // 401 : Unauthorized
                 $response = stargps_device_management_check_api_connection($_POST['type_gps'], $_POST['url'], $_POST['email'], $_POST['password']);
                 if(is_array($response)){
                        if( array_key_exists('status', $response ) && $response['status'] =='ok' ){
                             if ( stargps_device_management_check_save_api($_POST['type_gps'], $_POST['url'], $_POST['url'].'/api/get_devices', $_POST['email'], $_POST['password'],$response['token'] ) ){
                                 echo '<span class="">L\'API: <b>'.$_POST['url'].'</b> est ajouté </span>';
                                 
                             }else{
                                 echo '<span class="">L\'email: <b>' . $_POST['email'] . '</b> existe deja ! </span>';
                                 
                             }
                             
                        }else{
                       echo '<pre>';
                       var_dump(stargps_device_management_check_api_connection($_POST['type_gps'], $_POST['url'], $_POST['email'], $_POST['password']));
                       echo '</pre>';
                        }
                 }
             }
            
            
            exit();
            
        }        
        public function stargps_device_management_devices_recharge_manuelle(){
            
               
	
		$_POST["ddlar"]="ok";
//                echo '<pre>';
//                var_dump($_POST);
//                echo '</pre>';
//                exit();

		if( isset( $_POST["ddlar"] ) && isset( $_POST["nmsms"] ) ){
                    
                    define( 'API_ACCESS_KEY', 'AAAA1VrkBqE:APA91bE3OYDhgZPVqNTl4oWnMh2NLUgiyDL4yB-KvAtcgl8q-A632IBuDnNCGjKr72kQkkODH8PZ1aPgiT26sOjJ9bFtyTBhhFFwv7NlCaUYxoy6wnDpc2IwZaKljqaLQswFAd96lESt');
 
		$msg = array(
			'body' 	=> $_POST["nmsms"] ,
			'Title'	=> "ok",
			'matr'	=> " aan aan aan"  , 
			'id'	=> rand(1,10000) ,       	
			'agance' => "najm",         	
			'tyype'	=> 248,
			'androidNotificationChannel'	=> "foreground_najm"
		);
		$fields = array(
			'to' => 'fCaIZj7pBSI:APA91bFwHPYmL6QeGnNC_ERs2xTvnXwx4ZSM3tzPvuSUOWcDakSK3pLOeiAqdZwQWQLZ_6U6j0_dRUL8LaeIjLgpO7NmcdzQ8ZejXmwhHkqCJEnXjFpaSJq0KcBSf1ethNtTycFRzlTA',
			'data'	=> $msg
		);
	
	
		$headers = array(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
			);
        
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
                if( curl_errno( $ch ) ){
                   $error_msg = curl_error($ch);
                   
                }
		curl_close( $ch );
		
                if (isset($error_msg)) {
                    
                    echo 'Erreur cUrl';
                    exit();
                }  
                
		echo "OK!";

		exit();                
		}
	}
	public function stargps_device_management_devices_rest_api(){
            
		register_rest_route( 'devices/v1', '/all/', 
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array ( $this , 'rest_api_get_devices' ),
				'permission_callback' => '__return_true'
				) );
	}
        
	public function rest_api_get_devices(){
            
		global $wpdb;
		$table_devices = $wpdb->prefix.'stargps_devices_management';
		$where_clause = ""; 
		$sql_query = "SELECT sim_no, date_recharge, next_recharge  FROM {$table_devices} " . $where_clause . " ;";
		$result = $wpdb->get_results( $sql_query , ARRAY_A );
        
		return json_encode($result);
	}
}
