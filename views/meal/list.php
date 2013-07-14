<?php
	if (isset($meal_list) && $meal_list['error'])
		echo '<p class="error">' . $meal_list['error']['id'] . '. ' . $meal_list['error']['message'] . '</p>';
	else if (isset($meal_list) && !$meal_list['error']) {
?>
<table id="meal_list">
				<thead>
					<tr>
						<th>Date</th>
						<th>Dish</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($meal_list['meals'] as $meal) { ?>
<tr class="<?= $meal['row_class']; ?>">
						<td><?= $meal['date']; ?></td>
						<td><?= $meal['dish']; ?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
<?php } ?>