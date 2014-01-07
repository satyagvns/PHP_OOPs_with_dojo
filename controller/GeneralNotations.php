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
		
		
		public function numberToWords($number) {
    
		    $hyphen      = '-';
		    $conjunction = ' and ';
		    $separator   = ', ';
		    $negative    = 'negative ';
		    $decimal     = ' point ';
		    $dictionary  = array(
		        0                   => 'zero',
		        1                   => 'one',
		        2                   => 'two',
		        3                   => 'three',
		        4                   => 'four',
		        5                   => 'five',
		        6                   => 'six',
		        7                   => 'seven',
		        8                   => 'eight',
		        9                   => 'nine',
		        10                  => 'ten',
		        11                  => 'eleven',
		        12                  => 'twelve',
		        13                  => 'thirteen',
		        14                  => 'fourteen',
		        15                  => 'fifteen',
		        16                  => 'sixteen',
		        17                  => 'seventeen',
		        18                  => 'eighteen',
		        19                  => 'nineteen',
		        20                  => 'twenty',
		        30                  => 'thirty',
		        40                  => 'fourty',
		        50                  => 'fifty',
		        60                  => 'sixty',
		        70                  => 'seventy',
		        80                  => 'eighty',
		        90                  => 'ninety',
		        100                 => 'hundred',
		        1000                => 'thousand',
		        1000000             => 'million',
		        1000000000          => 'billion',
		        1000000000000       => 'trillion',
		        1000000000000000    => 'quadrillion',
		        1000000000000000000 => 'quintillion'
		    );
		    
		    if (!is_numeric($number)) {
		        return false;
		    }
		    
		    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
		        // overflow
		        trigger_error(
		            'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
		            E_USER_WARNING
		        );
		        return false;
		    }

		    if ($number < 0) {
		        return $negative . numberToWords(abs($number));
		    }
		    
		    $string = $fraction = null;
		    
		    if (strpos($number, '.') !== false) {
		        list($number, $fraction) = explode('.', $number);
		    }
		    
		    switch (true) {
		        case $number < 21:
		            $string = $dictionary[$number];
		            break;
		        case $number < 100:
		            $tens   = ((int) ($number / 10)) * 10;
		            $units  = $number % 10;
		            $string = $dictionary[$tens];
		            if ($units) {
		                $string .= $hyphen . $dictionary[$units];
		            }
		            break;
		        case $number < 1000:
		            $hundreds  = $number / 100;
		            $remainder = $number % 100;
		            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
		            if ($remainder) {
		                $string .= $conjunction . numberToWords($remainder);
		            }
		            break;
		        default:
		            $baseUnit = pow(1000, floor(log($number, 1000)));
		            $numBaseUnits = (int) ($number / $baseUnit);
		            $remainder = $number % $baseUnit;
		            $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
		            if ($remainder) {
		                $string .= $remainder < 100 ? $conjunction : $separator;
		                $string .= numberToWords($remainder);
		            }
		            break;
		    }
		    
		    if (null !== $fraction && is_numeric($fraction)) {
		        $string .= $decimal;
		        $words = array();
		        foreach (str_split((string) $fraction) as $number) {
		            $words[] = $dictionary[$number];
		        }
		        $string .= implode(' ', $words);
		    }
		    
		    return $string;
		}
		
		
		
		
		
	}
?>