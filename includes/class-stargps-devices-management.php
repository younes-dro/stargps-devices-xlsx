<?php
/**
 * 
 */

class Stargps_Devices_Management {
	 
	protected $plugin_name;

	protected $version;
        
	private static $instance;

	function __construct() {
        
	}

	public static function instance() {

		if ( ! self::$instance ) {
                    
			self::$instance = new Stargps_Devices_Management();                       
			if ( defined( 'STARGPSDEVICESMANAGEMENT' ) ) {
                            
				self::$instance->version = STARGPSDEVICESMANAGEMENT;
                                
			} else {
                            
				self::$instance->version = '1.0.0';
                                
			}
                        
			self::$instance->plugin_name = 'stargps-devices-management';
			self::$instance->load_dependencies();
			self::$instance->define_admin_hooks();
		}
        
	return self::$instance;
    }
    
	private function load_dependencies() {
        
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';            
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions_xlsx.php';                            
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sql_run_retrieve_devices.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-stargps-devices-management-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-stargps-devices-management-admin-xlsx.php';                
        
	}
        
	private function define_admin_hooks() {
		
		$plugin_admin = new Stargps_Devices_Management_Admin ( $this->get_plugin_name(), $this->get_version() ); 
		$plugin_admin_xlsx = new Stargps_Devices_Management_Admin_Xlsx ( $this->get_plugin_name(), $this->get_version() );                 
                
		add_action( 'admin_enqueue_scripts', array ( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array ( $plugin_admin, 'enqueue_scripts' ) );                
		add_action( 'admin_menu' , array ( $plugin_admin, 'stargps_devices_management_add_settings_menu' ) );
		add_action( 'wp_ajax_stargps_device_management_devices_date_recharge' , array ( $plugin_admin, 'stargps_devices_management_query_date_recharge' ) );
		add_action( 'wp_ajax_stargps_device_management_devices_run_wstargpsma' , array ( $plugin_admin, 'stargps_devices_management_run_wstargpsma' ) );                
		add_action( 'wp_ajax_stargps_device_management_update_sim_no' , array ( $plugin_admin, 'stargps_device_management_update_sim_no' ) );                                
		add_action( 'wp_ajax_stargps_device_management_send_recharge_sim' , array ( $plugin_admin, 'stargps_device_management_send_recharge_sim' ) );                                                
		add_action( 'wp_ajax_stargps_device_management_devices_connect_api' , array ( $plugin_admin, 'stargps_device_management_devices_connect_api' ) );                
		add_action( 'wp_ajax_stargps_device_management_devices_recharge_manuelle' , array ( $plugin_admin, 'stargps_device_management_devices_recharge_manuelle' ) );                                                                
		add_action( 'rest_api_init' , array ( $plugin_admin, 'stargps_device_management_devices_rest_api' ) );                                                                
                
		// Xlsx 
		add_action('wp_ajax_upload_xlsx', array ( $plugin_admin_xlsx , 'file_upload_callback' ) );
		add_action('wp_ajax_nopriv_upload_xlsx', array ( $plugin_admin_xlsx, 'file_upload_callback' ) );
		
                add_action('wp_ajax_refresh_xlsx', array ( $plugin_admin_xlsx , 'refresh_xlsx' ) );
		add_action('wp_ajax_nopriv_refresh_xlsx', array ( $plugin_admin_xlsx, 'refresh_xlsx' ) );  
		
                add_action('wp_ajax_import_xlsx', array ( $plugin_admin_xlsx , 'import_xlsx' ) );
		add_action('wp_ajax_nopriv_import_xlsx', array ( $plugin_admin_xlsx, 'import_xlsx' ) );
                
		add_action('wp_ajax_delete_xlxs', array ( $plugin_admin_xlsx , 'delete_xlxs' ) );
		add_action('wp_ajax_nopriv_delete_xlxs', array ( $plugin_admin_xlsx, 'delete_xlxs' ) );                  
		
                add_action( 'wp_ajax_stargps_device_management_devices_date_recharge_xlsx' , array ( $plugin_admin_xlsx, 'stargps_devices_management_query_date_recharge' ) );                
		add_action( 'wp_ajax_nopriv_stargps_device_management_devices_date_recharge_xlsx' , array ( $plugin_admin_xlsx, 'stargps_devices_management_query_date_recharge' ) );                
		
                add_action( 'wp_ajax_stargps_device_management_send_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_send_recharge_sim_xlsx' ) );                                                                
		add_action( 'wp_ajax_nopriv_stargps_device_management_send_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_send_recharge_sim_xlsx' ) );                                                                                
                
                add_action( 'wp_ajax_stargps_device_management_confirm_send_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_confirm_send_recharge_sim_xlsx' ) );                                                                
		add_action( 'wp_ajax_nopriv_stargps_device_management_confirm_send_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_confirm_send_recharge_sim_xlsx' ) );                
		
                add_action( 'wp_ajax_stargps_device_management_send_valider_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_send_valider_recharge_sim_xlsx' ) );                                                                
		add_action( 'wp_ajax_nopriv_stargps_device_management_send_valider_recharge_sim_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_send_valider_recharge_sim_xlsx' ) );                
		
		add_action('wp_ajax_stargps_device_management_remove_table', array ( $plugin_admin_xlsx , 'stargps_device_management_remove_table' ) );
		add_action('wp_ajax_nopriv_stargps_device_management_remove_table', array ( $plugin_admin_xlsx, 'stargps_device_management_remove_table' ) );                              
                                
                add_action( 'wp_ajax_stargps_device_management_date_recharge_80_jours' , array ( $plugin_admin_xlsx, 'stargps_devices_management_query_date_recharge' ) );                
		add_action( 'wp_ajax_nopriv_stargps_device_management_date_recharge_80_jours' , array ( $plugin_admin_xlsx, 'stargps_devices_management_query_date_recharge' ) );                  
               
		add_action( 'wp_ajax_stargps_device_management_devices_recharge_manuelle_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_devices_recharge_manuelle_xlsx' ) );                                                                                
		add_action( 'wp_ajax_nopriv_stargps_device_management_devices_recharge_manuelle_xlsx' , array ( $plugin_admin_xlsx, 'stargps_device_management_devices_recharge_manuelle_xlsx' ) );                                                                                                
                
		add_action('wp_ajax_confirm_upload', array ( $plugin_admin_xlsx , 'confirm_upload_callback' ) );
		add_action('wp_ajax_nopriv_confirm_upload', array ( $plugin_admin_xlsx, 'confirm_upload_callback' ) );
                
		add_action('wp_ajax_cancel_upload', array ( $plugin_admin_xlsx , 'cancel_upload_callback' ) );
		add_action('wp_ajax_nopriv_cancel_upload', array ( $plugin_admin_xlsx, 'cancel_upload_callback' ) );                 
	}
        
        public function stargps_device_management_remove_table(){
            
            echo 'ok';
            
            exit();
        }

                public function get_plugin_name() {
		
		return $this->plugin_name;
	}
        
	public function get_version() {
            
		return $this->version;
	}
}

