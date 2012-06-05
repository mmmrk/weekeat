<?php
	if (isset($eatage_statistics) && $eatage_statistics['error'])
		echo '<p class="error">' . $eatage_statistics['error']['id'] . '. ' . $eatage_statistics['error']['message'] . '</p>';
	else if (isset($eatage_statistics) && !$eatage_statistics['error']) {
?>
<div id="eatage_statistics">
						<p>Total number of eatages: <?= $eatage_statistics['num_eatages']; ?></p>
					</div>
<?php } ?>