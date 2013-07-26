<?php
	// could use cleaner structure
	// only use for special cases
	$controller_data_collection = array();

	switch ($app->route['section']) { 
		case 'meal':
			switch ($app->route['page']) {
				case 'add':
					switch ($app->route['action']) {
						default:
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
	
	$controller_data_collection[] = $app->call_controller_with_page($app->route['section'], $app->route['page']);

//	var_dump($controller_data_collection);

	foreach ($controller_data_collection as $controller_data)
		foreach ($controller_data as $name => $data)
			$app->view_data[$name] = $data; 
?>