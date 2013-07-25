<?php
	if (isset($meal_statistics) && $meal_statistics['error'])
		echo '<p class="error">' . $meal_statistics['error']['id'] . '. ' . $meal_statistics['error']['message'] . '</p>';
	else if (isset($meal_statistics) && !$meal_statistics['error']) {
?>
<div id="meal_statistics">
						<p>Total number of meals: <?= $meal_statistics['num_meals']; ?></p>
					</div>
<?php } ?>