<?php
	$application_data = array (
		'error' => false,
		'curdate' => date('Y-m-d'),
		'page' => 'calendar'
	);

	if (isset($_GET['view']))
		switch ($_GET['view']) {
			case 'admin':
			case 'todo':
			case 'statistics':
				$application_data['page'] = $_GET['view'];
			break;
		}

	switch ($application_data['page']) {
		case 'admin':
		case 'statistics':
			$application_data['controller'] = 'application';
			$application_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : $application_data['page'];
		break;
		case 'calendar':
			$application_data['controller'] = 'eatage';
			$application_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : 'calendar';
		break;
		case 'todo':
			$application_data['controller'] = 'todo';
			$application_data['action'] = (isset($_GET['action'])) ? $_GET['action'] : 'list';
	}

	if (!isset($db)) {
		$db = new dbh($server, $username, $password);
		$db->use_db($database);
	}

	$query  = 'SELECT * FROM dish ';
	$query .= 'JOIN eatage ON dish.id = eatage.dish_id ';
	$query .= 'WHERE eatage.date = CURDATE()';

	$result = $db->query($query);

	if ($db->error) {
		$application_data['error']['id']	  = $db->errno;
		$application_data['error']['message'] = $db->error;
	}

	if ($result && $result->num_rows == null)
		$application_data['todays_dish'] = false;
	else if ($result->num_rows == 1)
		$application_data['todays_dish'] = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));

	$result->free();
?>