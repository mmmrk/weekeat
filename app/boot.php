<?php
	class Boot extends Application {
		public static $db;

		public $params, $route;
		public $error, $current_date, $display_date, $todays_dishes, $site_params, $view_data;

		public function __construct () {
			$this->error = false;
			$this->current_date = date('Y-m-d');
			
			$this->set_params();

			$this->set_route($this->params['GET']);
			$this->collect_site_params($this->params);
			$this->set_display_date_from_collection($this->site_params['GET']);
			
			self::db_init(DbConfig::$server, DbConfig::$database, DbConfig::$username, DbConfig::$password);
		}

		public function set_params () {
			$this->params = array(
				'GET' => $_GET,
				'POST' => $_POST
			);
		}

		public static function db_init ($server, $database, $username, $password) {
			if (!isset(self::$db)) {
				self::$db = new dbh($server, $username, $password);
				self::$db->use_db($database);
			}
		}

		public function call_controller ($controller, $function, $args=false) {
			$safe_controller = ucfirst($controller) . 'Controller';

			if (!is_callable($safe_controller, $function))
				return false;

			$safe_args = (!is_array($args)) ? array($args) : $args;

			return call_user_func_array(array($safe_controller, $function), $safe_args);
		}

		public function call_controller_with_page ($controller, $page, $args=false) {
			if (!$this->page_exists($controller, $page))
				return false;

			return $this->call_controller($controller, $page, $args);
		}

		private function collect_site_params ($params) {
			$this->site_params = array ();

			foreach ($params as $param_collection => $collection) {
				if (empty($collection))
					$this->site_params[$param_collection] = array();
				else {
					if (!array_key_exists($param_collection, $this->site_params))
						$this->site_params[$param_collection] = array();

					foreach ($collection as $param_key => $param_value)
						if ($param_key != 'section' && $param_key != 'page' && $param_key != 'action')
							$this->site_params[$param_collection][$param_key] = $param_value;
				}
			}
		}

		private function set_route ($params) {
			$this->route = array(
				'section' => $this->get_current_section($params),
				'page' => $this->get_current_page($params),
				'action' => $this->get_current_action($params)
			);
		}
		
		private function section_exists ($section) {
			return array_key_exists($section, AppConfig::$sitemap);
		}

		private function page_exists ($section, $page) {
			return ($this->section_exists($section) && array_key_exists($page, AppConfig::$sitemap[$section]));
		}

		private function action_exists($section, $page, $action) {
			return ($this->page_exists($section, $page) && in_array($action, AppConfig::$sitemap[$section][$page]));
		}

		public function get_current_section ($url_params) {
			if (!isset($url_params['section']))
				return AppConfig::$default_section;

			return ($this->section_exists($url_params['section'])) ? $url_params['section'] : AppConfig::$default_section;
		}

		public function get_current_page ($url_params) {
			if (!isset($url_params['page']))
				return AppConfig::$default_page[$this->get_current_section($url_params)];

			return (isset($url_params['page']) || $this->page_exists($url_params['section'], $url_params['page'])) ? $url_params['page'] : AppConfig::$default_page[$this->get_current_section($url_params)];
		}

		public function get_current_action ($url_params) {
			if (!isset($url_params['action']))
				return false;

			return (isset($url_params['action']) && $this->action_exists($url_params['section'], $url_params['page'], $url_params['action'])) ? $url_params['action'] : false;
		}

		public function get_current_url ($with_section=false, $with_page=false, $with_action=false, $with_site_GET_params=false) {
			$url = $_SERVER['PHP_SELF'];

			$url .= ($with_section) ? '?section=' . $this->route['section'] : '';
			$url .= ($with_section && $with_page) ? '&page=' . $this->route['page'] : '';
			$url .= ($with_section && $with_page && $with_action) ? '&action=' . $this->route['action'] : '';

			if ($with_site_GET_params)
				foreach ($this->site_params['GET'] as $key => $value)
					$url .= '&' . $key . '=' . $value;


			return $url;
		}

		public function get_current_full_url () {
			return $this->get_current_url(true, true, true, true);
		}

		public function set_display_date($date) {
			$this->display_date = (is_date($date)) ? $date : $this->current_date;
		}

		private function set_display_date_from_collection ($collection) {
			$date = (array_key_exists('date', $collection)) ? $collection['date'] : $this->current_date;

			$this->set_display_date($date);
		}
	}

	if (!isset($db)) {
		$db = new dbh(DbConfig::$server, DbConfig::$username, DbConfig::$password);
		$db->use_db(DbConfig::$database);
	}

	$query  = 'SELECT * FROM dish ';
	$query .= 'JOIN meal ON dish.id = meal.dish_id ';
	$query .= 'WHERE meal.date = CURDATE()';

	$result = $db->query($query);

	if ($db->error) {
		$app_data['error']['id']	  = $db->errno;
		$app_data['error']['message'] = $db->error;
	}

	if ($result && $result->num_rows == null)
		$app_data['todays_dish'] = false;
	else if ($result->num_rows == 1)
		$app_data['todays_dish'] = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));

	$result->free();

	//$app = new AppController();
?>