<!-- class should be called "meal_list_item" or "weekday_meal"	-->
		<li class="meal_item">
			<span class="meal"><?= $meal['name']; ?></span>
			<h4 class="dish"><?= $meal['dish']['name']; ?></h4>
			<p class="dish_tags">
				<?php foreach ($meal['dish']['tags'] as $dish_tag) echo $dish_tag . ' '; ?>
			</p>
			<p class="dish_description">
				<?= $meal['dish']['description']; ?>
			</p>
			<?php if (count($meal['shopping_list']) > 0) { ?>
			<ul class="meal_shopping_list icon-basket">
				<?php // UL with eblypling ?>
				<?php
					foreach ($meal['shopping_list'] as $shopping_list_item) {
						echo "<li>" . $shopping_list_item . "</li>\n";
					}
				?>
			</ul>
			<?php } ?>
