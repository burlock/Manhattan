<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Meine Daten</title>

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
							<li class="dropdown-header">Angeschossen wie: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../home/personalData.php">Persönliche Einstellungen</a></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Aussteigen</a></li>
						</ul>
					</li>
				</div>
				<?php if($userRow['employee'] == '1'){ ?>
					<a href="/common/files/CV Managing Tool - User Guide.pdf" style="float: right; margin-right: 60px; margin-top: 15px">Benutzerhandbuch</a>
				<?php }?>
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->
		
		
		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="../endsession.php">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="exitRequestLabel">Abmelden</h4>
					</div>
					<div class="modal-body">
						Haben Sie sich abmelden wollen?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Stormieren</button>
						<button type="submit" class="btn btn-primary">Wenn, melden</button>
					</div>
				</form>
			</div>
		</div> <!-- exitRequest Modal -->
		
		
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
				
				
				<!--  ****************************************   Start of displayed Modal HTML   ****************************************  -->
				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">
						<h2 class="page-header">Meine Daten</h2>
						<?php
						if(isset($_POST['hiddenPOST'])){
							switch ($_POST['hiddenPOST']){
								case 'hChangePassSubmit':
									if(!checkHashedPassChange($_POST['newPassword'], $_POST['confirmNewPassword'], getDBsinglefield('pass', 'users', 'login', $_SESSION['loglogin']), $userRow['language'], $keyError)){
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
										if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0', `passExpiration`='".addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'))."' WHERE `login`='".$_SESSION['loglogin']."'")){
											?>
											<script type="text/javascript">
												alert('Fehler: Es war nicht möglich ihr passwort zu aktualisieren.');
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
											alert('Fehler: Es war nicht möglich ihre sprache zu aktualisieren.');
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
											alert('Sprache aktualisiert.');
											window.location.href='personalData.php';
										</script>
										<?php
									}
								break;
							}
						}
						?>
						<!--  ****************************************   End of displayed Modal HTML   ****************************************  -->
						
						
						<!---------------------------------     Start of WebPage code initially showed     ---------------------------------->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">Passwort Ändern</h2>
							</div>
							<div id="panel-warning" class="panel panel-warning encapsulated center-block">
								<!-- If "passwdRestrictionsXX.txt" is changed function "checkXXXXXXPassChangeXX" (in validateFront.php) will be needed to be also changed -->
								<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/passwdRestrictionsDE.txt'; ?>
							</div>
							<div class="panel-body encapsulated center-block">
								<form id="changePasswordForm" name="changePasswordForm" class="form-horizontal" action="personalData.php" method="post" onsubmit="return equalPassword(newPassword, confirmNewPassword, '<?php echo getCurrentLanguage($_SERVER['SCRIPT_NAME']); ?>')">
									<div class="form-group">
										<label for="newPassword" class="control-label col-xs-3">Neues passwort</label>
										<div class="col-xs-8">
											<input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="" required data-toggle="tooltip" title="Introduce la nueva contraseña" autocapitalize="off">
										</div> 
									</div>
									<div class="form-group">
										<label for="confirmNewPassword" class="control-label col-xs-3">Wiederholen passwort</label>
										<div class="col-xs-8">
											<input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" placeholder="" required data-toggle="tooltip" title="Confirma la nueva contraseña" autocapitalize="off">
											<div class="fluid-container pull-right" style="margin-top: 15px;">
												<input type="hidden" value="hChangePassSubmit" name="hiddenPOST">
												<button type="submit" class="btn btn-primary" name="changePassword">Änderung</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						
						
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">Sprache ändern</h2>
							</div>
							<div class="panel-body encapsulated center-block">
								<form id="changeLanguageForm" name="changeLanguageForm" class="form-horizontal" action="personalData.php" method="post">
									<div class="form-group">
										<label for="changeLanguage" class="control-label col-xs-3">Gewählte sprache: </label>
										<div class="col-xs-8">
											<select name="changeLanguage" class="form-control">
												<?php 
												$userLanguage = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
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
												<button type="submit" class="btn btn-primary" name="changeLangSubmit">Änderung</button>
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
