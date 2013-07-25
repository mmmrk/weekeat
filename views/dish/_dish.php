<tr>
						<th>Name:</th>
						<td><?= $dish['name']; ?></td>
					</tr>
					<tr>
						<th>Description:</th>
						<td><?= $dish['description']; ?></td>
					</tr>
					<tr>
						<th>URL:</th>
						<td><?= $dish['url']; ?></td>
					</tr>
					<tr>
						<th>Tags:</th>
						<td>
							<?php 
								for ($i=1; $i<$dish['num_tags']; $i++)
									echo $dish['tags'][$i]['name'] . ' ';
							?>
						</td>
					</tr>
					<tr>
						<th>Recipe:</th>
						<td><?= $dish['recipe']; ?></td>
					</tr>