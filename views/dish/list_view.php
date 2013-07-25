<?php
	var_dump($app->view_data);
	if (isset($app->view_data['list_view']) && $app->view_data['list_view']['error'])
		echo '<p class="error">' . $app->view_data['list_view']['error']['id'] . '. ' . $app->view_data['list_view']['error']['message'] . '</p>';
	else if (isset($app->view_data['list_view']) && !$app->view_data['list_view']['error']) {
?>
<table id="dish_list">
				<thead>
					<tr>
						<th>Dish</th>
						<th>Tags</th>
						<th>Add Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($app->view_data['list_view']['dishes'] as $dish) {
							$tagcount = count($dish['tags']);
					?>
					<tr class="<?= $dish['row_class']; ?>">
						<td><?= $dish['name']; ?></td>
						<td>
							<?php
								for ($i=0; $i++; $i<$tagcount) {
									echo $dish['tags'][$i];
									if ($i<$tagcount) echo ', ';
								}
							?>
						</td>
						<td><?= $dish['created_at']; ?></td>
					</tr>
					<?php } ?>
 				</tbody>
			</table>
<?php } ?>
