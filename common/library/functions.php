<?php

/*******************************************************************************
 * *************************************************************************** *
 ******************  HERE BEGINS DDBB STANDARD PHP FUNCTIONS  ******************
 * *************************************************************************** *
 *******************************************************************************/



/* Lets the program to establish connection against DDBB. We will only need to enter manually name of the DDBB.
 * Entry: N/A
 * Exit: Connection instance
 */
function connectDB(){
	$connection = mysqli_connect('localhost','root','', 'PRJ2014001') or die('MySQL connection error. Please contact administrator');

	$connection->query("SET NAMES 'utf8'");
	
	return $connection;
}



/* Delete 1 registry from a given table
 * Entry (dbtable): Name for DB table
 * Entry (primaryname): Field used to identify uniquely registry to be erased
 * Entry (primaryvalue): Value that identifies uniquely the registry to be erased
 * Exit: Boolean that indicates if it was OK or KO
 */
function deleteDBrow($dbtable, $primaryname, $primaryvalue){
	$conexion = connectDB();

	$query = "DELETE FROM `$dbtable` WHERE `$primaryname`='$primaryvalue'";

	if(mysqli_query($conexion, $query) or die("Error deleting DB registry: ".mysqli_error($conexion))){
		mysqli_close($conexion);
		return 1;
	}
	else{
		mysqli_close($conexion);
	}
}



/* Executes a complete DB query sent by PHP code, whatever it would be
 * Entry ($query): Complete query sent from original code
 * Exit: Returns 1 if succesfully executed
 */
function executeDBquery($query){
	$conexion = connectDB();

	if(mysqli_query($conexion, $query) or die("Error in DB request: ".mysqli_error($conexion))){
		mysqli_close($conexion);
		return 1;
	}
	else {
		mysqli_close($conexion);
	}
}


/* Extract name of the column whose position is passed in parameter
 * Entry (dbtable): Name of the table
 * Entry (column): Number of column in which it is desired to know its name
 * Exit (cname): Array with name
 */
function getDBcolumnname($dbtable, $column){
	$connection = connectDB();
	$query = "SELECT * FROM `$dbtable` LIMIT 1";
	if($result = mysqli_query($connection, $query)){
		$i = 0;
		while($colObject = mysqli_fetch_field($result)){
			if($i == $column){
				$colName = ($colObject->name);
			}
			$i++;
		}
		mysqli_free_result($result);
	}
	mysqli_close($connection);
	return $colName;
}



/* Returns Array (if succeded) with all matched values in one given column
 * Entry (fieldrequested): Field where possible matches will be searched
 * Entry (dbtable): Name of table
 * Entry (fieldsupported): Field used by SELECT query to identify matches in "fieldrequested"
 * Entry (infosupported): Value that indicates match
 * Exit (row): Array with matched values
 */
function getDBcolumnvalue($fieldrequested, $dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();
	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error extracting matching array: ".mysqli_error($conexion));
	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}



/* Returns all values in one column, ordered by especified ID
 * Entry (columnrequested): Name of the column which values want to be extracted
 * Entry (dbtable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBcompletecolumnID($columnrequested, $dbtable, $id){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT `$columnrequested` FROM `$dbtable` ORDER BY `$id`") or die("Complete column extraction error: ".mysqli_error($conexion));

	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}



/* Returns all DISTINCT values in one column, ordered by especified ID
 * Entry (colRequested): Name of the column which distinct values want to be extracted
 * Entry (dbTable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBDistCompleteColID($colRequested, $dbTable, $id){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT DISTINCT `$colRequested` FROM `$dbTable` ORDER BY `$id`") or die("Distinct complete extraction error: ".mysqli_error($connection));

	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($connection);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
	}
}



/* Returns Array (if succeded) with all NOT matched values in one given column, ordered by selected ID. Opposite to "getDBcolumnvalue"
 * Entry (fieldReq): Field where possible NON-matching values will be searched
 * Entry (dbTable): Name of table
 * Entry (fieldSup): Field used by SELECT query to identify NON-matching values in "fieldReq"
 * Entry (infoSup): String that indicates NON-matching value to be obviated from exit row
 * Entry (id): Unique identificator used to get possible output array ordered
 * Exit (row): Output array with NON-matching values
 */
function getDBNoMatchColValueID($fieldReq, $dbTable, $fieldSup, $infoSup, $id){
	$connection = connectDB();
	
	$result = mysqli_query($connection, "SELECT `$fieldReq` FROM `$dbTable` WHERE `$fieldSup`!='$infoSup' ORDER BY `$id`") or die("Error obtaining non-matching array: ".mysqli_error($connection));
	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($connection);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
	}
}



/* Counts number of columns in a table
 * Entry (dbtable): Name for the table in which will be counted number of columns
 * Exit (num_columns): Integer with total number of columns in table
 */
function getDBnumcolumns($dbtable){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT * FROM `$dbtable` LIMIT 1");
	$numColumns = mysqli_field_count($connection);
	mysqli_free_result($result);
	mysqli_close($connection);
	return $numColumns;
}



/* Gets a complete row from DB for supported data
 * Entry (dbtable): Name for the table where row must be get
 * Entry (fieldsupported): Name for column used to select uniquely the row
 * Entry (infosupported): Unique value used to identify uniquely the row
 * Exit (fila): Complete and unique row
 */
