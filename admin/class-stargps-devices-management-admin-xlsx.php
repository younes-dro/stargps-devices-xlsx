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
                    
			if ( file_exists( $this->xlsx_folder . '/' . $this->callHTMLEntities( $_FILES["file"]["name"] ) ) ){
                            
				$file_tmp_name_path = $this->xlsx_folder . '/' . 'tmp-' .$this->callHTMLEntities( $_FILES["file"]["name"] );
                                $file_tmp_name = 'tmp-' .$this->callHTMLEntities( $_FILES["file"]["name"] ) ;
                                move_uploaded_file( $_FILES["file"]["tmp_name"] , $file_tmp_name_path );
                                echo json_encode(['re' => 'f' , 'file_tmp_name' => $file_tmp_name ]);
				exit();
			}
                   
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
         * Confirm Upload file Xlsx
         */
	public function confirm_upload_callback(){

		$new_name = substr( $_POST['file_name'],4 );
                
		if ( rename( $this->xlsx_folder . '/' .$_POST['file_name'], $this->xlsx_folder . '/' . $new_name ) ) {
			echo json_encode(['re' => '1']);
		}else{
			echo json_encode(['re' => '0']);
		} 
                
 		exit();	           
	}
        /**
         * Confirm Upload file Xlsx
         */
	public function cancel_upload_callback(){

		if ( unlink( $this->xlsx_folder . '/' .$_POST['file_name'] ) ) {
			echo json_encode(['re' => '1']);
		}else{
			echo json_encode(['re' => '0']);
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
		( `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, ".implode(" VARCHAR(191) , ", $table_columns ). " 
		LONGTEXT,`status` VARCHAR(10) , UNIQUE KEY (`id`) , UNIQUE KEY `uniq_id` (`idimei`,`sim-no`) ) $charset_collate;";
                
		$createdTable = dbDelta( $createSQL );           

                $count = 0;
                $errors = '';
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
                                
                                $data['status'] = 'active';
                                
				if( $wpdb->insert( $table_name, $data) ){                                                         
					$count++;
				}  else {
                                   $errors .= '<div class="notice notice-error is-dismissible">' . $wpdb->last_error . '</div>';
                                   
                                }
			}
		} else{
			echo 'Table was not created';
		} 
		echo '<h4>Table crée: <b>' . $table_name . '</b></h4>';
		echo '<h4>Nombre de ligne inséré: '. $count . '</h4>';
                
		if ( !empty( $errors ) ){
			echo $errors;
		}
                
            exit();
	}
        
	public function stargps_devices_management_query_date_recharge() {
		global $wpdb;
                


		$table_devices = '`' . $_POST['app'] . '`';

                if( empty( $_POST['app'] ) ){
                    echo '<div class="notice notice-error is-dismissible"><p>Selectionner une Application</p></div>';
                    exit();
                }
                $order_by = "";
                $where = ' WHERE 1=1';
                $select_all = false;
                if( isset( $_POST['recharge_80_j'] ) ){
//                   $select_all = true;
                    $days_ago = date("Y-m-d" , strtotime( date( "Y-m-d", strtotime( "-80 day" ) ) ) );
                    
                    $where.= " AND  STR_TO_DATE( `date-recharge` , '%d-%m-%Y') < '" . $days_ago . "' ";
                    
                    $where .= " AND `id` NOT IN ( SELECT `id` from {$table_devices} WHERE  month(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = month(curdate() ) AND year(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = year(curdate()) ) ";
                    if( ! empty( $_POST['date_recharge'] ) ){
                        $where.= get_length_date( $_POST['date_recharge'] , 'date-recharge' ); 
                       //$where.= " AND `date-recharge` = '". $_POST['date_recharge'] ."'";
                    }
                    if( ! empty( $_POST['next_recharge'] ) ){
                        $where.= get_length_date( $_POST['next_recharge'] , 'next-recharge' ); 
//                       $where.= " AND `next-recharge` = '". $_POST['next_recharge'] ."'";
                    }
                    if( ! empty( $_POST['expiry_date'] ) ){
                        $where.= get_length_date( $_POST['expiry_date'] , 'expiry' ); 
//                       $where.= " AND `expiry` = '". $_POST['expiry_date'] ."'";
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
                    if( $_POST['status'] != 'all' ){
                        $where.= " AND `status` = '". $_POST['status'] ."'"; 
                    }                    
                    if ( $_POST['order_by'] === 'next-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `next-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'date-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `date-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'expiry' ){
                        $order_by = " ORDER BY STR_TO_DATE( `expiry` , '%d-%m-%Y') "  . $_POST['order'];
                    }else{
                        $order_by = " ORDER BY `" .  $_POST['order_by'] . "` " . $_POST['order'];
                    }                    
                }else{
                    $select_all = true;
                    if( ! empty( $_POST['date_recharge'] ) ){
                       $where.= get_length_date( $_POST['date_recharge'] , 'date-recharge' ); 
                       //$where.= " AND `date-recharge` = '". $_POST['date_recharge'] ."'";
                    }
                    if( ! empty( $_POST['next_recharge'] ) ){
                       $where.= get_length_date( $_POST['next_recharge'] , 'next-recharge' ); 
//                       $where.= " AND `next-recharge` = '". $_POST['next_recharge'] ."'";
                    }
                    if( ! empty( $_POST['expiry_date'] ) ){
                        $where.= get_length_date( $_POST['expiry_date'] , 'expiry' ); 
//                       $where.= " AND `expiry` = '". $_POST['expiry_date'] ."'";
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
                    if( $_POST['status'] != 'all' ){
                        $where.= " AND `status` = '". $_POST['status'] ."'"; 
                    }                    
                    if( ! empty( $_POST['sim_no'] ) ){
                        $where.= " AND `sim-no` LIKE '%". $_POST['sim_no'] ."%'"; 
                    }
                    if ( $_POST['order_by'] === 'next-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `next-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'date-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `date-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'expiry' ){
                        $order_by = " ORDER BY STR_TO_DATE( `expiry` , '%d-%m-%Y') "  . $_POST['order'];
                    }else{
                    $order_by = " ORDER BY `" .  $_POST['order_by'] . "` " . $_POST['order'];
                    }
                    
                }
             
		$sql = "SELECT * FROM {$table_devices} " . $where . $order_by . " ;";
                          
		$devices = $wpdb->get_results( $sql , ARRAY_A );

                $row_increment = 1; 
		if ( is_array( $devices ) && count( $devices ) ){

				echo '<h2>Nombre de resultat: ' . count( $devices ) . '</h2><br>';
                                if( $select_all ){
                                    echo '<div class="update-delete-button">';
                                    echo '<button class="open-my-dialog button button-primary stargps-devices-management-btn" id="update_selected_devices">Update Selected devices</button>';
                                    if ( current_user_can( 'administrator' ) ){
                                    echo '<button class="button button-primary stargps-devices-management-btn"  id="delete_selected_devices">Delete Selected devices</button>';
                                    }
                                    echo ' <span class="deleting-message"></span>';
                                    echo '<input type="hidden" id="delete-devices-app" value="'. $_POST['app'] . '" >';
                                    echo '</div>';
                                }
                                echo stargps_device_management_head_table_xlsx( 'devices', $select_all );
				echo '<tbody id="the-list">'; 
				foreach ( $devices as $device ) {
                                    $check_box_device = "";
                                    
                                    if( $device['status']  === 'active' ){
                                        $status = '<td><span class="dashicons dashicons-saved"></span></td>';                                    
                                    } else if ( $device['status']  === 'disabled' ){
                                        $status = '<td><span class="dashicons dashicons-controls-pause"></span></td>';                                    
                                    } else if ( $device['status']  === 'expired' ){
                                    
                                        $status = '<td><span class="dashicons dashicons-warning"></span></td>';                                    
                                    } else if ( $device['status']  === 'removed' ){
                                    
                                        $status = '<td><span class="dashicons dashicons-no"></span></td>';                                    
                                    }   
                                    
                                    ( $select_all ) ? $check_box_device = '<td><input type="checkbox" value="' . $device['id'] . '" name="elementDevice" class="elementDevice" /></td>' : '';                                    
                                    $no_need = ( DateTime::createFromFormat('d-m-Y', $device['date-recharge'] ) === false ) ? 'style="background-color: #b2b0c8;"' : '';
                                    echo '<tr data-id=' . $device['id'] . ' class="line-'.$device['id'].'"' . $no_need. '>';
                                    echo $check_box_device;
                                    echo '<td><b>' . $row_increment . '</b>';
                                    if ($select_all){
                                    echo '<span data-id=' . $device['id'] . '  class="dashicons dashicons-edit modification_rapide"> Edit</span>'; 
                                    }
                                    echo '</td>';                                    
                                    echo $status;
                                    echo '<td>' . $device['id'] . '</td>';
                                    echo '<td class="customer-name">' . $device['customer-name'] . '</td>';
                                    echo '<td>' . $device['login'] . '</td>';
                                    echo '<td>' . $device['tel-clt'] . '</td>';
                                    echo '<td>' . $device['target-name'] . '</td>';
                                    echo '<td>' . $device['idimei'] . '</td>';
                                    if( ! empty ( $no_need ) ){
                                        echo '<td>Off</<td>';
                                    }else{
                                    echo '<td>'.
                                        $device['sim-no'].
                                        '<br><button  type="button" title="Send recharge " data-sim-no="'. $device['sim-no'] .'" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '" class="send-sim-recharge cpanelbutton dashicons dashicons-controls-play"></button> '
                                        .'<span class="stargps-spinner spinner-small"></span>'
                                        .'<br><button  type="button" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '"  class="send-sim-valider cpanelbutton dashicons dashicons-saved"></button>'
                                        .'<br><button  type="button" class="send-sim-reload cpanelbutton dashicons dashicons-image-rotate"></button>'
                                        .'</td>';
                                        
                                    }                                    
				echo '<td>' . $device['type'] . '</td>';
				echo '<td>' . $device['expiry'] . '</td>';
				echo '<td>' . $device['sim-op'] . '</td>';
				echo '<td>' . $device['date-recharge'] . '</td>';
				echo '<td>' . $device['next-recharge'] . '</td>'; 
				echo '<td>' . $device['app'] . '</td>'; 
                                echo '<td>' . $device['remarks'] . '</td>';                                         
				echo '</tr>';
                                if ( $select_all ){
                                /**
                                 * Edit 
                                 */
                                echo '<tr class="edit-' . $device['id'] . ' inline-edit-row ">';
                                echo '<td colspan="16">';
                                echo '<form id="form-' . $device['id'] . '">';
                                echo '<fieldset class="inline-edit-col-left">';
                                //echo '<legend class="inline-edit-legend">Modification rapide</legend>';
                                echo '<div class="inline-edit-col">';
                                echo '<label><span class="title">Customer: </span><span class="input-text-wrap"><input type="text" name="customer-name" class="ptitle" value="' . $device['customer-name'] . '"></span></label>';
                                echo '<label><span class="title">Num tel: </span><span class="input-text-wrap"><input type="text" name="tel-clt" value="' . $device['tel-clt'] . '" autocomplete="off" spellcheck="false"></span></label>';
                                echo '<label><span class="title">Target name: </span><span class="input-text-wrap"><input type="text" name="target-name" value="' . $device['target-name'] . '" autocomplete="off" spellcheck="false"></span></label>';

                                echo '</div>';
                                echo '</fieldset>';
                                echo '<fieldset class="inline-edit-col-left">';
                                //echo '<legend class="inline-edit-legend">-----</legend>';                                
                                echo '<div class="inline-edit-col">';
                                
                                if ( current_user_can( 'administrator' ) ){
                                echo '<label><span class="title">SIM no: </span><span class="input-text-wrap"><input type="text" name="sim-no" value="' . $device['sim-no'] . '" autocomplete="off" spellcheck="false"></span></label>';                                
                                echo '<label><span class="title">Groupe ID: </span><span class="input-text-wrap"><input type="text" name="groupe-id" value="' . $device['#'] . '" autocomplete="off" spellcheck="false"></span></label>';                                
                                echo '<label><span class="title">Next Re: </span><span class="input-text-wrap"><input type="text" class="date_picker" name="next-recharge" value="' . $device['next-recharge'] . '" autocomplete="off" spellcheck="false"></span></label>';                                                                
                                }
                                
                                echo '<label><span class="title">Type Device: </span><span class="input-text-wrap"><input type="text" name="type" class="ptitle" value="' . $device['type'] . '"></span></label>';
                                echo '<label><span class="title">Expiry: </span><span class="input-text-wrap"><input type="text" class="date_picker" name="expiry" value="' . $device['expiry'] . '" autocomplete="off" spellcheck="false"></span></label>';
                                echo '</div>';
                                echo '</fieldset>';
                                echo '<fieldset class="inline-edit-col-left">';
                                //echo '<legend class="inline-edit-legend">-----</legend>';                                
                                echo '<div class="inline-edit-col">';
                                echo '<label><span class="title">Recharge: </span><span class="input-text-wrap"><input type="text" class="date_picker" name="date-recharge" value="' . $device['date-recharge'] . '" autocomplete="off" spellcheck="false"></span></label>';                                
                                
                                echo '<label><span class="title">Remarks: </span><span class="input-text-wrap"><input type="text" name="remarks" value="' . $device['remarks'] . '" autocomplete="off" spellcheck="false"></span></label>';
                                if ( current_user_can( 'administrator' ) ){

                                echo '<label><span class="title">Status: </span><span class="input-text-wrap"><select name="status">' . get_selected_status( $device['status'] ) . '</select></span></label>';
                                }
                                echo '</div>';
                                echo '</fieldset>';
                                echo '<input type="hidden" name="table-app" value="' . $_POST['app'] . '">';
                                echo '<input type="hidden" name="device_id" value="' . $device['id'] . '">';                                
                                echo '</form>';
                                echo '<div style="clear: both; margin-bottom:20px">';
                                echo '<button data-id=' . $device['id'] . ' type="button" class="annuler button cancel">Annuler</button>';                                
                                echo '<button data-id=' . $device['id'] . ' type="button" class="update-rapide button button-primary save ">Mettre à jour</button>';
                                echo '<span class="stargps-spinner spinner-' . $device['id'] . '"></span>';
                                echo '<span class="confirm-' . $device['id'] . '"></span>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                                }
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
                $table_name = $_POST['table'];
                $device_id = $_POST['device_id'];
                
                $eighty_days_from_now = date("Y-m-d" , strtotime( date( "Y-m-d", strtotime( "+80 day" ) ) ) );
                $where = " WHERE `id` = '" . $device_id . "' AND  STR_TO_DATE( `next-recharge` , '%d-%m-%Y') <= '" . $eighty_days_from_now . "' ";
		$sql = "SELECT * FROM `{$table_name}` " . $where . " ;"; 
		$result = $wpdb->get_results( $sql , ARRAY_A );                
                                            
                if ( count( $result )  === 1 ){
                    echo json_encode(['re' => '1']);
                    
                    exit();
                }
                
                               
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
                	echo json_encode(['re' => 'OK!']);
		

			exit();
		}            
	}
	public function stargps_device_management_confirm_send_recharge_sim_xlsx(){
                
                               
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
                	echo json_encode(['re' => 'OK!']);
		

			exit();
		}            
	}        
	public function stargps_device_management_send_valider_recharge_sim_xlsx(){
		global $wpdb;
                                
		$table_devices = $_POST['table'];                
		$device_id = $_POST['device_id']; 
       
		$where_clause = "  WHERE id =" . $device_id;
		$effectiveDate = strtotime( "+80 days", strtotime( date ( "d-m-Y" ) ) );
		$next_recharge = date( "d-m-Y", $effectiveDate );
		$sql_query = "UPDATE `{$table_devices}` SET `next-recharge` = '" . $next_recharge . "' , `date-recharge`='" . date( "d-m-Y") . "'" . $where_clause . " ;";
                $wpdb->query( $sql_query );
                
		
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
        public function generate_form_rows(){
            
            
            if( empty( $_POST['app'] ) || empty( $_POST['num_rows'] ) ){
            
                echo '<div class="notice notice-error is-dismissible"><p>Selectionner une Application ou un nombre valide </p></div>';
                exit();
            }
            
            $num_rows = $_POST['num_rows'];
            $app = $_POST['app'];
            $plus_one_year = strtotime( "+1 year", strtotime( date ( "d-m-Y" ) ) );
            $expiry_date = date( "d-m-Y", $plus_one_year );
                
            $date_recharge = date ( "d-m-Y" );
		
            $plus_80_days = strtotime( "+80 days", strtotime( date ( "d-m-Y" ) ) );
            $next_recharge = date( "d-m-Y", $plus_80_days ); 
            
            $html=  '';
            for ( $i = 0 ; $i < $num_rows ; $i++ ){            
                $html .= '<form name="new_devices">';
                $html .= '<div class="line">';
                $html .= '<input type="text" name="customer-name" placeholder="Customer name" />';
                $html .= '<input type="text" name="login" placeholder="Login" />';
                $html .= '<input type="text" name="tel_ctl" placeholder="Tel Client" />';
                $html .= '<input type="text" name="target_name" placeholder="Target name" />';
                $html .= '<input type="text" name="idimei" placeholder="IDIMEI" />';
                $html .= '<input type="text" name="sim_no" placeholder="SIM No" />';
                $html .= '<input type="text" name="type" placeholder="Type" />';
                $html .= '<input type="text" name="expiry_date" value="' . $expiry_date . '" />';
                $html .= '<input type="text" name="sim_op" placeholder="SIM Operateur" />';
                $html .= '<input type="text" name="date_recharge"  value="' . $date_recharge . '" />';
                $html .= '<input type="text" name="next_recharge"  value="' . $next_recharge . '" />';                
                $html .= '<input type="text" name="remarks" placeholder="Remarks" />';
                
                $html .= '<select name="status">'
                        . '<option value="active" selected>Enable</option>'
                        . '<option value="disabled">Pause</option>'
                        . '<option value="expired">Expired</option></select>';
                $html .= '<span class="stargps-spinner"></span></div>';
                $html .= '<input type="hidden" name="num_rows" value="' . $num_rows . '">';
                $html .= '<input type="hidden" name="selected_app" value="' . $app . '">';
                $html .= '</form>';
            }
            $html .= '<a id="add_new_devices" class="stargps-devices-management-btn">Submit</a>';
            echo $html;
            exit();
        }
        public function add_new_devices(){
		global $wpdb;
                                
		$number_array = count( $_POST['new_devices'] );
                
		$app_key = $number_array - 1;
		$table_name = $_POST['new_devices'][$app_key]['value'];
                
//               echo '<pre>';
//               var_dump($_POST);
//               echo '</pre>';
//               exit();
			$data = array(
				'customer-name' =>  $_POST['new_devices'][0]['value'] , 
				'login' =>  $_POST['new_devices'][1]['value'] ,
				'tel-clt' =>  $_POST['new_devices'][2]['value'] ,
				'target-name' =>  $_POST['new_devices'][3]['value'] ,
				'idimei' =>  $_POST['new_devices'][4]['value'] ,
				'sim-no' =>  $_POST['new_devices'][5]['value'] ,
				'type' =>  $_POST['new_devices'][6]['value'] ,
				'expiry' => $_POST['new_devices'][7]['value'],
				'sim-op' =>   $_POST['new_devices'][8]['value'] ,
				'date-recharge' => $_POST['new_devices'][9]['value'],
				'next-recharge' => $_POST['new_devices'][10]['value'],
				'remarks' =>  $_POST['new_devices'][11]['value'],
				'status' => $_POST['new_devices'][12]['value'],
				'app' =>  $_POST['new_devices'][14]['value'] ,                                    
				
				 );
                        
			if( check_sim_no_new_device( $_POST['new_devices'][5]['value'], $table_name ) ){
				echo json_encode( ['re' => 'duplicate_sim_no'] );
				exit();    
			}
			if(check_idimei_new_device( $_POST['new_devices'][4]['value'], $table_name ) ){
				echo json_encode( ['re' => 'duplicate_idimei'] );
				exit();    
			}                        
                        
			if( $wpdb->insert( $table_name, $data) ){    
					echo json_encode(['re' => 'yes']);
				}else{
					echo json_encode(['re' => 'no']);
                                }

            exit();
        }
	public function relancer() {
		global $wpdb;
                
		$table_devices = '`' . $_POST['app'] . '`';
                //$mois = '`' . $_POST['mois'] . '`';
                
                if( empty( $_POST['app'] )  ){
                    echo '<div class="notice notice-error is-dismissible"><p>Selectionner une Application </p></div>';
                    exit();
                }
                

                $order_by = "";                
                $where = ' WHERE 1=1';
                $title = "";
                if( $_POST['mois'] === 'mois_en_cours' ) {
                    
                    $where .= " AND month(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = month(curdate() ) AND year(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = year(curdate()) ";
                    $title = "Devices date d'expiration du mois:" . date('m');
                    
                } else if( $_POST['mois'] === 'mois_prochain' ){
                    
                    $next_month = date('m',strtotime('+1 month'));
                    $where .= " AND month(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = '". $next_month . "' AND year(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = year(curdate())  ";
                    $title = "Devices date d'expiration du mois:" . $next_month;
                    
                }else if ($_POST['mois'] === 'mois_dernier' ){
                   
                    $last_month = date('m',strtotime('-1 month'));
                    $where .= " AND month(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = '". $last_month . "' AND year(STR_TO_DATE( `expiry` , '%d-%m-%Y') ) = year(curdate()) ";
                    $title = "Devices date d'expiration du mois:" . $last_month;
                }
                    if( ! empty( $_POST['date_recharge'] ) ){
                        $where.= get_length_date( $_POST['date_recharge'] , 'date-recharge' ); 
                       //$where.= " AND `date-recharge` = '". $_POST['date_recharge'] ."'";
                    }
                    if( ! empty( $_POST['next_recharge'] ) ){
                        $where.= get_length_date( $_POST['next_recharge'] , 'next-recharge' ); 
//                       $where.= " AND `next-recharge` = '". $_POST['next_recharge'] ."'";
                    }
                    if( ! empty( $_POST['expiry_date'] ) ){
                        $where.= get_length_date( $_POST['expiry_date'] , 'expiry' ); 
//                       $where.= " AND `expiry` = '". $_POST['expiry_date'] ."'";
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
                    if( $_POST['status'] != 'all' ){
                        $where.= " AND `status` = '". $_POST['status'] ."'"; 
                    }                    
                    if ( $_POST['order_by'] === 'next-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `next-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'date-recharge' ){
                        $order_by = " ORDER BY STR_TO_DATE( `date-recharge` , '%d-%m-%Y') "  . $_POST['order'];
                    }else if( $_POST['order_by'] === 'expiry' ){
                        $order_by = " ORDER BY STR_TO_DATE( `expiry` , '%d-%m-%Y') "  . $_POST['order'];
                    }else{
                        $order_by = " ORDER BY `" .  $_POST['order_by'] . "` " . $_POST['order'];
                    }                    
             
		$sql = "SELECT * FROM {$table_devices} " . $where .  $order_by . " ;";
                
                // echo $sql;
                // exit();
		$devices = $wpdb->get_results( $sql , ARRAY_A );


                $row_increment = 1; 
		if ( is_array( $devices ) && count( $devices ) ){
				echo '<h2>' . $title . ' <br>Nombre de resultat: ' . count( $devices ) . '</h2><br>';
                                echo stargps_device_management_head_table_xlsx( 'devices' );
				echo '<tbody id="the-list">'; 
				foreach ( $devices as $device ) {
                                    $no_need = ( DateTime::createFromFormat('d-m-Y', $device['date-recharge'] ) === false ) ? 'style="background-color: #b2b0c8;"' : '';
                                    echo '<tr ' . $no_need. '>';
                                    echo '<td><b>' . $row_increment . '</b></td>';  
                                     if( $device['status']  === 'active' ){
                                        $status = '<td><span class="dashicons dashicons-saved"></span></td>';                                    
                                    } else if ( $device['status']  === 'disabled' ){
                                        $status = '<td><span class="dashicons dashicons-controls-pause"></span></td>';                                    
                                    } else if ( $device['status']  === 'expired' ){
                                    
                                        $status = '<td><span class="dashicons dashicons-warning"></span></td>';                                    
                                    } else if ( $device['status']  === 'removed' ){
                                    
                                        $status = '<td><span class="dashicons dashicons-no"></span></td>';                                    
                                    }
                                    echo $status;
                                    echo '<td>' . $device['id'] . '</td>';
                                    
                                    echo '<td>' . $device['customer-name'] . '</td>';
                                    echo '<td>' . $device['login'] . '</td>';
                                    echo '<td>' . $device['tel-clt'] . '</td>';
                                    echo '<td>' . $device['target-name'] . '</td>';
                                    echo '<td>' . $device['idimei'] . '</td>';
                                    if( ! empty ( $no_need ) ){
                                        echo '<td>Off</<td>';
                                    }else{
                                    echo '<td>'.
                                        $device['sim-no']
                                        //'<br><button  type="button" title="Send recharge " data-sim-no="'. $device['sim-no'] .'" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '" class="send-sim-recharge cpanelbutton dashicons dashicons-controls-play"></button> '
                                        ///.'<span class="stargps-spinner spinner-small"></span>'
                                        //.'<br><button  type="button" data-id="' . $device['id'] . '" data-table="' . $_POST['app'] . '"  class="send-sim-valider cpanelbutton dashicons dashicons-saved"></button>'
                                        //.'<br><button  type="button" class="send-sim-reload cpanelbutton dashicons dashicons-image-rotate"></button>'
                                        .'</td>';
                                        
                                    }                                    
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
        public function update_rapide(){
		global $wpdb;
		
                // Admin
                if ( current_user_can( 'administrator' ) ){
//                    echo '<pre>';
//                    var_dump($_POST);
//                    echo '</pre>';
//                    exit();
                    $table_name = $_POST['data_form'][11]['value'];
                    $sim_no = trim( $_POST['data_form'][3]['value'] );
                    $device_id = $_POST['data_form'][12]['value'];
                    
                    if( check_sim_no($device_id, $sim_no, $table_name ) ){
                        echo json_encode(['re' => 'duplicate_sim_no']);
                        exit();
                        
                    } else {
                        if( isset( $_POST['data_form'][8]['value'] ) && !empty( trim ( $_POST['data_form'][8]['value'] ) ) ){
                            $date_recharge = trim ( $_POST['data_form'][8]['value'] ) ;                                 
                        }else{
                            $date_recharge = date("d-m-Y");
                        }                        
                        if( isset( $_POST['data_form'][5]['value'] ) && !empty( trim ( $_POST['data_form'][5]['value'] ) ) ){
                            $next_recharge = $_POST['data_form'][5]['value'] ;                                 
                        }else{
                            $next_recharge = date("d-m-Y" , strtotime( "+80 days", strtotime( $date_recharge   ) ) );
                        }
                        
			$data = array(
				'customer-name' =>  $_POST['data_form'][0]['value'] , 
				'tel-clt' =>  $_POST['data_form'][1]['value'] ,
				'target-name' =>  $_POST['data_form'][2]['value'] ,
				'sim-no' =>  $_POST['data_form'][3]['value'] ,
                                '#' =>  $_POST['data_form'][4]['value'] ,
				'type' =>  $_POST['data_form'][6]['value'] ,
				'expiry' => $_POST['data_form'][7]['value'] ,
				'date-recharge' => $date_recharge ,
				'next-recharge' => $next_recharge ,                                 
				'remarks' =>  $_POST['data_form'][9]['value'],
                            'status' =>  $_POST['data_form'][10]['value']);  
                        
                        $where = ['id' => $device_id ];
                        
                        if( $wpdb->update( $table_name, $data, $where )){
                           echo json_encode(['re' => 'yes']);
                        }else{
                           echo json_encode(['re' => 'no']);
                        }
                        exit();                        
                    }
                    
                    
                // User  
                }else{
                    $table_name = $_POST['data_form'][7]['value'];
                    $device_id = $_POST['data_form'][8]['value'];                    
                    if( isset( $_POST['data_form'][5]['value'] ) && !empty( trim ( $_POST['data_form'][5]['value'] ) ) ){
                            $date_recharge = trim ( $_POST['data_form'][5]['value'] ) ; 
                            $next_recharge = date("d-m-Y" , strtotime( "+80 days", strtotime( $date_recharge   ) ) );
                    }else{
                            $date_recharge = date("d-m-Y");
                            $next_recharge = date("d-m-Y" , strtotime( "+80 days", strtotime( date("d-m-Y")   ) ) );
                    }                                                        
			$data = array(
				'customer-name' =>  $_POST['data_form'][0]['value'] , 
				'tel-clt' =>  $_POST['data_form'][1]['value'] ,
				'target-name' =>  $_POST['data_form'][2]['value'] ,
				'type' =>  $_POST['data_form'][3]['value'] ,
				'expiry' => $_POST['data_form'][4]['value'] ,
				'date-recharge' => $date_recharge ,
				'next-recharge' => $next_recharge ,                                 
				'remarks' =>  $_POST['data_form'][6]['value']   );  
                        
                        $where = ['id' => $device_id ];
                        
                        if( $wpdb->update( $table_name, $data, $where )){
                           echo json_encode(['re' => 'yes']);
                        }else{
                           echo json_encode(['re' => 'no']);
                        }
                        exit();                    
                }
                               

        }
        public function update_dialog_form(){
		global $wpdb;
		$table_name = $_POST['data_form'][1]['value'];
                $device_ids = explode('-', $_POST['data_form'][0]['value'] );

                $n = count($device_ids);
                $condition = 0;
                $data = array();
                
//                echo '<pre>';
//                var_dump($_POST['data_form']);
//                echo '</pre>';
//                exit();
                for ( $i = 0; $i < $n -1 ; $i++){
                  
                  if( $_POST['data_form'][2]['value'] != "0"){
                      $data['customer-name'] = $_POST['data_form'][2]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][3]['value'] != "0"){
                      $data['login'] = $_POST['data_form'][3]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][4]['value'] != "0"){
                       $data['tel-clt'] = $_POST['data_form'][4]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][5]['value'] != "0"){
                      $data['target-name'] = $_POST['data_form'][5]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][6]['value'] != "0"){
                       $data['type'] = $_POST['data_form'][6]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][7]['value'] != "0"){
                      $data['date-recharge'] = $_POST['data_form'][7]['value']; 
                      $condition++;
                  }                  
                  if( $_POST['data_form'][8]['value'] != "0"){
                      $data['expiry'] = $_POST['data_form'][8]['value']; 
                      $condition++;
                  }
                  if( $_POST['data_form'][9]['value'] != "0"){
                      $data['sim-op'] = $_POST['data_form'][9]['value'];
                      $condition++;
                  }
                  if( $_POST['data_form'][10]['value'] != "0"){
                      $data['remarks'] = $_POST['data_form'][10]['value'];
                      $condition++;
                  } 
                  
                  if ( $condition === 0){
                      break;
                  } 
                  $where = ['id' => $device_ids[$i] ];
                  if( ! $wpdb->update( $table_name, $data, $where )){
                       echo json_encode(['re' => 'no']);
                       exit();
                  }
                }                
                if( $condition === 0){
                    echo json_encode(['re' => 'no-change']);
                    exit();
                }
                echo json_encode(['re' => 'yes']);
                
                exit();               

        }
        public function delete_devices(){
		global $wpdb;
                
		$table_name = $_POST['app'];
                $device_ids = explode('-', $_POST['device_ids']);

                //$ids = implode( ',', array_map( 'absint', $device_ids ) );
                $ids = array_map( 'absint', $device_ids ) ;
                $n = 0;
                foreach ( $ids as $value ) {
                    if( $value === 0 ){
                        continue;
                    } 
                    if (get_device_status( $value, $table_name )[0]['status'] === 'removed' ){
                        $sql = "DELETE FROM `{$table_name}` WHERE `id` ='" . $value . "'; ";
                       if( $wpdb->query( $sql ) ){
                        $n++;
                       }
                        
                    } else {
			$data = array(
                            'status' => 'removed');  
                        
                        $where = ['id' => $value ];
                        
                        $wpdb->update( $table_name, $data, $where );                     
                    }
                
                                    
                }
//                exit();
//                $sql = "DELETE FROM `{$table_name}` WHERE ID IN($ids)";
//                
//                
//                if( $n = $wpdb->query( $sql ) ){
                    echo json_encode(['re' => 'yes' , 'n' => $n]);
                    
//                }else{
//                       echo json_encode(['re' => 'no']);                    
//                       
//                }
                                
                
                exit();               

        }        
}
