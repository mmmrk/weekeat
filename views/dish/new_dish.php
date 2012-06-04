<?php
	if (isset($new_dish) && $new_dish['error'])
		echo '<p class="error">' . $new_dish['error']['id'] . '. ' . $new_dish['error']['message'] . '</p>';
	else if (isset($new_dish) && !$new_dish['error']) {
		$dish = $new_dish;
?>
<h4>New dish added</h4>
			<table>
				<tbody>
					<?php require_once('/views/dish/_dish.php'); ?>
				</tbody>
			</table>
<?php } ?>