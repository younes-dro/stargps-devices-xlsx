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
function  stargps_device_management_head_table_xlsx( $from ='' ){
    $select_all_recharge= '';
	( $from === 'devices') ? $select_all_recharge = '<span class="select-all-recharge dashicons dashicons-saved"></span><span class="spinner-small"></span>' : '';
    
    
	$table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
	$table .= '<thead>';
	$table .= '<tr>';
	$table .= '<th scope="col" class="manage-column ">N°</th>';
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


