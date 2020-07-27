<?php
	/*
A function which validates user input and sanitises it to
prevent SQL injection. This will prepare any string variables
for query, $string parameter it defines is it String or not(Email)
*/

function filterIt($value, $connect, $string){
		$filter = FILTER_SANITIZE_STRING;

		
			if($string != true){
				$filter = FILTER_SANITIZE_EMAIL;
			}
				
			
$validatedData = filter_var($value, $filter);
$sanitisedValue = mysqli_real_escape_string($connect, $validatedData);
return $sanitisedValue;
	
	if(($string == false) && (!filter_var($value,FILTER_VALIDATE_EMAIL))){
                            $errors .= $invalidEmail;             
	}
}
?>