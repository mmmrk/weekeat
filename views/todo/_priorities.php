<select name="priority_id" id="priority_id">
			<?php foreach ($priorities as $priority) { ?>
				<option value="<?= $priority['id']; ?>"><?= $priority['name']; ?></option>
			<?php } ?>
			</select>