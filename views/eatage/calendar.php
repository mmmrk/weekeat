<?php
	if (isset($eatage_calendar) && $eatage_calendar['error'])
		echo '<p class="error">' . $eatage_calendar['error']['id'] . '. ' . $eatage_calendar['error']['message'] . '</p>';
	else if (isset($eatage_calendar) && !$eatage_calendar['error']) {
		$eatages = $eatage_calendar['eatages'];
		$calendar = $eatage_calendar['calendar'];
?>
<div id="calendar">
	<?php
		require('_week.php');
		require('_month.php');
	?>
	</div>
<?php } ?>