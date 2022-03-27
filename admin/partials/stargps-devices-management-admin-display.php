<?php

/**
 * 
 */
?>
<?php
if ( isset( $_GET['save_devices_msg'] ) ) {
	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_devices_msg'] ); ?></p>
	</div>
	<?php
}
?>
<h1><img  class="stargps-devices-management-logo" src="<?php echo STARGPSDEVICESMANAGEMENT_PLUGIN_URL?>/assets/images/logo-medium.png" /><?php echo __( 'Stargps Devices Management', 'stargps-devices-management' ); ?></h1>
		<div id="stargps-devices-management-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="devices" ><?php echo __( 'Devices', 'stargps-devices-management' ); ?></button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="api" ><?php echo __( 'API', 'stargps-devices-management' ); ?></button>

				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php echo __( 'Paramètres', 'stargps-devices-management' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php echo __( 'Logs', 'stargps-devices-management' ); ?>	
				</button>
				</li>  
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="manuelle" ><?php echo __( 'Recharge manuelle', 'stargps-devices-management' ); ?>	
				</button>
				</li>                                
			
                        </ul>
			<div class="skltbs-panel-group">
				<div id="ets_stargps-devices-management_application_details" class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/pages/stargps_devices_management_devices.php'; ?>
				</div>     
				<div id="stargps-devices-management_api" class="stargps-devices-management-tab-conetent skltbs-panel wrap">
					<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/pages/stargps_devices_management_api.php'; ?>
				</div>

				<div id='stargps-devices-management_advanced' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/pages/stargps_devices_management_advanced.php'; ?>
				</div>
				<div id='stargps-devices-management_logs' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/pages/stargps_devices_management_error_log.php'; ?>
				</div> 
				<div id='stargps-devices-management_manuelle' class="stargps-devices-management-tab-conetent skltbs-panel">
				<?php require_once STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH . 'admin/partials/pages/stargps_devices_management_manuelle.php'; ?>
				</div>                            
			</div>  
		</div>