<?php
	class AppController {
		
		public static function statistics () {

			$holder = array(
				DishController::statistics(),
				MealController::statistics(),
				TagController::statistics()
				//TodoController::statistics()
			);

			$statistics = array();

			foreach ($holder as $stat)
				foreach ($stat as $stat_name => $stat_item)
					$statistics[$stat_name] = $stat_item;

			return array('statistics' => $statistics);
		}

		public static function admin () {

		}
	}

/********** OLD
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
OLD **********/
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