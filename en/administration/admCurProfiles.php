<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Profile Managament</title>

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
			window.location.href='/en/index.html';
		</script>
		<?php
	}
	else{
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/en/staticHeader.php';
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
						<h2 class="page-header">Profile Management</h2>
						<?php
						
						/*****************************     Start of FORM validations     *****************************/
						if(isset($_POST['newPsubmit'])){
							if (isset($_POST['newPName']) && !empty($_POST['newPName'])){
								$newProfile = $_POST['newPName'];
								if(strpos(trim($newProfile), " ") > 0){
									$newProfile = str_replace(' ', '', $newProfile);
								}
								//New profile is registrated in every table where necessary (profiles, administration and home)
								$newProfile = dropAccents($newProfile);
								if((!executeDBquery("INSERT INTO `profiles` (`id`, `name`, `active`, `created`) VALUES (NULL, '".$newProfile."', '1', CURRENT_TIMESTAMP)")) || 
								(!executeDBquery("INSERT INTO `home` (`id`, `profile`, `active`, `pendingCVs`, `checkedCVs`, `searchCVs`, `personalData`) VALUES (NULL, '".$newProfile."', '1', '0', '0', '0', '0')")) ||
								(!executeDBquery("INSERT INTO `administration` (`id`, `profile`, `active`, `admGenOptions`, `profiles`, `admCurProfiles`, `admNewProfile`, `users`, `admCurUsers`, `admNewUser`) VALUES (NULL, '".$newProfile."', '0', '0', '0', '0', '0', '0', '0', '0')"))){
									?>
									<script type="text/javascript">
										alert('Error creating new profile');
										window.location.href='admCurProfiles.php';
									</script>
									<?php
								}
								else{
									?>
									<script type="text/javascript">
										alert('Perfil created successfully');
										window.location.href='admCurProfiles.php';
									</script>
									<?php
								}
							}
						}
						
						elseif(isset($_POST['eProfilesend'])){
							$profileRow = getDBrow('profiles', 'id', $_POST['hiddenCurProfID']);
							if($profileRow['active'] == '1'){
								$tablesKeyNames = getDBcompletecolumnID('key', 'mainNames', 'id');
								foreach($tablesKeyNames as $i){
									executeDBquery("UPDATE `.$i.` SET `active`='".$_POST['ePactive']."' WHERE `id`='".$_POST['hiddenCurProfID']."'");
								}
							}
							executeDBquery("UPDATE `profiles` SET `active`='".$_POST['ePactive']."' WHERE `id`='".$profileRow['id']."'");
						}
						
						elseif(isset($_GET['hiddenGET'])){
							/*
							switch($_GET['hiddenGET']){
								case 'hDelProfile':
									$profileRow = getDBrow('profiles', 'id', $_GET['codvalue']);
									if(!deleteDBrow('profiles', 'id', $_GET['codvalue'])){
										?>
										<script type="text/javascript">
											alert('Error deleting Profile.');
											window.location.href='admCurProfiles.php';
										</script>
										<?php 
									}
									else{
										//AUN TENDRIA QUE BORRAR TODOS LOS USUARIOS DE ESE PERFIL Y BORRAR SU CVs SI FUERAN CANDIDATOS
										//TAMBIÉN HABRÍA QUE BORRAR LOS REGISTROS EN 'home' y 'administrtion'
									}
								break;
								
							}
							*/
							?>
							<script type="text/javascript">
								alert('Para evitar inconsistencias la función de borrado de Perfiles está desactivada');
								window.location.href='admCurProfiles.php';
							</script>
							<?php 
						}//end of GET
						/*****************************     End of FORM validations     *****************************/
						
						/*************************     Start of WebPage code as showed     *************************/
						?>
						<div class="panel panel-default"> <!-- Panel de Perfiles Existentes -->
							<div class="panel-heading">
								<h3 class="panel-title">Existing Profiles</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table id="profilesTable" class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Profile</th>
												<th>Active</th>
												<th>Created</th>
												<th>Users</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$profileKeyRow = getDBcompletecolumnID('name', 'profiles', 'id');
											$k = 1;
											foreach($profileKeyRow as $i){
												$showedProfileRow = getDBrow('profiles', 'name', $i);
												echo "<tr>";
													echo "<td>" . $k . "</td>";
													echo "<td><a class='launchModal' href='admCurProfiles.php?codvalue=" . $showedProfileRow['id'] . "'>" . $showedProfileRow['name'] . "</a></td>";
													if($showedProfileRow['active']){
														echo "<td>Yes</td>";
													}
													else{
														echo "<td>No</td>";
													}
													echo "<td>" . $showedProfileRow['created'] . "</td>";
													echo "<td>" . $showedProfileRow['numUsers'] . "</td>";
													echo "<td><a href='admCurProfiles.php?codvalue=" . $showedProfileRow['id'] . "&hiddenGET=hDelProfile' onclick=\"return confirmProfileDeletion('".getCurrentLanguage($_SERVER['SCRIPT_NAME'])."');\">Delete</a></td>";
													echo "</tr>";
												$k++;
											}
											?>
										</tbody>
									</table>
								</div>
								
								<?php 
								if($_SESSION['logprofile'] == 'SuperAdmin'){
									?>
									<div class="container-fluid center-block">
										<h4>New Profile</h4>
										<form class="form-inline" role="form" name="newProfile" action="admCurProfiles.php" method="post" onsubmit="return confirmProfileCreation()">
											<div class="form-group">
												<label class="sr-only" for="newPName">Profile</label>
												<input type="text" class="form-control" size="20" name="newPName" placeholder="Profile" />
												<!-- Por defecto queda activado, por lo que no incluyo la posibilidad de crearlo desactivado. Así lo he decidido -->
												<button type="submit" class="btn btn-primary" name="newPsubmit" value="Add">Add</button>
												<input type="hidden" value="hNewPsubmit" name="hiddenfield">
											</div>
										</form>
									</div>
									<?php
								}
								/*************************     End of WebPage code as showed     *************************/
								?>
							</div>
						</div> <!-- Panel de Perfiles existentes -->
					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
			</div> <!-- row -->
		</div> <!-- class="container bs-docs-container" -->
		
		
		<?php
		/****************************************     Start of Functional code for Modal HTML     ****************************************/
		
		$editedProfileRow = getDBrow('profiles', 'id', $_GET['codvalue']);
		?>
		<div id="editUserModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content panel-info">
					<div class="modal-header panel-heading">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Profile: <?php echo $editedProfileRow['name'] ?></h4>
					</div>
					<form id="editedProfile" class="form-horizontal" role="form" name="editedProfile" autocomplete="off" method="post" action="admCurProfiles.php">
						<div class="modal-body">
							<div class="form-group">
								<label id="editedProfileLabel" class="control-label col-sm-2" for="ePid">Identifier: </label> 
								<div class="col-sm-10">
									<input class="form-control" type='text' name='ePid' value="<?php echo $editedProfileRow['id'] ?>" autocomplete="off" disabled />
								</div>
							</div>
							
							<div class="form-group">
								<label id="editedProfileLabel" class="control-label col-sm-2" for="ePname">Name: </label> 
								<div class="col-sm-10">
									<input class="form-control" type='text' name='ePname' value="<?php echo $editedProfileRow['name'] ?>" autocomplete="off" disabled />
								</div>
							</div>
							
							<div class="form-group">
								<label id="editedProfileLabel" class="control-label col-sm-2" for="ePactive">Active: </label>
								<div class="col-sm-10">
									<div class="radio-inline">
										<?php
										if($editedProfileRow['active'] == 0){
										?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="ePactive" value="0" checked>No</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="ePactive" value="1">Yes</label>
										<?php
										}
										else{
										?>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="ePactive" value="0">No</label>
											<label id="noPadding" class="radio-inline"><input class="radio-inline" type="radio" name="ePactive" value="1" checked>Yes</label>
										<?php
										}
										?>
									</div>
								</div> 
							</div>
							
							<div class="form-group">
								<label id="editedProfileLabel" class="control-label col-sm-2" for="ePcreated">Created: </label> 
								<div class="col-sm-10">
									<input class="form-control" type='text' name='ePcreated' value="<?php echo $editedProfileRow['created'] ?>" autocomplete="off" disabled />
								</div>
							</div>
							
							<div class="form-group">
								<label id="editedProfileLabel" class="control-label col-sm-2" for="ePusers">Num. Users: </label> 
								<div class="col-sm-10">
									<input class="form-control" type='text' name='ePusers' value="<?php echo $editedProfileRow['numUsers'] ?>" autocomplete="off" disabled />
								</div>
							</div>
						</div>
						
						<div class="modal-footer">
							<input type="hidden" value="<?php echo $editedProfileRow['id']; ?>" name="hiddenCurProfID">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" name="eProfilesend">Save <span class="glyphicon glyphicon-floppy-save"></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<?php 
		/*****************************************     End of Functional code for Modal HTML     *****************************************/
		
		
	}//del "else" de $_SESSION.

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
 			echo "			window.location.href='admCurProfiles.php';";
			echo "		});";
			echo "	});  ";
			echo "</script> ";
		}
	?>

</body>
</html>
