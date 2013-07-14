			<select name="tag[]" id="tag[]" multiple="multiple">
			<?php			
				foreach ($tags as $tag) {
			?>
				<option value="<?= $tag['id']; ?>"><?= $tag['name']; ?></option>
			<?php 
				}
			?>
			</select>