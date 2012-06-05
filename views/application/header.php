<div id="menu">
			<header>
				<h1><a href="<?= $_SERVER['PHP_SELF']; ?>">weekeat</a></h1>
			</header>
			<nav>
				<a href="<?= $_SERVER['PHP_SELF'] . '?view=calendar'; ?>">Calendar</a>
				<a href="<?= $_SERVER['PHP_SELF'] . '?view=statistics'; ?>">Statistics</a>
				<a href="<?= $_SERVER['PHP_SELF'] . '?view=admin'; ?>">Admin</a>
				<a href="<?= $_SERVER['PHP_SELF'] . '?view=todo'; ?>">Todo</a>
			</nav>
			<div style="clear: both"></div>
		</div>