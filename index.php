<?php
	function __autoload($classname){
		$filename = "controller/".$classname.".php";
		include_once($filename);
	}

	$obj = new GeneralNotations();
?>