<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Validación un formulario</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="../common/js/functions.js"></script>
	
	<script>
		//Functions used to add/remove in realtime Language fields 
		var rowNum = 0;
		function addLanguage(frm){
			if (frm.add_idiomas.value == ''){
				return ;
			}
			if (frm.add_nidiomas.value == ''){
				return ;
			}
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowLanguage'+rowNum+'"><div class="col-sm-5"><input class="form-control" type="hidden" name="idiomas[]" value="'+frm.add_idiomas.value+'" ><input class="form-control" type="text" name="idiomasf[]" value="'+frm.add_idiomas.value+'" disabled></div><div class="col-sm-5"><input class="form-control" type="hidden" name="nidiomas[]" value="'+frm.add_nidiomas.value+'" ><input class="form-control" type="text" name="fnidiomas[]" value="'+frm.add_nidiomas.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeLanguage('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormLanguage').append(row);
			ajaxGetLanguage(frm.add_idiomas.value);
			frm.add_idiomas.value = '';
			frm.add_nidiomas.value = '';
		}
		
		function removeLanguage(rnum){
			jQuery('#rowLanguage'+rnum).remove();
			ajaxDelLanguage(rnum);
		}
		
		//Functions used to add/remove realtime Education fields 
		var rowNum = 0;
		function addDegree(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowDegree'+rowNum+'"><div class="col-sm-11"><input class="form-control" type="hidden" name="educ[]" value="'+frm.add_educ.value+'"><input class="form-control" type="text" name="feduc[]" value="'+frm.add_educ.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeDegree('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormDegree').append(row);
			frm.add_educ.value = '';
		}
		
		function removeDegree(rnum){
			jQuery('#rowDegree'+rnum).remove();
		}
		
		//Functions to add/remove Career/Occupation (Proffession) fields in realtime 
		function addProf(frm){
		rowNum ++;
		var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowProf'+rowNum+'"><div class="col-sm-11"><input class="form-control" type="hidden" name="prof[]" value="'+frm.add_prof.value+'"><input class="form-control" type="text" name="fprof[]" value="'+frm.add_prof.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeProf('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
		jQuery('#uploadFormProf').append(row);
		frm.add_prof.value = '';
		}
		
		function removeProf(rnum){
			jQuery('#rowProf'+rnum).remove();
		}
		
		//Functions to add/remove Experience fields in realtime 
		function addCareer(frm){
			rowNum ++;

			var row ='<div class="row" style="padding-left: 0px; margin-bottom: 10px;" id="rowCareer'+rowNum+'"> \
				<div class="col-sm-5"> \
					<div class="row"> \
						<div class="col-sm-6"> \
									<input class="form-control" type="hidden" name="empr[]" value="'+frm.add_empr.value+'"> \
									<input class="form-control" type="text" name="fempr[]" value="'+frm.add_empr.value+'" readonly> \
						</div> \
						<div class="col-sm-6"> \
									<input class="form-control" type="hidden" name="categ[]" value="'+frm.add_categ.value+'" > \
									<input class="form-control" type="text" name="fcateg[]" value="'+frm.add_categ.value+'" disabled> \
						</div> \
					</div> \
					<div class="row"> \
						<div class="col-sm-6"> \
									<input class="form-control" type="hidden" name="expstart[]" value="'+frm.add_expstart.value+'"> \
									<input class="form-control" type="text" name="fexpstart[]" value="'+frm.add_expstart.value+'" disabled> \
						</div>			 \
						<div class="col-sm-6"> \
									<input class="form-control" type="hidden" name="expend[]" value="'+frm.add_expend.value+'"> \
									<input class="form-control" type="text" name="fexpend[]" value="'+frm.add_expend.value+'" disabled> \
						</div> \
					</div> \
				</div> \
				<div class=" row col-sm-4"> \
					<div class="col-sm-10"> \
								<input class="form-control" type="hidden" name="desc[]" value="'+frm.add_desc.value+'"></textarea> \
								<textarea class="form-control" name="fdesc[]" value="'+frm.add_desc.value+'" readonly>'+frm.add_desc.value+'</textarea> \
					</div>	 \
					<div class="btn-toolbar col-sm-1"> \
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="removeCareer('+rowNum+');" type="button"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div> \
					</div> \
				</div>					 \
			</div>';	
			jQuery('#uploadFormCareer').append(row);
			frm.add_empr.value = '';
			frm.add_categ.value = '';
			frm.add_expstart.value = '';
			frm.add_expend.value = '';
			frm.add_desc.value = '';
		}
		
		function removeCareer(rnum){
			jQuery('#rowCareer'+rnum).remove();
		}
		
		//Function to add/remove Nationalities in realtime 
		function addNationality(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowNationality'+rowNum+'"><div class="col-sm-11"><input class="form-control" type="text" name="nat[]" value="'+frm.add_nat.value+'" readonly></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeNationality('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormNationality').append(row);
			frm.add_nat.value = '';
		}
		
		function removeNationality(rnum){
			jQuery('#rowNationality'+rnum).remove();
		}
		function addFiles(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowFiles'+rowNum+'"><div class="col-sm-9"><input class="form-control" type="file" name="archivo'+rowNum+'"></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeFiles('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFiles').append(row);

			frm.add_archivos.value = '';
		}
		
		function removeFiles(rnum){
			jQuery('#rowFiles'+rnum).remove();
		}
		
		
		//Function to realtime check characters written in Salary field 
		function checkOnlyNumbers(e){
			tecla = e.which || e.keyCode;
			patron = /\d/; // Solo acepta números
			te = String.fromCharCode(tecla);
			return (patron.test(te) || tecla == 9 || tecla == 8);
		}
		
		//Function used to check in realtime a phone number in which there could be included dashes (guiones) 
		function checkDashedNumbers(e){
			tecla = e.which || e.keyCode;
			//patron = /\d\\-/; // Solo acepta números
			patron = /[0-9\\-]/;
			te = String.fromCharCode(tecla);
			return (patron.test(te) || tecla == 9 || tecla == 8);
		}

		//Function to check in realtime photo's extensions 
		function checkJSPhotoExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(jpg|png|jpeg)$/i.test(fileExt);
			if(!acceptedExts){
				//var cleared = document.getElementById('foto').value = "";
				var cleared = document.getElementById(fileId).value = "";
				//alert ("El fichero "+fileItself+" no posee una extensión válida");
				alert ("\'"+fileExt+"\' no es una extensión válida para su fotografía");
				return false;
			}
		}

		//Function to check in realtime doc's extensions 
		function checkJSDocsExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(pdf|doc|docx|xls|xlsx|csv|txt|rtf)$/i.test(fileExt);
			if(!acceptedExts){
				//var cleared = document.getElementById('foto').value = "";
				var cleared = document.getElementById(fileId).value = "";
				//alert ("El fichero "+fileItself+" no posee una extensión válida");
				alert ("\'"+fileExt+"\' no es una extensión válida para su documento");
				return false;
			}
		}
		</script>
	
