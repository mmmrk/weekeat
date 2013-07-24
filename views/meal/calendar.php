<?php
	if (isset($meal_calendar) && $meal_calendar['error'])
		echo '<p class="error">' . $meal_calendar['error']['id'] . '. ' . $meal_calendar['error']['message'] . '</p>';
	else if (isset($meal_calendar) && !$meal_calendar['error']) {
		$meals = $meal_calendar['meals'];
		$calendar = $meal_calendar['calendar'];

		require('_week.php');
		require('_sidebar.php');
	}
?>