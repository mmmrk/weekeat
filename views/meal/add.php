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

			$day = CalendarView::date_info(strtotime($app->display_date));

			//var_dump($app->view_data['meals_of_the_day']['meals']);
			$weekday_meals = (array_key_exists($app->display_date, $app->view_data['meals_of_the_day']['meals'])) ? $app->view_data['meals_of_the_day']['meals'][$app->display_date] : false;
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
			<div id="add_meal_step_1" class="tab_content active">
				<h2>1. Select method</h2>

				<p>
					<a href="<?= (false) ? $app->get_current_url(true, true, false) . '&action=from_dish&date=' . $app->display_date : ''; ?>#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.1">
						<label for="add_meal_method_false">Add from list</label>
						<input name="add_meal_method_new" id="add_meal_method_false" type="radio" value="false">
					</a>
				</p>

				<p><i>Or</i></p>

				<p>
					<a href="#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.2">
						<label for="add_meal_method_true">Add from form</label>
						<input name="add_meal_method_new" id="add_meal_method_true" type="radio" value="true">
					</a>
				</p>
			</div>

			<?php require('views/meal/_form.php'); ?>
	</div>

	<div class="tab_container_footer">
		<div class="inner">
			<button type="button" class="button prev action" disabled><i class="icon icon-left-dir"></i> Back</button>
			<button type="button" class="button next action">Next <i class="icon icon-right-dir"></i></button>
			<button type="submit" class="button confirm" form="add_meal_form" disabled><i class="icon icon-ok"></i> Add</button>
		</div>
	</div>
</div>
