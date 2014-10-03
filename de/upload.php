<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Formularvalidierung</title>
	
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
							Beginn<input class="form-control" type="text" name="feducStart[]" value="'+frm.addEducStart.value+'" disabled> \
						</div> \
						<div class="col-sm-4"> \
							<input class="form-control" type="hidden" name="educEnd[]" value="'+frm.addEducEnd.value+'"> \
							Ende<input class="form-control" type="text" name="feducEnd[]" value="'+frm.addEducEnd.value+'" disabled> \
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
				alert ("\'"+fileExt+"\' es ist keine gültige erweiterung für ihre fotografie.");
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
				alert ("\'"+fileExt+"\' es ist keine gültige erweiterung für ihre dokument.");
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
		
		//At the very beggining I will ensure that no local var has a previous value
		unset($key);
		unset($entry);
		unset($str_idiomas);
		unset($str_nidiomas);
		unset($str_educ);
		unset($strEducCenter);
		unset($strEducStart);
		unset($strEducEnd);
		unset($str_prof);
		unset($str_empr);
		unset($str_categ);
		unset($str_expstart);
		unset($str_expend);
		unset($str_expcity);
		unset($str_expcountry);
		unset($str_desc);
		unset($str_nat);
		unset($outNations);
		unset($outName);
		unset($outSurname);
		unset($checkError);
		unset($outAddrName);
		unset($outAddrNumber);
		unset($cleanedOther);
		unset($cleanedSkill1);
		unset($cleanedSkill2);
		unset($cleanedSkill3);
		unset($cleanedSkill4);
		unset($cleanedSkill5);
		unset($cleanedSkill6);
		unset($cleanedSkill7);
		unset($cleanedSkill8);
		unset($cleanedSkill9);
		unset($cleanedSkill10);
		unset($userDir);
		unset($insertCVQuery);
		unset($photoUploadFile);
		unset($image);
		unset($strCountry);
		
		//The very first validation will be LOPD checkbox
		foreach ($_POST as $key => $entry){
			if(is_array($entry)){
				if($key == idiomas){
					$str_idiomas = implode('|',$entry);
				}
				if($key == nidiomas){
					$str_nidiomas = implode('|',$entry);
				}
				if($key == educ){
					//Must be checked with htmlentities
					$str_educ = implode('|', $entry);
					$str_educ = trim(htmlentities($str_educ));
				}
				if($key == educCenter){
					//Must be checked with htmlentities
					$strEducCenter = implode('|', $entry);
					$strEducCenter = trim(htmlentities($strEducCenter));
				}
				if($key == educStart){
					$strEducStart = implode('|',$entry);
				}
				if($key == educEnd){
					$strEducEnd = implode('|',$entry);
				}
				if($key == prof){
					//No need to check it as it becomes from a 'select'
					$str_prof = implode('|', $entry);
				}
				if($key == empr){
					//Must be checked with htmlentities
					$str_empr = implode('|',$entry);
					$str_empr = trim(htmlentities($str_empr));
				}
				if($key == categ){
					//Must be checked with htmlentities
					$str_categ = implode('|',$entry);
					$str_categ = trim(htmlentities($str_categ));
				}
				if($key == expstart){
					$str_expstart = implode('|',$entry);
				}
				if($key == expend){
					$str_expend = implode('|',$entry);
				}
				if($key == expcity){
					//Must be checked with htmlentities
					$str_expcity = implode('|',$entry);
					$str_expcity = trim(htmlentities($str_expcity));
				}
				if($key == expcountry){
					//Must be checked with htmlentities
					$str_expcountry = implode('|',$entry);
					$str_expcountry = trim(htmlentities($str_expcountry));
				}
				if($key == desc){
					//Must be checked with htmlentities
					$str_desc = implode('|',$entry);
					$str_desc = trim(htmlentities($str_desc));
				}
				if($key == nat){
					//str_nat es 'nationalities' en la BD (en addRow5)
					if(isset($key)){
						//This is made to avoid as possible SQL Injection
						checkNationality($entry, $outNations);
						$str_nat = $outNations;
					}
				}
			 }
		}
		//This first validation lets the system avoid double-recording of the registry if form is refreshed by Candidate via 'CMD+R' or 'F5' in his/her keyboard
		if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])){
			//If CV had been previously saved user will be blocked and sent to loggin page
			executeDBquery("UPDATE `users` SET `active`='0', `cvSaved`='1' WHERE `login`='".$_SESSION['loglogin']."'");
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('<?php echo "Sie haben bereits ihren lebenslauf gespeichert. Aus sicherheitsgründen wurde ihre benutzer deaktiviert."; ?>');
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
				alert('Error: Das datum gibt an dass es nicht volljährig oder falsch ist.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkDNI_NIE($_POST['blanknie'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Überprüfen sie den NIE. Die angezeigte zahl ist falsch.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(getDBsinglefield(nie, cvitaes, nie, $_POST['blanknie'])){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Der eingegebene NIE ist bereits in der datenbank vorhanden.');
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
				alert('Error: Typ, namem oder nummer in der adresse nicht angezeigt.');
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
				alert('Error: Die handy nummer ist falsch.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		
		elseif((strlen($_POST['blankphone']) > 0) && (!checkPhone($_POST['blankphone']))){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: Zusätzliche telefonnummer ist falsch.');
				window.location.href='home.php';
			</script>
			<?php 
		}
		
		elseif(!filter_var($_POST['blankmail'], FILTER_VALIDATE_EMAIL)){
			unset($_POST['push_button']);
			?>
			<script type="text/javascript">
				alert('Error: E-mail ist falsch.');
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
			
			//One last change. If Candidate introduced a Postal code, as it only permits "España", we change it to "Spanien", just to be in german when showing CV
			if($_POST['blankaddrcountry'] == 'España'){
				$strCountry = 'Spanien';
			}
			else{
				$strCountry = $_POST['blankaddrcountry'];
			}
			
			$insertCVQuery = "INSERT INTO `cvitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
			`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `educTittle`, `educCenter`, `educStart`, `educEnd`, `career`, 
			`experCompany`, `experStart`, `experEnd`, `experPos`, `experDesc`, `experCity`, `experCountry`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, 
			`cvDate`, `userLogin`, `salary`) VALUES 
			(NULL, '".$_POST['blanknie']."', 'pending', '".$outName."', '".$outSurname."', '".$_POST['blankbirthdate']."', '".$str_nat."', '".$_POST['blanksex']."', '".$_POST['blankaddrtype']."', '".$outAddrName."', 
			'".$outAddrNumber."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."', '".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', 
			'".$_POST['blankaddrpostalcode']."', '".$strCountry."', '".$_POST['blankaddrprovince']."', '".$_POST['blankaddrcity']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', 
			'".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', '".$str_idiomas."', '".$str_nidiomas."', '".$str_educ."', '".$strEducCenter."', 
			'".$strEducStart."', '".$strEducEnd."', '".$str_prof."', '".$str_empr."', '".$str_expstart."', '".$str_expend."', '".$str_categ."', '".$str_desc."', '".$str_expcity."', '".$str_expcountry."', '".$cleanedOther."', 
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
					alert('Es gab ein problem speichern ihren lebenslauf.');
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
		}
		
	}//For (isset($_POST['push_button'])) that check whether FORM has being sent or not

	/**********************************     End of FORM validations     **********************************/
	
	
	/******************************     Start of WebPage code as showed     ******************************/
?>
<!-- EN CADA CAMPO COMPROBARÉ SI EL USUARIO YA INSERTÓ PREVIAMENTE EL CV if(getDBsinglefield('cvSaved', 'users', 'login', $_SESSION['loglogin'])) -->

Die Felder mit * sind Pflichtfelder.

<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group"> <!-- Nombre -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankname">Namen: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blankname' minlength='3' maxlength='50' placeholder="Min. 3 zeichen" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Apellidos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksurname">Nachnamen: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blanksurname' maxlength='50' placeholder="Min. 3 zeichen" required/>
				</div>
			</div>
			
			<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankbirthdate">Geburtsdatum: * </label>
				<div class="col-sm-3">
					<input class="form-control" type='date' name='blankbirthdate' id='blankbirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
				</div>
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanknie">Personalausweis: * </label>
				<div class="col-sm-5">
					<input class="form-control" type='text' name='blanknie' id='blanknie' maxlength="9" placeholder="12345678L (8 Zahlen) / X1234567T (7 Zahlen)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE();" required/>
				</div>
			</div>
			
			<div class="form-group"> <!-- Sexo & Nacionalidad -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksex">Geschlecht: * </label>
				<div class="col-sm-3">
					<div class='radio-inline'>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='0' required>Mann</label>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='1'>Frau</label>
					</div>
				</div>
			
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_nat">Nationalität: * </label> 
				<div class="col-sm-4" id="uploadFormNationality">
					<select class="form-control" name="add_nat" >
						<option value="" selected disabled> Presse "+" nach wahl... </option>
						<option value="Spanien"> Spanien </option>
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
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrtype">Adresse: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankaddrtype" >
						<?php
						echo "<option value='' selected>-- Typ --</option>";
						$addressTypes = getDBcompletecolumnID('key', 'addressTypes', 'id');
						foreach($addressTypes as $i){
							echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
						}
						?>
					</select>					
					<input class="form-control form-inline" type="text" name="blankaddrname" size="25" maxlength="50" placeholder="Namen">
					<input class="form-control form-inline" type="text" name="blankaddrnum" size="1" maxlength="4" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrportal" size="2" maxlength="4" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrstair" size="1" maxlength="4" placeholder="Leiter" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrfloor" size="1" maxlength="4" placeholder="Boden">
					<input class="form-control form-inline" type="text" name="blankaddrdoor" size="2" maxlength="4" placeholder="Tor" onkeyup="this.value=this.value.toUpperCase();">
					<br><br>
					
					<select class="form-control form-inline pull-right" name="blankaddrpostalcode" onchange="ajaxGetAddress(this.value)" style="margin-top:5px;">
						<option value="" selected>-- Postleitzahl --</option>
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
							echo '<option>Geben sie ihre Postleitzahl...</option>';
						echo '</select>';
						?>
					</div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmobile">Handy: * </label> 
				<div class="col-sm-4">
					<input class="form-control" type="text" name="blankmobile" maxlength="9" placeholder="[6-7]XXXXXXXX" required onkeypress="return checkOnlyNumbers(event)">
				</div>
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankphone">Zusätzliche Tlf.: </label> 
				<div class="col-sm-4">
					<input class="form-control" type="text" name="blankphone" maxlength="18" placeholder="Bei. 0034-910000000" onkeypress="return checkDashedNumbers(event)">
				</div>
			</div>
			
			<div class="form-group"> <!-- Correo Electrónico -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmail">E-mail: * </label> 
				<div class="col-sm-10">
					<input class="form-control" type="email" name="blankmail" placeholder="email@beispiel.com" required>
				</div>
			</div>		

			<div class="form-group">  <!-- Carnet de Conducir -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankdrivingtype">Führerschein: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankdrivingtype" >
						<?php
						echo "<option value='' selected>-- Typ --</option>";
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
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmarital">Familienstand: </label> 
				<div class="col-sm-4">
					<select class="form-control" name="blankmarital">
						<option selected disabled value="">Ich bin...</option>
						<?php
						$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						$maritStatus = getDBcompletecolumnID($userLang, 'maritalStatus', 'id');

						foreach($maritStatus as $i){
							echo "<option value=" . getDBsinglefield('key', 'maritalStatus', $userLang, $i) . ">" . $i . "</option>";
						}
						?>
					</select>
				</div>
				
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksons">Kinder: </label> 
				<div class="col-sm-4">
					<input class="form-control" type="number" name="blanksons" maxlength="2" min="0" onkeypress="return checkOnlyNumbers(event)">
				</div>
			</div>
			
			<div class="form-group"> <!-- Foto -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="foto">Fotografie: </label>
				<div class="col-sm-10">
					<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
					<p class="help-block">Unterstützte typen: JPG, JPEG o PNG. Máx: 1024Kb</p>
				</div>
			</div>

			<div class="form-group tooltip-demo"> <!-- Archivos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" ><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Unterstützte typen: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT o RTF. Máx: 1024Kb"></span> zusätzliche Dokumente: </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
				<div id="uploadFiles" class="col-sm-9">
					<input class="form-control" type="file" name="archivo" />	
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addFiles(this.form);" type="button"><span >Eine weitere datei hinzufügen</span></button></div>
				</div>
				</div>
			</div>

			<div class="form-group"> <!-- Nivel de Idiomas -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_idiomas">Sprachkenntnisse: * </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
					<div class="col-sm-7" id="uploadFormLanguage">
						<select class="form-control" name="add_idiomas">
						<option selected disabled value=""> Presse "+" nach wahl... </option>
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
							<option selected value=""> Presse "+" nach wahl...</option>
							<option value="A1">A1</option>
							<option value="A2">A2</option>
							<option value="B1">B1</option>
							<option value="B2">B2</option>
							<option value="C1">C1</option>
							<option value="C2">C2</option>
							<option value="mothertongue">Muttersprache</option>
						</select>
						<a href="http://europass.cedefop.europa.eu/de/resources/european-language-levels-cefr/cef-ell-document.pdf">Tabelle der europäischen Ebene</a>
					</div>
					<div class="btn-toolbar col-sm-1">
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addLanguage(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Educación -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_educ"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Umfasst die titel, die sie auf folgende weise haben: Titel und Spezialität, Zentrum der Studien, Anfangs und Enddatum"></span> Ausbildung: </label>
				<div class="col-sm-10" id="uploadFormDegree">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-11">
							<div class="row">
								<div class="col-sm-12">
									<input class="form-control" type="text" name="add_educ" placeholder='Drücken sie auf "+" nach wie ausbildung... ' />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<input class="form-control" type="text" name="addEducCenter" placeholder="Lernzentrum" />
								</div>
							</div>
							<div class="row">
								<label id="uploadFormLabel" class="control-label col-sm-2">Beginn</label>
								<div class="col-sm-4">
									<input class="form-control" type="date" name="addEducStart" />
								</div>			
								<label id="uploadFormLabel" class="control-label col-sm-2">Ende</label>
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
				<!-- <label id="uploadFormLabel" class="control-label col-sm-2" for="add_prof"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn ihr titel nicht in der liste angezeigt wird, wählen sie andere und kontaktieren sie uns bitte über administracion@perspectiva-alemania.com"></span> Beruf: *</label> -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_prof"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title=""></span> Beruf: *</label>
				<div id="uploadFormProf" class="col-sm-9">
					<select class="form-control" name="add_prof">
						<option selected value=""> Presse "+" nach wahl... </option>
						<option value="other"> Andere </option>
						<?php 
							$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'careers', 'id');
							foreach($eduNames as $i){
								echo '<option value="'.$i.'">' . $i . '</option>';
							}
						?>
					</select>
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addProf(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Trayectoria/Experiencia -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_career">Was haben sie in den letzten jahren getan? </label> 
				<div class="col-sm-10" id="uploadFormCareer">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-11">
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_empr" placeholder="Unternehmen" />
								</div>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_categ" placeholder="Stellung" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_expcity" placeholder="Stadt" />
								</div>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_expcountry" placeholder="Staat" />
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
								<textarea class="form-control" name="add_desc" placeholder="Stellenbeschreibung"></textarea>
							</div>	
							<div class="btn-toolbar col-sm-1">
								<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addCareer(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
							</div>
						</div>					
					</div>
				</div>
			</div>
			
			<div class="form-group"> <!-- Salario -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksalary">Gehaltsvorstellung: </label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blanksalary" maxlength="7" placeholder="€ netto/jahr" onkeypress="return checkOnlyNumbers(event)">
				</div>
			</div>

			<div class="form-group"> <!-- Otros datos de Interés -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Weitere Angaben: </label>
				<div class="col-sm-10">
					<textarea class="form-control" type="number" name="blankother" placeholder="Schreiben sie hier alle informationen, die angemessen und erscheint nicht in anderen bereichen..."></textarea>	
				</div>
			</div>		

			<div class="form-group"> <!-- 10 Tags -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Wesentliche berufliche Inhalte</label>
				<div class="col-sm-10">
					<?php
					
					$tipArray = array(1 => 'Ich bin spezialisiert auf...', 
									2 => 'In den letzten jahren ich gewonnen haben solide kenntnisse und erfahrungen im bereich der...', 
									3 => 'Ich habe mehr als... jahrelange erfahrung in...',
									4 => 'In den letzten... jahren haben meine berufliche tätigkeit im bereich entwickelt...', 
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
			<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="blanklopd" required> Ich habe die <a href="javascript:alert('Recuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');">Nutungzbedingungen</a> und Datenschutzbestimmungen gelesen und akzeptiert.</label>
			<div class="btn-group pull-right">
				<button type="submit" name ="push_button" class="btn btn-primary" onclick="return confirmFormSend(formu, '<?php echo getCurrentLanguage($_SERVER['SCRIPT_NAME']); ?>');">Senden</button>
			</div>
		</div> <!-- Panel Footer-->
	</div> <!-- Panel -->
</form>

</body>
</html>
