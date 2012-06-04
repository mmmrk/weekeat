<div id="manage">
				<nav id="manage_nav">
					<a <?= ($application_data['manage']['view'] == 'entry' || $application_data['manage']['view'] == 'new') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=entry'; ?>">Entry</a>
					&nbsp;
					<a <?= ($application_data['manage']['view'] == 'statistics') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=statistics'; ?>">Statistics</a>
					&nbsp;
					<a <?= ($application_data['manage']['view'] == 'admin') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=admin'; ?>">Admin</a>
					&nbsp;
					<a <?= ($application_data['manage']['view'] == 'todo') ? ' class="active"' : ''; ?> href="<?= $_SERVER['PHP_SELF'] . '?manage=show&view=todo'; ?>">Todo</a>
				</nav>
				<?php require_once('/views/application/tab_' . $application_data['manage']['view'] . '.php'); ?>
		
			</div>