function getDBrow($dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();
	$result = mysqli_query($conexion, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error obtaining registry: ".mysqli_error($conexion));
	if(mysqli_num_rows($result) <= 0 ){
		mysqli_free_result($result);
		mysqli_close($conexion);
		return 0;
	}
	else{
		$fila = mysqli_fetch_array($result);
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $fila;
	}
}



/* Counts total number of rows in a table
 * Entry (dbtable): DB where wanted to know total number of registries
 * Exit (num_rows): Integer with number of rows
 */
function getDBrowsnumber($dbtable){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT COUNT(*) FROM `$dbtable`") or die("Error obtaining row's number: ".mysqli_error($conexion));

	$num_rows = mysqli_fetch_array($result);
	mysqli_free_result($result);
	mysqli_close($conexion);
	return $num_rows[0];
}



/* Extracts a unique value from 1 single row
 * Entry (fieldrequested): Field in which is needed value
 * Entry (dbtable): Table where to exectue SELECT query
 * Entry (fieldsupported): Field used to execute SELECT query
 * Entry (infosupported): Unique value used to execute SELECT query
 * Exit (singleDBfield): Array stored in "fieldrequested" var
 */
function getDBsinglefield($fieldrequested, $dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error obtaining single value: ".mysqli_error($conexion));

	if (mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldrequested];
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $singleDBfield;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}



/* Extracts a unique value from 1 single row searching it using 2 different fields
 * Entry (fieldreq): Field in which is needed value
 * Entry (dbtable): Table where to exectue SELECT query
 * Entry (fieldsup1): Name for 1st field used to execute SELECT query
 * Entry (infosup1): Unique value for 1st field used to execute SELECT query
 * Entry (fieldsup2): Name for 2nd field used to execute SELECT query
 * Entry (infosup2): Unique value for 2nd field used to execute SELECT query
 * Exit (singleDBfield): Array stored in "fieldreq" var
 */
function getDBsinglefield2($fieldreq, $dbtable, $fieldsup1, $infosup1, $fieldsup2, $infosup2){
	$conexion = connectDB();
	$result = mysqli_query($conexion, "SELECT `$fieldreq` FROM `$dbtable` WHERE `$fieldsup1`='$infosup1' AND `$fieldsup2`='$infosup2'") or die("Error obtaining single value: ".mysqli_error($conexion));
	if(mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldreq];
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $singleDBfield; //Devuelve un string
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}



/* Obtains all the tables' names in the DB
 * Exit (row): Array with all the names
 */
function getDBTablesNames(){
	$connection = connectDB();
	//$result = mysql_listtables();
	//$result = mysql_list_tables('prj2014001');
	//$result = mysql_list_tables('PRJ2014001');
	$result = mysqli_query($connection, "SHOW TABLES");
	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($connection);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
	}
}



/* Inserts 1 single row in a given table, without being necessary to know how many fields registry or dbTable have.
 * PRE: 'id' field (which is always first field) must be autoincrement, as it is not inserted in this function
 * Entry (registry): Array with all the fields/values for dbTable
 * Entry (dbTable): Table in which row/registry will be inserted
 * Exit (): Bool
 */
function insertDBRow($registry, $dbTable){
	$connection = connectDB();
	
	$numRegs = count($registry);
	if($numRegs > 0){
		$varCount = 1;
		//Creating part of the query in which field names are declared
		$query = "INSERT INTO `$dbTable` (";
		while ($varCount < $numRegs){
			$query = $query.'`'.getDBcolumnname($dbTable, $varCount).'`, ';
			$varCount++;
		}
		$query = $query.'`'.getDBcolumnname($dbTable, $varCount).'`) VALUES (';
		//echo 'Los campos son: '.$query.'<br>';
		
		$valueCount = 0;
		while ($valueCount < $numRegs-1){
			$query = $query.'\''.$registry[$valueCount].'\', ';
			$valueCount++;
		}
		$query = $query.'\''.$registry[$valueCount].'\')';
		
		//echo 'Finalmente... '.$query.'<br>';
		if(mysqli_query($connection, $query) or die("Error while recording registry: ".mysqli_error($connection))){
			mysqli_close($connection);
			return 1;
		}
		else{
			mysqli_close($connection);
			return 0;
		}
	}
	else{
		mysqli_close($connection);
		return 0;
	}
}



/* Inserts into given table all the information inside a plain-text file. Function made only for developer's use
 * PRE: Must be 'txt' or 'csv' format. Each registry in separated lines, always same delimiter. File must not have extra line at the end of file
 * PRE: dbTable must have 1 more field ('id' field) than the number of fields in registry
 * Entry (file): Name of plain text file to be uploaded
 * Entry (delimiter): Character used to separate each field
 * Entry (keyPos): Integer position for future key (to be used when normalizing string that will be register's key in 'createKey')
 * Entry (dbTable): DB Table where to insert every registry
 * Exit (): Bool
 */
