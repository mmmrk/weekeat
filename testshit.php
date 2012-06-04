<?= '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="application/html+xml; charset=utf-8" />
		<meta name="author" content="lillkillen" />
		<title>weekeat</title>
		<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" title="style" href="style.css" />
	</head>
	<body>
		<?php
		/* OLD SHIET 
			$calendar['today']				= time();
			$calendar['date']['day'] 		= date('d', $calendar['today']);
			$calendar['date']['month'] 		= date('m', $calendar['today']);
			$calendar['date']['year'] 		= date('Y', $calendar['today']);
			$calendar['firstday']['date'] 	= mktime(0, 0, 0, $calendar['date']['month'], 1, $calendar['date']['year']);
			$calendar['firstday']['title']	= date('F', $calendar['firstday']['date']);
			$calendar['day_of_week']		= date('N', $calendar['firstday']['date']);

			$calendar['month']['num_days'] = cal_days_in_month(CAL_GREGORIAN, $calendar['date']['month'], $calendar['date']['year']);

			$calendar['weeks'][0] = array(
				'week_num' => (int)date('W', $calendar['firstday']['date']),
				'week_days' => array()
			);

			$day_counter = 1;
			$week_counter = 1;

			//if there are blanks in the first week
			for (; $day_counter<$calendar['day_of_week']; $day_counter++)
				$calendar['weeks'][0]['week_days'][$day_counter-1] = array();

			for ($day_num=1; $day_num<=$calendar['month']['num_days']; $day_num++, $day_counter++) {
				$calendar['weeks'][$week_counter-1]['week_days'][$day_counter-1] = $day_num;

				if ($day_counter > 6) {
					$calendar['weeks'][$week_counter] = array (
						'week_num' => $calendar['weeks'][$week_counter-1]['week_num']+1,
						'week_days' => array() 
					);

					$day_counter = 0;
					$week_counter++;
				}
			}
		*/ /*
			$today['timestamp']			= time();
			$today['date']['day']		= date('d', $today['timestamp']);
			$today['date']['month'] 	= date('m', $today['timestamp']);
			$today['date']['year'] 		= date('Y', $today['timestamp']);
			$today['date']['string'] 	= date('Y-m-d');
			$today['month_days'] 		= date('t', $today['timestamp']);
			$today['week']				= (int)date('W', $today['timestamp']);

			$firstday['timestamp']		= mktime(0, 0, 0, $today['date']['month'], 1, $today['date']['year']);
			$firstday['date']['day']	= date('d', $firstday['timestamp']);
			$firstday['week']			= (int)date('W', $firstday['timestamp']);

			$lastday['timestamp']		= mktime(0, 0, 0, $today['date']['month']+1, 0, $today['date']['year']);
			$lastday['week']			= (int)date('W', $lastday['timestamp']);

			$startday['timestamp']		= mktime(0, 0, 0, date('n', $firstday['timestamp']), date('j', $firstday['timestamp']) - date('N', $firstday['timestamp'])+1);
			$startday['date']['day']	= date('d', $startday['timestamp']);
			$startday['date']['month'] 	= date('m', $startday['timestamp']);
			$startday['date']['year'] 	= date('Y', $startday['timestamp']);
			$startday['date']['string'] = date('Y-m-d', $startday['timestamp']);
			$startday['month_days'] 	= date('t', $startday['timestamp']);
			$startday['week']			= $firstday['week'];

			function date_in_num_days ($current_timestamp, $interval) {
				return strtotime(date('Y-m-d', $current_timestamp) . " +$interval day");
			}

			$calendar['weeks'] = array();
			$current_day = $startday['timestamp'];

			for ($x=0, $i=$startday['week']; $i<=$lastday['week']; $x++, $i++) {
				$calendar['weeks'][$x] = array('week_number' => $i);

				for ($y=0; $y<7; $y++) {
					$calendar['weeks'][$x]['days'][$y] = array(
						'day_num' 		=> date('d', $current_day),
						'date_string' 	=> date('Y-m-d', $current_day)
					);
					$current_day = date_in_days($current_day, 1);
				}
			}

			foreach ($calendar['weeks'] as $week)
				var_dump($week);
*/
		//	if (false) {
			require_once('/tools_helpers/calendar.php');

			$calendar = new Calendar(time());
		?>
		<div class="table" id="week">
			<?php
				$week = $calendar->get_week();

				foreach ($week['days'] as $day) {
					echo "<div class=\"tr\">\n\t";
					echo "<div class=\"th\">" . $day['date']['weekday'] . "</div>\n\t";
					echo "<div class=\"td\">" . $day['date']['string'] . "</div>\n\t";
					echo "</div>\n\t";
				}
				/*$curwknum = null;
				foreach ($calendar['weeks'] as $key => $week)
					if ($week['week_num'] == (int)date('W', $calendar['timestamp']))
						$curwknum = $key;

				foreach ($calendar['weeks'][$key]['week_days'] as $day) {
					echo "<div class=\"tr\">\n\t";
					echo '';
				}*/
			?>
		</div>
		<div class="table" id="calendar">
			<div class="tr">
				<div class="th">Wk</div>
				<div class="th">Monday</div>
				<div class="th">Tuesday</div>
				<div class="th">Wednesday</div>
				<div class="th">Thursday</div>
				<div class="th">Friday</div>
				<div class="th">Saturday</div>
				<div class="th">Sunday</div>
			</div>
			<?php
				//var_dump($calendar->week_matrix);
				foreach ($calendar->week_matrix as $week) {
					echo "<div class=\"tr\">\n\t";
					echo '<div class="td">' . $week['week_number'] . "</div>\n\t";
					foreach ($week['days'] as $day)
						if ($day == array())
							echo "<div class=\"td\"></div>\n";
						else
							echo '<div class="td">' . $day['date']['day'] . "</div>\n";
					echo "</div>\n";
				}
			?>
		</div>
	</body>
</html>