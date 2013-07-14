<?php
	if (isset($todo_list) && $todo_list['error'])
		echo '<p class="error">' . $todo_list['error']['id'] . '. ' . $todo_list['error']['message'] . '</p>';
	else if (isset($todo_list) && !$todo_list['error']) {
?>
<table id="todo_list">
				<thead>
					<tr>
						<th>ID</th>
						<th>Item</th>
						<th>Priority</th>
						<th>Status</th>
						<th>Added at</th>
						<th>Completed at</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($todo_list['todos'] as $todo) { ?>
<tr class="<?= $todo['row_class']; ?>">
						<td><?= $todo['id']; ?></td>
						<td><?= $todo['item']; ?></td>
						<td><?= $todo['priority']; ?></td>
						<td><?= $todo['status']; ?></td>
						<td><?= $todo['created_at']; ?></td>
						<td><?= $todo['completed_at']; ?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
<?php } ?>