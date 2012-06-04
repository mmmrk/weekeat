<tr>
						<th>Name:</th>
						<td><?= $dish['name']; ?></td>
					</tr>
					<tr>
						<th>URL:</th>
						<td><?= $dish['url']; ?></td>
					</tr>
					<?php if ($dish['num_labels'] > 0) { ?>
<th rowspan="<?= $dish['num_labels']; ?>">Labels:</th>
						<td><?= $dish['labels'][0]['name']; ?></td>
					</tr>
					<?php
							for ($i=1; $i<$dish['num_labels']; $i++)
								echo '<tr><td>' . $dish['labels'][$i]['name'] . "</td></tr>\n\t\t\t\t";
						}
						else {
					?>
<tr>
						<th>Labels:</th>
						<td></td>
					</tr>
					<?php } ?>
<tr>
						<th>Recipe:</th>
						<td><?= $dish['recipe']; ?></td>
					</tr>