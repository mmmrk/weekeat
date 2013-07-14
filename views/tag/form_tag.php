					<?php
						if ( isset($form_tag) && $form_tag['error'] )
							echo '<p class="error">' . $form_tag['error']['id'] . '. ' . $form_tag['error']['message'] . '</p>';
						else if ( isset($form_tag) && !$form_tag['error'] ) {
					?>
					<form id="form_tag" action="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=new'; ?>" method="post">
						<span>New Tag</span>
						<br />
						<input type="hidden" name="new_tag" id="new_tag" value="1" />
						<tag for="name">Name</tag>
						<input type="text" name="name" id="name" />
						<br />
						<input type="submit" value="Submit" />
					</form>
					<?php } ?>