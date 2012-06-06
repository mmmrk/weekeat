<?php
	// FORM
	if ( $application_data['controller'] == 'eatage' && $application_data['action'] == 'form' ) {
		$form_eatage = array (
			'error' => false
		);

		$dish_result = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat`'); 
		$label_result = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat_labels`');

		if ($db->error) {
			$new_eatage['error']['id'] = $db->errno;
			$new_eatage['error']['message'] = 'EATAGE FORM: ' . $db->error;
		}
		else if ($dish_result && $label_result) {
			$form_eatage['ready_dishes'] = array();
			$form_eatage['ready_labels'] = array();

			while ($ready_dish = $dish_result->fetch_array(MYSQLI_ASSOC))
				array_push($form_eatage['ready_dishes'], $db->safe_output_string_array($ready_dish));

			while ($ready_label = $label_result->fetch_array(MYSQLI_ASSOC))
				array_push($form_eatage['ready_labels'], $db->safe_output_string_array($ready_label));

			$dish_result->free();
			$label_result->free();
		}
	}

	// NEW
	if ( $application_data['controller'] == 'eatage' && $application_data['action'] == 'new' ) {
		$new_eatage = array (
			'error' => false
		);

		( !empty($_POST['date'])	) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: date');
		( !empty($_POST['dish_id'])	) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: dish');

		if (!$new_eatage['error']) {
			$safe_input = $db->safe_input_string_array($_POST);

			if ( strtolower($safe_input['dish_id']) == 'random' ||  !(int)$safe_input['dish_id'] ) {
				$query  = 'SELECT `id` ';
				$query .= 'FROM `ready_to_eat` ';
				
				if ( !(int)$safe_input['label_id'] ) {
					$query .= 'JOIN `dish_labels` ON `dish_labels`.`dish_id` = `ready_to_eat`.`id` ';
					$query .= 'WHERE `dish_labels`.`label_id` = ' . $safe_input['label_id'] . ' ';
				}

				$query .= 'ORDER BY rand() LIMIT 1';

				$result = $db->query($query);
				$entry = $result->fetch_array(MYSQLI_ASSOC);
				$dish_id = $entry['id'];
				$result->free();
			}
			else
				$dish_id = $safe_input['dish_id'];

			if ($sql_date = make_sql_date($safe_input['date'])) {
				$query  = 'INSERT INTO `eatage` (`date`, `dish_id`) ';
				$query .= 'VALUES (' . $sql_date . ', ' . $dish_id . ')';
				$db->iquery($query);
			}
			else {
				$new_eatage['error']['id'] = 2;
				$new_eatage['error']['message'] = 'NEW EATAGE: Submitted date is not valid';
			}

			if ($db->error) {
				$new_eatage['error']['id'] = $db->errno;
				$new_eatage['error']['message'] = 'NEW EATAGE: ' . $db->error;
			}
			else {
				$new_eatage['date'] = $safe_input['date'];
				$new_eatage['dish']['id'] = $dish_id;
				$new_eatage['dish']['labels'] = array();

				$query  = 'SELECT `dish`.* ';
				$query .= 'FROM `dish` ';
				$query .= 'WHERE `dish`.`id` = ' . $dish_id;

				$result = $db->query($query);
				$safe_dish = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
				$result->free();

				foreach ($safe_dish as $key => $value)
					$new_eatage['dish'][$key] = $value;

				$query  = 'SELECT `id`, `name`, `icon` ';
				$query .= 'FROM `label` ';
				$query .= 'JOIN `dish_labels` ON `dish_labels`.`label_id` = `label`.`id` ';
				$query .= 'WHERE `dish_labels`.`dish_id` = ' . $new_eatage['dish']['id'];

				$result = $db->query($query);
				while ($label = $result->fetch_array(MYSQLI_ASSOC))
					array_push($new_eatage['dish']['labels'], $db->safe_output_string_array($label));

				$new_eatage['dish']['num_labels'] = count($new_eatage['dish']['labels']);

				unset($query);
				$result->free();
			}
		}
	}

	// STATISTICS
	if ($application_data['action'] == 'statistics') {
		$eatage_statistics = array (
			'error' => false
		);

		$query = 'SELECT COUNT(*) AS `num_eatages` FROM `eatage`';
		$result = $db->query($query);

		if ( $db->error ) {
			$eatage_statistics['error']['id'] = $db->errno;
			$eatage_statistics['error']['message'] = 'EATAGE STATISTICS: ' . $db->error;
		}
		else if ( $result ) {
			$safe_stats = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
			$eatage_statistics['num_eatages'] = $safe_stats['num_eatages'];

			unset($query);
			$result->free();
		}
	}

	// CALENDAR
	if ( $application_data['controller'] == 'eatage' && $application_data['action'] == 'calendar' ) {
		$eatage_calendar = array (
			'error' => false
		);

		$date = (isset($_GET['date']) && is_date($_GET['date'])) ? strtotime($_GET['date']) : time(); 

		$calendar = new Calendar($date);
		$first_day = $calendar->first_day();
		$last_day  = $calendar->last_day();

		$query 	= 'SELECT `eatage`.`date` AS `date`, `dish`.`id` AS `dish_id`, `dish`.`name` AS `dish`, `dish`.`url`, `dish`.`recipe`, `eatage`.`shopping_list`, `label`.`id` AS `label_id`, `label`.`name` AS `label` ';
		$query .= 'FROM `eatage` ';
		$query .= 'JOIN `dish` ON `dish`.`id` = `eatage`.`dish_id` ';
		$query .= 'JOIN `dish_labels` ON `dish_labels`.`dish_id` = `dish`.`id` '; 
		$query .= 'LEFT JOIN `label` ON `label`.`id` = `dish_labels`.`label_id` ';
		$query .= 'WHERE `eatage`.`date` BETWEEN "' . $first_day['date']['string'] . '" AND "' . $last_day['date']['string'] . '" ';
		$query .= 'ORDER BY `date` ASC';

		$result = $db->query($query);

		if ( $db->error ) {
			$eatage_calendar['error']['id'] 	 = $db->errno;
			$eatage_calendar['error']['message'] = 'EATAGE CALENDAR: ' . $db->error;
		}
		else if ( $result ) {
			$eatage_calendar['eatages']  = array();
			$eatage_calendar['calendar'] = $calendar;

			while ($eatage = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_eatage = $db->safe_output_string_array($eatage);
				$safe_eatage['today'] = ($calendar->today_date() == $safe_eatage['date']);
				
				if ( array_key_exists($safe_eatage['date'], $eatage_calendar['eatages']) )
					$eatage_calendar['eatages'][$safe_eatage['date']]['labels'][$safe_eatage['label_id']] = $safe_eatage['label'];
				else {
					$eatage_calendar['eatages'][$safe_eatage['date']] = array (
						'dish_id' 		=> $safe_eatage['dish_id'],
						'dish'			=> $safe_eatage['dish'],
						'url'			=> $safe_eatage['url'],
						'recipe'		=> $safe_eatage['recipe'],
						'shopping_list'	=> $safe_eatage['shopping_list'],
						'labels'		=> array ( $safe_eatage['label_id'] => $safe_eatage['label'] )
					);
				}
			}
		}
	}

	// LIST
	if ( $application_data['controller'] == 'eatage' && $application_data['action'] == 'list' ) {
		$eatage_list = array (
			'error' => false
		);

		$query 	= 'SELECT `eatage`.`date` as `date`, `dish`.`id` as `dish_id`, `dish`.`name` as `dish` ';
		$query .= 'FROM `eatage` ';
		$query .= 'JOIN `dish` ON `dish`.`id` = `eatage`.`dish_id` ';
		$query .= 'ORDER BY `date` DESC';

		$result = $db->query($query);

		if ( $db->error ) {
			$eatage_list['error']['id'] = $db->errno;
			$eatage_list['error']['message'] = 'LIST EATAGES: ' . $db->error;
		}
		else if ( $result ) {
			$eatage_list['eatages'] = array();
			$counter = 0;

			while ($eatage = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_eatage = $db->safe_output_string_array($eatage);
				$safe_eatage['row_class']  = ($counter++ % 2 == 0) ? 'even' : 'odd';
				$safe_eatage['row_class'] .= ($safe_eatage['date'] == $application_data['curdate']) ? ' today' : '';
				
				array_push($eatage_list['eatages'], $safe_eatage);
			}

			unset($counter);
			unset($query);
			$result->free();
		}
	}
?>