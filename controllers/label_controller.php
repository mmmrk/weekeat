<?php
	// FORM
	if ($application_data['manage'] && $application_data['manage']['view'] == 'admin') {
		$form_label = array(
			'error' => false
		);

		if ($db->error) {
			$form_label['error']['id'] = $db->errno;
			$form_label['error']['message'] = 'FORM LABEL: ' . $db->error;
		}
		else {}
	}

	// NEW
	if ( $application_data['manage'] && $application_data['manage']['view'] == 'new' && !empty($_POST['new_label']) && !empty($_POST['name']) ) {
		$new_label = array (
			'error' => false
		);

		$safe_input = $db->safe_input_string_array($_POST);

		echo $query = 'INSERT INTO `label` (`name`, `created_at`) VALUES ("' . $safe_input['name'] . '", NOW())'; 

		$label_id = $db->iquery($query);

		if ( $db->error ) {
			$new_label['error']['id'] = $db->errno;
			$new_label['error']['message'] = 'NEW LABEL: ' . $db->error;
		}
		else if ( $label_id ) {
			$new_label['id'] = $label_id;
			$new_label['name'] = $safe_input['name'];
		}
	}

	// STATISTICS
	if (isset($application_data['manage']['view']) && $application_data['manage']['view'] == 'statistics') {
		$label_statistics = array (
			'error' => false
		);

		$query 	= 'SELECT `lu`.`id` as `id`, `lu`.`label` as `name`, `lu`.`times_used`, `le`.`times_eaten` ';
		$query .= 'FROM `label_usage` AS `lu` ';
		$query .= 'JOIN `label_eatage` AS `le` ON `lu`.`id` = `le`.`id` ';
		$query .= 'ORDER BY `lu`.`id` ASC';

		$result = $db->query($query);

		if ( $db->error ) {
			$label_statistics['error']['id'] = $db->errno;
			$label_statistics['error']['message'] = 'LABEL STATISTICS: ' . $db->error;
		}
		else if ( $result ) {
			$label_statistics['labels'] = array();
			$counter = 0;
			while ($stats = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_stats = $db->safe_output_string_array($stats);
				$safe_stats['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';

				array_push($label_statistics['labels'], $safe_stats);
			}

			unset($counter);
			unset($query);
			$result->free();
		}
	}
?>