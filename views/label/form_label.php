					<?php
						if ( isset($form_label) && $form_label['error'] )
							echo '<p class="error">' . $form_label['error']['id'] . '. ' . $form_label['error']['message'] . '</p>';
						else if ( isset($form_label) && !$form_label['error'] ) {
					?>
					<form id="form_label" action="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=new'; ?>" method="post">
						<span>New Label</span>
						<br />
						<input type="hidden" name="new_label" id="new_label" value="1" />
						<label for="name">Name</label>
						<input type="text" name="name" id="name" />
						<br />
						<input type="submit" value="Submit" />
					</form>
					<?php } ?>