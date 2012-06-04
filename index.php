<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta name="author" content="lillkillen" />
		<title>weekeat</title>
		<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" title="style" href="stylesheet.css" />
	</head>
	<body>
		<?php
			require_once('/tools_helpers/helpers.php');
			require_once('controllers.php');
			require_once('/views/application/header.php');
		?>

		<div id="week">
			<?php require_once('/views/eatage/eatage_week.php'); ?>

		</div>
		<div id="calendar">
			<?php require_once('/views/eatage/eatage_calendar.php'); ?>
		</div>
	</body>
</html>