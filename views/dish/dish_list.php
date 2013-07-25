<?php
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
 				</tbody>
			</table>
<?php } ?>