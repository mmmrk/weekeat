<select name="tag_id" id="tag_id">
				<option value="random">Random</option>
			<?php foreach ($tags as $tag) { ?>
				<option value="<?= $tag['id']; ?>"><?= $tag['name']; ?></option>
			<?php } ?>
			</select>