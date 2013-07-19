<div id="new" class="tab">
					<?php
						if ( isset($new_meal) )
							/*changed root*/ require_once('views/meal/new_meal.php');
						else if ( isset($new_label) )
							/*changed root*/ require_once('views/tag/new_tag.php');
						else if ( isset($new_dish) )
							/*changed root*/ require_once('views/dish/new_dish.php');
						else if ( isset($new_todo) )
							/*changed root*/ require_once('views/todo/new_todo.php');
					?>
				</div>