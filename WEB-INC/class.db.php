<?php
class db {
    public function __construct() {
        $this->connect();
    }
    
    public function __destruct() {
        $this->close();
    }


    private function connect() {
		$dbc = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect to MySQL: ' . mysql_error() );
		mysql_query("SET NAMES 'utf8'") or die("Error: Could not SET NAMES" . mysql_error());
		mysql_select_db(MYSQL_BASE) or die ('Could not select the database: ' . mysql_error() );
	}
        private function close() {
		mysql_close();
	}
};
