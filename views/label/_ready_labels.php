<select name="label_id" id="label_id">
				<option value="random">Random</option>
			<?php foreach ($ready_labels as $ready_label) { ?>
				<option value="<?= $ready_label['id']; ?>"><?= $ready_label['name']; ?></option>
			<?php } ?>
			</select>