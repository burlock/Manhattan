<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs Pendientes</title>

	<!-- Custom styles for this template -->
	<link href="/common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="/common/img/apple-touch-icon.png">
</head>

<body>
	<?php
	if (!$_SESSION[loglogin]){
		?>
		<script type="text/javascript">
			window.location.href='/es/index.html';
		</script>
		<?php
	}
	else {
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/es/staticHeader.php';
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
				<!--  ****** -----------------------------------   End of Left Main Menu   ------------------------------------- ******  -->
				
				
				<?php
				/* *******************************************   Start of Form validations   ******************************************** */
				if(isset($_POST[eCurCVsend]) || isset($_GET[hiddenGET])){
					
					/* **********  This file will do every needed checking in pendingCVs and checkedCVs  ********** */
					
					include $_SERVER[DOCUMENT_ROOT] . '/common/code/curCVFormCheckings.php';
					
					/* **********  This file will do every needed checking in pendingCVs and checkedCVs  ********** */
					
				}//(isset($_POST[eCurCVsend]) || isset($_GET[hiddenGET]))
				/*****  ----------------------------------------   End of Form validations   ---------------------------------------  *****/
				
				
				/*******************************************   Start of displayed Modal HTML   ********************************************/
				elseif(isset($_GET[codvalue])){
					?>
					<div id="editCVModal" class="modal fade bs-example-modal-lg">
						<div class="modal-dialog modal-lg">
							<div class="modal-content panel-info">
								<div class="modal-header panel-heading">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Validando CV <?php echo $_GET[codvalue] ?></h4>
								</div>
	
								<?php
								$editedCVRow = getDBrow(cvitaes, nie, $_GET[codvalue]);
								$userFilesDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".($editedCVRow[userLogin])."/";
								
								if(!ifCreateDir($userFilesDir, 0777)){
									?>
									<script type="text/javascript">
										alert('Error recuperando información del directorio de Usuario. Por favor, contacte con el administrador.');
										window.location.href='../home.php';
									</script>
									<?php 
								}
								?>
								
								<form id="editedCV" class="form-horizontal" role="form" name="editedCV" autocomplete="off" method="post" action="pendingCVs.php" enctype="multipart/form-data">
									<?php
									/* **********  This file lets an Administrator modify one single CV's information in pendingCVs and checkedCVs  ********** */
									include $_SERVER['DOCUMENT_ROOT'] . '/common/code/es/checkingModal.php';
									/* **********  This file lets an Administrator modify one single CV's information in pendingCVs and checkedCVs  ********** */
									?>
									
									<div class="modal-footer">
										<!-- LA SIGUIENTE LINEA VALDRIA SI SOLO USARAMOS 1 CV... COMO SE DECIDA USAR MÁS DE UNO, NO VALE -->
										<!-- Si un CV está Pending no puede ser pausado -->
										<!-- <button type="submit" class="btn btn-primary pull-left" name="eCurCVhist" >Pausar CV</button> -->
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary" name="eCurCVsend">Validar CV <span class="glyphicon glyphicon-ok"> </span></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php
				}//isset($_GET[hiddenGET]) del Modal
				/*****  --------------------------------------   End of displayed Modal HTML   --------------------------------------  *****/
				
				
				else{
					?>
					<!--  ***********************************   Start of Web Page as initially showed   ***********************************  -->
					<div class="col-md-9 scrollable" role="main"> 
						<div class="bs-docs-section">
							<h2 class="page-header">CVs Pendientes de Revisar</h2>
							<?php 
								if((getDBrowsnumber('cvitaes') == 0) || (count($cvIDs = getDBcolumnvalue('id', cvitaes, 'cvStatus', 'pending')) == 0)){
								echo 'No hay CVs por revisar.';
							}
							else{
								?>
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>NIE</th>
												<th>Nombre</th>
												<th>Apellidos</th>
												<th>Acción</th>
											</tr>
										</thead>
		
										<tbody>
										<?php 
										foreach($cvIDs as $i){
											$cvRow = getDBrow(cvitaes, 'id', $i);
											echo "<tr>";
											echo "<td><a href='pendingCVs.php?codvalue=" . ($cvRow['nie']) . "'>" . ($cvRow['nie']) . "</a></td>";
											echo "<td>" . ($cvRow['name']) . "</td>";
											echo "<td>" . ($cvRow['surname']) . "</td>";
											echo "<td><a href='pendingCVs.php?codvalue=" . $cvRow['id'] . "&hiddenGET=hDelPendingCV' onclick='return confirmPendingCVDeletion(\"" . getCurrentLanguage($_SERVER['SCRIPT_NAME']) . "\");'>Borrar</a></td>";
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

		if (isset($_GET[codvalue])) {
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
