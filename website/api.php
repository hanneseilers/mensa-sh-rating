<?php

/*
 * MENSA SH API
* author: Hannes Eilers
* version: 0.0.1-alpha
*
* Parameters
* f		Function to call.
*			f=getRating recieves rating
*			f=addRating adds rating
*			f=getComments returns list of comments
* loc		Name of city (location) of mensa
* mensa	Name of mensa
* meal	Title/name of meal
* rating Rating value of meal
* hash	Hash value of client that sends rating
* com		Comment/text for rating
* pig		pig=1 if meal includes pig
* cow		pig=1 if meal includes cow
* vege	vege=1 if meal is vegetarian
* vega	vega=1 if meal is vegan
* alc		alc=1 if meal includes alcohol
*
* Return values
* ok				If adding rating was successfull
* |			  	Seperates values
* err				If an error occured. That can happen
*		  			due to problems with database connection
*					or invalid/missing parameters
* nf				Meal not found
*/

include('php/db.php');
include('php/err.php');

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

	switch($f){
		case "addRating":
			addRatingToDB($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);
			break;

		case "getRating":
			getRatingFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			break;

		case "getComments":
			getCommentsFromDB($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			break;

		default:
			echo( $ERR );

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
			addRating($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);
			echo( $OK );

		} else{
			// meal not available > add meal
			addMeal($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
			addRatingToDB($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment);
		}

	} else{
		die($ERR);
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
		echo( "$OK$SEPERATOR$rating" );

	} else{
		die($ERR);
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
		echo( "$OK" );

		foreach($comments as $comment)
			echo( "$SEPERATOR$comment" );

	} else{
		die($ERR);
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