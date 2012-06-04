<?php
	require_once('/tools_helpers/dbconfig.php');
	require_once('/tools_helpers/dbh.php');
	require_once('/tools_helpers/calendar.php');
	require_once('/tools_helpers/helpers.php');

	$application_data = array (
		'error' => false,
		'curdate' => date('Y-m-d')
	);

	if (!isset($_GET['manage']) || $_GET['manage'] != 'show')
		$application_data['manage'] = false;
	else
		$application_data['manage'] = array(
			'view' => 'statistics'
		);

	if ($application_data['manage'] && isset($_GET['view']))
		switch ($_GET['view']) {
			case 'entry':
			case 'new':
			case 'admin':
			case 'todo':
				$application_data['manage']['view'] = $_GET['view'];
			break;
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
		$application_data['error']['id'] = $db->errno;
		$application_data['error']['message'] = $db->error;
	}

	if ($result && $result->num_rows == null)
		$application_data['todays_dish'] = false;
	else if ($result->num_rows == 1)
		$application_data['todays_dish'] = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));

	$result->free();
?>