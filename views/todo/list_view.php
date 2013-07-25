<?php
	if (isset($app->view_data['list_view']) && $app->view_data['list_view']['error'])
		echo '<p class="error">' . $app->view_data['list_view']['error']['id'] . '. ' . $app->view_data['list_view']['error']['message'] . '</p>';
	else if (isset($app->view_data['list_view']) && !$app->view_data['list_view']['error']) {
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
					<?php foreach ($app->view_data['list_view']['todos'] as $todo) { ?>
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