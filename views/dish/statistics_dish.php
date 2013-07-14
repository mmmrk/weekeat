<?php
	if (isset($dish_statistics) && $dish_statistics['error'])
		echo '<p class="error">' . $dish_statistics['error']['id'] . '. ' . $dish_statistics['error']['message'] . '</p>';
	else if (isset($dish_statistics) && !$dish_statistics['error']) {
?>
<div id="dish_statistics">
						<p>Total number of dishes: <?= count($dish_statistics['dishes']); ?></p>
						<table id="stats_per_dish">
							<caption>Dishes</caption>
							<thead>
								<tr>
									<th>Dish</th>
									<th>Added at</th>
									<th>Tag Count</th>
									<th>Times Eaten</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($dish_statistics['dishes'] as $dish) { ?>
	<tr class="<?= $dish['row_class']; ?>">
									<td><?= $dish['name']; ?></td>
									<td><?= $dish['created_at']; ?></td>
									<td><?= $dish['num_tags']; ?></td>
									<td><?= $dish['times_eaten']; ?></td>
								</tr>
							<?php } ?>
</tbody>
						</table>
					</div>
<?php } ?>