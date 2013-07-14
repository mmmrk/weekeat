<div id="manage">
				<nav id="manage_nav">
					<a <?= ($app_data['manage']['view'] == 'entry' || $app_data['manage']['view'] == 'new') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=entry'; ?>">Entry</a>
					&nbsp;
					<a <?= ($app_data['manage']['view'] == 'statistics') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=statistics'; ?>">Statistics</a>
					&nbsp;
					<a <?= ($app_data['manage']['view'] == 'admin') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=admin'; ?>">Admin</a>
					&nbsp;
					<a <?= ($app_data['manage']['view'] == 'todo') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=todo'; ?>">Todo</a>
				</nav>
				<?php require_once('/views/app/tab_' . $app_data['manage']['view'] . '.php'); ?>
		
			</div>