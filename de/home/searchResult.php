<?php 
	session_start();
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	set_time_limit(1800);
	set_include_path('/common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
	set_include_path(get_include_path() . PATH_SEPARATOR . "/common/cppdf");
?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs Gefunden</title>
	
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
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');
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

				<div class="col-md-9 scrollable" role="main">
					<div class="bs-docs-section">
						<h2 class="page-header">Die Ergebnisse der Suche</h2>
						<?php
						include 'Cezpdf.php';

						$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";

						class Creport extends Cezpdf{
							function Creport($p,$o){
								$this->__construct($p, $o,'none',array());
							}
						}
						
						if(strlen($_POST[blankWordKey])>0){
							$criteria = "WHERE (
								`nie` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`name` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`surname` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`keyCountry` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`country` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`province` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`city` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`mail` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`marital` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`keyLanguage` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`educTittle` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`educCenter` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`keyOccupation` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`company` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`position` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`city` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`country` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`description` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`otherDetails` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill1` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill2` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill3` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill4` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill5` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill6` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill7` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill8` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill9` LIKE '%".securizeString($_POST[blankWordKey])."%' OR 
								`skill10` LIKE '%".securizeString($_POST[blankWordKey])."%' ) AND `cvStatus` = 'checked'";
						}
						else{
							//Búsqueda específica. Solo se usarán como filtro aquellos campos que vengan provisionados
							$moreThanOne = false; //If there is more than one fields to look for.
							$criteria ="WHERE `cvStatus` = 'checked'";
							$otherFields = "";
							if(strlen($_POST[blankNIE]) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `nie` LIKE '%".securizeString($_POST[blankNIE])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`nie` LIKE '%".securizeString($_POST[blankNIE])."%'";
								}
							}
							
							if(strlen($_POST['blankNationality']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `keyCountry` = '$_POST[blankNationality]'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`keyCountry` = '$_POST[blankNationality]'";
								}
							}
							
							if(isset($_POST['blankSex'])){
								if($moreThanOne){
									$otherFields = $otherFields." AND `sex` = '$_POST[blankSex]'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`sex` = '$_POST[blankSex]'";
								}
							}
							
							if(strlen($_POST['blankCity']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `city` LIKE '%".securizeString($_POST[blankCity])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`city` LIKE '%".securizeString($_POST[blankCity])."%'";
								}
							}
							
							if(strlen($_POST['blankProvince']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `province` LIKE '%".securizeString($_POST[blankProvince])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`province` LIKE '%".securizeString($_POST[blankProvince])."%'";
								}
							}
							
							if(strlen($_POST['blankDrivingType']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `drivingType` = '$_POST[blankDrivingType]'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`drivingType` = '$_POST[blankDrivingType]'";
								}
							}
							
							if(strlen($_POST['blankCivilStatus']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `marital` = '%$_POST[blankCivilStatus]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`marital` = '%$_POST[blankCivilStatus]%'";
								}
							}
							
							if($_POST['blankSons'] != NULL){
								if($moreThanOne){
									$otherFields = $otherFields." AND `sons` = '$_POST[blankSons]'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`sons` = '$_POST[blankSons]'";
								}
							}
							
							if(strlen($_POST['blankLanguages']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `keyLanguage` = '%$_POST[blankLanguages]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`keyLanguage` = '%$_POST[blankLanguages]%'";
								}
							}
							
							if(strlen($_POST['blankLangLevels']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `level` = '%$_POST[blankLangLevels]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`level` = '%$_POST[blankLangLevels]%'";
								}
							}
							
							if(strlen($_POST['blankEducTittle']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `educTittle` LIKE '%".securizeString($_POST[blankEducTittle])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`educTittle` LIKE '%".securizeString($_POST[blankEducTittle])."%'";
								}
							}
							
							if(strlen($_POST['blankEducCenter']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `educCenter` LIKE '%".securizeString($_POST[blankEducCenter])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`educCenter` LIKE '%".securizeString($_POST[blankEducCenter])."%'";
								}
							}
							
							if(strlen($_POST['blankCareer']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `career` LIKE '%".securizeString($_POST[blankCareer])."%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`career` LIKE '%".securizeString($_POST[blankCareer])."%'";
								}
							}
							
							if(strlen($_POST['blankCandidateStatus']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `candidateStatus` = '$_POST[blankCandidateStatus]'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`candidateStatus` = '$_POST[blankCandidateStatus]'";
								}
							}
							
							$and = " AND ";
							$open = "(";
							$close = ")";
							if($moreThanOne){
								$criteria = $criteria.$and.$open.$otherFields.$close;
							}
							else{
								//aqui no vendría nada si se hace una búsqueda vacía, solo los CVs 'checked' se buscarían.
								//Entrar en este 'else' implica estar haciendo una búsqueda vacía, sin filtros. Eso mostraría todos los CVs en estado 'checked'.
							}
						}//else que busca por campos específicos
						
						
						$query = "SELECT DISTINCT cvitaes.*, (SELECT GROUP_CONCAT(userCountries.keyCountry SEPARATOR '|') FROM userCountries WHERE cvitaes.nie = userCountries.userNIE), 
							(SELECT GROUP_CONCAT(userLanguages.keyLanguage SEPARATOR '|') FROM userLanguages WHERE cvitaes.nie = userLanguages.userNIE), 
							(SELECT GROUP_CONCAT(userLanguages.level SEPARATOR '|') FROM userLanguages WHERE cvitaes.nie = userLanguages.userNIE),
							(SELECT GROUP_CONCAT(userEducations.educTittle SEPARATOR '|') FROM userEducations WHERE cvitaes.nie = userEducations.userNIE), 
							(SELECT GROUP_CONCAT(userEducations.educCenter SEPARATOR '|') FROM userEducations WHERE cvitaes.nie = userEducations.userNIE), 
							(SELECT GROUP_CONCAT(userEducations.educStart SEPARATOR '|') FROM userEducations WHERE cvitaes.nie = userEducations.userNIE), 
							(SELECT GROUP_CONCAT(userEducations.educEnd SEPARATOR '|') FROM userEducations WHERE cvitaes.nie = userEducations.userNIE), 
							(SELECT GROUP_CONCAT(userOccupations.keyOccupation SEPARATOR '|') FROM userOccupations WHERE cvitaes.nie = userOccupations.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.company SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.position SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.start SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.end SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.city SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.country SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE), 
							(SELECT GROUP_CONCAT(userExperiences.description SEPARATOR '|') FROM userExperiences WHERE cvitaes.nie = userExperiences.userNIE) 
							FROM cvitaes INNER JOIN userCountries ON cvitaes.nie = userCountries.userNIE ".$criteria;
						
						//AÑADIR UN 'ORDER BY `surname`' PARA QUE PUEDA MOSTRAR EL RESULTADO ORDENADO ALFABÉTICAMENTE SEGUN LOS APELLIDOS
						
						
						$connection = connectDB();
						
						if($result = mysqli_query($connection, $query)) {
							$columnTittles = array("ID", "Name", "Nationalität", "Beruf");
							//If query returns any result, table with information is showed.
							if(mysqli_num_rows($result) > 0){
								$rowID = 0;
								$reportType = $_POST[reportType];
								if ($_POST[reportType] == "custom_report"){
									$_SESSION['customReportChecks'] = $_POST['per'];
								}
								//NIE from resultant Candidate is saved in an Array
								$resultNIEsRow = array();
								echo "<div class='table-responsive'>";
									echo "<table id='resultTable' class='table table-striped table-hover'>";
										echo "<thead>";
											echo "<tr>";
											foreach ($columnTittles as $valor){
												echo "<th>$valor</th>";
											}
											echo "</tr>";
										echo "</thead>";
										while($resultRow = mysqli_fetch_row($result)){
											$id[$resultRow[0]] = $resultRow[1]; //$id[$fila['id']] = $fila['nie'];
											echo "<tr>";
												//Instead of using user's id, will be used an auto-increment id
												echo "<td>".($rowID+1)."</td>";
												echo "<td><a href=viewCV.php?id_b=".$resultRow[0]."&reportType=".$reportType." target=_blank>".$resultRow[4].', '.$resultRow[3]."</a></td>";
												//INCLUIR SOLO SI QUIEREN SEPARADOS NOMBRE Y APELLIDOS echo "<td>".($resultRow[4])."</td>";
												echo "<td>".($resultRow[43])."</td>";
												echo "<td>".($resultRow[50])."</td>";
											echo "</tr>";
											$resultNIEsRow[$rowID] = $resultRow[1]; //ESTO ES $id_o[$i]=$valor
											$rowID++;
										}//while
									echo "</table>";
								echo "</div>";
								mysqli_free_result($result);
							}
							else{
								echo "Es gibt keine Ergebnisse für Ihre Suche.";
							}
						}
						
						echo "<form id='downloadSearchReport' name='downloadSearchReport' class='form-horizontal' method='post' action='downloadFile.php?doc=$filezip'>";
							echo "<div id='form_download' class='form-group pull-right' style='margin: 1px;'>";
								echo "<button type='submit' name='downloadSearchReportButton' class='btn btn-primary' >Bericht Herunterladen <span class='glyphicon glyphicon-download-alt'> </span></button>";
							echo "</div>";
						echo "</form>";
						
						$_SESSION["serializedNIEs"] = serialize($resultNIEsRow);
						$_SESSION["id"] = serialize($id);//ESTO ES UN ARRAY CON LOS VALORES DEL CAMPO 'id' EN LA TABLA cvitaes
						
						?>
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
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

</body>
</html>
