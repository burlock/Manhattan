<?php
session_start();

if (!$_SESSION['loglogin']){
	?>
	<script type="text/javascript">
		window.location.href='/en/index.html';
	</script>
	<?php
}
else {
	/*******  Nationalities/Countries treatment previously to be saved in DDBB  *******/
	$wholeNationalities = explode("|", $_POST['eCCVnationalities']);
	$securedNats = securizeArray($wholeNationalities);
	
	$natIDs = getDBcolumnvalue('id', 'userCountries', 'userNIE', $_POST['eCCVnie']);
	$j = 0;
	foreach($securedNats as $i){
		//Checking that new Nationality is valid as per 'countries' table
		//ESTO ES NECESARIO PORQUE, PARA QUE SE PUDIERAN LISTAR LOS PAISES POR ORDEN ALFABETICO ESTANDO ESPAÑA LA 1ª ERA NECESARIO SACARLA DE LA TABLA
		if(getDBsinglefield('german', 'countries', 'german', $i) || $i == 'Spanien'){
			//If that nationality does not appear inside table for that userNIE must be inserted
			if(!getDBsinglefield2('keyCountry', 'userCountries', 'userNIE', $_POST['eCCVnie'], 'keyCountry', $i)){
				executeDBquery("INSERT INTO `userCountries` (`userNIE`, `keyCountry`) VALUES ('".$_POST['eCCVnie']."', '".$i."')");
			}
		}
		//If new hand-written nationality does not exists, error must be returned
		else{
			switch ($userRow['language']){
				case 'german':
					?>
					<script type="text/javascript">
						alert('Fehler: Mindestens einer der Nationalitäten ist nicht richtig geschrieben.');
						window.location.href='/de/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					?>
					<script type="text/javascript">
						alert('Error: At least 1 of the nationalities is not properly written.');
						window.location.href='/en/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
				
				default:
					?>
					<script type="text/javascript">
						alert('Error: Al menos 1 de las nacionalidades no está debidamente escrita.');
						window.location.href='/es/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
			}
		}
	}//foreach
	
	//LUEGO RECORRO EL VIEJO Y ELIMINARÉ EL 'language' Y 'langLevel' DEL VIEJO QUE NO ESTEN EN EL NUEVO
	foreach($natIDs as $i){
		if(!in_array(getDBsinglefield('keyCountry', 'userCountries', 'id', $i), $securedNats)){
			executeDBquery("DELETE FROM `userCountries` WHERE `id` = '".$i."'");
		}
	}
	/*  -----------------  End of Natinalities/Countries Treatment  ----------------  */
	
	
	/*****  Language and Language level's treatment previous to be saved in DDBB  *****/
	//Unmounting each "Lang:LangLv" group of elements as an array (explode converts a string to an array)
	$wholeLangInfo = explode('|',$_POST['eCCVlanguagesMerged']);
	
	$langsArray = array();
	$langLevelsArray = array();
	$i = 0;
	foreach($wholeLangInfo as $key => $value){
		//Separating each $wholeLangInfo position into 2 new arrays: 1st array for language names; 2nd for language levels
		$auxArray = explode(':', $value);
		$langsArray[$i] = $auxArray[0];
		$langLevelsArray[$i] = $auxArray[1];
		$i++;
	}
	$securedLangs = securizeArray($langsArray);
	$securedLangLevels = securizeArray($langLevelsArray);
	
	//TENDRE 2 VECTORES DE 'languages', UNO EL DE ENTRADA NUEVO, Y OTRO EL QUE YA ESTUVIERA.
	//TENGO QUE RECORRER EL NUEVO Y COMPARARLO CON EL VIEJO. SI EL IDIOMA DEL NUEVO ESTABA YA EN EL VIEJO, COMPARO SI SUS 'langLevel' TAMBIEN COINCIDEN PARA MODIFICARLO O NO
	$langIDs = getDBcolumnvalue('id', 'userLanguages', 'userNIE', $_POST['eCCVnie']);
	$j = 0;
	foreach($securedLangs as $i){
		//Checking that new language is valid as per 'languages' table
		if(getDBsinglefield('key', 'languages', 'key', $i)){
			if($prevLang = getDBrow2('userLanguages', 'userNIE', $_POST['eCCVnie'], 'keyLanguage', $i)){
				if($prevLang['level'] != $securedLangLevels[$j]){
					executeDBquery("UPDATE `userLanguages` SET `level` = ' ".$securedLangLevels['$j'] ."' WHERE `userNIE` = '".$_POST['eCCVnie']."' AND `keyLanguage` = '".$i."'");
				}
			}
			else{
				executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST['eCCVnie']."', '".$i."', '".$securedLangLevels[$j]."')");
			}
			$j++;
		}
		//If new hand-written language does not exist, error must be returned
		else{
			switch ($userRow['language']){
				case 'german':
					?>
					<script type="text/javascript">
						alert('Fehler: Mindestens einer der Sprachen nicht richtig geschrieben.');
						window.location.href='/de/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					?>
					<script type="text/javascript">
						alert('Error: At least 1 of the languages is not properly written.');
						window.location.href='/en/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
				
				default:
					?>
					<script type="text/javascript">
						alert('Error: Al menos 1 de los idiomas no está debidamente escrito.');
						window.location.href='/es/home/checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				break;
			}
		}
	}//foreach
	
	//LUEGO RECORRO EL VIEJO Y ELIMINARÉ EL 'language' Y 'langLevel' DEL VIEJO QUE NO ESTEN EN EL NUEVO
	foreach($langIDs as $i){
		if(!in_array(getDBsinglefield('keyLanguage', 'userLanguages', 'id', $i), $securedLangs)){
			executeDBquery("DELETE FROM `userLanguages` WHERE `id` = '".$i."'");
		}
	}
	/*  ----------  End of Language and Language level's Treatment  ----------  */
	
	
	/************  Education's Treatment previous to be saved in DDBB  *************/
	$educIDs = getDBcolumnvalue('id', 'userEducations', 'userNIE', $_POST['eCCVnie']);
	for($i=0; $i<$_POST['eCCVcontEduc']; $i++){
		$securedEducTittle = securizeString($_POST["eCCVeducTittle$i"]);
		$securedEducCenter = securizeString($_POST["eCCVeducCenter$i"]);
		$securedEducStart = securizeString($_POST["eCCVeducStart$i"]);
		$securedEducEnd = securizeString($_POST["eCCVeducEnd$i"]);
		
		if(!checkEducation($securedEducTittle, $securedEducCenter, $securedEducStart, $securedEducEnd, $userRow['language'], $checkError)){
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
			</script>
			<?php 
		}
		else{
			$prevEducRow = getDBrow('userEducations', 'id', $educIDs[$i]);
			if(!(($securedEducTittle == $prevEducRow['educTittle']) && ($securedEducCenter == $prevEducRow['educCenter']) && 
			($securedEducStart == $prevEducRow['educStart']) &&($securedEducEnd == $prevEducRow['educEnd']))){
				executeDBquery("UPDATE `userEducations` SET
					`userNIE` = '".$_POST["eCCVnie"]."', 
					`educTittle` = '".$securedEducTittle."', 
					`educCenter` = '".$securedEducCenter."', 
					`educStart` = '".$securedEducStart."', 
					`educEnd` = '".$securedEducEnd."', 
				WHERE `userNIE` = '".$_POST['eCCVnie']."' AND `id` = '".$educIDs[$i]."'");
			}
		}
	}
	/*  --------------------  End of Education's Treatment  --------------------  */
	
	
	/*******  Careers/Occupations treatment previously to be saved in DDBB  *******/
	//COMO QUIERO HACER QUE PA NOS DIGA QUE NO QUIERE PROFESIONES (para qué, habiendo Estudios y Experiencias) NO VOY A ASEGURARME QUE LOS DATOS SON COHERENTES, TAN SOLO QUE NO DAÑAN LA BD
	$wholeOccupations = explode("|", $_POST['eCCVcareer']);
	$securedOccupations = securizeArray($wholeOccupations);
	
	$occupIDs = getDBcolumnvalue('id', 'userOccupations', 'userNIE', $_POST['eCCVnie']);
	foreach($securedOccupations as $i){
		//PODRIA COMPROBAR SI TIENE TAMAÑO MINIMO, PERO PASANDO.
		//If form occupation does not appear as one of user's occupations, it will be inserted
		if(!getDBsinglefield2('keyOccupation', 'userOccupations', 'userNIE', $_POST['eCCVnie'], 'keyOccupation', $i)){
			executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST['eCCVnie']."', '".$i."')");
		}
	}
	
	//After that, must be checked if any previous occupation should be deleted from 'userOccupations' table
	foreach($occupIDs as $i){
		if(!in_array(getDBsinglefield('keyOccupation', 'userOccupations', 'id', $i), $securedOccupations)){
			executeDBquery("DELETE FROM `userOccupations` WHERE `id` = '".$i."'");
		}
	}
	
	/*  ----------------  End of Careers/Occupations Treatment  ----------------  */
	
	
	/************  Experience's Treatment previous to be saved in DDBB  ************/
	$expIDs = getDBcolumnvalue('id', 'userExperiences', 'userNIE', $_POST['eCCVnie']);
	for($i=0; $i<$_POST['eCCVcontExp']; $i++){
		$securedExperCompany = securizeString($_POST["eCCVexperCompany$i"]);
		$securedExperPos = securizeString($_POST["eCCVexperPos$i"]);
		$securedExperStart = securizeString($_POST["eCCVexperStart$i"]);
		$securedExperEnd = securizeString($_POST["eCCVexperEnd$i"]);
		$securedExperCity = securizeString($_POST["eCCVexperCity$i"]);
		$securedExperCountry = securizeString($_POST["eCCVexperCountry$i"]);
		$securedExperDesc = securizeString($_POST["eCCVexperDesc$i"]);
		
		$prevExperRow = getDBrow('userExperiences', 'id', $expIDs[$i]);
		if(!(($securedExperCompany == $prevExperRow['company']) && ($securedExperPos == $prevExperRow['position']) && 
		($securedExperStart == $prevExperRow['start']) && ($securedExperEnd == $prevExperRow['end']) && 
		($securedExperCity == $prevExperRow['city']) && ($securedExperCountry == $prevExperRow['country']) && ($securedExperDesc == $prevExperRow['description']))){
			executeDBquery("UPDATE `userExperiences` SET 
				`userNIE` = '".$_POST["eCCVnie"]."', 
				`company` = '".$securedExperCompany."', 
				`position` = '".$securedExperPos."', 
				`start` = '".$securedExperStart."', 
				`end` = '".$securedExperEnd."', 
				`city` = '".$securedExperCity."', 
				`country` = '".$securedExperCountry."',
				`description` = '".$securedExperDesc."' 
			WHERE `userNIE` = '".$_POST['eCCVnie']."' AND `id` = '".$expIDs[$i]."'");
		}
	}
	/*  --------------------  End of Experience's Treatment  --------------------  */
}
?>