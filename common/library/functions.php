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
	$connection = connectDB();

	$query = "DELETE FROM `$dbtable` WHERE `$primaryname`='$primaryvalue'";

	if(mysqli_query($connection, $query) or die("Error deleting DB registry: ".mysqli_error($connection))){
		mysqli_close($connection);
		return 1;
	}
	else{
		mysqli_close($connection);
	}
}



/* Executes a complete DB query sent by PHP code, whatever it would be
 * Entry ($query): Complete query sent from original code
 * Exit: Returns 1 if succesfully executed
 */
function executeDBquery($query){
	$connection = connectDB();

	if(mysqli_query($connection, $query) or die("Error in DB request: ".mysqli_error($connection))){
		mysqli_close($connection);
		return 1;
	}
	else {
		mysqli_close($connection);
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
	$connection = connectDB();
	$result = mysqli_query($connection, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error extracting matching array: ".mysqli_error($connection));
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



/* Returns all values in one column, ordered by especified ID
 * Entry (columnrequested): Name of the column which values want to be extracted
 * Entry (dbtable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBcompletecolumnID($columnrequested, $dbtable, $id){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `$columnrequested` FROM `$dbtable` ORDER BY `$id`") or die("Complete column extraction error: ".mysqli_error($connection));

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
	$connection = connectDB();
	$result = mysqli_query($connection, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error obtaining registry: ".mysqli_error($connection));
	if(mysqli_num_rows($result) <= 0 ){
		mysqli_free_result($result);
		mysqli_close($connection);
		return 0;
	}
	else{
		$fila = mysqli_fetch_array($result);
		mysqli_free_result($result);
		mysqli_close($connection);
		return $fila;
	}
}



/* Counts total number of rows in a table
 * Entry (dbtable): DB where wanted to know total number of registries
 * Exit (num_rows): Integer with number of rows
 */
function getDBrowsnumber($dbtable){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT COUNT(*) FROM `$dbtable`") or die("Error obtaining row's number: ".mysqli_error($connection));

	$num_rows = mysqli_fetch_array($result);
	mysqli_free_result($result);
	mysqli_close($connection);
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
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error obtaining single value: ".mysqli_error($connection));

	if (mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldrequested];
		mysqli_free_result($result);
		mysqli_close($connection);
		return $singleDBfield;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
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
	$connection = connectDB();
	$result = mysqli_query($connection, "SELECT `$fieldreq` FROM `$dbtable` WHERE `$fieldsup1`='$infosup1' AND `$fieldsup2`='$infosup2'") or die("Error obtaining single value: ".mysqli_error($connection));
	if(mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldreq];
		mysqli_free_result($result);
		mysqli_close($connection);
		return $singleDBfield; //Devuelve un string
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
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




/* Checks whether a list of nouns given in an input array are ALL found between the complete column 
 * (no quiere decir que el número de datos que hay en la columna de la tabla coincida con el número de palabras del input)
 * Entry (inputArray): Array that contains the list of words/nouns to be searched
 * Entry (dbTable): Table in which every word/noun in the array will be searched
 * Entry (dbColumn): Column where search will be done
 * Exit (): TRUE or FALSE
 */ 
function isAllArrayInColumn($inputArray, $dbTable, $dbColumn){
	foreach($inputArray as $i){
		if(!getDBsinglefield($dbColumn, $dbTable, $dbColumn, $i)){
			return false;
		}
	}
	return true;
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



/* Gets the 2 digits that identify the root for a language ('en', 'es'...). It takes no count on user, only filePath
 * Entry (filePath): String that contains full relative path (i.e. '/es/home/pendingCVs.php')
 * Exit (): String with the 2 digits that represents each language as per its filePath
 */
/*
function getLangDigits($filePath){
	if(strstr($filePath, "/en/")){
		return "en";
	}
	if(strstr($filePath, "/es/")){
		return "es";
	}
}
*/
function getLangDigits($filePath){
	//Elimino el primer carácter (que es "/")
	$langDigits = substr($filePath, 1);
	
	//Elimino todos los caracteres a la derecha del siguiente "/" (barra incluida)
	return strstr($langDigits, "/", true);
}



/* Gets current language for the php page displayed. Done comparing "de", "en" or "es" suffixes in file path
 * Entry (filePath): String where read/search the language suffix
 * Exit (keyLanguage): String with the language where php file is
 */
function getCurrentLanguage($filePath){
	if(strpos($filePath, "/de/") === 0){
		$lang = "german";
		return $lang;
	}
	if(strpos($filePath, "/en/") === 0){
		$lang = "english";
		return $lang;
	}
	if(strpos($filePath, "/es/") === 0){
		$lang = "spanish";
		return $lang;
	}
	$lang = "spanish";
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



/* Checks whether a given password matches every requirement when changed by a new one. And if it is different to previous password. Called from "personalData.php" and "validateFront.php"
 * Multilingual version
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Entry (hashedKey): String directly extracted from DB that contains old password
 * Entry (loggedUserLang): String that indicates language of the user which is trying to change its password
 * Exit (keyError): String with the error when needed (or void)
 */
function checkHashedPassChange($key1, $key2, $hashedKey, $loggedUserLang, &$keyError){
	switch ($loggedUserLang){
		case 'german':
			if($key1 != $key2){
				$keyError = "Fehler: Die Passwörter müssen übereinstimmen.";
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
			if(!(crypt($hashedKey, $key1) == $key1)){
				$keyError = "Fehler: Das passwort muss sich von der letzten unterscheiden.";
				return false;
			}
		break;
		
		case 'english':
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
			if(!(crypt($hashedKey, $key1) == $key1)){
				$keyError = "Error: Password must be different to last one.";
				return false;
			}
		break;
		
		default:
			if($key1 != $key2){
				$keyError = "Error: Ambas contraseñas deben ser iguales.";
				return false;
			}
			if(strlen($key1) < 6){
				$keyError = "Error: La contraseña debe tener al menos 6 caracteres.";
				return false;
			}
			if(strlen($key1) > 16){
				$keyError = "Error: La contraseña no puede tener más de 16 caracteres.";
				return false;
			}
			if(!preg_match('`[a-z]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos una letra minúscula.";
				return false;
			}
			if(!preg_match('`[A-Z]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos una letra mayúscula.";
				return false;
			}
			if(!preg_match('`[0-9]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos un caracter numérico.";
				return false;
			}
			if(crypt($key1, $hashedKey) == $hashedKey){
				$keyError = "Error: La contraseña debe ser distinta a la última.";
				return false;
			}
		break;
	}
	$keyError = "";
	return true;
}



/* Checks whether a given password is strong enough (and properly written) when changed for a new one. Called from "validateFront.php"
 * Multilingual version
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Entry (loggedUserLang): String that indicates language of the user which is trying to change its password
 * Exit (keyError): String with the error when needed (or void)
 */
function checkSimplePassChange($key1, $key2, $loggedUserLang, &$keyError){
	switch ($loggedUserLang){
		case 'german':
			if($key1 != $key2){
				$keyError = "Fehler: Beiden feldern stimmen nicht überein.";
				return false;
			}
			if(strlen($key1) < 6){
				$keyError = "Fehler: Das passwort muss mindestens 6 zeichen lang sein.";
				return false;
			}
			if(strlen($key1) > 16){
				$keyError = "Fehler: Das passwort darf nicht mehr als 16 zeichen sein.";
				return false;
			}
			if (!preg_match('`[a-z]`',$key1)){
				$keyError = "Fehler: Das passwort muss mindestens einen kleinbuchstaben.";
				return false;
			}
			if (!preg_match('`[A-Z]`',$key1)){
				$keyError = "Fehler: Das passwort muss mindestens einen großbuchstaben.";
				return false;
			}
			if (!preg_match('`[0-9]`',$key1)){
				$keyError = "Fehler: Das passwort muss mindestens ein numerisches Zeichen enthalten.";
				return false;
			}
		break;
		
		case 'english':
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
		break;
		
		default:
			if($key1 != $key2){
				$keyError = "Error: Ambos campos de contraseña no coinciden.";
				return false;
			}
			if(strlen($key1) < 6){
				$keyError = "Error: La contraseña debe tener al menos 6 caracteres.";
				return false;
			}
			if(strlen($key1) > 16){
				$keyError = "Error: La contraseña no puede tener más de 16 caracteres.";
				return false;
			}
			if (!preg_match('`[a-z]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos una letra minúscula.";
				return false;
			}
			if (!preg_match('`[A-Z]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos una letra mayúscula.";
				return false;
			}
			if (!preg_match('`[0-9]`',$key1)){
				$keyError = "Error: La contraseña debe tener al menos un caracter numérico.";
				return false;
			}
		break;
	}
	$keyError = "";
	return true;
}



/* Obtains a non-hashed password between the '$str' list, with 8 characters length
 * Exit (cad): String of 8 characters length
 */
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



/* Checks whether a Candidate is under legal age, and whether a birthdate is well formatted, avoiding as possible security breachs.
 * Called from 'pendingCVs.php'. Multilingual version.
 * Entry (inDate): String that contains birthdate
 * Entry (loggedUserLang): String that indicates language of the user whose address must be checked
 * Exit (outDate): Returned string for birthdate
 * Exit (checkError): String with text that includes a description of the error
 */
/*
function checkBirthdate($inDate, $loggedUserLang, &$outDate, &$checkError){
	if(eregMySQLCheckDate(htmlentities($inDate, ENT_QUOTES, 'UTF-8'))){
		$inDate = trim(htmlentities($inDate, ENT_QUOTES, 'UTF-8'));
	}
	$checkError = "";
	
	switch ($loggedUserLang){
		case 'german':
			if(!preg_match('/^d{4}-d{2}-d{2}/', $inDate)){
				$checkError = "Fehler: Datumsformat Ungültig.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Fehler: Das Datum zeigt an, dass der Kandidat nicht volljährig.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Fehler: Datum leer.";
				return false;
			}
		break;
		
		case 'english':
			if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $inDate)){
				$checkError = "Error: Invalid date format.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: Date indicates that Candidate is not over legal age.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Error: Empty date.";
				return false;
			}
			break;
		
		default:
			if(!preg_match('/^d{4}-d{2}-d{2}/', $inDate)){
				$checkError = "Error: Formato de fecha no válido.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: La fecha indica que el Candidato no es mayor de edad.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Error: Fecha vacía.";
				return false;
			}
			break;
	}
	$checkError = "";
	return true;
}
*/
/*
function checkBirthdate($inDate, $loggedUserLang, &$outDate, &$checkError){
	if(eregMySQLCheckDate(htmlentities($inDate, ENT_QUOTES, 'UTF-8'))){
		$inDate = trim(htmlentities($inDate, ENT_QUOTES, 'UTF-8'));
	}
	$checkError = "";
	
	switch ($loggedUserLang){
		case 'german':
			if(!checkDateYYYY_MM_DD($inDate)){
				$checkError = "Fehler: Datumsformat Ungültig.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Fehler: Das Datum zeigt an, dass der Kandidat nicht volljährig.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Fehler: Datum leer.";
				return false;
			}
		break;
		
		case 'english':
			if(!checkDateYYYY_MM_DD($inDate)){
				$checkError = "Error: Invalid date format.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: Date indicates that Candidate is not over legal age.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Error: Empty date.";
				return false;
			}
			break;
		
		default:
			if(!checkDateYYYY_MM_DD($inDate)){
				$checkError = "Error: Formato de fecha no válido.";
				return false;
			}
			if(!isAdult($inDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: La fecha indica que el Candidato no es mayor de edad.";
				return false;
			}
			if($inDate == '0000-00-00'){
				$checkError = "Error: Fecha vacía.";
				return false;
			}
			break;
	}
	$checkError = "";
	return true;
}
*/
function checkBirthdate($inDate, $loggedUserLang, &$outDate, &$checkError){
	if(eregMySQLCheckDate(htmlentities($inDate, ENT_QUOTES, 'UTF-8'))){
		//$connection = connectDB();
		//$outDate = trim(htmlentities(mysli_real_escape_string($connection, $inDate), ENT_QUOTES, 'UTF-8'));
		$outDate = trim(htmlentities($inDate, ENT_QUOTES, 'UTF-8'));
	}
	$checkError = "";
	
	switch ($loggedUserLang){
		case 'german':
			if(!checkDateYYYY_MM_DD($outDate)){
				$checkError = "Fehler: Datumsformat Ungültig.";
				return false;
			}
			if(!isAdult($outDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Fehler: Das Datum zeigt an, dass der Kandidat nicht volljährig.";
				return false;
			}
			if($outDate == '0000-00-00'){
				$checkError = "Fehler: Datum leer.";
				return false;
			}
		break;
		
		case 'english':
			if(!checkDateYYYY_MM_DD($outDate)){
				$checkError = "Error: Invalid date format.";
				return false;
			}
			if(!isAdult($outDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: Date indicates that Candidate is not over legal age.";
				return false;
			}
			if($outDate == '0000-00-00'){
				$checkError = "Error: Empty date.";
				return false;
			}
			break;
		
		default:
			if(!checkDateYYYY_MM_DD($outDate)){
				$checkError = "Error: Formato de fecha no válido.";
				return false;
			}
			if(!isAdult($outDate, getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
				$checkError = "Error: La fecha indica que el Candidato no es mayor de edad.";
				return false;
			}
			if($outDate == '0000-00-00'){
				$checkError = "Error: Fecha vacía.";
				return false;
			}
			break;
	}
	$checkError = "";
	return true;
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




/* Checks whether both Driving License fields are properly fullfilled. Called from 'upload.php'
 * Multilingual version.
 * Entry (type): String which indicates type of license (A, B...)
 * Entry (licDate): Date in format YYYY-MM-DD
 * Entry (loggedUserLang): String that indicates language of user whose driving license must be checked
 * Exit: Boolean
 */
function checkDrivingLicense($type, $licDate, $loggedUserLang, &$checkError){
	switch ($loggedUserLang){
		case 'german':
			if((strlen($type) > 0) && (strlen($licDate) == 0)){
				$checkError = "Fehler: führerschein nicht angegeben Datum.";
				return false;
			}
			elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
				$checkError = "Fehler: Typ des führerschein nicht angegeben.";
				return false;
			}
			elseif(!isPreviousDate($licDate)){
				$checkError = "Fehler: Datum der führerschein kann nicht zukunft sein.";
				return false;
			}
		break;
		
		case 'english':
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
		break;
		
		default:
			if((strlen($type) > 0) && (strlen($licDate) == 0)){
				$checkError = "Error: Fecha de obtención del permiso de conducir no indicada.";
				return false;
			}
			elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
				$checkError = "Error: Tipo de permiso de conducir no indicado.";
				return false;
			}
			elseif(!isPreviousDate($licDate)){
				$checkError = "Error: La fecha de permiso de conducir no puede ser futura.";
				return false;
			}
		break;
	}
	$checkError = "";
	return true;
}




/* Checks whether a complete Education (Tittle, Center, Start and End) is OK or KO.
 * Entry (eTittle): String which represents Education's tittle
 * Entry (eCenter): String which represents Education's center
 * Entry (eStart): String that indicates start DATE
 * Entry (eEnd): String that indicates end DATE
 * Entry (loggedUserLang): String that indicates language of the user whose address must be checked
 * Exit (checkError): String with descriptive error text, when something is wrong
 */
/*
function checkEducation($eTittle, $eCenter, $eStart, $eEnd, $loggedUserLang, &$checkError){
	switch ($loggedUserLang){
		case 'german':
			if((strlen($eTittle) < 6) || (strlen($eCenter) < 6)){
				$checkError = "Fehler: Titel oder Anstalt sollte mehr als 6 Zeichen haben.";
				return false;
			}
			if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eStart)){
				$checkError = "Fehler: Das Format des Anfangsdatum Ausbildungen ist falsch.";
				return false;
			}
			if((strlen($eEnd) > 0) && (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eEnd))){
				$checkError = "Fehler: Das Format des Enddatum Ausbildungen ist falsch.";
				return false;
			}
			if(!isStartPreviousToEndDate($eStart, $eEnd)){
				$checkError = "Fehler: Die Anfangsdatum Ausbildungen ist neuer als das Enddatum.";
				return false;
			}
		break;
		
		case 'english':
			if((strlen($eTittle) < 6) || (strlen($eCenter) < 6)){
				$checkError = "Error: Tittle or Center must have more than 6 characters.";
				return false;
			}
			if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eStart)){
				$checkError = "Error: Initial Date format is incorrect in one of the educations.";
				return false;
			}
			if((strlen($eEnd) > 0) && (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eEnd))){
				$checkError = "Error: End Date format is incorrect in one of the educations.";
				return false;
			}
			if(!isStartPreviousToEndDate($eStart, $eEnd)){
				$checkError = "Error: Initial date in one of the educations is newer than its End date.";
				return false;
			}
		break;
		
		default:
			if((strlen($eTittle) < 6) || (strlen($eCenter) < 6)){
				$checkError = "Error: Título o Centro deben tener más de 6 caracteres.";
				return false;
			}
			if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eStart)){
				$checkError = "Error: El formato de la Fecha Inicial de una de las educaciones es incorrecto.";
				return false;
			}
			if((strlen($eEnd) > 0) && (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $eEnd))){
				$checkError = "Error: El formato de la Fecha Final de una de las educaciones es incorrecto.";
				return false;
			}
			if(!isStartPreviousToEndDate($eStart, $eEnd)){
				$checkError = "Error: La Fecha inicial de una de las educaciones es más reciente que su Fecha final.";
				return false;
			}
		break;
	}
	$checkError = "";
	return true;
}
*/
function checkEducation($eTittle, $eCenter, $eStart, $eEnd, $loggedUserLang, &$checkError){
	$arrayTittle = explode("|", $eTittle);
	$arrayCenter = explode("|", $eCenter);
	$arrayStart = explode("|", $eStart);
	$arrayEnd = explode("|", $eEnd);
	
	$contArrays = count($arrayTittle);
	
	switch ($loggedUserLang){
		case 'german':
			//foreach($arrayTittle as $i){
			for($i=0; $i<$contArrays; $i++){
				if((strlen($arrayTittle[$i]) < 6) || (strlen($arrayCenter[$i]) < 6)){
					$checkError = "Fehler: Titel oder Anstalt sollte mehr als 6 Zeichen haben.";
					return false;
				}
				if(!checkDateYYYY_MM_DD($arrayStart[$i])){
					$checkError = "Fehler: Das Format des Anfangsdatum Ausbildungen ist falsch.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!checkDateYYYY_MM_DD($arrayEnd[$i]))){
					$checkError = "Fehler: Das Format des Enddatum Ausbildungen ist falsch.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!isStartPreviousToEndDate($arrayStart[$i], $arrayEnd[$i]))){
					$checkError = "Fehler: Die Anfangsdatum Ausbildungen ist neuer als das Enddatum.";
					return false;
				}
			}
		break;
		
		case 'english':
			for($i=0; $i<$contArrays; $i++){
				if((strlen($arrayTittle[$i]) < 6) || (strlen($arrayCenter[$i]) < 6)){
					$checkError = "Error: Tittle or Center must have more than 6 characters.";
					return false;
				}
				if(!checkDateYYYY_MM_DD($arrayStart[$i])){
					$checkError = "Error: Initial Date format is incorrect in one of the educations.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!checkDateYYYY_MM_DD($arrayEnd[$i]))){
					$checkError = "Error: End Date format is incorrect in one of the educations.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!isStartPreviousToEndDate($arrayStart[$i], $arrayEnd[$i]))){
					$checkError = "Error: Initial date in one of the educations is newer than its End date.";
					return false;
				}
			}
		break;
		
		default:
			for($i=0; $i<$contArrays; $i++){
				if((strlen($arrayTittle[$i]) < 6) || (strlen($arrayCenter[$i]) < 6)){
					$checkError = "Error: Título o Centro deben tener más de 6 caracteres.";
					return false;
				}
				if(!checkDateYYYY_MM_DD($arrayStart[$i])){
					$checkError = "Error: El formato de la Fecha Inicial de una de las educaciones es incorrecto.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!checkDateYYYY_MM_DD($arrayEnd[$i]))){
					$checkError = "Error: El formato de la Fecha Final de una de las educaciones es incorrecto.";
					return false;
				}
				if((strlen($arrayEnd[$i]) > 0) && (!isStartPreviousToEndDate($arrayStart[$i], $arrayEnd[$i]))){
					$checkError = "Error: La Fecha inicial de una de las educaciones es más reciente que su Fecha final.";
					return false;
				}
			}
		break;
	}
	$checkError = "";
	return true;
}

	


/* Checks whether a complete address (Name and Number for this Form) are well-formatted, avoiding as possible security breachs. Called from 'upload.php'
 * Multilingual version.
 * Entry (inName): String which contains name of the address
 * Entry (inNumber): String which contains number/letter for given address name
 * Entry (loggedUserLang): String that indicates language of the user whose address must be checked
 * Exit (outAddrName): Returned string for Address Name
 * Exit (outAddrNumber): Returned string for Address Number
 * Exit (checkError): String with text that includes a description of the error */
function checkFullAddress($inName, $inNumber, $loggedUserLang, &$outAddrName, &$outAddrNumber, &$checkError){
	$connection = connectDB();
	
	$outAddrName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outAddrNumber = trim(htmlentities(mysqli_real_escape_string($connection, $inNumber), ENT_QUOTES, 'UTF-8'));
	
	switch ($loggedUserLang){
		case 'german':
			if(strlen($outAddrName) < 2){
				$checkError = "Fehler: Ungültige adresse.";
				return false;
			}
			elseif(strlen($outAddrNumber) < 1){
				$checkError = "Fehler: Die in den gültigen angegebene anzahl.";
				return false;
			}
		break;
		
		case 'english':
			if(strlen($outAddrName) < 2){
				$checkError = "Error: Invalid address.";
				return false;
			}
			elseif(strlen($outAddrNumber) < 1){
				$checkError = "Error: Address number not provided or not valid.";
				return false;
			}
		break;
		
		default:
			if(strlen($outAddrName) < 2){
				$checkError = "Error: Dirección no válida.";
				return false;
			}
			elseif(strlen($outAddrNumber) < 1){
				$checkError = "Error: Número no indicado o no válido";
				return false;
			}
		break;
	}
	$checkError = "";
	return true;
}




/* Checks whether a Name & Surname are both correct to be saved in a DB. Called from 'upload.php', 'pendingCVs.php' and 'checkedCVs.php'
 * Entry (inName): Input string for Name
 * Entry (inSurname): Input string for Surname
 * Entry (loggedUserLang): String that indicates the language of the user whose name must be checked
 * Exit (outName): Returned string for Name
 * Exit (outSurname): Returned string for Surname
 * Exit (checkError): String with text that includes a description of the error
 */
function checkFullName($inName, $inSurname, $loggedUserLang, &$outName, &$outSurname, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName), ENT_QUOTES, 'UTF-8'));
	$outSurname = trim(htmlentities(mysqli_real_escape_string($connection, $inSurname), ENT_QUOTES, 'UTF-8'));
	
	switch ($loggedUserLang){
		case 'german':
			if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
				$checkError = "Fehler: Namen und Nachname müssen mindestens 3 zeichen sein.";
				return false;
			}
		break;
		
		case 'english':
			if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
				$checkError = "Error: Name and Surname must be at least 3 characters each.";
				return false;
			}
		break;
		
		default:
			if((strlen($outName) < 3) || (strlen($outSurname) < 3)){
				$checkError = "Error: Nombre y Apellidos deben tener al menos 3 caracteres cada uno.";
				return false;
			}
		break;
	}
	
	$checkError = "";
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
 * Entry (inNations): Input STRING acting as an ARRAY with 1 or more nationalities
 * Exit (outNations): Output STRING acting as an ARRAY for nationalities
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



/* Checks whether a phone number is valid or not (00XX-XXXXXXXXXXXXX)
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



/* Generates a future date from current date
 * Entry (monthsNumber): Integer which indicates the number of months to be added
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function addMonthsToDate($monthsNumber){
	$endDate = date('Y-m-d', strtotime('+'.$monthsNumber.' month'));
	return $endDate;
}



/* Checks whether an input string has MySQL DATE format (YYYY-MM-DD).
 * Entry (dateString): Input string where is supposed to be a date in format YYYY-MM-DD
 * Exit (): Boolean
 */
function checkDateYYYY_MM_DD($dateString){
	return ereg("(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])", $dateString);
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



/* Checks whether 2 given dates (both in YYYY-MM-DD format) are well-formatted and if Start Date is previous to End Date.
 * PRE: Both dates (that really are strings) MUST be in YYYY-MM-DD format.
 * Entry (inStartDate): String that represents Date in format YYYY-MM-DD
 * Entry (inEndDate): String that represents Date in format YYYY-MM-DD
 * Exit: TRUE if Start Date is previous (older) to End Date. FALSE if not.
 */
function isStartPreviousToEndDate($inStartDate, $inEndDate){
	$auxStartDate = strtotime($inStartDate);
	$auxEndDate = strtotime($inEndDate);
	
	if($auxStartDate < $auxEndDate){
		return true;
	}
	else{
		return false;
	}
}





/**************************************************************************
 * **************************  OTHER FUNCTIONS  ************************* *
 **************************************************************************/



/* Checks if user's profile is granted to acceed to the script/file loaded as per folder permissions established in each table (i.e. 'home' or 'administration')
 * Entry (filePath): String that contains full relative path (i.e. '/es/home/pendingCVs.php')
 * Entry (myFile): String that indicates the name of the folder the script is in
 * Entry (userProfile): String with user's profile
 */
/*
function accessGranted($filePath, $myFile, $userProfile){
	$fileName = getPhpFileName($filePath);
	echo 'fileName vale '.$fileName.'<br>';
	//Si se trata de un fichero excepción (ser excepción significa que no tienen su correspondiente columna en ninguna tabla de BBDD), devuelvo 'true', sin comprobar
	if(($fileName == 'searchResult') || ($fileName == 'viewCV')){
		if(($userProfile == 'SuperAdmin') || ($userProfile == 'Administrador') || ($userProfile == 'Lector')){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return getDBsinglefield($fileName, $myFile, 'profile', $userProfile);
	}
}
*/
/* Esta función tiene problemas <NO CHUTA> al comprobar los ficheros de nivel 1 (home y administration). Por lo que, para evitar que falle dicha comprobación, debo establecer una excepción más:
 * A home.php debo dejarle acceso a todo el mundo, sea el perfil que sea
 * A administration.php no hay problema, porque dicho fichero redirige automáticamente a admGenOptions.php, fichero que sí está controlado dinámicamente
 */
function accessGranted($filePath, $myFile, $userProfile){
	$fileName = getPhpFileName($filePath);
	//Si se trata de un fichero excepción (ser excepción significa que no tienen su correspondiente columna en ninguna tabla de BBDD), devuelvo 'true', sin comprobar
	if(($fileName == 'searchResult') || ($fileName == 'viewCV')){
		if(($userProfile == 'SuperAdmin') || ($userProfile == 'Administrador') || ($userProfile == 'Lector')){
			return true;
		}
		else{
			return false;
		}
	}
	elseif($fileName == 'home'){
		return true;
	}
	else{
		return getDBsinglefield($fileName, $myFile, 'profile', $userProfile);
	}
}




/* Function in charge of calling internally to any other function and DDBB query involved in creating a new Candidate
 * Entry (newUser): String with the name/key for the new Candidate to be inserted/created in the system
 * Entry (loggedUserLang): Language of the 'Administrador' that is creating new Candidate. Used to know the language in which error messages must be reported to emerging window
 * Exit (addError): Output error text when something goes wrong
 */
function addCandidate($newUser, $loggedUserLang, &$addError){
	$nextUserNumber = getNextCandidateNumber();
	//Se podría controlar el error de cada comando o función a ejecutar (a futuro)
	executeDBquery("UPDATE `otherOptions` SET `value`='".$nextUserNumber."' WHERE `key`='lastCandidate'");
	$candProfNumUsers = getDBsinglefield('numUsers', 'profiles', 'name', 'Candidato');
	$candProfNumUsers += 1;
	executeDBquery("UPDATE `profiles` SET `numUsers`='".$candProfNumUsers."' WHERE `name`='Candidato'");
	//Creating newUser's folder to store his/her data when updating his/her CV
	$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$newUser."/";
	if(!ifCreateDir($userDir, 0777)){
		switch($loggedUserLang) {
			case 'german':
				$addError = "Fehler beim Benutzer Verzeichnissystem erstellen (ADCUUSERDIR)";
				return false;
			break;
			
			case 'english':
				$addError = "Error creating Candidate\'s directories system (ADCUUSERDIR)";
				return false;
			break;
			
			default:
				$addError = "Error al crear el sistema de directorios del Candidato (ADCUUSERDIR)";
				return false;
			break;
		}
	}
	$addError = "";
	return true;
}



/* Checks whether uploaded files (NON images) are valid or not. COMENTADO DE MOMENTO EN 'upload.php'
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



/* Gets from DDBB number of the last Candidate generated, and adds 1, in order to now which is the next number to be used to generate a new Candidate
 * PRE: N/A
 * Exit (): Integer
 */
function getNextCandidateNumber(){
	$nextCandidateNumber = getDBsinglefield('value', 'otherOptions', 'key', 'lastCandidate');
	$nextCandidateNumber = $nextCandidateNumber+1;
	
	return $nextCandidateNumber;
}



/* Creates name for the next Candidate to be used by the APP
 * Exit (): String with key/code/name of the new generated Candidate
 */
function getNextCandidateName(){
	//Makes number to be completed with '0' at the left side of the number
	$nextCandidateNumber=sprintf("%06d",getNextCandidateNumber());
	$nextCandidateName="pa_".$nextCandidateNumber;
	
	return $nextCandidateName;
}



/* Gets the name for 'myFile' to make this variable dynamic
 * Entry (filePath): String that contains full relative path, from 'myFile' name could be reached
 * Entry (userCurProject): String that contains key name of user's project 
 * Exit (): String that corresponds to 'myFile' name
 */
function getMyFile($filePath){
	//2 language digits are extracted
	$lang = getLangDigits($filePath);
	
	//Now, universal path is reached (i.e. "es/home.php")
	$langPath = strstr($filePath, $lang);
	
	//Language digits does not matter here. We delete them (i.e. "es/")
	$noLangPath = substr($langPath, 3);
	
	//Si es "/es/home/myFiles.php" será...
	//If path in here is something like "home/myFiles.php" we cut the string from the next "/" and on
	if($myFile = strstr($noLangPath, "/", true)){
		return $myFile;
	}
	//Si es "/es/home.php" será...
	//If path in here is sth like "home.php" we cut the string from the "." and on
	elseif($myFile = strstr($noLangPath, ".", true)){
		return $myFile;
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



/* Gets the name (and ONLY the name, without any path) of the PHP script
 * Entry (filePath): String that contains full relative path (i.e. "/es/home/pendingCVs.php")
 * Exit (): String that only contains the name of PHP file (i.e. "pendingCVs.php")
 * PRE: filePath must not be empty
 */
function getPhpFileName($filePath){
	//'mb_strrchr' Finds the last occurrence of a character in a string and returns the right part of the string
	//'substr' Returns part of a string. In this case returns all the string except the first character and excepts the last 4 characters
	
	//return substr(mb_strrchr($filePath, "/"), 1);
	return substr(mb_strrchr($filePath, "/"), 1, -4);
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
