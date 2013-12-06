<?php
	class DishController {

		public static function add () {
			$db = Boot::$db;

			$dish_form = array(
				'error' => false
			);

			$result = $db->query('SELECT * FROM tag');

			if ($db->error) {
				$dish_form['error']['id'] = $db->errno;
				$dish_form['error']['message'] = 'FORM DISH: ' . $db->error;
			}
			else {
				$dish_form['tags'] = array();
				while ($tag = $result->fetch_array(MYSQLI_ASSOC))
					array_push($dish_form['tags'], $db->safe_output_string_array($tag));

				unset($query);
				$result->free();
			}

			return array('dish_form' => $dish_form);
		}

		public static function make_create_query ( $dish ) {
			$dish_query = false;

			if ( !empty($dish['name']) && !empty($dish['description']) && (!empty($dish['url']) || !empty($dish['recipe'])) )
				$dish_query  = 'INSERT INTO `dish` (`name`, `description`, `url`, `recipe`, `created_at`) ';
				$dish_query .= 'VALUES ("' . $dish['name'] . '", "' . $dish['description'] . '", "' . $dish['url'] . '", "' . $dish['recipe'] . '", NOW())';

			return $dish_query;
		}

		public static function create ( $dish, $transaction_db=false, $redirect=false ) {
			$db = Boot::$db;

			$new_dish = array (
				'error' => false
			);

			( !empty($dish['name'])								) ? null : $new_dish['error'][] = array('id' => '1.1', 'message' => 'Missing required field: name');
			( !empty($dish['tags'])	&& is_array($dish['tags'])	) ? null : $new_dish['error'][] = array('id' => '1.2', 'message' => 'Missing required field: tags');
			( !empty($dish['url']) || !empty($dish['recipe'])	) ? null : $new_dish['error'][] = array('id' => '1.3', 'message' => 'Missing required field: recipe or url');

			if ( !$new_dish['error'] ) {
				if (!$transaction_db)
					$db->autocommit(false);

				$transaction_errors = false;

				$dish_query	  = 'INSERT INTO `dish` (`name`, `description`, `url`, `recipe`, `created_at`) ';
				$dish_query  .= 'VALUES ("' . $dish['name'] . '", "' . $dish['description'] . '", "' . $dish['url'] . '", "' . $dish['recipe'] . '", NOW())';

				( $dish_id = $db->iquery($dish_query)						 ) ? null : $transaction_errors = 'DISH INSERT ERROR';

				$tag_links = TagController::link_dish_tags($dish_id, $dish['tags'], $db);

				if ( $tag_links['dish_tags']['error'] ) {
					$new_dish['tags']['error']['id'] = $tag_links['dish_tags']['error']['id'];
					$new_dish['tags']['error']['message'] = $tag_links['dish_tags']['error']['message'];
				}

				if ( $transaction_errors || $db->error || $new_dish['tags']['error'] ) {
					$db->rollback();

					$new_dish['error']['id'] = $db->errno;
					$new_dish['error']['message'] = 'NEW DISH: ' . $db->error . ' (' . $transaction_errors . ')';
				}
				else if ( !$transaction_errors && $dish_id ) {
					(!$transaction_db) ? null : $db->commit();
					unset($dish_query);

					if ($redirect) header('Location: ' . $app->current_page);

					$new_dish['id'] = $dish_id;

					foreach ($dish as $key => $value)
						$new_dish[$key] = $value;

					$new_dish['tags']		= TagController::get_dish_tags($dish_id);
					$new_dish['num_tags']	= count($new_dish['tags']);
				}
			}
			var_dump($new_dish);

			return array('new_dish' => $new_dish);
		}

		public static function statistics () {
			$db = Boot::$db;

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

			return array('dish_statistics' => $dish_statistics);
		}

		public static function list_view ($dish_selection = 'dish') {
			$db = Boot::$db;

			$dish_list = array (
				'error' => false
			);

			$dish_selection = (empty($dish_selection)) ? 'dish' : $dish_selection;

			$query  = 'SELECT `dish`.`id`, `dish`.`name`, `dish`.`description`, `dish`.`url`, `tag`.`name` as `tag`, CAST(`dish`.`created_at` AS DATE) AS `created_at`, ';
			$query .= '`ml`.`last_meal_date` as `last_meal_date`, `dotd`.`last_dotd_date` as `last_dotd_date`, COUNT(`meal`.`dish_id`) AS `times_eaten`';
			$query .= 'FROM `' . $dish_selection . '` AS `dish`';
			$query .= 'LEFT JOIN ( ';
				$query .= 'SELECT `meal`.`dish_id`, MAX(`meal`.`date`) AS `last_meal_date` ';
				$query .= 'FROM `meal` ';
				$query .= 'GROUP BY `meal`.`dish_id` ';
			$query .= ') AS `ml` ON `ml`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN ( ';
				$query .= 'SELECT `dod`.`dish_id`, MAX(`dod`.`date`) AS `last_dotd_date`  ';
				$query .= 'FROM `dish_of_the_day` as `dod` ';
				$query .= 'GROUP BY `dod`.`dish_id` ';
			$query .= ') AS `dotd` ON `dotd`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `meal` ON `meal`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
			$query .= 'GROUP BY `dish`.`id`, `dish`.`name`, `dish`.`description`, `dish`.`url`, `tag`.`name`, `dish`.`created_at`, `ml`.`last_meal_date`, `dotd`.`last_dotd_date` ';
			$query .= 'ORDER BY `name` ASC';

			$result = $db->query($query);

			if ( $db->error ) {
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

						// $safe_entry['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';
						$safe_entry['tags'][] = $safe_entry['tag'];

						unset($safe_entry['tag']);

						$dish_list['dishes'][$safe_entry['id']] = $safe_entry;

					}
					else
						array_push($dish_list['dishes'][$safe_entry['id']]['tags'], $safe_entry['tag']);
				}

				$result->free();
			}

			return array('dish_list' => $dish_list);
		}

		public static function get_latest () {
			$db = Boot::$db;

			$latest_dishes_list = array (
				'error' => false
			);

			$query  = 'SELECT `id`, `name`, `description`, `url`, CAST(`created_at` AS DATE) AS `created_at` ';
			$query .= 'FROM `dish` ';
			$query .= 'ORDER BY `created_at` DESC ';
			$query .= 'LIMIT 5';

			$result = $db->query($query);

			if ( $db->error ) {
				$latest_dishes_list['error']['id'] = $db->errno;
				$latest_dishes_list['error']['message'] = 'LATEST DISHES: ' . $db->error;
			}
			else if ( $result ) {
				while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_entry = $db->safe_output_string_array($entry);

					$latest_dishes_list['dishes'][$safe_entry['id']] = array (
						'id' 			=> $safe_entry['id'],
						'name' 			=> $safe_entry['name'],
						'description' 	=> $safe_entry['description'],
						'url' 			=> $safe_entry['url'],
						'created_at' 	=> $safe_entry['created_at']
					);
				}

				$result->free();
			}

			return array('latest_dishes_list' => $latest_dishes_list);
		}

		public static function set_dish_of_the_day ( $dish_id=false, $dotd_date=false ) {
			$db = Boot::$db;

			$set_dotd = array (
				'error' => false
			);

			// TO IMPLEMENT LATER
			if (!$dish_id);

			$safe_date = ($dotd_date && is_date($dotd_date)) ? $dotd_date : date('Y-m-d');
			$sql_date = make_sql_date($safe_date);

			$query  = 'INSERT INTO `dish_of_the_day` ';
			$query .= 'SELECT ' . $sql_date . ' AS `date`, `ready_for_dotd`.`id` ';
			$query .= 'FROM `ready_for_dotd` ';
			$query .= 'ORDER BY RAND() LIMIT 1';

			$entry = $db->query($query);

			if ($db->error) {
				$set_dotd['error']['id']	  = $db->errno;
				$set_dotd['error']['message'] = $db->error;
			}
			else if ( $entry ) {}

			return self::get_dish_of_the_day($safe_date);

		}

		public static function get_dish_of_the_day ( $dotd_date=false ) {
			$db = Boot::$db;

			$dotd = array (
				'error' => false
			);

			$query  = 'SELECT `dish`.* FROM `dish` ';
			$query .= 'INNER JOIN `dish_of_the_day` AS `dotd` ON `dish`.`id` = `dotd`.`dish_id` ';
			$query .= 'WHERE `dotd`.`date` = ';
			$query .= (!$dotd_date || !is_date($dotd_date)) ? make_sql_date('today') : make_sql_date($dotd_date);

			$result = $db->query($query);

			if ($db->error) {
				$dotd['error']['id']	  = $db->errno;
				$dotd['error']['message'] = $db->error;
			}
			else if ($result && $result->num_rows == null) {
				//$dotd['error']['id'] 	 = 10;
				//$dotd['error']['message'] = 'THERE IS NO DISH OF THE DAY';
				return self::set_dish_of_the_day(false, $dotd_date);
			}
			else if ( $result ) {
				$dotd['dish'] = array();

				while ($entry = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_entry = $db->safe_output_string_array($entry);

					$dotd['dish'][$safe_entry['id']] = array (
						'id' 			=> $safe_entry['id'],
						'name' 			=> $safe_entry['name'],
						'description' 	=> $safe_entry['description'],
						'url' 			=> $safe_entry['url'],
						'created_at' 	=> $safe_entry['created_at']
					);
				}
				$result->free();
			}

			return array('dish_of_the_day' => $dotd);
		}

		public static function get_dish ( $dish_id, $include_tags=false ) {
			if (!is_numeric($dish_id) && !self::dish_exists($dish_id)) return false;

			$db = Boot::$db;

			$query = 'SELECT * FROM `dish` ';

			$result = $db->query('SELECT * FROM `dish` WHERE `id` = ' . $dish_id);

			return (!$result) ? false : $result->fetch_array(MYSQLI_ASSOC);
		} 

		public static function get_dish_with_tags ($dish_id) {

		}

		public static function get_random_dish () {
			if ($result  = $db->query('SELECT `id` FROM `ready_to_eat` ORDER BY rand() LIMIT 1') ) {
				$entry   = $result->fetch_array(MYSQLI_ASSOC);
				$dish_id = $entry['id'];
				$result->free();

				return self::get_dish_with_tags($dish_id);
			}
			return false;
		}

		public static function dish_exists ( $dish ) {
			if (!is_numeric($dish) && !is_string($dish)) return false;

			$db = Boot::$db;

			return (is_numeric($dish)) ? $db->entry_exists('dish', $dish) : $db->string_entry_exists('dish', 'name', $dish);
		}
	}

/********** OLD
	// FORM
	if ( $app_data['controller'] == 'dish' || $app_data['page'] == 'calendar' ) {

		$dish_form = array(
			'error' => false
		);

		$result = $db->query('SELECT * FROM tag');

		if ($db->error) {
			$dish_form['error']['id'] = $db->errno;
			$dish_form['error']['message'] = 'FORM DISH: ' . $db->error;
		}
		else {
			$dish_form['tags'] = array();
			while ($tag = $result->fetch_array(MYSQLI_ASSOC))
				array_push($dish_form['tags'], $db->safe_output_string_array($tag));

			unset($query);
			$result->free();
		}
	}

	// CREATE
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
		$query .= 'LIMIT 5';

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
OLD **********/
?>
