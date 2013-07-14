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
						<td rowspan="<?= $tagcount; ?>"><?= $dish['name']; ?></td>
						<td><?= $dish['tags'][0]; ?></td>
						<td rowspan="<?= $tagcount; ?>"><?= $dish['created_at']; ?></td>
					</tr>
					<?php
						for($i=1; $i<$tagcount; $i++)
							echo '<tr class="' . $dish['row_class'] . '"><td>' . $dish['tags'][$i] . "</td></tr>\n\t\t\t\t\t";
						}
 					?>

 				</tbody>
			</table>
<?php } ?>