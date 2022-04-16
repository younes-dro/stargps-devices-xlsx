<?php

/*
* common functions file for Xlsx.
*/

function stargps_device_management_get_files_xlsx(){
    
	$stargp_xlsx_folder = trailingslashit( wp_upload_dir()['basedir'] ) . STARGPSDEVICESMANAGEMENT_XLSX_FOLDER;
    
	if ( is_dir( $stargp_xlsx_folder ) ){
		$table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th scope="col" class="manage-column ">Nom ficher </th>';
		$table .= '<th scope="col"  class="manage-column ">Size</th>';
		$table .= '<th scope="col"  class="manage-column ">Run</th>';
                if ( current_user_can( 'administrator' ) ) {
		$table .= '<th scope="col"  class="manage-column ">Supprimer</th>'; 
                }
		$table .= '</tr>'; 
		$table .= '</thead>';
		$table .= '<tbody id="the-list">';
                
		$files =  list_files( $stargp_xlsx_folder);
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				$filesize = size_format( filesize( $file ) );
				$filename = basename( $file );
				$table .= '<tr id="table_' . $filename . '">';
				$table .= '<td>' . $filename . '</td>';
				$table .= '<td>' . $filesize . '</td>';                                
				$table .= '<td><button data-name="' . $filename . '"  type="button" title="Lancer"  class="import-xlxs dashicons dashicons-controls-play"></button></td>';                                                                
                                if ( current_user_can( 'administrator' ) ) {
				$table .= '<td><button data-name="' . $filename . '"  type="button" title="Lancer"  class="delete-xlxs dashicons dashicons-table-col-delete"></button><span class="spinner-small stargps-spinner"></span></td>';                                                                
                                }
				$table .= '</tr>';
			}
		}
		$table .= '</tbody>'; 
		$table .= '</table>';
                
                echo $table;
	}
}
/**
 * Get List table  
 */
function stargps_device_management_get_table_select_menu(){
	global $wpdb;
	$TABLE_SCHEMA =  $wpdb->dbname;
	$PREFIX = $wpdb->prefix;        
        
	$table_xlsx = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" .$TABLE_SCHEMA . "' AND TABLE_NAME LIKE '" . $PREFIX . "xlsx_%'";
        
	$tables = $wpdb->get_results( $table_xlsx, ARRAY_A ); 
        
	if ( is_array( $tables ) && count( $tables ) ) {
	?>
	<label>Application: </label>
            <select name="app" id="app">
                <option id="" value=""> - </option>
	<?php foreach ( $tables as $key => $table ) { ?>
		<option value="<?php echo $table['TABLE_NAME'] ?>"><?php echo $table['TABLE_NAME'] ?></option>                
	<?php } ?>
	</select>         
	<?php             
	}else{
		echo 'Pas de Table';
	}
}
function stargps_device_management_get_table_select_menu_80(){
	global $wpdb;
	$TABLE_SCHEMA =  $wpdb->dbname;
	$PREFIX = $wpdb->prefix;        
        
	$table_xlsx = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" .$TABLE_SCHEMA . "' AND TABLE_NAME LIKE '" . $PREFIX . "xlsx_%'";
        
	$tables = $wpdb->get_results( $table_xlsx, ARRAY_A ); 
        
	if ( is_array( $tables ) && count( $tables ) ) {
	?>
	<label>Application: </label>
            <select name="app" id="app_80">
                <option id="" value=""> - </option>
	<?php foreach ( $tables as $key => $table ) { ?>
		<option value="<?php echo $table['TABLE_NAME'] ?>"><?php echo $table['TABLE_NAME'] ?></option>                
	<?php } ?>
	</select>         
	<?php             
	}else{
		echo 'Pas de Table';
	}
}
function stargps_device_management_get_table_select_menu_new_devices(){
	global $wpdb;
	$TABLE_SCHEMA =  $wpdb->dbname;
	$PREFIX = $wpdb->prefix;        
        
	$table_xlsx = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" .$TABLE_SCHEMA . "' AND TABLE_NAME LIKE '" . $PREFIX . "xlsx_%'";
        
	$tables = $wpdb->get_results( $table_xlsx, ARRAY_A ); 
        
	if ( is_array( $tables ) && count( $tables ) ) {
	?>
	<label>Application: </label>
            <select name="app" id="app_new_devices">
                <option id="" value=""> - </option>
	<?php foreach ( $tables as $key => $table ) { ?>
		<option value="<?php echo $table['TABLE_NAME'] ?>"><?php echo $table['TABLE_NAME'] ?></option>                
	<?php } ?>
	</select>         
	<?php             
	}else{
		echo 'Pas de Table';
	}
}
function  stargps_device_management_head_table_xlsx( $from ='' , $select_all = false ){
    
	$check_box = '';
	$status = '';    
	if( $select_all ){
		$check_box = '<th scope="col" class="manage-column "><input type="checkbox" id="checkAll" /></th>'; 
                $status = '<th scope="col" class="manage-column ">Status</th>'; 
	}
	
   

	$table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
	$table .= '<thead>';
	$table .= '<tr>';
        $table .= $check_box;
	$table .= '<th scope="col" class="manage-column ">N°</th>';
        $table .= $status;        
	$table .= '<th scope="col" class="manage-column ">ID</th>';
	$table .= '<th scope="col"  class="manage-column ">Customer</th>';
//	$table .= '<th scope="col"  class="manage-column ">B</th>';
	$table .= '<th scope="col"  class="manage-column ">Login</th>';
	$table .= '<th scope="col"  class="manage-column ">Num tel</th>';
	$table .= '<th scope="col"  class="manage-column ">Target</th>';
	$table .= '<th scope="col"  class="manage-column ">IMEI</th>';
	$table .= '<th scope="col"  class="manage-column column-sim-no ">SIM no</th>';
	$table .= '<th scope="col"  class="manage-column ">Type device</th>';
	$table .= '<th scope="col"  class="manage-column ">Expiry</th>';
	$table .= '<th scope="col"  class="manage-column ">Operator</th>';
	$table .= '<th scope="col"  class="manage-column ">Date recharge</th>';
	$table .= '<th scope="col"  class="manage-column ">Next recharge</th>'; 
	$table .= '<th scope="col"  class="manage-column ">App</th>'; 
	$table .= '<th scope="col"  class="manage-column ">Remarks</th>'; 
	$table .= '</tr>';        
	$table .= '</thead>';
	
        return $table;
}

