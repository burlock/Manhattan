<?php
session_start();

if(!$_SESSION[loglogin]){
	?>
	<script type="text/javascript">
		window.location.href='/<?php echo getUserLangDigits($userRow[language]); ?>/index.html';
	</script>
	<?php
}
else{
	$_SESSION[cvBirthdate] = $_POST[cvBirthdate];
	unset($_POST[cvBirthdate]);
	$_SESSION[h_cvLang] = $_POST[h_cvLang];
	unset($_POST[h_cvLang]);
	$_SESSION[h_cvLangLevel] = $_POST[h_cvLangLevel];
	unset($_POST[h_cvLangLevel]);
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
			executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvLang]."', '".$_POST[cvLangLevel]."')");
			if(isset($_SESSION[h_cvLang])){
				$cont = count($_SESSION[h_cvLang]);
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
			if(isset($_SESSION[h_cvEducTittle])){
				$cont = count($_SESSION[h_cvEducTittle]);
				for($i=1; $i<$cont; $i++){
					executeDBquery("INSERT INTO `userEducations` (`userNIE`, `educTittle`, `educCenter`, `educStart`, `educEnd`) VALUES 
					('".$_POST[cvnie]."', '".$_SESSION[h_cvEducTittle][$i]."', '".$_SESSION[h_cvEducCenter][$i]."', '".$_SESSION[h_cvEducStart][$i]."', '".$_SESSION[h_cvEducEnd][$i]."')");
				}
			}
			
			//Every user's Occupation/Career will be saved in 'userOccupations' table. As it is a required field, there will be, at least, 1 career
			executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST[cvnie]."', '".$_POST[cvCareer]."')");
			if(isset($_SESSION[h_cvCareer])){
				$cont = count($_SESSION[h_cvCareer]);
				for($i=0; $i<$cont; $i++){
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
				if(isset($_SESSION[h_cvExpCompany])){
					$cont = count($_SESSION[h_cvExpCompany]);
					for($i=1; $i<$cont; $i++){
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
			<script type="text/javascript">
				<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
				alert('Gracias por completar tu CV. En breve nos pondremos en contacto contigo.\nRecuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');
				window.location.href='./endsession.php';
			</script>
			<?php
		}//Else of inserting OK CV in 'cvitaes' table
	}//End of DB registry saving, and all its corresponding files
	
}