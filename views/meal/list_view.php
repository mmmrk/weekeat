<?php
	if (isset($app->view_data['list_view']) && $app->view_data['list_view']['error'])
		echo '<p class="error">' . $app->view_data['list_view']['error']['id'] . '. ' . $app->view_data['list_view']['error']['message'] . '</p>';
	else if (isset($app->view_data['list_view']) && !$app->view_data['list_view']['error']) {
?>
<table id="meal_list">
				<thead>
					<tr>
						<th>Date</th>
						<th>Dish</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($app->view_data['list_view']['meals'] as $meal) { ?>
<tr class="<?= $meal['row_class']; ?>">
						<td><?= $meal['date']; ?></td>
						<td><?= $meal['dish']; ?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
<?php } ?>