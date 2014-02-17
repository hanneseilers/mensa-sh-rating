<?php

function processRatingQuery($loc, $mensa, $query){
	
	global $ERR;
	$response = "";
	
	if( $loc && $mensa && $query ){
		// split recieved query
		$data = explode("|", $query);
		
		// go through all request of the query
		foreach( $data as $mealKey ){
			if( strlen($mealKey) > 5 ){
				
				// get name of meal
				$meal = str_replace("_", " ", substr($mealKey, 0, (strlen($mealKey)-5)) );
				
				// get parameters of meal
				$pig = $mealKey[strlen($mealKey)-5];
				$cow = $mealKey[strlen($mealKey)-4];
				$vegetarian = $mealKey[strlen($mealKey)-3];
				$vegan = $mealKey[strlen($mealKey)-2];
				$alc = $mealKey[strlen($mealKey)-1];
				
				$rating = getRatingFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
				$response =  $response."\n".$mealKey.$rating;
			}
		}
	}
	else{
		return $ERR;
	}
	
	if( strlen($response) == 0 ){
		return $ERR;
	}
	return $response;
	
}

?>