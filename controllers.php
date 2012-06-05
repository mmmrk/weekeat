<?php
	require_once('/tools_helpers/dbconfig.php');
	require_once('/tools_helpers/dbh.php');
	require_once('/tools_helpers/calendar.php');
	require_once('/tools_helpers/helpers.php');


	require_once('/controllers/application_controller.php');

	if (!$application_data['error'] && $application_data['controller']) {
		require_once('/controllers/eatage_controller.php');
		require_once('/controllers/dish_controller.php');
		require_once('/controllers/label_controller.php');
		require_once('/controllers/todo_controller.php');
	}
?>