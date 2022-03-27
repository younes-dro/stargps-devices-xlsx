<?php
/**
 * Class to handle log of API errors
 */
class Logs {
	function __construct() {
		// Clear all existing logs.
		
	}

	/**
	 * Static property to define log file name
	 *
	 * @param None
	 * @return string $log_file_name
	 */
	public static $log_file_name = 'class-stargps-devices-management.txt';

	/**
	 * Clear previous logs history
	 *
	 * @param None
	 * @return None
	 */


	/**
	 * Add API error logs into log file
	 *
	 * @param array  $response_arr
	 * @param array  $backtrace_arr
	 * @param string $error_type
	 * @return None
	 */
	public static function write_api_response_logs( $response_arr, $backtrace_arr = array() ) {

		$error        = current_time( 'mysql' );


		$uuid             = get_option( 'stargps-devices-management_uuid_file_name' );
		$log_file_name    = $uuid . self::$log_file_name;
		if( is_array( $response_arr ) ){
                    
                    $error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['error'];
                    file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
                                            
		}


	}
}

