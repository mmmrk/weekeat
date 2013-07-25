<?php
	class TodoController {

		public static function form () {
			$form_todo = array(
				'error' => false
			);

			$prio_query		= 'SELECT * FROM priority';
			$status_query	= 'SELECT * FROM status';

			$prio_result	= $db->query($prio_query);
			$status_result	= $db->query($status_query);

			if ($db->error) {
				$form_tag['error']['id'] = $db->errno;
				$form_tag['error']['message'] = 'FORM TODO: ' . $db->error;
			}
			else if ($prio_result && $status_result) {
				$form_todo['priorities'] = array();
				$form_todo['statuses']	 = array();

				while ($prio = $prio_result->fetch_array(MYSQLI_ASSOC)) {
					$safe_prio = $db->safe_output_string_array($prio);
					array_push($form_todo['priorities'], $safe_prio);
				}

				while ($status = $status_result->fetch_array(MYSQLI_ASSOC)) {
					$safe_status = $db->safe_output_string_array($status);
					array_push($form_todo['statuses'], $safe_status);
				}
			}

			return $form_todo;
		}

		public static function create () {
			$new_todo = array (
				'error' => false
			);

			( !empty($_POST['item'])		) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: item');
			( !empty($_POST['priority_id'])	) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: priority');
			( !empty($_POST['status_id'])	) ? null : $new_todo['error'][] = array('id' => '1.3', 'message' => 'Missing required field: status');

			if ( !$new_todo['error'] ) {
				$safe_input = $db->safe_input_string_array($_POST);

				$query = 'INSERT INTO `todo` (`item`, `priority_id`, `status_id`, `created_at`) VALUES ("' . $safe_input['item'] . '", ' . $safe_input['priority_id'] . ', '  . $safe_input['status_id'] . ', NOW())';
				$todo_id = $db->iquery($query);

				if ( $db->error ) {
					$new_todo['error']['id'] = $db->errno;
					$new_todo['error']['message'] = 'NEW TODO: ' . $db->error;
				}
				else if ( $todo_id ) {
					$query  = 'SELECT `todo`.*, `priority`.`name` AS `priority`, `status`.`state` AS `status` ';
					$query .= 'FROM `todo` ';
					$query .= 'JOIN `priority` ON `priority`.`id` = `todo`.`priority_id` ';
					$query .= 'JOIN `status` ON `status`.`id` = `todo`.`status_id` ';
					$query .= 'WHERE `todo`.`id` = ' . $todo_id;

					$result = $db->query($query);
					$safe_todo = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
					foreach ($safe_todo as $key => $value)
						$new_todo[$key] = $value;
				}
			}

			return $new_todo;
		}

		public static function get_list () {
			$todo_list = array (
				'error' => false
			);

			$query  = 'SELECT `todo`.*, `priority`.`name` AS `priority`, `status`.`state` AS `status` ';
			$query .= 'FROM `todo` ';
			$query .= 'JOIN `priority` ON `priority`.`id` = `todo`.`priority_id` ';
			$query .= 'JOIN `status` ON `status`.`id` = `todo`.`status_id` ';
			$query .= 'ORDER BY FIELD(`status`.`state`, "ongoing", "ignored", "pending", "done"), FIELD(`priority`.`name` , "critical", "high", "medium", "low"), `todo`.`id` ASC';
		
			$result = $db->query($query);

			if ( $db->error ) {
				$todo_list['error']['id'] = $db->errno;
				$todo_list['error']['message'] = 'NEW TODO: ' . $db->error;
			}
			else if ( $result ) {
				$todo_list['todos'] = array();
				$counter = 0;

				while ($todo = $result->fetch_array(MYSQLI_ASSOC)) {
					$safe_todo = $db->safe_output_string_array($todo);
					$safe_todo['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';

					array_push($todo_list['todos'], $safe_todo);
				}

				$todo_list['num_todos'] = count($todo_list['todos']);

				unset($query);
				unset($counter);
				$result->free();
			}

			return $todo_list;
		}
	}

	// FORM
	if ( $app_data['controller'] == 'todo' ) {
		$form_todo = array(
			'error' => false
		);

		$prio_query		= 'SELECT * FROM priority';
		$status_query	= 'SELECT * FROM status';

		$prio_result	= $db->query($prio_query);
		$status_result	= $db->query($status_query);

		if ($db->error) {
			$form_tag['error']['id'] = $db->errno;
			$form_tag['error']['message'] = 'FORM TODO: ' . $db->error;
		}
		else if ($prio_result && $status_result) {
			$form_todo['priorities'] = array();
			$form_todo['statuses']	 = array();

			while ($prio = $prio_result->fetch_array(MYSQLI_ASSOC)) {
				$safe_prio = $db->safe_output_string_array($prio);
				array_push($form_todo['priorities'], $safe_prio);
			}

			while ($status = $status_result->fetch_array(MYSQLI_ASSOC)) {
				$safe_status = $db->safe_output_string_array($status);
				array_push($form_todo['statuses'], $safe_status);
			}
		}
	}

	// NEW
	if ( $app_data['controller'] == 'todo' && $app_data['action'] == 'new' ) {
		$new_todo = array (
			'error' => false
		);

		( !empty($_POST['item'])		) ? null : $new_todo['error'][] = array('id' => '1.1', 'message' => 'Missing required field: item');
		( !empty($_POST['priority_id'])	) ? null : $new_todo['error'][] = array('id' => '1.2', 'message' => 'Missing required field: priority');
		( !empty($_POST['status_id'])	) ? null : $new_todo['error'][] = array('id' => '1.3', 'message' => 'Missing required field: status');

		if ( !$new_todo['error'] ) {
			$safe_input = $db->safe_input_string_array($_POST);

			$query = 'INSERT INTO `todo` (`item`, `priority_id`, `status_id`, `created_at`) VALUES ("' . $safe_input['item'] . '", ' . $safe_input['priority_id'] . ', '  . $safe_input['status_id'] . ', NOW())';
			$todo_id = $db->iquery($query);

			if ( $db->error ) {
				$new_todo['error']['id'] = $db->errno;
				$new_todo['error']['message'] = 'NEW TODO: ' . $db->error;
			}
			else if ( $todo_id ) {
				$query  = 'SELECT `todo`.*, `priority`.`name` AS `priority`, `status`.`state` AS `status` ';
				$query .= 'FROM `todo` ';
				$query .= 'JOIN `priority` ON `priority`.`id` = `todo`.`priority_id` ';
				$query .= 'JOIN `status` ON `status`.`id` = `todo`.`status_id` ';
				$query .= 'WHERE `todo`.`id` = ' . $todo_id;

				$result = $db->query($query);
				$safe_todo = $db->safe_output_string_array($result->fetch_array(MYSQLI_ASSOC));
				foreach ($safe_todo as $key => $value)
					$new_todo[$key] = $value;
			}
		}
	}

	// LIST
	if ( $app_data['controller'] == 'todo' && $app_data['action'] == 'list' ) {
		$todo_list = array (
			'error' => false
		);

		$query  = 'SELECT `todo`.*, `priority`.`name` AS `priority`, `status`.`state` AS `status` ';
		$query .= 'FROM `todo` ';
		$query .= 'JOIN `priority` ON `priority`.`id` = `todo`.`priority_id` ';
		$query .= 'JOIN `status` ON `status`.`id` = `todo`.`status_id` ';
		$query .= 'ORDER BY FIELD(`status`.`state`, "ongoing", "ignored", "pending", "done"), FIELD(`priority`.`name` , "critical", "high", "medium", "low"), `todo`.`id` ASC';
	
		$result = $db->query($query);

		if ( $db->error ) {
			$todo_list['error']['id'] = $db->errno;
			$todo_list['error']['message'] = 'NEW TODO: ' . $db->error;
		}
		else if ( $result ) {
			$todo_list['todos'] = array();
			$counter = 0;

			while ($todo = $result->fetch_array(MYSQLI_ASSOC)) {
				$safe_todo = $db->safe_output_string_array($todo);
				$safe_todo['row_class'] = ($counter++ % 2 == 0) ? 'even' : 'odd';

				array_push($todo_list['todos'], $safe_todo);
			}

			$todo_list['num_todos'] = count($todo_list['todos']);

			unset($query);
			unset($counter);
			$result->free();
		}
	}
?>