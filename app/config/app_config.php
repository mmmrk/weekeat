<?php
	final class AppConfig {
		
		public static $sitemap = array (
			'meal' => array (
				'calendar_week' => array(),
				'calendar_month'=> array(),
				'add' 			=> array(
					'create'
				),
				'show' 			=> array(),
				'list_view' 	=> array()
			),
			'dish' => array (
				'list_view' 	=> array(),
				'add' 			=> array(),
				'create' 		=> array(),
				'show' 			=> array()
			),
			'app' => array (
				'admin' 		=> array(),
				'statistics' 	=> array()
			),
			'todo' => array (
				'form' 			=> array(),
				'create' 		=> array(),
				'list_view' 	=> array()
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