<?php
	function __autoload($classname){
		$filename = "controller/".$classname.".php";
		include_once($filename);
	}

	$GN = new GeneralNotations();
	
	$filename = "cv.doc";
	?>
	<!-- 
	<iframe src="http://docs.google.com/gview?url=http://www.example.com/report.doc"></iframe>
	-->
	<?php
    $content = $GN->getWordDocContent($filename);
    if($content !== false) {

        echo nl2br($content);   
    }
    else {
        echo 'Couldn\'t find the file. Please check that file.';
    }
	
?>