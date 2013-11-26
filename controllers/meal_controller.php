<?php
	class MealController {

		public static function add () {
			$db = Boot::$db;

			$meal_form = array (
				'error' => false
			);

			$dish_result	= $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat`');
			$tag_result 	= $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat_tags`');

			if ($db->error) {
				$new_meal['error']['id'] 		= $db->errno;
				$new_meal['error']['message'] 	= 'MEAL FORM: ' . $db->error;
			}
			else if ($dish_result && $tag_result) {
				$meal_form['ready_dishes']	= array();
				$meal_form['ready_tags'] 	= array();

				while ($ready_dish = $dish_result->fetch_array(MYSQLI_ASSOC))
					array_push($meal_form['ready_dishes'], $db->safe_output_string_array($ready_dish));

				while ($ready_tag = $tag_result->fetch_array(MYSQLI_ASSOC))
					array_push($meal_form['ready_tags'], $db->safe_output_string_array($ready_tag));

				$dish_result->free();
				$tag_result->free();
			}

			return array('meal_form' => $meal_form);
		}

		public static function make_create_query ($dish_id, $meal_name, $date, $shopping_list) {
			$meal_query = false;

			if ( (is_numeric($dish_id) && $dish_id > 0) && is_date($date) && !empty($meal_name) ) {
				$meal_query  = 'INSERT INTO `meal` (`date`, `dish_id`, `name`, `shopping_list`, `created_at`) ';
				$meal_query .= 'VALUES (' . make_sql_date($date) . ', ' . $dish_id . ', "' . $meal_name . '", "' . $shopping_list .  '", NOW())';
			}
		
			return $meal_query;
		}

		public static function new_create ($input_data, $redirect=false) {
			$db = Boot::$db;
			$db->autocommit(false);

			$new_meal = array (
				'error' => false
			);

			$input_dish	= $input_data['dish'];
			$input_meal	= $input_data['meal'];
			$input_date	= $input_data['display_date'];
			$add_method = $input_data['add_method'];

			if ($add_method == 1 && isset($input_dish['id'])) {
				( $dish = DishController::get_dish($input_dish['id']) ) ? null : $transaction_errors=true;
				$dish_id = ($dish) ? $dish['id'] : false;
			}
			else if ($add_method == 2 && isset($input_dish['name']) && $dish_query = DishController::make_create_query($input_dish) )
				( $dish_id = $db->iquery($dish_query) ) ? null : $transaction_errors=true;
			else
				$transaction_errors = true;

			if (isset($input_dish['tags']) && is_array($input_dish['tags']) && isset($dish_id) && $dish_link_query = TagController::make_dish_link_query($dish_id, $input_dish['tags']))
				$db->query($dish_link_query) ? null : $transaction_errors=true;
			else
				$transaction_errors = true;

			if (isset($input_meal['name']) && is_date($input_date) && isset($dish_id) && $meal_query = self::make_create_query($dish_id, $input_meal['name'], $input_date, $input_meal['shopping_list']))
				( $meal_id = $db->iquery($meal_query) ) ? null : $transaction_errors=true;
			else
				$transaction_errors = true;

			if ($transaction_errors)
				$db->rollback();
			else {
				$db->commit();

				/*foreach ($input_meal as $key => $value)
					$new_meal[$key] = $value;

				foreach ($input_dish as $key => $value)
					$new_meal['dish'][$key] => $value;

				foreach ($input_dish[])*/
			}
			if ($redirect) header('Location: ' . Application::current_page() . '?date=' . $meal['date']);
			
			return array('new_meal' => $new_meal);
		}

		public static function create ($input_data, $redirect=false) {
			$db = Boot::$db;
			
			$new_meal = array (
				'error' => false,
				'dish' => array (
						'error' => false
					)
			);

			$meal = $input_data['meal'];
			$dish = $input_data['dish'];
			$meal_date = $input_data['display_date'];

			( !empty($meal_date) && is_date($meal_date) 						) ? null : $new_meal['error'] = array('id' => '1.1', 'message' => 'Missing required field: meal date');
			( !empty($meal['name'])											 	) ? null : $new_meal['error'] = array('id' => '1.2', 'message' => 'Missing required field: meal name');
			( isset($input_data['add_method']) && $input_data['add_method'] > 0 ) ? null : $new_meal['error'] = array('id' => '1.3', 'message' => 'Missing add method');
			
			if (!$new_meal['error']) {

				$db->autocommit(false);
				
				// SET/CREATE MEAL DISH
				if (isset($dish['id']) && $input_data['add_method'] == 1 ) {
					( $dish = DishController::get_dish($dish['id']) ) ? null : $new_meal['dish']['error'] = array('id' => '1.1', 'message' => 'Selected dish does not exist or was not found');

					( $new_meal['dish']['error'] || !$dish ) ? null : $new_meal['dish']['id'] = $dish['id'];
				}
				else if (isset($dish['name']) && $input_data['add_method'] == 2) {
					//$result = DishController::create($input_data['dish'], $db);

					( $dish_query = DishController::make_create_query($dish) ) ? null : $new_meal['dish']['error'] = array('id' => '2.2', 'message' => 'Missing required field: dish data');
					( $dish_query && $dish_id = $db->iquery($dish_query) 	 ) ? null : $new_meal['dish']['error'] = array('id' => '2.3', 'message' => 'An error occured while creating the dish');

					if ( $new_meal['dish']['error'] || $db->error ) {
						$new_meal['dish']['error']['query'] = $dish_query;
						$new_meal['dish']['error']['sql'] 	= $db->errno . ' NEW DISH: ' . $db->error;
					}
					else {
						( $dish_tag_query = TagController::make_dish_link_query($dish_id, $dish['tags']) ) ? null : $new_meal['dish']['error'] = array('id' => '3.1', 'message' => 'Could not make dish tags. Missing/Faulty data');
						( $dish_tag_query && $dish_tags = $db->query($dish_tag_query) 					 ) ? null : $new_meal['dish']['error'] = array('id' => '3.2', 'message' => 'An error occurred while linking dish tags');

						if ( !$dish_tags || $new_meal['dish']['error'] || $db->error ) {
							$new_meal['dish']['error']['query'] = $dish_tag_query;
							$new_meal['dish']['error']['sql']	= $db->errno . ' DISH TAGS: ' . $db->error;
						}
						else {
							$new_meal['dish'] 			= $dish;
							$new_meal['dish']['id'] 	= $dish_id;
							$new_meal['dish']['tags'] 	= $dish['tags'];	
						}
					}
				}

				if (isset($new_meal['dish']['id'], $meal_date) && is_date($meal_date)) {
					( $meal_query = self::make_create_query($new_meal['dish']['id'], $meal['name'], $meal_date, $meal['shopping_list']) ) ? null : $new_meal['error'] = array('id' => '2.1', 'message' => 'Missing required field: meal data');
					( $meal_query && $meal_id = $db->iquery($meal_query) 																) ? null : $new_meal['error'] = array('id' => '2.2', 'message' => 'An error occured while creating the dish');

					//var_dump($meal_query);

					if ($new_meal['error'] || $db->error) {
						$new_meal['error']['query'] = $meal_query;
						$new_meal['error']['sql'] 	= $db->errno . ' NEW MEAL: ' . $db->error;
					}
					else if ( $meal_id ) {
						$new_meal['id'] = $meal_id;

						foreach ($meal as $key => $value)
							$new_meal[$key]	= $value;

						foreach ($dish as $key => $value)
							$new_meal['dish'][$key] = $value;
					}
				}	
			}

			( !$db->error && !$new_meal['error'] && (!isset($new_meal['dish']['error']) || !$new_meal['dish']['error']) ) ? $db->commit() : $db->rollback();

			//var_dump($new_meal);
			
			if ($redirect) header('Location: ' . Application::current_page() . '?date=' . $meal_date);
			
			return array('new_meal' => $new_meal);
		}

		public static function statistics () {
			$db = Boot::$db;

			$meal_statistics = array (
				'error' => false
			);

			$query  = 'SELECT COUNT(*) AS `num_meals` FROM `meal`';
			$result = $db->query($query);

			if ( $db->error ) {
				$meal_statistics['error']['id'] 	   = $db->errno;
				$meal_statistics['error']['message'] = 'MEAL STATISTICS: ' . $db->error;
			}
			else if ( $result ) {
				$safe_stats = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
				$meal_statistics['num_meals'] = $safe_stats['num_meals'];

				unset($query);
				$result->free();
			}

			return array('meal_statistics' => $meal_statistics);
		}

		public static function calendar_week ($display_date, $single_date_display=false) {
			$db = Boot::$db;

			$meal_calendar = array (
				'error' => false
			);

			$working_date = (is_date($display_date)) ? strtotime($display_date) : time();

			$calendar  = new CalendarView($working_date);
			$first_day = $calendar->first_day();
			$last_day  = $calendar->last_day();

			$query 	= 'SELECT `meal`.`id` AS `meal_id`, `meal`.`date` AS `date`, `dish`.`id` AS `dish_id`, `meal`.`name` AS `meal_name`, `dish`.`name` AS `dish_name`, `dish`.`description` AS `description`, `dish`.`url`, `dish`.`recipe`, `meal`.`shopping_list`, `tag`.`id` AS `tag_id`, `tag`.`name` AS `tag` ';
			$query .= 'FROM `meal` ';
			$query .= 'JOIN `dish` ON `dish`.`id` = `meal`.`dish_id` ';
			$query .= 'LEFT JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';

			if ($single_date_display && $sql_single_date = make_sql_date($display_date))
				$query .= 'WHERE `meal`.`date` = ' . $sql_single_date . ' ';
			else
				$query .= 'WHERE `meal`.`date` BETWEEN "' . $first_day['date']['string'] . '" AND "' . $last_day['date']['string'] . '" ';

			$query .= 'ORDER BY `date` ASC';

			$result = $db->query($query);

			if ( $db->error ) {
				$meal_calendar['error']['id'] 	 = $db->errno;
				$meal_calendar['error']['message'] = 'MEAL CALENDAR: ' . $db->error;
			}
			else if ( $result ) {
				$meal_calendar['meals']  = array();
				$meal_calendar['calendar'] = $calendar;

				while ( $meal = $result->fetch_array(MYSQLI_ASSOC) ) {
					$safe_meal 			= $db->safe_output_string_array($meal);
					$safe_meal['today'] = ($calendar->today_date() == $safe_meal['date']);

					if ( array_key_exists($safe_meal['date'], $meal_calendar['meals']) && array_key_exists($safe_meal['date']['meal_id'], $meal_calendar['meals'][$safe_meal['date']]) )
						$meal_calendar['meals'][$safe_meal['date']][$safe_meal['meal_id']]['dish']['tags'][$safe_meal['tag_id']] = $safe_meal['tag'];
					else {
						$meal_calendar['meals'][$safe_meal['date']][$safe_meal['meal_id']] = array (
							'meal_id'		=> $safe_meal['meal_id'],
							'name'			=> $safe_meal['meal_name'],
							'shopping_list'	=> ($safe_meal['shopping_list'] == '') ? array() : explode(', ', $safe_meal['shopping_list']),
							'dish'			=> array (
								'dish_id' 		=> $safe_meal['dish_id'],
								'name'			=> $safe_meal['dish_name'],
								'description'	=> $safe_meal['description'],
								'url'			=> $safe_meal['url'],
								'recipe'		=> $safe_meal['recipe'],
								'tags'			=> array ( $safe_meal['tag_id'] => $safe_meal['tag'] )
							)
						);
					}
				}
			}

			return (!$single_date_display) ? array('calendar_week' => $meal_calendar) : array('meals_of_the_day' => $meal_calendar);
		}

		public static function meals_of_the_day ($motd_date) {
			$db = Boot::$db;

			$motd = array (
				'error' => false
			);

			$sql_date = (is_date($motd_date)) ? $motd_date : date('Y-m-d');

			return self::calendar_week($sql_date, true);

/*			$query 	= 'SELECT `meal`.`id` AS `meal_id`, `meal`.`date` AS `date`, `dish`.`id` AS `dish_id`, `meal`.`name` AS `meal_name`, `dish`.`name` AS `dish_name`, `dish`.`description` AS `description`, `dish`.`url`, `dish`.`recipe`, `meal`.`shopping_list`, `tag`.`id` AS `tag_id`, `tag`.`name` AS `tag` ';
			$query .= 'FROM `meal` ';
			$query .= 'JOIN `dish` ON `dish`.`id` = `meal`.`dish_id` ';
			$query .= 'LEFT JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
			$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
			$query .= 'WHERE `meal`.`date` = ' . $sql_date . ' ';
			$query .= 'ORDER BY `dish_id` ASC';

			$result = $db->query($query);

			if ( $db->error ) {
				$motd['error']['id']		= $db->errno;
				$motd['error']['message']	= 'MEAL CALENDAR: ' . $db->error;
			}
			else if ( $result ) {
				$motd['meals']  = array();

				while ( $meal = $result->fetch_array(MYSQLI_ASSOC) ) {
					$safe_meal = $db->safe_output_string_array($meal);

					if ( array_key_exists($safe_meal['id'], $motd['meals']) )
						$motd['meals'][$safe_meal['id']]['tags'][$safe_meal['tag_id']] = $safe_meal['tag'];
					else {
						$motd['meals'][$safe_meal['id']] = array (
							'meal_id'		=> $safe_meal['meal_id'],
							'name'			=> $safe_meal['meal_name'],
							'shopping_list'	=> $safe_meal['shopping_list'],
							'dish'			=> array (
								'dish_id' 		=> $safe_meal['dish_id'],
								'name'			=> $safe_meal['dish_name'],
								'description'	=> $safe_meal['description'],
								'url'			=> $safe_meal['url'],
								'recipe'		=> $safe_meal['recipe'],
								'tags'			=> array ( $safe_meal['tag_id'] => $safe_meal['tag'] )
							)
						);
					}
				}
			}

			return array('meals_of_the_day' => $motd);
*/
		}

		public static function list_view () {
			$db = Boot::$db;

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
				$meal_list['error']['message'] = 'LIST MEALS: ' . $db->error;
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

			return array('meal_list' => $meal_list);
		}
	}


