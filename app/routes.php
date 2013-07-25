<?php
	//could use cleaner structure
	// only use for special cases
	switch ($app->route['section']) { 
		case 'meals':
			switch ($app->route['page']) {}
		break;

		case 'app':
			switch ($app->route['page']) {}
		break;
	}

	$app->view_data[$app->route['page']] = $app->call_controller($app->route['section'], $app->route['page'], Boot::$db);
?>