//function massiveUpload($file, $delimiter, $keyPos, $dbTable){ SU USARA IDENTIFICADOR PARA LA 'key'
function massiveUpload($file, $delimiter, $dbTable){
	$allFileContent = file_get_contents($file);
	$eachRegistry = explode("\n", $allFileContent);
	foreach ($eachRegistry as $registry){
		$registryArray = explode($delimiter, $registry);
		//If any registry does not have 1 less field than dbTable, proccess will abort
		if(count($registryArray) != (getDBnumcolumns($dbTable)-1)){
			echo 'Alguno de los registros no cumple las condiciones para ser insertado en BD.';
			return 0;
		}
		else{
			//This creates array-vector as it will be inserted in DB
			//$toInsertArray = createArrayKey($registryArray);
			$toInsertArray = prepareArray($registryArray);
			//comprobar si la key ya está insertada
			insertDBRow($toInsertArray, $dbTable);
		}
	}
	return 1;
}





/***************************************************************************************************************************
 * *********************************************************************************************************************** *
 * ****************************  HERE BEGINS NON STANDARD PHP FUNCTIONS (NOT RELATED TO DDBB) **************************** *
 * *********************************************************************************************************************** *
 ***************************************************************************************************************************/





/**************************************************************************
 * ********************  LANGUAGE RELATED FUNCTIONS  ******************** *
 **************************************************************************/



/* Gets the 2 digits that identify the root for a language ('de', 'en', 'es') for logged user.
 * Entry (userLanguage): String where current user's language can be extracted. It is 'language' field in "users" table
 * Exit (): String as follows: "de", "en" or "es"
 */
function getUserLangDigits($userLanguage){
	if($userLanguage == "english"){
		return "en";
	}
	if($userLanguage == "german"){
		return "de";
	}
	if($userLanguage == "spanish"){
		return "es";
	}
}



/* Gets root characters ('de', 'en', 'es') for logged user's language in use. Better said, in what language is currently being displayed the page
 * Entry (userLanguage): String where current user's language can be extracted. It is 'language' field in "users" table
 * Exit (): String as follows: "/de/", "/en/" or "/es/"
 */
function getUserRoot($userLanguage){
	if($userLanguage == "english"){
		return "/en/";
	}
	if($userLanguage == "german"){
		return "/de/";
	}
	if($userLanguage == "spanish"){
		return "/es/";
	}
}



/* Gets universal path for any file. Universal path is the relative path without language part (i.e. "home.php" for "/es/home.php" or "home/personalData.php" for "/es/home/personalData.php")
 * Entry (filePath): String that contains full relative path (language included)
 * Exit (): String without root or language part for that same string
 */
function getNoRootPath($filePath){
	//This function returns the end of original string without the "n" first characters (4 in this case)
	return substr($filePath, 4);
}



/* Gets the translation for language
 * Entry (keyLanguage): Language key on DB
 * Entry (languageToBeTranslated): Desired language to $keyLanguage be translated
 * Exit (translation): Term into desired language
 */
function getLanguageTranslation($keyLanguage, $languageToBeTranslated){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `$languageToBeTranslated` FROM `siteLanguages` WHERE `key` = '$keyLanguage'") or die ("Error translating the language: ".mysqli_error($connection));
	$translation = mysqli_fetch_array($result);
	return $translation[0];
}



/* Gets key for a language
 * Entry (languageToBeTranslated): Desired language to be translated
 * Entry (languageWritten): In wich language are written $languageToBeTranslated
 * Exit (translation): Term into desired language
 */
function getKeyLanguage($languageToBeTranslated, $languageWritten){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `key` FROM `siteLanguages` WHERE `$languageWritten` = '$languageToBeTranslated'") or die ("Error translating the language: ".mysqli_error($connection));
	$translation = mysqli_fetch_array($result);
	return $translation[0];
}



/* Gets current language for the php page displayed. Done comparing "de", "en" or "es" suffixes in file path
 * Entry (filePath): String where read/search the language suffix
 * Exit (keyLanguage): String with the language where php file is
 */
function getCurrentLanguage($filePath){
	if(strpos($filePath, "/de/") === 0){
		//echo $lang;
		$lang = "german";
		return $lang;
	}
	if(strpos($filePath, "/en/") === 0){
		//echo $lang;
		$lang = "english";
		return $lang;
	}
	if(strpos($filePath, "/es/") === 0){
		//echo $lang;
		$lang = "spanish";
		return $lang;
	}
	$lang = "spanish";
	//echo "k";
	return $lang;
}





/**************************************************************************
 * ********************  PASSWORD RELATED FUNCTIONS  ******************** *
 **************************************************************************/


/* Generates a Hash key using Blowfish Algorithm to create after it a password
 * Entry (password): String wanted to be hashed
 * Exit (crypt): Hash key
 */
function blowfishCrypt($password, $rounds = 7){
	$saltChars = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $rounds);
	for($i=0; $i<22; $i++){
		$salt .= $saltChars[mt_rand(0, 63)];
	}
	return crypt($password, $salt);
}



//HAY UNA VERSION JAVASCRIPT DE ESTA FUNCION YA QUE CREO QUE NO SE USA
/* Checks whether a given password is strong enough (and properly written) when changed for a new one
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Exit (keyError): String with the error when needed (or void)
 */
