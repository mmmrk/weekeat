<?php
	$dish_list = $app->view_data['dish_list'];

	if (isset($dish_list) && $dish_list['error'])
		echo '<p class="error">' . $dish_list['error']['id'] . '. ' . $dish_list['error']['message'] . '</p>';
	else if (isset($dish_list) && !$dish_list['error']) {
?>
<table id="dish_list">
	<thead>
		<tr>
			<th></th>
			<th>Dish</th>
			<th>URL</th>
			<th>Tags</th>
			<th>Add Date</th>
			<th>Last Eaten</th>
			<th>Eat count</th>
			<th>Last DOTD</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($dish_list['dishes'] as $dish) {
			// var_dump($dish);
		?>
		<tr <?php if ($dish['last_dotd_date'] == $app->current_date) echo 'class="dotd"'; ?> data-dish-id="<?= $dish['id']; ?>">
			<td>
				<?php if ($dish['last_dotd_date'] == $app->current_date) echo '<i class="icon icon-asterisk"></i>'; ?>
			</td>
			<td>
				<?= $dish['name']; ?>
				<input class="hidden_description" type="hidden" value="<?= $dish['description']; ?>">
			</td>
			<td><?php if ($dish['url']) echo '<a href="' . $dish['url'] . '" class="icon icon-info"></a>' ?></td>
			<td class="tags">
				<?php
					$t = 0;
					foreach ($dish['tags'] as $tag) {
						echo ($t > 0) ? ', ' . $tag : $tag;
						$t++;
					}
				?>
			</td>
			<td><?= $dish['created_at']; ?></td>
			<td><?= $dish['last_meal_date']; ?></td>
			<td><?= $dish['times_eaten']; ?></td>
			<td><?= $dish['last_dotd_date']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>
