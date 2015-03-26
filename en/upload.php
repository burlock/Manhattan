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
		
		/* **********  This file will do every needed checking in upload  ********** */
		
		include $_SERVER[DOCUMENT_ROOT] . '/common/code/uploadCheckings.php';
		
		/* **********  This file will do every needed checking in upload  ********** */
		
	}//isset($_POST[push_button])
	/* -----------------------------------     End of FORM validations     ----------------------------------- */
	
	
	/* *****************************    Start of WEB Page as initially showed    ***************************** */
	else{
		//----  Start of ACTIVE Candidate. A CV was already saved, and that info now appears in each field  ----//
		if(getDBsinglefield(cvSaved, users, login, $_SESSION[loglogin]) == 1){
			$cvRow = getDBrow2(cvitaes, userLogin, $_SESSION[loglogin], firstCV, 1);
			?>
			<form id="uploadForm" class="form-horizontal" name="formu" action="" method="post" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">Fields with * are mandatory</div>
					<div class="panel-body">
						<fieldset> <!-- Datos Personales del Candidato -->
							<div class="form-group"> <!-- Nombre -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvName">Name: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvName' minlength='3' maxlength='50' placeholder="Min. 3 characters" value="<?php echo $cvRow[name]; ?>" required/>
								</div>
							</div> <!-- Fin Nombre -->
							
							<div class="form-group"> <!-- Apellidos -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSurname">Surname: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="text" name='cvSurname' maxlength='50' placeholder="Min. 3 characters" value="<?php echo $cvRow[surname]; ?>" required/>
								</div>
							</div> <!-- Fin Apellidos -->
							
							<div class="form-group"> <!-- Fecha de Nacimiento & DNI/NIE -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvBirthdate">Birthdate: * </label>
								<div class="col-sm-3">
									<?php if(isset($_SESSION[cvBirthdate])){ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $_SESSION[cvBirthdate]; ?>" required/>
										<?php 
										unset($_SESSION[cvBirthdate]);
										} else{ ?>
										<input class="form-control" type="date" name='cvBirthdate' id='cvBirthdate' autocomplete="off" placeholder="aaaa-mm-dd" value="<?php echo $cvRow[birthdate] ?>" required/>
										<?php } ?>
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvnie">ID card: * </label>
								<div class="col-sm-5">
									<!-- Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano -->
									<input class="form-control" type="text" name='cvnie' id='cvnie' maxlength="9" placeholder="12345678L (8 Nums.) / X1234567T (7 Nums.)" value="<?php echo $cvRow[nie] ?>" onkeyup="this.value=this.value.toUpperCase();" onblur="jsCheckDNI_NIE_ES();"required/>
								</div>
							</div> <!-- Fin Fecha de Nacimiento & DNI/NIE -->
							
							<div class="form-group tooltip-demo"> <!-- Sexo & Nacionalidad -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSex">Gender: * </label>
								<div class="col-sm-3">
									<div class='radio-inline'>
										<?php if($cvRow[sex] == 0){ ?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" required checked="checked" />Male</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" />Female</label>
										<?php } else{ ?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" required />Male</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="cvSex" value="<?php echo $cvRow[sex]; ?>" checked="checked" />Female</label>
										<?php } ?>
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
								
									<input class="form-control" type="text" name="cvAddrName" size="34" maxlength="50" placeholder="Address' name" value="<?php echo $cvRow[addrName]; ?>">
									<input class="form-control" type="text" name="cvAddrNum" size="3" maxlength="4" placeholder="Num" value="<?php echo $cvRow[addrNum]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrPortal" size="3" maxlength="4" placeholder="Portal" value="<?php echo $cvRow[portal]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrStair" size="3" maxlength="4" placeholder="Stair" value="<?php echo $cvRow[stair]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									<input class="form-control" type="text" name="cvAddrFloor" size="3" maxlength="4" placeholder="Floor" value="<?php echo $cvRow[addrFloor]; ?>">
									<input class="form-control" type="text" name="cvAddrDoor" size="3" maxlength="4" placeholder="Door" value="<?php echo $cvRow[addrDoor]; ?>" onkeyup="this.value=this.value.toUpperCase();">
									
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrCity" size="45" maxlength="50" placeholder="City" value="<?php echo $cvRow[city]; ?>">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrPostalCode" size="25" maxlength="12" placeholder="Postal code" value="<?php echo $cvRow[postalCode]; ?>" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div>
										
									<div class="row">
										<div class="col-sm-8">
											<input class="form-control form-inline" type="text" name="cvAddrProvince" size="45" maxlength="30" placeholder="Province" value="<?php echo $cvRow[province]; ?>">
										</div>
										<div class="col-sm-1">
											<input class="form-control form-inline" type="text" name="cvAddrCountry" size="25" maxlength="30" placeholder="Country" value="<?php echo $cvRow[country]; ?>">
										</div>
									</div>
								</div>
							</div> <!-- Fin Dirección -->
							
							<div class="form-group"> <!-- Teléfono Móvil & Teléfono Adicional -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMobile">Mobile: * </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvMobile" maxlength="18" placeholder="I.e. 0034-699000000" value="<?php echo $cvRow[mobile]; ?>" required onkeypress="return checkDashedNumbers(event)">
								</div>
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvPhone">Additional Tlf.: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="text" name="cvPhone" maxlength="18" placeholder="I.e. 0034-910000000" value="<?php echo $cvRow[phone]; ?>" onkeypress="return checkDashedNumbers(event)">
								</div>
							</div> <!-- Fin Teléfono Móvil & Teléfono Adicional -->
										
							<div class="form-group"> <!-- Correo Electrónico -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMail">E-mail: * </label> 
								<div class="col-sm-10">
									<input class="form-control" type="email" name="cvMail" placeholder="email@example.com" value="<?php echo $cvRow[mail]; ?>" required>
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
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvMarital">Marital status: </label>
								<div class="col-sm-4">
									<select class="form-control" name="cvMarital">
										<option selected disabled value="">I am...</option>
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
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvSons">Children: </label> 
								<div class="col-sm-4">
									<input class="form-control" type="number" name="cvSons" maxlength="2" min="0" value="<?php echo $cvRow[sons]; ?>" onkeypress="return checkOnlyNumbers(event)">
								</div>
							</div> <!-- Fin Estado Civil & Hijos -->
							
							<div class="form-group tooltip-demo"> <!-- Foto -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Supported types: JPG, JPEG or PNG. Max: 1Mb"></span> Photo: </label>
								<div class="col-sm-10">
									<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
								</div>
							</div> <!-- Fin Foto -->
							
							<div class="form-group tooltip-demo"> <!-- Archivos/Docs. adicionales -->
								<label id="uploadFormLabel" class="control-label col-sm-2"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="CV, Certificate... Supported types: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT or RTF. Max: 1Mb. Choose as much as you want by using 'CTRL' key"></span> Additional docs.: </label>
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
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraLang('tabla_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other language"></span></a>
									<h3 class="panel-title">Sprachkenntnisse *</h3>
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
										<tr class="panel panel-default panel-body form-inline" id="tabla_1_fila_1" > 
											<td>
											<label id="uploadFormLabel" class="control-label col-sm-0" for="cvLang">Language: </label>
													<select class="form-control" name="cvLang" required>
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
														<select class="form-control" name="cvLangLevel" required>
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
								}
								?>
							</div> <!-- Fin Nivel de Idiomas -->
							
							<div class="panel panel-default"> <!-- Educación -->
								<div class="panel-heading">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvEducTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other education"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Include all the titles you have as follows: Title and Specialty, Study center, Start and end dates"></span> Education: *</h3>
								</div>
							
								<table id="cvEducTable_1" align="center">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Title: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="">Title: </label>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvEducTittle">Title: </label>
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
								}
								?>
							</div> <!-- Fin Educación -->
							
							<div class="panel panel-default"> <!-- Profesión -->
								<div class="panel-heading tooltip-demo">
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvCareerTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other career"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="If your title does not appear in the list select Other and contact us through administracion@perspectiva-alemania.com"></span> Career: *</label></h3>
								</div>
							
								<table id="cvCareerTable_1">
									<tbody>
										<!-- FILA NO VISIBLE -->
										<tr class="panel panel-default panel-body form-inline" id="clonable" style="display:none">
											<td>
												<select class="form-control" name="h_cvCareer[]">
													<option selected disabled value="">Choose career... </option>
													<option value="other"> Other </option>
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
													<option selected disabled value="">Choose career... </option>
													<option value="other"> Other </option>
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
												<label id="uploadFormLabel" class="control-label col-sm-0" for="cvCareer">Career: </label>
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
												<textarea class="form-control" name="h_cvExpDescription[]" rows="5" placeholder="Description" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
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
												<textarea class="form-control" name="cvExpDescription" rows="5" placeholder="Description" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
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
							</div> <!-- Fin Salario -->
				
							<div class="form-group"> <!-- Otros datos de Interés -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther">Other interesting information: </label>
								<div class="col-sm-10">
									<textarea class="form-control" type="number" name="cvOther" placeholder="Write here any other relevant information that does not appear in any other field in the form..." value="<?php echo $cvRow[otherDetails] ?>"></textarea>	
								</div>
							</div>  <!-- Fin Otros datos de Interés -->
							
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
								
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvnie">ID card: * </label>
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
								}
								?>
							</div> <!-- Fin Nivel de Idiomas -->
							
							
							<div class="panel panel-default"> <!-- Educación -->
								<div class="panel-heading tooltip-demo">
									<!-- <a class="btn btn-primary btn-xs pull-right glyphicon-plus" href="javascript:addExtraEduc('cvEducTable_1');"></a> -->
									<a class="btn btn-primary btn-xs pull-right" href="javascript:addExtraEduc('cvEducTable_1');"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-original-title="Add other education"></span></a>
									<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Include all the titles you have as follows: Title and Specialty, Study center, Start and end dates"></span> Education: *</h3>
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
													<option selected disabled value="">Choose career... </option>
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
													<option selected disabled value="">Choose career... </option>
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
												<textarea class="form-control" name="cvExpDescription" rows="5" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>" ></textarea>
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
							</div> <!-- Fin Salario -->
				
							<div class="form-group"> <!-- Otros datos de Interés -->
								<label id="uploadFormLabel" class="control-label col-sm-2" for="cvOther">Other interesting information: </label>
								<div class="col-sm-10">
									<textarea class="form-control" type="number" name="cvOther" placeholder="Write here any other relevant information that does not appear in any other field in the form..." value="<?php echo $cvRow[otherDetails] ?>"></textarea>	
								</div>
							</div> <!-- Fin Otros datos de Interés -->
				
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
									
									for($i=1; $i<=10; $i++){
										echo "<div class='col-sm-6' style='margin-bottom: 10px;'>";
											$iSkill = skill.$i;
											echo "<input class='form-control' type='text' name='cvskill$i' maxlength='100' placeholder='$tipArray[$i]' value='$cvRow[$iSkill]'>";
										echo "</div>";
									}
									?>
								</div>
							</div> <!-- Fin 10 Tags -->
						</fieldset>
						
					</div> <!-- Panel Body -->
					
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