<?php // requirements: $meals (meal_controller) $calendar (calendar) ?>
	<?php $month = $calendar->get_month(); ?>
	<nav id="calendar_nav">
		<h3><?= $month['string']; ?></h3>
		<div class="inner">
			<table>
				<thead>
					<tr>
						<th class="week"></th>
						<th class="day">M</th>
						<th class="day">T</th>
						<th class="day">W</th>
						<th class="day">T</th>
						<th class="day">F</th>
						<th class="day">S</th>
						<th class="day">S</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($calendar->get_all_weeks() as $week) { ?>
					<tr>
						<th class="week" scope="row"><?= $week['week_number']; ?></th>
						<?php
							foreach ($week['days'] as $day) {
								$today_content = (array_key_exists($day['date']['string'], $month_meals)) ? $month_meals[$day['date']['string']] : '';
						?>
						<td class="day<?= !empty($today_content) ? ' booked' : ''; ?><?= ($day['date']['string'] == $app->current_date) ? ' today' : ''; ?>">
							<?= '<a href="' . $_SERVER['PHP_SELF'] . '?section=meal&date=' . $day['date']['string'] .'">' . $day['date']['day'] . '</a>'; ?>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</nav>
