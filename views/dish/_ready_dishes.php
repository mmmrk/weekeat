<select name="dish_id" id="dish_id">
				<option value="random">Random</option>
			<?php foreach ($ready_dishes as $ready_dish) { ?>
				<option value="<?= $ready_dish['id']; ?>"><?= $ready_dish['name']; ?></option>
			<?php } ?>
			</select>