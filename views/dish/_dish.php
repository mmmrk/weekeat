<tr>
						<th>Name:</th>
						<td><?= $dish['name']; ?></td>
					</tr>
					<tr>
						<th>URL:</th>
						<td><?= $dish['url']; ?></td>
					</tr>
					<?php if ($dish['num_tags'] > 0) { ?>
<th rowspan="<?= $dish['num_tags']; ?>">Tags:</th>
						<td><?= $dish['tags'][0]['name']; ?></td>
					</tr>
					<?php
							for ($i=1; $i<$dish['num_tags']; $i++)
								echo '<tr><td>' . $dish['tags'][$i]['name'] . "</td></tr>\n\t\t\t\t";
						}
						else {
					?>
<tr>
						<th>Tags:</th>
						<td></td>
					</tr>
					<?php } ?>
<tr>
						<th>Recipe:</th>
						<td><?= $dish['recipe']; ?></td>
					</tr>