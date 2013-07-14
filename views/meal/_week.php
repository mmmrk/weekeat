<?php // requirements: $meals (meal_controller) $calendar (calendar) ?>
	<?php $week = $calendar->get_week(); ?>
	<div id="week_calendar">
		<ul>
		<?php 
			foreach ($week['days'] as $day) { 
				$meal = (array_key_exists($day['date']['string'], $meals)) ? $meals[$day['date']['string']] : false;
		?>
			<li class="week_calendar_item <?= (($day['date']['string'] == $calendar->today_date()) ? ' today current' : ''); ?>">
				<h3><?= $day['date']['weekday']; ?> <time datetime="<?= $day['date']['string']; ?>" class="date"><?= $day['date']['day_short']; ?></time></h3>
				<ul class="meal_list">
				<?php 
					//FOREACH MEAL ITEM ON THIS DAY
					if ($meal) { ?>
					<li class="meal_item">
						<span class="meal">MEAL TITLE</span>
						<h4 class="dish"><?= "DISH"//$meal['dish']; ?></h4>
						<p class="dish_tags">
							<?php // foreach ($meal['tags'] as $tag) echo "$tag "; ?>
						</p>
						<p class="dish_description">
							<?= "DESCRIPTION"//$meal['description']; ?>
						</p>
						<ul class="meal_shopping_list">
							<?php // UL with eblypling ?>
							<?= "<li>SHOPPING LIST</li>"//$meal['shopping_list']; ?>
						</ul>
				<?php }
				  else { ?>
					<li class="no_meals_item">
						<p>No meals today</p>
						<p><a class="button" href="?p=add_meal&amp;d=201307<?php echo $day['date']['weekday_short']; ?>"><i class="icon">+</i> Add a meal</a></p>
					</li>
				<?php } ?>
				</ul>
			</li>
		<?php } ?>
		</ul>
	</div>