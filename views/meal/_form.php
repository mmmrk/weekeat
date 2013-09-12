					<?php
						if ( isset($form_meal) && $form_meal['error'] )
							echo '<p class="error">' . $form_meal['error']['id'] . '. ' . $form_meal['error']['message'] . '</p>';
						//else if ( isset($form_meal) && !$form_meal['error'] ) {
						//	$ready_dishes = $form_meal['ready_dishes'];
						//	$ready_tags = $form_meal['ready_tags'];
					?>
					<form id="add_meal_form" action="<?= $_SERVER['PHP_SELF'] . '?section=meal&page=create&date=' . $app->display_date; ?>" method="post">
						<input type="hidden" name="add_method" value="0" />
						<input type="hidden" name="dish[id]" value="0" />

						<div id="add_meal_step_2" class="tab_content">
							<div class="content_part content_part_1">
								<h2>2. Add from list</h2>

								<?php
									require('views/dish/_list.php');
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
								<label for="meal[name]">Meal name</label>
								<input name="meal[name]" type="text" value="dinner">
							</div>

							<div class="input_group">
								<label for="meal[shopping_list]">Shopping list</label>
								<textarea id="meal[shopping_list]" cols="75" rows="2" placeholder="Eg: potatoes, olive oil, salt"></textarea>
							</div>
						</div>
					</form>
					<?php //} ?>