<select name="status_id" id="status_id">
			<?php foreach ($statuses as $status) { ?>
				<option value="<?= $status['id']; ?>"><?= $status['state']; ?></option>
			<?php } ?>
			</select>