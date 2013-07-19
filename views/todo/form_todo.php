					<?php
						if ( isset($form_todo) && $form_todo['error'] )
							echo '<p class="error">' . $form_todo['error']['id'] . '. ' . $form_todo['error']['message'] . '</p>';
						else if ( isset($form_todo) && !$form_todo['error'] ) {
							$priorities = $form_todo['priorities'];
							$statuses	= $form_todo['statuses'];
					?>
					<form id="form_todo" action="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=new'; ?>" method="post">
						<span>New Todo</span>
						<br />
						<input type="hidden" name="new_todo" id="new_todo" value="1" />
						<label for="item">Item</label>
						<input type="text" name="item" id="item" />
						<br />
						<label for="priority">Priority</label>
						<?php  /*changed root*/ require('views/todo/_priorities.php'); ?>
						<br />
						<label for="status">Status</label>
						<?php  /*changed root*/ require('views/todo/_statuses.php'); ?>
						<br />
						<input type="submit" value="Submit" />
					</form>
					<?php } else echo 'wtf?'; ?>