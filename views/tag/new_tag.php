<?php
	if (isset($new_tag) && $new_tag['error'])
		echo '<p class="error">' . $new_tag['error']['id'] . '. ' . $new_tag['error']['message'] . '</p>';
	else if (isset($new_tag) && !$new_tag['error']) {
?>
<h4>New tag added</h4>
			<table>
				<tbody>
					<tr>
						<th>Name:</th>
						<td><?= $new_tag['name']; ?></td>
					</tr>
				</tbody>
			</table>
<?php } ?>