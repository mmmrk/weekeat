<?php // requirements: dish_of_the_day (dish_controller) ?>
<div id="dish_of_the_day">
	<h3>Dish of the day</h3>
	<ul>
		<?php
			if (isset($app->view_data['dish_of_the_day']) && $app->view_data['dish_of_the_day']['error']) {
				echo '<li>ERROR ' . $app->view_data['dish_of_the_day']['error']['id'] . ': ' . $app->view_data['dish_of_the_day']['error']['message'] . '</li>';
			}
			else
				//var_dump($app->view_data['dish_of_the_day']);
				//$dish = $app->view_data['dish_of_the_day']['dish'];
				foreach ($app->view_data['dish_of_the_day']['dish'] as $dish)
					//var_dump($dish);
					require('_dish_list_item_short.php');
		?>
	</ul>
</div>
