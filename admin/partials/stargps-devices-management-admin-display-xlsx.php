<?php

/**
 * 
 */
?>

<h1><img  class="stargps-devices-management-logo" src="<?php echo STARGPSDEVICESMANAGEMENT_PLUGIN_URL?>/assets/images/logo-medium.png" /><?php echo __( 'Fichiers xlsx', 'stargps-devices-management' ); ?></h1>
		<div id="stargps-devices-management-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="devices-xlsx" ><?php echo __( '<span class="dashicons dashicons-editor-table"></span> Devices', 'stargps-devices-management' ); ?></button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="devices-80-jours" ><?php echo __( '<span class="dashicons dashicons-calendar-alt"></span> 80 Jours', 'stargps-devices-management' ); ?></button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="new-devices" ><?php echo __( '<span class="dashicons dashicons-database-add"></span> Add devices', 'stargps-devices-management' ); ?></button>
				</li>                                
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="liste" ><?php echo __( '<span class="dashicons dashicons-database-import"></span> Import', 'stargps-devices-management' ); ?></button>

				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="upload" ><?php echo __( '<span class="dashicons dashicons-upload"></span> Upload', 'stargps-devices-management' ); ?>	
				</button>
				</li>
                                <?php if ( current_user_can( 'administrator' ) ){?>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="remove" ><?php echo __( '<span class="dashicons dashicons-database-remove"></span> Remove tables', 'stargps-devices-management' ); ?>	
				</button>
				</li>   
                                <?php } ?>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="upload" ><?php echo __( '<span class="dashicons dashicons-smartphone"></span> Recharge manuelle', 'stargps-devices-management' ); ?>	
				</button>
				</li>                                
                               
			
                        </ul>
			<div class="skltbs-panel-group">
				<div id="ets_stargps-devices-management_devices_xlsx" class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_devices_xlsx.php'; ?>
				</div>                            
				<div id="ets_stargps-devices-management_devices_80_jours" class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_80_jours.php'; ?>
				</div>
				<div id="ets_stargps-devices-management_new_devices" class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_new_devices.php'; ?>
				</div>                             
				<div id="stargps-devices-management_liste" class="stargps-devices-management-tab-conetent skltbs-panel wrap">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_import.php'; ?>
				</div>

				<div id='stargps-devices-management_import' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_upload.php'; ?>
				</div>
				<?php if ( current_user_can( 'administrator' ) ) :?>
				<div id='stargps-devices-management_remove' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_remove.php'; ?>
				</div> 
				<?php endif; ?>
				<div id='stargps-devices-management_manuelle' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/xlsx/stargps_devices_management_manuelle_xlsx.php'; ?>
				</div>                            
			</div>  
		</div>