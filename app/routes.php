<?php
	// could use cleaner structure
	// only use for special cases
	$controller_data_collection = array();

	switch ($app->route['section']) { 
		case 'meal':
			switch ($app->route['page']) {
				case 'calendar_week':
					default:
						$controller_data_collection[] = $app->call_controller('dish', 'get_latest');
				break;
				case 'add':
					switch ($app->route['action']) {
						case 'create':
						break;
						default:
							$controller_data_collection[] = $app->call_controller('meal', 'meals_of_the_day', $app->display_date);
							$controller_data_collection[] = $app->call_controller('dish', 'list_view');
							$controller_data_collection[] = $app->call_controller('dish', 'add');
						break;
					}
				break;
			}
		break;

		case 'app':
			switch ($app->route['page']) {}
		break;
	}
	
	$controller_data_collection[] = $app->call_controller('dish', 'get_dish_of_the_day');

	// SHOULD FIND NICER SOLUTION FOR THIS
	if ($app->route['section'] == 'meal' && $app->route['page'] == 'calendar_week')
		$controller_data_collection[] = $app->call_controller_with_page($app->route['section'], $app->route['page'], $app->display_date);
	else
		$controller_data_collection[] = $app->call_controller_with_page($app->route['section'], $app->route['page']);

	foreach ($controller_data_collection as $controller_data)
		foreach ($controller_data as $name => $data)
			$app->view_data[$name] = $data; 

	//var_dump($app->view_data);
?>