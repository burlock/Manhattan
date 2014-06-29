<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Mis Datos</title>

	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">
	<!-- <link href="../../common/css/styles.css" rel="stylesheet"> -->
	<!-- <link href="../common/css/docs.css" rel="stylesheet"> -->

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
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-d H:i:s');
		$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
		if($elapsedTime > $_SESSION['sessionexpiration']){
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
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
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
							<li><a href="../home/personalData.php">Configuración personal</a></li>
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



		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();
		?>


		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
							$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
							$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
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
											if(($myFileProfileRow[$k] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))) {
												if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
													$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
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
						<h2 class="page-header">Mis datos</h2>
						<?php
						/*****************************  DESDE AQUI...
						if(isset($_POST['changePassword'])){
							//if(!checkPassChange($_POST['newPassword'], $_POST['confirmNewPassword'], $keyError)){
							if(!checkPassChangeES($_POST['newPassword'], $_POST['confirmNewPassword'], getDBsinglefield('pass', 'users', 'login', $_SESSION['loglogin']), $keyError)){
								?>
								<script type="text/javascript">
									alert('<?php echo $keyError; ?>');
									window.location.href='personalData.php';
								</script>
								<?php 
							}
							//That's when system generates new Blowfish password
							else{
								$newCryptedPass = blowfishCrypt($_POST['newPassword']);
								if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0' WHERE `login`='".$_SESSION['loglogin']."'")){
								//session_destroy(); DEBERIA DESTRUIR LA SESSION
									?>
									<script type="text/javascript">
										alert('No fue posible actualizar su contraseña.');
										window.location.href='personalData.php';
									</script>
									<?php 
								}
								else{
									$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
									$_SESSION['logprofile'] = $userRow['profile'];
									$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
									$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
									?>
									<script type="text/javascript">
										window.location.href='personalData.php';
									</script>
									<?php 
								}
							}
						}
						... HASTA AQUI HABRÁ QUE BORRARLO **********************************/
						
						
						if(isset($_POST['hiddenPOST'])){
							switch ($_POST['hiddenPOST']){
								case 'hChangePassSubmit':
									if(!checkHashedPassChangeES($_POST['newPassword'], $_POST['confirmNewPassword'], getDBsinglefield('pass', 'users', 'login', $_SESSION['loglogin']), $keyError)){
										?>
										<script type="text/javascript">
											alert('<?php echo $keyError; ?>');
											window.location.href='personalData.php';
										</script>
										<?php 
									}
									//That's when system generates new Blowfish password
									else{
										$newCryptedPass = blowfishCrypt($_POST['newPassword']);
										//if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0', `passExpiration`='".addMonthsToDate(6)."' WHERE `login`='".$_SESSION['loglogin']."'")){
										if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0', `passExpiration`='".addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'))."' WHERE `login`='".$_SESSION['loglogin']."'")){
											?>
											<script type="text/javascript">
												alert('No fue posible actualizar su contraseña.');
												window.location.href='personalData.php';
											</script>
											<?php 
										}
										else{
											$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
											$_SESSION['logprofile'] = $userRow['profile'];
											$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
											$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
											?>
											<script type="text/javascript">
												window.location.href='personalData.php';
											</script>
											<?php 
										}
									}
								break;
								
								case 'hChangeLangSubmit':
									if(!executeDBquery("UPDATE `users` SET `language`='".$_POST['changeLanguage']."' WHERE `login`='".$_SESSION['loglogin']."'")){
										?>
										<script type="text/javascript">
											alert('No fue posible actualizar su idioma.');
											window.location.href='personalData.php';
										</script>
										<?php 
									}
									else{
										$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
										$_SESSION['logprofile'] = $userRow['profile'];
										$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
										$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
										?>
										<script type="text/javascript">
											window.location.href='personalData.php';
										</script>
										<?php 
									}
								break;
							}
						}
						?>
						
						<!---------------------------------     Start of WebPage code initially showed     ---------------------------------->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">Cambio de contraseña</h2>
							</div>
							
							<div id="panel-warning" class="panel panel-warning encapsulated center-block">
								<!-- If "passwdRestrictionsXX.txt" is changed function "checkXXXXXXPassChangeXX" (in validateFront.php) will be needed to be also changed -->
								<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/passwdRestrictionsES.txt'; ?>
							</div>
							<div class="panel-body encapsulated center-block">
								<form id="changePasswordForm" name="changePasswordForm" class="form-horizontal" action="personalData.php" method="post" onsubmit="return equalPassword(newPassword, confirmNewPassword)">
									<div class="form-group">
										<label for="newPassword" class="control-label col-xs-3">Nueva contraseña</label>
										<div class="col-xs-8">
											<input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="" required data-toggle="tooltip" title="Introduce la nueva contraseña" autocapitalize="off">
										</div> 
									</div>
									<div class="form-group">
										<label for="confirmNewPassword" class="control-label col-xs-3">Repita contraseña</label>
										<div class="col-xs-8">
											<input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" placeholder="" required data-toggle="tooltip" title="Confirma la nueva contraseña" autocapitalize="off">
											<div class="fluid-container pull-right" style="margin-top: 15px;">
												<input type="hidden" value="hChangePassSubmit" name="hiddenPOST">
												<button type="submit" class="btn btn-primary" name="changePassword">Cambiar</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						
						
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">Idioma</h2>
							</div>
							
							<div class="panel-body encapsulated center-block">
								<form id="changeLanguageForm" name="changeLanguageForm" class="form-horizontal" action="personalData.php" method="post">
									<div class="form-group">
										<label for="changeLanguage" class="control-label col-xs-3">Idioma seleccionado: </label>
										<div class="col-xs-8">
											<select name="changeLanguage" class="form-control">
												<!-- <option selected value=''>Idioma</option> -->
												<?php 
												$userLanguage = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
												//$languageKeys = getDBcompletecolumnID('key', 'siteLanguages', 'id');
												$localLanguages = getDBcompletecolumnID($userLanguage, 'siteLanguages', $userLanguage);
												foreach($localLanguages as $i){
													$keyLang = getDBsinglefield('key', 'siteLanguages', $userLanguage, $i);
													if($keyLang == $userLanguage){
														echo "<option selected value=" . $keyLang . ">" . $i . "</option>";
													}
													else{
														echo "<option value=" . $keyLang . ">" . $i . "</option>";
													}
												}
												?>
											</select>
											<div class="fluid-container pull-right" style="margin-top: 15px;">
												<input type="hidden" value="hChangeLangSubmit" name="hiddenPOST">
												<button type="submit" class="btn btn-primary" name="changeLangSubmit">Cambiar</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						
						<!---------------------------------     End of WebPage code initially showed     ---------------------------------->
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

</body>
</html>

</html>
