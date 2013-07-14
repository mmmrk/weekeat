<?php
	if (isset($tag_statistics) && $tag_statistics['error'])
		echo '<p class="error">' . $tag_statistics['error']['id'] . '. ' . $tag_statistics['error']['message'] . '</p>';
	else if (isset($tag_statistics) && !$tag_statistics['error']) {
?>
<div id="tag_statistics">
						<p>Total number of tags: <?= count($tag_statistics['tags']); ?></p>
						<table id="statistics_by_tag">
							<caption>Tags</caption>
							<thead>
								<tr>
									<th>Tag</th>
									<th>Times Used</th>
									<th>Times Eaten</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($tag_statistics['tags'] as $tag) { ?>
	<tr class="<?= $tag['row_class']; ?>">
									<td><?= $tag['name']; ?></td>
									<td><?= $tag['times_used']; ?></td>
									<td><?= $tag['times_eaten']; ?></td>
								</tr>
							<?php } ?>
</tbody>
						</table>
					</div>
<?php } ?>