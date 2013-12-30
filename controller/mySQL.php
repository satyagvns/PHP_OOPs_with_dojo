<?php
	class mySQL {

		/* Global variables are defined at the starting level. 
		   so If you want to change you can change here itself instead of changing in all areas. */

		var $server;
		var $userName;
		var $password;
		var $db;
		var $mySQLConnection;
		var $mySQLSelectDB;

		/* Constructor will call to create a DB connection. You must call when app is loading or else. 
		   your database can not be established. */

		public function __construct() {

			$this->server = 'localhost';
			$this->userName = 'root';
			$this->password = '';
			$this->db = 'test_db';

			$this->mySQLConnection = mysql_connect($this->server, $this->userName, $this->password) or die('Connection Not Established');

			$this->mySQLSelectDB = mysql_select_db($this->db, $this->mySQLConnection) or die('Database Not Setup Properly... Please check your 
				database is created in mySQL which is mentioned in this APP...');
			//print ('Connection Established...\n');
		}

		/* Please call this destruct in log off function. or else connection will be establish for ever. 
		   It will be problem in server burden. */

		public function __destruct() {
			mysql_close($this->mySQLConnection);
		} 

	}
?>