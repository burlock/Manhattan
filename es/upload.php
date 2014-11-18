<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Validación de formulario</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="../common/js/functions.js"></script>
	
	<script>
		//Functions used to add/remove in realtime Language fields 
		var rowNum = 0;
		function addLanguage(frm){
			//This 'if' prevents that any new Language field could be add by pressing "+" if one of the 2 language fields is empty
			if((frm.add_idiomas.value == '') || (frm.add_nidiomas.value == '')){
				return ;
			}
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowLanguage'+rowNum+'"> \
				<div class="col-sm-5"> \
					<input class="form-control" type="hidden" name="idiomas[]" value="'+frm.add_idiomas.value+'" > \
					<input class="form-control" type="text" name="idiomasf[]" value="'+frm.add_idiomas.value+'" disabled> \
				</div> \
				<div class="col-sm-5"> \
					<input class="form-control" type="hidden" name="nidiomas[]" value="'+frm.add_nidiomas.value+'" > \
					<input class="form-control" type="text" name="fnidiomas[]" value="'+frm.add_nidiomas.value+'" disabled> \
				</div> \
				<div class="btn-toolbar col-sm-1"> \
					<div class="btn-group btn-group-sm"> \
						<button type="button" class="btn btn-default" onclick="removeLanguage('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button> \
					</div> \
				</div> \
			</div>';
			jQuery('#uploadFormLanguage').append(row);
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
			if ((frm.add_educ.value == '') || (frm.addEducCenter.value == '') || (frm.addEducStart.value == '')){
				return ;
			}
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowDegree'+rowNum+'"> \
				<div class="col-sm-11"> \
					<div class="row"> \
						<div class="col-sm-12"> \
							<input class="form-control" type="hidden" name="educ[]" value="'+frm.add_educ.value+'"> \
							<input class="form-control" type="text" name="feduc[]" value="'+frm.add_educ.value+'" disabled> \
						</div> \
					</div> \
					<div class="row"> \
						<div class="col-sm-12"> \
							<input class="form-control" type="hidden" name="educCenter[]" value="'+frm.addEducCenter.value+'"> \
							<input class="form-control" type="text" name="feducCenter[]" value="'+frm.addEducCenter.value+'" disabled> \
						</div> \
					</div> \
					<div class="row"> \
						<div class="col-sm-4"> \
							<input class="form-control" type="hidden" name="educStart[]" value="'+frm.addEducStart.value+'"> \
							Inicio<input class="form-control" type="text" name="feducStart[]" value="'+frm.addEducStart.value+'" disabled> \
						</div> \
						<div class="col-sm-4"> \
							<input class="form-control" type="hidden" name="educEnd[]" value="'+frm.addEducEnd.value+'"> \
							Fin<input class="form-control" type="text" name="feducEnd[]" value="'+frm.addEducEnd.value+'" disabled> \
						</div> \
					</div> \
				</div> \
					<div class="btn-toolbar col-sm-1"> \
						<div class="btn-group btn-group-sm"> \
						<button type="button" class="btn btn-default" onclick="removeDegree('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button> \
					</div> \
				</div> \
			</div>';
			jQuery('#uploadFormDegree').append(row);
			frm.add_educ.value = '';
			frm.addEducCenter.value = '';
			frm.addEducStart.value = '';
			frm.addEducEnd.value = '';
		}
		
		function removeDegree(rnum){
			jQuery('#rowDegree'+rnum).remove();
		}
		
		//Functions to add/remove Career/Occupation (Proffession) fields in realtime 
		function addProf(frm){
			if (frm.add_prof.value == ''){
				return ;
			}
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowProf'+rowNum+'"> \
				<div class="col-sm-11"> \
					<input class="form-control" type="hidden" name="prof[]" value="'+frm.add_prof.value+'"> \
					<input class="form-control" type="text" name="fprof[]" value="'+frm.add_prof.value+'" disabled> \
				</div> \
				<div class="btn-toolbar col-sm-1"> \
					<div class="btn-group btn-group-sm"> \
						<button type="button" class="btn btn-default" onclick="removeProf('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button> \
					</div> \
				</div> \
			</div>';
			jQuery('#uploadFormProf').append(row);
			frm.add_prof.value = '';
		}
		
		function removeProf(rnum){
			jQuery('#rowProf'+rnum).remove();
		}
		
		//Functions to add/remove Experience fields in realtime 
		function addCareer(frm){
			if((frm.add_empr.value == '') || (frm.add_categ.value == '') || (frm.add_expcity.value == '') || (frm.add_expcountry.value == '') || (frm.add_expstart.value == '') || (frm.add_desc.value == '')){
				return ;
			}
			rowNum ++;
			var row ='<div class="row" style="padding-left: 0px; margin-bottom: 10px;" id="rowCareer'+rowNum+'"> \
				<div class="col-sm-11"> \
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
							<input class="form-control" type="hidden" name="expcity[]" value="'+frm.add_expcity.value+'"> \
							<input class="form-control" type="text" name="fexpcity[]" value="'+frm.add_expcity.value+'" readonly> \
						</div> \
						<div class="col-sm-6"> \
							<input class="form-control" type="hidden" name="expcountry[]" value="'+frm.add_expcountry.value+'" > \
							<input class="form-control" type="text" name="fexpcountry[]" value="'+frm.add_expcountry.value+'" disabled> \
						</div> \
					</div> \
					<div class="row"> \
						<div class="col-sm-6"> \
							<input class="form-control" type="hidden" name="expstart[]" value="'+frm.add_expstart.value+'"> \
							<input class="form-control" type="text" name="fexpstart[]" value="'+frm.add_expstart.value+'" disabled> \
						</div> \
						<div class="col-sm-6"> \
							<input class="form-control" type="hidden" name="expend[]" value="'+frm.add_expend.value+'"> \
							<input class="form-control" type="text" name="fexpend[]" value="'+frm.add_expend.value+'" disabled> \
						</div> \
					</div> \
				</div> \
				<div class=" row col-sm-12"> \
					<div class="col-sm-11"> \
						<input class="form-control" type="hidden" name="desc[]" value="'+frm.add_desc.value+'"></textarea> \
						<textarea class="form-control" name="fdesc[]" value="'+frm.add_desc.value+'" readonly>'+frm.add_desc.value+'</textarea> \
					</div> \
					<div class="btn-toolbar col-sm-1"> \
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="removeCareer('+rowNum+');" type="button"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div> \
					</div> \
				</div> \
			</div>';	
			jQuery('#uploadFormCareer').append(row);
			frm.add_empr.value = '';
			frm.add_categ.value = '';
			frm.add_expcity.value = '';
			frm.add_expcountry.value = '';
			frm.add_expstart.value = '';
			frm.add_expend.value = '';
			frm.add_desc.value = '';
		}
		
		function removeCareer(rnum){
			jQuery('#rowCareer'+rnum).remove();
		}
		
		//Function to add/remove Nationalities in realtime 
		function addNationality(frm){
			if (frm.add_nat.value == ''){
				return ;
			}
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowNationality'+rowNum+'"> \
				<div class="col-sm-10"> \
					<input class="form-control" type="text" name="nat[]" value="'+frm.add_nat.value+'" readonly> \
				</div> \
				<div class="btn-toolbar col-sm-1"> \
					<div class="btn-group btn-group-sm"> \
						<button type="button" class="btn btn-default" onclick="removeNationality('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button> \
					</div> \
				</div> \
			</div>';
			jQuery('#uploadFormNationality').append(row);
			frm.add_nat.value = '';
		}
		
		function removeNationality(rnum){
			jQuery('#rowNationality'+rnum).remove();
		}
		
		function addFiles(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowFiles'+rowNum+'"> \
				<div class="col-sm-9"> \
					<input class="form-control" type="file" name="archivo'+rowNum+'"> \
				</div> \
				<div class="btn-toolbar col-sm-1"> \
					<div class="btn-group btn-group-sm"> \
						<button type="button" class="btn btn-default" onclick="removeFiles('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button> \
					</div> \
				</div> \
			</div>';
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
				var cleared = document.getElementById(fileId).value = "";
				alert ("\'"+fileExt+"\' no es una extensión válida para su fotografía.");
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
				var cleared = document.getElementById(fileId).value = "";
				alert ("\'"+fileExt+"\' no es una extensión válida para su documento.");
				return false;
			}
		}
	</script>
</head>


<body>

<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');
	
	if(isset($_POST['push_button'])){
		
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/varAssignments.php';
		
		//This first validation lets the system avoid double-recording of the registry if form is refreshed by Candidate via 'CMD+R' or 'F5' in his/her keyboard
		if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])){
			//If CV had been previously saved user will be blocked and sent to loggin page
			executeDBquery("UPDATE `users` SET `active`='0', `cvSaved`='1' WHERE `login`='".$_SESSION['loglogin']."'");
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo "Usted ya ha guardado su CV. Por seguridad, su usuario ha sido desactivado."; ?>');
				window.location.href='./endsession.php';
			</script>
			<?php 
		}
		elseif(!checkFullName($_POST['blankname'], $_POST['blanksurname'], $userRow['language'], $outName, $outSurname, $checkError)){
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
				alert('Error: La fecha indica que no es mayor de edad o es incorrecta.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkDNI_NIE($_POST['blanknie'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Revise el NIE. El indicado es incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(getDBsinglefield(nie, cvitaes, nie, $_POST['blanknie'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: El NIE introducido ya existe en Base de Datos.');
				window.location.href='home.php';
			</script>
			<?php 
		}

		/* SE QUITA PROVISIONALMENTE ESTA COMPROBACIÓN POR NO CONSEGUIR CONTROLARLA DESDE JAVASCRIPT
		elseif(!isset($str_nat)){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: No se ha indicado Nacionalidad.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		*/
		//Sex and Type of address are automatically detected as restricted fields
		
		//Address won't be mandatory but, if included, will be necessary to fulfill 'type', 'name' and 'number'
		/*
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
			elseif(!checkFullAddress($_POST['blankaddrname'], $_POST['blankaddrnum'], $userRow['language'], $outAddrName, $outAddrNumber, $checkError)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('<?php echo $checkError; ?>');
					window.location.href='home.php';
				</script>
				<?php
			}
		}
		*/
		
		//Address won't be mandatory but, if included, will be necessary to fulfill 'type', 'name' and 'number'
		elseif(((strlen($_POST['blankaddrtype']) > 0) || (strlen($_POST['blankaddrname']) > 0) || (strlen($_POST['blankaddrnum']) > 0) || (strlen($_POST['blankaddrportal']) > 0) || 
		(strlen($_POST['blankaddrstair']) > 0) || (strlen($_POST['blankaddrfloor']) > 0) || (strlen($_POST['blankaddrdoor']) > 0)) && 
		((strlen($_POST['blankaddrtype']) < 1) || (strlen($_POST['blankaddrname']) < 1) || (strlen($_POST['blankaddrnum']) < 1))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: No se ha indicado tipo, nombre o número en la dirección.');
				window.location.href='home.php';
			</script>
			<?php
		}
		elseif(((strlen($_POST['blankaddrtype']) > 0) || (strlen($_POST['blankaddrname']) > 0) || (strlen($_POST['blankaddrnum']) > 0) || (strlen($_POST['blankaddrportal']) > 0) || 
		(strlen($_POST['blankaddrstair']) > 0) || (strlen($_POST['blankaddrfloor']) > 0) || (strlen($_POST['blankaddrdoor']) > 0)) && 
		(!checkFullAddress($_POST['blankaddrname'], $_POST['blankaddrnum'], $userRow['language'], $outAddrName, $outAddrNumber, $checkError))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php
		}
		
		elseif(!checkMobile($_POST['blankmobile'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: El número de móvil es incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		
		elseif((strlen($_POST['blankphone']) > 0) && (!checkPhone($_POST['blankphone']))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: El número de teléfono adicional es incorrecto.');
				window.location.href='home.php';
			</script>
			<?php 
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
		/* SE QUITA PROVISIONALMENTE ESTA COMPROBACIÓN POR NO CONSEGUIR CONTROLARLA DESDE JAVASCRIPT
		elseif($str_idiomas == '' || $str_nidiomas == '' || $str_nidiomas == '%null%'){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: No se ha indicado idioma y nivel. Indique al menos 1 de cada.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		*/
		/*
		elseif((strlen($_POST['blankdrivingtype']) > 0) || (strlen($_POST['blankdrivingdate']) > 0)){
			if(!checkDrivingLicense($_POST['blankdrivingtype'], $_POST['blankdrivingdate'], $userRow['language'], $checkError)){
				unset($_POST['push_button']);
				?>
				<script type="text/javascript">
					alert('<?php echo $checkError; ?>');
					window.location.href='home.php';
				</script>
				<?php 
			}
		}
		*/
		elseif(((strlen($_POST['blankdrivingtype']) > 0) || (strlen($_POST['blankdrivingdate']) > 0)) && (!checkDrivingLicense($_POST['blankdrivingtype'], $_POST['blankdrivingdate'], $userRow['language'], $checkError))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		
		//Only if EVERY check is OK can proceed with process to insert registry in DB
		else{
			$insertCVQuery = "INSERT INTO `cvitaes` (`nie`, `cvStatus`, `name`, `surname`, `birthdate`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, `phone`, 
			`postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, 
			`skill7`, `skill8`, `skill9`, `skill10`, `cvDate`, `userLogin`, `salary`) VALUES 
			('".$_POST['blanknie']."', 'pending', '".$outName."', '".$outSurname."', '".$_POST['blankbirthdate']."', '".$_POST['blanksex']."', '".$_POST['blankaddrtype']."', '".$outAddrName."', '".$outAddrNumber."', 
			'".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."', '".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', '".$_POST['blankaddrpostalcode']."', '".$strCountry."', 
			'".$_POST['blankaddrprovince']."', '".$_POST['blankaddrcity']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', 
			'".$_POST['blankmarital']."', '".$_POST['blanksons']."', '".$securedOther."', '".$securedSkill1."', '".$securedSkill2."', '".$securedSkill3."', '".$securedSkill4."', '".$securedSkill5."', 
			'".$securedSkill6."', '".$securedSkill7."', '".$securedSkill8."', '".$securedSkill9."', '".$securedSkill10."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."', '".$_POST['blanksalary']."')";
			
			
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
					alert('Hubo un problema guardando su CV.');
					window.location.href='home.php';
				</script>
				<?php 
			}
			else{
				//Each user's Language is saved in 'userLanguages' table.
				$auxCont = count($arrayLanguages);
				for($i=0; $i<$auxCont; $i++){
					executeDBquery("INSERT INTO `userLanguages` (`userNIE`, `keyLanguage`, `level`) VALUES ('".$_POST['blanknie']."', '".$arrayLanguages[$i]."', '".$arrayLangLevels[$i]."')");
				}
				
				//Each user's Education is saved in 'userEducations' table.
				$auxCont = count($securedEducTittle);
				for($i=0; $i<$auxCont; $i++){
					executeDBquery("INSERT INTO `userEducations` (`userNIE`, `educTittle`, `educCenter`, `educStart`, `educEnd`) VALUES 
					('".$_POST['blanknie']."', '".$securedEducTittle[$i]."', '".$securedEducCenter[$i]."', '".$arrayEducStart[$i]."', '".$arrayEducEnd[$i]."')");
				}
				
				//Each user's Occupation is saved in 'userOccupations' table.
				$auxCont = count($arrayOccupations);
				for($i=0; $i<$auxCont; $i++){
					executeDBquery("INSERT INTO `userOccupations` (`userNIE`, `keyOccupation`) VALUES ('".$_POST['blanknie']."', '".$arrayOccupations[$i]."')");
				}
				
				//Each user's Experience is saved in 'userExperiences' table.
				$auxCont = count($securedExperPosition);
				for($i=0; $i<$auxCont; $i++){
					/*
					executeDBquery("INSERT INTO `userExperiences` (`userNIE`, `company`, `position`, `start`, `end`, `city`, `country`, `description`) VALUES 
					('".$_POST['blanknie']."', '".$securedExperCompany[$i]."', '".$securedExperPosition[$i]."', '".$arrayExperStart[$i]."', '".$arrayExperEnd[$i]."', 
					'".$securedExperCity[$i]."', '".$securedExperCountry[$i]."', '".$securedExperDescription[$i]."')");
					*/
					executeDBquery("INSERT INTO `userExperiences` (`userNIE`, `expCompany`, `expPosition`, `expStart`, `expEnd`, `expCity`, `expCountry`, `expDescription`) VALUES 
					('".$_POST['blanknie']."', '".$securedExperCompany[$i]."', '".$securedExperPosition[$i]."', '".$arrayExperStart[$i]."', '".$arrayExperEnd[$i]."', 
					'".$securedExperCity[$i]."', '".$securedExperCountry[$i]."', '".$securedExperDescription[$i]."')");
				}
				
				//Each user's Nationality/Country is saved in 'userCountries' table.
				$auxCont = count($arrayCountries);
				for($i=0; $i<$auxCont; $i++){
					//executeDBquery("INSERT INTO `userCountries` (`userNIE`, `keyCountry`) VALUES ('".$_POST['blanknie']."', '".$arrayCountries[$i]."')");
					executeDBquery("INSERT INTO `userNationalities` (`userNIE`, `keyCountry`) VALUES ('".$_POST['blanknie']."', '".$arrayCountries[$i]."')");
				}
				
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
				/*
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
							window.location.href='endsession.php';
						</script>
						<?php 
					}
				}
				*/
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
							alert('Ha habido un problema al guardar su foto (code PUPLOAD0). No obstante su CV ha sido guardado con éxito.');
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
					alert('Gracias por completar tu CV. En breve nos pondremos en contacto contigo.\nRecuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');
					window.location.href='./endsession.php';
				</script>
				<?php
			}
		}//End of DB registry saving, and all its corresponding files
		
	}//For (isset($_POST['push_button'])) that check whether FORM has being sent or not

	/**********************************     End of FORM validations     **********************************/
	
	
	/******************************     Start of WebPage code as showed     ******************************/
?>
<!-- EN CADA CAMPO COMPROBARÉ SI EL USUARIO YA INSERTÓ PREVIAMENTE EL CV if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])) -->

Los campos que poseen * son obligatorios.

<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group"> <!-- Nombre -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankname">Nombre: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blankname' minlength='3' maxlength='50' placeholder="Min. 3 caracteres" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Apellidos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksurname">Apellidos: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blanksurname' maxlength='50' placeholder="Min. 3 caracteres" required/>
				</div>
			</div>
			
			<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankbirthdate">Fecha de Nacimiento: * </label>
				<div class="col-sm-3">
					<input class="form-control" type='date' name='blankbirthdate' id='blankbirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
				</div>
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanknie">DNI/NIE: * </label>
				<div class="col-sm-5">
					<input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE();" required/>
				</div>
			</div>
			
			<div class="form-group"> <!-- Sexo & Nacionalidad -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksex">Sexo: * </label>
				<div class="col-sm-3">
					<div class='radio-inline'>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='0' required>Hombre</label>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='1'>Mujer</label>
					</div>
				</div>
			
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_nat">Nationalidad: * </label> 
				<div class="col-sm-4" id="uploadFormNationality">
					<select class="form-control" name="add_nat" >
						<option value="" selected disabled> Pulse "+" tras elegir... </option>
						<option value="Spanien"> España </option>
						<?php 
						$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						$countryName = getDBcompletecolumnID($userLang, 'countries', $userLang);
						foreach($countryName as $i){
							//Allways saved in german, to make it easier to show it later when searching CVs
							//echo '<option value="' . getDBsinglefield('key', 'countries', $userLang, $i) . '">' . $i . '</option>';
							echo '<option value="' . getDBsinglefield('german', 'countries', $userLang, $i) . '">' . $i . '</option>';
						}
						?>
					</select>
				</div>
				<div class="btn-toolbar col-sm-1">
					  <div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="addNationality(this.form);"><span class="glyphicon glyphicon-plus"></span></button></div>	
				</div>
			</div>
			
			<div class="form-group">  <!-- Dirección -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrtype">Dirección: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankaddrtype" >
						<?php
						echo "<option value='' selected>-- Tipo --</option>";
						$addressTypes = getDBcompletecolumnID('key', 'addressTypes', 'id');
						foreach($addressTypes as $i){
							echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
						}
						?>
					</select>					
					<input class="form-control form-inline" type="text" name="blankaddrname" size="25" maxlength="50" placeholder="Nombre">
					<input class="form-control form-inline" type="text" name="blankaddrnum" size="1" maxlength="4" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrportal" size="2" maxlength="4" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrstair" size="1" maxlength="4" placeholder="Esc" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrfloor" size="1" maxlength="4" placeholder="Piso">
					<input class="form-control form-inline" type="text" name="blankaddrdoor" size="2" maxlength="4" placeholder="Puerta" onkeyup="this.value=this.value.toUpperCase();">
					<br><br>
					
					<select class="form-control form-inline pull-right" name="blankaddrpostalcode" onchange="ajaxGetAddress(this.value)" style="margin-top:5px;">
						<option value="" selected>-- Código postal --</option>
						<?php 
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
							echo '<option>Indique su Código postal...</option>';
						echo '</select>';
						?>
					</div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmobile">Tfno. móvil: * </label> 
				<div class="col-sm-4">
					<input class="form-control" type="text" name="blankmobile" maxlength="9" placeholder="[6-7]XXXXXXXX" required onkeypress="return checkOnlyNumbers(event)">
				</div>
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankphone">Otro teléfono: </label> 
				<div class="col-sm-4">
					<input class="form-control" type="text" name="blankphone" maxlength="18" placeholder="Ej. 0034-910000000" onkeypress="return checkDashedNumbers(event)">
				</div>
			</div>
			
			<div class="form-group"> <!-- Correo Electrónico -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmail">E-mail: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type="email" name="blankmail" placeholder="correo@ejemplo.com" required>
				</div>
			</div>		

			<div class="form-group">  <!-- Carnet de Conducir -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankdrivingtype">Carnet de conducir: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankdrivingtype" >
						<?php
						echo "<option value='' selected>-- Tipo --</option>";
						$drivingTypes = getDBcompletecolumnID('key', 'drivingTypes', 'id');
						foreach($drivingTypes as $i){
							echo '<option value='.$i.'>'.$i.'</option>';
						}
						?>
					</select>
				<input class='form-control form-inline' type='date' name='blankdrivingdate' id='blankdrivingdate' placeholder='aaaa-mm-dd'>
				</div>
			</div>
			
			<div class="form-group"> <!-- Estado Civil  & Hijos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmarital">Estado civil: </label> 
				<div class="col-sm-4">
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
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksons">Hijos: </label> 
				<div class="col-sm-4">
					<input class="form-control" type="number" name="blanksons" maxlength="2" min="0" onkeypress="return checkOnlyNumbers(event)">
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
				<label id="uploadFormLabel" class="control-label col-sm-2" ><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Tipos admitidos: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT o RTF. Máx: 1024Kb"></span> Documentos adicionales: </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
				<div id="uploadFiles" class="col-sm-9">
					<input class="form-control" type="file" name="archivo" />	
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addFiles(this.form);" type="button"><span >Añadir otro archivo</span></button></div>
				</div>
				</div>
			</div>

			<div class="form-group"> <!-- Nivel de Idiomas -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_idiomas">Idioma/s: * </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
					<div class="col-sm-7" id="uploadFormLanguage">
						<select class="form-control" name="add_idiomas">
						<option selected disabled value=""> Pulse "+" tras elegir... </option>
						<?php
							$langNames = getDBcompletecolumnID($userLang, 'languages', $userLang);
							
							foreach($langNames as $i){
							$resultado = strpos($_SESSION['langselected'], $i);
								if ($resultado == FALSE){
									echo "<option value=" . getDBsinglefield('key', 'languages', $userLang, $i) . ">" . $i ."</option>";
								}
							}
							?>
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control" name="add_nidiomas">
							<option selected value=""> Pulse "+" tras elegir...</option>
							<option value="A1">A1</option>
							<option value="A2">A2</option>
							<option value="B1">B1</option>
							<option value="B2">B2</option>
							<option value="C1">C1</option>
							<option value="C2">C2</option>
							<option value="mothertongue">Lengua materna</option>
						</select>
						<a href="http://europass.cedefop.europa.eu/es/resources/european-language-levels-cefr/cef-ell-document.pdf">Tabla de Niveles europeos</a>
					</div>
					<div class="btn-toolbar col-sm-1">
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addLanguage(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				</div>
			</div>
			
			<div class="form-group tooltip-demo"> <!-- Educación -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_educ"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Incluye los títulos que tengas de la siguiente forma: Titulo y Especialidad, Centro de estudios, Fechas de inicio y fin"></span> Educación: *</label> 
				<div class="col-sm-10" id="uploadFormDegree">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-11">
							<div class="row">
								<div class="col-sm-12">
									<input class="form-control" type="text" name="add_educ" placeholder='Título (Pulse "+" tras elegir su educación)' />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<input class="form-control" type="text" name="addEducCenter" placeholder="Centro Educativo" />
								</div>
							</div>
							<div class="row">
								<label id="uploadFormLabel" class="control-label col-sm-2">Inicio</label>
								<div class="col-sm-4">
									<input class="form-control" type="date" name="addEducStart" />
								</div>			
								<label id="uploadFormLabel" class="control-label col-sm-2">Fin</label>
								<div class="col-sm-4">
									<input class="form-control" type="date" name="addEducEnd" />
								</div>
							</div>
						</div>
						<div class="btn-toolbar col-sm-1">
							<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addDegree(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group tooltip-demo"> <!-- Profesión -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_prof"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Si su título no aparece en el listado, seleccione Otra y póngase en contacto con nosotros a traves de administracion@perspectiva-alemania.com"></span> Profesión: *</label>
				<div id="uploadFormProf" class="col-sm-9">
					<select class="form-control" name="add_prof">
						<option selected value=""> Pulse "+" tras elegir... </option>
						<option value="other"> Otra </option>
						<?php 
							//$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'careers', 'id');
							$eduNames = getDBcompleteColumnID($userRow['language'], 'careers', 'id');
							foreach($eduNames as $i){
								//echo '<option value="'.$i.'">' . $i . '</option>';
								echo '<option value="'.getDBsinglefield('german', 'careers', $userRow['language'], $i).'">' . $i . '</option>';
							}
						?>
					</select>
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addProf(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Trayectoria/Experiencia -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_career">¿Qué has hecho estos últimos años? </label> 
				<div class="col-sm-10" id="uploadFormCareer">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-11">
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
									<input class="form-control" type="text" name="add_expcity" placeholder="Ciudad" />
								</div>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_expcountry" placeholder="País" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expstart" />
								</div>			
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expend" />
								</div>
							</div>
						</div>
						<div class=" row col-sm-12">
							<div class="col-sm-11">
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
					
					$tipArray = array(1 => 'Estoy especializado en...', 
									2 => 'En los últimos años he adquirido sólidos conocimientos y experiencia en el ámbito de...', 
									3 => 'Tengo más de... años de experiencia en...',
									4 => 'Durante los últimos... años he desarrollado mi actividad profesional en el sector...', 
									5 => '...', 
									6 => '...', 
									7 => '...', 
									8 => '...', 
									9 => '...', 
									10 => '...');
					
					for ($i=1; $i <= 10 ; $i++) { 
						echo "<div class='col-sm-6' style='margin-bottom: 10px;'>";
						echo "<input class='form-control' type='text' name='blankskill$i' maxlength='100' placeholder='$tipArray[$i]'>";
						echo "</div>";
					}
					?>
				</div>
			</div>			
		</div> <!-- Panel Body -->

		<div class="panel-footer">
			<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="blanklopd" required> He leído y acepto las <a href="javascript:alert('Recuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');">condiciones de uso</a> y política de privacidad</label>
			<div class="btn-group pull-right">
				<button type="submit" name ="push_button" class="btn btn-primary" onclick="return confirmFormSend(formu, '<?php echo getCurrentLanguage($_SERVER['SCRIPT_NAME']); ?>');">Enviar</button>
			</div>
		</div> <!-- Panel Footer-->
	</div> <!-- Panel -->
</form>

</body>
</html>
