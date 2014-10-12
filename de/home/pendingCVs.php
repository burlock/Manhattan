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
					
					include $_SERVER['DOCUMENT_ROOT'] . '/common/code/pendingFormCheckings.php';
					
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
						$updateCVQuery = "	UPDATE `cvitaes` SET 
							`nie` = '".$_POST['eCCVnie']."',
							`cvStatus` = 'checked',
							`name` = '".$outName."',
							`surname` = '".$outSurname."',
							`birthdate` = '".$outDate."',
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
						WHERE `nie` = '".$_POST['eCCVnie']."';";
						
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
				
				
				/*******************************************   Start of displayed Modal HTML   ********************************************/
				elseif(isset($_GET['codvalue'])){
					?>
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
									
									<?php
									include $_SERVER['DOCUMENT_ROOT'] . '/common/code/de/checkingModal.php';
									?>
									
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Stornieren</button>
										<button type="submit" class="btn btn-primary" name="eCurCVsend">CV zu überprüfen <span class="glyphicon glyphicon-ok"> </span></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php
				}//isset($_GET['hiddenGET']) del Modal
				/*****  --------------------------------------   End of displayed Modal HTML   --------------------------------------  *****/
				
				
				else{
					?>
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
											//echo "<td><a href='pendingCVs.php?codvalue=" . $cvRow['id'] . "&hiddenGET=hDelPendingCV' onclick='return confirmPendingCVDeletionDE();'>Löschen</a></td>";
											echo "<td><a href='pendingCVs.php?codvalue=" . $cvRow['id'] . "&hiddenGET=hDelPendingCV' onclick='return confirmPendingCVDeletion(" . getCurrentLanguage($_SERVER['SCRIPT_NAME']) . ");'>Löschen</a></td>";
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
					<?php
				}//del else que muestra la tabla inicial de Candidatos pendientes
				?>
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
