<?php
	// extended class for convenience
	class dbh extends mysqli {

		function __construct ($server, $username, $password) {
			parent::__construct($server, $username, $password);

			if (mysqli_connect_error())
				die ('ERROR #' . mysqli_connect_errno() . ': ' . mysqli_connect_error());

			$this->set_charset('utf8');
		}

		function use_db ($database, $check=false) {
			if ( !$check || ($check && $this->db_exists($database)) )
				return $this->select_db($database);
			
			return false;
		}

		function entry_exists ($table, $entry_id) {
			$result = $this->query("SELECT COUNT(*) AS `exists` FROM $table WHERE `id` = $entry_id");
			$row = $result->fetch_object();
			
			$exists = (bool)$row->exists;
			$result->free();

			return $exists;
		}

		function string_entry_exists($table, $field, $string) {
			$result = $this->query("SELECT COUNT(*) AS `exists` FROM $table WHERE `$field` = `$string`");
			$row = $result->fetch_object();

			$exists = (bool)$row->exists;
			$result->free();

			return $exists;
		}

		function db_exists ($database) {
			$result = $this->query("SELECT COUNT(*) AS `exists` FROM `INFORMATION_SCHEMA`.`SCHEMATA` WHERE `SCHEMATA`.`SCHEMA_NAME`=`$database`");
			$row = $result->fetch_object();

			$exists = (bool)$row->exists;
			$result->free();

			return $exists;
		}

		function iquery ($query) {
			$this->query($query);
			return $this->insert_id;
		}

		function safe_input_string ($string) {
			$safe_string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;

			return $this->real_escape_string($safe_string);
		}

		function safe_input_string_array ($array) {
			$safe_array = array();

			foreach ($array as $key => $value)
				if (is_array($value))
					$safe_array[$key] = $this->safe_input_string_array($value);
				else
					$safe_array[$key] = $this->safe_input_string($value);

			return $safe_array;
		}

		function safe_output_string ($string) {
			return htmlspecialchars($string);
		}

		function safe_output_string_array ($array) {
			$safe_array = array();

			foreach ($array as $key => $value)
				if (is_array($value))
					$safe_array[$key] = $this->safe_output_string_array($value);
				else
					$safe_array[$key] = $this->safe_output_string($value);

			return $safe_array;
		}
	}
?>