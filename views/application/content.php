<div id="content">
			<?php
				if (!isset($application_data['controller'], $application_data['action']) && isset($application_data['page']))
					echo $application_data['page'];
				//else if (file_exists('/views/' . $application_data['controller'] . '/' . $application_data['action'] . '.php'))
					require('/views/' . $application_data['controller'] . '/' . $application_data['action'] . '.php');
				/*else {
					echo '<p>error: ' . $application_data['controller'] . '/' . $application_data['action'] . '</p>';
					echo '<p>/views/' . $application_data['controller'] . '/' . $application_data['action'] . '.php</p>';
				}*/
			?>
		</div>