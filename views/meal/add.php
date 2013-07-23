<?php if (isset($_GET['date'])) { ?>
	<div id="add_meal_day_overview" class="week_calendar_item">
		<?php
			# move variables to controller

			$day = array(
				'date' => array(
					'weekday' => 'Wednesday',
					'string' => $_GET['date'],
					'day_short' => array_pop(explode('-', $_GET['date']))
				)
			);

			$meal = false;

			require('_weekday.php');
		?>
	</div>
<?php } ?>

<div id="add_meal_input">
	<div id="add_meal_input_header">
		<h1>Add meal</h1>
		<nav id="add_meal_input_navigation">
			<ol>
				<li><a href="#add_meal_step_1">1. Select method</a></li>
				<li><a href="#add_meal_step_2">2. Select dish</a></li>
				<li><a href="#add_meal_step_3">3. Add meal details</a></li>
			</ol>
		</nav>
	</div>
	
	<div id="add_meal_step_1" class="add_meal_step">
		<h2>1. Select method</h2>

		<a href="#add_meal_step_2" class="add_meal_method_select" data-method="add_meal_step_2.1">Add from list</a>
		<a href="#add_meal_step_2" class="add_meal_method_select" data-method="add_meal_step_2.2">Add from form</a>
	</div>
	
	<div id="add_meal_step_2" class="add_meal_step">
		<div id="add_meal_no_method">
			<p>
				Go to <a href="#add_meal_step_1">step 1</a> to select input method
			</p>
		</div>

		<div class="step_part step_part_1">
			<h2>2. Add from list</h2>
	
			<?php
				require('views/dish/dish_list.php'); 
			?>
		</div>

		<div class="step_part step_part_2">
			<?php
				$form_dish_heading = '2. Add from form';
				require('views/dish/form_dish.php');
			?>
		</div>
	</div>
	
	<div id="add_meal_step_3" class="add_meal_step">
		<h2>3. Meal details</h2>

		<div class="input_group">
			<label for="meal_details_shopping_list">Shopping list</label>
			<textarea id="meal_details_shopping_list" cols="75" rows="2" placeholder="Eg: potatoes, olive oil, salt"></textarea>
		</div>
	</div>
</div>