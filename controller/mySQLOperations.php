<?php

/*
AUTHOR  : SATYANARAYANA MURTHY U V N
Version : 0.1v
Purpose : Provide CRUD operations in MySQL to APP
*/
	class mySQLOperations extends mySQL {
		
		public function __construct() {
			parent::__construct();
			//print ("Coming to mySQLOperations class\n");
		}

		/* Get Data from Table by using this fuction
		   Inputs : 
		   		tableName : Provide Table Name which you want to get Data
		   		cols : Provide columns to get data. If you want all columns Please provide "*" or empty
		   		conditions : This will be String format
		   		limit : 
		   		orderBy : 
		   	Output : Two Dimentional Array will be given, you need to do operations with this array
		*/

		public function fetchDataFromTable($tableName, $cols, $conditions, $limit, $orderBy) {

			//If some times columns will be empty or not defined. So I am taking care about all possible ways, 
			//If column is empty or not defined. * will apply to get data, means all columns will come 

			$cols = (defined($cols) && $cols == '') ? "*" : $cols;

			//Basic Query formation
			$queryString = "SELECT ". $cols . " FROM " . $tableName;
			
			//Add ons like limit, conditions and order by. We are not taking care about any JOINs here.

			$queryString .= (defined($conditions) && $conditions != '')? " WHERE " . $conditions : ''; 
			$queryString .= (defined($limit) && $limit != '') ? " LIMIT " . $limit : '';
			$queryString .= (defined($orderBy) && $orderBy != '')? " ORDER BY " . $orderBy : '';
			
			//Querying to DB with basing PHP mySQL function
			$result = mysql_query($queryString);
			
			//Output will be store in output function. We are not ready to allow any user to use basing MySQL Functions in any other classes.
			//So you we will club all data and give to use in array format. They can use this data in any way.
			$output = Array();
			while($res = mysql_fetch_assoc($result)){
				array_push($output, $res);
			}
			//Output contains Array
			return $output;
		}


		/*
			If anyone wants to use direct query instead of our fetchDataFromTable function, They can use "query" function
		*/
		public function query ($query) {
			//If Any user passes empty string, we should not query, because its waste of time to execute in DB, and main
			//Intension is to reduce DB burden
			if($query != ''){
				
				//Result will store all data related to our query
				
				$result = mysql_query($query);

				//this will store the entire output from our query
				
				$output = Array();
				
				while($res = mysql_fetch_assoc($result)){
					array_push($output, $res);
				}
				
				return $output;

			}else{

				die("Please Provide Details here...");
			}
		}

		/* Update Data in Table by using "updateDataToTable" fuction
		   Inputs : 
		   		tableName : Provide Table Name which you want to get Data
		   		setValues : After SET what columns you need to change and what are those values. This will be string format
		   		conditions : This will be String format
		   	Output : Two Dimentional Array will be given, you need to do operations with this array
		*/

		public function updateDataToTable($tableName, $setValues, $conditions){

			//Checking all condition before proceed to execute

			$tableName = (!isset($tableName) || $tableName == '') ? die("Please Provide Table Name") : $tableName;
			$setValues = (!isset($setValues) || $setValues == '') ? die("Please Provide Update Values") : $setValues;
			$conditions = (!isset($conditions) || $conditions == '') ? '' : $conditions;

			//Preparing query to execute to mysql
			$queryString = "UPDATE " . $tableName . " SET " . $setValues . " WHERE " . $conditions;
			$result = mysql_query($queryString) or die("We are unable to query at this time. Please check your Connection or Query");

			//result will be digit. So no need to run loop
			return $result;
		}

		public function deleteDataFromTable($tableName, $conditions){

			
			$tableName = (!isset($tableName) || $tableName == '') ? die("Please Provide Table Name") : $tableName;
			$conditions = (!isset($conditions) || $conditions == '') ? '' : $conditions;

			$queryString = "DELETE FROM " . $tableName . " WHERE " . $conditions;
			$result = mysql_query($queryString) or die("We are unable to query at this time. Please check your Connection or Query");
			return $result;

		}

	}
?>