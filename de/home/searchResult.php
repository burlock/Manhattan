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
						$enlace = connectDB();
						
						if(strlen($_POST[blankWordKey])>0){
							$criteria = "WHERE (`nie` LIKE '%$_POST[blankWordKey]%' OR `name` LIKE '%$_POST[blankWordKey]%' OR `surname` LIKE '%$_POST[blankWordKey]%' OR `nationalities` LIKE '%$_POST[blankWordKey]%' OR `country` LIKE '%$_POST[blankWordKey]%' OR `province` LIKE '%$_POST[blankWordKey]%' OR `city` LIKE '%$_POST[blankWordKey]%' OR `mail` LIKE '%$_POST[blankWordKey]%' OR `marital` LIKE '%$_POST[blankWordKey]%' OR `language` LIKE '%$_POST[blankWordKey]%' OR `educTittle` LIKE '%$_POST[blankWordKey]%' OR `educCenter` LIKE '%$_POST[blankWordKey]%' OR `career` LIKE '%$_POST[blankWordKey]%' OR `experCity` LIKE '%$_POST[blankWordKey]%' OR `experCountry` LIKE '%$_POST[blankWordKey]%' OR `experPos` LIKE '%$_POST[blankWordKey]%' OR `experDesc` LIKE '%$_POST[blankWordKey]%' OR `otherDetails` LIKE '%$_POST[blankWordKey]%' OR `skill1` LIKE '%$_POST[blankWordKey]%' OR `skill2` LIKE '%$_POST[blankWordKey]%' OR `skill3` LIKE '%$_POST[blankWordKey]%' OR `skill4` LIKE '%$_POST[blankWordKey]%' OR `skill5` LIKE '%$_POST[blankWordKey]%' OR `skill6` LIKE '%$_POST[blankWordKey]%' OR `skill7` LIKE '%$_POST[blankWordKey]%' OR `skill8` LIKE '%$_POST[blankWordKey]%' OR `skill9` LIKE '%$_POST[blankWordKey]%' OR `skill10` LIKE '%$_POST[blankWordKey]%') AND `cvStatus` = 'checked'";
						}
						else{
							$moreThanOne = false; //If there is more than one fields to look for.
							$criteria ="WHERE `cvStatus` = 'checked'";
							$otherFields = "";
							if(strlen($_POST['blankNIE']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `nie` LIKE '%$_POST[blankNIE]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`nie` LIKE '%$_POST[blankNIE]%'";
								}
							}
							
							if(strlen($_POST['blankNationality']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `nationalities` LIKE '%$_POST[blankNationality]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`nationalities` LIKE '%$_POST[blankNationality]%'";
								}
							}
							
							if(isset($_POST['blankSex'])){
								if($moreThanOne){
									$otherFields = $otherFields." AND `sex` LIKE '%$_POST[blankSex]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`sex` LIKE '%$_POST[blankSex]%'";
								}
							}
							
							if(strlen($_POST['blankCity']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `city` LIKE '%$_POST[blankCity]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`city` LIKE '%$_POST[blankCity]%'";
								}
							}
							
							if(strlen($_POST['blankProvince']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `province` LIKE '%$_POST[blankProvince]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`province` LIKE '%$_POST[blankProvince]%'";
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
									$otherFields = $otherFields." AND `marital` LIKE '%$_POST[blankCivilStatus]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`marital` LIKE '%$_POST[blankCivilStatus]%'";
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
									$otherFields = $otherFields." AND `language` LIKE '%$_POST[blankLanguages]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`language` LIKE '%$_POST[blankLanguages]%'";
								}
							}
							
							if(strlen($_POST['blankLangLevels']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `langLevel` LIKE '%$_POST[blankLangLevels]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`langLevel` LIKE '%$_POST[blankLangLevels]%'";
								}
							}
							
							if(strlen($_POST['blankEducTittle']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `educTittle` LIKE '%$_POST[blankEducTittle]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`educTittle` LIKE '%$_POST[blankEducTittle]%'";
								}
							}
							
							if(strlen($_POST['blankEducCenter']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `educCenter` LIKE '%$_POST[blankEducCenter]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`educCenter` LIKE '%$_POST[blankEducCenter]%'";
								}
							}
							
							if(strlen($_POST['blankCareer']) > 0){
								if($moreThanOne){
									$otherFields = $otherFields." AND `career` LIKE '%$_POST[blankCareer]%'";
								}
								else{
									$moreThanOne = true;
									$otherFields = $otherFields."`career` LIKE '%$_POST[blankCareer]%'";
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
						}						
						
						$consulta = "SELECT * FROM `cvitaes`".$criteria;
						
						if ($resultado = mysqli_query($enlace, $consulta)) {
							//Obtaining field information for every column
							//$info_campo = mysqli_fetch_fields($resultado);
							$valores_mostrar = array("id", "name", "surname", "nationalities", "career");
							$columnTittles = array("ID", "Namen", "Nachnamen", "Nationalität", "Beruf");
							echo "<div class='table-responsive'>";
								echo "<table id='resultTable' class='table table-striped table-hover'>";
									echo "<thead>";
										echo "<tr>";
										foreach ($columnTittles as $valor) {
											echo "<th>$valor</th>";
										}
										echo "</tr>";
									echo "</thead>";
									
									//Extracting number of rows in result
									$auxNumRow = 1;
									while ($fila = $resultado->fetch_assoc()) {
										$pdf_file_name = "";
										$pdf_file_name = $fila['userLogin'];
										$imagen_o=$output_dir.$fila['userLogin']."/photo.jpg";
										$logo=$output_dir."/logo.png";
										$id[$fila['id']] = $fila['nie'];
										if ($fila['sex']==0){
											$fila['sex'] = "hombre";
										}
										if ($fila['sex']==1){
											$fila['sex'] = "mujer";
										}
										if ($_POST[reportType] == "custom_report"){
											$reportType=custom_report;
										}
										if ($_POST[reportType] == "blind_report"){
											$reportType=blind_report;
										}
										if ($_POST[reportType] == "full_report"){
											$reportType=full_report;
										}
										echo "<tr>";
											//Instead of using user's id, will be used an auto-increment id
											echo "<td>".$auxNumRow."</td>";
											echo "<td><a href=viewCV.php?id_b=".$fila['id']."&reportType=".$reportType." target=_blank>".$fila[$valores_mostrar[1]]."</a></td>";
											echo "<td>".($fila[$valores_mostrar[2]])."</td>";
											echo "<td>".($fila[$valores_mostrar[3]])."</td>";
											echo "<td>".($fila[$valores_mostrar[4]])."</td>";
										echo "</tr>";
										$auxNumRow++;
									}
								echo "</table>";
							echo "</div>";
							mysqli_free_result($resultado);
						}
						$i=0;
						foreach ($id as $valor) {
							$id_o[$i]=$valor;
							$i++;
						}
						echo "<form id='downloadSearchReport' name='downloadSearchReport' class='form-horizontal' method='post' action='downloadFile.php?doc=$filezip'>";
							echo "<div id='form_download' class='form-group pull-right' style='margin: 1px;'>";
								echo "<button type='submit' name='downloadSearchReportButton' class='btn btn-primary' >Bericht Herunterladen   <span class='glyphicon glyphicon-download-alt'> </span></button>";
							echo "</div>";
						echo "</form>";
						//$_SESSION["custom"]= serialize($_POST[per]);
						$_SESSION["id_o"] = serialize($id_o);
						$_SESSION["id"] = serialize($id);
						$_SESSION['customReportChecks'] = $_POST['per'];
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
