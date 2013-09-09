<?php // requirements: dish_list_latest (dish_controller) ?>
<ul id="latest_dishes">
	<?php
		foreach ($app->view_data['latest_dishes_list']['dishes'] as $dish)
			require('_dish_list_item_short.php');
	?>
</ul>