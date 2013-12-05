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
			<div id="add_meal_step_1" class="tab_content active full_width">
				<h2>1. Select method</h2>

				<p>
					<a href="<?= (false) ? $app->get_current_url(true, true, false) . '&action=from_dish&date=' . $app->display_date : ''; ?>#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.1">
						<label for="add_meal_method_false">Add from list</label>
						<input name="add_meal_method_new" id="add_meal_method_false" type="radio" value="false">
					</a>
					&nbsp;
					Or
					&nbsp;
					<a href="#add_meal_step_2" class="content_part_toggle tab_nav_link" data-part="2.2">
						<label for="add_meal_method_true">Add from form</label>
						<input name="add_meal_method_new" id="add_meal_method_true" type="radio" value="true">
					</a>
				</p>


				<div class="quick_options">
					<div class="option list_option">
						<h3>Ready to eat</h3>
						<ul class="small_list">
							<li>
								<h4>Ytterligare test</h4>
								<ul class="inline_list">
									<li>tags</li>
									<li>och</li>
									<li>sånt</li>
								</ul>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, iste, molestiae adipisci quisquam dolores tenetur.</p>
							</li>
							<li>
								<h4>Jobbig testmat</h4>
								<ul class="inline_list">
									<li>tags</li>
									<li>skit</li>
									<li>och</li>
									<li>sånt</li>
								</ul>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, non quam culpa architecto quasi doloremque.</p>
							</li>
							<li>
								<h4>Hyséns Ben</h4>
								<ul class="inline_list">
									<li>sånt</li>
									<li>och</li>
									<li>skit</li>
								</ul>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, facilis, non unde hic earum eius!</p>
							</li>
							<li>
								<h4>Skrovmål</h4>
								<ul class="inline_list">
									<li>tags</li>
									<li>och</li>
									<li>skit</li>
								</ul>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam quas placeat modi nihil atque voluptas?</p>
							</li>
							<li>
								<h4>Mindre test</h4>
								<ul class="inline_list">
									<li>skit</li>
								</ul>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus, dolores ratione ea neque reprehenderit ut.</p>
							</li>
						</ul>
					</div>

					<div class="option text_option">
						<h3>Dish of the day</h3>
						<h4>Ytterligare test</h4>
						<ul class="inline_list">
							<li>tags</li>
							<li>och</li>
							<li>sånt</li>
						</ul>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem ipsum eum at deserunt animi provident quia. Minima illum mollitia non?</p>
					</div>

					<div class="option text_option">
						<h3>Random</h3>
						<h4>Ytterligare test</h4>
						<ul class="inline_list">
							<li>tags</li>
							<li>och</li>
							<li>sånt</li>
						</ul>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem ipsum eum at deserunt animi provident quia. Minima illum mollitia non?</p>
					</div>
				</div>
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
