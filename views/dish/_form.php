					<?php
						$dish_form = $app->view_data['dish_form'];
						if ( isset($dish_form) && $dish_form['error'] )
							echo '<p class="error">' . $dish_form['error']['id'] . '. ' . $dish_form['error']['message'] . '</p>';
						else if ( isset($dish_form) && !$dish_form['error'] ) {
							$tags = $dish_form['tags'];
					?>
					<div class="form_col">
						<div class="input_group required">
							<label for="dish[name]">Dish name</label>
							<input type="text" name="dish[name]">
						</div>

						<div class="input_group required">
							<label for="dish[description]">Description</label>
							<textarea type="date" name="dish[description]" cols="75" rows="3"></textarea>
						</div>

						<div class="input_group">
							<label for="dish[recipe]">Recipe</label>
							<textarea type="date" name="dish[recipe]" cols="75" rows="10"></textarea>
						</div>
					</div>

					<div class="form_col">
						<div class="input_group">
							<label for="dish[url]">URL</label>
							<input type="text" name="dish[url]">
						</div>

						<div class="input_group">
							<label>Tags</label>
							<?php require('views/tag/_ready_tags.php'); ?>
						</div>
					</div>
				<?php } ?>
