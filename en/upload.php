<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Form Validation</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="../common/js/functions.js"></script>
	
	<?php include $_SERVER[DOCUMENT_ROOT] . '/common/code/uploadHeadScripts.php'; ?>
</head>


<body>

<?php
	require_once($_SERVER[DOCUMENT_ROOT] . '/common/library/functions.php');
	require_once($_SERVER[DOCUMENT_ROOT] . '/common/library/SimpleImage.php');
	
	
	/* ***********************************    Start of FORM validations    *********************************** */
	
	if(isset($_POST[push_button])){
		
		//DESDE AQUI PARA LLEVAR A FICHERO APARTE
		/*
		$_SESSION[cvBirthdate] = $_POST[cvBirthdate];
		unset($_POST[cvBirthdate]);
		$_SESSION[h_cvLang] = $_POST[h_cvLang];
		unset($_POST[h_cvLang]);
		$_SESSION[h_cvLangLevel] = $_POST[h_cvLangLevel];
		unset($_POST[h_cvLangLevel]);
		$_SESSION[h_cvEducTittle] = $_POST[h_cvEducTittle];
		unset($_POST[h_cvEducTittle]);
		$_SESSION[h_cvEducCenter] = $_POST[h_cvEducCenter];
		unset($_POST[h_cvEducCenter]);
		$_SESSION[h_cvEducStart] = $_POST[h_cvEducStart];
		unset($_POST[h_cvEducStart]);
		$_SESSION[h_cvEducEnd] = $_POST[h_cvEducEnd];
		unset($_POST[h_cvEducEnd]);
		$_SESSION[h_cvCareer] = $_POST[h_cvCareer];
		unset($_POST[h_cvCareer]);
		$_SESSION[h_cvExpCompany] = $_POST[h_cvExpCompany];
		unset($_POST[h_cvExpCompany]);
		$_SESSION[h_cvExpPosition] = $_POST[h_cvExpPosition];
		unset($_POST[h_cvExpPosition]);
		$_SESSION[h_cvExpStart] = $_POST[h_cvExpStart];
		unset($_POST[h_cvExpStart]);
		$_SESSION[h_cvExpEnd] = $_POST[h_cvExpEnd];
		unset($_POST[h_cvExpEnd]);
		$_SESSION[h_cvExpCity] = $_POST[h_cvExpCity];
		unset($_POST[h_cvExpCity]);
		$_SESSION[h_cvExpCountry] = $_POST[h_cvExpCountry];
		unset($_POST[h_cvExpCountry]);
		$_SESSION[h_cvExpDescription] = $_POST[h_cvExpDescription];
		unset($_POST[h_cvExpDescription]);
		*/
		$_SESSION[cvBirthdate] = $_POST[cvBirthdate];
		unset($_POST[cvBirthdate]);
		$_SESSION[h_cvEducTittle] = securizeArray($_POST[h_cvEducTittle]);
		unset($_POST[h_cvEducTittle]);
		$_SESSION[h_cvEducCenter] = securizeArray($_POST[h_cvEducCenter]);
		unset($_POST[h_cvEducCenter]);
		$_SESSION[h_cvEducStart] = securizeArray($_POST[h_cvEducStart]);
		unset($_POST[h_cvEducStart]);
		$_SESSION[h_cvEducEnd] = securizeArray($_POST[h_cvEducEnd]);
		unset($_POST[h_cvEducEnd]);
		$_SESSION[h_cvCareer] = $_POST[h_cvCareer];
		unset($_POST[h_cvCareer]);
		$_SESSION[h_cvExpCompany] = securizeArray($_POST[h_cvExpCompany]);
		unset($_POST[h_cvExpCompany]);
		$_SESSION[h_cvExpPosition] = securizeArray($_POST[h_cvExpPosition]);
		unset($_POST[h_cvExpPosition]);
		$_SESSION[h_cvExpStart] = securizeArray($_POST[h_cvExpStart]);
		unset($_POST[h_cvExpStart]);
		$_SESSION[h_cvExpEnd] = securizeArray($_POST[h_cvExpEnd]);
		unset($_POST[h_cvExpEnd]);
		$_SESSION[h_cvExpCity] = securizeArray($_POST[h_cvExpCity]);
		unset($_POST[h_cvExpCity]);
		$_SESSION[h_cvExpCountry] = securizeArray($_POST[h_cvExpCountry]);
		unset($_POST[h_cvExpCountry]);
		$_SESSION[h_cvExpDescription] = securizeArray($_POST[h_cvExpDescription]);
		unset($_POST[h_cvExpDescription]);
		
		//Every form field that could be filled with text should be securized before being saved to DDBB
		//$securedBirthdate = securizeString($_POST[cvBirthdate]);
		$securedBirthdate = securizeString($_SESSION[cvBirthdate]);
		$securedPostal = securizeString($_POST[cvAddrPostalCode]);
		$securedCity = securizeString($_POST[cvAddrCity]);
		$securedProvince = securizeString($_POST[cvAddrProvince]);
		$securedCountry = securizeString($_POST[cvAddrCountry]);
		$securedMail = securizeString($_POST[cvMail]);
		$securedDrivingDate = securizeString($_POST[cvDrivingDate]);
		$securedOther = securizeString($_POST[cvOther]);
		$securedSkill1 = securizeString($_POST[cvskill1]);
		$securedSkill2 = securizeString($_POST[cvskill2]);
		$securedSkill3 = securizeString($_POST[cvskill3]);
		$securedSkill4 = securizeString($_POST[cvskill4]);
		$securedSkill5 = securizeString($_POST[cvskill5]);
		$securedSkill6 = securizeString($_POST[cvskill6]);
		$securedSkill7 = securizeString($_POST[cvskill7]);
		$securedSkill8 = securizeString($_POST[cvskill8]);
		$securedSkill9 = securizeString($_POST[cvskill9]);
		$securedSkill10 = securizeString($_POST[cvskill10]);
		
		//This first validation lets the system avoid double-recording of the registry if form is refreshed by Candidate via 'CMD+R' or 'F5' in his/her keyboard
		if(getDBsinglefield(cvSaved, users, login, $_SESSION[loglogin])){
			//If CV had been previously saved user will be blocked and sent to loggin page
			executeDBquery("UPDATE `users` SET `active`='0', `cvSaved`='1' WHERE `login`='".$_SESSION[loglogin]."'");
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('<?php echo "Usted ya ha guardado su CV. Por seguridad, su usuario ha sido desactivado."; ?>');
				window.location.href='./endsession.php';
			</script>
			<?php 
		}
		//Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano
		elseif(!checkFullNameES($_POST[cvName], $_POST[cvSurname], $outName, $outSurname, $checkError)){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				history.back();
			</script>
			<?php 
		}
		//elseif(!isAdult($_POST[cvBirthdate], getDBsinglefield(value, otherOptions, key, legalAge))){
		elseif(!isAdult($_SESSION[cvBirthdate], getDBsinglefield(value, otherOptions, key, legalAge))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: La fecha indica que no es mayor de edad o es incorrecta.');
				history.back();
			</script>
			<?php 
		}
		elseif(!checkDNI_NIE($_POST[cvnie])){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: Revise el NIE. El indicado es incorrecto.');
				history.back();
			</script>
			<?php 
		}
		elseif(getDBsinglefield(nie, cvitaes, nie, $_POST[cvnie])){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: El NIE introducido ya existe en Base de Datos.');
				history.back();
			</script>
			<?php 
		}
		//Sex and Nationality are automatically detected as restricted fields
		
		//Address won't be mandatory but, if included, will be necessary to fulfill 'type', 'name' and 'number'
		elseif(((strlen($_POST[cvAddrType]) > 0) || (strlen($_POST[cvAddrName]) > 0) || (strlen($_POST[cvAddrNum]) > 0) || (strlen($_POST[cvAddrPortal]) > 0) || 
		(strlen($_POST[cvAddrStair]) > 0) || (strlen($_POST[cvAddrFloor]) > 0) || (strlen($_POST[cvAddrDoor]) > 0)) && 
		((strlen($_POST[cvAddrType]) < 1) || (strlen($_POST[cvAddrName]) < 1) || (strlen($_POST[cvAddrNum]) < 1))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: No se ha indicado tipo, nombre o número en la dirección.');
				history.back();
			</script>
			<?php
		}
		//Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano
		elseif(((strlen($_POST[cvAddrType]) > 0) || (strlen($_POST[cvAddrName]) > 0) || (strlen($_POST[cvAddrNum]) > 0) || (strlen($_POST[cvAddrPortal]) > 0) || 
		(strlen($_POST[cvAddrStair]) > 0) || (strlen($_POST[cvAddrFloor]) > 0) || (strlen($_POST[cvAddrDoor]) > 0)) && 
		(!checkFullAddressES($_POST[cvAddrName], $_POST[cvAddrNum], $outAddrName, $outAddrNumber, $checkError))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				history.back();
			</script>
			<?php
		}
		//Relajación de la restricción del móvil según correo del 22/01
		elseif((strlen($_POST[cvMobile]) < 1) || (!checkPhone($_POST[cvMobile]))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: El número de móvil es incorrecto.');
				history.back();
			</script>
			<?php 
		}
		elseif((strlen($_POST[cvPhone]) > 0) && (!checkPhone($_POST[cvPhone]))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: El número de teléfono adicional es incorrecto.');
				history.back();
			</script>
			<?php 
		}
		elseif(!filter_var($_POST[cvMail], FILTER_VALIDATE_EMAIL)){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Error: Revise el correo electrónico. Formato incorrecto.');
				history.back();
			</script>
			<?php 
		}
		//Language and Language level are automatically detected as restricted fields
		
		//Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano
		elseif(((strlen($_POST[cvDrivingType]) > 0) || (strlen($_POST[cvDrivingDate]) > 0)) && (!checkDrivingLicenseES($_POST[cvDrivingType], $_POST[cvDrivingDate], $checkError))){
			unset($_POST[push_button]);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				history.back();
			</script>
			<?php 
		}
		
		//Only if EVERY field checking is OK, the procedure to save CV can be started. Nationalities, Languages, Educations, Experiences and Careers are saved in different tables
		else{
			/*
			$insertCVQuery = "INSERT INTO `cvitaes` (`nie`, `cvStatus`, `name`, `surname`, `birthdate`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, `phone`, 
			`postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, 
			`skill7`, `skill8`, `skill9`, `skill10`, `cvDate`, `userLogin`, `salary`) VALUES 
			('".$_POST[cvnie]."', 'pending', '".$outName."', '".$outSurname."', '".$_POST[cvBirthdate]."', '".$_POST[cvSex]."', '".$_POST[cvAddrType]."', '".$outAddrName."', '".$outAddrNumber."', 
			'".$_POST[cvAddrPortal]."', '".$_POST[cvAddrStair]."', '".$_POST[cvAddrFloor]."', '".$_POST[cvAddrDoor]."', '".$_POST[cvPhone]."', '".$_POST[cvAddrPostalCode]."', '".$strCountry."', 
			'".$_POST[cvAddrProvince]."', '".$_POST[cvAddrCity]."', '".$_POST[cvMobile]."', '".$_POST[cvMail]."', '".$_POST[cvDrivingType]."', '".$_POST[cvDrivingDate]."', 
			'".$_POST[cvMarital]."', '".$_POST[cvSons]."', '".$securedOther."', '".$securedSkill1."', '".$securedSkill2."', '".$securedSkill3."', '".$securedSkill4."', '".$securedSkill5."', 
			'".$securedSkill6."', '".$securedSkill7."', '".$securedSkill8."', '".$securedSkill9."', '".$securedSkill10."', CURRENT_TIMESTAMP, '".$_SESSION[loglogin]."', '".$_POST[cvSalary]."')";
			*/
			/*
			$insertCVQuery = "INSERT INTO `cvitaes` (`nie`, `cvStatus`, `name`, `surname`, `birthdate`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, `phone`, 
			`postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, 
			`skill7`, `skill8`, `skill9`, `skill10`, `cvDate`, `userLogin`, `salary`) VALUES 
			('".$_POST[cvnie]."', 'pending', '".$outName."', '".$outSurname."', '".$securedBirthdate."', '".$_POST[cvSex]."', '".$_POST[cvAddrType]."', '".$outAddrName."', '".$outAddrNumber."', 
			'".$_POST[cvAddrPortal]."', '".$_POST[cvAddrStair]."', '".$_POST[cvAddrFloor]."', '".$_POST[cvAddrDoor]."', '".$_POST[cvPhone]."', $securedPostal, '".$securedCountry."', '".$securedProvince."',  
			'".$securedCity."', '".$_POST[cvMobile]."', '".$securedMail."', '".$_POST[cvDrivingType]."', '".$securedDrivingDate."', '".$_POST[cvMarital]."', '".$_POST[cvSons]."', '".$securedOther."', 
			'".$securedSkill1."', '".$securedSkill2."', '".$securedSkill3."', '".$securedSkill4."', '".$securedSkill5."', '".$securedSkill6."', '".$securedSkill7."', '".$securedSkill8."', 
			'".$securedSkill9."', '".$securedSkill10."', CURRENT_TIMESTAMP, '".$_SESSION[loglogin]."', '".$_POST[cvSalary]."')";
			*/
			$insertCVQuery = "INSERT INTO `cvitaes` (`nie`, `cvStatus`, `name`, `surname`, `birthdate`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, `phone`, 
			`postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, 
			`skill7`, `skill8`, `skill9`, `skill10`, `cvDate`, `userLogin`, `salary`) VALUES 
			('".$_POST[cvnie]."', 'pending', '".$outName."', '".$outSurname."', '".$securedBirthdate."', '".$_POST[cvSex]."', '".$_POST[cvAddrType]."', '".$outAddrName."', '".$outAddrNumber."', 
			'".$_POST[cvAddrPortal]."', '".$_POST[cvAddrStair]."', '".$_POST[cvAddrFloor]."', '".$_POST[cvAddrDoor]."', '".$_POST[cvPhone]."', '".$securedPostal."', '".$securedCountry."', 
			'".$securedProvince."', '".$securedCity."', '".$_POST[cvMobile]."', '".$securedMail."', '".$_POST[cvDrivingType]."', '".$securedDrivingDate."', '".$_POST[cvMarital]."', '".$_POST[cvSons]."', 
			'".$securedOther."', '".$securedSkill1."', '".$securedSkill2."', '".$securedSkill3."', '".$securedSkill4."', '".$securedSkill5."', '".$securedSkill6."', '".$securedSkill7."', '".$securedSkill8."', 
			'".$securedSkill9."', '".$securedSkill10."', CURRENT_TIMESTAMP, '".$_SESSION[loglogin]."', '".$_POST[cvSalary]."')";
			
			if(!executeDBquery($insertCVQuery)){
				unset($_POST[push_button]);
				?>
				<script type="text/javascript">
					<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
					alert('Error: Debido a un problema en la BBDD su CV no pudo ser guardado.');
					history.back();
				</script>
				<?php 
			}
			else{
				//Every user's Nationality will be saved in 'userNationalities' table. As it is a required field, there will be, at least, 1 language
				$cont = count($_POST[cvNation]);
				for($i=0; $i<$cont; $i++){
					executeDBquery("INSERT INTO `userNationalities` (`userNIE`, `keyCountry`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvNation][$i]."')");
				}
				
				//Every user's Language will be saved in 'userLanguages' table. As it is a required field, there will be, at least, 1 language
				/*
				executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvLang]."', '".$_POST[cvLangLevel]."')");
				if(isset($_POST[h_cvLang])){
					$cont = count($_POST[h_cvLang]);
					for($i=0; $i<$cont; $i++){
						executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST[cvnie]."', '".$_POST[h_cvLang][$i]."', '".$_POST[h_cvLangLevel][$i]."')");
					}
				}
				*/
				executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvLang]."', '".$_POST[cvLangLevel]."')");
				if(isset($_POST[h_cvLang])){
					$cont = count($_POST[h_cvLang]);
					for($i=0; $i<$cont; $i++){
						executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST[cvnie]."', '".$_SESSION[h_cvLang][$i]."', '".$_SESSION[h_cvLangLevel][$i]."')");
					}
				}
				
				//Every user's Education will be saved in 'userEducations' table. As it is a required field, there will be, at least, 1 education
				$securedEducTittle = securizeString($_POST[cvEducTittle]);
				$securedEducCenter = securizeString($_POST[cvEducCenter]);
				$securedEducStart = securizeString($_POST[cvEducStart]);
				$securedEducEnd = securizeString($_POST[cvEducEnd]);
				executeDBquery("INSERT INTO `userEducations` (`userNIE`, `educTittle`, `educCenter`, `educStart`, `educEnd`) VALUES 
				('".$_POST[cvnie]."', '".$securedEducTittle."', '".$securedEducCenter."', '".$securedEducStart."', '".$securedEducEnd."')");
				if(isset($_POST[h_cvEducTittle])){
					/*
					$securedh_EducTittle = securizeArray($_POST[h_cvEducTittle]);
					$securedh_EducCenter = securizeArray($_POST[h_cvEducCenter]);
					$securedh_EducStart = securizeArray($_POST[h_cvEducStart]);
					$securedh_EducEnd = securizeArray($_POST[h_cvEducEnd]);
					*/
					$cont = count($_POST[h_cvEducTittle]);
					for($i=1; $i<=$cont; $i++){
						/*
						executeDBquery("INSERT INTO `userEducations` (`userNIE`, `educTittle`, `educCenter`, `educStart`, `educEnd`) VALUES 
						('".$_POST[cvnie]."', '".$securedh_EducTittle[$i]."', '".$securedh_EducCenter[$i]."', '".$securedh_EducStart[$i]."', '".$securedh_EducEnd[$i]."')");
						*/
						executeDBquery("INSERT INTO `userEducations` (`userNIE`, `educTittle`, `educCenter`, `educStart`, `educEnd`) VALUES 
						('".$_POST[cvnie]."', '".$_SESSION[h_cvEducTittle][$i]."', '".$_SESSION[h_cvEducCenter][$i]."', '".$_SESSION[h_cvEducStart][$i]."', '".$_SESSION[h_cvEducEnd][$i]."')");
					}
				}
				
				//Every user's Occupation/Career will be saved in 'userOccupations' table. As it is a required field, there will be, at least, 1 career
				executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvCareer]."')");
				if(isset($_POST[h_cvCareer])){
					$cont = count($_POST[h_cvCareer]);
					for($i=0; $i<$cont; $i++){
						/*
						executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST[cvnie]."', '".$_POST[h_cvCareer][$i]."')");
						*/
						executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST[cvnie]."', '".$_SESSION[h_cvCareer][$i]."')");
					}
				}
				
				//Every user's Experience will be saved in 'userExperiences' table.
				if(isset($_POST[cvExpCompany])){
					$securedExpCompany = securizeString($_POST[cvExpCompany]);
					$securedExpPosition = securizeString($_POST[cvExpPosition]);
					$securedExpStart = securizeString($_POST[cvExpStart]);
					$securedExpEnd = securizeString($_POST[cvExpEnd]);
					$securedExpCity = securizeString($_POST[cvExpCity]);
					$securedExpCountry = securizeString($_POST[cvExpCountry]);
					$securedExpDescription = securizeString($_POST[cvExpDescription]);
					executeDBquery("INSERT INTO `userExperiences` (`userNIE`, `expCompany`, `expPosition`, `expStart`, `expEnd`, `expCity`, `expCountry`, `expDescription`) VALUES 
					('".$_POST[cvnie]."', '".$securedExpCompany."', '".$securedExpPosition."', '".$securedExpStart."', '".$securedExpEnd."', '".$securedExpCity."', '".$securedExpCountry."', '".$securedExpDescription."')");
					if(isset($_POST[h_cvExpCompany])){
						/*
						$securedh_ExpCompany = securizeArray($_POST[h_cvExpCompany]);
						$securedh_ExpPosition = securizeArray($_POST[h_cvExpPosition]);
						$securedh_ExpStart = securizeArray($_POST[h_cvExpStart]);
						$securedh_ExpEnd = securizeArray($_POST[h_cvExpEnd]);
						$securedh_ExpCity = securizeArray($_POST[h_cvExpCity]);
						$securedh_ExpCountry = securizeArray($_POST[h_cvExpCountry]);
						$securedh_ExpDescription = securizeArray($_POST[h_cvExpDescription]);
						*/
						$cont = count($_POST[h_cvExpCompany]);
						for($i=1; $i<=$cont; $i++){
							/*
							executeDBquery("INSERT INTO `userExperiences` (`userNIE`, `expCompany`, `expPosition`, `expStart`, `expEnd`, `expCity`, `expCountry`, `expDescription`) VALUES 
							('".$_POST[cvnie]."', '".$securedh_ExpCompany[$i]."', '".$securedh_ExpPosition[$i]."', '".$securedh_ExpStart[$i]."', '".$securedh_ExpEnd[$i]."', '".$securedh_ExpCity[$i]."', 
							'".$securedh_ExpCountry[$i]."', '".$securedh_ExpDescription[$i]."')");
							*/
							executeDBquery("INSERT INTO `userExperiences` (`userNIE`, `expCompany`, `expPosition`, `expStart`, `expEnd`, `expCity`, `expCountry`, `expDescription`) VALUES 
							('".$_POST[cvnie]."', '".$_SESSION[h_cvExpCompany][$i]."', '".$_SESSION[h_cvExpPosition][$i]."', '".$_SESSION[h_cvExpStart][$i]."', '".$_SESSION[h_cvExpEnd][$i]."', 
							'".$_SESSION[h_cvExpCity][$i]."', '".$_SESSION[h_cvExpCountry][$i]."', '".$_SESSION[h_cvExpDescription][$i]."')");
						}
					}
				}
				
				//------------------------------   Files uploading   ------------------------------//
				if($_FILES[candidatFiles][name][0]){
					$userDir = $_SERVER[DOCUMENT_ROOT] . "/cvs/".$_SESSION[loglogin]."/";
					if(ifCreateDir($userDir, 0777)){
						//Every uploaded file is checked like if it was an array
						for($i=0; $i<count($_FILES[candidatFiles][name]); $i++){
							//Now files are checked about their restrictions to be uploaded
							if(checkUploadedFileES($_FILES[candidatFiles][name][$i], $_FILES[candidatFiles][type][$i], $_FILES[candidatFiles][size][$i], $errorText) && is_uploaded_file($_FILES[candidatFiles][tmp_name][$i])){
								$_FILES[candidatFiles][name][$i] = str_replace(" ","_",$_FILES[candidatFiles][name][$i]);
								if(!move_uploaded_file($_FILES[candidatFiles][tmp_name][$i], $userDir.$_FILES[candidatFiles][name][$i])){
									?>
									<script type="text/javascript">
									<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
										alert('Error: Hubo un problema al guardar uno de sus documentos. No obstante su CV fue guardado con éxito.');
										window.location.href='endsession.php';
									</script>
									<?php 
								}
							}
							else{
								?>
								<script type="text/javascript">
									alert('Error: <?php echo $errorText; ?>.');
									window.location.href='endsession.php';
								</script>
								<?php 
							}
						}
					}
					else{
						?>
						<script type="text/javascript">
						<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
							alert('Error: Hubo un problema al guardar sus documentos. No obstante su CV fue guardado con éxito.');
							window.location.href='endsession.php';
						</script>
						<?php 
					}
				}
				
				//------------------------------   Photograph uploading   ------------------------------//
				if(isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])){
					$photoUploadFile = $userDir."photo";
					if(move_uploaded_file($_FILES['foto']['tmp_name'], $photoUploadFile)){
						$image = new SimpleImage(); 
						$image->load($photoUploadFile); 
						$image->resize(240,320); 
						$image->save($photoUploadFile.".jpg"); 
						unlink($photoUploadFile);
						#echo "El archivo es válido y fue cargado exitosamente.\n";
					}
					else{
						#echo "¡Posible ataque de carga de archivos!\n";
						?>
						<script type="text/javascript">
							<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
							alert('Ha habido un problema al guardar su foto (code PUPLOAD0). No obstante su CV ha sido guardado con éxito.');
							window.location.href='endsession.php';
						</script>
						<?php 
					}
				}
				
				//Every checking and insert query was OK. Blocking Candidate and logging her/him off
				executeDBquery("UPDATE `users` SET `active`='0', `cvSaved`='1' WHERE `login`='".$_SESSION[loglogin]."'");
				unset($_POST[push_button]);
				?>
				<script type="text/javascript">
					<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
					alert('Gracias por completar tu CV. En breve nos pondremos en contacto contigo.\nRecuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');
					window.location.href='./endsession.php';
				</script>
				<?php
			}//Else of inserting OK CV in 'cvitaes' table
		}//End of DB registry saving, and all its corresponding files
		
		//HASTA AQUI PARA LLEVARLO A FICHERO APARTE
		
		
	}//isset($_POST[push_button])
	/* -----------------------------------     End of FORM validations     ----------------------------------- */
	
	
	/* *****************************    Start of WEB Page as initially showed    ***************************** */
	else{
		//----  Start of ACTIVE Candidate. A CV was already saved, and that info now appears in each field  ----//
		if(getDBsinglefield(cvSaved, users, login, $_SESSION[loglogin]) == 1){
			$cvRow = getDBrow2(cvitaes, userLogin, $_SESSION[loglogin], firstCV, 1);
			
			
			
			
			
		}
		//-----  End of ACTIVE Candidate. A CV was already saved, and that info now appears in each field  -----//
		
		//------------------  Start of Candidate is ACTIVE and has NOT previously saved a CV  ------------------//
		else{
			?>
			<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">Fields with * are mandatory</div>
					<div class="panel-body">
						<fieldset> <!-- Datos Personales del Candidato -->
							<div class="form-group"> <!-- Nombre -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvName">Name: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvName' minlength='3' maxlength='50' placeholder="Min. 3 characters" required/>
								</div>
							</div> <!-- Fin Nombre -->
							
							<div class="form-group"> <!-- Apellidos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSurname">Surname: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvSurname' maxlength='50' placeholder="Min. 3 characters" required/>
								</div>
							</div><!-- Fin Apellidos -->
							
							<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvBirthdate">Birthdate: * </label>
								<div class="col-sm-3">
									<?php if(isset($_SESSION[cvBirthdate])){ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $_SESSION[cvBirthdate]; ?>" required/>
										<?php 
										unset($_SESSION[cvBirthdate]);
										} else{ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
									<?php } ?>
										<!--
									< ?php if(isset($_POST[cvBirthdate])){ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="< ?php echo $_POST[cvBirthdate]; ?>" required/>
										< ?php 
										unset($_POST[cvBirthdate]);
										} else{ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
										< ?php } ?>
										-->
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvnie">DNI/NIE: * </label>
								<div class="col-sm-5">
									<!-- <input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE();" required/> -->
									<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
									<input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE_ES();"required/>
								</div>
							</div> <!-- Fin Fecha de Nacimiento & DNI/NIE -->
							
							<div class="form-group tooltip-demo"> <!-- Sexo & Nacionalidad -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSex">Gender: * </label>
								<div class="col-sm-3">
									<div class='radio-inline'>
										<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='cvSex' value='0' required>Male</label>
										<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='cvSex' value='1'>Female</label>
									</div>
								</div>
							
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvNation"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="If more than one, select all while keeping pressed 'CTRL' key"></span> Nationality: * </label>
								<div class="col-sm-5" id="uploadFormNationality">
									<select class="form-control" name="cvNation[]" multiple="multiple" required>
										<option value="Spanien"> Spain </option>
										<?php 
										$userLang = getDBsinglefield(language, users, login, $_SESSION[loglogin]);
										$countryName = getDBcompletecolumnID($userLang, countries, $userLang);
										foreach($countryName as $i){
											//Allways saved in german, to make it easier to show it later when searching CVs
											//echo '<option value="' . getDBsinglefield(key, countries, $userLang, $i) . '">' . $i . '</option>';
											echo '<option value="' . getDBsinglefield(german, countries, $userLang, $i) . '">' . $i . '</option>';
										}
										?>
									</select>
								</div>
							</div> <!-- Fin Sexo & Nacionalidad -->
							
							<div class="form-group"> <!-- Dirección -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvAddrType">Address: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvAddrType">
										<?php
										echo "<option value=''>-- Type --</option>";
										$addressTypes = getDBcompletecolumnID(key, addressTypes, id);
										foreach($addressTypes as $i){
											if($i == $cvRow[addrType]){
												echo '<option value='.$i.' selected>'.getDBsinglefield(getCurrentLanguage($_SERVER[SCRIPT_NAME]), addressTypes, key, $i).'</option>';
											}
											else{
												echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER[SCRIPT_NAME]), addressTypes, key, $i).'</option>';
											}
										}
										?>
									</select>
								
									<input class="form-control" type="text" name="cvAddrName" size="34" maxlength="50" placeholder="Address' name">
									<input class="form-control" type="text" name="cvAddrNum" size="3" maxlength="4" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrPortal" size="3" maxlength="4" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrStair" size="3" maxlength="4" placeholder="Stair" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrFloor" size="3" maxlength="4" placeholder="Floor">
									<input class="form-control" type="text" name="cvAddrDoor" size="3" maxlength="4" placeholder="Door" onkeyup="this.value=this.value.toUpperCase();">
									
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrCity" size="45" maxlength="50" placeholder="City">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrPostalCode" size="25" maxlength="12" placeholder="Postal code" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div>
										
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrProvince" size="45" maxlength="30" placeholder="Province">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrCountry" size="25" maxlength="30" placeholder="Country">
										</div>
									</div>
								</div>
							</div> <!-- Fin Dirección -->
							
							<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMobile">Mobile: * </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvMobile" maxlength="18" placeholder="I.e. 0034-699000000" required onkeypress="return checkDashedNumbers(event)">
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvPhone">Additional Tlf.: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvPhone" maxlength="18" placeholder="I.e. 0034-910000000" onkeypress="return checkDashedNumbers(event)">
								</div>
							</div> <!-- Fin Teléfono Móvil & Teléfono Adicional -->
										
							<div class="form-group"> <!-- Correo Electrónico -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMail">E-mail: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="email" name="cvMail" placeholder="email@example.com" required>
								</div>
							</div> <!-- Fin Correo Electrónico -->
				
							<div class="form-group tooltip-demo"> <!-- Carnet de Conducir -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvDrivingType"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Delivery date"></span> Driving license: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvDrivingType" >
										<?php
										echo "<option value='' selected>-- Type --</option>";
										$drivingTypes = getDBcompletecolumnID(key, drivingTypes, id);
										foreach($drivingTypes as $i){
											echo '<option value='.$i.'>'.$i.'</option>';
										}
										?>
									</select>
									<input class='form-control form-inline' type="date" name='cvDrivingDate' id='cvDrivingDate' placeholder='aaaa-mm-dd'>
								</div>
							</div> <!-- Fin Carnet de Conducir -->
							
							<div class="form-group"> <!-- Estado Civil & Hijos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMarital">Marital status: </label>
								<div class="col-sm-4">
									<select class="form-control" name="cvMarital">
										<option selected disabled value="">I am...</option>
										<?php
										$userLang = getDBsinglefield(language, users, login, $_SESSION[loglogin]);
										$maritStatus = getDBcompletecolumnID($userLang, maritalStatus, id);
				
										foreach($maritStatus as $i){
											echo "<option value=" . getDBsinglefield(key, maritalStatus, $userLang, $i) . ">" . $i . "</option>";
										}
										?>
									</select>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSons">Children: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="number" name="cvSons" maxlength="2" min="0" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Estado Civil & Hijos -->
							
							<div class="form-group tooltip-demo"> <!-- Foto -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="CV, Certificates... Supported types: JPG, JPEG or PNG. Max: 1Mb"></span> Photo: </label>
								<div class="col-sm-10">
									<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
								</div>
							</div> <!-- Fin Foto -->
							
							<div class="form-group tooltip-demo"> <!-- Archivos/Docs. adicionales -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="CV, Certificates... Supported types: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT o RTF. Max: 1Mb. Choose as much as you want by using 'CTRL' key"></span> Additional docs.: </label>
								<div class="col-sm-10">
									<?php
									/* ESTO LO TENGO QUE USAR PARA CARGAR LOS ARCHIVOS
									$userFilesDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".($editedCVRow['userLogin'])."/";
									$userFilesArray  = scandir($userFilesDir);
									foreach($userFilesArray as $value){
										if(preg_match("/\w+/i", $value)){
											echo "<a class='btn btn-danger btn-xs' href='$_SERVER[SCRIPT_NAME]?codvalue=" . $editedCVRow[userLogin] . "&dFile=" . $value . "&hiddenGET=hDelCVFile' onclick='return confirmDelCVFile(\"" . getCurrentLanguage($_SERVER['SCRIPT_NAME']) . "\");'><span class='glyphicon glyphicon-remove'></span></a>&nbsp";
											echo "<a href=/es/home/downloadFileSingle.php?doc=".$userFilesDir.$value.">$value</a><br>";
										}
									}
									*/
									?>
									<div id="uploadFiles" class="col-sm9">
										<input class="form-control" type="file" name="candidatFiles[]" multiple="multiple">
									</div>
								</div>
							</div> <!-- Fin Archivos/Docs. adicionales -->
						</fieldset> <!-- Fin Datos Personales del Candidato -->
						
						
						<fieldset id="jsLanguage">
							<div class="panel panel-default"> <!-- Nivel de Idiomas -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus" data-toggle="tooltip" data-original-title="Press + if you want to add more"></span> </a> -->
									<!-- OK <a href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign pull-right" data-toggle="tooltip" data-original-tittle="Press + if you want to add more"></span></a> -->
									<!-- <a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign pull-right" data-toggle="tooltip" data-original-tittle="Press + if you want to add more"></span></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other language"></span></a>
									<h3 class="panel-title">Language *</h3>
									<a href="http://europass.cedefop.europa.eu/de/resources/european-language-levels-cefr/cef-ell-document.pdf">European Level's table</a>
								</div>
								
								<table id="tabla_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none"> 
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Language: </label>
													<select class="form-control" name="h_cvLang[]">
														<option selected disabled value="">Choose language... </option>
														<?php
														$langNames = getDBcompletecolumnID($userLang, languages, $userLang);
														
														foreach($langNames as $i){
														$resultado = strpos($_SESSION[langselected], $i);
															if ($resultado == FALSE){
																echo "<option value=" . getDBsinglefield(key, languages, $userLang, $i) . ">" . $i ."</option>";
															}
														}
														?>
													</select>
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Level: </label>
														<select class="form-control" name="h_cvLangLevel[]">
															<option selected disabled value="">Choose level...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Mother tongue</option>
														</select>
											</td>
											
											<td><a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline input-sm" id="tabla_1_fila_1" >
											<td>
											<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Language: </label>
													<select class="form-control input-sm" name="cvLang" required>
														<option selected disabled value="">Choose language... </option>
														<?php
														$langNames = getDBcompletecolumnID($userLang, languages, $userLang);
														
														foreach($langNames as $i){
														$resultado = strpos($_SESSION[langselected], $i);
															if ($resultado == FALSE){
																echo "<option value=" . getDBsinglefield(key, languages, $userLang, $i) . ">" . $i ."</option>";
															}
														}
														?>
													</select>
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Level: </label>
														<select class="form-control input-sm" name="cvLangLevel" required>
															<option selected disabled value="">Choose level...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Mother tongue</option>
														</select>
											</td>
											
											<td> </td> 
										</tr>
									</tbody>
								</table>
								
								<?php
								if(isset($_SESSION[h_cvLang])){
									for($j=0; $j<count($_SESSION[h_cvLang]); $j++){
										?>
										<div class="panel panel-default form-control form-inline" align="center">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Language: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLang][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Level: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLangLevel][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
									/* ESTO HAY QUE PASARLO A LA PARTE DE COMPROBACIONES DEL FORM (ARRIBA DEL TODO). SI LO DEJAMOS AQUI NO PODRA ENVIARSE CON EL RESTO DEL FORMULARIO
									unset($_SESSION[h_cvLang]);
									unset($_SESSION[h_cvLangLevel]);
									*/
								}
								?>
							</div> <!-- Fin Nivel de Idiomas -->
							
							
							<div class="panel panel-default"> <!-- Educación -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvEducTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvEducTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other education"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Include all the titles you have as follows: Title and Specialty, Study center, Start and end dates"></span> Education: *</label></h3>
								</div>
							
								<!-- <table id="cvEducTable_1" align="center"> -->
								<table id="cvEducTable_1">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Tittle: </label>
												<input class="form-control" type="text" name="h_cvEducTittle[]" value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Center: </label>
												<input class="form-control" type="text" name="h_cvEducCenter[]" value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Start: </label>
												<input class="form-control" type="text" name="h_cvEducStart[]" value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">End: </label>
												<input class="form-control" type="text" name="h_cvEducEnd[]" value="<?php echo getDBsinglefield(educEnd, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td><a href="#" onClick="delExtraEduc(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Tittle: </label>
												<input class="form-control" type="text" name="cvEducTittle" required value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Center: </label>
												<input class="form-control" type="text" name="cvEducCenter" required value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Start: </label>
												<input class="form-control" type="text" name="cvEducStart" required value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">End: </label>
												<input class="form-control" type="text" name="cvEducEnd" required value="<?php echo getDBsinglefield(educEnd, userEducations, idEdu, $i); ?>" />
											</td>
	
											<td> </td> 
										</tr>
									</tbody>
								</table>
								
								<?php
								if(isset($_SESSION[h_cvEducTittle])){
									for($j=1; $j<count($_SESSION[h_cvEducTittle]); $j++){
										?>
										<div class="panel panel-default form-control form-inline" align="center">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducTittle">Tittle: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducTittle][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducCenter">Center: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducCenter][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducStart">Start: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducEnd">End: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducEnd][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
									/* ESTO HAY QUE PASARLO A LA PARTE DE COMPROBACIONES DEL FORM (ARRIBA DEL TODO). SI LO DEJAMOS AQUI NO PODRA ENVIARSE CON EL RESTO DEL FORMULARIO
									unset($_SESSION[h_cvEducTittle]);
									unset($_SESSION[h_cvEducCenter]);
									unset($_SESSION[h_cvEducStart]);
									unset($_SESSION[h_cvEducEnd]);
									*/
								}
								?>
							</div> <!-- Fin Educación -->
							
							<div class="panel panel-default"> <!-- Profesión -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvCareerTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvCareerTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other career"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="If your title does not appear in the list select Other and contact us through administracion@perspectiva-alemania.com"></span> Career: *</label></h3>
								</div>
							
								<table id="cvCareerTable_1">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<select class="form-control" name="h_cvCareer[]">
													<option selected disabled value=""> Press "+" after choose... </option>
													<option value="other"> Other </option>
													<?php 
														//$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'careers', 'id');
														$eduNames = getDBcompleteColumnID($userRow[language], careers, id);
														foreach($eduNames as $i){
															//echo '<option value="'.$i.'">' . $i . '</option>';
															echo '<option value="'.getDBsinglefield(german, careers, $userRow[language], $i).'">' . $i . '</option>';
														}
													?>
												</select>
											</td>
											
											<td><a href="#" onClick="delExtraCareer(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1">
											<td>
												<select class="form-control" name="cvCareer" required>
													<option selected disabled value=""> Press "+" after choose... </option>
													<option value="other"> Other </option>
													<?php 
														//$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'careers', 'id');
														$eduNames = getDBcompleteColumnID($userRow[language], careers, id);
														foreach($eduNames as $i){
															//echo '<option value="'.$i.'">' . $i . '</option>';
															echo '<option value="'.getDBsinglefield(german, careers, $userRow[language], $i).'">' . $i . '</option>';
														}
													?>
												</select>
											</td>
											
											<td> </td> 
										</tr>
									</tbody>
								</table>
								
								<?php
								if(isset($_SESSION[h_cvCareer])){
									for($j=0; $j<count($_SESSION[h_cvCareer]); $j++){
										?>
										<div class="panel panel-default form-control form-inline" align="center">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvCareer">Career: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvCareer][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
									/* ESTO HAY QUE PASARLO A LA PARTE DE COMPROBACIONES DEL FORM (ARRIBA DEL TODO). SI LO DEJAMOS AQUI NO PODRA ENVIARSE CON EL RESTO DEL FORMULARIO
									unset($_SESSION[h_cvCareer]);
									*/
								}
								?>
							</div> <!-- Fin Profesión -->
							
							<div class="panel panel-default"> <!-- Trayectoria / Experiencia -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvExperTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvExperTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other experience"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="If current job, leave blank the field 'End'"></span> What have you done these last years? </h3>
								</div>
							
								<table id="cvExperTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Company: </label>
												<input class="form-control" type="text" name="h_cvExpCompany[]" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Position: </label>
												<input class="form-control" type="text" name="h_cvExpPosition[]" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Start: </label>
												<input class="form-control" type="text" name="h_cvExpStart[]" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">End: </label>
												<input class="form-control" type="text" name="h_cvExpEnd[]" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">City: </label>
												<input class="form-control" type="text" name="h_cvExpCity[]" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Country: </label>
												<input class="form-control" type="text" name="h_cvExpCountry[]" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Description: </label>
												<textarea class="form-control" name="h_cvExpDescription[]" rows="5" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
											</td>
											
											<td><a href="#" onClick="delExtraExp(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Company: </label>
												<input class="form-control" type="text" name="cvExpCompany" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Position: </label>
												<input class="form-control" type="text" name="cvExpPosition" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Start: </label>
												<input class="form-control" type="text" name="cvExpStart" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">End: </label>
												<input class="form-control" type="text" name="cvExpEnd" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">City: </label>
												<input class="form-control" type="text" name="cvExpCity" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Country: </label>
												<input class="form-control" type="text" name="cvExpCountry" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Description: </label>
												<textarea class="form-control" name="cvExpDescription" rows="5" placeholder="Stellenbeschreibung" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
											</td>
	
											<td> </td> 
										</tr>
									</tbody>
								</table>
								
								<?php
								if(isset($_SESSION[h_cvExpCompany])){
									for($j=1; $j<count($_SESSION[h_cvExpCompany]); $j++){
										?>
										<div class="panel panel-default form-control form-inline" align="center">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCompany">Company: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCompany][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpPosition">Position: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpPosition][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpStart">Start: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpEnd">End: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpEnd][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCity">City: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCity][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCountry">Country: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCountry][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpDescription">Description: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpDescription][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
									/* ESTO HAY QUE PASARLO A LA PARTE DE COMPROBACIONES DEL FORM (ARRIBA DEL TODO). SI LO DEJAMOS AQUI NO PODRA ENVIARSE CON EL RESTO DEL FORMULARIO
									unset($_SESSION[h_cvExpCompany]);
									unset($_SESSION[h_cvExpPosition]);
									unset($_SESSION[h_cvExpStart]);
									unset($_SESSION[h_cvExpEnd]);
									unset($_SESSION[h_cvExpCity]);
									unset($_SESSION[h_cvExpCountry]);
									unset($_SESSION[h_cvExpDescription]);
									*/
								}
								?>
							</div> <!-- Fin Trayectoria / Experiencia -->
						</fieldset>
						
						<fieldset>
							<div class="form-group"> <!-- Salario -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSalary">Desired salary: </label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="cvSalary" maxlength="7" placeholder="€ net/year" value="<?php echo $cvRow[salary] ?>" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div>
				
							<div class="form-group"> <!-- Otros datos de Interés -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther">Other interesting information: </label>
								<div class="col-sm-10">
									<textarea class="form-control" type="number" name="cvOther" placeholder="Write here any other relevant information that does not appears in any other field in the form..." value="<?php echo $cvRow[otherDetails] ?>"></textarea>	
								</div>
							</div>		
				
							<div class="form-group tooltip-demo"> <!-- 10 Tags -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="It is not mandatory to fill in all the 10 fields"></span> 10 key points from my personal experience</label>
								<div class="col-sm-10">
									<?php
									$tipArray = array(1 => '#1 I am specialized in...', 
													2 => '#2 In the last years I have acquired solid knowledgements and experience in...', 
													3 => '#3 I have more than... years of experience in...',
													4 => '#4 During the last... years I have developed my professional activity in the sector...', 
													5 => '#5 ...', 
													6 => '#6 ...', 
													7 => '#7 ...', 
													8 => '#8 ...', 
													9 => '#9 ...', 
													10 => '#10 ...');
									
									for ($i=1; $i <= 10 ; $i++) { 
										echo "<div class='col-sm-6' style='margin-bottom: 10px;'>";
										$iSkill = skill.$i;
										echo "<input class='form-control' type='text' name='cvskill$i' maxlength='100' placeholder='$tipArray[$i]' value='$cvRow[$iSkill]'>";
										echo "</div>";
									}
									?>
								</div>
							</div>
						</fieldset>
						
					</div> <!-- Panel Body -->
					
					<?php
					//For security reasons, every "$_SESSION" variable is unsetted
					unset($_SESSION[h_cvLang]);
					unset($_SESSION[h_cvLangLevel]);
					unset($_SESSION[h_cvEducTittle]);
					unset($_SESSION[h_cvEducCenter]);
					unset($_SESSION[h_cvEducStart]);
					unset($_SESSION[h_cvEducEnd]);
					unset($_SESSION[h_cvCareer]);
					unset($_SESSION[h_cvExpCompany]);
					unset($_SESSION[h_cvExpPosition]);
					unset($_SESSION[h_cvExpStart]);
					unset($_SESSION[h_cvExpEnd]);
					unset($_SESSION[h_cvExpCity]);
					unset($_SESSION[h_cvExpCountry]);
					unset($_SESSION[h_cvExpDescription]);
					unset($_SESSION[cvBirthdate]);
					?>
					
					<div class="panel-footer">
						<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="cvlopd" required> I have read and accept the <a href="javascript:alert('Recuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');">terms of use</a> and privacy policy.</label>
						<div class="btn-group pull-right">
							<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
							<button type="submit" name ="push_button" class="btn btn-primary" onclick="return confirmFormSendES(formu);">Send</button>
						</div>
					</div> <!-- Panel Footer-->
				</div> <!-- class="panel-default" -->
			</form>
		<?php
		}
		//-------------------  End of Candidate is ACTIVE and has NOT previously saved a CV  -------------------//
		
		
		
		
	}
	/* -----------------------------     End of WEB Page as initially showed     ----------------------------- */
	?>


</body>
</html>
