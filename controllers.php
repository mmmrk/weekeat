<?php
	require_once('/controllers/application_controller.php');

	if (!$application_data['error']) {
		require_once('/controllers/eatage_controller.php');
		require_once('/controllers/dish_controller.php');
		require_once('/controllers/label_controller.php');
		require_once('/controllers/todo_controller.php');
	}
?>