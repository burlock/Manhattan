<?php 
	session_start();
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	set_time_limit(1800);
	set_include_path('../../common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
	set_include_path(get_include_path() . PATH_SEPARATOR . "../../common/cppdf");
?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs Encontrados</title>
	
	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../../common/img/apple-touch-icon.png">
</head>

<body>
	<?php
	if (!$_SESSION['loglogin']){
		?>
		<script type="text/javascript">
			window.location.href='../index.html';
		</script>
		<?php
	}
	else {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');

		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		
		//Identifying the name of the folder this script is in it can be later shown the rest of level 1 menus as the user navigates through them, knowing what of them is active (id='onlink')
		$myFile = 'home';
		
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-d H:i:s');
		$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
		//URL direct navigation for loggedin users with no granted access is limited here, as session expiration
		if(($elapsedTime > $_SESSION['sessionexpiration']) || (!accessGranted($_SERVER['SCRIPT_NAME'], $myFile, $userRow['profile']))){
			?>
			<script type="text/javascript">
				window.location.href='../endsession.php';
			</script>
			<?php
		}
		else{
			$_SESSION['lastupdate'] = $curUpdate;
			unset($lastUpdate);
			unset($curUpdate);
			unset($elapsedTime);
		}
		
		//Checks whether loaded php page/file corresponds to logged user's language
		if(getCurrentLanguage($_SERVER['SCRIPT_NAME']) != $userRow['language']){
			$userRootLang = getUserRoot($userRow['language']);
			$noRootPath = getNoRootPath($_SERVER['SCRIPT_NAME']);
			?>
			<script type="text/javascript">
				window.location.href='<?php echo $userRootLang.$noRootPath ?>';
			</script>
			<?php
		}
		?>
		
		
		<!-- Static navbar -->
		<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
			<div id="top_line" class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
				</div>
				<div class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<button type="button" class="navbar-toggle always-visible" data-toggle="dropdown">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Conectado como: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../home/personalData.php">Configuración personal</a></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Salir</a></li>
						</ul>
					</li>
				</div>
				<?php if($userRow['employee'] == '1'){ ?>
					<a href="/common/files/CV Managing Tool - User Guide.pdf" style="float: right; margin-right: 60px; margin-top: 15px">Guía de Usuario</a>
				<?php }?>
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->
		
		
		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="../endsession.php">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="exitRequestLabel">Cerrar sesión</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro de que quieres salir?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Sí, cerrar sesión</button>
					</div>
				</form>
			</div>
		</div> <!-- exitRequest Modal -->
		
		
		<?php
			$pendingCVs = getPendingCVs();
		?>
		
		
		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
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
						<h2 class="page-header">Resultados de la Búsqueda</h2>
						<?php
						include 'Cezpdf.php';

						$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";

						class Creport extends Cezpdf{
							function Creport($p,$o){
								$this->__construct($p, $o,'none',array());
							}
						}
						$numero = date("YmdHis");
						$enlace = connectDB();
						
						if(strlen($_POST[blankWordKey])>0){
							$criteria="where `nie` like '%$_POST[blankWordKey]%' or `nationalities` like '%$_POST[blankWordKey]%' or `province` like '%$_POST[blankWordKey]%' or `city` like '%$_POST[blankWordKey]%' or `marital` like '%$_POST[blankWordKey]%' or `language` like '%$_POST[blankWordKey]%' or `education` like '%$_POST[blankWordKey]%' or `career` like '%$_POST[blankWordKey]%' or `experPos` like '%$_POST[blankWordKey]%' or `experDesc` like '%$_POST[blankWordKey]%' or `otherDetails` like '%$_POST[blankWordKey]%' and cvStatus = 'checked';";
						}
						else{
							$criteria="where `nie` like '%$_POST[blankNIE]%' and `nationalities` like '%$_POST[blankNationality]%' and `sex` like '%$_POST[blankSex]%' and `drivingType` like '%$_POST[drivingtype]%' and `marital` like '%$_POST[civilStatus]%' and `sons` like '%$_POST[blankSons]%' and `language` like '%$_POST[blankLanguages]%' and `career` like '%$_POST[blankJob]%' and cvStatus = 'checked';";
						}						
						
						$consulta = "SELECT * FROM `cvitaes`".$criteria;
						
						if ($resultado = mysqli_query($enlace, $consulta)) {
							//Obtaining field information for every column
							//$info_campo = mysqli_fetch_fields($resultado);
							$valores_mostrar = array("id", "name", "surname", "nationalities", "career");
							$columnTittles = array("ID", "Nombre", "Apellidos", "Nacionalidad", "Profesión");
							echo "<div class='table-responsive'>";
								echo "<table id='resultTable' class='table table-striped table-hover'>";
									echo "<thead>";
										echo "<tr>";
										foreach ($columnTittles as $valor){
											echo "<th>$valor</th>";
										}
										echo "</tr>";
									echo "</thead>";
									
									//Extracting number of rows in result
									$auxNumRow = 1;
									while ($fila = $resultado->fetch_assoc()) {
										$pdf_file_name = "";
										$pdf_file_name = $fila['userLogin'];
										$imagen_o=$output_dir.$fila['userLogin']."/fotor.jpg";
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
								echo "<button type='submit' name='downloadSearchReportButton' class='btn btn-primary' >Descargar Informe   <span class='glyphicon glyphicon-download-alt'> </span></button>";
							echo "</div>";
						echo "</form>";
						$_SESSION["custom"]= serialize($_POST[per]);
						$_SESSION["id_o"] = serialize($id_o);
						$_SESSION["id"] = serialize($id);
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
