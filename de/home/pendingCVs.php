<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Lebensläufe Ohrringe</title>

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
							//Extracts the number of pending CVs, which is showed as a number inside a globe in every page
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

		
				<!--  ******-----------------------------------   Start of Form validations   ------------------------------------*****  -->
				<?php
				/*****  Code executed when trying to validate a CV  *****/
				if (isset($_POST['eCurCVsend'])) {
					
					/*****  Language and Language level's treatment previous to be saved in DDBB  *****/
					//Unmounting "Lang:LangLv" structure to insert it into DB (explode breaks a string into an array)
					$wholeLangInfo = explode('|',$_POST['eCCVlanguagesMerged']);
					
					$finalLang = "";
					$finalLangLv = "";
					foreach($wholeLangInfo as $key => $value) {
						//Separating each 'Language' and its 'Language Level' as an array
						$array = explode(':',$value);					
						$finalLang = $finalLang . $array[0] . '|';
						$finalLangLv = $finalLangLv . $array[1] . '|';
					}
					
					$finalLang = substr($finalLang, 0, -1);
					$finalLangLv = substr($finalLangLv, 0, -1);
					
					$finalLang = htmlentities($finalLang, ENT_QUOTES, 'UTF-8');
					$finalLangLv = htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8');
					
					$languageArray = explode("|", $finalLang);
					/*  ----------  End of Language and Language level's Treatment  ----------  */
					
					/*****  Experience's Treatment previous to be saved in DDBB  *****/
					//Mounting Experience information
					$string_experCompany = "";
					$string_experStart = "";
					$string_experEnd = "";
					$string_experPos = "";
					$stringExpCity = "";
					$stringExpCountry = "";
					$string_experDesc = "";
					
					for ($i=0; $i < $_POST['eCCV_counterExperience']; $i++) { 
						$string_experCompany = $string_experCompany . $_POST["eCCVexperCompany$i"] . '|';
						$string_experStart = $string_experStart . $_POST["eCCVexperStart$i"] . '|';
						$string_experEnd = $string_experEnd . $_POST["eCCVexperEnd$i"] . '|';
						$string_experPos = $string_experPos . $_POST["eCCVexperPos$i"] . '|';
						$stringExpCity = $stringExpCity . $_POST["eCCVexperCity$i"] . '|';
						$stringExpCountry = $stringExpCountry . $_POST["eCCVexperCountry$i"] . '|';
						$string_experDesc = $string_experDesc . $_POST["eCCVexperDesc$i"] . '|';
					}
					
					//Cleaning last '|' character from each string
					$string_experCompany = substr($string_experCompany, 0, -1);
					$string_experStart = substr($string_experStart, 0, -1);
					$string_experEnd = substr($string_experEnd, 0, -1);
					$string_experPos = substr($string_experPos, 0, -1);
					$stringExpCity = substr($stringExpCity, 0, -1);
					$stringExpCountry = substr($stringExpCountry, 0, -1);
					$string_experDesc = substr($string_experDesc, 0, -1);
					
					/*  ----------  End of Experience's Treatment  ----------  */
					
					/*****  Education's Treatment previous to be saved in DDBB  *****/
					//Mounting Education information
					$stringEducTittle = "";
					$stringEducCenter = "";
					$stringEducStart = "";
					$stringEducEnd = "";
					
					for($i=0; $i<$_POST['eCCVcontEduc']; $i++){
						$stringEducTittle = $stringEducTittle . $_POST["eCCVeducTittle$i"] . '|';
						$stringEducCenter = $stringEducCenter . $_POST["eCCVeducCenter$i"] . '|';
						$stringEducStart = $stringEducStart . $_POST["eCCVeducStart$i"] . '|';
						$stringEducEnd = $stringEducEnd . $_POST["eCCVeducEnd$i"] . '|';
					}
					
					//Cleaning last '|' character from each string
					$stringEducTittle = htmlentities(substr($stringEducTittle, 0, -1), ENT_QUOTES, 'UTF-8');
					$stringEducCenter = htmlentities(substr($stringEducCenter, 0, -1), ENT_QUOTES, 'UTF-8');
					$stringEducStart = htmlentities(substr($stringEducStart, 0, -1), ENT_QUOTES, 'UTF-8');
					$stringEducEnd = htmlentities(substr($stringEducEnd, 0, -1), ENT_QUOTES, 'UTF-8');
					/*  ----------  End of Education's Treatment  ----------  */
					
					if(!checkFullName($_POST['eCCVname'], $_POST['eCCVsurname'], $userRow['language'], $outName, $outSurname, $checkError)){
						?>
						<script type="text/javascript">
							alert('<?php echo $checkError; ?>');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif(!checkBirthdate($_POST['eCCVbirthdate'], $userRow['language'], $outDate, $checkError)){
						?>
						<script type="text/javascript">
							alert('<?php echo $checkError; ?>');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif((htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8') == '') || !isImplodedArrayInDBExcept(htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8'), 'countries', 'german', '|', 'Spanien')){
						?>
						<script type="text/javascript">
							alert('Fehler: Mindestens 1 der Nationalitäten ist nicht korrekt geschrieben.');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif(!checkMobile(htmlentities($_POST['eCCVmobile'], ENT_QUOTES, 'UTF-8'))){
						?>
						<script type="text/javascript">
							alert('Fehler: Die mobile ist nicht richtig geschrieben.');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif(!filter_var(htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL)){
						?>
						<script type="text/javascript">
							alert('Fehler: Die E-mail ist nicht richtig geschrieben.');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					//Checks if every language is OK or not
					elseif(htmlentities($finalLang, ENT_QUOTES, 'UTF-8') == '' || htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8') == '' || !(isAllArrayInColumn($languageArray, 'languages', 'key'))){
						?>
						<script type="text/javascript">
							alert('Fehler: Mindestens einer der Sprachen nicht richtig geschrieben.');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif(!checkEducation($stringEducTittle, $stringEducCenter, $stringEducStart, $stringEducEnd, $userRow['language'], $checkError)){
						?>
						<script type="text/javascript">
							alert('<?php echo $checkError; ?>');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					elseif(!strlen($_POST['eCCVcandidateStatus']) > 0){
						?>
						<script type="text/javascript">
							alert('Keine Sonderstellung für den Kandidaten.');
							window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					else{
						$inDBOtherPhone = trim(htmlentities($_POST['eCCVphone'], ENT_QUOTES, 'UTF-8'));
						if(!checkPhone($inDBOtherPhone)){
							$inDBOtherPhone = '';
						}
						$updateCVQuery = "	UPDATE `cvitaes` 
											SET `nie` = '".$_POST['eCCVnie']."',
												`cvStatus` = 'checked',
												`name` = '".$outName."',
												`surname` = '".$outSurname."',
												`birthdate` = '".$outDate."',
												`nationalities` = '".htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8')."',
												`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
												`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
												`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
												`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
												`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
												`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
												`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
												`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
												`phone` = '".$inDBOtherPhone."',
												`postalCode` = '".htmlentities($_POST['eCCVpostal'], ENT_QUOTES, 'UTF-8')."',
												`country` = '".htmlentities($_POST['eCCVcountry'], ENT_QUOTES, 'UTF-8')."',
												`province` = '".htmlentities($_POST['eCCVprovince'], ENT_QUOTES, 'UTF-8')."',
												`city` = '".htmlentities($_POST['eCCVcity'], ENT_QUOTES, 'UTF-8')."',
												`mobile` = '".htmlentities($_POST['eCCVmobile'], ENT_QUOTES, 'UTF-8')."',
												`mail` = '".htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8')."',
												`drivingType` = '".htmlentities($_POST['eCCVdrivingType'], ENT_QUOTES, 'UTF-8')."',
												`drivingDate` = '".htmlentities($_POST['eCCVdrivingDate'], ENT_QUOTES, 'UTF-8')."',
												`marital` = '".htmlentities($_POST['eCCVmarital'], ENT_QUOTES, 'UTF-8')."',
												`sons` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
												`language` = '".htmlentities($finalLang, ENT_QUOTES, 'UTF-8')."',
												`langLevel` = '".htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8')."',
												`educTittle` = '".$stringEducTittle."',
												`educCenter` = '".$stringEducCenter."',
												`educStart` = '".$stringEducStart."',
												`educEnd` = '".$stringEducEnd."',
												`career` = '".htmlentities($_POST['eCCVcareer'], ENT_QUOTES, 'UTF-8')."',
												`experCompany` = '".htmlentities($string_experCompany, ENT_QUOTES, 'UTF-8')."',
												`experStart` = '".htmlentities($string_experStart, ENT_QUOTES, 'UTF-8')."',
												`experEnd` = '".htmlentities($string_experEnd, ENT_QUOTES, 'UTF-8')."',
												`experPos` = '".htmlentities($string_experPos, ENT_QUOTES, 'UTF-8')."',
												`experCity` = '".htmlentities($stringExpCity, ENT_QUOTES, 'UTF-8')."',
												`experCountry` = '".htmlentities($stringExpCountry, ENT_QUOTES, 'UTF-8')."',
												`experDesc` = '".htmlentities($string_experDesc, ENT_QUOTES, 'UTF-8')."',
												`otherDetails` = '".htmlentities($_POST['eCCVotherDetails'], ENT_QUOTES, 'UTF-8')."',
												`skill1` = '".htmlentities($_POST['eCCVskill1'], ENT_QUOTES, 'UTF-8')."',
												`skill2` = '".htmlentities($_POST['eCCVskill2'], ENT_QUOTES, 'UTF-8')."',
												`skill3` = '".htmlentities($_POST['eCCVskill3'], ENT_QUOTES, 'UTF-8')."',
												`skill4` = '".htmlentities($_POST['eCCVskill4'], ENT_QUOTES, 'UTF-8')."',
												`skill5` = '".htmlentities($_POST['eCCVskill5'], ENT_QUOTES, 'UTF-8')."',
												`skill6` = '".htmlentities($_POST['eCCVskill6'], ENT_QUOTES, 'UTF-8')."',
												`skill7` = '".htmlentities($_POST['eCCVskill7'], ENT_QUOTES, 'UTF-8')."',
												`skill8` = '".htmlentities($_POST['eCCVskill8'], ENT_QUOTES, 'UTF-8')."',
												`skill9` = '".htmlentities($_POST['eCCVskill9'], ENT_QUOTES, 'UTF-8')."',
												`skill10` = '".htmlentities($_POST['eCCVskill10'], ENT_QUOTES, 'UTF-8')."',
												`cvDate` = '".htmlentities($_POST['eCCVcvDate'], ENT_QUOTES, 'UTF-8')."',
												`salary` = '".htmlentities($_POST['eCCVsalary'], ENT_QUOTES, 'UTF-8')."',
												`comments` = '".htmlentities($_POST['eCCVcomments'], ENT_QUOTES, 'UTF-8')."',
												`candidateStatus` = '".$_POST['eCCVcandidateStatus']."'
											WHERE `nie` = '".htmlentities($_POST['eCCVnie'], ENT_QUOTES, 'UTF-8')."';";
		
						if((!executeDBquery($updateCVQuery))){
							?>
							<script type="text/javascript">
								alert('Fehler beim prüfen CV.');
								window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
							</script>
							<?php 
						}
						else {
							?>
							<script type="text/javascript">
								alert('CV erfolgreich überarbeitet.');
								window.location.href='pendingCVs.php';
							</script>
							<?php
						}
					}
				}
				elseif(isset($_GET['hiddenGET'])){
					switch($_GET['hiddenGET']){
						case 'hDelPendingCV':
							$pendingCVRow = getDBrow('cvitaes', 'id', $_GET['codvalue']);
							if(!deleteDBrow('users', 'login', $pendingCVRow['userLogin'])){
								unset ($_GET['codvalue']);
								unset ($pendingCVRow);
								?>
								<script type="text/javascript">
									alert('Fehler beim Löschen der Benutzer aus dem aufgegebenen CVs.');
									window.location.href='pendingCVs.php';
								</script>
								<?php 
							}
							elseif(!deleteDBrow('cvitaes', 'id', $_GET['codvalue'])){
								unset ($_GET['codvalue']);
								unset ($pendingCVRow);
								?>
								<script type="text/javascript">
									alert('Fehler beim löschen der ausstehenden CV.');
									window.location.href='pendingCVs.php';
								</script>
								<?php 
							}
							else{
								$numCandidateUsers = getDBsinglefield('numUsers', 'profiles', 'name', 'Candidato');
								$numCandidateUsers--;
								executeDBquery("UPDATE `profiles` SET `numUsers`='".$numCandidateUsers."' WHERE `name`='Candidato'");
								$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$pendingCVRow['userLogin']."/";
								$files  = scandir($userDir);
								foreach ($files as $value){
									unlink($userDir.$value);
								}
								rmdir($userDir);
							}
						break;
					}
					?>
					<script type="text/javascript">
						window.location.href='pendingCVs.php';
					</script>
					<?php 
				}//end of $_GET['hiddenGET']
				/*****  ----------------------------------------   End of Form validations   ---------------------------------------  *****/
				?>
				<!--  ******------------------------------------   End of Form validations   -------------------------------------*****  -->


				<!--  ****************************************   Start of displayed Modal HTML   ****************************************  -->
				<div id="editCVModal" class="modal fade bs-example-modal-lg">
					<div class="modal-dialog modal-lg">
						<div class="modal-content panel-info">
							<div class="modal-header panel-heading">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Überprüfen von CV <?php echo $_GET['codvalue'] ?></h4>
							</div>

							<?php
							$editedCVRow = getDBrow('cvitaes', 'nie', $_GET['codvalue']);
							$userFilesDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".($editedCVRow['userLogin'])."/";
							
							if(!ifCreateDir($userFilesDir, 0777)){
								?>
								<script type="text/javascript">
									alert('Fehler beim Abrufen von Benutzerverzeichnis Informationen. Bitte kontaktieren Sie Administrator.');
									window.location.href='../home.php';
								</script>
								<?php 
							}
							?>

							<form id="editedCV" class="form-horizontal" role="form" name="editedCV" autocomplete="off" method="post" action="pendingCVs.php">
								<div class="modal-body">

									<div class="form-group"> <!-- Nombre -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVname">Namen: * </label> 
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVname' value="<?php echo ($editedCVRow['name']) ?>" autocomplete="off" />
										</div>
									</div>

									<div class="form-group"> <!-- Apellidos -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsurname">Nachnamen: * </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVsurname' value="<?php echo ($editedCVRow['surname']) ?>" autocomplete="off"/>
										</div>
									</div>
									
									<div class="form-group"> <!-- Fecha de Nacimiento & NIE -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVbirthdate">Geburtsdatum: * </label>
										<div class="col-sm-4">
											<input class="form-control" type='date' name='eCCVbirthdate' id='eCCVbirthdate' value="<?php echo ($editedCVRow['birthdate']) ?>" onChange="jsIsAdult(this.id, 18)" required>
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnie">DNI/NIE: * </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVnie' value="<?php echo ($editedCVRow['nie']) ?>" onkeyup='this.value=this.value.toUpperCase();' readonly/>
										</div>
									</div>
									
									<div class="form-group tooltip-demo">  <!-- Nacionalidad -->
										<?php
										//$nationalityQueryResult = getDBDistCompleteColID("english", "countries", "english");
										$nationalityQueryResult = getDBDistCompleteColID("german", "countries", "german");
										$nationalities_string = implode(', ', $nationalityQueryResult);
										?>
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnationalities"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title='Spanien, <?php echo $nationalities_string; ?>'></span> Nationalität: * </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVnationalities' value="<?php echo ($editedCVRow['nationalities']) ?>" data-role='tagsinput' />
										</div>
									</div>

									<div class="form-group"> <!-- Sexo -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsex">Geschlecht: * </label>
										<div class="col-sm-10">
											<div class='radio-inline'>
												<?php
													if(($editedCVRow['sex']) == 0){
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0' checked>Mann</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1'>Weib</label>";
													}
													else {
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0'>Mann</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1' checked>Weib</label>";
													}
												?>
											</div>
										</div>
									</div>
									
									<div class="form-group">  <!-- Tipo Dirección & Nombre Dirección -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrtype">Adresstyp: </label>
										<div class="col-sm-3">
											<!-- <input class="form-control" type='text' name='eCCVaddrtype' value="< ?php echo ($editedCVRow['addrType']) ?>"> -->
											<select class="form-control" name="eCCVaddrtype">
												<?php
												$addressTypes = getDBcompletecolumnID('key', 'addressTypes', 'id');
												echo "<option value=''>-- wählen --</option>";
												if(strlen($editedCVRow['addrType']) > 0){
													foreach($addressTypes as $i){
														if($i == $editedCVRow['addrType']){
															echo '<option value='.$i.' selected>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
														}
														else{
															echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
														}
													}
												}
												else{
													foreach($addressTypes as $i){
														echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
													}
												}
												?>
											</select>
										</div>
									
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrName">Adresse namen: </label>
										<div class="col-sm-5">
											<input class="form-control" type='text' name='eCCVaddrName' value="<?php echo ($editedCVRow['addrName']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Número, Portal y Escalera -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrNum">Nummer: </label>
										<div class="col-sm-2">
											<input class="form-control" type='text' name='eCCVaddrNum' maxlength='4' value="<?php echo ($editedCVRow['addrNum']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
										
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrPortal">Halle: </label>										
										<div class="col-sm-2">
											<input class="form-control" type='text' name='eCCVaddrPortal' maxlength='4' value="<?php echo ($editedCVRow['portal']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrStair">Aufgang: </label>										
										<div class="col-sm-2">
											<input class="form-control" type='text' name='eCCVaddrStair' maxlength='4' value="<?php echo ($editedCVRow['stair']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>
									
									<div class="form-group" >  <!-- Piso y Puerta -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrFloor">Stockwerk: </label>										
										<div class="col-sm-3">
											<input class="form-control" type='text' name='eCCVaddrFloor' maxlength='4' value="<?php echo ($editedCVRow['addrFloor']) ?>">
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrDoor">Tor: </label>										
										<div class="col-sm-3">
											<input class="form-control" type='text' name='eCCVaddrDoor' maxlength='4' value="<?php echo ($editedCVRow['addrDoor']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>

									<div class="form-group" >  <!-- Código Postal y Localidad -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVpostal">Postleitzahl: </label>										
										<div class="col-sm-3">
											<input class="form-control" type='text' name='eCCVpostal' maxlength='5' value="<?php echo $editedCVRow['postalCode'] ?>" onkeypress="return checkOnlyNumbers(event)">
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcity">Stadt: </label>										
										<div class="col-sm-5">
											<input class="form-control" type='text' name='eCCVcity' value="<?php echo ($editedCVRow['city']) ?>">										
										</div>
									</div>

									<div class="form-group" >  <!-- Provincia y País -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVprovince">Kreis: </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVprovince' value="<?php echo ($editedCVRow['province']) ?>">										
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcountry">Staat: </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVcountry' value="<?php echo ($editedCVRow['country']) ?>">										
										</div>
									</div>
									
									<div class="form-group" >  <!-- Teléfono Móvil y Teléfono adicional -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmobile">Handy: * </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVmobile' maxlength='9' value="<?php echo $editedCVRow['mobile'] ?>" onkeypress="return checkOnlyNumbers(event)">										
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVphone">Andere telefon: </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVphone' maxlength='18' placeholder='00[COD. PAIS]-NUMERO' value="<?php echo $editedCVRow['phone'] ?>" onkeypress="return checkDashedNumbers(event)">
										</div>
									</div>
									
									<div class="form-group" >  <!-- Correo Electrónico -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmail">E-mail: * </label>										
										<div class="col-sm-10">
											<input class="form-control" type='mail' name='eCCVmail' value="<?php echo ($editedCVRow['mail']) ?>">										
										</div>
									</div>
									
									<div class="form-group" >  <!-- Carnet de Conducir -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVdrivingType">Führerschein: </label>										
										<div class="col-sm-4">
											<!-- <input class="form-control" type='text' name='eCCVdrivingType' value="< ?php echo ($editedCVRow['drivingType']) ?>"> -->
											<select class="form-control" name="eCCVdrivingType">
												<?php
												$drivingTypes = getDBcompletecolumnID('key', 'drivingTypes', 'id');
												echo "<option value=''>-- wählen --</option>";
												if(strlen($editedCVRow['drivingType']) > 0){
													foreach($drivingTypes as $i){
														if($i == $editedCVRow['drivingType']){
															echo '<option value='.$i.' selected>'.$i.'</option>';
														}
														else{
															echo '<option value='.$i.'>'.$i.'</option>';
														}
													}
												}
												else{
													foreach($drivingTypes as $i){
														echo '<option value='.$i.'>'.$i.'</option>';
													}
												}
												?>
											</select>
										</div>
										<div class="col-sm-6">
											<input class='form-control' type='date' name='eCCVdrivingDate' placeholder='aaaa-mm-dd' onChange="jsIsPreviousDate(this.id, '<?php echo getCurrentLanguage($_SERVER['SERVER_NAME']); ?>')" value="<?php echo ($editedCVRow['drivingDate']) ?>">
										</div>
									</div>
									
									<div class="form-group" >  <!-- Estado Civil e Hijos -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmarital">Familienstand: </label>
										<div class="col-sm-4">
											<select class="form-control" name="eCCVmarital" >
												<?php 
												$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
												$maritalStatus = getDBcompletecolumnID($userLang, 'maritalStatus', $userLang);
												echo "<option selected value=''>-- wählen --</option>";
												foreach($maritalStatus as $i){
													$keyMarital = getDBsinglefield('key', 'maritalStatus', $userLang, $i);
													if($keyMarital == $editedCVRow['marital']){
														echo "<option selected value=" . $keyMarital . ">" . $i . "</option>";
													}
													else{
														echo "<option value=" . $keyMarital . ">" . $i . "</option>";
													}
												}
												?>
											</select>
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsons">Kinder: </label>
										<div class="col-sm-4">
											<input class="form-control" type='number' name='eCCVsons' maxlength='2' min='0' value="<?php echo $editedCVRow['sons'] ?>">
										</div>
									</div>
									
									<div class="form-group" >  <!-- Idiomas -->
										<?php
										$languageKeys = getDBcompletecolumnID('key', 'languages', 'id');
										$languageString = implode(', ', $languageKeys);
										
										$mergedLanguages = explode('|',$editedCVRow['language']);
										$mergedLangLevels = explode('|',$editedCVRow['langLevel']);
										$hashedLanguages = array_combine($mergedLanguages,$mergedLangLevels);
										?>
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVlanguagesMerged"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title='<?php echo $languageString; ?>'></span> Sprachen: * </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVlanguagesMerged' value="<?php foreach ($hashedLanguages as $lang => $lv) { echo ($lang) . ':' . ($lv) . '|'; } ?>" data-role='tagsinput'>
										</div>
									</div>
									
									<?php
									$arrayEducTittle = explode('|', $editedCVRow['educTittle']);
									$arrayEducCenter = explode('|', $editedCVRow['educCenter']);
									$arrayEducStart = explode('|', $editedCVRow['educStart']);
									$arrayEducEnd = explode('|', $editedCVRow['educEnd']);
									
									echo "<div class='form-group' >  <!-- Educación -->";
									echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVeducation'>Ausbildung *: </label>";
									echo "	<div class='col-sm-10'>";
									
									for($contEduc=0; $contEduc<count($arrayEducTittle); $contEduc++){
										echo "	<div class='panel panel-default'>";
										echo "		<div class='panel-heading'>";
										echo "			<h3 class='panel-title'>Ausbildung #".($contEduc+1) . "</h3>";
										echo "		</div>";
										echo "		<div class='panel-body'>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVeducTittle$contEduc'>Titel: </label>";
										echo " 				<div class='col-sm-10'>";
										echo "					<input class='form-control' type='text' name='eCCVeducTittle$contEduc' value='" . ($arrayEducTittle[$contEduc]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVeducCenter$contEduc'>Anstalt: </label>";
										echo " 				<div class='col-sm-10'>";
										echo "					<input class='form-control' type='text' name='eCCVeducCenter$contEduc' value='" . ($arrayEducCenter[$contEduc]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVeducStart$contEduc'>Beginn: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='date' name='eCCVeducStart$contEduc' value='" . ($arrayEducStart[$contEduc]) . "' >";
										echo " 				</div>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVeducEnd$contEduc'>Ende: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='date' name='eCCVeducEnd$contEduc' value='" . ($arrayEducEnd[$contEduc]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "		</div>";
										echo "	</div>";
									}
									
									echo "	</div>";
									echo "</div>";
									echo "<input type='hidden' name='eCCVcontEduc' value='$contEduc' >";
									?>
									
									<div class="form-group" >  <!-- Profesión -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcareer">Durchgeführten berufe: * </label>	<!-- Se puede omitir -->
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVcareer' value='<?php echo ($editedCVRow['career']) ?>' data-role='tagsinput'>										
										</div>
									</div>

									<?php 
									$array_experCompany = explode('|',$editedCVRow['experCompany']);
									$array_experStart = explode('|',$editedCVRow['experStart']);
									$array_experEnd = explode('|',$editedCVRow['experEnd']);
									$array_experPos = explode('|',$editedCVRow['experPos']);
									$arrayExpCity = explode('|',$editedCVRow['experCity']);
									$arrayExpCountry = explode('|',$editedCVRow['experCountry']);
									$array_experDesc = explode('|',$editedCVRow['experDesc']);

									echo "<div class='form-group' >  <!-- Experiencia -->";
									echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperience'>Letzten jahren: </label>";
									echo "	<div class='col-sm-10'>";
									
									for ($counterExperience=0; $counterExperience < count($array_experCompany); $counterExperience++) { 
										echo "	<div class='panel panel-default'>";
										echo "		<div class='panel-heading'>";
										echo "			<h3 class='panel-title'>Erfahrung #".($counterExperience+1) . "</h3>";
										echo "		</div>";
										echo "		<div class='panel-body'>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperCompany$counterExperience'>Kompanie: </label>";
										echo " 				<div class='col-sm-10'>";
										echo "					<input class='form-control' type='text' name='eCCVexperCompany$counterExperience' value='" . ($array_experCompany[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperCity$counterExperience'>Stadt: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='text' name='eCCVexperCity$counterExperience' value='" . ($arrayExpCity[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperCountry$counterExperience'>Staat: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='text' name='eCCVexperCountry$counterExperience' value='" . ($arrayExpCountry[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperStart$counterExperience'>Startseite: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='text' name='eCCVexperStart$counterExperience' value='" . ($array_experStart[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperEnd$counterExperience'>Ende: </label>";
										echo " 				<div class='col-sm-4'>";
										echo "					<input class='form-control' type='text' name='eCCVexperEnd$counterExperience' value='" . ($array_experEnd[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperPos$counterExperience'>Stellung: </label>";
										echo " 				<div class='col-sm-10'>";
										echo "					<input class='form-control' type='text' name='eCCVexperPos$counterExperience' value='" . ($array_experPos[$counterExperience]) . "' >";
										echo " 				</div>";
										echo "			</div>";
										echo "			<div class='form-group'>";
										echo "				<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperDesc$counterExperience'>Beschreibung: </label>";
										echo " 				<div class='col-sm-10'>";
										echo "					<textarea class='form-control' name='eCCVexperDesc$counterExperience'>" . ($array_experDesc[$counterExperience]) . "</textarea>";
										echo " 				</div>";
										echo "			</div>";
										echo "		</div>";
										echo "	</div>";
									}
									
									echo "	</div>";
									echo "</div>";
									echo "<input type='hidden' name='eCCV_counterExperience' value='$counterExperience' >";
									?>
									
									<div class="form-group" >  <!-- Salario Deseado -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsalary">Gehaltsvorstellung: </label>
										<div class="col-sm-10 input-group">
											<input class="form-control" type='text' name='eCCVsalary' maxlength='7' value="<?php echo ($editedCVRow['salary']) ?>" onkeypress="return checkOnlyNumbers(event)">
											<span class="input-group-addon">€ netto/jahr</span>
										</div>
									</div>										

									<div class="form-group" >  <!-- Otros Detalles -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVotherDetails">Zusatzinfo: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVotherDetails' value="<?php echo ($editedCVRow['otherDetails']) ?>">
										</div>
									</div>		

									<div class="form-group" >  <!-- Ficheros -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVfiles">Ficheros: </label>
										<div class="col-sm-10">
										<?php
											$userFilesArray  = scandir($userFilesDir);
											foreach ($userFilesArray as $value){
												if (preg_match("/\w+/i", $value)) {
													echo "<a href=downloadFileSingle.php?doc=".$userFilesDir.$value.">$value</a><br>";
												}
											}
											?>		
										</div>						
									</div>	

									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">Fähigkeiten des kandidaten</h3>
										</div>
										<div class="panel-body">
											<?php
											for ($i=1; $i <= 10; $i++) { 
												echo "<div class='form-group' >  <!-- Habilidad ".$i." -->";
												echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVskill".$i."'>#".$i.": </label>";
												echo "	<div class='col-sm-10'>";
												echo "		<input class='form-control' type='text' name='eCCVskill".$i."' value='".($editedCVRow["skill$i"])."'>";
												echo "	</div>";
												echo "</div>";
											}
											?>
										</div>
									</div>

									<div class="form-group" >  <!-- Comentarios -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcomments">Kommentare: </label>	
										<div class="col-sm-10">
											<textarea class="form-control" type='text' name='eCCVcomments' value="<?php echo ($editedCVRow['comments']) ?>"><?php echo ($editedCVRow['comments']) ?></textarea>
										</div>
									</div>	

									<div class="form-group" >  <!-- Estado del Candidato y Fecha de CV -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcandidateStatus">Zustand des kandidaten: * </label>	
										<div class="col-sm-4">
											<select class="form-control" name='eCCVcandidateStatus'>
												<option value=''>-- wählen --</option>
												<option value='available'>Verfügbar</option>
												<option value='working'>Arbeiten</option>
												<option value='discarded'>Ausgeschlossen</option>
											</select>
										</div>

										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcvDate">Datum CV: </label>
										<div class="col-sm-4">
											<input class="form-control" type='text' name='eCCVcvDate' value="<?php echo ($editedCVRow['cvDate']) ?>" readonly>
										</div>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Abmelden</button>
									<button type="submit" class="btn btn-primary" name="eCurCVsend">CV zu überprüfen <span class="glyphicon glyphicon-ok"> </span></button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!--  *****-----------------------------------   End of displayed Modal HTML   -----------------------------------*****  -->


				<!--  ***********************************   Start of Web Page as initially showed   ***********************************  -->
				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">
						<h2 class="page-header">Lebensläufe zu Prüfzwecken</h2>
						<?php 
							if((getDBrowsnumber('cvitaes') == 0) || (count($cvIDs = getDBcolumnvalue('id', 'cvitaes', 'cvStatus', 'pending')) == 0)){
							echo 'Keine Lebensläufe für die Überprüfung.';
						}
						else{
							?>
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th>NIE</th>
											<th>Namen</th>
											<th>Nachnamen</th>
											<th>Aktion</th>
										</tr>
									</thead>
	
									<tbody>
									<?php 
									foreach($cvIDs as $i){
										$cvRow = getDBrow('cvitaes', 'id', $i);
										echo "<tr>";
										echo "<td><a href='pendingCVs.php?codvalue=" . ($cvRow['nie']) . "'>" . ($cvRow['nie']) . "</a></td>";
										echo "<td>" . ($cvRow['name']) . "</td>";
										echo "<td>" . ($cvRow['surname']) . "</td>";
										echo "<td><a href='pendingCVs.php?codvalue=" . $cvRow['id'] . "&hiddenGET=hDelPendingCV' onclick='return confirmPendingCVDeletionDE();'>Löschen</a></td>";
										echo "</tr>";
									}
									?>
									</tbody>
								</table>
							</div>
							<?php 
						}
						?>
					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
				<!--  ******------------------------------   End of Web Page as initially showed   -------------------------------*****  -->
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
	<script src="../../common/js/bootstrap-tagsinput.js"></script>

	<!-- Own document functions -->
	<!-- Show modal if password has to be changed -->
	<?php 

		if (isset($_GET['codvalue'])) {
			echo "<script type='text/javascript'>";
			echo "	$(document).ready(function(){";
			echo "		$('#editCVModal').modal('show');";
			echo "		$('#editCVModal').on('hidden.bs.modal', function () {";
 			echo "			window.location.href='pendingCVs.php';";
			echo "		});";
			echo "	});  ";
			echo "</script> ";
		}
	?>	

</body>
</html>
