					<?php
						if ( isset($form_meal) && $form_meal['error'] )
							echo '<p class="error">' . $form_meal['error']['id'] . '. ' . $form_meal['error']['message'] . '</p>';
						else if ( isset($form_meal) && !$form_meal['error'] ) {
							$ready_dishes = $form_meal['ready_dishes'];
							$ready_tags = $form_meal['ready_tags'];
					?>
					<form id="form_meal" action="<?= $_SERVER['PHP_SELF'] . '?section=meal&page=create'; ?>" method="post">
						<span>New Meal</span>
						<br />
						<input type="hidden" name="new_meal" id="new_meal" value="1" />
						<label for="date">Date</label>
						<input type="date" name="date" id="date" value="today" />
						<br />
						<label for="dish_id">Dish</label>
						<?php require('/views/dish/_ready_dishes.php'); ?>
						<br />
						<label for="label_id">Tag</label>
						<?php require('/views/tag/_ready_tags.php'); ?>
						<br />
						<input type="submit" value="Submit" />
					</form>
					<?php } ?>