<div id="app_body">
			<?php
				require('views/' . $app->route['section'] . '/' . $app->route['page'] . '.php');

				//  OLDER REQUIRE
				//	require('views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php');
				
				/* FIX ERROR MESSAGING
					echo '<p>error: ' . $app_data['controller'] . '/' . $app_data['action'] . '</p>';
					echo '<p>/views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php</p>';
				}*/
			?>
		</div>