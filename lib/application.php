<?php
	abstract class Application {
		public static $db;
		public $params;

		public static abstract function db_init($server, $database, $username, $password);

		abstract function set_params();

		abstract function call_controller($ontroller, $function, $args);

		public static function current_domain () {
			return substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
		}

		public static function current_page () {
			return $_SERVER['SCRIPT_NAME'];
		}
		
		public static function current_url () {
			return $_SERVER['PHP_SELF'];
		}
		
		public static function current_url_full () {
			return $_SERVER['REQUEST_URI'];
		}

		public static function get_request_method () {
			return $_SERVER['REQUEST_METHOD'];
		}

		// may not be needed
		public static function get_request_path () {
			return !is_null(@$_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		}

	}
?>