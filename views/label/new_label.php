<?php
	if (isset($new_label) && $new_label['error'])
		echo '<p class="error">' . $new_label['error']['id'] . '. ' . $new_label['error']['message'] . '</p>';
	else if (isset($new_label) && !$new_label['error']) {
?>
<h4>New label added</h4>
			<table>
				<tbody>
					<tr>
						<th>Name:</th>
						<td><?= $new_label['name']; ?></td>
					</tr>
				</tbody>
			</table>
<?php } ?>