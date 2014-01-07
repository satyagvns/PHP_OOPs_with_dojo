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
		   Input         : File Name with path
		   Output        : String format content
		*/
		public function getWordDocContent($fileName){
		
			// Creating new variables to store document content
		    $striped_content = '';
			$content = '';
			/* Checking for a format of uploaded document
				if that document related to "docx" below code will work
				or else there is one more code to read "doc" format
				
				pathinfo is the pre-defined function in PHP to get details on file.
				here we want only extension of the file so that I used PATHINFO_EXTENSION
			*/
			$docFormat = pathinfo($fileName, PATHINFO_EXTENSION);
			if($docFormat == 'docx'){ // Checking file extenstion
				/* Before going to read any document you need to search weather the file is
				   exists or not. if the file is not exists, it will return false
				*/
				if(!$fileName || !file_exists($fileName)) return false;
				/* zip_open is one of the pre-defined PHP function. We can read archive
					using the function.
				*/
				$zip = zip_open($fileName);
				/*
					if file return any number, that means we are trying to read non archived
					files. So you will get errors like zip does not contain any content
				*/
				if (!$zip || is_numeric($zip)) return false;
				//Reading file Starts here
				while ($zip_entry = zip_read($zip)) {
					if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
					print (zip_entry_name($zip_entry)); exit;
					if (zip_entry_name($zip_entry) != "word/document.xml") continue;
					$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					zip_entry_close($zip_entry);
				}
				zip_close($zip);
				/*
					Basically docx format is designed based on the XML Format. 
					So after reading document, we need to clear all xml related 
					tags. or else You will confuse by seeing xml tags in document
				*/
				$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
				$content = str_replace('</w:r></w:p>', "\r\n", $content);
				$striped_content = strip_tags($content);
			} else {
				
				// We are using same class function, So we need to use $this->. or else.
				// It will throw erros.
				$striped_content = $this->getWordDocContentForOnlyDoc($fileName);
			}
			//content will be store in string and send to mail file.
			return $striped_content;
			
		}
		
		/* Purpose       : To Read Word content (Only Text) in Word Document
		   Author        : U V N SATYANARAYANA MURTHY
		   Function Name : getWordDocContentForOnlyDoc
		   Input         : File Name with path
		   Output        : String of content
		*/
		
		public function getWordDocContentForOnlyDoc($fileName){
			/*
				Basically doc file designed in normal way. So that, we no need to read
				file with zip readers. These are normal file open and read functions in
				PHP.
			*/
			$fileHandle = fopen($fileName, "r");
		    $line = @fread($fileHandle, filesize($fileName));   
		    $lines = explode(chr(0x0D),$line);
		    $outtext = "";
		    foreach($lines as $thisline)
		      {
		        $pos = strpos($thisline, chr(0x00));
		        if (($pos !== FALSE)||(strlen($thisline)==0))
		          {
		          } else {
		            $outtext .= $thisline." ";
		          }
		      }
		     $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","\r\n",$outtext);
		    return $outtext;
		}
	}
?>