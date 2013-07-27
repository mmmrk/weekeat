<?php
	if (isset($app->view_data['calendar_week']) && $app->view_data['calendar_week']['error'])
		echo '<p class="error">' . $app->view_data['calendar_week']['error']['id'] . '. ' . $app->view_data['calendar_week']['error']['message'] . '</p>';
	else if (isset($app->view_data['calendar_week']) && !$app->view_data['calendar_week']['error']) {
		$month_meals	= $app->view_data['calendar_week']['meals'];
		$calendar		= $app->view_data['calendar_week']['calendar'];

		require('_week.php');
		require('_sidebar.php');
	}
?>