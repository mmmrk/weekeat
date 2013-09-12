<?php // requirements: dish_list_latest (dish_controller) ?>
<div id="latest_dishes">
	<h3>Latest dishes</h3>
	<ul>
		<?php
			foreach ($app->view_data['latest_dishes_list']['dishes'] as $dish)
				require('_dish_list_item_short.php');
		?>
	</ul>
</div>
