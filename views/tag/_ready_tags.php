<select name="tag_id" id="tag_id">
				<option value="random">Random</option>
			<?php foreach ($ready_tags as $ready_tag) { ?>
				<option value="<?= $ready_tag['id']; ?>"><?= $ready_tag['name']; ?></option>
			<?php } ?>
			</select>