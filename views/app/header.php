<header id="app_header">
			<h1 id="app_logo"><a href="<?= $_SERVER['PHP_SELF']; ?>">weekeat</a></h1>
			<nav id="app_menu">
				<ul>
					<li class="current"><a href="<?= $_SERVER['PHP_SELF'] . '?view=week'; ?>">This Week</a></li>
					<!-- FOR LATER <li><a href="<?= $_SERVER['PHP_SELF'] . '?view=calendar'; ?>">Calendar</a></li> -->
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?view=statistics'; ?>">Statistics</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?view=admin'; ?>">Admin</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?view=todo'; ?>">Todo</a></li>
				</ul>
			</nav>
		</header>