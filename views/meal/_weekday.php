<h3><?= $day['date']['weekday']; ?> <time datetime="<?= $day['date']['string']; ?>" class="date"><?= $day['date']['day_short']; ?></time></h3>
	<!-- ul should be called weekday or weekday_meals -->
	<ul class="meal_list">
	<?php
		//var_dump($weekday_meals);
		//FOREACH MEAL ITEM ON THIS DAY
		if (isset($weekday_meals) && !empty($weekday_meals) && is_array($weekday_meals))
			foreach ($weekday_meals as $meal)
				require('_weekday_meal.php');

		else { 
	?>
		<!-- more descriptive class name would be "no_meal_items" or "weekday_empty" or sth -->
		<li class="no_meals_item">
			<p>No meals today</p>
			<p><a class="button action" href="?section=meal&amp;page=add&amp;date=<?php echo $day['date']['string']; ?>"><i class="icon">+</i> Add a meal</a></p>
		</li>
	<?php } ?>
	</ul>