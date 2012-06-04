			<select name="label[]" id="label[]" multiple="multiple">
			<?php			
				foreach ($labels as $label) {
			?>
				<option value="<?= $label['id']; ?>"><?= $label['name']; ?></option>
			<?php 
				}
			?>
			</select>