function  stargps_device_management_head_remove_data_table() {
    
    global $wpdb;
	
    $tables_xlsx = "show tables like '%_xlsx_%';";
    $tables = $wpdb->get_results( $tables_xlsx, ARRAY_A );
    
	$table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
	$table .= '<thead>';
	$table .= '<tr>';
	$table .= '<th scope="col"  class="manage-column ">Table name</th>';
//	$table .= '<th scope="col"  class="manage-column ">Vider</th>';
	$table .= '<th scope="col"  class="manage-column ">Supprimer</th>';
	$table .= '</tr>';        
	$table .= '</thead>'; 
	$table .= '<tbody id="the-list">';        
    
    foreach ($tables as $key => $value) {
        foreach($value as $v){
		$table .= '<tr id="table_'.$v.'">';
		$table .= '<td>' . $v . '</td>';                              
		$table .= '<td><button data-name="' . $v . '"  type="button" title="Lancer"  class="delete-table dashicons dashicons-table-row-delete"></button><span class="spinner-small stargps-spinner"></span></td>';                                                                
		$table .= '</tr>';
           
        }
    }
    
    $table .= '</tbody>'; 
    $table .= '</table>';
                
    echo $table;
    
    
}
function stargps_device_management_get_table_select_menu_relancer(){
	global $wpdb;
	$TABLE_SCHEMA =  $wpdb->dbname;
	$PREFIX = $wpdb->prefix;        
        
	$table_xlsx = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" .$TABLE_SCHEMA . "' AND TABLE_NAME LIKE '" . $PREFIX . "xlsx_%'";
        
	$tables = $wpdb->get_results( $table_xlsx, ARRAY_A ); 
        
	if ( is_array( $tables ) && count( $tables ) ) {
	?>
	<label>Application: </label>
            <select name="app" id="app_relancer">
                <option id="" value=""> - </option>
	<?php foreach ( $tables as $key => $table ) { ?>
		<option value="<?php echo $table['TABLE_NAME'] ?>"><?php echo $table['TABLE_NAME'] ?></option>                
	<?php } ?>
	</select>         
	<?php             
	}else{
		echo 'Pas de Table';
	}
}
/**
 * To check  sim no when updating device 
 *
 * @param INT $device_id Device id
 * @param INT $sim_no Device sim number
 * @param STRING $app Table name
 * @return BOOL|ARRAY $device
 */
function check_sim_no ( $device_id, $sim_no, $app){
    
	global $wpdb;
	$sql_query = "SELECT * FROM `{$app}` WHERE `sim-no` = '" . $sim_no . "' AND `id` !=" . $device_id . ";"; 
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( is_array( $result ) ){
		$device = $result;
	}else{
		$device = false;
	}
        
	return $device;
}
/**
 * To check  sim no when adding new device 
 *
 * @param INT $sim_no Device sim number
 * @param STRING $app Table name
 * @return BOOL|ARRAY $device
 */
function check_sim_no_new_device (  $sim_no, $app ){
    
	global $wpdb;
	$sql_query = "SELECT * FROM `{$app}` WHERE `sim-no` = '" . trim( $sim_no ) . "';"; 
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( is_array( $result ) ){
		$device = $result;
	}else{
		$device = false;
	}
        
	return $device;
}
/**
 * To check  IDIMEI when adding new device 
 *
 * @param INT $idimei Device idimei
 * @param STRING $app Table name
 * @return BOOL|ARRAY $device
 */
function check_idimei_new_device (  $idimei, $app ){
    
	global $wpdb;
	$sql_query = "SELECT * FROM `{$app}` WHERE `idimei` = '" . trim( $idimei ) . "';"; 
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( is_array( $result ) ){
		$device = $result;
	}else{
		$device = false;
	}
        
	return $device;
}
function get_selected_status( $status ){
	$options_status = '';
	if( $status === 'active' ){
		$options_status .=   '<option value="active" selected>Enable</option>';  
	} else {
		$options_status .=   '<option value="active">Enable</option>';  
	}
	if( $status === 'disabled' ){
		$options_status .=   '<option value="disabled" selected>Pause</option>';
	} else {
		$options_status .=   '<option value="disabled">Pause</option>';
	}
	if( $status === 'expired' ){
		$options_status .=   '<option value="expired" selected>Expired</option>';
	} else {
		$options_status .=   '<option value="expired">Expired</option>';
	}
	if( $status === 'removed' ){
		$options_status .=   '<option value="removed" selected>Removed</option>';
	} else {
		$options_status .=   '<option value="removed">Removed</option>';
	}
        
        return $options_status; 

}
function get_device_status ( $device_id , $app ){
	global $wpdb;
	$sql_query = "SELECT `status` FROM `{$app}` WHERE `id` = '" . trim( $device_id ) . "';"; 
	$result = $wpdb->get_results( $sql_query , ARRAY_A );
	if( is_array( $result ) ){
		$status = $result;
	}else{
		$status = false;
	}
        
	return $status;    
    
}

