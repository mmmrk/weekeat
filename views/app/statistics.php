<div id="statistics_tab" class="tab">
					<?php
						//var_dump($app->view_data);
						$meal_statistics = $app->view_data['statistics']['meal_statistics'];
						require_once('views/meal/_statistics.php');
						$dish_statistics = $app->view_data['statistics']['dish_statistics'];
						require_once('views/dish/_statistics.php');
						$tag_statistics = $app->view_data['statistics']['tag_statistics'];
						require_once('views/tag/_statistics.php');
					?>
					<div style="clear: both"></div>
				</div>