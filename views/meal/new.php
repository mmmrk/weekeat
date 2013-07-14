<?php
	if (isset($new_meal) && $new_meal['error'])
		echo '<p class="error">' . $new_meal['error']['id'] . '. ' . $new_meal['error']['message'] . '</p>';
	else if (isset($new_meal) && !$new_meal['error']) {
		$dish = $new_meal['dish'];
?>
<h4>New meal added</h4>
			<table>
				<tbody>
					<tr>
						<th>Date:</th>
						<td><?= $new_meal['date']; ?></td>
					</tr>
					<?php require('/views/dish/_dish.php'); ?>
				</tbody>
			</table>
<?php } ?>