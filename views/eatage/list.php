<?php
	if (isset($eatage_list) && $eatage_list['error'])
		echo '<p class="error">' . $eatage_list['error']['id'] . '. ' . $eatage_list['error']['message'] . '</p>';
	else if (isset($eatage_list) && !$eatage_list['error']) {
?>
<table id="eatage_list">
				<thead>
					<tr>
						<th>Date</th>
						<th>Dish</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($eatage_list['eatages'] as $eatage) { ?>
<tr class="<?= $eatage['row_class']; ?>">
						<td><?= $eatage['date']; ?></td>
						<td><?= $eatage['dish']; ?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
<?php } ?>