/*
function checkPassChange($key1, $key2, &$keyError){
	if($key1 != $key2){
		//$keyError = "Ambas contraseñas deben ser iguales";
		$keyError = "Error: Both passwords must be identical.";
		return false;
	}
	if(strlen($key1) < 6){
		//$keyError = "La contraseña debe tener al menos 6 caracteres";
		$keyError = "Error: Password must be at least 6 characters.";
		return false;
	}
	if(strlen($key1) > 16){
		//$keyError = "La contraseña no puede tener más de 16 caracteres";
		$keyError = "Error: Password must not be more than 16 characters.";
		return false;
	}
	if (!preg_match('`[a-z]`',$key1)){
		//$keyError = "La contraseña debe tener al menos una letra minúscula";
		$keyError = "Error: Password must contain at least one lowercase letter.";
		return false;
	}
	if (!preg_match('`[A-Z]`',$key1)){
		//$keyError = "La contraseña debe tener al menos una letra mayúscula";
		$keyError = "Error: Password must contain at least one uppercase letter.";
		return false;
	}
	if (!preg_match('`[0-9]`',$key1)){
		//$keyError = "La contraseña debe tener al menos un caracter numérico";
		$keyError = "Error: Password must contain at least one numeric character.";
		return false;
	}
	$keyError = "";
	return true;
}
*/
/* Checks whether a given password matches every requirement when changed by a new one. And if it is different to previous password. Called from "personalData.php" and "validateFront.php"
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Entry (hashedKey): String directly extracted from DB that contains old password
 * Exit (keyError): String with the error when needed (or void)
 */
function checkHashedPassChangeDE($key1, $key2, $hashedKey, &$keyError){
	if($key1 != $key2){
		$keyError = "Fehler: Beide passwörter müssen übereinstimmen.";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "Fehler: Das passwort muss mindestens 6 zeichen lang sein";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "Fehler: Das passwort darf nicht mehr als 16 zeichen sein.";
		return false;
	}
	if(!preg_match('`[a-z]`',$key1)){
		$keyError = "Fehler: Das passwort muss mindestens einen kleinbuchstaben enthalten.";
		return false;
	}
	if(!preg_match('`[A-Z]`',$key1)){
		$keyError = "Fehler: Das passwort muss mindestens einen großbuchstaben enthalten.";
		return false;
	}
	if(!preg_match('`[0-9]`',$key1)){
		$keyError = "Fehler: Das passwort muss mindestens ein numerisches zeichen enthalten.";
		return false;
	}
	//elseif((!(crypt($_POST['logpasswd'], $userRow['pass']) == $userRow['pass'])) && (!$userRow['needPass'])){
	if(!(crypt($hashedKey, $key1) == $key1)){
		$keyError = "Fehler: Das passwort muss sich von der letzten unterscheiden.";
		return false;
	}
	$keyError = "";
	return true;
}


function checkHashedPassChangeEN($key1, $key2, $hashedKey, &$keyError){
	if($key1 != $key2){
		$keyError = "Error: Both passwords must be identical.";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "Error: Password must be at least 6 characters.";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "Error: Password must not be more than 16 characters.";
		return false;
	}
	if(!preg_match('`[a-z]`',$key1)){
		$keyError = "Error: Password must contain at least one lowercase letter.";
		return false;
	}
	if(!preg_match('`[A-Z]`',$key1)){
		$keyError = "Error: Password must contain at least one uppercase letter.";
		return false;
	}
	if(!preg_match('`[0-9]`',$key1)){
		$keyError = "Error: Password must contain at least one numeric character.";
		return false;
	}
	//elseif((!(crypt($_POST['logpasswd'], $userRow['pass']) == $userRow['pass'])) && (!$userRow['needPass'])){
	if(!(crypt($hashedKey, $key1) == $key1)){
		$keyError = "La contraseña debe ser distinta a la última";
		return false;
	}
	$keyError = "";
	return true;
}


