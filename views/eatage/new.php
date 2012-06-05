<?php
	if (isset($new_eatage) && $new_eatage['error'])
		echo '<p class="error">' . $new_eatage['error']['id'] . '. ' . $new_eatage['error']['message'] . '</p>';
	else if (isset($new_eatage) && !$new_eatage['error']) {
		$dish = $new_eatage['dish'];
?>
<h4>New eatage added</h4>
			<table>
				<tbody>
					<tr>
						<th>Date:</th>
						<td><?= $new_eatage['date']; ?></td>
					</tr>
					<?php require('/views/dish/_dish.php'); ?>
				</tbody>
			</table>
<?php } ?>