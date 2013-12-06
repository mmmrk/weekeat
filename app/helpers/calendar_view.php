<?php
	class CalendarView {
		private $today 		= array();
		private $active_day	= array();
		private $month 		= array(
			'num_days'	=> null,
			'string'	=> null
		);

		private $week_matrix = array();

		static function date_in_num_days ($current_timestamp, $interval) {
			return strtotime(date('Y-m-d', $current_timestamp) . " +$interval day");
		}

		static function first_day_of_week ($timestamp) {
			return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp) - date('N', $timestamp)+1, date('Y', $timestamp));
		}

		static function last_day_of_week ($timestamp) {
			return self::date_in_num_days(self::first_day_of_week($timestamp), 7);
		}

		static function first_day_of_month ($timestamp) {
			return mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp));
		}

		static function last_day_of_month ($timestamp) {
			return mktime(0, 0, 0, date('m', $timestamp)+1, 0, date('Y', $timestamp));
		}

		static function week_number ($timestamp) {
			return (int)date('W', $timestamp);
		}

		static function next_week_number ($timestamp) {
			return self::week_number(self::date_in_num_days($timestamp, 7));
		}

		static function last_week_of_year ($timestamp) {
			$last_day = self::current_year($timestamp) . '-12-31';

			return self::week_number(strtotime($last_day));
		}

		static function num_weeks_between_weeks ($start_week, $end_week) {
			$last_week = self::last_week_of_year($start_week);

			return ($start_week < $end_week) ? ($end_week-$start_week) : (($end_week+$last_week)-$start_week)-1;
		}

		static function num_weeks_between_dates ($start_date, $end_date) {
			return self::num_weeks_between_weeks(self::week_number($start_date), self::week_number($end_date));
		}

		static function current_year ($timestamp) {
			return date('Y', $timestamp);
		}

		static function date_info ($timestamp) {
			$data = array(
				'timestamp' => $timestamp
			);

			$data['date']['string']  		= date('Y-m-d', $timestamp);
			$data['date']['weekday'] 		= date('l', $timestamp);
			$data['date']['weekday_short']	= date('D', $timestamp);
			$data['date']['day'] 	 		= date('d', $timestamp);
			$data['date']['day_short'] 	 	= date('j', $timestamp);
			$data['date']['month']	 		= date('m', $timestamp);
			$data['date']['year']	 		= date('Y', $timestamp);
			$data['week']			 		= (int)date('W', $timestamp);

			return $data;
		}

		function __construct ($today) {
			$this->today 				= $today;
			$this->active_day 			= $today;
			$this->month['num_days'] 	= date('t', $today);
			$this->month['string']		= date('F', $today);
			$this->generate();
		}

		function generate () {
			$working_day	= self::first_day_of_week($this->month_first_day());

			$start_week		= self::week_number($working_day);
			$end_week		= self::week_number($this->month_last_day());

			for ($x=0; $x<=self::num_weeks_between_weeks($start_week, $end_week); $x++) {
				$this->week_matrix[$x] = array('week_number' => self::week_number($working_day));

				for ($y=0; $y<7; $y++) {
					$this->week_matrix[$x]['days'][$y] = self::date_info($working_day);
					$working_day = self::date_in_num_days($working_day, 1);
				}
			}
		}

		function get_today () {
			return $this->today;
		}

		function set_today ($timestamp) {
			$this->today = $timestamp;
		}

		function select_day ($timestamp) {
			if ($this->date_in_calendar($timestamp)) {
				$this->working_day = $timestamp;
				return true;
			}
			return false;
		}

		function get_week ($week_number = false) {
			if (!$week_number)
				$week_number = $this->week_number($this->active_day);

			foreach ($this->week_matrix as $week)
				if ($week['week_number'] == $week_number)
					return $week;

			return false;
		}

		function get_all_weeks () {
			return $this->week_matrix;
		}

		function get_month () {
			return $this->month;
		}

		function first_day () {
			return $this->week_matrix[0]['days'][0];
		}

		function last_day () {
			return $this->week_matrix[count($this->week_matrix)-1]['days'][6];
		}

		function today_date ($pattern = 'Y-m-d') {
			return date($pattern, $this->active_day);
		}

		function week_first_day () {
			return self::first_day_of_week($this->active_day);
		}

		function week_last_day () {
			return self::last_day_of_week($this->active_day);
		}

		function month_first_day () {
			return self::first_day_of_month($this->active_day);
		}

		function month_last_day () {
			return self::last_day_of_month($this->active_day);
		}

		function date_in_calendar ($timestamp) {
			$first_day	= $this->first_day();
			$last_day	= $this->last_day();

			return ($timestamp > $first_day['timestamp'] && $timestamp < $last_day['timestamp']);
		}
	}
?>