function checkHashedPassChangeES($key1, $key2, $hashedKey, &$keyError){
	if($key1 != $key2){
		$keyError = "Ambas contraseñas deben ser iguales";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "La contraseña debe tener al menos 6 caracteres";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "La contraseña no puede tener más de 16 caracteres";
		return false;
	}
	if(!preg_match('`[a-z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra minúscula";
		return false;
	}
	if(!preg_match('`[A-Z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra mayúscula";
		return false;
	}
	if(!preg_match('`[0-9]`',$key1)){
		$keyError = "La contraseña debe tener al menos un caracter numérico";
		return false;
	}
	if(crypt($key1, $hashedKey) == $hashedKey){
		$keyError = "La contraseña debe ser distinta a la última";
		return false;
	}
	$keyError = "";
	return true;
}



/* Checks whether a given password is strong enough (and properly written) when changed for a new one. Called from "validateFront.php"
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Exit (keyError): String with the error when needed (or void)
 */
function checkSimplePassChangeDE($key1, $key2, &$keyError){
	if($key1 != $key2){
		$keyError = "Error: Beiden feldern stimmen nicht überein.";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "Error: Das passwort muss mindestens 6 zeichen lang sein.";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "Error: Das passwort darf nicht mehr als 16 zeichen sein.";
		return false;
	}
	if (!preg_match('`[a-z]`',$key1)){
		$keyError = "Error: Das passwort muss mindestens einen kleinbuchstaben.";
		return false;
	}
	if (!preg_match('`[A-Z]`',$key1)){
		$keyError = "Error: Das passwort muss mindestens einen großbuchstaben.";
		return false;
	}
	if (!preg_match('`[0-9]`',$key1)){
		$keyError = "Error: Das passwort muss mindestens ein numerisches Zeichen enthalten.";
		return false;
	}
	$keyError = "";
	return true;
}

function checkSimplePassChangeEN($key1, $key2, &$keyError){
	if($key1 != $key2){
		$keyError = "Error: Both password fields do not match.";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "Error: Password must be at least 6 characters.";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "Error: Password must not be more than 16 characters.";
		return false;
	}
	if (!preg_match('`[a-z]`',$key1)){
		$keyError = "Error: Password must contain at least one lowercase letter.";
		return false;
	}
	if (!preg_match('`[A-Z]`',$key1)){
		$keyError = "Error: Password must contain at least one uppercase letter.";
		return false;
	}
	if (!preg_match('`[0-9]`',$key1)){
		$keyError = "Error: Password must contain at least one numeric character.";
		return false;
	}
	$keyError = "";
	return true;
}

function checkSimplePassChangeES($key1, $key2, &$keyError){
	if($key1 != $key2){
		$keyError = "Ambos campos de contraseña no coinciden.";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "La contraseña debe tener al menos 6 caracteres.";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "La contraseña no puede tener más de 16 caracteres.";
		return false;
	}
	if (!preg_match('`[a-z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra minúscula.";
		return false;
	}
	if (!preg_match('`[A-Z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra mayúscula.";
		return false;
	}
	if (!preg_match('`[0-9]`',$key1)){
		$keyError = "La contraseña debe tener al menos un caracter numérico.";
		return false;
	}
	$keyError = "";
	return true;
}



function getRandomPass(){
	$str = "_-$%&/()=?!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$cad = "";
	$passLen = 8;
	for($i=0;$i<$passLen;$i++){
		$cad .= substr($str,rand(0,62),1);
	}
	return $cad;
}



/* Checks whether current password is about to expire
 * Entry (curDate): String with current date in YYYY-MM-DD format
 * Entry (curExpirate): String with current expiration date, which should a future date
 * Exit (true/false): Boolean that tells if password is about to expire or not
 */
function suggestPassword($curDate, $curExpirate, &$days){
	$datetime1 = date_create($curDate);
	$datetime2 = date_create($curExpirate);
	$interval = date_diff($datetime1, $datetime2);
	$days = $interval->format('%a');
	if($days > getDBsinglefield('value', 'otherOptions', 'key', 'passExpiryAdvise')){
		//It won't be necessary to remind user about changing password
		return false;
	}
	else{
		return true;
	}
}





/*******************************************************************************
 * ********************  PERSONAL DATA RELATED FUNCTIONS  ******************** *
 *******************************************************************************/



/* Checks whether a complete address (Name and Number for this Form) are well-formatted, avoiding as possible security breachs
 * Entry (inText): Input string which contains text written in form
 * Exit (outText): Output string, prepared to be registered in DB
 */
function cleanFreeText($inText){
	$connection = connectDB();
	
	//$outText = trim(htmlentities(mysqli_real_escape_string($connection, $inText)));
	$outText = trim(htmlentities(mysqli_real_escape_string($connection, $inText), ENT_QUOTES, 'UTF-8'));
	
	return $outText;
}




/* Checks whether a DNI (native) or NIE (abroad people with national document) is well-formatted and written or not
 * Entry (nie): String
 * Exit: Boolean
 */
function checkDNI_NIE($nie){
	$connection = connectDB();
	
	$outNie = trim(htmlentities(mysqli_real_escape_string($connection, $nie)));
	
	if(strlen($outNie) != 9){
		return false;      
	}
	//Possible values for end letter
	$letterValues = array(0 => 'T', 1 => 'R', 2 => 'W', 3 => 'A', 4 => 'G', 5 => 'M', 6 => 'Y', 7 => 'F', 8 => 'P', 9 => 'D', 10 => 'X', 11 => 'B',
	12 => 'N', 13 => 'J', 14 => 'Z', 15 => 'S', 16 => 'Q', 17 => 'V', 18 => 'H', 19 => 'L', 20 => 'C', 21 => 'K', 22 => 'E');
	
	//Checks if matches with an original DNI
	if(preg_match('/^[0-9]{8}[A-Z]$/i', $outNie)){
		//Checking letter match
		if(strtoupper($outNie[strlen($outNie) - 1]) != $letterValues[((int) substr($outNie, 0, strlen($outNie) - 1)) % 23]){
			return false;
		}
		else{
			return true; 
		}
	}
	//Checks if matches with an original NIE
	elseif(preg_match('/^[XYZ][0-9]{7}[A-Z]$/i', $outNie)){
		//Checking letter match
		if(strtoupper($outNie[strlen($outNie) - 1]) != $letterValues[((int) substr($outNie, 1, strlen($outNie) - 2)) % 23]){
			return false;
		}
		else{
			return true;
		}
	}
	//If function arrives here is because entry string is not valid
	return false; 
}




/* Checks whether both Driving License fields are properly fullfilled
 * Entry (type): String which indicates type of license (A, B...)
 * Entry (date): Date in format YYYY-MM-DD
 * Exit: Boolean
 */
function checkDrivingLicenseDE($type, $licDate, &$checkError){
	if((strlen($type) > 0) && (strlen($licDate) == 0)){
		$checkError = "Error: führerschein nicht angegeben Datum.";
		return false;
	}
	elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
		$checkError = "Error: Typ des führerschein nicht angegeben.";
		return false;
	}
	elseif(!isPreviousDate($licDate)){
		$checkError = "Error: Datum der führerschein kann nicht zukunft sein.";
		return false;
	}
	$checkError = "";
	return true;
}


function checkDrivingLicenseEN($type, $licDate, &$checkError){
	if((strlen($type) > 0) && (strlen($licDate) == 0)){
		$checkError = "Error: Driving License date is missing.";
		return false;
	}
	elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
		$checkError = "Error: Driving License type is missing.";
		return false;
	}
	elseif(!isPreviousDate($licDate)){
		$checkError = "Error: Driving License date cannot be a future date.";
		return false;
	}
	$checkError = "";
	return true;
}


function checkDrivingLicenseES($type, $licDate, &$checkError){
	if((strlen($type) > 0) && (strlen($licDate) == 0)){
		$checkError = "Fecha de obtención del permiso de conducir no indicada.";
		return false;
	}
	elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
		$checkError = "Tipo de permiso de conducir no indicado.";
		return false;
	}
	elseif(!isPreviousDate($licDate)){
		$checkError = "La fecha de permiso de conducir no puede ser futura";
		return false;
	}
	$checkError = "";
	return true;
}




/* Checks whether a complete address (Name and Number for this Form) are well-formatted, avoiding as possible security breachs
 * Entry (inName): String which contains name of the address
 * Entry (inNumber): String which contains number/letter for given address name
 * Exit (outAddrName): Returned string for Address Name
 * Exit (outAddrNumber): Returned string for Address Number
 * Exit (checkError): String with text that includes a description of the error */
function checkFullAddressDE($inName, $inNumber, &$outAddrName, &$outAddrNumber, &$checkError){
	$connection = connectDB();
	
	$outAddrName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outAddrNumber = trim(htmlentities(mysqli_real_escape_string($connection, $inNumber), ENT_QUOTES, 'UTF-8'));
	if(strlen($outAddrName) < 2){
		$checkError = "Error: Ungültige adresse.";
		return false;
	}
	elseif(strlen($outAddrNumber) < 1){
		$checkError = "Error: Die in den gültigen angegebene anzahl.";
		return false;
	}
		return true;
}


function checkFullAddressEN($inName, $inNumber, &$outAddrName, &$outAddrNumber, &$checkError){
	$connection = connectDB();
	
	$outAddrName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outAddrNumber = trim(htmlentities(mysqli_real_escape_string($connection, $inNumber), ENT_QUOTES, 'UTF-8'));
	if(strlen($outAddrName) < 2){
		$checkError = "Error: Invalid address.";
		return false;
	}
	elseif(strlen($outAddrNumber) < 1){
		$checkError = "Error: Address number not provided or not valid.";
		return false;
	}
		return true;
}


function checkFullAddressES($inName, $inNumber, &$outAddrName, &$outAddrNumber, &$checkError){
	$connection = connectDB();
	
	$outAddrName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outAddrNumber = trim(htmlentities(mysqli_real_escape_string($connection, $inNumber), ENT_QUOTES, 'UTF-8'));
	if(strlen($outAddrName) < 2){
		$checkError = "Error: Dirección no válida.";
		return false;
	}
	elseif(strlen($outAddrNumber) < 1){
		$checkError = "Error: Número no indicado o no válido";
		return false;
	}
		return true;
}




/* Checks whether a Name & Surname are both correct to be saved in a DB
 * Entry (inName): Input string for Name
 * Entry (inSurname): Input string for Surname
 * Exit (outName): Returned string for Name
 * Exit (outSurname): Returned string for Surname
 * Exit (checkError): String with text that includes a description of the error
 */
function checkFullNameDE($inName, $inSurname, &$outName, &$outSurname, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outSurname = trim(htmlentities(mysqli_real_escape_string($connection, $inSurname), ENT_QUOTES, 'UTF-8'));
	if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
		$checkError = "Error: Namen und Nachname müssen mindestens 3 zeichen sein.";
		return false;
	}
	return true;
}


