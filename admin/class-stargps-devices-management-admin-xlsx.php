<?php

/**
 * The admin-specific functionality of the plugin.
 * 
 */
class Stargps_Devices_Management_Admin_Xlsx {

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
	 * Folder to save xslx
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    Name folder
	 */
	private $xlsx_folder;



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
                $this->xlsx_folder = trailingslashit( wp_upload_dir()['basedir'] ) . STARGPSDEVICESMANAGEMENT_XLSX_FOLDER ;
                if ( ! is_dir( $this->xlsx_folder ) ) {
                    
                    wp_mkdir_p( $this->xlsx_folder );
                    chmod( $this->xlsx_folder , 0777 );
                    
                }                
                

	}
	/**
	 * conversion de l'encodage HTML
	 * @param type $initial
	 * @return type
	 */
	public function callHTMLEntities($initial) {
		$s = htmlentities( $initial );
		$s = preg_replace( "/&(.)(acute|cedil|circ|ring|tilde|uml|grave);/", "$1", $s );
		$s = preg_replace( '#&([A-za-z]{2})(?:lig);#', '1', $s ); // pour les ligatures e.g. 'Å“'
		return strtolower( sanitize_file_name( $s ) );
        }
        
        /**
         * Upload file Xlsx
         */
	public function file_upload_callback(){
            
		$arr_img_ext = array( 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel' );
                
		if ( in_array( $_FILES['file']['type'], $arr_img_ext ) ) {
                   
			$upload = move_uploaded_file( $_FILES["file"]["tmp_name"] ,$this->xlsx_folder . '/' . $this->callHTMLEntities( $_FILES["file"]["name"] ) );
			if( $upload ){    
				echo json_encode(['re' => '1']);
			}else{
				echo json_encode(['re' => '0']);
			}
		}else{
			echo json_encode(['re' => 'exension fichier non supporté']);
		} 
                
 		exit();	           
	}
        
        /**
         * Delete Xlsx file
         */
	public function delete_xlxs (){
            
            $file_name = $_POST['file_name'];
            $abs_path_file = $this->xlsx_folder . '/' . $file_name;
            
            if ( file_exists( $this->xlsx_folder . '/' . $file_name ) ){
                
                if( wp_delete_file_from_directory( $abs_path_file , $this->xlsx_folder ) ){  
                    echo 'ok';
                }else{
                    echo '0';
                }
            }else{
                echo 'non';
            }
            
            exit();	           
	}        
        
        /**
         * Refresh Xlsx files
         * 
         */
	public function  refresh_xlsx () {
		stargps_device_management_get_files_xlsx();
 		exit();	                       
	}
        
	/**
	 * Import xlsx files to database
	 * 
	 *
	 */
	public function import_xlsx ( ) {
		global $wpdb;            
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );            
 		$charset_collate = $wpdb->get_charset_collate();
                
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libraries/vendor/autoload.php';
            
		$file_name =  $_POST['file_name'];
		$path_file =  trailingslashit( wp_upload_dir()['basedir'] ) . STARGPSDEVICESMANAGEMENT_XLSX_FOLDER . '/' . $file_name;
            
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path_file);
		$spreadsheet = $spreadsheet->getActiveSheet();
		$data_array =  $spreadsheet->toArray();
                
		$table_name = $wpdb->prefix . 'xlsx_' . basename( $file_name, '.xlsx' );
		$table_columns = array();
                $table_columns_insert = array();
                
                
		foreach ( $data_array[0] as $column ) {
                    
			if ( is_null( $column ) || empty( $column ) ){
				continue;
			}
			if ( strlen(  $this->callHTMLEntities( $column ) ) === 0 ){
				$table_columns[] = '`#`';
                                $table_columns_insert[] = '#';
				continue;
			}
			$table_columns[] = '`' . $this->callHTMLEntities( $column ) . '`';
                        $table_columns_insert[] = $this->callHTMLEntities( $column );
		}
                                
		$createSQL = "CREATE TABLE IF NOT EXISTS `$table_name`
		( `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, ".implode(" LONGTEXT , ", $table_columns ). " 
		LONGTEXT, UNIQUE KEY id (id)) $charset_collate;";
		dbDelta( $createSQL );           

                $count = 0;
		if ( ! ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) ) {
			foreach ( $data_array as  $k =>$data ) {
                                    
				if( $k === 0) continue;
				if ( is_null( $data_array[$k][0] ) ) continue;
                                
                              //  for( $i = 0 ; $i < 14 ; $i++ ){
                                   // $l.$i = '';
                                    //( is_null( $data_array[$k][$i] ) ) ? $l.$i = '' : $l.$i =  $data_array[$k][$i];
                                    
                                //}
                                
				( is_null( $data_array[$k][0] ) ) ? $l0 = '' : $l0 =  $data_array[$k][0];
				( is_null( $data_array[$k][1] ) ) ? $l1 = '' : $l1 = $data_array[$k][1];                                
				( is_null( $data_array[$k][2] ) ) ? $l2 = '' : $l2 =  $data_array[$k][2];                    
				( is_null( $data_array[$k][3] ) ) ? $l3 = '' : $l3 =  $data_array[$k][3];
				( is_null( $data_array[$k][4] ) ) ? $l4 = '' : $l4 =  $data_array[$k][4]; 
                                ( is_null( $data_array[$k][5] ) ) ? $l5 = '' : $l5 =  $data_array[$k][5]; 
                                ( is_null( $data_array[$k][6] ) ) ? $l6 = '' : $l6 =  $data_array[$k][6]; 
                                ( is_null( $data_array[$k][7] ) ) ? $l7 = '' : $l7 =  $data_array[$k][7];
                                ( is_null( $data_array[$k][8] ) ) ? $l8 = '' : $l8 =  $data_array[$k][8]; 
                                ( is_null( $data_array[$k][9] ) ) ? $l9 = '' : $l9 =  $data_array[$k][9]; 
                                ( is_null( $data_array[$k][10] ) ) ? $l10 = '' : $l10 =  $data_array[$k][10]; 
                                ( is_null( $data_array[$k][11] ) ) ? $l11 = '' : $l11 =  $data_array[$k][11]; 
                                ( is_null( $data_array[$k][12] ) ) ? $l12 = '' : $l12 =  $data_array[$k][12]; 
                                ( is_null( $data_array[$k][13] ) ) ? $l13 = '' : $l13 =  $data_array[$k][13]; 
                                
				$data=array(
					$table_columns_insert[0] => $l0, 
					$table_columns_insert[1] => $l1,
					$table_columns_insert[2] => $l2,
					$table_columns_insert[3] => $l3,
					$table_columns_insert[4] => $l4,
					$table_columns_insert[5] => (string) $l5,
					$table_columns_insert[6] => $l6,
					$table_columns_insert[7] => $l7,
					$table_columns_insert[8] => $l8,
					$table_columns_insert[9] => $l9,
					$table_columns_insert[10] => $l10,
					$table_columns_insert[11] => $l11,
					$table_columns_insert[12] => $l12,                                    
					$table_columns_insert[13] => $l13 );  
				if( $wpdb->insert( $table_name, $data) ){                                                         
					$count++;
				}
			}
		} else{
			echo 'Table was not created';
		}    
                echo '<h4>Table crée: <b>' . $table_name . '</b></h4>';
                echo '<h4>Nombre de ligne inséré: '. $count . '</h4>';

                
                exit();

		
            exit();
	}
        
	public function stargps_devices_management_query_date_recharge() {
		global $wpdb;
                


		$table_devices = '`' . $_POST['app'] . '`';

                if( empty( $_POST['app'] ) ){
                    echo '<div class="notice notice-error is-dismissible"><p>Selectionner une Application</p></div>';
                    exit();
                }
                $where = ' WHERE 1=1';
                if( isset( $_POST['recharge_80_j'] ) ){
                   
                    $days_ago = date("Y-m-d" , strtotime( date( "Y-m-d", strtotime( "-80 day" ) ) ) );
                    
                     $where.= " AND  STR_TO_DATE( `date-recharge` , '%d-%m-%Y') <= '" . $days_ago . "' ";
                }else{
                    
                    if( ! empty( $_POST['date_recharge'] ) ){
                       $where.= " AND `date-recharge` = '". $_POST['date_recharge'] ."'";
                    }
                    if( ! empty( $_POST['next_recharge'] ) ){
                       $where.= " AND `next-recharge` = '". $_POST['next_recharge'] ."'";
                    }                
                    if( ! empty( $_POST['customer_name'] ) ){
                       $where.= " AND `customer-name` LIKE '%". $_POST['customer_name'] ."%'"; 
                    }
                    if( ! empty( $_POST['imei'] ) ){
                        $where.= " AND `idimei` LIKE '%". $_POST['imei'] ."%'"; 
                    }
                    if( ! empty( $_POST['type_device'] ) ){
                        $where.= " AND `type` LIKE '%". $_POST['type_device'] ."%'"; 
                    } 
                    if( ! empty( $_POST['tel_clt'] ) ){
                        $where.= " AND `tel-clt` LIKE '%". $_POST['tel_clt'] ."%'"; 
                    }
                    if( ! empty( $_POST['sim_no'] ) ){
                        $where.= " AND `sim-no` LIKE '%". $_POST['sim_no'] ."%'"; 
                    }                    
                    
                }
             
		$sql = "SELECT * FROM {$table_devices} " . $where . " ;";
		$devices = $wpdb->get_results( $sql , ARRAY_A );

                $row_increment = 1; 
		if ( is_array( $devices ) && count( $devices ) ){
				echo '<h2>Nombre de resultat: ' . count( $devices ) . '</h2><br>';
                                echo stargps_device_management_head_table_xlsx( 'devices' );
				echo '<tbody id="the-list">'; 
				foreach ( $devices as $device ) {
                                    echo '<tr>';
                                    echo '<td><b>' . $row_increment . '</b></td>';                                    
                                    echo '<td>' . $device['id'] . '</td>';
                                    echo '<td>' . $device['customer-name'] . '</td>';
                                    echo '<td>' . $device['login'] . '</td>';
                                    echo '<td>' . $device['tel-clt'] . '</td>';
                                    echo '<td>' . $device['target-name'] . '</td>';
                                    echo '<td>' . $device['idimei'] . '</td>';
                                    echo '<td>'.
                                        $device['sim-no'].
                                        '<br><button  type="button" title="Send recharge " data-sim-no="'. $device['sim-no'] .'" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '" class="send-sim-recharge cpanelbutton dashicons dashicons-controls-play"></button> '
                                        .'<span class="stargps-spinner spinner-small"></span>'
                                        .'<br><button  type="button" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '"  class="send-sim-valider cpanelbutton dashicons dashicons-saved"></button>'
                                        .'<br><button  type="button" class="send-sim-reload cpanelbutton dashicons dashicons-image-rotate"></button>'
                                        .'</td>';
				echo '<td>' . $device['type'] . '</td>';
				echo '<td>' . $device['expiry'] . '</td>';
				echo '<td>' . $device['sim-op'] . '</td>';
				echo '<td>' . $device['date-recharge'] . '</td>';
				echo '<td>' . $device['next-recharge'] . '</td>'; 
				echo '<td>' . $device['app'] . '</td>'; 
                                echo '<td>' . $device['remarks'] . '</td>';                                         
				echo '</tr>';
                                $row_increment++;
                                }
                                echo '</tbody>'; 
				echo '</table>';                                
		}else{
                    echo 'Pas de devices ! ';
                }
                                    
                
                exit();
	}
	public function stargps_device_management_send_recharge_sim_xlsx(){
		global $wpdb;
                
//                echo '</pre>';
//                var_dump($_POST);
//                echo '</pre>';                              
//                exit();
                               
		$_POST["ddlar"]="ok";

		if( ( $_SERVER['SERVER_NAME'] ) === '127.0.0.2' ){
			$TO = 'dqL0XKRHwlQ:APA91bGIM6Icd639RMjvLKpIBXNI9hAEre4wnMp-sm1-MQMuJQi_KUVn-lSSyB_5QEbh5BB2jrquaBoHu0saiTfQR-mFAc9t4CpcXI15-e2KRrqfVrWC-8nvKCvDYHurjuaiWS8YLZu-';
		}else{
			$TO = 'fCaIZj7pBSI:APA91bFwHPYmL6QeGnNC_ERs2xTvnXwx4ZSM3tzPvuSUOWcDakSK3pLOeiAqdZwQWQLZ_6U6j0_dRUL8LaeIjLgpO7NmcdzQ8ZejXmwhHkqCJEnXjFpaSJq0KcBSf1ethNtTycFRzlTA';
		}
                
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
				'to' => $TO,
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
	public function stargps_device_management_send_valider_recharge_sim_xlsx(){
		global $wpdb;
                
//                echo '</pre>';
//                var_dump($_POST);
//                echo '</pre>';                              
//                exit();
                
		$table_devices = $_POST['table'];                
		$device_id = $_POST['device_id']; 
       
		$where_clause = "  WHERE id =" . $device_id;
		$effectiveDate = strtotime( "+80 days", strtotime( date ( "d-m-Y" ) ) );
		$next_recharge = date( "d-m-Y", $effectiveDate );
		$sql_query = "UPDATE `{$table_devices}` SET `next-recharge` = '" . $next_recharge . "' , `date-recharge`='" . date( "d-m-Y") . "'" . $where_clause . " ;";
                $wpdb->query( $sql_query );
                
//                echo '</pre>';
//                var_dump($sql_query);
//                echo '</pre>';                              
//                exit();
		
                echo "Valider!";

		exit();
		          
	}
        public function stargps_device_management_remove_table(){
            global $wpdb;
                
            if ( isset( $_POST['table'] ) ){
                $drop_table =  "DROP TABLE `{$_POST['table']}` ; ";
                
               if ( $wpdb->query( $drop_table ) ){
                   
                   echo 'ok';
               } else {
                   echo '0';
               }
            }
                
            exit();
        }
        public function stargps_device_management_devices_recharge_manuelle_xlsx(){      
	
		$_POST["ddlar"]="ok";
		if( ( $_SERVER['SERVER_NAME'] ) === '127.0.0.2' ){
			$TO = 'dqL0XKRHwlQ:APA91bGIM6Icd639RMjvLKpIBXNI9hAEre4wnMp-sm1-MQMuJQi_KUVn-lSSyB_5QEbh5BB2jrquaBoHu0saiTfQR-mFAc9t4CpcXI15-e2KRrqfVrWC-8nvKCvDYHurjuaiWS8YLZu-';
		}else{
			$TO = 'fCaIZj7pBSI:APA91bFwHPYmL6QeGnNC_ERs2xTvnXwx4ZSM3tzPvuSUOWcDakSK3pLOeiAqdZwQWQLZ_6U6j0_dRUL8LaeIjLgpO7NmcdzQ8ZejXmwhHkqCJEnXjFpaSJq0KcBSf1ethNtTycFRzlTA';
		}
                $check = preg_match ("/\b0[0-9]+(?:\.[0-9]+)?\b/" , trim( $_POST["nmsms"] ) );
                
                if ( $check === 0 ){
                    echo '<div class="notice notice-error is-dismissible"><p>Saisir un num valide</p></div>';
                    exit();                    
                }
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
				'to' => $TO,
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
		
			if ( isset( $error_msg ) ) {
				echo 'Erreur cUrl';
				exit();
			}  
                
			echo "OK!";

			exit();                
		}
	}        
}