</head>

<body onload="ajaxGetLanguage('inventado');">

<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');
	
	
	if(isset($_POST['push_button'])){
		//LO 1º A COMPROBAR SERA QUE EL CHECK LOPD ESTÉ VALIDADO Y QUE NO EXISTA
		#echo "entro en el if $_POST[senduser]";
		foreach ($_POST as $key => $entry){
			//echo $key.'<br>';
			//echo $key.' es: -->'.$_POST[$key].'<--<br>';
			if(is_array($entry)){
				if($key == idiomas){
					//str_idiomas es 'language' en la BD (en addLanguage)
					$str_idiomas = implode('|',$entry);
					//echo 'Idiomas es: '.$str_idiomas.'<br>';
				}
				if($key == nidiomas){
					//str_nidiomas es 'langLevel' en la BD (en addLanguage)
					$str_nidiomas = implode('|',$entry);
					//echo 'Nivel de idiomas es: '.$str_nidiomas;
				}
				if($key == educ){
					//str_educ es 'education' en la BD (en addDegree)
					//Must be checked with htmlentities
					$str_educ = implode('|', $entry);
					$str_educ = trim(htmlentities($str_educ));
					//echo 'Educación es: '.$str_educ.'<br>';
				}
				if($key == prof){
					//str_educ es 'career' en la BD (en addProf)
					$str_prof = implode('|', $entry);
					//$str_prof = trim(htmlentities($str_prof));
					//echo 'Profesion es: '.$str_educ;
				}
				if($key == empr){
					//str_empr es 'experCompany' en la BD (en addRow4)
					//Must be checked with htmlentities
					$str_empr = implode('|',$entry);
					$str_empr = trim(htmlentities($str_empr));
					//echo 'Empresa es: '.$str_empr;
				}
				if($key == categ){
					//str_categ es 'experPos' en la BD (en addRow4)
					//Must be checked with htmlentities
					$str_categ = implode('|',$entry);
					$str_categ = trim(htmlentities($str_categ));
					//echo 'Categoria es: '.$str_categ;
				}
				if($key == expstart){
					//str_expstart es 'experStart' en la BD (en addRow4)
					$str_expstart = implode('|',$entry);
					//echo 'Ini-exp es: '.$str_expstart;
				}
				if($key == expend){
					//str_expend es 'experEnd' en la BD (en addRow4)
					$str_expend = implode('|',$entry);
					//echo 'Fin-exp es: '.$str_expend;
				}
				if($key == desc){
					//str_desc es 'experDesc' en la BD (en addRow4)
					//Must be checked with htmlentities
					$str_desc = implode('|',$entry);
					$str_desc = trim(htmlentities($str_desc));
					//echo 'Descripción es: '.$str_desc;
				}
				/*
				if($key == nat){
					//str_nat es 'nationalities' en la BD (en addRow5)
					$str_nat = implode('|',$entry);
				}
				*/
				if($key == nat){
					//str_nat es 'nationalities' en la BD (en addRow5)
					if(isset($key)){
						//This is made to avoid as possible SQL Injection
						checkNationality($entry, $outNations);
						$str_nat = $outNations;
						//echo 'str_nat es: '.$str_nat;
					}
					//$str_nat = implode('|',$entry);
				}
				#print $key . ": " . implode(',',$entry) . "<br>";
			 }
			 else {
			   #print $key . ": " . $entry . "<br>";
			 }
		}
		
		//This first validation let the system avoid double-recording of the registry if form is refreshed by Candidate via 'CMD+R' or 'F5' in his/her keyboard
		if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkFullName($_POST['blankname'], $_POST['blanksurname'], $outName, $outSurname, $checkError)){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!isAdult($_POST['blankbirthdate'], getDBsinglefield('value', 'otherOptions', 'key', 'legalAge'))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: La fecha indica que no es mayor de edad.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkDNI_NIE($_POST['blanknie'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Revise la fecha de nacimiento. Formato incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!isset($str_nat)){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: No se ha indicado Nacionalidad.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		//Sex and Type of address are automatically detected as restricted fields
		
		//Address won't be mandatory but, if included, will be necessary to fulfill 'type', 'name' and 'number'
		elseif((strlen($_POST['blankaddrtype']) > 0) || (strlen($_POST['blankaddrname']) > 0) || (strlen($_POST['blankaddrnum']) > 0) || (strlen($_POST['blankaddrportal']) > 0) || 
		(strlen($_POST['blankaddrstair']) > 0) || (strlen($_POST['blankaddrfloor']) > 0) || (strlen($_POST['blankaddrdoor']) > 0)){
			if((strlen($_POST['blankaddrtype']) < 1) || (strlen($_POST['blankaddrname']) < 1) || (strlen($_POST['blankaddrnum']) < 1)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('Error: No se ha indicado tipo, nombre o número en la dirección.');
					window.location.href='home.php';
				</script>
				<?php
			}
			elseif(!checkFullAddress($_POST['blankaddrname'], $_POST['blankaddrnum'], $outAddrName, $outAddrNumber, $checkError)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('<?php echo $checkError; ?>');
					window.location.href='home.php';
				</script>
				<?php
			}
		}
		elseif(!checkMobile($_POST['blankmobile'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Revise el número de móvil. Formato incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		//This could be an international phone (should start with '00(49)'. It is not mandatory
		elseif(strlen($_POST['blankphone']) > 0){
			if(!checkPhone($_POST['blankphone'])){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('Error: Revise el número de teléfono. Formato incorrecto.');
					window.location.href='home.php';
				</script>
				<?php 
			}
		}
		elseif(!filter_var($_POST['blankmail'], FILTER_VALIDATE_EMAIL)){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Revise el correo electrónico. Formato incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		//As it is a drop down menu, there is no need to check it with 'htmlentities'
		elseif($str_idiomas == '' || $str_nidiomas == '' || $str_nidiomas == '%null%'){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: No se ha indicado idioma y nivel. Indique al menos 1 de cada.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif((strlen($_POST['blankdrivingtype']) > 0) || (strlen($_POST['blankdrivingdate']) > 0)){
			if(!checkDrivingLicense($_POST['blankdrivingtype'], $_POST['blankdrivingdate'], $checkError)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('<?php echo $checkError; ?>');
					window.location.href='home.php';
				</script>
				<?php 
			}
		}
		
		//Only if EVERY check is OK can proceed with process to insert registry in DB
		else{
			$cleanedOther = cleanFreeText($_POST['blankother']);
			$cleanedSkill1 = cleanFreeText($_POST['blankskill1']);
			$cleanedSkill2 = cleanFreeText($_POST['blankskill2']);
			$cleanedSkill3 = cleanFreeText($_POST['blankskill3']);
			$cleanedSkill4 = cleanFreeText($_POST['blankskill4']);
			$cleanedSkill5 = cleanFreeText($_POST['blankskill5']);
			$cleanedSkill6 = cleanFreeText($_POST['blankskill6']);
			$cleanedSkill7 = cleanFreeText($_POST['blankskill7']);
			$cleanedSkill8 = cleanFreeText($_POST['blankskill8']);
			$cleanedSkill9 = cleanFreeText($_POST['blankskill9']);
			$cleanedSkill10 = cleanFreeText($_POST['blankskill10']);
				
			
			$insertCVQuery = "INSERT INTO `cvitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
			`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `education`, `career`, 
			`experCompany`, `experStart`, `experEnd`, `experPos`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, 
			`cvDate`, `userLogin`, `salary`) VALUES 
			(NULL, '".$_POST['blanknie']."', 'pending', '".$outName."', '".$outSurname."', '".$_POST['blankbirthdate']."', '".$str_nat."', '".$_POST['blanksex']."',
			'".$_POST['blankaddrtype']."', '".$outAddrName."', '".$outAddrNumber."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
			'".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', '".$_POST['blankaddrpostalcode']."', '".$_POST['blankaddrcountry']."', '".$_POST['blankaddrprovince']."', '".$_POST['blankaddrcity']."',
			'".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', 
			'".$str_idiomas."', '".$str_nidiomas."', '".$str_educ."', '".$str_prof."', '".$str_empr."', '".$str_expstart."', '".$str_expend."', '".$str_categ."', '".$str_desc."', '".$cleanedOther."', 
			'".$cleanedSkill1."', '".$cleanedSkill2."', '".$cleanedSkill3."', '".$cleanedSkill4."', '".$cleanedSkill5."', '".$cleanedSkill6."', '".$cleanedSkill7."', 
			'".$cleanedSkill8."', '".$cleanedSkill9."', '".$cleanedSkill10."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."', '".$_POST['blanksalary']."')";
			
					$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$_SESSION['loglogin']."/";
					/*
					if(ifCreateDir($userDir, 0777)){
						echo 'Ha entrado en el ifCreateDir.<br>';
						for ($i=0;$i<100;$i++){
							echo 'Iteracion '.$i.'<br>';
							if ($i==0){
								if (isset($_FILES["archivo"])){
									echo 'Archivo '.$i.' preparado para ser subido <br>';
									$_FILES['archivo']['name']= str_replace(" ","_",$_FILES['archivo']['name']);
									move_uploaded_file($_FILES['archivo']['tmp_name'],$userDir.$_FILES['archivo']['name']);
									echo 'Aquí ya debería haber subido el '.$i.'<br>';
								}
							}
							else{
								if (isset($_FILES["archivo$i"])){
									echo 'Archivo '.$i.' preparado para ser subido <br>';
									$_FILES["archivo$i"]['name']= str_replace(" ","_",$_FILES["archivo$i"]['name']);
									move_uploaded_file($_FILES["archivo$i"]['tmp_name'],$userDir.$_FILES["archivo$i"]['name']);
									echo 'Aquí ya debería haber subido el '.$i.'<br>';
								}
							}
						}	
					}
					*/
					/*
					if(ifCreateDir($userDir, 0777)){
						echo 'Ha entrado en el ifCreateDir.<br>';
						$numFiles = count($_FILES["archivo"]["name"]);
						echo 'Hay '.$numFiles.' archivos a subir<br>';
						for ($i=0; $i<$numFiles; $i++){
							echo 'Iteracion '.$i.'<br>';
							if ($i==0){
								if (isset($_FILES["archivo"])){
									echo 'Archivo '.$i.' preparado para ser subido <br>';
									$_FILES['archivo']['name']= str_replace(" ","_",$_FILES['archivo']['name']);
									move_uploaded_file($_FILES['archivo']['tmp_name'],$userDir.$_FILES['archivo']['name']);
									echo 'Aquí ya debería haber subido el '.$i.'<br>';
								}
							}
							else{
								if (isset($_FILES["archivo$i"])){
									echo 'Archivo '.$i.' preparado para ser subido <br>';
									$_FILES["archivo$i"]['name']= str_replace(" ","_",$_FILES["archivo$i"]['name']);
									move_uploaded_file($_FILES["archivo$i"]['tmp_name'],$userDir.$_FILES["archivo$i"]['name']);
									echo 'Aquí ya debería haber subido el '.$i.'<br>';
								}
							}
						}	
					}
					exit();
					*/
			
			//checkUploadedFileES($_FILES['archivos'][0], $errorText);
			//checkUploadedFileES($_FILES['archivos']['name'][0], $_FILES['archivos']['mime'][0], $_FILES['archivos']['type'][0], $_FILES['archivos']['size'][0], $errorText);
			/*
			checkUploadedFileES($_FILES['archivos']['name'][0], $_FILES['archivos']['type'][0], $_FILES['archivos']['size'][0], $errorText);
			echo 'El error ...'.$errorText;
			exit();
			*/
			
			if(!executeDBquery($insertCVQuery)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('Hubo un problema guardando su CV. Contacte con el administrador.');
					window.location.href='home.php';
				</script>
				<?php 
			}
			else{
				/* Being here (under this 'else') means that insert query was OK. So user must be inactivated and redirected to 'index.html'
				 * But before, we check if user wishes to upload any file or photo
				 */
				//if(isset($_FILES['archivos']) && is_uploaded_file($_FILES['archivos']['tmp_name'][0])){
				if(isset($_FILES['archivo'])){
					
					$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$_SESSION['loglogin']."/";
					//echo $userDir;
					/*
					//if(!ifCreateDir($userDir, 0777)){
					if(ifCreateDir($userDir, 0777)){
						$numFiles = count($_FILES["archivo"]["name"]);
						for ($i=0; $i<$numFiles; $i++){
							//Upload for each Candidate file
							if(checkUploadedFileES($_FILES['archivo']['name'][$i], $_FILES['archivo']['type'][$i], $_FILES['archivo']['size'][$i], $errorText) && is_uploaded_file($_FILES['archivo']['tmp_name'][0])){
								$_FILES['archivo']['name'][$i] = str_replace(" ","_",$_FILES['archivo']['name'][$i]);
								move_uploaded_file($_FILES['archivo']['tmp_name'][$i], $userDir.$_FILES['archivo']['name'][$i]);
								//$tmp_name = $_FILES["archivos"]["tmp_name"][$i];
								//$name = $_FILES["archivos"]["name"][$i];
							}
							else{
								?>
								<script type="text/javascript">
									alert('Problem uploading file (code FUPLOAD<?php echo $i; ?>). Anyway, CV was successfully inserted.');
									window.location.href='endsession.php';
								</script>
								<?php 
							}
						}
					}
					*/
					
					if(ifCreateDir($userDir, 0777)){
						for ($i=0;$i<100;$i++){
							if ($i==0){
								if (isset($_FILES["archivo"])){
									$_FILES['archivo']['name']= str_replace(" ","_",$_FILES['archivo']['name']);
									move_uploaded_file($_FILES['archivo']['tmp_name'],$userDir.$_FILES['archivo']['name']);
								}
							}
							else{
								if (isset($_FILES["archivo$i"])){
									$_FILES["archivo$i"]['name']= str_replace(" ","_",$_FILES["archivo$i"]['name']);
									move_uploaded_file($_FILES["archivo$i"]['tmp_name'],$userDir.$_FILES["archivo$i"]['name']);
								}
							}
						}	
					}
				}
				//Now Candidate photo will be uploaded
				if(isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])){
					$photoUploadFile = $userDir."foto";
					if(move_uploaded_file($_FILES['foto']['tmp_name'], $photoUploadFile)){
						$image = new SimpleImage(); 
						$image->load($photoUploadFile); 
						$image->resize(250,250); 
						$image->save($photoUploadFile."r.jpg"); 
						unlink($photoUploadFile);
						#echo "El archivo es válido y fue cargado exitosamente.\n";
					}
					else{
						#echo "¡Posible ataque de carga de archivos!\n";
						?>
						<script type="text/javascript">
							alert('Ha habido un problema al guardar su foto (code PUPLOAD0). No obstante su CV ha sido guardado con éxito.');
							//window.location.href='home.php';
							window.location.href='endsession.php';
						</script>
						<?php 
					}
				}
				
				//blocks candidate and redirects her/him to index.html
				executeDBquery("UPDATE `users` SET `active`='0', `cvSaved`='1' WHERE `login`='".$_SESSION['loglogin']."'");
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					//alert('CV insertado con éxito. Gracias!');
					alert('Gracias por insertar su CV. Por seguridad, su usuario ha sido desactivado.');
					window.location.href='./endsession.php';
				</script>
				<?php
			}
		}
	}//del (isset($_POST[]))

	/**********************************     End of FORM validations     **********************************/
	
	/******************************     Start of WebPage code as showed     ******************************/
?>
<!-- EN CADA CAMPO COMPROBARÉ SI EL USUARIO YA INSERTÓ PREVIAMENTE EL CV if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])) -->

Los campos que poseen * son obligatorios.

<!-- <form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data"> -->
<!-- <form id="uploadForm" class="form-horizontal" name="formu" action="" onSubmit="confirmFormSendES(this.form)" method="post" enctype="multipart/form-data"> -->
<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group"> <!-- Nombre -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankname">Nombre: * </label> 
				<div class="col-sm-10">
					<!-- <input class="form-control" type='text' name='blankname' autocomplete="off" required/> -->
					<input class="form-control" type='text' name='blankname' maxlength='50' placeholder="Min. 3 caracteres" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Apellidos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksurname">Apellidos: * </label> 
				<div class="col-sm-10">
					<!-- <input class="form-control" type='text' name='blanksurname' autocomplete="off" required/> -->
					<input class="form-control" type='text' name='blanksurname' maxlength='50' placeholder="Min. 3 caracteres" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Fecha de Nacimiento -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankbirthdate">Fecha de Nacimiento: * </label> 
				<div class="col-sm-10">
					<!-- <input class="form-control" type='date' name='blankbirthdate' id='blankbirthdate' autocomplete="off" placeholder="aaaa-mm-dd" onChange="jsIsAdult(this.id, 18)" required/> -->
					<input class="form-control" type='date' name='blankbirthdate' id='blankbirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
				</div>
			</div>		

			<div class="form-group"> <!-- DNI/NIE -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanknie">DNI/NIE: * </label>
				<div class="col-sm-10">
					<!-- <input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="[8 digs.]+[LETRA] ó [LETRA]+[7 digs.]+[LETRA]" onkeyup="this.value=this.value.toUpperCase();" onChange="jsCheckDNI_NIE(this.id);" required/> -->
					<!-- <input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="[8 digs.]+[LETRA] ó [LETRA]+[7 digs.]+[LETRA]" onChange="jsCheckDNI_NIE(this.id);" required/> -->
					<!-- <input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="Ej. DNI 12345678L (8 nums.) Ej. NIE X1234567T (7 nums.)" onblur="jsCheckDNI_NIE();" required/> -->
					<input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="Ej. DNI 12345678L (8 nums.) Ej. NIE X1234567T (7 nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE();" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Nacionalidad -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_nat">Nacionalidad: * </label> 
				<div class="col-sm-9" id="uploadFormNationality">
					<select class="form-control" name="add_nat" >
						<option value="" selected disabled> Pulse "+" tras elegir... </option>
						<option value="Spain"> España </option>
						<?php 
						$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						$countryName = getDBcompletecolumnID($userLang, 'countries', $userLang);
						foreach($countryName as $i){
							//echo "<option value=" . getDBsinglefield('key', 'countries', $userLang, $i) . ">" . $i . "</option>";
							echo '<option value="' . getDBsinglefield('key', 'countries', $userLang, $i) . '">' . $i . '</option>';
						}
						?>
					</select>
				</div>
				<div class="btn-toolbar col-sm-1">
					  <div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="addNationality(this.form);"><span class="glyphicon glyphicon-plus"></span></button></div>	
				</div>
			</div>	

			<div class="form-group"> <!-- Sexo -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksex">Sexo: * </label>
				<div class="col-sm-10">
					<div class='radio-inline'>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='0' required>Hombre</label>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='1'>Mujer</label>
					</div>
				</div>
			</div>							

			<div class="form-group">  <!-- Dirección -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrtype">Dirección: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankaddrtype" >
						<option value="" selected>-- Tipo --</option>
						<option value="Acceso">Acceso</option>
						<option value="Acera">Acera</option>
						<option value="Alameda">Alameda</option>
						<option value="Autopista">Autopista</option>
						<option value="Autovía">Autovía</option>
						<option value="Avenida">Avenida</option>
						<option value="C. Comercial">C. Comercial</option>
						<option value="Calle">Calle</option>
						<option value="Callejón">Callejón</option>
						<option value="Camino">Camino</option>
						<option value="Cañada">Cañada</option>
						<option value="Carrer">Carrer</option>
						<option value="Carrera">Carrera</option>
						<option value="Carretera">Carretera</option>
						<option value="Cuesta">Cuesta</option>
						<option value="Glorieta">Glorieta</option>
						<option value="Pasadizo">Pasadizo</option>
						<option value="Pasaje">Pasaje</option>
						<option value="Paseo">Paseo</option>
						<option value="Plaza">Plaza</option>
						<option value="Rambla">Rambla</option>
						<option value="Ronda">Ronda</option>
						<option value="Sendero">Sendero</option>
						<option value="Travesía">Travesía</option>
						<option value="Urbanización">Urbanización</option>
						<option value="Vía">Vía</option>
					</select>					
					<input class="form-control form-inline" type="text" name="blankaddrname" size="25" maxlength="50" placeholder="Nombre">
					<input class="form-control form-inline" type="text" name="blankaddrnum" size="1" maxlength="4" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrportal" size="2" maxlength="4" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrstair" size="1" maxlength="4" placeholder="Esc" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrfloor" size="1" maxlength="4" placeholder="Piso">
					<input class="form-control form-inline" type="text" name="blankaddrdoor" size="2" maxlength="4" placeholder="Puerta" onkeyup="this.value=this.value.toUpperCase();">
					<br><br>
					
					<select class="form-control form-inline pull-right" name="blankaddrpostalcode" onchange="ajaxGetAddress(this.value)" style="margin-top:5px;">
						<option value="" selected>-- Código Postal --</option>
						<?php 
							//$cpCol = getDBDistCompleteColID('postalCode', 'postalCitiesES', 'postalCode');
							$xmlPostalCodes = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/common/data/postal_codes.xml');

							foreach ($xmlPostalCodes->provincia as $p) {
								foreach ($p->CodigoPostal as $cp) {
									$PostalCodeNumber = $cp['value'];
									echo "<option value=" . $PostalCodeNumber . ">" . $PostalCodeNumber . "</option>";
								}
							}
						?>
					</select>
					<div id="txtHint">
						<?php 
						echo '<select class="form-control" name="blankaddrcity" id="blankaddrcity" disabled style="margin-top:5px; width:60%">';
							echo '<option>Su localidad...</option>';
						echo '</select>';
						?>
					</div>
				</div>
			</div>	

			<div class="form-group"> <!-- Teléfono Móvil -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmobile">Tfno. Móvil: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blankmobile" maxlength="9" placeholder="[6-7]XXXXXXXX" required onkeypress="return checkOnlyNumbers(event)">
				</div>
			</div>

			<div class="form-group"> <!-- Otro Teléfono -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankphone">Otro Tfno.: </label> 
				<div class="col-sm-10">
					<!-- <input class="form-control" type="text" name="blankphone" maxlength="18" placeholder="00[COD. PAIS]-NUMERO" onkeypress="return checkDashedNumbers(event)"> -->
					<input class="form-control" type="text" name="blankphone" maxlength="18" placeholder="Ej. 0034-910000000" onkeypress="return checkDashedNumbers(event)">
				</div>
			</div>

			<div class="form-group"> <!-- Correo Electrónico -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmail">eMail: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type="email" name="blankmail" placeholder="correo@ejemplo.com" required>
				</div>
			</div>		

			<div class="form-group">  <!-- Carnet de Conducir -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankdrivingtype">Carnet Conducir: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankdrivingtype">
						<option value=""> Tipo </option>
						<option value="AM">AM</option>
						<option value="A">A</option>
						<option value="A1">A1</option>
						<option value="A2">A2</option>
						<option value="B">B</option>
						<option value="C">C</option>
						<option value="C1">C1</option>
						<option value="D">D</option>
						<option value="D1">D1</option>
						<option value="E">E</option>
						<option value="BTP">BTP</option>
					</select>				
				<!-- <input class='form-control form-inline' type='date' name='blankdrivingdate' id='blankdrivingdate' placeholder='aaaa-mm-dd' onChange="jsIsPreviousDate(this.id)"> -->
				<input class='form-control form-inline' type='date' name='blankdrivingdate' id='blankdrivingdate' placeholder='aaaa-mm-dd'>
				</div>				
			</div>
			
			<div class="form-group"> <!-- Estado Civil -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmarital">Estado Civil: </label> 
				<div class="col-sm-10">
					<select class="form-control" name="blankmarital">
						<option selected disabled value="">Estoy...</option>
						<?php
						$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						$maritStatus = getDBcompletecolumnID($userLang, 'maritalStatus', 'id');

						foreach($maritStatus as $i){
							echo "<option value=" . getDBsinglefield('key', 'maritalStatus', $userLang, $i) . ">" . $i . "</option>";
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group"> <!-- Hijos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksons">Hijos: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="number" name="blanksons" maxlength="2" min="0">
				</div>
			</div>		

			<div class="form-group"> <!-- Foto -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="foto">Foto: </label>
				<div class="col-sm-10">
					<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
					<p class="help-block">Tipos admitidos: JPG, JPEG o PNG. Máx: 1024Kb</p>
				</div>
			</div>

			<div class="form-group tooltip-demo"> <!-- Archivos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" ><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Tipos admitidos: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT o RTF. Máx: 1024Kb"></span> Archivos Adicionales: </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
				<div id="uploadFiles" class="col-sm-9">
					<input class="form-control" type="file" name="archivo" />	
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addFiles(this.form);" type="button"><span >Añadir otro Archivo</span></button></div>
				</div>
				</div>
				
				</div>
				
			<div class="form-group"> <!-- Nivel de Idiomas -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_idiomas">Idioma/s: * </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
					<div class="col-sm-6" id="uploadFormLanguage">
						<div id="txtHint2"></div>
						</select>
					</div>
					<div class="col-sm-5">
						<select class="form-control" name="add_nidiomas">
							<option selected value="null"> Pulse "+" tras elegir...</option>
							<option value="A1">A1</option>
							<option value="A2">A2</option>
							<option value="B1">B1</option>
							<option value="B2">B2</option>
							<option value="C1">C1</option>
							<option value="C2">C2</option>
							<option value="mothertongue">Lengua Materna</option>
						</select>
						<a href="http://europass.cedefop.europa.eu/es/resources/european-language-levels-cefr/cef-ell-document.pdf">NIVELES EUROPEOS – TABLA DE AUTO EVALUACIÓN (PDF)</a>
					</div>
					<div class="btn-toolbar col-sm-1">
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addLanguage(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				</div>
			</div>			

			<div class="form-group tooltip-demo"> <!-- Educación -->
				<!-- <label id="uploadFormLabel" class="control-label col-sm-2" for="add_educ"> Educacion: </label> --> 
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_educ"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Incluye los títulos que tengas de la siguiente forma: Titulo y Especialidad, Centro de estudios, Fechas de inicio y fin"></span> Educación: </label> 
				<div id="uploadFormDegree" class="col-sm-9">
					<input class="form-control" type="text" name="add_educ" placeholder='Pulse "+" tras incluir su educación... ' />					
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addDegree(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
				</div>
			</div>
			
			<div class="form-group tooltip-demo"> <!-- Profesión -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_prof"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Si su título no aparece en el listado, póngase en contacto con nosotros a través de administracion@perspectiva-alemania.com"></span> Profesión: *</label> 
				<div id="uploadFormProf" class="col-sm-9">
					<select class="form-control" name="add_prof">
						<option selected value=""> Pulse "+" tras elegir... </option>
						<?php 
							$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'careers', 'id');
							foreach($eduNames as $i){
								//echo "<option value=" . $i . ">" . $i . "</option>";
								//FpSOU=UZ
								//echo '<option value=' " . $i . " '>' . $i . '</option>';
								echo '<option value="'.$i.'">' . $i . '</option>';
							}
						?>
					</select>						
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addProf(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
				</div>
			</div>	

			<div class="form-group"> <!-- Trayectoria -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_career">¿Qué has hecho estos últimos años? </label> 
				<div class="col-sm-10" id="uploadFormCareer">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-5">
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_empr" placeholder="Empresa" />
								</div>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_categ" placeholder="Posición" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expstart" placeholder="Inicio" />
								</div>			
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expend" placeholder="Fin" />
								</div>
							</div>
						</div>
						<div class=" row col-sm-4">
							<div class="col-sm-10">
								<textarea class="form-control" name="add_desc" placeholder="Descripción del puesto"></textarea>
							</div>	
							<div class="btn-toolbar col-sm-1">
								<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addCareer(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
							</div>
						</div>					
					</div>
				</div>
			</div>		

			<div class="form-group"> <!-- Salario -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksalary">Salario deseado: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blanksalary" maxlength="7" placeholder="€ neto/año" onkeypress="return checkOnlyNumbers(event)">
				</div>
			</div>

			<div class="form-group"> <!-- Otros datos de Interés -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Otros datos de interés: </label> 
				<div class="col-sm-10">
					<textarea class="form-control" type="number" name="blankother" placeholder="Exponga aquí cualquier dato que estime oportuno y no aparezca en ningún otro campo..."></textarea>	
				</div>
			</div>		

			<div class="form-group"> <!-- 10 Tags -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Los 10 puntos clave de mi experiencia profesional</label> 
				<div class="col-sm-10">
					<?php
					
					$tipArray = array(1 => 'Estoy especializado en ...', 
									2 => 'En los últimos años he adquirido sólidos conocimientos y experiencia en el ámbito de ...', 
									3 => 'Tengo más de ...años de experiencia en',
									4 => 'Durante los últimos .. años he desarrollado mi actividad profesional en el sector ...', 
									5 => '...', 
									6 => '...', 
									7 => '...', 
									8 => '...', 
									9 => '...', 
									10 => '...');
					
					for ($i=1; $i <= 10 ; $i++) { 
						echo "	<div class='col-sm-5' style='margin-bottom: 10px;'>";
						//echo "		<input class='form-control' type='text' name='blankskill$i'>";
						echo "		<input class='form-control' type='text' name='blankskill$i' placeholder='$tipArray[$i]'>";
						echo "	</div>";
					}
					?>
				</div>
			</div>			
		</div> <!-- Panel Body -->

		<div class="panel-footer">
			<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="blanklopd" required> He leído y acepto las condiciones de uso y política de privacidad</label>
			<div class="btn-group pull-right">
				<!-- <button type="submit" name ="push_button" class="btn btn-primary" onclick="this.disabled=true; this.value='Enviando...'; this.form.submit();" >Enviar</button> -->
				<!-- <button type="submit" name ="push_button" class="btn btn-primary">Enviar</button> -->
				<!-- <button type="submit" name ="push_button" class="btn btn-primary" onclick=" return confirmFormSendES()">Enviar</button> -->
				<!-- <button type="submit" name ="push_button" class="btn btn-primary" onclick=" return confirmFormSendES(this.form)">Enviar</button> -->
				<!-- <button type="submit" name ="push_button" class="btn btn-primary">Enviar</button> -->
				<button type="submit" name ="push_button" class="btn btn-primary" onclick=" return confirmFormSendES(this.form)">Enviar</button>
			</div>
		</div> <!-- Panel Footer-->
	</div> <!-- Panel -->
</form>

</body>
</html>
