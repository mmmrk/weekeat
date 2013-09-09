					<?php
						$dish_form = $app->view_data['dish_form'];
						if ( isset($dish_form) && $dish_form['error'] )
							echo '<p class="error">' . $dish_form['error']['id'] . '. ' . $dish_form['error']['message'] . '</p>';
						else if ( isset($dish_form) && !$dish_form['error'] ) {
							$tags = $dish_form['tags'];
					?>
					<input type="hidden" name="new_meal" id="new_meal" value="1" />

					<div class="form_col">
						<div class="input_group required">
							<label for="dish_name">Dish name</label>
							<input type="text" name="dish_name" id="new_dish_name">
						</div>

						<div class="input_group required">
							<label for="dish_description">Description</label>
							<textarea type="date" name="dish_description" id="dish_description"cols="75" rows="3"></textarea>
						</div>

						<div class="input_group">
							<label for="dish_recipe">Recipe</label>
							<textarea type="date" name="dish_recipe" id="dish_recipe" cols="75" rows="10"></textarea>
						</div>
					</div>

					<div class="form_col">
						<div class="input_group">
							<label for="dish_url">URL</label>
							<input type="text" name="dish_url" id="dish_url">
						</div>

						<div class="input_group">
							<label>Tag</label>
							<?php require('views/tag/_ready_tags.php'); ?>
						</div>
					</div>
				<?php } ?>
