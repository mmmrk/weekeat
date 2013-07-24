<?php
	final class AppConfig {
		
		public static $sitemap = array (
			'meals' => array (
				'calendar_week',
				'calendar_month',
				'add',
				'create',
				'show',
				'list'
			),
			'admin' => array (
				'form'
			),
			'statistics' => array (
				'show'
			),
			'todo' => array (
				'form',
				'create',
				'list'
			)
		);

		public static $default_section = 'meals';

		public static $default_action = array (
			'meals' 		=> 'calendar_week',
			'admin' 		=> 'form',
			'statistics' 	=> 'show',
			'todo' 			=> 'list'
		);
	}
?>