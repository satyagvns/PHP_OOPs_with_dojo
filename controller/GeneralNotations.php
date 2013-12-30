<?php
	class GeneralNotations extends mySQLOperations {
		public function __construct() {
			parent::__construct();
			//print ("Coming to GeneralNotations class\n");
		} 

		public function getData(){
			$getResult = parent::deleteDataFromTable('test', 'username = "murthy"');
			print_r($getResult);
		}
	}
?>