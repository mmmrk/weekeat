<ul class="tag_list">
	<?php foreach ($tags as $tag) { ?>
		<li>
			<input type="checkbox" name="dish[tags][]" value="<?= $tag['id'];?>" />
			<label><?= $tag['name']?></label>
		</li>
	<?php } ?>
</ul>
