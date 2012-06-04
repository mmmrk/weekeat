<?php
	if (isset($label_statistics) && $label_statistics['error'])
		echo '<p class="error">' . $label_statistics['error']['id'] . '. ' . $label_statistics['error']['message'] . '</p>';
	else if (isset($label_statistics) && !$label_statistics['error']) {
?>
<div id="label_statistics">
						<p>Total number of labels: <?= count($label_statistics['labels']); ?></p>
						<table id="statistics_by_label">
							<caption>Labels</caption>
							<thead>
								<tr>
									<th>Label</th>
									<th>Times Used</th>
									<th>Times Eaten</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($label_statistics['labels'] as $label) { ?>
	<tr class="<?= $label['row_class']; ?>">
									<td><?= $label['name']; ?></td>
									<td><?= $label['times_used']; ?></td>
									<td><?= $label['times_eaten']; ?></td>
								</tr>
							<?php } ?>
</tbody>
						</table>
					</div>
<?php } ?>