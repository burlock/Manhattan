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
						
						/**********************************************   Start of FORM validations   *********************************************/
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
										alert('Der ausgewählte Benutzer existiert bereits.');
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
											alert('Fehler beim Erstellen des neuen Benutzers.');
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
											alert('<?php echo $newUser; ?> Benutzer wurde erfolgreich erstellt. \nIhr Standardpasswort ist: <?php echo $initialPass; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								}
							}
						}
						
						elseif(isset($_POST['newUsubmitC'])){
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
						
						/* MANTENGO CAPADO EL CÓDIGO QUE AÑADE UN Candidato MEDIANTE SU MAIL, A PESAR DE QUE SOLO LO MUESTRE PARA SuperAdmin
						elseif(isset($_POST['newQuickCsubmit'])){
							//Separating name from domain in input email
							if(isset($_POST['newCmail']) && !empty($_POST['newCmail'])){
								$candidateMail = $_POST['newCmail'];
								//Esto hace justo lo que hacen las primeras 4 líneas del 'if' de Nuevo Usuario (eliminar espacios en blanco y quitar acentos)
								$properMail = setStringAsKey($candidateMail);
								$candLogin = strstr($properMail, "@", TRUE);
								if(getDBsinglefield(login, users, login, $candLogin)){
									?>
									<script type="text/javascript">
										alert('El usuario que se intenta crear ya existe.');
										window.location.href='admCurUsers.php';
									</script>
									<?php
								}
								else{
									$initialPass = getRandomPass();
									$expirationDate = addMonthsToDate(getDBsinglefield(value, otherOptions, key, expirationMonths));
									
									if(!executeDBquery("INSERT INTO `users` (`id`, `login`, `pass`, `mail`, `profile`, `active`, `needPass`, `created`, `passExpiration`) VALUES 
									(NULL, '".utf8_decode($candLogin)."', '".$initialPass."', '".utf8_decode($properMail)."', 'Candidato', '1', '1', CURRENT_TIMESTAMP, '".$expirationDate."')")){
									?>
										<script type="text/javascript">
											alert('Error al crear el nuevo usuario.');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										//+1 to profiles' "Candidato" parameter, and creation of user's directory
										if(!addCandidate($newUser, $userRow['language'], $addError)){
											?>
											<script type="text/javascript">
												alert('<?php echo $addError; ?>');
												window.location.href='admCurUsers.php';
											</script>
											<?php
										}
										else{
											//AQUI SERIA DONDE TENDRIA QUE ENVIAR EL CORREO Administrador Y Candidato.
											?>
											<script type="text/javascript">
												alert('Datos de acceso para el Candidato creado:\n Login: <?php echo $candLogin; ?> \n Password: <?php echo $initialPass; ?> \n URL: http://areaprivada.perspectivaalemania.com ');
												window.location.href='admCurUsers.php';
											</script>
											<?php
										}
									}
								}
							}
						}
						*/
												
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
									//if(!executeDBquery("UPDATE `users` SET `pass`='".$initialPass."', `needPass`='1', `passExpiration`='".$expirationDate."' WHERE `id`='".$userRow['id']."'")){
									if(!executeDBquery("UPDATE `users` SET `pass`='".$initialPass."', `active`='1', `needPass`='1', `passExpiration`='".$expirationDate."' WHERE `id`='".$userRow['id']."'")){
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
						}//end of isset($_GET['hiddenGET'])
						
						
						elseif(isset($_POST[hiddenCurUser])){
					
							//QUE EL LOGIN NO ESTE REPETIDO, Y QUE ESTE NORMALIZADO
							$editedUserRow = getDBrow(users, id, $_POST[hiddenCurUser]);
							
							/***************  Block of code that validates content sent from the form. It is only acceeded after clicking on 'eUsersend' SUBMIT (Modal window)  ***************/			
							
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
										alert('Der angewandte Loggin erfüllt nicht die gültigen Anforderungen.');
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
										alert('Der angewandte Loggin erfüllt nicht die gültigen Anforderungen.');
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
										alert('Der angewandte Loggin erfüllt nicht die gültigen Anforderungen.');
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
										alert('Der angewandte Loggin erfüllt nicht die gültigen Anforderungen.');
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
							if(isset($_POST['eUshowing']) && ($_POST['eUshowing'] != $editedUserRow['showing'])){
								if(!executeDBquery("UPDATE `users` SET `showing`='".$_POST['eUshowing']."' WHERE `id`='".$_POST['hiddenCurUser']."'")){
									?>
									<script type="text/javascript">
										alert('Error ADEDUSERADIO11');
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
						/*****  ----------------------------------------   End of FORM validations   ---------------------------------------  *****/
						
						
						/*******************************************   Start of displayed Modal HTML   ********************************************/
						elseif(isset($_GET[codvalue])){
							$editedUserRow = getDBrow(users, id, $_GET[codvalue]);
							?>
							<div id="editUserModal" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content panel-info">
										<div class="modal-header panel-heading">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">Benutzer: <?php echo $editedUserRow[login] ?></h4>
										</div>
										<form id="editedUser" class="form-horizontal" role="form" name="editedUser" autocomplete="off" method="post" action="admCurUsers.php">
											<div class="modal-body">
												<?php if($_SESSION[logprofile] == 'SuperAdmin'){ ?>
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
													<div class="row">
														<div class="col-sm-7">
															<input class="form-control" type='password' name='eUpasswd' value="<?php echo $editedUserRow['pass'] ?>" disabled />
														</div>
														<div class="col-sm-2">
															<?php 
															//If a non 'SuperAdmin' user edits any 'test' user it won't be able to reset its password (ES DECIR, SOLO 'SuperAdmin' PUEDE RESETEAR LA CONTRASEÑA DE USUARIO 'testX')
															if($_SESSION['logprofile'] != 'SuperAdmin'){
																if(strpos($editedUserRow['login'], 'test') === false){
																	echo "<a class='btn btn-warning' href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick=\"return confirmPwdReset('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."');\">zurücksetzen</a>";
																}
															}
															else{
																echo "<a class='btn btn-warning' href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick=\"return confirmPwdReset('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."');\">zurücksetzen</a>";
															}
															?>
														</div>
													</div>
												</div>
												
												<?php if($_SESSION[logprofile] == 'SuperAdmin'){ ?>
												<div class="form-group">
													<label id="editedUserLabel" class="control-label col-sm-2" for="eUprofile">Profil: </label> 
													<div class="col-sm-10">
														<?php if($editedUserRow[profile] == 'Candidato'){ ?>
															<input class="form-control" type="text" name="eUprofile" value="Candidato" disabled />
														<?php }
														else{
															echo "<select class='form-control' name='eUprofile'>";
															$profNamesColumn = getDBNoMatchColValueID(name, profiles, name, Candidato, id);
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
														?>
													</div>
												</div>
												<?php }
												elseif($_SESSION[logprofile] == 'Administrador'){ ?>
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUprofile">Profil: </label>
														<div class="col-sm-10">
															<?php if($editedUserRow[profile] == 'Candidato'){ ?>
																<input class="form-control" type="text" name="eUprofile" value="Candidato" disabled />
															<?php }
															else{
																echo "<select class='form-control' name='eUprofile'>";
																$profNamesColumn = getDBcompletecolumnID(name, profiles, id);
																foreach($profNamesColumn as $i){
																	if(($i != 'SuperAdmin') && ($i != 'Candidato')){
																		if($i == $editedUserRow[profile]){
																			echo "<option selected value=" . $i . ">" . $i . "</option>";
																		}
																		else{
																			echo "<option value=" . $i . ">" . $i . "</option>";
																		}
																	}
																}
																echo "</select>";
															}
															?>
														</div>
													</div>
												<?php }
												else{ ?>
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUprofile">Profil: </label>
														<div class="col-sm-10">
															<input class="form-control" type="text" name="eUprofile" value=<?php echo $editedUserRow[profile]; ?> disabled />
														</div>
													</div>
												<?php
												}
												
												//If user has a "Candidato" profile it will be shown his/her NIE
												if($editedUserRow[profile] == 'Candidato'){ ?>
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUuser">NIE: </label>
														<div class="col-sm-10">
															<input class="form-control" type="text" name="eUuser" value=<?php echo getDBsinglefield(nie, cvitaes, userLogin, $editedUserRow[login]); ?> disabled /><br/>
														</div>
													</div>
												<?php }
												if($_SESSION[logprofile] == 'SuperAdmin'){ ?>
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUemployee">Angestellter: </label>
														<div class="col-sm-10">
															<div class="radio-inline">
															<?php if($editedUserRow[employee] == 0){ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUemployee" value="0" checked>Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUemployee" value="1">Ja</label>
															<?php }
															else{ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUemployee" value="0">Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUemployee" value="1" checked>Ja</label>
															<?php } ?>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUshowing">Zeige: </label>
														<div class="col-sm-10">
															<div class="radio-inline">
															<?php if($editedUserRow[showing] == 0){ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUshowing" value="0" checked>CV</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUshowing" value="1">Job</label>
															<?php }
															else{ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUshowing" value="0">CV</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUshowing" value="1" checked>Job</label>
															<?php } ?>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUactive">Gültig: </label>
														<div class="col-sm-10">
															<div class="radio-inline">
															<?php if($editedUserRow[active] == 0){ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="0" checked>Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="1">Ja</label>
															<?php }
															else{ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="0">Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="1" checked>Ja</label>
															<?php } ?>
															</div>
														</div>
													</div>
												<?php }
												elseif($_SESSION[logprofile] == 'Administrador'){ ?>
													<div class="form-group">
														<label id="editedUserLabel" class="control-label col-sm-2" for="eUactive">Gültig: </label>
														<div class="col-sm-10">
															<div class="radio-inline">
															<?php if($editedUserRow[active] == 0){ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="0" checked disabled>Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="1" disabled>Ja</label>
															<?php }
															else{ ?>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="0" disabled>Nicht</label>
																<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="eUactive" value="1" checked disabled>Ja</label>
															<?php } ?>
															</div>
														</div>
													</div>
												<?php } ?>
												<div class="form-group">
													<label id="editedUserLabel" class="control-label col-sm-2" for="eUlanguage">Sprache: </label>
													<div class="col-sm-10">
														<select class="form-control" name="eUlanguage">													
															<?php 
															$languagesColumn = getDBcompletecolumnID(key, siteLanguages, id);
															foreach($languagesColumn as $i){
																if($i == $editedUserRow[language]){
																	echo "<option selected value=" . $i . ">" . getDBsinglefield($userRow[language], siteLanguages, key, $i) . "</option>";
																}
																else{
																	echo "<option value=" . $i . ">" . getDBsinglefield($userRow[language], siteLanguages, key, $i) . "</option>";
																}
															} ?>
														</select>
													</div>
												</div>
												
												<div class="form-group">
													<label id="editedUserLabel" class="control-label col-sm-2" for="eUcreated">Gegündet: </label>
													<div class="col-sm-10">
														<input class="form-control" type="text" name="eUcreated" value=<?php echo $editedUserRow[created]; ?> disabled />
													</div>
												</div>
												
												<div class="form-group">
													<label id="editedUserLabel" class="control-label col-sm-2" for="eUconnection">Letzte Verbindung: </label>
													<div class="col-sm-10">
														<input class="form-control" type="text" name="eUconnection" value=<?php echo $editedUserRow[lastConnection]; ?> disabled />
													</div>
												</div>
												
												<div class="form-group">
													<label id="editedUserLabel" class="control-label col-sm-2" for="eUexpiration">Password gültig bis: </label>
													<div class="col-sm-10">
														<input class="form-control" type="text" name='eUexpiration' value=<?php echo $editedUserRow[passExpiration]; ?> disabled />
													</div>
												</div>
											</div>
											
											<div class="modal-footer">
												<?php 
												/*
												//If a non 'SuperAdmin' user edits any 'test' user it won't be able to reset its password
												if($_SESSION['logprofile'] != 'SuperAdmin'){
													if(strpos($editedUserRow['login'], 'test') === false){
														echo "<td><a href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick=\"return confirmPwdReset('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."');\">Passwort zurücksetzen</a></td>";
													}
												}
												else{
													echo "<td><a href='admCurUsers.php?codvalue=" . $editedUserRow['id'] . "&hiddenGET=hResPwd' onclick=\"return confirmPwdReset('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."');\">Passwort zurücksetzen</a></td>";
												}
												*/
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
						}//isset($_GET[codvalue]) del Modal
						/*****  --------------------------------------   End of displayed Modal HTML   --------------------------------------  *****/
						
						
						/*************************************    Start of WebPage code as initially showed    *************************************/
						else{
							if($_SESSION['logprofile'] == 'SuperAdmin'){
								?>
								<div class="panel panel-default"> <!-- Panel de Usuarios Existentes -->
									<div class="panel-heading">
										<h3 class="panel-title">Existierende Benutzer</h3>
									</div>
									<div class="panel-body">
										
										<div class="container-fluid center-block">
											<form class="form-inline" role="form" name="newCand" action="admCurUsers.php" method="post">
												<div class="form-group">
													<b>schnelle Erstellung von Kandidaten: </b><input type="text" class="form-control" name="newCmail" size="60%" maxlength="50" placeholder="Candidate mail" />
												</div>
												<button type="submit" class="btn btn-primary" name="newQuickCsubmit" value="AñadirC">Erstellen Kandidat</button>
											</form>
										</div>
										
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
														<th>Letzte Verbindung</th>
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
															echo "<td><a href='admCurUsers.php?codvalue=" . $showedUserRow['id'] . "&hiddenGET=hDelUser' onclick=\"return confirmUserDeletion('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."')\">Löschen</a></td>";
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
													<input type="text" class="form-control" name="newUName" size="25" maxlength="20" placeholder="Benutzer" />
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
												<!-- LO SUYO SERÍA USAR LA CREACIÓN RÁPIDA -->
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
										
										<!-- DE MOMENTO PARA EL Administrador NO LO MUESTRO. SOLO PARA EL SuperAdmin Y LO MANTENDRÉ CAPADO
										<div class="container-fluid center-block">
											<form class="form-inline" role="form" name="newCand" action="admCurUsers.php" method="post">
												<div class="form-group">
													<b>schnelle Erstellung von Kandidaten: </b><input type="text" class="form-control" name="newCmail" size="60%" maxlength="50" placeholder="Candidate mail" />
												</div>
												<button type="submit" class="btn btn-primary" name="newQuickCsubmit" value="AñadirC">Erstellen Kandidat</button>
											</form>
										</div>
										-->
										
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
														<th>Letzte Verbindung</th>
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
													<input type="text" class="form-control" name="newUName" size="25" maxlength="20" placeholder="Benutzer" />
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
												<!-- LO SUYO SERÍA USAR LA CREACIÓN RÁPIDA -->
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
						}
						/*****  -------------------------------    End of WebPage code as initially showed    -------------------------------  *****/
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
