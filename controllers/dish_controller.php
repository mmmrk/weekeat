<?php
	class DishController {

		public static function add () {
			$form_dish = array(
				'error' => false
			);

			$result = $db->query('SELECT * FROM tag');
			
			if ($db->error) {
				$form_dish['error']['id'] = $db->errno;
				$form_dish['error']['message'] = 'FORM DISH: ' . $db->error;
			}
			else {
				$form_dish['tags'] = array();
				while ($tag = $result->fetch_array(MYSQLI_ASSOC))
					array_push($form_dish['tags'], $db->safe_output_string_array($tag));
				
				unset($query);
				$result->free();
			}

			return $form_dish;
		}

		public static function create () {
			$new_dish = array (
				'error' => false
			);

			( !empty($_POST['name'])							) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: name');
			( !empty($_POST['tag[]'])							) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: tag');
			( !empty($_POST['url']) || !empty($_POST['recipe'])	) ? null : $new_todo['error'][] = array('id' => '1.3', 'message' => 'Missing required field: recipe or url');

			if ( !$new_dish['error'] ) {
				$safe_input = $db->safe_input_string_array($_POST);

				$db->autocommit(false);
				$transaction_errors = false;
				
				$dish_query	  = 'INSERT INTO `dish` (`name`, `url`, `recipe`, `created_at`) ';
				$dish_query  .= 'VALUES ("' . $safe_input['name'] . '", "' . $safe_input['url'] . '", "' . $safe_input['recipe'] . '", NOW())';

				$tag_query  = 'INSERT INTO `dish_tags` (`dish_id`, `tag_id`, `created_at`) ';
				$tag_query .= 'VALUES ';

				foreach ($safe_input['tag[]'] as $key => $value)
					$tag_query .= '(' . $dish_id . ', ' . $value . ', NOW()), ';
			
				//some cleaning up on the query
				$tag_query  = (substr($tag_query, -1) == ',') ? substr($tag_query, 0, -1) : $tag_query;

				(		$dish_id = $db->iquery($dish_query)		  ) ? null : $transaction_errors = true;
				(			$db->iquery($tag_query)				  ) ? null : $transaction_errors = true;
				($db->affected_rows == count($safe_input['tag[]'])) ? null : $transaction_errors = true;

				($transaction_errors) ? $db->rollback() : $db->commit();

				if ( $db->error ) {
					$new_dish['error']['id'] = $db->errno;
					$new_dish['error']['message'] = 'NEW DISH: ' . $db->error;
				}
				else if ( $dish_id ) {
					$new_dish['id'] = $dish_id;
					$new_dish['tags'] = array();

					foreach ($safe_input as $key => $value)
						$new_dish[$key] = $value;

					$query  = 'SELECT `id`, `name`, `icon` ';
					$query .= 'FROM `tag` ';
					$query .= 'JOIN `dish_tags` ON `dish_tags`.`tag_id` = `tag`.`id` ';
					$query .= 'WHERE `dish_tags`.`dish_id` = ' . $dish_id;

					$result = $db->query($query);
					while ($tag = $result->fetch_array(MYSQLI_ASSOC))
						array_push($new_dish['tags'], $db->safe_output_string_array($tag));

					$new_dish['num_tags'] = count($new_dish['tags']);

					unset($query);
					$result->free();
				}
			}

			return $new_dish;
		}

		public static function get_statistics () {
			$dish_statistics = array (
				'error' => false
			);

			$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_tags` as `num_tags`, COUNT(`meal`.`dish_id`) AS `times_eaten` ';
			$query .= 'FROM `dish` ';
			$query .= 'LEFT JOIN ( ';
			$query .= 'SELECT `d1`.`id` AS `dish_id`, COUNT(`dl`.`tag_id`) as `num_tags` ';
			$query .= 'FROM `dish` AS `d1` ';
			$query .= 'LEFT JOIN `dish_tags` AS `dl` ON `dl`.`dish_id` = `d1`.`id` ';
			$query .= 'GROUP BY `d1`.`id` ';
			$query .= ') AS `lc` ON `lc`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `meal` ON `meal`.`dish_id` = `dish`.`id` ';
			$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_tags` ';
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

			return $dish_statistics;
		}

		public static function get_list () {
			$dish_list = array (
				'error' => false
			);

			$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `tag`.`name` as `tag`, `dish`.`created_at`, COUNT(`meal`.`dish_id`) AS `times_eaten` ';
			$query .= 'FROM `dish` ';
			$query .= 'LEFT JOIN `meal` ON `meal`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
			$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `tag`.`name`, `dish`.`created_at` ';
			$query .= 'ORDER BY `dish`.`id` ASC';
			
			$result = $db->query($query);

			if ($db->error) {
				$dish_list['error']['id'] = $db->errno;
				$dish_list['error']['message'] = 'LIST DISHES: ' . $db->error;
			}
			else if ( $result ) {
				$counter = 0;
				$used_ids = array();

				while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_entry = $db->safe_output_string_array($entry);

					if (!in_array($safe_entry['id'], $used_ids)) {
						array_push($used_ids, $safe_entry['id']);

						$safe_entry['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';
						$safe_entry['tags'][] = $safe_entry['tag'];

						unset($safe_entry['tag']);

						$dish_list['dishes'][$safe_entry['id']] = $safe_entry;

					}
					else
						array_push($dish_list['dishes'][$safe_entry['id']]['tags'], $safe_entry['tag']);
				}

				$result->free();
			}

			return $dish_list;
		}

		public static function get_latest () {
			$dish_list_latest = array (
				'error' => false
			);

			$query  = 'SELECT `id`, `name`, `description`, `url`, `created_at` ';
			$query .= 'FROM `dish` ';
			$query .= 'ORDER BY `created_at` DESC ';
			$query .= 'LIMIT 10';

			$result = $db->query($query);

			if ($db->error) {
				$dish_list_latest['error']['id'] = $db->errno;
				$dish_list_latest['error']['message'] = 'LATEST DISHES: ' . $db->error;
			}
			else if ( $result ) {
				while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_entry = $db->safe_output_string_array($entry);
					
					$dish_list_latest['dishes'][$safe_entry['id']] = array(
						'id' 			=> $safe_entry['id'],
						'name' 			=> $safe_entry['name'],
						'description' 	=> $safe_entry['description'],
						'url' 			=> $safe_entry['url'],
						'created_at' 	=> datetime_to_date($safe_entry['created_at'])
					);
				}

				$result->free();
			}

			return $dish_list_latest;
		}

		// NEEDS REBUILDING - CODE + DB
		public static function dish_of_the_day () {
			$dish_of_the_day = array(
				'error' => false
			);

			$query  = 'SELECT * FROM `dish` ';
			$query .= 'JOIN `meal` ON `dish`.`id` = `meal`.`dish_id` ';
			$query .= 'WHERE `meal`.`date` = ' . make_sql_date('today');

			$result = $db->query($query);

			if ($db->error) {
				$dish_of_the_day['error']['id']	  = $db->errno;
				$dish_of_the_day['error']['message'] = $db->error;
			}
			else if ($result && $result->num_rows == null) {
				$dish_of_the_day['error']['id'] 	 = 10;
				$dish_of_the_day['error']['message'] = 'THERE IS NO DISH OF THE DAY';
			}
			else {
				$dish_of_the_day = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));

				$result->free();
			}

			return $dish_of_the_day;
		}
	}

	// FORM
	if ( $app_data['controller'] == 'dish' || $app_data['page'] == 'calendar' ) {
		$form_dish = array(
			'error' => false
		);

		$result = $db->query('SELECT * FROM tag');
		
		if ($db->error) {
			$form_dish['error']['id'] = $db->errno;
			$form_dish['error']['message'] = 'FORM DISH: ' . $db->error;
		}
		else {
			$form_dish['tags'] = array();
			while ($tag = $result->fetch_array(MYSQLI_ASSOC))
				array_push($form_dish['tags'], $db->safe_output_string_array($tag));
			
			unset($query);
			$result->free();
		}
	}

	// NEW
	if ( $app_data['controller'] == 'dish' || $app_data['action'] == 'new' ) {
		$new_dish = array (
			'error' => false
		);

		( !empty($_POST['name'])							) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: name');
		( !empty($_POST['tag[]'])							) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: tag');
		( !empty($_POST['url']) || !empty($_POST['recipe'])	) ? null : $new_todo['error'][] = array('id' => '1.3', 'message' => 'Missing required field: recipe or url');

		if ( !$new_dish['error'] ) {
			$safe_input = $db->safe_input_string_array($_POST);

			$db->autocommit(false);
			$transaction_errors = false;
			
			$dish_query	  = 'INSERT INTO `dish` (`name`, `url`, `recipe`, `created_at`) ';
			$dish_query  .= 'VALUES ("' . $safe_input['name'] . '", "' . $safe_input['url'] . '", "' . $safe_input['recipe'] . '", NOW())';

			$tag_query  = 'INSERT INTO `dish_tags` (`dish_id`, `tag_id`, `created_at`) ';
			$tag_query .= 'VALUES ';

			foreach ($safe_input['tag[]'] as $key => $value)
				$tag_query .= '(' . $dish_id . ', ' . $value . ', NOW()), ';
		
			//some cleaning up on the query
			$tag_query  = (substr($tag_query, -1) == ',') ? substr($tag_query, 0, -1) : $tag_query;

			(		$dish_id = $db->iquery($dish_query)		  ) ? null : $transaction_errors = true;
			(			$db->iquery($tag_query)				  ) ? null : $transaction_errors = true;
			($db->affected_rows == count($safe_input['tag[]'])) ? null : $transaction_errors = true;

			($transaction_errors) ? $db->rollback() : $db->commit();

			if ( $db->error ) {
				$new_dish['error']['id'] = $db->errno;
				$new_dish['error']['message'] = 'NEW DISH: ' . $db->error;
			}
			else if ( $dish_id ) {
				$new_dish['id'] = $dish_id;
				$new_dish['tags'] = array();

				foreach ($safe_input as $key => $value)
					$new_dish[$key] = $value;

				$query  = 'SELECT `id`, `name`, `icon` ';
				$query .= 'FROM `tag` ';
				$query .= 'JOIN `dish_tags` ON `dish_tags`.`tag_id` = `tag`.`id` ';
				$query .= 'WHERE `dish_tags`.`dish_id` = ' . $dish_id;

				$result = $db->query($query);
				while ($tag = $result->fetch_array(MYSQLI_ASSOC))
					array_push($new_dish['tags'], $db->safe_output_string_array($tag));

				$new_dish['num_tags'] = count($new_dish['tags']);

				unset($query);
				$result->free();
			}
		}
	}

	// STATISTICS
	if ( $app_data['controller'] == 'application' || $app_data['page'] == 'statistics' ) {
		$dish_statistics = array (
			'error' => false
		);

		$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_tags` as `num_tags`, COUNT(`meal`.`dish_id`) AS `times_eaten` ';
		$query .= 'FROM `dish` ';
		$query .= 'LEFT JOIN ( ';
		$query .= 'SELECT `d1`.`id` AS `dish_id`, COUNT(`dl`.`tag_id`) as `num_tags` ';
		$query .= 'FROM `dish` AS `d1` ';
		$query .= 'LEFT JOIN `dish_tags` AS `dl` ON `dl`.`dish_id` = `d1`.`id` ';
		$query .= 'GROUP BY `d1`.`id` ';
		$query .= ') AS `lc` ON `lc`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `meal` ON `meal`.`dish_id` = `dish`.`id` ';
		$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `dish`.`created_at`, `lc`.`num_tags` ';
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
	if ( $app_data['controller'] == 'dish' && $app_data['action'] == 'list' ) {
		$dish_list = array (
			'error' => false
		);

		$query 	= 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`url`, `tag`.`name` as `tag`, `dish`.`created_at`, COUNT(`meal`.`dish_id`) AS `times_eaten` ';
		$query .= 'FROM `dish` ';
		$query .= 'LEFT JOIN `meal` ON `meal`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
		$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`url`, `tag`.`name`, `dish`.`created_at` ';
		$query .= 'ORDER BY `dish`.`id` ASC';
		
		$result = $db->query($query);

		if ($db->error) {
			$dish_list['error']['id'] = $db->errno;
			$dish_list['error']['message'] = 'LIST DISHES: ' . $db->error;
		}
		else if ( $result ) {
			$counter = 0;
			$used_ids = array();

			while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_entry = $db->safe_output_string_array($entry);

				if (!in_array($safe_entry['id'], $used_ids)) {
					array_push($used_ids, $safe_entry['id']);

					$safe_entry['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';
					$safe_entry['tags'][] = $safe_entry['tag'];

					unset($safe_entry['tag']);

					$dish_list['dishes'][$safe_entry['id']] = $safe_entry;

				}
				else
					array_push($dish_list['dishes'][$safe_entry['id']]['tags'], $safe_entry['tag']);
			}

			$result->free();
		}
	}

	//LIST LATEST DISHES
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'calendar' ) {
		
		$dish_list_latest = array (
			'error' => false
		);

		$query  = 'SELECT `id`, `name`, `description`, `url`, `created_at` ';
		$query .= 'FROM `dish` ';
		$query .= 'ORDER BY `created_at` DESC ';
		$query .= 'LIMIT 10';

		$result = $db->query($query);

		if ($db->error) {
			$dish_list_latest['error']['id'] = $db->errno;
			$dish_list_latest['error']['message'] = 'LATEST DISHES: ' . $db->error;
		}
		else if ( $result ) {
			while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_entry = $db->safe_output_string_array($entry);
				
				$dish_list_latest['dishes'][$safe_entry['id']] = array(
					'id' 			=> $safe_entry['id'],
					'name' 			=> $safe_entry['name'],
					'description' 	=> $safe_entry['description'],
					'url' 			=> $safe_entry['url'],
					'created_at' 	=> datetime_to_date($safe_entry['created_at'])
				);
			}

			$result->free();
		}
	}
?>