/********** OLD
	// FORM
	if ( $app_data['controller'] == 'meal' && $app_data['action'] == 'form' ) {
		$meal_form = array (
			'error' => false
		);

		$dish_result  = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat`');
		$tag_result = $db->query('SELECT DISTINCT `id`, `name` FROM `ready_to_eat_tags`');

		if ($db->error) {
			$new_meal['error']['id'] 		= $db->errno;
			$new_meal['error']['message'] = 'EATAGE FORM: ' . $db->error;
		}
		else if ($dish_result && $tag_result) {
			$meal_form['ready_dishes'] = array();
			$meal_form['ready_tags'] = array();

			while ($ready_dish = $dish_result->fetch_array(MYSQLI_ASSOC))
				array_push($meal_form['ready_dishes'], $db->safe_output_string_array($ready_dish));

			while ($ready_tag = $tag_result->fetch_array(MYSQLI_ASSOC))
				array_push($meal_form['ready_tags'], $db->safe_output_string_array($ready_tag));

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
				$new_meal['error']['message'] = 'NEW MEAL: Submitted date is not valid';
			}

			if ($db->error) {
				$new_meal['error']['id'] 		= $db->errno;
				$new_meal['error']['message'] = 'NEW MEAL: ' . $db->error;
			}
			else {
				$new_meal['date'] 			= $safe_input['date'];
				$new_meal['dish']['id']		= $dish_id;
				$new_meal['dish']['tags']	= array();

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
			$meal_statistics['error']['message'] = 'MEAL STATISTICS: ' . $db->error;
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

		$calendar  = new CalendarView($date);
		$first_day = $calendar->first_day();
		$last_day  = $calendar->last_day();

		$query 	= 'SELECT `meal`.`id` AS `meal_id`, `meal`.`date` AS `date`, `dish`.`id` AS `dish_id`, `dish`.`name` AS `dish`, `dish`.`url`, `dish`.`recipe`, `meal`.`shopping_list`, `tag`.`id` AS `tag_id`, `tag`.`name` AS `tag` ';
		$query .= 'FROM `meal` ';
		$query .= 'JOIN `dish` ON `dish`.`id` = `meal`.`dish_id` ';
		$query .= 'JOIN `dish_tags` ON `dish_tags`.`dish_id` = `dish`.`id` ';
		$query .= 'LEFT JOIN `tag` ON `tag`.`id` = `dish_tags`.`tag_id` ';
		$query .= 'WHERE `meal`.`date` BETWEEN "' . $first_day['date']['string'] . '" AND "' . $last_day['date']['string'] . '" ';
		$query .= 'ORDER BY `date` ASC';

		$result = $db->query($query);

		if ( $db->error ) {
			$meal_calendar['error']['id'] 	 = $db->errno;
			$meal_calendar['error']['message'] = 'MEAL CALENDAR: ' . $db->error;
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
			$meal_list['error']['message'] = 'LIST MEALS: ' . $db->error;
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
OLD **********/
?>