<div id="new" class="tab">
					<?php
						if ( isset($new_eatage) )
							require_once('/views/eatage/new_eatage.php');
						else if ( isset($new_label) )
							require_once('/views/label/new_label.php');
						else if ( isset($new_dish) )
							require_once('/views/dish/new_dish.php');
						else if ( isset($new_todo) )
							require_once('/views/todo/new_todo.php');
					?>
				</div>