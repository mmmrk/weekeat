					<?php
						if ( isset($form_dish) && $form_dish['error'] )
							echo '<p class="error">' . $form_dish['error']['id'] . '. ' . $form_dish['error']['message'] . '</p>';
						else if ( isset($form_dish) && !$form_dish['error'] ) {
							$labels = $form_dish['labels'];
					?>
					<form id="new_dish" action="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=new'; ?>" method="post">
						<span>New Dish</span>
						<br />
						<input type="hidden" name="new_dish" value="1" />
						<label for="name">Name</label>
						<input type="text" name="name" id="name" />
						<br />
						<label for="url">URL</label>
						<input type="text" name="url" id="url" />
						<br />
						<label for="tag[]">Tag</label>
						<?php require_once('/views/application/_tags.php'); ?>
						<br />
						<label for="recipe">Recipe</label>
						<br />
						<textarea name="recipe" id="recipe"></textarea>
						<br />
						<input type="submit" value="Submit" />
					</form>
				<?php } ?>