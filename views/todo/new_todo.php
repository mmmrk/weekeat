<?php
	if (isset($new_todo) && $new_todo['error'])
		echo '<p class="error">' . $new_todo['error']['id'] . '. ' . $new_todo['error']['message'] . '</p>';
	else if (isset($new_todo) && !$new_todo['error']) {
		$todo = $new_todo;
?>
<h4>New todo added</h4>
			<table>
				<tbody>
					<?php require_once('/views/todo/_todo.php'); ?>
				</tbody>
			</table>
<?php } ?>