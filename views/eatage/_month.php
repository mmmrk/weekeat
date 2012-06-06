<?php // requirements: $eatages (eatage_controller) $calendar (calendar) ?>
	<?php $month = $calendar->get_month(); ?>
	<div id="month_calendar">
		<header>
			<h3><?= $month['string']; ?></h3>
		</header>
		<div class="table">
			<div class="thead tr">
				<!--<div class="th week">Wk</div>-->
				<div class="td week"></div>
				<div class="th day">Mon</div>
				<div class="th day">Tue</div>
				<div class="th day">Wed</div>
				<div class="th day">Thu</div>
				<div class="th day">Fri</div>
				<div class="th day">Sat</div>
				<div class="th day">Sun</div>
			</div>
			<?php foreach ($calendar->get_all_weeks() as $week) { ?>
			<div class="tr">
				<div class="td week"><?= $week['week_number']; ?></div>
				<?php
					foreach ($week['days'] as $day) {
						$today_content = (array_key_exists($day['date']['string'], $eatages)) ? $eatages[$day['date']['string']] : '';
				?>	
					<div class="td day<?= !empty($today_content) ? ' booked' : ''; ?><?= ($day['date']['string'] == $application_data['curdate']) ? ' today' : ''; ?>">
						<?= '<a href="' . $_SERVER['PHP_SELF'] . '?view=calendar&date=' . $day['date']['string'] .'">' . $day['date']['day'] . '</a>'; ?>
					</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>