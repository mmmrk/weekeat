<?php
	if (isset($eatage_calendar) && $eatage_calendar['error'])
		echo '<p class="error">' . $eatage_calendar['error']['id'] . '. ' . $eatage_calendar['error']['message'] . '</p>';
	else if (isset($eatage_calendar) && !$eatage_calendar['error']) {
		$calendar = $eatage_calendar['calendar'];
?>
<div id="eatage_week" class="table">
		<?php
			$current_week = $calendar->get_week();
			foreach ($current_week['days'] as $day) {
		?>
			<div class="tr">
				<div class="td day<?= (($day['date']['string'] == $calendar->today_date()) ? ' today' : ''); ?>">
					<?php 
						echo $day['date']['weekday'] . ' ( ' . $day['date']['string'] . " )\n\t\t";
						echo (array_key_exists($day['date']['string'], $eatage_calendar['eatages']) ? "\n\t\t" . $eatage_calendar['eatages'][$day['date']['string']]['dish'] : '');
					?>
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>