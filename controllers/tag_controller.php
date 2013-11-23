<?php
	class TagController {

		public static function add () {
			$db = Boot::$db;

			$tag_form = array(
				'error' => false
			);

			if ($db->error) {
				$tag_form['error']['id'] = $db->errno;
				$tag_form['error']['message'] = 'FORM LABEL: ' . $db->error;
			}
			else {}

			return array('tag_form' => $tag_form);
		}

		public static function create () {
			$db = Boot::$db;

			$new_tag = array (
				'error' => false
			);

			( !empty($_POST['new_tag']) && !empty($_POST['name']) ) ? null : $new_tag['error'][] = array('id' => '1.1', 'message' => 'Missing required field: name'); 

			if ( !$new_tag['error'] ) {
				$safe_input = $db->safe_input_string_array($_POST);

				echo $query = 'INSERT INTO `tag` (`name`, `created_at`) VALUES ("' . $safe_input['name'] . '", NOW())'; 

				$tag_id = $db->iquery($query);

				if ( $db->error ) {
					$new_tag['error']['id'] = $db->errno;
					$new_tag['error']['message'] = 'NEW LABEL: ' . $db->error;
				}
				else if ( $tag_id ) {
					$new_tag['id'] = $tag_id;
					$new_tag['name'] = $safe_input['name'];
				}
			}

			return array('new_tag' => $new_tag);
		}

		public static function make_dish_link_query ($dish_id, $tag_ids) {
			$link_query = false;

			if (is_numeric($dish_id) && $dish_id > 0 && !empty($tag_ids) && is_array($tag_ids)) {
				$link_query  = 'INSERT INTO `dish_tags` (`dish_id`, `tag_id`, `created_at`) ';
				$link_query .= 'VALUES ';

				foreach ($tag_ids as $tag_id)
					$link_query .= '(' . $dish_id . ', ' . $tag_id . ', NOW()), ';
			}

			return $link_query;
		}

		public static function link_dish_tags ($dish_id, $tag_ids, $transaction_db=false) {
			
			$db = ($transaction_db) ? $transaction_db : Boot::$db;
			$dish_tags = array (
				'error' => false
			);

			if (!empty($tag_ids) && is_array($tag_ids)) {
				
				$db->autocommit(false);
				$transaction_errors = false;

				$tag_query  = 'INSERT INTO `dish_tags` (`dish_id`, `tag_id`, `created_at`) ';
				$tag_query .= 'VALUES ';

				foreach ($tag_ids as $tag_id)
					$tag_query .= '(' . $dish_id . ', ' . $tag_id . ', NOW()), ';

				//some cleaning up on the query
				echo $tag_query  = (substr($tag_query, -2) == ', ') ? substr($tag_query, 0, -2) : $tag_query;

				($db->iquery($tag_query)) ? null : $transaction_errors = 'DISH TAG INSERT ERROR';

				if ($transaction_errors || $db->error) {
					if (!$transaction_db) $db->rollback();

					$dish_tags['error']['id'] = $db->errno;
					$dish_tags['error']['message'] = 'DISH TAG LINK: ' . $db->error . ' (' . $transaction_errors . ')';
				}
				else {
					if (!$transaction_dbn) $db->commit();
					
					$dish_tags['num_tags'] = $db->affected_rows;
					$dish_tags['link_success'] = ($db->affected_rows == count($tag_ids));
				}
			}

			return array('dish_tags' => $dish_tags);//$db->affected_rows == count($tag_ids);
		}

		public static function get_dish_tags ( $dish_id ) {
			$db = Boot::$db;
			
			$dish_tags = array();

			$query  = 'SELECT `id`, `name` ';
			$query .= 'FROM `tag` ';
			$query .= 'JOIN `dish_tags` ON `dish_tags`.`tag_id` = `tag`.`id` ';
			$query .= 'WHERE `dish_tags`.`dish_id` = ' . $dish_id;

			if (!$result = $db->query($query)) return false;

			while ($tag = $result->fetch_array(MYSQLI_ASSOC))
				array_push($tags, $db->safe_output_string_array($tag));
			
			$result->free();
			
			return $dish_tags;
		}

		public static function statistics () {
			$db = Boot::$db;
			
			$tag_statistics = array (
				'error' => false
			);

			$query 	= 'SELECT `lu`.`id` as `id`, `lu`.`tag` as `name`, `lu`.`times_used`, `le`.`times_eaten` ';
			$query .= 'FROM `tag_usage` AS `lu` ';
			$query .= 'JOIN `tag_meal` AS `le` ON `lu`.`id` = `le`.`id` ';
			$query .= 'ORDER BY `lu`.`id` ASC';

			$result = $db->query($query);

			if ( $db->error ) {
				$tag_statistics['error']['id'] = $db->errno;
				$tag_statistics['error']['message'] = 'LABEL STATISTICS: ' . $db->error;
			}
			else if ( $result ) {
				$tag_statistics['tags'] = array();
				$counter = 0;
				while ($stats = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_stats = $db->safe_output_string_array($stats);
					$safe_stats['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';

					array_push($tag_statistics['tags'], $safe_stats);
				}

				unset($counter);
				unset($query);
				$result->free();
			}

			return array('tag_statistics' => $tag_statistics);
		}

	}

/********** OLD
	// FORM
	if ( $app_data['controller'] == 'application' && $app_data['action'] == 'admin' ) {
		$form_tag = array(
			'error' => false
		);

		if ($db->error) {
			$form_tag['error']['id'] = $db->errno;
			$form_tag['error']['message'] = 'FORM LABEL: ' . $db->error;
		}
		else {}
	}

	// NEW
	if ( $app_data['controller'] == 'application' && $app_data['action'] == 'new'  && $app_data['page'] == 'admin' ) {
		$new_tag = array (
			'error' => false
		);

		( !empty($_POST['new_tag']) && !empty($_POST['name']) ) ? null : $new_tag['error'][] = array('id' => '1.1', 'message' => 'Missing required field: name'); 

		if ( !$new_tag['error'] ) {
			$safe_input = $db->safe_input_string_array($_POST);

			echo $query = 'INSERT INTO `tag` (`name`, `created_at`) VALUES ("' . $safe_input['name'] . '", NOW())'; 

			$tag_id = $db->iquery($query);

			if ( $db->error ) {
				$new_tag['error']['id'] = $db->errno;
				$new_tag['error']['message'] = 'NEW LABEL: ' . $db->error;
			}
			else if ( $tag_id ) {
				$new_tag['id'] = $tag_id;
				$new_tag['name'] = $safe_input['name'];
			}
		}
	}

	// STATISTICS
	if ( $app_data['controller'] == 'application' && $app_data['action'] == 'statistics' ) {
		$tag_statistics = array (
			'error' => false
		);

		$query 	= 'SELECT `lu`.`id` as `id`, `lu`.`tag` as `name`, `lu`.`times_used`, `le`.`times_eaten` ';
		$query .= 'FROM `tag_usage` AS `lu` ';
		$query .= 'JOIN `tag_meal` AS `le` ON `lu`.`id` = `le`.`id` ';
		$query .= 'ORDER BY `lu`.`id` ASC';

		$result = $db->query($query);

		if ( $db->error ) {
			$tag_statistics['error']['id'] = $db->errno;
			$tag_statistics['error']['message'] = 'LABEL STATISTICS: ' . $db->error;
		}
		else if ( $result ) {
			$tag_statistics['tags'] = array();
			$counter = 0;
			while ($stats = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_stats = $db->safe_output_string_array($stats);
				$safe_stats['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';

				array_push($tag_statistics['tags'], $safe_stats);
			}

			unset($counter);
			unset($query);
			$result->free();
		}
	}
OLD **********/
?>