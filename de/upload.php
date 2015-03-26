<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Formularvalidierung</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="../common/js/functions.js"></script>
	
	<?php include $_SERVER[DOCUMENT_ROOT] . '/common/code/uploadHeadScripts.php'; ?>
</head>


<body>
<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');
	
	
	/* ***********************************    Start of FORM validations    *********************************** */
	if(isset($_POST[push_button])){
		
		/* **********  This file will do every needed checking in upload  ********** */
		
		include $_SERVER[DOCUMENT_ROOT] . '/common/code/uploadCheckings.php';
		
		/* **********  This file will do every needed checking in upload  ********** */
		
	}//isset($_POST[push_button])
	/* -----------------------------------     End of FORM validations     ----------------------------------- */
	
	
	//Part of code executed only when FORM is not sent yet
	/******************************     Start of WebPage code as showed     ******************************/
	else{
		//----  Start of ACTIVE Candidate. A CV was already saved, and that info now appears in each field  ----//
		if(getDBsinglefield(cvSaved, users, login, $_SESSION[loglogin]) == 1){
			$cvRow = getDBrow2(cvitaes, userLogin, $_SESSION[loglogin], firstCV, 1);
			
			?>
			<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">Die Felder mit * sind Pflichtfelder</div>
					<div class="panel-body">
						<fieldset> <!-- Datos Personales del Candidato -->
							<div class="form-group"> <!-- Nombre -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvName">Namen: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvName' minlength='3' maxlength='50' placeholder="Min. 3 Buchstaben" value="<?php echo $cvRow[name]; ?>" required/>
								</div>
							</div> <!-- Fin Nombre -->
							
							<div class="form-group"> <!-- Apellidos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSurname">Nachnamen: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvSurname' maxlength='50' placeholder="Min. 3 Buchstaben" value="<?php echo $cvRow[surname]; ?>" required/>
								</div>
							</div> <!-- Fin Apellidos -->
							
							<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvBirthdate">Geburtsdatum: * </label>
								<div class="col-sm-3">
									<?php if(isset($_SESSION[cvBirthdate])){ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $_SESSION[cvBirthdate]; ?>" required/>
										<?php 
										unset($_SESSION[cvBirthdate]);
										} else{ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $cvRow[birthdate] ?>" required/>
										<?php } ?>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvnie">Personalausweis: * </label>
								<div class="col-sm-5">
									<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
									<input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" value="<?php echo $cvRow[nie] ?>" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE_ES();"required/>
								</div>
							</div> <!-- Fin Fecha de Nacimiento & DNI/NIE -->
							
							<div class="form-group tooltip-demo"> <!-- Sexo & Nacionalidad -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSex">Geschlecht: * </label>
								<div class="col-sm-3">
									<div class='radio-inline'>
										<?php if($cvRow[sex] == 0){ ?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" required checked="checked" />Mann</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" />Frau</label>
										<?php } else{ ?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" required />Mann</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" checked="checked" />Frau</label>
										<?php } ?>
									</div>
								</div>
							
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvNation"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Sie mehr als eine haben, wählen Sie alle bei gedrückter 'Strg'-Taste"></span> Nationalität: * </label>
								<div class="col-sm-5" id="uploadFormNationality">
									<select class="form-control" name="cvNation[]" multiple="multiple" required>
										<option value="Spanien"> Spanien </option>
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
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvAddrType">Adresse: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvAddrType">
										<?php
										echo "<option value=''>-- Typ --</option>";
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
								
									<input class="form-control" type="text" name="cvAddrName" size="34" maxlength="50" placeholder="Namen' name" value="<?php echo $cvRow[addrName]; ?>">
									<input class="form-control" type="text" name="cvAddrNum" size="3" maxlength="4" placeholder="Num" value="<?php echo $cvRow[addrNum]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrPortal" size="3" maxlength="4" placeholder="Portal" value="<?php echo $cvRow[portal]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrStair" size="3" maxlength="4" placeholder="Leiter" value="<?php echo $cvRow[stair]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrFloor" size="3" maxlength="4" placeholder="Boden" value="<?php echo $cvRow[addrFloor]; ?>">
									<input class="form-control" type="text" name="cvAddrDoor" size="3" maxlength="4" placeholder="Tor" value="<?php echo $cvRow[addrDoor]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrCity" size="45" maxlength="50" placeholder="Stadt" value="<?php echo $cvRow[city]; ?>">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrPostalCode" size="25" maxlength="12" placeholder="Postleitzahl" value="<?php echo $cvRow[postalCode]; ?>" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div>
										
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrProvince" size="45" maxlength="30" placeholder="Kreis" value="<?php echo $cvRow[province]; ?>">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrCountry" size="25" maxlength="30" placeholder="Staat" value="<?php echo $cvRow[country]; ?>">
										</div>
									</div>
								</div>
							</div> <!-- Fin Dirección -->
							
							<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMobile">Handy: * </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvMobile" maxlength="18" placeholder="Bei. 0034-699000000" value="<?php echo $cvRow[mobile]; ?>" required onkeypress="return checkDashedNumbers(event)">
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvPhone">Zusätzliche Tlf.: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvPhone" maxlength="18" placeholder="Bei. 0034-910000000" value="<?php echo $cvRow[phone]; ?>" onkeypress="return checkDashedNumbers(event)">
								</div>
							</div> <!-- Fin Teléfono Móvil & Teléfono Adicional -->
										
							<div class="form-group"> <!-- Correo Electrónico -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMail">E-mail: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="email" name="cvMail" placeholder="email@beispiel.com" value="<?php echo $cvRow[mail]; ?>" required>
								</div>
							</div> <!-- Fin Correo Electrónico -->
							
							<div class="form-group tooltip-demo"> <!-- Carnet de Conducir -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvDrivingType"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Liefertermin"></span> Führerschein: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvDrivingType" >
										<?php
										echo "<option value='' selected>-- Typ --</option>";
										$drivingTypes = getDBcompletecolumnID(key, drivingTypes, id);
										foreach($drivingTypes as $i){
											if($i == $cvRow[drivingType]){
												echo '<option value='.$i.' selected>'.$i.'</option>';
											}
											else{
												echo '<option value='.$i.'>'.$i.'</option>';
											}
										}
										?>
									</select>
									<input class="form-control form-inline" type="date" name="cvDrivingDate" id="cvDrivingDate" placeholder="aaaa-mm-dd" value="<?php echo $cvRow[drivingDate]; ?>">
								</div>
							</div> <!-- Fin Carnet de Conducir -->
							
							<div class="form-group"> <!-- Estado Civil & Hijos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMarital">Familienstand: </label>
								<div class="col-sm-4">
									<select class="form-control" name="cvMarital">
										<option selected disabled value="">Ich bin...</option>
										<?php
										$userLang = getDBsinglefield(language, users, login, $_SESSION[loglogin]);
										$maritStatus = getDBcompletecolumnID($userLang, maritalStatus, id);
				
										foreach($maritStatus as $i){
											if($i == getDBsinglefield($userLang, maritalStatus, key, $cvRow[marital])){
												echo "<option value=" . getDBsinglefield(key, maritalStatus, $userLang, $i) . " selected>" . $i . "</option>";
											}
											else{
												echo "<option value=" . getDBsinglefield(key, maritalStatus, $userLang, $i) . ">" . $i . "</option>";
											}
										}
										?>
									</select>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSons">Kinder: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="number" name="cvSons" maxlength="2" min="0" value="<?php echo $cvRow[sons]; ?>" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Estado Civil & Hijos -->
							
							<div class="form-group tooltip-demo"> <!-- Foto -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Mögliche Dateien: JPG, JPEG, PNG. Max: 1Mb"></span> Fotografie: </label>
								<div class="col-sm-10">
									<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
								</div>
							</div> <!-- Fin Foto -->
							
							<div class="form-group tooltip-demo"> <!-- Archivos/Docs. adicionales -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="CV, Zertifikate... Mögliche Dateien: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT, RTF. Max: 1Mb. Wählen Sie aus, wie viele mit der 'Strg'-Taste"></span> zusätzliche Dokumente: </label>
								<div class="col-sm-10">
									<div id="uploadFiles" class="col-sm9">
										<input class="form-control" type="file" name="candidatFiles[]" multiple="multiple">
									</div>
								</div>
							</div> <!-- Fin Archivos/Docs. adicionales -->
						</fieldset> <!-- Fin Datos Personales del Candidato -->
						
						
						<fieldset id="jsLanguage">
							<div class="panel panel-default"> <!-- Nivel de Idiomas -->
								<div class="panel-heading">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Hinzufügen einer anderen Sprache"></span></a>
									<h3 class="panel-title">Sprachkenntnisse *</h3>
									<a href="http://europass.cedefop.europa.eu/de/resources/european-language-levels-cefr/cef-ell-document.pdf">Tabelle der europäischen Ebene</a>
								</div>
								
								<table id="tabla_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none"> 
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
													<select class="form-control" name="h_cvLang[]">
														<option selected disabled value="">Auswahl... </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
														<select class="form-control" name="h_cvLangLevel[]">
															<option selected disabled value="">Auswahl...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Muttersprache</option>
														</select>
											</td>
											
											<td><a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" > 
											<td>
											<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
													<select class="form-control" name="cvLang" required>
														<option selected disabled value="">Auswahl... </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
														<select class="form-control" name="cvLangLevel" required>
															<option selected disabled value="">Auswahl...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Muttersprache</option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLang][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLangLevel][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Nivel de Idiomas -->
							
							<div class="panel panel-default"> <!-- Educación -->
								<div class="panel-heading">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvEducTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie eine weitere Ausbildung"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Bitte, Ausbildung folgender Weise einfügen: Bezeichnung der erworbenen Qualifikation, Bildungs- oder Ausbildungseinrichtung, Anfangs- und Enddatum"></span> Ausbildung: *</h3>
								</div>
							
								<table id="cvEducTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Titel: </label>
												<input class="form-control" type="text" name="h_cvEducTittle[]" value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Zentrum: </label>
												<input class="form-control" type="text" name="h_cvEducCenter[]" value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="h_cvEducStart[]" value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="h_cvEducEnd[]" value="<?php echo getDBsinglefield(educEnd, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td><a href="#" onClick="delExtraEduc(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Titel: </label>
												<input class="form-control" type="text" name="cvEducTittle" required value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Zentrum: </label>
												<input class="form-control" type="text" name="cvEducCenter" required value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="cvEducStart" required value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducTittle">Titel: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducTittle][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducCenter">Zentrum: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducCenter][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducStart">Beginn: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducEnd">Ende: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducEnd][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Educación -->
							
							<div class="panel panel-default"> <!-- Profesión -->
								<div class="panel-heading tooltip-demo">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvCareerTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie einen anderen Beruf"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Ihr Beruf nicht in der Liste aufgeführt ist, wählen Sie `Andere Berufe` und nehmen Sie bitte per E-Mail administracion@perspectiva-alemania.com Kontakt mit uns auf"></span> Berufserfahrung: *</label></h3>
								</div>
							
								<table id="cvCareerTable_1">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<select class="form-control" name="h_cvCareer[]">
													<option selected disabled value=""> Auswahl... </option>
													<option value="other"> Andere Berufe </option>
													<?php 
														$eduNames = getDBcompleteColumnID($userRow[language], careers, id);
														foreach($eduNames as $i){
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
													<option selected disabled value=""> Auswahl... </option>
													<option value="other"> Andere Berufe </option>
													<?php 
														$eduNames = getDBcompleteColumnID($userRow[language], careers, id);
														foreach($eduNames as $i){
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvCareer">Berufserfahrung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvCareer][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Profesión -->
							
							<div class="panel panel-default"> <!-- Trayectoria / Experiencia -->
								<div class="panel-heading tooltip-demo">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvExperTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie eine weitere Erfahrung"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Ihre aktuelle Arbeit, leer lassen das Feld 'Ende'"></span> Berufliche Tätigkeiten der letzten Jahre </h3>
								</div>
							
								<table id="cvExperTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Unternehmen: </label>
												<input class="form-control" type="text" name="h_cvExpCompany[]" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellung: </label>
												<input class="form-control" type="text" name="h_cvExpPosition[]" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="h_cvExpStart[]" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="h_cvExpEnd[]" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stadt: </label>
												<input class="form-control" type="text" name="h_cvExpCity[]" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Staat: </label>
												<input class="form-control" type="text" name="h_cvExpCountry[]" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellenbeschreibung: </label>
												<textarea class="form-control" name="h_cvExpDescription[]" rows="5" placeholder="Stellenbeschreibung" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
											</td>
											
											<td><a href="#" onClick="delExtraExp(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Unternehmen: </label>
												<input class="form-control" type="text" name="cvExpCompany" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellung: </label>
												<input class="form-control" type="text" name="cvExpPosition" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="cvExpStart" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="cvExpEnd" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stadt: </label>
												<input class="form-control" type="text" name="cvExpCity" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Staat: </label>
												<input class="form-control" type="text" name="cvExpCountry" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellenbeschreibung: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCompany">Unternehmen: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCompany][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpPosition">Stellung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpPosition][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpStart">Beginn: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpEnd">Ende: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpEnd][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCity">Stadt: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCity][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCountry">Staat: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCountry][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpDescription">Stellenbeschreibung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpDescription][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Trayectoria / Experiencia -->
						</fieldset>
						
						
						<fieldset>
							<div class="form-group"> <!-- Salario -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSalary">Gehaltsvorstellung: </label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="cvSalary" maxlength="7" placeholder="€ Netto/Jahr" value="<?php echo $cvRow[salary] ?>" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Salario -->
				
							<div class="form-group"> <!-- Otros datos de Interés -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther">Weitere Angaben: </label>
								<div class="col-sm-10">
									<textarea class="form-control" type="number" name="cvOther" placeholder="Vermerken Sie hier alle wichtigen Daten, die nicht an anderer Stelle erscheinen" value="<?php echo $cvRow[otherDetails] ?>"></textarea>	
								</div>
							</div>  <!-- Fin Otros datos de Interés -->
							
							<div class="form-group tooltip-demo"> <!-- 10 Tags -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Es ist nicht obligatorisch, die 10 Felder auszufüllen"></span> Wesentliche berufliche Inhalte</label>
								<div class="col-sm-10">
									<?php
									$tipArray = array(1 => '#1 Ich habe mich spezialisiert auf...', 
													2 => '#2 In den letzten Jahren habe ich...', 
													3 => '#3 Ich habe über... Jahre Erfahrung in...',
													4 => '#4 ...', 
													5 => '#5 ...', 
													6 => '#6 ...', 
													7 => '#7 ...', 
													8 => '#8 ...', 
													9 => '#9 ...', 
													10 => '#10 ...');
													
									for($i=1; $i<=10; $i++){
										echo "<div class='col-sm-6' style='margin-bottom: 10px;'>";
											$iSkill = skill.$i;
											echo "<input class='form-control' type='text' name='cvskill$i' maxlength='100' placeholder='$tipArray[$i]' value='$cvRow[$iSkill]'>";
										echo "</div>";
									}
									?>
								</div>
							</div>  <!-- Fin 10 Tags -->
						</fieldset>
					</div> <!-- Panel Body -->
					
					<div class="panel-footer">
						<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="cvlopd" required> Ich habe die <a href="javascript:alert('Recuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');">Nutzungsbedingungen</a> und Datenschutzbestimmungen gelesen und akzeptiert.</label>
						<div class="btn-group pull-right">
							<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
							<button type="submit" name ="push_button" class="btn btn-primary" onclick="return confirmFormSendES(formu);">Senden</button>
						</div>
					</div> <!-- Panel Footer-->
				</div> <!-- class="panel-default" -->
			</form>
			<?php
		}
		/* ************************************    ----------------------------------------    ************************************ */
		//---------------  End of ACTIVE Candidate. A CV was already saved, and that info now appears in each field  ---------------//
		/* ************************************    ----------------------------------------    ************************************ */
		
		
		/* ************************************    ----------------------------------------    ************************************ */
		//----------------------------  Start of Candidate is ACTIVE and has NOT previously saved a CV  ----------------------------//
		/* ************************************    ----------------------------------------    ************************************ */
		else{
			?>
			<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">Die Felder mit * sind Pflichtfelder</div>
					<div class="panel-body">
						<fieldset> <!-- Datos Personales del Candidato -->
							<div class="form-group"> <!-- Nombre -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvName">Namen: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvName' minlength='3' maxlength='50' placeholder="Min. 3 Buchstaben" required/>
								</div>
							</div> <!-- Fin Nombre -->
							
							<div class="form-group"> <!-- Apellidos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSurname">Nachnamen: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvSurname' maxlength='50' placeholder="Min. 3 Buchstaben" required/>
								</div>
							</div> <!-- Fin Apellidos -->
							
							<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvBirthdate">Geburtsdatum: * </label>
								<div class="col-sm-3">
									<?php if(isset($_SESSION[cvBirthdate])){ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $_SESSION[cvBirthdate]; ?>" required/>
										<?php 
										unset($_SESSION[cvBirthdate]);
										} else{ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" required/>
										<?php } ?>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvnie">Personalausweis: * </label>
								<div class="col-sm-5">
									<!-- <input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE();" required/> -->
									<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
									<input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE_ES();"required/>
								</div>
							</div> <!-- Fin Fecha de Nacimiento & DNI/NIE -->
							
							<div class="form-group tooltip-demo"> <!-- Sexo & Nacionalidad -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSex">Geschlecht: * </label>
								<div class="col-sm-3">
									<div class='radio-inline'>
										<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='cvSex' value='0' required>Mann</label>
										<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='cvSex' value='1'>Frau</label>
									</div>
								</div>
							
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvNation"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Sie mehr als eine haben, wählen Sie alle bei gedrückter 'Strg'-Taste"></span> Nationalität: * </label>
								<div class="col-sm-5" id="uploadFormNationality">
									<select class="form-control" name="cvNation[]" multiple="multiple" required>
										<option value="Spanien"> Spanien </option>
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
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvAddrType">Adresse: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvAddrType">
										<?php
										echo "<option value=''>-- Typ --</option>";
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
								
									<input class="form-control" type="text" name="cvAddrName" size="34" maxlength="50" placeholder="Namen' name">
									<input class="form-control" type="text" name="cvAddrNum" size="3" maxlength="4" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrPortal" size="3" maxlength="4" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrStair" size="3" maxlength="4" placeholder="Leiter" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrFloor" size="3" maxlength="4" placeholder="Boden">
									<input class="form-control" type="text" name="cvAddrDoor" size="3" maxlength="4" placeholder="Tor" onkeyup="this.value=this.value.toUpperCase();">
									
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrCity" size="45" maxlength="50" placeholder="Stadt">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrPostalCode" size="25" maxlength="12" placeholder="Postleitzahl" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div>
										
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrProvince" size="45" maxlength="30" placeholder="Kreis">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrCountry" size="25" maxlength="30" placeholder="Staat">
										</div>
									</div>
								</div>
							</div> <!-- Fin Dirección -->
							
							<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMobile">Handy: * </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvMobile" maxlength="18" placeholder="Bei. 0034-699000000" required onkeypress="return checkDashedNumbers(event)">
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvPhone">Zusätzliche Tlf.: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvPhone" maxlength="18" placeholder="Bei. 0034-910000000" onkeypress="return checkDashedNumbers(event)">
								</div>
							</div> <!-- Fin Teléfono Móvil & Teléfono Adicional -->
										
							<div class="form-group"> <!-- Correo Electrónico -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMail">E-mail: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="email" name="cvMail" placeholder="email@beispiel.com" required>
								</div>
							</div> <!-- Fin Correo Electrónico -->
				
							<div class="form-group tooltip-demo"> <!-- Carnet de Conducir -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvDrivingType"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Liefertermin"></span> Führerschein: </label>
								<div class="col-sm-10 form-inline">
									<select class="form-control form-inline" name="cvDrivingType" >
										<?php
										echo "<option value='' selected>-- Typ --</option>";
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
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMarital">Familienstand: </label>
								<div class="col-sm-4">
									<select class="form-control" name="cvMarital">
										<option selected disabled value="">Ich bin...</option>
										<?php
										$userLang = getDBsinglefield(language, users, login, $_SESSION[loglogin]);
										$maritStatus = getDBcompletecolumnID($userLang, maritalStatus, id);
				
										foreach($maritStatus as $i){
											echo "<option value=" . getDBsinglefield(key, maritalStatus, $userLang, $i) . ">" . $i . "</option>";
										}
										?>
									</select>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSons">Kinder: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="number" name="cvSons" maxlength="2" min="0" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Estado Civil & Hijos -->
							
							<div class="form-group tooltip-demo"> <!-- Foto -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Mögliche Dateien: JPG, JPEG, PNG. Max: 1Mb"></span> Fotografie: </label>
								<div class="col-sm-10">
									<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
								</div>
							</div> <!-- Fin Foto -->
							
							<div class="form-group tooltip-demo"> <!-- Archivos/Docs. adicionales -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="CV, Zertifikate... Mögliche Dateien: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT, RTF. Max: 1Mb. Wählen Sie aus, wie viele mit der 'Strg'-Taste"></span> zusätzliche Dokumente: </label>
								<div class="col-sm-10">
									<div id="uploadFiles" class="col-sm9">
										<input class="form-control" type="file" name="candidatFiles[]" multiple="multiple">
									</div>
								</div>
							</div> <!-- Fin Archivos/Docs. adicionales -->
						</fieldset> <!-- Fin Datos Personales del Candidato -->
						
						<fieldset id="jsLanguage">
							<div class="panel panel-default"> <!-- Nivel de Idiomas -->
								<div class="panel-heading">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraLang('tabla_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Hinzufügen einer anderen Sprache"></span></a>
									<h3 class="panel-title">Sprachkenntnisse *</h3>
									<a href="http://europass.cedefop.europa.eu/de/resources/european-language-levels-cefr/cef-ell-document.pdf">Tabelle der europäischen Ebene</a>
								</div>
								
								<table id="tabla_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none"> 
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
													<select class="form-control" name="h_cvLang[]">
														<option selected disabled value="">Auswahl... </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
														<select class="form-control" name="h_cvLangLevel[]">
															<option selected disabled value="">Auswahl...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Muttersprache</option>
														</select>
											</td>
											
											<td><a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" > 
											<td>
											<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
													<select class="form-control" name="cvLang" required>
														<option selected disabled value="">Auswahl... </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
														<select class="form-control" name="cvLangLevel" required>
															<option selected disabled value="">Auswahl...</option>
															<option value="A1">A1</option>
															<option value="A2">A2</option>
															<option value="B1">B1</option>
															<option value="B2">B2</option>
															<option value="C1">C1</option>
															<option value="C2">C2</option>
															<option value="mothertongue">Muttersprache</option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Sprachkenntnisse: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLang][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLangLevel">Niveau: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvLangLevel][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Nivel de Idiomas -->
							
							<div class="panel panel-default"> <!-- Educación -->
								<div class="panel-heading">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvEducTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvEducTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie eine weitere Ausbildung"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Bitte, Ausbildung folgender Weise einfügen: Bezeichnung der erworbenen Qualifikation, Bildungs- oder Ausbildungseinrichtung, Anfangs- und Enddatum"></span> Ausbildung: *</h3>
								</div>
							
								<table id="cvEducTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Titel: </label>
												<input class="form-control" type="text" name="h_cvEducTittle[]" value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Zentrum: </label>
												<input class="form-control" type="text" name="h_cvEducCenter[]" value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="h_cvEducStart[]" value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="h_cvEducEnd[]" value="<?php echo getDBsinglefield(educEnd, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td><a href="#" onClick="delExtraEduc(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Titel: </label>
												<input class="form-control" type="text" name="cvEducTittle" required value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Zentrum: </label>
												<input class="form-control" type="text" name="cvEducCenter" required value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="cvEducStart" required value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducTittle">Titel: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducTittle][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducCenter">Zentrum: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducCenter][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducStart">Beginn: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducEnd">Ende: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvEducEnd][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Educación -->
							
							<div class="panel panel-default"> <!-- Profesión -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvCareerTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvCareerTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie einen anderen Beruf"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Ihr Beruf nicht in der Liste aufgeführt ist, wählen Sie `Andere Berufe` und nehmen Sie bitte per E-Mail administracion@perspectiva-alemania.com Kontakt mit uns auf"></span> Berufserfahrung: *</label></h3>
								</div>
							
								<table id="cvCareerTable_1">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<select class="form-control" name="h_cvCareer[]">
													<option selected disabled value=""> Auswahl... </option>
													<option value="other"> Andere Berufe </option>
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
													<option selected disabled value=""> Auswahl... </option>
													<option value="other"> Andere Berufe </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvCareer">Berufserfahrung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvCareer][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Profesión -->
							
							<div class="panel panel-default"> <!-- Trayectoria / Experiencia -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvExperTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvExperTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Fügen Sie eine weitere Erfahrung"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Wenn Ihre aktuelle Arbeit, leer lassen das Feld 'Ende'"></span> Berufliche Tätigkeiten der letzten Jahre </h3>
								</div>
							
								<table id="cvExperTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Unternehmen: </label>
												<input class="form-control" type="text" name="h_cvExpCompany[]" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellung: </label>
												<input class="form-control" type="text" name="h_cvExpPosition[]" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="h_cvExpStart[]" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="h_cvExpEnd[]" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stadt: </label>
												<input class="form-control" type="text" name="h_cvExpCity[]" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Staat: </label>
												<input class="form-control" type="text" name="h_cvExpCountry[]" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellenbeschreibung: </label>
												<textarea class="form-control" name="h_cvExpDescription[]" rows="5" placeholder="Stellenbeschreibung" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
											</td>
											
											<td><a href="#" onClick="delExtraExp(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a></td> 
										</tr>
										
										<!-- FILA VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" >
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Unternehmen: </label>
												<input class="form-control" type="text" name="cvExpCompany" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellung: </label>
												<input class="form-control" type="text" name="cvExpPosition" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Beginn: </label>
												<input class="form-control" type="text" name="cvExpStart" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Ende: </label>
												<input class="form-control" type="text" name="cvExpEnd" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stadt: </label>
												<input class="form-control" type="text" name="cvExpCity" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Staat: </label>
												<input class="form-control" type="text" name="cvExpCountry" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>" />
											</td>
											
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Stellenbeschreibung: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCompany">Unternehmen: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCompany][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpPosition">Stellung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpPosition][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpStart">Beginn: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpStart][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpEnd">Ende: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpEnd][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCity">Stadt: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCity][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpCountry">Staat: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpCountry][$j]; ?>">
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvExpDescription">Stellenbeschreibung: </label>
												<input type="text" disabled value="<?php echo $_SESSION[h_cvExpDescription][$j]; ?>">
												<!-- <a href="#" onClick="delExtraLang(this.parentNode.parentNode)"><span class="btn btn-danger btn-xs glyphicon-minus"></span></a> -->
										</div>
										<?php 
									}
								}
								?>
							</div> <!-- Fin Trayectoria / Experiencia -->
						</fieldset>
						
						<fieldset>
							<div class="form-group"> <!-- Salario -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSalary">Gehaltsvorstellung: </label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="cvSalary" maxlength="7" placeholder="€ Netto/Jahr" value="<?php echo $cvRow[salary] ?>" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Salario -->
				
							<div class="form-group"> <!-- Otros datos de Interés -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther">Weitere Angaben: </label>
								<div class="col-sm-10">
									<textarea class="form-control" type="number" name="cvOther" placeholder="Vermerken Sie hier alle wichtigen Daten, die nicht an anderer Stelle erscheinen" value="<?php echo $cvRow[otherDetails] ?>"></textarea>	
								</div>
							</div>  <!-- Fin Otros datos de Interés -->
				
							<div class="form-group tooltip-demo"> <!-- 10 Tags -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Es ist nicht obligatorisch, die 10 Felder auszufüllen"></span> Wesentliche berufliche Inhalte</label>
								<div class="col-sm-10">
									<?php
									$tipArray = array(1 => '#1 Ich habe mich spezialisiert auf...', 
													2 => '#2 In den letzten Jahren habe ich...', 
													3 => '#3 Ich habe über... Jahre Erfahrung in...',
													4 => '#4 ...', 
													5 => '#5 ...', 
													6 => '#6 ...', 
													7 => '#7 ...', 
													8 => '#8 ...', 
													9 => '#9 ...', 
													10 => '#10 ...');
													
									for($i=1; $i<=10; $i++){
										echo "<div class='col-sm-6' style='margin-bottom: 10px;'>";
											$iSkill = skill.$i;
											echo "<input class='form-control' type='text' name='cvskill$i' maxlength='100' placeholder='$tipArray[$i]' value='$cvRow[$iSkill]'>";
										echo "</div>";
									}
									?>
								</div>
							</div>  <!-- Fin 10 Tags -->
						</fieldset>
						
					</div> <!-- Panel Body -->
					
					<div class="panel-footer">
						<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="cvlopd" required> Ich habe die <a href="javascript:alert('Recuerda que en cualquier momento puedes ejercer tu derecho de oposición, acceso, rectificación y cancelación, en lo que respecta al tratamiento de tus datos personales por parte de PERSPECTIVA ALEMANIA, a través de un escrito a la siguiente dirección: Perspectiva Alemania, Paseo de la Habana 5, 1º-dcha., 28036 Madrid.\nPara cualquier consulta no dudes en ponerte en contacto con nosotros.\nPERSPECTIVA ALEMANIA\nadministración@perspectiva-alemania.com');">Nutzungsbedingungen</a> und Datenschutzbestimmungen gelesen und akzeptiert.</label>
						<div class="btn-group pull-right">
							<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
							<button type="submit" name ="push_button" class="btn btn-primary" onclick="return confirmFormSendES(formu);">Senden</button>
						</div>
					</div> <!-- Panel Footer-->
				</div> <!-- class="panel-default" -->
			</form>
			<?php 
		}
		//-------------------  End of Candidate is ACTIVE and has NOT previously saved a CV  -------------------//
		
	}//del else que muestra el formulario
	?>
</body>
</html>