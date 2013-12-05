<?php //wrapper for listing/enumerating all dishes ?>

<div id="dish_list_page" class="tab_container fullsize_page">
	<div class="tab_container_header">
		<h1>Dish archive</h1>

		<nav class="tab_navigation">
			<ul class="tab_list">
				<li class="tab active"><a href="#list">View all dishes</a></li>
				<li class="tab"><a href="#form">Add new dish</a></li>
			</ul>
		</nav>
	</div>

	<div class="tab_container_body">
		<div id="list" class="tab_content active">
			<h2>Listing all dishes</h2>
			<?php require('views/dish/_list.php'); ?>
		</div>

		<div id="form" class="tab_content">
			<h2>Add new dish</h2>
			<?php require('views/dish/_form.php'); ?>
		</div>
	</div>
</div>