function checkFullNameEN($inName, $inSurname, &$outName, &$outSurname, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outSurname = trim(htmlentities(mysqli_real_escape_string($connection, $inSurname), ENT_QUOTES, 'UTF-8'));
	if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
		$checkError = "Error: Name and Surname must be at least 3 characters each.";
		return false;
	}
	return true;
}


function checkFullNameES($inName, $inSurname, &$outName, &$outSurname, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outSurname = trim(htmlentities(mysqli_real_escape_string($connection, $inSurname), ENT_QUOTES, 'UTF-8'));
	if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
		$checkError = "Nombre y Apellidos deben tener al menos 3 caracteres cada uno.";
		return false;
	}
	return true;
}



/* Checks whether a MOBILE phone number is valid or not
 * Entry (mobile): Integer which contains a number
 * Exit: Boolean
 */
function checkMobile($mobile){
	$connection = connectDB();
	
	$outMobile = trim(htmlentities(mysqli_real_escape_string($connection, $mobile)));
	
	if(strlen($outMobile) != 9){
		return false;
	}
	elseif(!preg_match('/^[6-7][0-9]{8}$/', $outMobile)){
		return false;
	}
	return true;
}



/* Checks whether field Nationality in form is properly fulfilled
 * Entry (inNations): Input string acting as an array with 1 or more nationalities
 * Exit (outNations): Output string acting as an array for nationalities
 * Exit: Boolean
 */
