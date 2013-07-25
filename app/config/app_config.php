<?php
	final class AppConfig {
		
		public static $sitemap = array (
			'meal' => array (
				'calendar_week',
				'calendar_month',
				'add',
				'create',
				'show',
				'list_view'
			),
			'dish' => array (
				'list_view',
				'add',
				'create',
				'show',
			),
			'app' => array (
				'admin',
				'statistics'
			),
			'todo' => array (
				'form',
				'create',
				'list_view'
			)
		);

		public static $default_section = 'meal';

		public static $default_page = array (
			'meal' 			=> 'calendar_week',
			'dish'			=> 'list_view',
			'app' 			=> 'admin',
			'todo' 			=> 'list_view'
		);
	}
?>