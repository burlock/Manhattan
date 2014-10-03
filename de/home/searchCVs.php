<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Suche CVs</title>
	
	<!-- Custom styles for this template -->
	<link href="/common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="/common/img/apple-touch-icon.png">
</head>

<body>
	<?php
	if (!$_SESSION['loglogin']){
		?>
		<script type="text/javascript">
			window.location.href='/de/index.html';
		</script>
		<?php
	}
	else {
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/de/staticHeader.php';
		?>
		
		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
							$pendingCVs = getPendingCVs();
							$digitLang = getUserLangDigits($userRow['language']);
							$LangDigitsName = $digitLang."Name";
							$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
							$mainNamesRow = getDBcompletecolumnID($LangDigitsName, 'mainNames', 'id');
							$j = 0;
							foreach($mainKeysRow as $i){
								if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
									if($myFile == $i){
										echo "<li class='active'><a href=../$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
										$j++;

										echo "<ul class='nav'>";

										$namesTable = $myFile.'Names';
										$numCols = getDBnumcolumns($myFile);
										$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
										for($k=3;$k<$numCols;$k++) {
											$colNamej = getDBcolumnname($myFile, $k);
											if(($myFileProfileRow[$k] == 1) && ($subLevelMenu = getDBsinglefield2($LangDigitsName, $namesTable, 'key', $colNamej, 'level', '2'))) {
												if(!getDBsinglefield2($LangDigitsName, $namesTable, 'fatherKey', $colNamej, 'level', '3')){
													$level2File = getDBsinglefield('key', $namesTable, $LangDigitsName, $subLevelMenu);
													// Because the file we are is a level 2 file, we do this comparision to make active element in list if it's this same file
													if ($level2File == 'pendingCVs') 
														$badge = "<span class='badge'>$pendingCVs</span>";
													else
														$badge = "";
													if ($level2File == basename(__FILE__, '.php')) 
														echo "<li class='active'>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
												}
												else{
													$arrayKeys = array();
													$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
													$checkFinished = 0;
													$l = 1;
													foreach($arrayKeys as $key){
														if($checkFinished == 0){
															if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($key, $myFile, 'profile', $userRow['profile']))){
																$level3File = $key;
																$checkFinished = 1;
															}
															else{
																$l++;
															}
														}
													}
													echo "<li><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}
										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";
									}
									else{
										echo "<li><a href=../$i.php>" . $mainNamesRow[$j] . "</a></li>";
										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->
				
				
				<!--  ***********************************   Start of Web Page as initially showed   ***********************************  -->
				<div class="col-md-9" role="main"> 
					<div class="bs-docs-section">
						<h2 class="page-header">Suche CVs</h2>
						<div class="panel panel-default scrollable">
							<div class="panel-heading">
								<h2 class="panel-title">Geben Sie Suchkriterien</h2>
							</div>
							<div class="panel-body scrollable">
								
								<form id="searchForm" name="searchForm" class="form-horizontal" method="post" action="searchResult.php" autocomplete="off" autocapitalize="off" enctype="multipart/form-data" onsubmit="return comprobar()";>
									<div id="form_WordKey" class="form-group">
										<label for="blankWordKey" class="control-label col-xs-3">Stichwort</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankWordKey" id="blankWordKey" placeholder="Suche auf ein beliebiges Feld (ein wort)" autofocus>
										</div>
									</div> <!-- id="form_WordKey" -->
									
									<div id="form_NIE" class="form-group"> <!-- NIE -->
										<label for="blankNIE" class="control-label col-xs-3">NIE</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankNIE" id="blankNIE" maxlength="9" placeholder="12345678L (8 Zahlen) / X1234567T (7 Zahlen)" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div> <!-- id="form_NIE" -->
									
									<div id="form_Nationality" class="form-group"> <!-- Nacionalidad -->
										<label for="blankNationality" class="control-label col-xs-3">Staatsangehörigkeit</label> 
										<div class="col-xs-9">
											<select name="blankNationality" class="form-control">
												<option value=""> -- wählen -- </option>
												<!-- Value for all countries must be in German because that language is used to save countries in DDBB -->
												<option value="Spanien"> Spanien </option>
												<?php 
												$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
												$countryName = getDBcompletecolumnID($userLang, 'countries', $userLang);
												foreach($countryName as $i){
													echo '<option value="' . getDBsinglefield('german', 'countries', $userLang, $i) . '">' . $i . '</option>';
												}
												?>
											</select>
										</div>
									</div> <!-- id="form_Nationality" -->
									
									<div id="form_genre" class="form-group"> <!-- Sexo -->
										<label for="blankSex" class="control-label col-xs-3">Geschlecht</label>
										<div class="col-xs-2" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="0"> Mann</label>
										</div>
										<div class="col-xs-2" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="1"> Frau</label>
										</div>
									</div> <!-- id="form_genre" -->
									
									<div id="formResidence" class="form-group"> <!-- Vivienda actual -->
										<label for="blankResidence" class="control-label col-xs-3">Strom Gehäuse</label>
										<div class="col-xs-5">
										<input type="text" class="form-control" name="blankCity" placeholder="Stadt" />
										</div>
										<div class="col-xs-4">
											<input type="text" class="form-control" name="blankProvince" placeholder="Provinz" />
										</div>
									</div> <!-- id="formResidence" -->
									
									<div id="form_Driving" class="form-group"> <!-- Permiso de conducir -->
										<label for="blankDrivingType" class="control-label col-xs-3">Führerschein</label>
										<div class="col-xs-3">
											<select name="blankDrivingType" class="form-control">
												<option selected value=''>-- wählen --</option>
												<?php
												$keyDrivingTypes = getDBcompletecolumnID('key', 'drivingTypes', 'id');
												foreach($keyDrivingTypes as $i){
													echo '<option value=' . $i . '>' . $i . '</option>';
												}
												?>
											</select>
										</div>
										<!-- NO SE PODRÁN HACER BÚSQUEDAS POR FECHA... DE MOMENTO
										<div class="col-xs-6">
											<input type="date" class="form-control" name="drivingDate" name="drivingDate" />
										</div>
										-->
									</div> <!-- id="form_Driving" -->
									
									<div id="form_Status" class="form-group"> <!-- Estado civil -->
										<label for="blankCivilStatus" class="control-label col-xs-3">Familienstand</label>
										<div class="col-xs-9">
											<select name="blankCivilStatus" class="form-control">
												<option value="">-- wählen --</option>
												<?php
												$keyMarital = getDBcompletecolumnID('key', 'maritalStatus', 'id');
												foreach($keyMarital as $i){
													echo '<option value=' . $i . '>' . getDBsinglefield($userRow['language'], 'maritalStatus', 'key', $i) . '</option>';
												}
												?>
											</select>
										</div>
									</div> <!-- id="form_Status" -->
									
									<div id="form_childrens" class="form-group"> <!-- Hijos -->
										<label for="blankSons" class="control-label col-xs-3">Kinder</label>
										<div class="col-xs-9">
											<input type="number" class="form-control" name="blankSons" id="blankSons" maxlength="2" onkeypress="return checkOnlyNumbers(event)">
										</div>
									</div> <!-- id="form_NIE" -->
									
									<div id="form_Languages" class="form-group"> <!-- Idiomas -->
										<label for="blankLanguages" class="control-label col-xs-3">Sprachkenntnisse</label>
										<div class="col-xs-4">
											<select name="blankLanguages" class="form-control">
												<option selected disabled value=''>-- Sprache wählen --</option>
												<?php
												$langNames = getDBcompletecolumnID($userRow['language'], 'languages', $userRow['language']);
												foreach ($langNames as $i){
													echo '<option value="' . getDBsinglefield('key', 'languages', $userRow['language'], $i) . '">' . $i . '</option>';
												}
												?>
											</select>
										</div>
										<div class="col-xs-5">
											<select name="blankLangLevels" class="form-control">
												<option selected value="">-- Wählen Sie Sprachkenntnisse --</option>
												<option value="A1">A1</option>
												<option value="A2">A2</option>
												<option value="B1">B1</option>
												<option value="B2">B2</option>
												<option value="C1">C1</option>
												<option value="C2">C2</option>
												<option value="mothertongue">Muttersprache</option>
											</select>
										</div>
									</div> <!-- id="form_languages" -->
									
									<div id="form_Title" class="form-group"> <!-- Educación -->
										<label for="titleType" class="control-label col-xs-3">Ausbildung</label>
										<div class="col-xs-5">
										<input type="text" class="form-control" name="blankEducTittle" placeholder="Schulabschluss" />
										</div>
										<div class="col-xs-4">
											<input type="text" class="form-control" name="blankEducCenter" placeholder="Schule" />
										</div>
									</div> <!-- id="form_Title" -->
									
									<div id="form_Profession" class="form-group"> <!-- Profesión -->
										<label for="blankCareer" class="control-label col-xs-3">Beruf</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankCareer" id="blankCareer" placeholder="Aktuelle beruf">
										</div>
									</div> <!-- id="form_Profession" -->
									
									<div id="formCandidateStatus" class="form-group"> <!-- Estado del Candidato -->
										<label for="blankCandidateStatus" class="control-label col-xs-3">Zustand des kandidaten</label>
										<div class="col-xs-9">
											<select name="blankCandidateStatus" class="form-control">
												<option value="">-- wählen --</option>
												<option value="available">Verfügbar</option>
												<option value="working">Arbeiten</option>
												<option value="discarded">Ausgeschlossen</option>
											</select>
										</div>
									</div> <!-- id="formCandidateStatus" -->
									
									<div id="report_set" class="panel panel-default">
  										<div class="panel-body">
											<div id="form_report" class="form-group">
												<label for="reportType" class="control-label col-xs-3">Typ des berichts</label>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="full_report" onclick="test(2);" checked> Volle</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="blind_report" onclick="test(2);"> Blind</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="custom_report" onclick="test(1);"> Personalisiert</label>
												</div>										
											</div> <!-- id="form_report" -->
											
											<hr>
											
											<div id="form_custom_report" class="form-group">
												<table>
													<tr>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="name" disabled> Nomen</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="birthdate" disabled> Geburtsdatum</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="nationalities" disabled> Staatsangehörigkeit</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="nie" disabled> Personalausweis</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="addrName" disabled> Derzeitige Anschrift</td>
													</tr>
													<tr>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="city" disabled> Stadt</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="province" disabled> Provinz</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="phone" disabled> Telefon</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="mobile" disabled> Mobiltelefon</td>
														<td style="padding-left:40px; padding-top:10px; font-size: 14px;"><input type="checkbox" name="per[]" value="mail" disabled> Email</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								
									<div id="form_submit" class="form-group pull-right" style="margin: 1px;">
										<button type="submit" name="Buscar" class="btn btn-primary" >Finden <span class="glyphicon glyphicon-search"> </span></button>
									</div>

								</form> <!-- id="searchForm" -->
								
							</div> <!-- class="panel-body" -->
						</div> <!-- class="panel panel-default" -->
					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
			</div> <!-- row -->
		</div> <!-- class="container bs-docs-container" -->

	<?php

	} //del "else" de $_SESSION.

	?>


<!-- Footer bar & info
	================================================== -->
	<div id="footer" class="hidden-xs hidden-sm" >
		<div class="container">
			<p class="text-muted">&copy; Perspectiva Alemania, S.L.</p>
		</div>
	</div>


<!-- Scripts. Placed at the end of the document so the pages load faster.
	================================================== -->
	<!-- Bootstrap core JavaScript -->
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

	<!-- Page own functions -->
	<script type="text/javascript">
		function test (temp){
			switch (temp){
				case 1    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = false ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				case 2    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = true ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				default    :
				alert('What to do?');
			}
		}
	</script>
	
	<script type="text/javascript">
		function comprobar(){
			var x = document.getElementById("searchForm");
			var texto = "";
			for (var i=0;i<x.length;i++){
				texto = texto + x.elements[i].name + "<br>";
			}
		}
	</script>

</body>
</html>
