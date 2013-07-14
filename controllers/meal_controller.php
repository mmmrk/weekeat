<?php
	// FORM
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'form' ) {
		$form_meal = array (
			'error' => false
		);

		$dish_result  = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat`'); 
		$tag_result = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat_tags`');

		if ($db->error) {
			$new_meal['error']['id'] 		= $db->errno;
			$new_meal['error']['message'] = 'EATAGE FORM: ' . $db->error;
		}
		else if ($dish_result && $tag_result) {
			$form_meal['ready_dishes'] = array();
			$form_meal['ready_tags'] = array();

			while ($ready_dish = $dish_result->fetch_array(MYSQLI_ASSOC))
				array_push($form_meal['ready_dishes'], $db->safe_output_string_array($ready_dish));

			while ($ready_tag = $tag_result->fetch_array(MYSQLI_ASSOC))
				array_push($form_meal['ready_tags'], $db->safe_output_string_array($ready_tag));

			$dish_result->free();
			$tag_result->free();
		}
	}

	// NEW
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'new' ) {
		$new_meal = array (
			'error' => false
		);

		( !empty($_POST['date'])	) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: date');
		( !empty($_POST['dish_id'])	) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: dish');

		if (!$new_meal['error']) {
			$safe_input = $db->safe_input_string_array($_POST);

			if ( strtolower($safe_input['dish_id']) == 'random' ||  !(int)$safe_input['dish_id'] ) {
				$query  = 'SELECT `id` ';
				$query .= 'FROM `ready_to_eat` ';
				if ( !(int)$safe_input['tag_id'] ) {
					$query .= 'JOIN `dish_tags` ON `dish_tags`.`dish_id` = `ready_to_eat`.`id` ';
					$query .= 'WHERE `dish_tags`.`tag_id` = ' . $safe_input['tag_id'] . ' ';
				}
				$query .= 'ORDER BY rand() LIMIT 1';

				$result  = $db->query($query);
				$entry   = $result->fetch_array(MYSQLI_ASSOC);
				$dish_id = $entry['id'];
				$result->free();
			}
			else
				$dish_id = $safe_input['dish_id'];

			if ($sql_date = make_sql_date($safe_input['date'])) {
				$query  = 'INSERT INTO `meal` (`date`, `dish_id`) ';
				$query .= 'VALUES (' . $sql_date . ', ' . $dish_id . ')';
				$db->iquery($query);
			}
			else {
				$new_meal['error']['id'] 		= 2;
				$new_meal['error']['message'] = 'NEW EATAGE: Submitted date is not valid';
			}

			if ($db->error) {
				$new_meal['error']['id'] 		= $db->errno;
				$new_meal['error']['message'] = 'NEW EATAGE: ' . $db->error;
			}
			else {
				$new_meal['date'] 		  = $safe_input['date'];
				$new_meal['dish']['id'] 	  = $dish_id;
				$new_meal['dish']['tags'] = array();

				$query  = 'SELECT `dish`.* ';
				$query .= 'FROM `dish` ';
				$query .= 'WHERE `dish`.`id` = ' . $dish_id;

				$result    = $db->query($query);
				$safe_dish = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
				$result->free();

				foreach ($safe_dish as $key => $value)
					$new_meal['dish'][$key] = $value;

				$query  = 'SELECT `id`, `name`, `icon` ';
				$query .= 'FROM `tag` ';
				$query .= 'JOIN `dish_tags` ON `dish_tags`.`tag_id` = `tag`.`id` ';
				$query .= 'WHERE `dish_tags`.`dish_id` = ' . $new_meal['dish']['id'];

				$result = $db->query($query);
				while ($tag = $result->fetch_array(MYSQLI_ASSOC))
					array_push($new_meal['dish']['tags'], $db->safe_output_string_array($tag));

				$new_meal['dish']['num_tags'] = count($new_meal['dish']['tags']);

				unset($query);
				$result->free();
			}
		}
	}

	// STATISTICS
	if ( $app_data['action'] == 'statistics' ) {
		$meal_statistics = array (
			'error' => false
		);

		$query  = 'SELECT COUNT(*) AS `num_meals` FROM `meal`';
		$result = $db->query($query);

		if ( $db->error ) {
			$meal_statistics['error']['id'] 	   = $db->errno;
			$meal_statistics['error']['message'] = 'EATAGE STATISTICS: ' . $db->error;
		}
		else if ( $result ) {
			$safe_stats = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
			$meal_statistics['num_meals'] = $safe_stats['num_meals'];

			unset($query);
			$result->free();
		}
	}

	// CALENDAR
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'calendar' ) {
		$meal_calendar = array (
			'error' => false
		);

		$date = (isset($_GET['date']) && is_date($_GET['date'])) ? strtotime($_GET['date']) : time(); 

		$calendar  = new Calendar($date);
		$first_day = $calendar->first_day();
		$last_day  = $calendar->last_day();

		$query 	= 'SELECT `meal`.`date` AS `date`, `dish`.`id` AS `dish_id`, `dish`.`name` AS `dish`, `dish`.`url`, `dish`.`recipe`, `meal`.`shopping_list`, `tag`.`id` AS `tag_id`, `tag`.`name` AS `tag` ';
		$query .= 'FROM `meal` ';
		$query .= 'JOIN `dish` ON `dish`.`id` = `meal`.`dish_id` ';
		$query .= 'JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` '; 
		$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
		$query .= 'WHERE `meal`.`date` BETWEEN "' . $first_day['date']['string'] . '" AND "' . $last_day['date']['string'] . '" ';
		$query .= 'ORDER BY `date` ASC';

		$result = $db->query($query);

		if ( $db->error ) {
			$meal_calendar['error']['id'] 	 = $db->errno;
			$meal_calendar['error']['message'] = 'EATAGE CALENDAR: ' . $db->error;
		}
		else if ( $result ) {
			$meal_calendar['meals']  = array();
			$meal_calendar['calendar'] = $calendar;

			while ($meal = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_meal = $db->safe_output_string_array($meal);
				$safe_meal['today'] = ($calendar->today_date() == $safe_meal['date']);
				
				if ( array_key_exists($safe_meal['date'], $meal_calendar['meals']) )
					$meal_calendar['meals'][$safe_meal['date']]['tags'][$safe_meal['tag_id']] = $safe_meal['tag'];
				else {
					$meal_calendar['meals'][$safe_meal['date']] = array (
						'dish_id' 		=> $safe_meal['dish_id'],
						'dish'			=> $safe_meal['dish'],
						'url'			=> $safe_meal['url'],
						'recipe'		=> $safe_meal['recipe'],
						'shopping_list'	=> $safe_meal['shopping_list'],
						'tags'		=> array ( $safe_meal['tag_id'] => $safe_meal['tag'] )
					);
				}
			}
		}
	}

	// LIST
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'list' ) {
		$meal_list = array (
			'error' => false
		);

		$query 	= 'SELECT `meal`.`date` as `date`, `dish`.`id` as `dish_id`, `dish`.`name` as `dish` ';
		$query .= 'FROM `meal` ';
		$query .= 'JOIN `dish` ON `dish`.`id` = `meal`.`dish_id` ';
		$query .= 'ORDER BY `date` DESC';

		$result = $db->query($query);

		if ( $db->error ) {
			$meal_list['error']['id'] 	 = $db->errno;
			$meal_list['error']['message'] = 'LIST EATAGES: ' . $db->error;
		}
		else if ( $result ) {
			$meal_list['meals'] = array();
			$counter = 0;

			while ($meal = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_meal = $db->safe_output_string_array($meal);
				$safe_meal['row_class']  = ($counter++ % 2 == 0) ? 'even' : 'odd';
				$safe_meal['row_class'] .= ($safe_meal['date'] == $app_data['curdate']) ? ' today' : '';
				
				array_push($meal_list['meals'], $safe_meal);
			}

			unset($counter);
			unset($query);
			$result->free();
		}
	}
?>