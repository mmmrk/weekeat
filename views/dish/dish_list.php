<?php
	if (isset($dish_list) && $dish_list['error'])
		echo '<p class="error">' . $dish_list['error']['id'] . '. ' . $dish_list['error']['message'] . '</p>';
	else if (isset($dish_list) && !$dish_list['error']) {
?>
<table id="dish_list">
				<thead>
					<tr>
						<th>Dish</th>
						<th>Labels</th>
						<th>Add Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($dish_list['dishes'] as $dish) {
							$labelcount = count($dish['labels']);
					?>
<tr class="<?= $dish['row_class']; ?>">
						<td rowspan="<?= $labelcount; ?>"><?= $dish['name']; ?></td>
						<td><?= $dish['labels'][0]; ?></td>
						<td rowspan="<?= $labelcount; ?>"><?= $dish['created_at']; ?></td>
					</tr>
					<?php
						for($i=1; $i<$labelcount; $i++)
							echo '<tr class="' . $dish['row_class'] . '"><td>' . $dish['labels'][$i] . "</td></tr>\n\t\t\t\t\t";
						}
 					?>

 				</tbody>
			</table>
<?php } ?>