<?php
	function is_date ($date_string) {
		if ( preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date_string, $date_array) )
			return checkdate($date_array[2] , $date_array[3] , $date_array[1]);

		return false;
	}

	function make_sql_date ($date_string) {
		if (is_date($date_string))
			return "'" . $date_string . "'";

		$date = false;

		switch ( strtolower($date_string) ) {
			case 'today':
			case 'idag':
			case 'now':
				$date = 'CURDATE()';
			break;

			case 'yesterday':
			case 'igår':
			case 'today-1':
				$date = 'CURDATE()- INTERVAL 1 DAY';
			break;

			case 'tomorrow':
			case 'imorrn':
			case 'imorgon':
			case 'today+1':
				$date  = 'CURDATE()+ INTERVAL 1 DAY';
			break;
		}

		return $date;
	}
?>