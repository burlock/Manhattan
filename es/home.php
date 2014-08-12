<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Inicio</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">
</head>

<body>
	<?php
	if (!$_SESSION['loglogin']){
		?>
		<script type="text/javascript">
			window.location.href='index.html';
		</script>
		<?php
	}
	else {
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-d H:i:s');
		$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
		if($elapsedTime > $_SESSION['sessionexpiration']){
			?>
			<script type="text/javascript">
				window.location.href='endsession.php';
			</script>
			<?php
		}
		else{
			$_SESSION['lastupdate'] = $curUpdate;
			unset($lastUpdate);
			unset($curUpdate);
			unset($elapsedTime);
		}
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		
		//Checks whether logged user has selected language or not, redirecting him to its proper language page/file if needed
		if((strlen($userRow['language']) < 1)){
			$userLang = getCurrentLanguage($_SERVER['SCRIPT_NAME']);
			if(!executeDBquery("UPDATE `users` SET `language`='".$userLang."' WHERE `login`='".$_SESSION['loglogin']."'")){
				?>
				<script type="text/javascript">
					alert('Error actualizando idioma neutro.');
					window.location.href='endsession.php';
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript">
					window.location.href='home.php';
				</script>
				<?php
			}
		}
		elseif(getCurrentLanguage($_SERVER['SCRIPT_NAME']) != $userRow['language']){
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
						<img src="../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
				</div>
				<!-- <div class="navbar-collapse collapse"> -->
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
								<li><a href="home/personalData.php">Configuración personal</a></li>
								<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Salir</a></li>
							</ul>
						</li>
					</div>
				<!-- </div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->


		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="endsession.php">
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
		</div>

		 
		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
			//$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

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
										echo "<li class='active'><a href=$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
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
													if ($level2File == 'pendingCVs') {
														echo "<li><span class='badge'>$pendingCVs</span><a href=home/$level2File.php>" . $subLevelMenu . " </a></li>";
													}
													else 
														echo "<li><a href=home/$level2File.php>" . $subLevelMenu . " </a></li>";
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
										echo "<li><a href=$i.php>" . $mainNamesRow[$j] . "</a></li>";
										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->
				
				
				<!--  ***********************************   Start of Web Page as showed for User   ***********************************  -->
				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">
						<?php 
						//Conditional block for 'Administrador' or 'SuperAdmin' profiles
						if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
							echo "<h1 class='page-header'>Noticias <br></h1>";
							echo "<div class='clearfix'>";
							if((getDBrowsnumber('cvitaes') == 0) || ($pendingCVs == 0)){
								echo "<h1 class='page-header'><small>No existen CVs por validar</small></h1>";
							}
							else{
								echo "<h2><small>Existen <a href=./home/pendingCVs.php>" . $pendingCVs . " </a> CVs por validar </small></h2><br>";
							}

							if(suggestPassword(date('Y-m-d'), $userRow['passExpiration'], $days)){
								echo "<h4 class='text-danger'>&nbsp;Su contraseña caduca en " . $days . " días. <a href=./home/personalData.php>Cambiar</a><span class='label label-danger notice-label pull-left'>Aviso</span></h4>";
								echo "</div>";
							}
						}
						//Conditional block for 'Lector' profile
						elseif($userRow['profile'] == 'Lector'){
							if(suggestPassword(date('Y-m-d'), $userRow['passExpiration'], $days)){
								echo "<h1 class='page-header'>Noticias <br></h1>";
								echo "<div class='clearfix'>";
								echo "<h4 class='text-danger'>&nbsp;Su contraseña caduca en " . $days . " días. <a href=./home/personalData.php>Cambiar</a><span class='label label-danger notice-label pull-left'>Aviso</span></h4>";
								echo "</div>";
							}
							else{
								echo "<h1 class='page-header'>Noticias <br></h1>";
								echo "<div class='clearfix'>";
							}
						}
						//Conditional block for any other profile (which in fact is only 'Candidato')
						else{
							echo "<h1 class='page-header'>Introduce tu CV... <small>" . $userRow['login'] . "</small></h1>";
							include 'upload.php';
						}
						//This part of code lets the system show a previously written message (SAVED IN FILE "broadcasting.txt")
						if(($userRow['employee'] == '1') && (file_exists($_SERVER['DOCUMENT_ROOT'].'/broadcasting.txt'))){
							echo "<br><h4 class='text-danger'><span class='label label-warning notice-label pull-left'>Información importante</span></h4><br>";
							echo "<h4 class='text-warning'>";
							include '../broadcasting.txt';
							echo "</h4>";
						}
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
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../common/js/functions.js"></script>
	<script src="../common/js/application.js"></script>
	<script src="../common/js/docs.min.js"></script>

</body>
</html>
