					<?php
						if ( isset($form_eatage) && $form_eatage['error'] )
							echo '<p class="error">' . $form_eatage['error']['id'] . '. ' . $form_eatage['error']['message'] . '</p>';
						else if ( isset($form_eatage) && !$form_eatage['error'] ) {
							$ready_dishes = $form_eatage['ready_dishes'];
							$ready_labels = $form_eatage['ready_labels'];
					?>
					<form id="form_eatage" action="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=new'; ?>" method="post">
						<span>New Eatage</span>
						<br />
						<input type="hidden" name="new_eatage" id="new_eatage" value="1" />
						<label for="date">Date</label>
						<input type="date" name="date" id="date" value="today" />
						<br />
						<label for="dish_id">Dish</label>
						<?php require('/views/dish/_ready_dishes.php'); ?>
						<br />
						<label for="label_id">Label</label>
						<?php require('/views/label/_ready_labels.php'); ?>
						<br />
						<input type="submit" value="Submit" />
					</form>
					<?php } ?>