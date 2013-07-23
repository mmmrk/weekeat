<?php
	require_once('tools_helpers/dbconfig.php');
	require_once('tools_helpers/dbh.php');
	require_once('tools_helpers/calendar.php');
	require_once('tools_helpers/helpers.php');


	require_once('controllers/app_controller.php');

	if (!$app_data['error'] && $app_data['controller']) {
		require_once('controllers/meal_controller.php');
		require_once('controllers/dish_controller.php');
		require_once('controllers/tag_controller.php');
		require_once('controllers/todo_controller.php');
	}
?>