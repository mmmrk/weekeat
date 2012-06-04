<?php
	// FORM
	if ($application_data['manage'] && $application_data['manage']['view'] == 'admin') {
		$form_dish = array(
			'error' => false
		);

		$result = $db->query('SELECT * FROM label');
		
		if ($db->error) {
			$form_dish['error']['id'] = $db->errno;
			$form_dish['error']['message'] = 'FORM DISH: ' . $db->error;
		}
		else {
			$form_dish['labels'] = array();
			while ($label = $result->fetch_array(MYSQLI_ASSOC))
				array_push($form_dish['labels'], $db->safe_output_string_array($label));
			
			unset($query);
			$result->free();
		}
	}

	// NEW
	if ( $application_data['manage'] && $application_data['manage']['view'] == 'new' && 
		!empty($_POST['new_dish']) && !empty($_POST['name']) && !empty($_POST['label[]']) && 
		( !empty($_POST['url']) || !empty($_POST['recipe']) ) ) {

		$new_dish = array (
			'error' => false
		);

		$safe_input = $db->safe_input_string_array($_POST);

		$db->autocommit(false);
		$transaction_errors = false;
		
		$dish_query	  = 'INSERT INTO `dish` (`name`, `url`, `recipe`, `created_at`) ';
		$dish_query  .= 'VALUES ("' . $safe_input['name'] . '", "' . $safe_input['url'] . '", "' . $safe_input['recipe'] . '", NOW())';

		$label_query  = 'INSERT INTO `dish_labels` (`dish_id`, `label_id`, `created_at`) ';
		$label_query .= 'VALUES ';

		foreach ($safe_input['label[]'] as $key => $value)
			$label_query .= '(' . $dish_id . ', ' . $value . ', NOW()), ';
	
		//some cleaning up on the query
		$label_query  = (substr($label_query, -1) == ',') ? substr($label_query, 0, -1) : $label_query;

		($dish_id = $db->iquery($dish_query)) ? null : $transaction_errors = true;
		($db->iquery($label_query)) ? null : $transaction_errors = true;
		($db->affected_rows == count($safe_input['label[]'])) ? null : $transaction_errors = true;

		($transaction_errors) ? $db->rollback() : $db->commit();

		if ( $db->error ) {
			$new_dish['error']['id'] = $db->errno;
			$new_dish['error']['message'] = 'NEW DISH: ' . $db->error;
		}
		else if ( $dish_id ) {
			$new_dish['id'] = $dish_id;
			$new_dish['labels'] = array();

			foreach ($safe_input as $key => $value)
				$new_dish[$key] = $value;

			$query  = 'SELECT `id`, `name`, `icon` ';
			$query .= 'FROM `label` ';
			$query .= 'JOIN `dish_labels` ON `dish_labels`.`label_id` = `label`.`id` ';
			$query .= 'WHERE `dish_labels`.`dish_id` = ' . $dish_id;

			$result = $db->query($query);
			while ($label = $result->fetch_array(MYSQLI_ASSOC))
				array_push($new_dish['labels'], $db->safe_output_string_array($label));

			$new_dish['num_labels'] = count($new_dish['labels']);

			unset($query);
			$result->free();
		}
	}

	// STATISTICS
	if (isset($application_data['manage']['view']) && $application_data['manage']['view'] == 'statistics') {
		$dish_statistics = array (
			'error' => false
		);

		$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_labels` as `num_labels`, COUNT(`eatage`.`dish_id`) AS `times_eaten` ';
		$query .= 'FROM `dish` ';
		$query .= 'LEFT JOIN ( ';
		$query .= 'SELECT `d1`.`id` AS `dish_id`, COUNT(`dl`.`label_id`) as `num_labels` ';
		$query .= 'FROM `dish` AS `d1` ';
		$query .= 'LEFT JOIN `dish_labels` AS `dl` ON `dl`.`dish_id` = `d1`.`id` ';
		$query .= 'GROUP BY `d1`.`id` ';
		$query .= ') AS `lc` ON `lc`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `eatage` ON `eatage`.`dish_id` = `dish`.`id` ';
		$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_labels` ';
		$query .= 'ORDER BY `times_eaten` DESC';

		$result = $db->query($query);

		if ( $db->error ) {
			$dish_statistics['error']['id'] = $db->errno;
			$dish_statistics['error']['message'] = 'DISH STATISTICS: ' . $db->error;
		}
		else if ( $result ) {
			$counter = 0;
			$dish_statistics['dishes'] = array();

			while ($stats = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_stats = $db->safe_output_string_array($stats);
				$safe_stats['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';
				
				array_push($dish_statistics['dishes'], $safe_stats);
			}

			unset($counter);
			$result->free();
		}	
	}

	// LIST
	if ( $application_data ) {
		$dish_list = array (
			'error' => false
		);

		$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `label`.`name` as `label`, `dish`.`created_at`, COUNT(`eatage`.`dish_id`) AS `times_eaten` ';
		$query .= 'FROM `dish` ';
		$query .= 'LEFT JOIN `eatage` ON `eatage`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `dish_labels` ON `dish_labels`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `label` ON `label`.`id` = `dish_labels`.`label_id` ';
		$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `label`.`name`, `dish`.`created_at` ';
		$query .= 'ORDER BY `dish`.`id` ASC';
		
		$result = $db->query($query);

		if ($db->error) {
			$new_eatage['error']['id'] = $db->errno;
			$new_eatage['error']['message'] = 'LIST DISHES: ' . $db->error;
		}
		else if ( $result ) {
			$counter = 0;
			$used_ids = array();

			while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_entry = $db->safe_output_string_array($entry);

				if (!in_array($safe_entry['id'], $used_ids)) {
					array_push($used_ids, $safe_entry['id']);

					$safe_entry['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';
					$safe_entry['labels'][] = $safe_entry['label'];

					unset($safe_entry['label']);

					$dish_list['dishes'][$safe_entry['id']] = $safe_entry;

				}
				else
					array_push($dish_list['dishes'][$safe_entry['id']]['labels'], $safe_entry['label']);
			}

			$result->free();
		}
	}
?>