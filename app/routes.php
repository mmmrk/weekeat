<?php
	// could use cleaner structure
	// only use for special cases
	$controller_data_collection = array();

	switch ($app->route['section']) { 
		case 'meal':
			switch ($app->route['page']) {
				case 'add':
					switch ($app->route['action']) {
						//case 'create':
							//$controller_data_collection[] = $app->call_controller('meal', 'create', false, false);
						//break;
						case 'create':
						break;
						default:
							$controller_data_collection[] = $app->call_controller('meal', 'meals_of_the_day', $app->display_date);
							$controller_data_collection[] = $app->call_controller('dish', 'list_view', 'ready_to_eat');
							$controller_data_collection[] = $app->call_controller('dish', 'add');
						break;
					}
				break;
				case 'calendar_week':
				default:
						$controller_data_collection[] = $app->call_controller('dish', 'get_latest');
				break;
			}
		break;

		case 'dish':
			switch ($app->route['page']) {
				case 'list_view':
					$controller_data_collection[] = $app->call_controller('dish', 'add');
				break;
			}
		break;

		case 'app':
			switch ($app->route['page']) {
				case 'admin':
					$controller_data_collection[] = $app->call_controller('todo', 'add');
					$controller_data_collection[] = $app->call_controller('dish', 'add');
					$controller_data_collection[] = $app->call_controller('tag', 'add');
				break;
			}
		break;
	}
	
	$controller_data_collection[] = $app->call_controller('dish', 'get_dish_of_the_day');

	//var_dump($app->route);
	//var_dump($controller_data_collection);
	//var_dump($app->view_data);
	//var_dump($app->site_params);
	//var_dump($app->input_params);

	// SHOULD FIND NICER SOLUTION FOR THIS
	if ($app->route['section'] == 'meal' && $app->route['page'] == 'calendar_week')
		$controller_data_collection[] = $app->call_controller_with_page($app->route['section'], $app->route['page'], $app->display_date);
	else if ($app->route['section'] == 'meal' && $app->route['page'] == 'add' && $app->route['action'] == 'create')
		$controller_data_collection[] = $app->call_controller('meal', 'create', array($app->input_params, true));
	else
		$controller_data_collection[] = $app->call_controller_with_page($app->route['section'], $app->route['page']);

	foreach ($controller_data_collection as $controller_data)
		foreach ($controller_data as $name => $data)
			$app->view_data[$name] = $data; 
?>