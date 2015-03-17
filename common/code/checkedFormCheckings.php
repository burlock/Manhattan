<?php
session_start();

if (!$_SESSION['loglogin']){
	/*
	?>
	<script type="text/javascript">
		window.location.href='/en/index.html';
	</script>
	<?php
	*/
	?>
	<script type="text/javascript">
		window.location.href='/<?php echo getUserLangDigits($userRow[language]); ?>/index.html';
	</script>
	<?php
}
else {
	/*******  Nationalities/Countries checkings previously to be saved in DDBB  *******/
	$wholeNationalities = explode("|", $_POST['eCCVnationalities']);
	$securedNats = securizeArray($wholeNationalities);
	
	//$natIDs = getDBcolumnvalue('id', 'userCountries', 'userNIE', $_POST['eCCVnie']);
	$natIDs = getDBcolumnvalue('idNat', 'userNationalities', 'userNIE', $_POST['eCCVnie']);
	$j = 0;
	foreach($securedNats as $i){
		//Checking that new Nationality is valid as per 'countries' table
		//ESTO ES NECESARIO PORQUE, PARA QUE SE PUDIERAN LISTAR LOS PAISES POR ORDEN ALFABETICO ESTANDO ESPAÑA LA 1ª ERA NECESARIO SACARLA DE LA TABLA
		if(getDBsinglefield('german', 'countries', 'german', $i) || $i == 'Spanien'){
			//If that nationality does not appear inside table for that userNIE must be inserted
			/*
			if(!getDBsinglefield2('keyCountry', 'userCountries', 'userNIE', $_POST['eCCVnie'], 'keyCountry', $i)){
				executeDBquery("INSERT INTO `userCountries` (`userNIE`, `keyCountry`) VALUES ('".$_POST['eCCVnie']."', '".$i."')");
			}
			*/
			if(!getDBsinglefield2('keyCountry', 'userNationalities', 'userNIE', $_POST['eCCVnie'], 'keyCountry', $i)){
				executeDBquery("INSERT INTO `userNationalities` (`userNIE`, `keyCountry`) VALUES ('".$_POST['eCCVnie']."', '".$i."')");
			}
		}
		//If new hand-written nationality does not exists, error must be returned
		else{
			switch ($userRow['language']){
				case 'german':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Fehler: Mindestens einer der Nationalitäten ist nicht richtig geschrieben.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error: At least 1 of the nationalities is not properly written.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				default:
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error: Al menos 1 de las nacionalidades no está debidamente escrita.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
			}
		}
	}//foreach
	
	//LUEGO RECORRO EL VIEJO Y ELIMINARÉ EL 'language' Y 'langLevel' DEL VIEJO QUE NO ESTEN EN EL NUEVO
	foreach($natIDs as $i){
		/*
		if(!in_array(getDBsinglefield('keyCountry', 'userCountries', 'id', $i), $securedNats)){
			executeDBquery("DELETE FROM `userCountries` WHERE `id` = '".$i."'");
		}
		*/
		if(!in_array(getDBsinglefield('keyCountry', 'userNationalities', 'idNat', $i), $securedNats)){
			executeDBquery("DELETE FROM `userNationalities` WHERE `idNat` = '".$i."'");
		}
	}
	/*  -----------------  End of Natinalities/Countries checkings  ----------------  */
	
	
	/*****  Language and Language level's checkings previous to be saved in DDBB  *****/
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
	$langIDs = getDBcolumnvalue('idLan', 'userLanguages', 'userNIE', $_POST['eCCVnie']);
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
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Fehler: Mindestens einer der Sprachen nicht richtig geschrieben.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error: At least 1 of the languages is not properly written.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				default:
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error: Al menos 1 de los idiomas no está debidamente escrito.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
			}
		}
	}//foreach
	
	//LUEGO RECORRO EL VIEJO Y ELIMINARÉ EL 'language' Y 'langLevel' DEL VIEJO QUE NO ESTEN EN EL NUEVO
	foreach($langIDs as $i){
		if(!in_array(getDBsinglefield('keyLanguage', 'userLanguages', 'idLan', $i), $securedLangs)){
			executeDBquery("DELETE FROM `userLanguages` WHERE `idLan` = '".$i."'");
		}
	}
	/*  ----------  End of Language and Language level's checkings  ----------  */
	
	
	/************  Education's Treatment previous to be saved in DDBB  *************/
	$educIDs = getDBcolumnvalue('idEdu', 'userEducations', 'userNIE', $_POST['eCCVnie']);
	for($i=0; $i<$_POST['eCCVcontEduc']; $i++){
		$securedEducTittle = securizeString($_POST["eCCVeducTittle$i"]);
		$securedEducCenter = securizeString($_POST["eCCVeducCenter$i"]);
		$securedEducStart = securizeString($_POST["eCCVeducStart$i"]);
		$securedEducEnd = securizeString($_POST["eCCVeducEnd$i"]);
		
		if(!checkEducation($securedEducTittle, $securedEducCenter, $securedEducStart, $securedEducEnd, $userRow['language'], $checkError)){
			unset($_POST['eCurCVsend']);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
			</script>
			<?php 
		}
		else{
			$prevEducRow = getDBrow('userEducations', 'idEdu', $educIDs[$i]);
			if(!(($securedEducTittle == $prevEducRow['educTittle']) && ($securedEducCenter == $prevEducRow['educCenter']) && 
			($securedEducStart == $prevEducRow['educStart']) &&($securedEducEnd == $prevEducRow['educEnd']))){
				executeDBquery("UPDATE `userEducations` SET
					`userNIE` = '".$_POST["eCCVnie"]."', 
					`educTittle` = '".$securedEducTittle."', 
					`educCenter` = '".$securedEducCenter."', 
					`educStart` = '".$securedEducStart."', 
					`educEnd` = '".$securedEducEnd."'
				WHERE `userNIE` = '".$_POST['eCCVnie']."' AND `idEdu` = '".$educIDs[$i]."'");
			}
		}
	}
	/*  --------------------  End of Education's checkings  --------------------  */
	
	
	/*******  Careers/Occupations checkings previously to be saved in DDBB  *******/
	//COMO QUIERO HACER QUE PA NOS DIGA QUE NO QUIERE PROFESIONES (para qué, habiendo Estudios y Experiencias) NO VOY A ASEGURARME QUE LOS DATOS SON COHERENTES, TAN SOLO QUE NO DAÑAN LA BD
	$wholeOccupations = explode("|", $_POST['eCCVcareer']);
	$securedOccupations = securizeArray($wholeOccupations);
	
	$occupIDs = getDBcolumnvalue('idOcc', 'userOccupations', 'userNIE', $_POST['eCCVnie']);
	foreach($securedOccupations as $i){
		//PODRIA COMPROBAR SI TIENE TAMAÑO MINIMO, PERO PASANDO.
		//If form occupation does not appear as one of user's occupations, it will be inserted
		if(!getDBsinglefield2('keyOccupation', 'userOccupations', 'userNIE', $_POST['eCCVnie'], 'keyOccupation', $i)){
			executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST['eCCVnie']."', '".$i."')");
		}
	}
	
	//After that, must be checked if any previous occupation should be deleted from 'userOccupations' table
	foreach($occupIDs as $i){
		if(!in_array(getDBsinglefield('keyOccupation', 'userOccupations', 'idOcc', $i), $securedOccupations)){
			executeDBquery("DELETE FROM `userOccupations` WHERE `idOcc` = '".$i."'");
		}
	}
	
	/*  ----------------  End of Careers/Occupations checkings  ----------------  */
	
	
	/************  Experience's Treatment previous to be saved in DDBB  ************/
	$expIDs = getDBcolumnvalue('idExp', 'userExperiences', 'userNIE', $_POST['eCCVnie']);
	for($i=0; $i<$_POST['eCCVcontExp']; $i++){
		$securedExperCompany = securizeString($_POST["eCCVexperCompany$i"]);
		$securedExperPos = securizeString($_POST["eCCVexperPos$i"]);
		$securedExperStart = securizeString($_POST["eCCVexperStart$i"]);
		$securedExperEnd = securizeString($_POST["eCCVexperEnd$i"]);
		$securedExperCity = securizeString($_POST["eCCVexperCity$i"]);
		$securedExperCountry = securizeString($_POST["eCCVexperCountry$i"]);
		$securedExperDesc = securizeString($_POST["eCCVexperDesc$i"]);
		
		$prevExperRow = getDBrow('userExperiences', 'idExp', $expIDs[$i]);
		/*
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
		*/
		if(!(($securedExperCompany == $prevExperRow['expCompany']) && ($securedExperPos == $prevExperRow['expPosition']) && 
		($securedExperStart == $prevExperRow['expStart']) && ($securedExperEnd == $prevExperRow['expEnd']) && 
		($securedExperCity == $prevExperRow['expCity']) && ($securedExperCountry == $prevExperRow['expCountry']) && ($securedExperDesc == $prevExperRow['expDescription']))){
			executeDBquery("UPDATE `userExperiences` SET 
				`userNIE` = '".$_POST["eCCVnie"]."', 
				`expCompany` = '".$securedExperCompany."', 
				`expPosition` = '".$securedExperPos."', 
				`expStart` = '".$securedExperStart."', 
				`expEnd` = '".$securedExperEnd."', 
				`expCity` = '".$securedExperCity."', 
				`expCountry` = '".$securedExperCountry."',
				`expDescription` = '".$securedExperDesc."' 
			WHERE `userNIE` = '".$_POST['eCCVnie']."' AND `idExp` = '".$expIDs[$i]."'");
		}
	}
	/*  --------------------  End of Experience's checkings  --------------------  */
	
	$inDBMobile = trim(htmlentities($_POST['eCCVmobile'], ENT_QUOTES, 'UTF-8'));
	
	if(!checkFullName($_POST['eCCVname'], $_POST['eCCVsurname'], $userRow['language'], $outName, $outSurname, $checkError)){
		unset($_POST['eCurCVsend']);
		?>
		<script type="text/javascript">
			alert('<?php echo $checkError; ?>');
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php 
	}
	
	elseif(!checkBirthdate($_POST['eCCVbirthdate'], $userRow['language'], $outDate, $checkError)){
		unset($_POST['eCurCVsend']);
		?>
		<script type="text/javascript">
			alert('<?php echo $checkError; ?>');
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php 
	}
	
	// Relajación de las Restricciones del Móvil, según correo del 22/01
	//elseif(!checkPhone($inDBMobile)){
	elseif(!checkPhoneM($inDBMobile, $userRow['language'], $checkError)){
		unset($_POST['eCurCVsend']);
		?>
		<script type="text/javascript">
			alert('<?php echo $checkError; ?>');
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php 
	}
	
	//elseif(!filter_var(htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL)){
	//elseif(!checkMailM($_POST[eCCVmail], $userRow[language], $checkError)){
	//elseif(!checkMailM($_POST['eCCVmail'], $userRow['language'], $checkError)){
	/*
	elseif(!checkMailM($_POST[eCCVmail], $userRow[language], $checkError)){
		unset($_POST['eCurCVsend']);
		?>
		<script type="text/javascript">
			alert('<?php echo $checkError; ?>');
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php
	}
	*/
	elseif(!filter_var(htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL)){
		switch ($userRow['language']){
			case 'german':
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Fehler: Die Email ist nicht richtig geschrieben.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
			
			case 'english':
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Error: Email is not valid.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
			
			default:
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Error: El Email no es válido.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
		}
	}
	
	//elseif(!strlen($_POST['eCCVcandidateStatus']) > 0){
	/*
	elseif(!checkCandidateStatusM($_POST['eCCVcandidateStatus'], $userRow[language], $checkError)){
		unset($_POST['eCurCVsend']);
		?>
		<script type="text/javascript">
			alert('<?php echo $checkError; ?>');
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php 
	}
	*/
	elseif(!strlen($_POST['eCCVcandidateStatus']) > 0){
		switch($loggedUserLang){
			case 'german':
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Fehler: Keine Sonderstellung für den Kandidaten.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
			
			case 'english':
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Error: A Status for the Candidate was not selected.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
			
			default:
				unset($_POST['eCurCVsend']);
				?>
				<script type="text/javascript">
					alert('Error: No se ha seleccionado Estado para el Candidato.');
					window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
				</script>
				<?php 
			break;
		}
	}
	
	/* Incluimos esta comprobación, a priori innecesaria, porque si se produce un error en "pendingFormCheckings.php" que debiera impedir la grabación del CV, 
	 * por la razón que sea, no aborta, provocando que el CV se valide aún teniendo errores.
	 */
	elseif(!isset($_POST['eCurCVsend'])){
		?>
		<script type="text/javascript">
			window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
		</script>
		<?php 
	}
	
	//After all checkings, every data is saved/modified
	else{
		$inDBOtherPhone = trim(htmlentities($_POST['eCCVphone'], ENT_QUOTES, 'UTF-8'));
		if(!checkPhone($inDBOtherPhone)){
			$inDBOtherPhone = '';
		}
		/*
		$updateCVQuery = "UPDATE `cvitaes` SET 
			`nie` = '".$_POST['eCCVnie']."',
			`cvStatus` = 'checked',
			`name` = '".$outName."',
			`surname` = '".$outSurname."',
			`birthdate` = '".$outDate."',
			`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
			`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
			`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
			`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
			`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
			`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
			`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
			`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
			`phone` = '".$inDBOtherPhone."',
			`postalCode` = '".htmlentities($_POST['eCCVpostal'], ENT_QUOTES, 'UTF-8')."',
			`country` = '".htmlentities($_POST['eCCVcountry'], ENT_QUOTES, 'UTF-8')."',
			`province` = '".htmlentities($_POST['eCCVprovince'], ENT_QUOTES, 'UTF-8')."',
			`city` = '".htmlentities($_POST['eCCVcity'], ENT_QUOTES, 'UTF-8')."',
			`mobile` = '".$inDBMobile."',
			`mail` = '".htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8')."',
			`drivingType` = '".htmlentities($_POST['eCCVdrivingType'], ENT_QUOTES, 'UTF-8')."',
			`drivingDate` = '".htmlentities($_POST['eCCVdrivingDate'], ENT_QUOTES, 'UTF-8')."',
			`marital` = '".htmlentities($_POST['eCCVmarital'], ENT_QUOTES, 'UTF-8')."',
			`sons` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
			`otherDetails` = '".htmlentities($_POST['eCCVotherDetails'], ENT_QUOTES, 'UTF-8')."',
			`skill1` = '".htmlentities($_POST['eCCVskill1'], ENT_QUOTES, 'UTF-8')."',
			`skill2` = '".htmlentities($_POST['eCCVskill2'], ENT_QUOTES, 'UTF-8')."',
			`skill3` = '".htmlentities($_POST['eCCVskill3'], ENT_QUOTES, 'UTF-8')."',
			`skill4` = '".htmlentities($_POST['eCCVskill4'], ENT_QUOTES, 'UTF-8')."',
			`skill5` = '".htmlentities($_POST['eCCVskill5'], ENT_QUOTES, 'UTF-8')."',
			`skill6` = '".htmlentities($_POST['eCCVskill6'], ENT_QUOTES, 'UTF-8')."',
			`skill7` = '".htmlentities($_POST['eCCVskill7'], ENT_QUOTES, 'UTF-8')."',
			`skill8` = '".htmlentities($_POST['eCCVskill8'], ENT_QUOTES, 'UTF-8')."',
			`skill9` = '".htmlentities($_POST['eCCVskill9'], ENT_QUOTES, 'UTF-8')."',
			`skill10` = '".htmlentities($_POST['eCCVskill10'], ENT_QUOTES, 'UTF-8')."',
			`cvDate` = '".htmlentities($_POST['eCCVcvDate'], ENT_QUOTES, 'UTF-8')."',
			`salary` = '".htmlentities($_POST['eCCVsalary'], ENT_QUOTES, 'UTF-8')."',
			`comments` = '".htmlentities($_POST['eCCVcomments'], ENT_QUOTES, 'UTF-8')."',
			`candidateStatus` = '".$_POST['eCCVcandidateStatus']."'
		WHERE `nie` = '".$_POST['eCCVnie']."';";
		*/
		switch (getMyFile($_SERVER[SCRIPT_NAME])){
			case 'pausedCVs':
				$updateCVQuery = "UPDATE `cvitaes` SET 
					`nie` = '".$_POST['eCCVnie']."',
					`cvStatus` = 'paused',
					`name` = '".$outName."',
					`surname` = '".$outSurname."',
					`birthdate` = '".$outDate."',
					`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
					`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
					`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
					`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
					`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
					`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
					`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
					`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
					`phone` = '".$inDBOtherPhone."',
					`postalCode` = '".htmlentities($_POST['eCCVpostal'], ENT_QUOTES, 'UTF-8')."',
					`country` = '".htmlentities($_POST['eCCVcountry'], ENT_QUOTES, 'UTF-8')."',
					`province` = '".htmlentities($_POST['eCCVprovince'], ENT_QUOTES, 'UTF-8')."',
					`city` = '".htmlentities($_POST['eCCVcity'], ENT_QUOTES, 'UTF-8')."',
					`mobile` = '".$inDBMobile."',
					`mail` = '".htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8')."',
					`drivingType` = '".htmlentities($_POST['eCCVdrivingType'], ENT_QUOTES, 'UTF-8')."',
					`drivingDate` = '".htmlentities($_POST['eCCVdrivingDate'], ENT_QUOTES, 'UTF-8')."',
					`marital` = '".htmlentities($_POST['eCCVmarital'], ENT_QUOTES, 'UTF-8')."',
					`sons` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
					`otherDetails` = '".htmlentities($_POST['eCCVotherDetails'], ENT_QUOTES, 'UTF-8')."',
					`skill1` = '".htmlentities($_POST['eCCVskill1'], ENT_QUOTES, 'UTF-8')."',
					`skill2` = '".htmlentities($_POST['eCCVskill2'], ENT_QUOTES, 'UTF-8')."',
					`skill3` = '".htmlentities($_POST['eCCVskill3'], ENT_QUOTES, 'UTF-8')."',
					`skill4` = '".htmlentities($_POST['eCCVskill4'], ENT_QUOTES, 'UTF-8')."',
					`skill5` = '".htmlentities($_POST['eCCVskill5'], ENT_QUOTES, 'UTF-8')."',
					`skill6` = '".htmlentities($_POST['eCCVskill6'], ENT_QUOTES, 'UTF-8')."',
					`skill7` = '".htmlentities($_POST['eCCVskill7'], ENT_QUOTES, 'UTF-8')."',
					`skill8` = '".htmlentities($_POST['eCCVskill8'], ENT_QUOTES, 'UTF-8')."',
					`skill9` = '".htmlentities($_POST['eCCVskill9'], ENT_QUOTES, 'UTF-8')."',
					`skill10` = '".htmlentities($_POST['eCCVskill10'], ENT_QUOTES, 'UTF-8')."',
					`cvDate` = '".htmlentities($_POST['eCCVcvDate'], ENT_QUOTES, 'UTF-8')."',
					`salary` = '".htmlentities($_POST['eCCVsalary'], ENT_QUOTES, 'UTF-8')."',
					`comments` = '".htmlentities($_POST['eCCVcomments'], ENT_QUOTES, 'UTF-8')."',
					`candidateStatus` = '".$_POST['eCCVcandidateStatus']."'
				WHERE `nie` = '".$_POST['eCCVnie']."';";
			break;
			
			//default is currently for both 'pendingCVs' and 'checkedCVs' files
			default:
				$updateCVQuery = "UPDATE `cvitaes` SET 
					`nie` = '".$_POST['eCCVnie']."',
					`cvStatus` = 'checked',
					`name` = '".$outName."',
					`surname` = '".$outSurname."',
					`birthdate` = '".$outDate."',
					`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
					`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
					`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
					`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
					`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
					`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
					`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
					`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
					`phone` = '".$inDBOtherPhone."',
					`postalCode` = '".htmlentities($_POST['eCCVpostal'], ENT_QUOTES, 'UTF-8')."',
					`country` = '".htmlentities($_POST['eCCVcountry'], ENT_QUOTES, 'UTF-8')."',
					`province` = '".htmlentities($_POST['eCCVprovince'], ENT_QUOTES, 'UTF-8')."',
					`city` = '".htmlentities($_POST['eCCVcity'], ENT_QUOTES, 'UTF-8')."',
					`mobile` = '".$inDBMobile."',
					`mail` = '".htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8')."',
					`drivingType` = '".htmlentities($_POST['eCCVdrivingType'], ENT_QUOTES, 'UTF-8')."',
					`drivingDate` = '".htmlentities($_POST['eCCVdrivingDate'], ENT_QUOTES, 'UTF-8')."',
					`marital` = '".htmlentities($_POST['eCCVmarital'], ENT_QUOTES, 'UTF-8')."',
					`sons` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
					`otherDetails` = '".htmlentities($_POST['eCCVotherDetails'], ENT_QUOTES, 'UTF-8')."',
					`skill1` = '".htmlentities($_POST['eCCVskill1'], ENT_QUOTES, 'UTF-8')."',
					`skill2` = '".htmlentities($_POST['eCCVskill2'], ENT_QUOTES, 'UTF-8')."',
					`skill3` = '".htmlentities($_POST['eCCVskill3'], ENT_QUOTES, 'UTF-8')."',
					`skill4` = '".htmlentities($_POST['eCCVskill4'], ENT_QUOTES, 'UTF-8')."',
					`skill5` = '".htmlentities($_POST['eCCVskill5'], ENT_QUOTES, 'UTF-8')."',
					`skill6` = '".htmlentities($_POST['eCCVskill6'], ENT_QUOTES, 'UTF-8')."',
					`skill7` = '".htmlentities($_POST['eCCVskill7'], ENT_QUOTES, 'UTF-8')."',
					`skill8` = '".htmlentities($_POST['eCCVskill8'], ENT_QUOTES, 'UTF-8')."',
					`skill9` = '".htmlentities($_POST['eCCVskill9'], ENT_QUOTES, 'UTF-8')."',
					`skill10` = '".htmlentities($_POST['eCCVskill10'], ENT_QUOTES, 'UTF-8')."',
					`cvDate` = '".htmlentities($_POST['eCCVcvDate'], ENT_QUOTES, 'UTF-8')."',
					`salary` = '".htmlentities($_POST['eCCVsalary'], ENT_QUOTES, 'UTF-8')."',
					`comments` = '".htmlentities($_POST['eCCVcomments'], ENT_QUOTES, 'UTF-8')."',
					`candidateStatus` = '".$_POST['eCCVcandidateStatus']."'
				WHERE `nie` = '".$_POST['eCCVnie']."';";
			break;
		}

		if((!executeDBquery($updateCVQuery))){
			/*
			?>
			<script type="text/javascript">
				alert('Fehler beim prüfen CV.');
				window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
			</script>
			<?php
			*/
			switch ($userRow['language']){
				case 'german':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Fehler beim prüfen CV.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error checking CV.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
				
				default:
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('Error revisando CV.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
					</script>
					<?php 
				break;
			}
		}
		else{
			//Once CV has been updated, it is checked if any file needs to be uploaded...
			if($_FILES[candidatFiles][name][0]){
				$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".getDBsinglefield(userLogin, cvitaes, nie, $_POST[eCCVnie])."/";
				if(ifCreateDir($userDir, 0777)){
					//Every uploaded file is checked like if it was an array
					for($i=0; $i<count($_FILES[candidatFiles][name]); $i++){
						//Now files are checked about their restrictions to be uploaded
						if(checkUploadedFileES($_FILES[candidatFiles][name][$i], $_FILES[candidatFiles][type][$i], $_FILES[candidatFiles][size][$i], $errorText) && is_uploaded_file($_FILES[candidatFiles][tmp_name][$i])){
							$_FILES[candidatFiles][name][$i] = str_replace(" ","_",$_FILES[candidatFiles][name][$i]);
							if(!move_uploaded_file($_FILES[candidatFiles][tmp_name][$i], $userDir.$_FILES[candidatFiles][name][$i])){
								/*
								?>
								<script type="text/javascript">
									alert('Fehler CCVFUPLOAD02 beim Speichern der Datei.');
									window.location.href='$_SERVER[SCRIPT_NAME]';
								</script>
								<?php
								*/
								switch ($userRow['language']){
									case 'german':
										unset($_POST['eCurCVsend']);
										?>
										<script type="text/javascript">
											alert('Fehler CCVFUPLOAD02 beim Speichern der Datei.');
											window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
										</script>
										<?php 
									break;
									
									case 'english':
										unset($_POST['eCurCVsend']);
										?>
										<script type="text/javascript">
											alert('Error CCVFUPLOAD02 saving file.');
											window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
										</script>
										<?php 
									break;
									
									default:
										unset($_POST['eCurCVsend']);
										?>
										<script type="text/javascript">
											alert('Error CCVFUPLOAD02 al guardar el archivo.');
											window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
										</script>
										<?php 
									break;
								}
							}
						}
						else{
							/*
							?>
							<script type="text/javascript">
								alert('Error CCVFUPLOAD01: <?php echo $errorText; ?>');
								window.location.href='$_SERVER[SCRIPT_NAME]';
							</script>
							<?php
							*/
							switch ($userRow['language']){
								case 'german':
									unset($_POST['eCurCVsend']);
									?>
									<script type="text/javascript">
										alert('Error CCVFUPLOAD01: <?php echo $errorText; ?>.');
										window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
									</script>
									<?php 
								break;
								
								case 'english':
									unset($_POST['eCurCVsend']);
									?>
									<script type="text/javascript">
										alert('Error CCVFUPLOAD01: <?php echo $errorText; ?>.');
										window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
									</script>
									<?php 
								break;
								
								default:
									unset($_POST['eCurCVsend']);
									?>
									<script type="text/javascript">
										alert('Error CCVFUPLOAD01: <?php echo $errorText; ?>.');
										window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>?codvalue=<?php echo $_POST['eCCVnie']; ?>';
									</script>
									<?php 
								break;
							}
						}
					}
				}
			}
			/*
			?>
			<script type="text/javascript">
				alert('CV erfolgreich überarbeitet.');
				window.location.href='$_SERVER[SCRIPT_NAME]';
			</script>
			<?php
			*/
			switch ($userRow['language']){
				case 'german':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('CV erfolgreich überarbeitet.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>';
					</script>
					<?php 
				break;
				
				case 'english':
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('CV checked successfully.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>';
					</script>
					<?php 
				break;
				
				default:
					unset($_POST['eCurCVsend']);
					?>
					<script type="text/javascript">
						alert('CV revisado satisfactoriamente.');
						window.location.href='<?php echo $_SERVER[SCRIPT_NAME]; ?>';
					</script>
					<?php 
				break;
			}
		}
	}
}
?>