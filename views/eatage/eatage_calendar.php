<?php
	if (isset($eatage_calendar) && $eatage_calendar['error'])
		echo '<p class="error">' . $eatage_calendar['error']['id'] . '. ' . $eatage_calendar['error']['message'] . '</p>';
	else if (isset($eatage_calendar) && !$eatage_calendar['error']) {
		$calendar = $eatage_calendar['calendar'];
?>
	<div id="eatage_calendar" class="table">
		<div class="thead tr">
			<!--<div class="th week">Wk</div>-->
			<div class="td week"></div>
			<div class="th day">Monday</div>
			<div class="th day">Tuesday</div>
			<div class="th day">Wednesday</div>
			<div class="th day">Thursday</div>
			<div class="th day">Friday</div>
			<div class="th day">Saturday</div>
			<div class="th day">Sunday</div>
		</div>
		<?php foreach ($calendar->get_all_weeks() as $week) { ?>
<div class="tr">
			<div class="td week"><?= $week['week_number']; ?></div>
			<?php
				foreach ($week['days'] as $day) {
					$today_content = (array_key_exists($day['date']['string'], $eatage_calendar['eatages'])) ? $eatage_calendar['eatages'][$day['date']['string']] : '';
					
					echo '<div class="td day' . (($day['date']['string'] == $calendar->today_date()) ? ' today' : '') . '">' . "\n\t\t" . $day['date']['day'];
					echo !empty($today_content) ? '<p>' . $today_content['dish'] . '</p>' : '';
					echo "\n\t\t</div>\n\t\t";
				}
			?>
</div>
<?php 
		}
	}
?>