<div id="top">
			<header>
				<h1><a href="<?= ($application_data['manage']) ? $_SERVER['PHP_SELF'] . '?manage=hide' : $_SERVER['PHP_SELF'] . '?manage=show' ?>">Fem vattnade fålar</a></h1>
			</header>
			<?php
				if (isset($application_data['manage']['view']))
					require_once('/views/application/_manage.php');
			?>

			<div style="clear: both"></div>
		</div>