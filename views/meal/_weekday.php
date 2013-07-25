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
			<p><a class="button action" href="?view=meal&amp;action=add&amp;date=<?php echo $day['date']['string']; ?>"><i class="icon">+</i> Add a meal</a></p>
		</li>
	<?php } ?>
	</ul>
