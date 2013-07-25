<?php
	class AppController {
		public $error, $current_date, $display_date, $todays_dishes, $site_params;

		public function __construct ($params) {
			$this->error = false;
			
			$this->collect_site_params($params);
			$this->request_method(get_request_method());

			$this->current_date(date('Y-m-d'));
			$this->set_display_date_from_collection($this->site_params['GET']);
		}

		private function collect_site_params ($params) {
			$this->site_params = array ();

			foreach ($params as $param_collection => $collection)
				foreach ($collection as $param_key => $param_value)
					if ($param_key != 'view' && $param_key != 'action')
						$this->site_params[$param_collection][$param_key] = $param_value;
		}

		private function set_route ($params) {
			$this->route = array(
				'section' => $this->get_current_section($params),
				'action' => $this->get_current_action($params)
			);
		}

		public function get_current_section ($url_string) {
			return (array_key_exists(AppConfig::$sitemap, $url_string['view'])) ? $url_string['view'] : AppConfig::$default_section;
		}

		public function get_current_action ($url_string) {
			return (in_array(AppConfig::$actions[$url_string['view']], $url_string['action'])) ? $url_string['action'] : AppConfig::$default_action[$url_string['action']];
		}

		public function set_display_date($date) {
			$this->display_date = (is_date($date)) ? $date : $this->current_date;
		}

		private function set_display_date_from_collection ($collection) {
			$date = (array_key_exists($collection, 'date')) ? $collection['date'] : $this->current_date;

			$this->set_display_date($date);
		}
	}

	$app_data = array (
		'error' => false,
		'curdate' => date('Y-m-d'),
		'page' => 'calendar'
	);

	if (isset($_GET['view']))
		switch ($_GET['view']) {
			case 'admin':
			case 'todo':
			case 'statistics':
				$app_data['page'] = $_GET['view'];
			break;
		}

	switch ($app_data['page']) {
		case 'admin':
		case 'statistics':
			$app_data['controller'] = 'app';
			$app_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : $app_data['page'];
		break;
		case 'calendar':
			$app_data['controller'] = 'meal';
			$app_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : 'calendar';
		break;
		case 'todo':
			$app_data['controller'] = 'todo';
			$app_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : 'list';
	}

/*	if (!isset($db)) {
		$db = new dbh($server, $username, $password);
		$db->use_db($database);
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
	*/
?>