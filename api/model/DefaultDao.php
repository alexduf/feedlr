<?php

class DefaultDao {
	
	private static $dbh = null;
	
	public function __construct() {
		if (!isset(DefaultDao::$dbh)) {
			DefaultDao::$dbh = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_SCHEMA, DB_USER, DB_PASSWORD);
		}
	}
	
	protected function getDbh() {
		return DefaultDao::$dbh;
	}
}

?>