function checkNationality($inNations, &$outNations){
	$connection = connectDB();
	
	$outArray = array();
	$numNats = count($inNations);
	for($i=0; $i<$numNats; $i++){
		$outSingleNation = trim(htmlentities(mysqli_real_escape_string($connection, $inNations[$i])));
		$outArray[$i] = $outSingleNation;
	}
	$outNations = implode("|",$outArray);
}



/* Checks whether a phone number is valid or not
 * Entry (phone): Integer which contains a number
 * Exit: Boolean
 */
function checkPhone($phone){
	$connection = connectDB();
	
	$outPhone = trim(htmlentities(mysqli_real_escape_string($connection, $phone)));
	
	if(strlen($outPhone) > 18){
		return false;
	}
	elseif(!preg_match('/(00[0-9]{2}[-][0-9]{3,13})|(00[0-9]{3}[-][0-9]{3,12})/', $outPhone)){
		return false;
	}
	return true;
}



/* Checks whether a person is adult or not, according input date
 * Entry (birthDate): Input date that represents birthdate
 * Entry (legalAge): Integer used to know the minimum legal age
 * Exit (): Bool
 */
function isAdult($birthDate, $legalAge){
	$adultDay = addDateToDate($birthDate, $legalAge);
	
	return isPreviousDate($adultDay);
}





/*************************************************************************
 * ********************  STRINGS RELATED FUNCTIONS  ******************** *
 *************************************************************************/


/* Erases/Strips/Removes any character in a string which contains any non-supported type of accent (if necessary)
 * Entry (incoming_string): String with accents
 * Exit: String without accents
 */
function dropAccents($incoming_string){
	$tofind = "ÀÁÂÄÅÃàáâäãÒÓÔÖÕòóôöõÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaOOOOOoooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return utf8_encode(strtr(utf8_decode($incoming_string), utf8_decode($tofind), $replac));
}



/* Checks whether a set of words/strings that previously were an array have been previously registered in DB
 * PRE: 'incomingString' is NOT empty
 * Entry (incomingString): Input string where every word that is intended to be registered in DB is
 * Entry (searchedTable): Table in which words/strings will be searched if they matches
 * Entry (keyColumn): Column used in 'searchedTable' to find out if each word is or not
 * Entry (delimiter): Character used to delimit imploded array (that now is an string)
 * Exit (): Boolean that indicates TRUE if every word in 'incomingString' is in searched DBTable or FALSE if not
 */
function isImplodedArrayInDB($incomingString, $searchedTable, $keyColumn, $delimiter){
	$auxArray = explode($delimiter, $incomingString);
	$i = 0;
	$arrayElems = count($auxArray);
	$isEqual = true;
	while($i<$arrayElems && $isEqual){
		//if($auxArray[$i] )
		if(!getDBsinglefield($keyColumn, $searchedTable, $keyColumn, $auxArray[$i])){
			$isEqual = false;
		}
		$i++;
	}
	return $isEqual;
}



/* Same as previous, but an exception is specified not to be compared
 * PRE: 'incomingString' is NOT empty
 * Entry (incomingString): Input string where every word that is intended to be registered in DB is
 * Entry (searchedTable): Table in which words/strings will be searched if they matches
 * Entry (keyColumn): Column used in 'searchedTable' to find out if each word is or not
 * Entry (delimiter): Character used to delimit imploded array (that now is an string)
 * Entry (exception): String/Word which is the exception, that won't be searched in 'searchedTable'
 * Exit (): Boolean that indicates TRUE if every word in 'incomingString' is in searched DBTable or FALSE if not
 */
function isImplodedArrayInDBExcept($incomingString, $searchedTable, $keyColumn, $delimiter, $exception){
	$auxArray = explode($delimiter, $incomingString);
	$i = 0;
	$arrayElems = count($auxArray);
	$isEqual = true;
	while($i<$arrayElems && $isEqual){
		if($auxArray[$i] != $exception){
			if(!getDBsinglefield($keyColumn, $searchedTable, $keyColumn, $auxArray[$i])){
				$isEqual = false;
			}
		}
		$i++;
	}
	return $isEqual;
}



/* Prepares a new login to be registered in DB, checking if it meets requirements
 * Entry (incomingLogin): Varchar that can includes non-supported characters in DB
 * Exit (): Varchar with no non-supported characters
 */
function normalizeLogin($incomingLogin){
	$aux = dropAccents($incomingLogin);
	if((strlen($aux) < 4) || (strlen($aux) > 16)){
		return 0;
	}
	else return $aux;
}



/* Prepares an input string to be registered in DB, checking if it meets requirements
 * Entry (inString): Varchar that can includes non-supported characters in DB
 * Exit (): Varchar with no non-supported characters
 */
function setStringAsKey($inString){
	$inString = str_replace(' ', '', $inString);
	$normalString = dropAccents($inString);
	if(strlen($normalString) < 1){
		return 0;
	}
	else return $normalString;
}



/* Erases non-supported characters. Eliminates inside blank spaces, and converts first word of key to lower case (Usually to save String in DB)
 * PRE: 
 * Entry (inArray): Input string in which there is a field that needs to be converted as key
 * Entry (keyPos): Integer where future key is. Use to be position 0 or 1
 * Exit (outArray): Output string, with brand new key generated
 */
