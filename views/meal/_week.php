<?php // requirements: $meals (meal_controller) $calendar (calendar) ?>
	<?php $week = $calendar->get_week(); ?>
	<div id="week_calendar">
		<ul>
		<?php
			//var_dump($month_meals);
			foreach ($week['days'] as $day) {
				$weekday_meals = (array_key_exists($day['date']['string'], $month_meals)) ? $month_meals[$day['date']['string']] : false;
		?>
			<li class="week_calendar_item <?= (($day['date']['string'] == $calendar->today_date()) ? ' today current' : ''); ?>">
				<?php require('_weekday.php'); ?>
			</li>
		<?php } ?>
		</ul>
	</div>