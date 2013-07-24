<?php
	class TagController {

		public static function add () {
			$form_tag = array(
				'error' => false
			);

			if ($db->error) {
				$form_tag['error']['id'] = $db->errno;
				$form_tag['error']['message'] = 'FORM LABEL: ' . $db->error;
			}
			else {}

			return $form_tag;
		}

		public static function create () {
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

			return $new_tag;
		}

		public static function get_statistics () {
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

			return $tag_statistics;
		}

	}

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
?>