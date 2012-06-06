<?php // requirements: $eatages (eatage_controller) $calendar (calendar) ?>
	<?php $week = $calendar->get_week(); ?>
	<div id="week_calendar">
		<?php 
			foreach ($week['days'] as $day) { 
				$eatage = (array_key_exists($day['date']['string'], $eatages)) ? $eatages[$day['date']['string']] : false;
		?>
			<div class="day<?= (($day['date']['string'] == $calendar->today_date()) ? ' today' : ''); ?>">
				<header>
					<h4><?= $day['date']['string']; ?></h4>
					<h3><?= $day['date']['weekday']; ?></h3>
				</header>
				<?php if ($eatage) { ?>
				<article>
					<p>
						<span>Dish: </span>
						<?= $eatage['dish']; ?>
					</p>
					<p>
						<span>Labels: </span>
						<br />
						<?php 
							foreach ($eatage['labels'] as $label) 
								echo "$label ";
						?>
					</p>
					<p>
						<span>Recipe: </span>
						<br />
						<?= $eatage['recipe']; ?>
					</p>
					<p>
						<span>Shopping List: </span>
						<br />
						<?= $eatage['shopping_list']; ?>
					</p>
				</article>
				<?php } ?>
				<footer></footer>
			</div>
		<?php } ?>
	</div>