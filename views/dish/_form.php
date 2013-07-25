					<?php
						/*
						if ( isset($form_dish) && $form_dish['error'] )
							echo '<p class="error">' . $form_dish['error']['id'] . '. ' . $form_dish['error']['message'] . '</p>';
						else if ( isset($form_dish) && !$form_dish['error'] ) {
							$labels = $form_dish['labels'];
						*/
						$form_dish_heading = (isset($form_dish_heading)) ? $form_dish_heading : 'New dish';
					?>
					<form id="new_dish" action="<?= $_SERVER['PHP_SELF'] . '?section=dish&view=create'; ?>" method="post">
						<h2><?php echo($form_dish_heading); ?></h2>
						<input type="hidden" name="new_meal" id="new_meal" value="1" />

						<div class="form_col">
							<div class="input_group required">
								<label for="new_dish_name">Dish name</label>
								<input type="text" name="name" id="new_dish_name">
							</div>

							<div class="input_group required">
								<label for="new_dish_description">Description</label>
								<textarea type="date" name="description" id="new_dish_description"cols="75" rows="3"></textarea>
							</div>

							<div class="input_group">
								<label for="new_dish_recipe">Recipe</label>
								<textarea type="date" name="description" id="new_dish_recipe" cols="75" rows="10"></textarea>
							</div>
						</div>

						<div class="form_col">
							<div class="input_group">
								<label for="new_dish_url">URL</label>
								<input type="text" name="name" id="new_dish_url">
							</div>

							<div class="input_group">
								<label for="label_id">Tag</label>
								<?php require('views/tag/_ready_tags.php'); ?>
							</div>
						</div>

						<div class="form_footer">
							<button type="button" class="button confirm">Use dish</button>
						</div>
					</form>
				<?php #} ?>
