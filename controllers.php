<?php
	/*changed root*/ require_once('tools_helpers/dbconfig.php');
	/*changed root*/ require_once('tools_helpers/dbh.php');
	/*changed root*/ require_once('tools_helpers/calendar.php');
	/*changed root*/ require_once('tools_helpers/helpers.php');


	/*changed root*/ require_once('controllers/app_controller.php');

	if (!$app_data['error'] && $app_data['controller']) {
		/*changed root*/ require_once('controllers/meal_controller.php');
		/*changed root*/ require_once('controllers/dish_controller.php');
		/*changed root*/ require_once('controllers/tag_controller.php');
		/*changed root*/ require_once('controllers/todo_controller.php');
	}
?>