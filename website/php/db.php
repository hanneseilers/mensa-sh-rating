<?php

include("err.php");

$SQL_HOST = "localhost";
$SQL_DB = "mensa_sh_rating";
$SQL_USR = "root";
$SQL_PW = "root";

$DB_HANDLER = null;

$DB_MEALS = "meals";
$DB_MENSEN = "mensen";
$DB_RATINGS = "ratings";
$DB_LOC = "locations";

$DB_TIMEDIFF = 100*60*24;

/*
 * Sends a request to database
*/
function sql_query($sql){	
	global $SQL_HOST;
	global $SQL_USR;
	global $SQL_PW;
	global $SQL_DB;

	global $DB_HANDLER;
	global $ERR;

	// connect to database
	if( $DB_HANDLER == null ){
		$DB_HANDLER = mysqli_connect( $SQL_HOST, $SQL_USR, $SQL_PW, $SQL_DB);
		
		if( mysqli_connect_errno() )
			die($ERR);
	}

	return mysqli_query($DB_HANDLER, $sql);
}



/*
 * Returns ID of location
*/
function getLocationID($loc){
	global $DB_LOC;
	global $ERR;

	// get request from db
	$sql = "SELECT idlocations FROM $DB_LOC WHERE location='$loc'";
	$res = sql_query($sql);

	// check result and return ID
	if( $res && mysqli_num_rows($res) > 0 ){
		return mysqli_fetch_assoc($res)['idlocations'];
	} else{
		die($ERR);
	}
}

/*
 * Returns ID of mensa
*/
function getMensaID($loc, $mensa){
	global $DB_MENSEN;
	global $ERR;

	// get request from db
	$locID = getLocationID($loc);
	$sql = "SELECT idmensen FROM $DB_MENSEN WHERE locations_idlocations='$locID' AND name='$mensa'";
	$res = sql_query($sql);

	// check result and return ID
	if( $res && mysqli_num_rows($res) > 0 ){
		return mysqli_fetch_assoc($res)['idmensen'];
	} else{
		die($ERR);
	}
}


/*
 * Returns meal entry or null if no meal found
*/
function getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $DB_MEALS;

	$locID = getLocationID($loc);
	$mensaID = getMensaID($loc, $mensa);
	$sql = "SELECT idmeals FROM $DB_MEALS WHERE mensen_locations_idlocations=$locID AND mensen_idmensen=$mensaID AND pig=$pig AND cow=$cow AND vegetarian=$vegetarian AND vegan=$vegan AND alc=$alc AND name='$meal'";
	$res = sql_query($sql);
	if( $res && mysqli_num_rows($res) > 0 ){
		return mysqli_fetch_assoc($res)['idmeals'];
	} else{
		return null;
	}
}


/*
 * Adds a meal to db
*/
function addMeal($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $DB_MEALS;
	global $ERR;

	// create db request
	$locID = getLocationID($loc);
	$mensaID = getMensaID($loc, $mensa);
	$sql = "INSERT INTO $DB_MEALS(name, pig, cow, vegetarian, vegan, alc, mensen_idmensen, mensen_locations_idlocations) VALUES('$meal', $pig, $cow, $vegetarian, $vegan, $alc, $mensaID, $locID)";
	// add meal
	if( !sql_query($sql) )
		die($ERR);
}

/*
 * Adds a rating
*/
function addRating($loc, $mensa, $meal, $rating, $hash, $pig, $cow, $vegetarian, $vegan, $alc, $comment){
	global $DB_RATINGS;
	global $ERR;
	global $OK;

	if( !checkForRating($loc, $mensa, $meal, $hash, $pig, $cow, $vegetarian, $vegan, $alc) ){

		// create db request
		$mealID = getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
		
		$date = new DateTime();
		$sql = "INSERT INTO $DB_RATINGS(rating, date, hash, comment, meals_idmeals) VALUES($rating, NOW(), '$hash', '$comment', $mealID)";

		// add rating
		if( !sql_query($sql) ){
			die($ERR);
		}

	} else{
		die($ERR);
	}
	
	return $OK;

}

/*
 * Returns true if there's already a rating for a meal for this day
*/
function checkForRating($loc, $mensa, $meal, $hash, $pig, $cow, $vegetarian, $vegan, $alc){
	global $DB_RATINGS;
	global $DB_TIMEDIFF;
	global $ERR;

	// send request to db
	$mealID = getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
	$sql = "SELECT * FROM $DB_RATINGS WHERE NOW()-date > 0 AND NOW()-date < $DB_TIMEDIFF AND hash='$hash' AND meals_idmeals=$mealID";
	$res = sql_query($sql);

	// check result
	if( $res && mysqli_num_rows($res) > 0 )
		return true;
	else
		return false;
}

/*
 * Returns average rating of meal
*/
function getRating($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $DB_RATINGS;
	global $NOTFOUND;

	// create db request
	$mealID = getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
	$sql = "SELECT ROUND( AVG(rating), 0 ) AS avg FROM $DB_RATINGS WHERE meals_idmeals=$mealID";
	$res = sql_query($sql);

	if( $res && mysqli_num_rows($res) > 0 )
		return mysqli_fetch_assoc($res)['avg'];
	else
		return($NOTFOUND);
}

/*
 * Returns array of comments
*/
function getComments($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc){
	global $DB_RATINGS;
	global $NOTFOUND;

	$mealID = getMealID($loc, $mensa, $meal, $pig, $cow, $vegetarian, $vegan, $alc);
	$sql = "SELECT comment FROM $DB_RATINGS WHERE meals_idmeals=$mealID and comment!=''";
	$res = sql_query($sql);
	$array = array();

	if( $res && mysqli_num_rows($res) > 0 ){
		$rows = mysqli_num_rows($res);
		for( $i=0; $i<$rows; $i++ )
			$array[$i] = mysqli_fetch_assoc($res)['comment'];
		return $array;

	} else
		die($NOTFOUND);
}

?>