function prepareArray($inArray){
	$numFields = count($inArray);
	for($i = 0; $i < $numFields; $i++){
		//Converts to uppercase first letter in every word in array-vector
		$inArray[$i] = ucwords($inArray[$i]);
	}
	$inArray[0] = setStringAsKey($inArray[0]);
	$inArray[0] = lcfirst($inArray[0]);
	return $inArray;
}





/**************************************************************************
 * ********************  TIMEDATE RELATED FUNCTIONS  ******************** *
 **************************************************************************/


/* Generates a future date from current date
 * Entry (monthsNumber): Integer which indicates the number of months to be added
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function addMonthsToDate($monthsNumber){
	$endDate = date('Y-m-d', strtotime('+'.$monthsNumber.' month'));
	return $endDate;
}



/* Calculates a future date adding X years to an input date
 * Entry (givenDate): Input given date in format 'YYYY-MM-DD'
 * Entry (years): Integer which indicates the number of years to be added
 * Entry (months): Integer which indicates the number of months to be added
 * Entry (days): Integer which indicates the number of days to be added
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function addDateToDate($givenDate, $years){
	$endDate = date('Y-m-d', strtotime("$givenDate + $years years"));
	return $endDate;
}



/* Checks whether an input string has MySQL DATE format (YYYY-MM-DD), confirming also that is not a future date
 * Entry (dateString): Input string where is supposed to be a date in format YYYY-MM-DD
 * Exit (): Bool
 */
function eregMySQLCheckDate($dateString){
	if(ereg("(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])", $dateString)){
		//return true;
		return isAdult($dateString, 18);
	}
	else{
		return false;
	}
}



/* Checks whether a given input date is well-formatted and is if it is also older than current date
 * Entry (prevDate): Date in format YYYY-MM-DD
 * Exit: Boolean that confirms if date is correct and older than current or not
 */
function isPreviousDate($prevDate){
	$auxDateArray = explode('-', $prevDate);
	$auxDateMonth = $auxDateArray[1];
	$auxDateYear = $auxDateArray[0];
	$auxDateDay = $auxDateArray[2];
	
	//Converting common dates to UNIX date to be compared each other
	$current = strtotime(date('Y-m-d'));
	$initDate = strtotime($prevDate);
	
	if((!checkdate($auxDateMonth, $auxDateDay, $auxDateYear)) || ($initDate > $current)){
		return false;
	}
	else{
		return true;
	}
}



/* Changes a given date to format "Y-m-d" (YYYY-MM-DD)
 * Entry (oldDate): String that includes the old format date
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function dateFormatToDB($oldDate){
	$endDate = date('Y-m-d', strtotime($oldDate));
	return $endDate;
}



/* Changes a given date (usually in DB) to format "d-m-Y" (DD-MM-YYYY). A common one to spanish people
 * Entry (oldDate): String that includes the old format date
 * Exit (endDate): Date in format "DD-MM-YYYY"
 */
function dateToSpanishFormat($oldDate){
	$endDate = date('d-m-Y', strtotime($oldDate));
	return $endDate;
}





/**************************************************************************
 * **************************  OTHER FUNCTIONS  ************************* *
 **************************************************************************/



/* Checks whether uploaded files (NON images) are valid or not
 * PRE: $_FILES must be isset() and file must be properly uploaded to TMP directory
 * Entry (fileName): Input resource which includes information about one file
 * Exit (errorText): Output text when something goes wrong 
 * Exit (): Bool
 */
function checkUploadedFileES($fileName, $fileType, $fileSize, &$errorText){
	$lowerCase = strtolower($fileName);
	//All these extensions will be the only-supported ones
	$whitelist = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'rtf');
	//$errorText = 'naaa';
	if(!in_array(end(explode('.', $lowerCase)), $whitelist)){
		$errorText = 'Tipo de ficheros no válido';
		//echo 'chunog';
		return false;
	}
	if($fileSize > 1048576){
		$errorText = 'El límite de tamaño para un fichero es de 1MB';
		//echo 'Chungo: ';
		return false;
	}
	$errorText ="";
	return true;
}
function checkUploadedFile($fileName, $fileType, $fileSize, &$errorText){
	$lowerCase = strtolower($fileName);
	//All these extensions will be the only-supported ones
	$whitelist = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'rtf');
	if(!in_array(end(explode('.', $lowerCase)), $whitelist)){
		$errorText = 'Error: Invalid file type.';
		return false;
	}
	if($fileSize > 1048576){
		$errorText = 'Error: 1MB file size exceeded.';
		return false;
	}
	$errorText ="";
	return true;
}



/* Gets the pending CVs number
 * Exit (singleDBfield): amount of pending CVs
 */
function getPendingCVs(){

	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT COUNT( * ) FROM cvitaes WHERE cvStatus = 'pending'") or die ("Error calculando el número de CVs pendientes: ".mysqli_error($connection));
	
	if (mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[0]; //Getting count (*) value
		mysqli_free_result($result);
		mysqli_close($connection);
		return $singleDBfield;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
	}
}



/* Checks whether a directory exists, creating it with given permissions if not
 * Entry (dir): String with complete path for directory
 * Entry (permits): Integer (in form 0XXX) that indicactes what permissions will have new directory
 */
function ifCreateDir($dir, $permits){
	if(!file_exists($dir)){
		if(!mkdir($dir, $permits)){
			return false;
		}
		elseif(!chmod($dir, $permits)){
			return false;
		}
		return true;
	}
	else
		return true;
}



?>
