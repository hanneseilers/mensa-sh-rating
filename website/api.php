<?php

/*
 * MENSA SH API
* author: Hannes Eilers
* version: 0.0.1-alpha
*
* Parameters
* f			Function to call.
*			f=getRating recieves rating
*			f=addRating adds rating
*			f=getComments returns list of comments
*			f=getRatingQuery returns list of ratings
* loc		Name of city (location) of mensa
* mensa		Name of mensa
* meal		Title/name of meal
* rating 	Rating value of meal
* hash		Hash value of client that sends rating
* com		Comment/text for rating
* pig		pig=1 if meal includes pig
* cow		pig=1 if meal includes cow
* vege		vege=1 if meal is vegetarian
* vega		vega=1 if meal is vegan
* alc		alc=1 if meal includes alcohol
* query		query of meal names to get ratings for
*
* Return values
* ok				If adding rating was successfull
* |			  		Seperates values
* err				If an error occured. That can happen
*		  			due to problems with database connection
*					or invalid/missing parameters
* nf				Meal not found
*/

include('php/db.php');
include('php/err.php');
include('php/query.php');

# Call process function
processURLParameter();



/*
 * Function to process recieved data
*/
function processURLParameter(){
	
	global $ERR;

	if( isset($_GET['f']) )
		$f = $_GET['f'];
	else
		$f = null;

	if( isset($_GET['loc']) )
		$loc = $_GET['loc'];
	else
		$loc = null;

	if( isset($_GET['mensa']) )
		$mensa = $_GET['mensa'];
	else
		$mensa = null;

	if( isset($_GET['meal']) )
		$meal = $_GET['meal'];
	else
		$meal = null;

	if( isset($_GET['rating']) )
		$rating = $_GET['rating'];
	else
		$rating = 0;

	if( isset($_GET['hash']) )
		$hash = $_GET['hash'];
	else
		$hash = null;

	if( isset($_GET['com']) )
		$comment = $_GET['com'];
	else
		$comment = "";

	if( isset($_GET['pig']) )
		$pig = $_GET['pig'];
	else
		$pig = 0;

	if( isset($_GET['cow']) )
		$cow = $_GET['cow'];
	else
		$cow = 0;

	if( isset($_GET['vege']) )
		$vegetarian = $_GET['vege'];
	else
		$vegetarian = 0;

	if( isset($_GET['vega']) )
		$vegan = $_GET['vega'];
	else
		$vegan = 0;

	if( isset($_GET['alc']) )
		$alc = $_GET['alc'];
	else
		$alc = 0;
	
	if( isset($_GET['query']) )
		$query = $_GET['query'];
	else
		$query = null;
	
	// process function
	processFunction($f, $loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment, $query);

}

function processFunction($f, $loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment, $query){
	
	global $ERR;
	global $NOTFOUND;
	global $OK;
	
	$ret = $ERR;
	
	switch($f){
		case "addRating":
			$ret = addRatingToDB($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);
			break;

		case "getRating":
			$ret = getRatingFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			break;

		case "getComments":
			$ret = getCommentsFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			break;
			
		case "getRatingQuery":
			$ret = processRatingQuery($loc, $mensa, $query);
			break;

		default:
			$ret = $ERR;
	}
	
	if( !$ret || $ret == $ERR ){
		echo( "$ERR" );
	}
	elseif( $ret == $NOTFOUND ) {
		echo( "$NOTFOUND" );
	}
	elseif( $ret == $OK ){
		echo "$ret";
	}
	else{
		echo "$OK$ret";
	}

}

/*
 * Function to add a new rating
*/
function addRatingToDB($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment){
	global $ERR;
	global $OK;;

	if( checkMealParameter($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc) &&
	$rating != null && $hash != null ){
		
		// every parameter needed is present
		$mealID = getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
		if( $mealID != null ){
			// meal already available > add rating	
			return addRating($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);

		} else{
			// meal not available > add meal
			addMeal($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			return addRatingToDB($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);
		}

	} else{
		return($ERR);
	}
}

/*
 * Function to get a rating value
*/
function getRatingFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $ERR;
	global $OK;
	global $SEPERATOR;

	if( checkMealParameter($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc) ){

		// every parameter needed is present
		$rating = getRating($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
		return "$SEPERATOR$rating" ;

	} else{
		return($ERR);
	}
}


/*
 * Function to get comments
*/
function getCommentsFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $ERR;
	global $OK;
	global $SEPERATOR;

	if( checkMealParameter($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc) ){

		// every parameter needed is present
		$comments = getComments($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
		$ret = "";

		foreach($comments as $comment)
			$ret += "$SEPERATOR$comment";
		
		return $ret;

	} else{
		return($ERR);
	}
}


/*
 * Returns true if all neeeded parameters are available
*/
function checkMealParameter($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	if( $loc != null &&
	$mensa != null &&
	$meal != null &&
	$pig != null &&
	$cow != null &&
	$vegetarian != null &&
	$vegan != null &&
	$alc != null )
		return true;
	else
		return false;
}





?>