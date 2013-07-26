<?php if (isset($app->site_params['GET']['date'], $app->display_date)) { ?>
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

		<nav class="tab_navigation disabled">
			<ol class="tab_list">
				<li class="tab active"><a href="#add_meal_step_1">1. Select method</a></li>
				<li class="tab"><a href="#add_meal_step_2">2. Select dish</a></li>
				<li class="tab"><a href="#add_meal_step_3">3. Add meal details</a></li>
			</ol>
		</nav>
	</div>

	<div class="tab_container_body">
		<form id="add_meal_form" action="<?= $_SERVER['PHP_SELF'] . '?section=meal&page=add&date=2013-07-22'; ?>" method="post">
			<div id="add_meal_step_1" class="tab_content active">
				<h2>1. Select method</h2>

				<p>
					<a href="<?= (false) ? $app->get_current_url(true, true, false) . '&action=from_dish&date=' . $app->display_date : ''; ?>#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.1">
						<label for="add_meal_method_false">Add from list</label>
						<input name="add_meal_method_new" id="add_meal_method_false" type="radio" value="false">
					</a>

					<a href="#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.2">
						<label for="add_meal_method_true">Add from form</label>
						<input name="add_meal_method_new" id="add_meal_method_true" type="radio" value="true">
					</a>
				</p>
			</div>

			<div id="add_meal_step_2" class="tab_content">
				<div class="content_part content_part_1">
					<h2>2. Add from list</h2>

					<?php
						require('views/dish/list_view.php');
					?>
				</div>

				<div class="content_part content_part_2">
					<h2>2. Add from form</h2>

					<?php
						require('views/dish/_form.php');
					?>
				</div>

				<div class="no_part_chosen">
					<h2>2. <i>No input method chosen</i></h2>

					<p>
						Go to <a class="tab_nav_link" href="#add_meal_step_1">step 1</a> to select input method
					</p>
				</div>
			</div>

			<div id="add_meal_step_3" class="tab_content">
				<h2>3. Meal details</h2>

				<div class="input_group required">
					<label for="meal_name">Meal name</label>
					<input name="meal_name" type="text" value="dinner">
				</div>

				<div class="input_group">
					<label for="meal_shopping_list">Shopping list</label>
					<textarea id="meal_shopping_list" cols="75" rows="2" placeholder="Eg: potatoes, olive oil, salt"></textarea>
				</div>
			</div>
		</form>
	</div>

	<div class="tab_container_footer">
		<button type="button" class="button prev action" disabled><i class="icon icon-left-dir"></i> Back</button>
		<button type="button" class="button next action">Next <i class="icon icon-right-dir"></i></button>
		<button type="submit" class="button confirm" form="add_meal_form" disabled><i class="icon icon-ok"></i> Add</button>
	</div>
</div>
