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

		/* Purpose       : To Read Word content (Only Text) in Word Document
		   Author        : U V N SATYANARAYANA MURTHY
		   Function Name : getWordDocContent
		   Input         : File Path
		   Output        : Array of contents
		*/
		public function getWordDocContent($filePath, $fileName){
			
		}
	}
?>