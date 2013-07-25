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

<div id="add_meal_input" class="tab_container">
	<div class="tab_container_header">
		<h1>Add meal</h1>
		<nav class="tab_list">
			<ol>
				<li class="tab active"><a href="#add_meal_step_1">1. Select method</a></li>
				<li class="tab"><a href="#add_meal_step_2">2. Select dish</a></li>
				<li class="tab"><a href="#add_meal_step_3">3. Add meal details</a></li>
			</ol>
		</nav>
	</div>

	<div class="tab_container_body">
		<div id="add_meal_step_1" class="tab_content active">
			<h2>1. Select method</h2>

			<a href="#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.1">Add from list</a>
			<a href="#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.2">Add from form</a>
		</div>

		<div id="add_meal_step_2" class="tab_content">
			<div class="content_part content_part_1">
				<h2>2. Add from list</h2>

				<?php
					require('views/dish/dish_list.php');
				?>
			</div>

			<div class="content_part content_part_2">
				<?php
					$form_dish_heading = '2. Add from form';
					require('views/dish/form_dish.php');
				?>
			</div>

			<div class="no_part_chosen">
				<p>
					Go to <a class="tab_nav_link" href="#add_meal_step_1">step 1</a> to select input method
				</p>
			</div>
		</div>

		<div id="add_meal_step_3" class="tab_content">
			<h2>3. Meal details</h2>

			<div class="input_group">
				<label for="meal_details_shopping_list">Shopping list</label>
				<textarea id="meal_details_shopping_list" cols="75" rows="2" placeholder="Eg: potatoes, olive oil, salt"></textarea>
			</div>
		</div>
	</div>
</div>
