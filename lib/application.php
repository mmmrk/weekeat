<?php
	abstract class Application {
		public $db, $request_method, $params;

		abstract function set_params();

		abstract function db_init($server, $database, $username, $password);

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

	}
?>