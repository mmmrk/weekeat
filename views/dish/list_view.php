<?php
	$dish_list = $app->view_data['list_view'];

	if (isset($dish_list) && $dish_list['error'])
		echo '<p class="error">' . $dish_list['error']['id'] . '. ' . $dish_list['error']['message'] . '</p>';
	else if (isset($dish_list) && !$dish_list['error']) {
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
						foreach ($dish_list['dishes'] as $dish) {
					?>
					<tr>
						<td><?= $dish['name']; ?></td>
						<td>
							<?php
								foreach ($dish['tags'] as $tag)
									echo $tag . ' ';
							?>
						</td>
						<td><?= $dish['created_at']; ?></td>
					</tr>
					<?php } ?>
 				</tbody>
			</table>
<?php } ?>
