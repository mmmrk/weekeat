<?php // requirements: $dish_list_latest (dish_controller) ?>
<ul id="latest_dishes">
	<?php foreach ($dish_list_latest['dishes'] as $dish) { ?>
		<li>
			<h4><?= $dish['name']; ?></h4>
			<time datetime="<?= $dish['created_at']; ?>" pubdate><?= $dish['created_at']; ?></time>
			<p><?= $dish['description']; ?></p>
		</li>
	<?php }	?>
</ul>