<?php 
		session_start();
		error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Benutzerverwaltung</title>

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
		$myFile = 'administration';

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
				<!-- <div class="navbar-collapse collapse"> -->
				<div class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<button type="button" class="navbar-toggle always-visible" data-toggle="dropdown">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Angeschlossen wie: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../home/personalData.php">Persönliche Einstellungen</a></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Aussteigen</a></li>
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
						<h4 class="modal-title" id="exitRequestLabel">Abmelden</h4>
					</div>
					<div class="modal-body">
						Haben Sie sich abmelden wollen?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Stornieren</button>
						<button type="submit" class="btn btn-primary">Wenn, melden</button>
					</div>
				</form>
			</div>
		</div>
		
		
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
													if ($level3File == basename(__FILE__, '.php')) 
														echo "<li class='active'><a href=$level3File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li><a href=$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}
										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";
									}
									else{ 
										if ($i == 'home')
											echo "<li><span class='badge'>$pendingCVs</span><a href=../$i.php>" . $mainNamesRow[$j] . " </a></li>";
										else 
											echo "<li><a href=../$i.php>" . $mainNamesRow[$j] . " </a></li>";

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
						<h2 class="page-header">Benutzerverwaltung</h2>
						<?php
						
						/*****************************     Start of FORM validations     *****************************/
						if(isset($_POST['newUsubmit'])){
							if (isset($_POST['newUName']) && !empty($_POST['newUName'])){
								$newUser = $_POST['newUName'];
								if(strpos(trim($newUser), " ") > 0){
									$newUser = str_replace(' ', '', $newUser);
								}
								$newUser = dropAccents($newUser);
								if(getDBsinglefield('login', 'users', 'login', $newUser)){
									?>
									<script type="text/javascript">
										alert('Der ausgewählte Benutzer existiert bereits');
										window.location.href='admCurUsers.php';
									</script>
									<?php
								}
								else{
									$initialPass = getRandomPass();
									$expirationDate = addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'));
									if(!executeDBquery("INSERT INTO `users` (`id`, `login`, `pass`, `profile`, `employee`, `active`, `language`, `needPass`, `created`, `passExpiration`) VALUES 
									(NULL, '".utf8_decode($newUser)."', '".$initialPass."', '".utf8_decode($_POST['newUProfile'])."', '1', '1', '".utf8_decode($_POST['newULanguage'])."', '1', CURRENT_TIMESTAMP, '".$expirationDate."')")){
									
										?>
										<script type="text/javascript">
											alert('Fehler beim Ausfüllen des neuen Benutzers');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										//Adding 1 user to newUser's profile
										$profileUsers = getDBsinglefield('numUsers', 'profiles', 'name', $_POST['newUProfile']);
										$profileUsers += 1;
										executeDBquery("UPDATE `profiles` SET `numUsers`='".$profileUsers."' WHERE `name`='".$_POST['newUProfile']."'");
										?>
										<script type="text/javascript">
											alert('Benutzer <?php echo $newUser; ?> wurde erfolgreich gespeichert. \nIhr Standardpasswort ist: <?php echo $initialPass; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								}
							}
						}
						
						if(isset($_POST['newUsubmitC'])){
							$newUser = getNextCandidateName();
							if(getDBsinglefield('login', 'users', 'login', $newUser)){
								?>
								<script type="text/javascript">
									alert('Der ausgewählte Benutzer existiert bereits.');
									window.location.href='admCurUsers.php';
								</script>
								<?php
							}
							else{
								$initialPass = getRandomPass();
								$expirationDate = addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'));
								if(!executeDBquery("INSERT INTO `users` (`id`, `login`, `pass`, `profile`, `active`, `needPass`, `created`, `passExpiration`) VALUES 
								(NULL, '".utf8_decode($newUser)."', '".$initialPass."', 'Candidato', '1', '1', CURRENT_TIMESTAMP, '".$expirationDate."')")){
								?>
									<script type="text/javascript">
										alert('Fehler beim Ausfüllen des neuen Kandidaten.');
										window.location.href='admCurUsers.php';
									</script>
									<?php
								}
								else{
									//+1 to otherOptions' "lastCandidate" parameter. +1 to profiles' "Candidato" parameter. Creation of user's directory
									if(!addCandidate($newUser, $userRow['language'], $addError)){
										?>
										<script type="text/javascript">
											alert('<?php echo $addError; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										?>
										<script type="text/javascript">
											alert('Zugangsinformationen für den Kandidat:\n Login: <?php echo $newUser; ?> \n Password: <?php echo $initialPass; ?> \n URL: http://areaprivada.perspectivaalemania.com ');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								}
							}
						}
						
						elseif(isset($_GET['hiddenGET'])){
							switch($_GET['hiddenGET']){
								case 'hDelUser':
									$userRow = getDBrow('users', 'id', $_GET['codvalue']);
									if(!deleteDBrow('cvitaes', 'userLogin', getDBsinglefield('login', 'users', 'id', $_GET['codvalue']))){
										?>
										<script type="text/javascript">
											alert('Fehler beim Kandidat lebenslauf löschen.');
											window.location.href='admCurUsers.php';
										</script>
										<?php 
									}
									else{
										if(!deleteDBrow('users', 'id', $_GET['codvalue'])){
											?>
											<script type="text/javascript">
												alert('Konnte den Kandidat zu löschen.');
												window.location.href='admCurUsers.php';
											</script>
											<?php 
										}
										else{
											$numProfileUsers = getDBsinglefield('numUsers', 'profiles', 'name', $userRow['profile']);
											$numProfileUsers--;
											executeDBquery("UPDATE `profiles` SET `numUsers`='".$numProfileUsers."' WHERE `name`='".$userRow['profile']."'");
											$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$userRow['login']."/";
											$files  = scandir($userDir);
											foreach ($files as $value){
												unlink($userDir.$value);
											}
											rmdir($userDir);
										}
									}
								break;
								
								case 'hResPwd':
									//DEBO LLAMAR A LA FUNCION getRandomPass() Y MARCAR A '1' EL FLAG needPass
									$userRow = getDBrow('users', 'id', $_GET['codvalue']);
									$initialPass = getRandomPass();
									$expirationDate = addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'));
									if(!executeDBquery("UPDATE `users` SET `pass`='".$initialPass."', `needPass`='1', `passExpiration`='".$expirationDate."' WHERE `id`='".$userRow['id']."'")){
										?>
										<script type="text/javascript">
											alert('Fehler beim benutzer attribut ändern.');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										?>
										<script type="text/javascript">
											alert('Benutzer-Passwort erfolgreich zurückgesetzt.\nNeues passwort: <?php echo $initialPass; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								break;
							}
							?>
							<script type="text/javascript">
								window.location.href='admCurUsers.php';
							</script>
							<?php 
						}//end of GET
						/*****************************     End of FORM validations     *****************************/
						
						/*************************     Start of WebPage code as showed     *************************/
						
						if($_SESSION['logprofile'] == 'SuperAdmin'){
							?>
							<div class="panel panel-default"> <!-- Panel de Usuarios Existentes -->
								<div class="panel-heading">
									<h3 class="panel-title">Existierende Benutzer</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="usersTable" class="table table-striped table-hover">
											<thead>
												<tr>
													<th>Id</th>
													<th>Login</th>
													<th>Profil</th>
													<th>Angestellter</th>
													<th>Gültig</th>
													<th>Sprache</th>
													<th>Gegründet</th>
													<th>Letzter Anschluss</th>
													<th>Password gültig bis</th>
													<th>Handlung</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$userKeyRow = getDBcompletecolumnID('login', 'users', 'id');
												$k = 1;
												foreach($userKeyRow as $i){
													$showedUserRow = getDBrow('users', 'login', $i);
													echo "<tr>";
														echo "<td>" . $k . "</td>";
														echo "<td><a class='launchModal' href='admCurUsers.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
														echo "<td>" . $showedUserRow['profile'] . "</td>";
														if($showedUserRow['employee'] == 1){
															echo "<td>Ja</td>";
														}
														else{
															echo "<td>Nicht</td>";
														}
														if($showedUserRow['active']){
															echo "<td>Ja</td>";
														}
														else{
															echo "<td>Nicht</td>";
														}
														echo "<td>" . getDBsinglefield($userRow['language'], 'siteLanguages', 'key', $showedUserRow['language']) . "</td>";
														echo "<td>" . $showedUserRow['created'] . "</td>";
														echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
														echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
														echo "<td><a href='admCurUsers.php?codvalue=" . $showedUserRow['id'] . "&hiddenGET=hDelUser' onclick='return confirmUserDeletionES();'>Borrar</a></td>";
													echo "</tr>";
													$k++;
												}
												?>
											</tbody>
										</table>
									</div>

									<div class="container-fluid center-block">
										<h4>Neuer Benutzer</h4>
										<form class="form-inline" role="form" name="newUser" action="admCurUsers.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="newUName">Benutzer</label>
												<input type="text" class="form-control" size="6" name="newUName" placeholder="Benutzer" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newUProfile">Profil</label>
												<select name="newUProfile" class="form-control">
													<option selected disabled value=''>Profil</option>
													<?php 
														$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
														foreach($profNames as $i){
															if ($i != 'Candidato'){
															echo "<option value=" . $i . ">" . $i . "</option>";}
														}
													?>
												</select>
											</div>
											<div class="form-group">
												<label class="sr-only" for="newULanguage">Sprache</label>
												<select name="newULanguage" class="form-control">
													<option selected disabled value=''>Sprache</option>
													<?php 
														$userLanguage = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
														$siteLanguages = getDBcompletecolumnID($userLanguage, 'siteLanguages', 'id');
														$languageKeys = getDBcompletecolumnID('key', 'siteLanguages', 'id');
														
														$i = 0;
														for ($i=0; $i < count($siteLanguages); $i++) { 
															echo "<option value=" . $languageKeys[$i] . ">" . $siteLanguages[$i] . "</option>";
														}
													?>
												</select>
											</div>
											<button type="submit" class="btn btn-primary" name="newUsubmit" value="Añadir">Hinzufügen</button>
											<button type="submit" class="btn btn-primary pull-right" name="newUsubmitC" value="AñadirC">Erstellen Kandidat</button>
										</form>
									</div>
									
								</div>
							</div> <!-- Panel de Usuarios existentes -->	
						<?php 
						}
						elseif($_SESSION['logprofile'] == 'Administrador'){
							?>
							<div class="panel panel-default"> <!-- Panel de Usuarios Existentes -->
								<div class="panel-heading">
									<h3 class="panel-title">Existierende Benutzer</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="usersTable" class="table table-striped table-hover">
											<thead>
												<tr>
													<th>Id</th>
													<th>Login</th>
													<th>Profil</th>
													<th>Gültig</th>
													<th>Sprache</th>
													<th>Gegründet</th>
													<th>Letzter Anschluss</th>
													<th>Password gültig bis</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$userKeyRow = getDBNoMatchColValueID('login', 'users', 'profile', 'SuperAdmin', 'id');
												$k = 1;
												foreach($userKeyRow as $i){
													$showedUserRow = getDBrow('users', 'login', $i);
													echo "<tr>";
													echo "<td>" . $k . "</td>";
													echo "<td><a class='launchModal' href='admCurUsers.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
													echo "<td>" . $showedUserRow['profile'] . "</td>";
													if($showedUserRow['active']){
														echo "<td>Ja</td>";
													}
													else{
														echo "<td>Nicht</td>";
													}
													echo "<td>" . getDBsinglefield($userRow['language'], 'siteLanguages', 'key', $showedUserRow['language']) . "</td>";
													echo "<td>" . $showedUserRow['created'] . "</td>";
													echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
													echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
													echo "</tr>";
													$k++;
												}
												?>
											</tbody>
										</table>
									</div>

									<div class="container-fluid center-block">
										<h4>Neuer Benutzer</h4>
										<form class="form-inline" role="form" name="newUser" action="admCurUsers.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="newUName">Benutzer</label>
												<input type="text" class="form-control" size="6" name="newUName" placeholder="Benutzer" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newUProfile">Profil</label>
												<select name="newUProfile" class="form-control">
													<option selected disabled value=''>Profil</option>
													<?php 
														$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
														foreach($profNames as $i){
															if(($i != 'SuperAdmin') && ($i != 'Candidato')){
																echo "<option value=" . $i . ">" . $i . "</option>";}
														}
													?>
												</select>
											</div>
											<div class="form-group">
												<label class="sr-only" for="newULanguage">Sprache</label>
												<select name="newULanguage" class="form-control">
													<option selected disabled value=''>Sprache</option>
													<?php 
														$userLanguage = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
														$siteLanguages = getDBcompletecolumnID($userLanguage, 'siteLanguages', 'id');
														$languageKeys = getDBcompletecolumnID('key', 'siteLanguages', 'id');
														$i = 0;
														for ($i=0; $i < count($siteLanguages); $i++) { 
															echo "<option value=" . $languageKeys[$i] . ">" . $siteLanguages[$i] . "</option>";
														}
													?>
												</select>
											</div>
											<button type="submit" class="btn btn-primary" name="newUsubmit" value="Añadir">Hinzufügen</button>
											<button type="submit" class="btn btn-primary pull-right" name="newUsubmitC" value="AñadirC">Erstellen Kandidat</button>
										</form>
									</div>
								</div>
							</div> <!-- Panel de Usuarios existentes -->	
						
						<?php 
						}
						else{
							//This code prevents app to enter in infinite-loop when other non-granted user could enter to this site
							?>
							<script type="text/javascript">
								window.location.href='../home.php';
							</script>
							<?php
						}
						?>
						
					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
			</div> <!-- row -->
		</div> <!-- class="container bs-docs-container" -->



		<?php 
			/***************************************************************************************************************************
			 * *********************************************************************************************************************** *
			 * *******************************  Functional code for Edit User Selected on Click Action  ****************************** *
			 * *********************************************************************************************************************** *
			 ***************************************************************************************************************************/
			
			$editedUserRow = getDBrow('users', 'id', $_GET['codvalue']);

		?>

		<!-- Modal HTML -->
		<div id="editUserModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content panel-info">
					<div class="modal-header panel-heading">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Benutzer: <?php echo $editedUserRow['login'] ?></h4>
					</div>
					<form id="editedUser" class="form-horizontal" role="form" name="editedUser" autocomplete="off" method="post" action="admCurUsers.php">
						<div class="modal-body">
							<?php if($_SESSION['logprofile'] == 'SuperAdmin'){ ?>
							<div class="form-group">
								<label id="editedUserLabel" class="control-label col-sm-2" for="newUProfile">Referenz: </label> 
								<div class="col-sm-10">
									<input class="form-control" type='text' name='newUProfile' value="<?php echo $editedUserRow['id'] ?>" autocomplete="off" disabled />
									<input type='hidden' name='eUcodUser' value="<?php echo $editedUserRow['id'] ?>">
								</div>
							</div>
							<?php } ?>
							
							<div class="form-group">
								<label id="editedUserLabel" class="control-label col-sm-2" for="eUlogin">Login: </label>
								<div class="col-sm-10">
									<input class="form-control" type='text' name='eUlogin' value="<?php echo $editedUserRow['login'] ?>" autocomplete="off" disabled>
								</div>
							</div>

							<div class="form-group">
								<label id="editedUserLabel" class="control-label col-sm-2" for="eUpasswd">Passwort: </label>
								<div class="col-sm-10">
									<input class="form-control" type='password' name='eUpasswd' value="<?php echo $editedUserRow['pass'] ?>"  disabled />
								</div>
							</div>

							<?php 
								if($_SESSION['logprofile'] == 'SuperAdmin'){
									echo "<div class='form-group'>";
									echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Profil: </label>";
									echo "<div class='col-sm-10'>";
									if($editedUserRow['profile'] == 'Candidato'){
										echo "<input class='form-control' type='text' name='eUprofile' value='Candidato' disabled />";
									}
									else{
										echo "<select class='form-control' name='eUprofile'>";
										$profNamesColumn = getDBNoMatchColValueID('name', 'profiles', 'name', 'Candidato', 'id');
										foreach($profNamesColumn as $i){
											if($i == $editedUserRow['profile']){
												echo "<option selected value=" . $i . ">" . $i . "</option>";
											}
											else{
												echo "<option value=" . $i . ">" . $i . "</option>";
											}
										}
										echo "</select>";
									}
									echo "</div>";
									echo "</div>";
								}
								elseif($_SESSION['logprofile'] == 'Administrador'){
									echo "<div class='form-group'>";
									echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Profil: </label>";
									echo "<div class='col-sm-10'>";
									if($editedUserRow['profile'] == 'Candidato'){
										echo "<input class='form-control' type='text' name='eUprofile' value='Candidato' disabled />";
									}
									else{
										echo "<select class='form-control' name='eUprofile'>";
										$profNamesColumn = getDBcompletecolumnID('name', 'profiles', 'id');
										foreach($profNamesColumn as $i){
											if(($i != 'SuperAdmin') && ($i != 'Candidato')){
												if($i == $editedUserRow['profile']){
													echo "<option selected value=" . $i . ">" . $i . "</option>";
												}
												else{
													echo "<option value=" . $i . ">" . $i . "</option>";
												}
											}
										}
										echo "</select>";
									}
									echo "</div>";
									echo "</div>";
								}
								else{
									echo "<div class='form-group'>";
									echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Profil: </label>";	
									echo "<div class='col-sm-10'>";
									echo "<input class='form-control' type='text' name='eUprofile' value='" . $editedUserRow['profile'] . "' disabled />";
									echo "</div>";
									echo "</div>";
								}

								//If user has profile "Candidato" will show his/her NIE
								if($editedUserRow['profile'] == "Candidato"){
									echo "<div class='form-group'>";
									echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUuser'>NIE: </label>";
									echo "<div class='col-sm-10'>";
									echo "<input class='form-control' type='text' name='eUuser' value='" . getDBsinglefield('nie', 'cvitaes', 'userLogin', $editedUserRow['login']) . "' disabled /><br/>";
									echo "</div>";
									echo "</div>";
								}

								if($_SESSION['logprofile'] == 'SuperAdmin'){
									echo "<div class='form-group'>";
									echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUemployee'>Angestellter: </label>";
									echo "<div class='col-sm-10'>";
									echo "<div class='radio-inline'>";
									if($editedUserRow['employee'] == 0){
										echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='0' checked>Nicht</label>";
										echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='1'>Ja</label>";
									}
									else{
										echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='0'>Nicht</label>";
										echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='1' checked>Ja</label>";
									}
									echo "</div>";
									echo "</div>";
									echo "</div>";
								}

								$isDisabled = '';
								if ($_SESSION['logprofile'] == 'Administrador') $isDisabled = 'disabled';

								echo "<div class='form-group'>";
								echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUactive'>Gültig: </label>";
								echo "<div class='col-sm-10'>";
								echo "<div class='radio-inline'>";
								if($editedUserRow['active'] == 0){
									echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='0'" . $isDisabled . " checked>Nicht</label>";
									echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='1'" . $isDisabled . ">Ja</label>";
								}
								else{
									echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='0'" . $isDisabled . ">Nicht</label>";
									echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='1'" . $isDisabled . " checked>Ja</label>";
								}
								echo "</div>";
								echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
								echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUlanguage'>Sprache: </label>";
								echo "<div class='col-sm-10'>";
								echo "<select class='form-control' name='eUlanguage'>";													
								$languagesColumn = getDBcompletecolumnID('key', 'siteLanguages', 'id');
								foreach($languagesColumn as $i){
									if($i == $editedUserRow['language']){
										echo "<option selected value=" . $i . ">" . getDBsinglefield($userRow['language'], 'siteLanguages', 'key', $i) . "</option>";
									}
									else{
										echo "<option value=" . $i . ">" . getDBsinglefield($userRow['language'], 'siteLanguages', 'key', $i) . "</option>";
									}
								}
								echo "</select>";
								echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
								echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUcreated'>Gegündet: </label>";
								echo "<div class='col-sm-10'>";
								echo "<input class='form-control' type='text' name='eUcreated' value='" . $editedUserRow['created'] . "' disabled />";
								echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
								echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUconnection'>Letzter Anschluss: </label>";
								echo "<div class='col-sm-10'>";
								echo "<input class='form-control' type='text' name='eUconnection' value='" . $editedUserRow['lastConnection'] . "' disabled />";
								echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
								echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUexpiration'>Password gültig bis: </label>";
								echo "<div class='col-sm-10'>";
								echo "<input class='form-control' type='text' name='eUexpiration' value='" . $editedUserRow['passExpiration'] . "' disabled />";
								echo "</div>";
								echo "</div>";	
							?>
						</div>
						
						<div class="modal-footer">
							<?php 
							//If a non 'SuperAdmin' user edits any 'test' user it won't be able to reset its password
							if($_SESSION['logprofile'] != 'SuperAdmin'){
								if(strpos($editedUserRow['login'], 'test') === false){
									echo "<td><a href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick='return confirmPwdResetES();'>Passwort zurücksetzen</a></td>";
								}
							}
							else{
								echo "<td><a href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick='return confirmPwdResetES();'>Passwort zurücksetzen</a></td>";
							}
							?>
							<input type="hidden" value="<?php echo $editedUserRow['id']; ?>" name="hiddenCurUser">
							<button type="button" class="btn btn-default" data-dismiss="modal">Stornieren</button>
							<button type="submit" class="btn btn-primary" name="eUsersend">Speichern <span class="glyphicon glyphicon-floppy-save"></button>
						</div>
					</form>
				</div>
			</div>
		</div>		


		<?php 
		/***************************************************************************************************************************
		 * *********************************************************************************************************************** *
		 * *******************************  Functional code for Edit User Selected on Click Action  ****************************** *
		 * *********************************************************************************************************************** *
		 ***************************************************************************************************************************/
	
	
		if(isset($_POST['hiddenCurUser'])){
	
			//QUE EL LOGIN NO ESTE REPETIDO, Y QUE ESTE NORMALIZADO
			$editedUserRow = getDBrow('users', 'id', $_POST['hiddenCurUser']);
			
			/***************  Block of code that validates content sent from the form. It is only acceded after clicking on 'eUsersend' SUBMIT  ***************/			
			
			/*************************************************************************************************/
			//1st case: eUlogin(0), eUprofile(0), eUlanguage(1)
			if(((!isset($_POST['eUlogin'])) || ((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] == $editedUserRow['login']))) && 
			((!isset($_POST['eUprofile'])) || ((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] == $editedUserRow['profile']))) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if((!executeDBquery("UPDATE `users` SET `language` = '".$_POST['eUlanguage']."' WHERE `id` = '".$_POST['hiddenCurUser']."'"))){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER001');
						window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
					</script>
					<?php 
				}
			}
			/*************************************************************************************************/
			//2nd case: eUlogin(0), eUprofile(1), eUlanguage(0)
			if(((!isset($_POST['eUlogin'])) || ((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] == $editedUserRow['login']))) && 
			((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] != $editedUserRow['profile'])) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!executeDBquery("UPDATE `users` SET `profile`='".$_POST['eUprofile']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER010');
						window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
					</script>
					<?php 
				}
			}
			
			/*************************************************************************************************/
			//3rd case: eUlogin(0), eUprofile(1), eUlanguage(1)
			if(((!isset($_POST['eUlogin'])) || ((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] == $editedUserRow['login']))) && 
			((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] != $editedUserRow['profile'])) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!executeDBquery("UPDATE `users` SET `profile`='".$_POST['eUprofile']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER011');
						window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
					</script>
					<?php 
				}
			}
			
			/*************************************************************************************************/
			//4th case: eUlogin(1), eUprofile(0), eUlanguage(0)
			if(((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] != $editedUserRow['login'])) && 
			((!isset($_POST['eUprofile'])) || ((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] == $editedUserRow['profile']))) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href='admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER100');
							window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//5th case: eUlogin(1), eUprofile(0), eUlanguage(1)
			if(((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] != $editedUserRow['login'])) && 
			((!isset($_POST['eUprofile'])) || ((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] == $editedUserRow['profile']))) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href='admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER101');
							window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//6th case: eUlogin(1), eUprofile(1), eUlanguage(0)
			if(((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] != $editedUserRow['login'])) && 
			((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] != $editedUserRow['profile'])) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href='admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER110');
							window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//7th case: eUlogin(1), eUprofile(1), eUlanguage(1)
			if(((isset($_POST['eUlogin'])) && ($_POST['eUlogin'] != $editedUserRow['login'])) && 
			((isset($_POST['eUprofile'])) && ($_POST['eUprofile'] != $editedUserRow['profile'])) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href='admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER111');
							window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			//Save whatever change made in any of the Radio buttons
			if(isset($_POST['eUemployee']) && ($_POST['eUemployee'] != $editedUserRow['employee'])){
				if(!executeDBquery("UPDATE `users` SET `employee`='".$_POST['eUemployee']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO10');
						window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
					</script>
					<?php 
				}
			}
			if(isset($_POST['eUactive']) && ($_POST['eUactive'] != $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `active`='".$_POST['eUactive']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO01');
						window.location.href='admCurUsers.php?codvalue=<?php echo $_POST['hiddenCurUser'];  ?>';
					</script>
					<?php 
				}
			}
	
			//If everything was OK on user's edit...
			
			if (isset($_POST['eUsersend'])) {
				echo "<script type='text/javascript'>";
				echo "	alert('Der Benutzer " . $editedUserRow['login'] . " wurde erfolgreich atualisiert');";
				echo "	window.location.href='admCurUsers.php';";
				echo "</script>";
			}
		
		}
		/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
		
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

	<!-- Own document functions -->
	<!-- Show modal if password has to be changed -->
	<?php 

		if (isset($_GET['codvalue'])) {
			echo "<script type='text/javascript'>";
			echo "	$(document).ready(function(){";
			echo "		$('#editUserModal').modal('show');";
			echo "		$('#editUserModal').on('hidden.bs.modal', function () {";
 			echo "			window.location.href='admCurUsers.php';";
			echo "		});";
			echo "	});  ";
			echo "</script> ";
		}
	?>

</body>
</html>
