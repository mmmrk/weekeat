<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		<meta name="author" content="lillkillen" />

		<title>weekeat</title>
		<link rel="stylesheet" type="text/css" title="styles" href="css/styles.css" />
	</head>
	<body>
		<?php
			require_once('controllers.php');
			require_once('tools_helpers/helpers.php');
		?>
		<div id="app_container">
			<?php
				require_once('views/app/header.php');
				require_once('views/app/body.php');
			?>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/main.js"></script>
	</body>
</html>