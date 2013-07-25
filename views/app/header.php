<header id="app_header">
			<h1 id="app_logo"><a href="<?= $_SERVER['PHP_SELF']; ?>">weekeat</a></h1>
			<nav id="app_menu">
				<ul>
					<li class="current"><a href="<?= $_SERVER['PHP_SELF'] . '?section=meal&action=calendar_week'; ?>">This Week</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?section=dish&page=list'; ?>">Dish Archive</a></li>
					<!-- FOR LATER <li><a href="<?= $_SERVER['PHP_SELF'] . '?section=meal&action=calendar_month'; ?>">Calendar</a></li> -->
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?section=app&page=statistics'; ?>">Statistics</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?section=app&page=admin'; ?>">Admin</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] . '?section=todo'; ?>">Todo</a></li>
				</ul>
			</nav>
		</header>