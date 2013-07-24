<?php
	class Boot extends Application {
		public $db, $request_method, $params;

		public function __construct () {
			$this->request_method = get_request_method();
			$this->db_init(DbConfig::$server, DbConfig::$database, DbConfig::$username, DbConfig::$password);
		}

		public function set_params () {
			$this->params = array(
				'GET' => $_GET,
				'POST' => $_POST
			);
		}

		public function db_init ($server, $database, $username, $password) {
			if (!isset($this->db)) {
				$this->db = new dbh($server, $username, $password);
				$this->db->use_db($database);
			}
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