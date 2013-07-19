<?php // requirements: $meals (meal_controller) $calendar (calendar) ?>
	<?php $week = $calendar->get_week(); ?>
	<div id="week_calendar">
		<ul>
		<?php 
			foreach ($week['days'] as $day) { 
				$meal = (array_key_exists($day['date']['string'], $meals)) ? $meals[$day['date']['string']] : false;
		?>
			<li class="week_calendar_item <?= (($day['date']['string'] == $calendar->today_date()) ? ' today current' : ''); ?>">
				<?php require('_weekday.php'); ?>
			</li>
		<?php } ?>
		</ul>
	</div>