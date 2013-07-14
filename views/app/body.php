<div id="app_body">
			<?php
				if (!isset($app_data['controller'], $app_data['action']) && isset($app_data['page']))
					echo $app_data['controller'];
				//else if (file_exists('/views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php'))
					
					//echo $app_data['controller'] . "/" . $app_data['page'];
					require('/views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php');
				//else echo '/views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php';
				/*else {
					echo '<p>error: ' . $app_data['controller'] . '/' . $app_data['action'] . '</p>';
					echo '<p>/views/' . $app_data['controller'] . '/' . $app_data['action'] . '.php</p>';
				}*/
			?>
		</div>