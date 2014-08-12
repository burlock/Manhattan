<?php session_start(); error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING); $wannaExit = false; $wannaGoTo ='index.html'; ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Passwort Verwaltung</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">

	<script type="text/javascript">
		var changePasswordFlag = false;
	</script>
</head>

<body>
	
	<?php 
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	
	/***************************************     Start of FORM validations     ***************************************/
	//Part of the code read when user is forced to change his/her password
	if($_POST['confirmNewPassword']){
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		$newCryptedPass = blowfishCrypt($_POST['newPassword']);
		/* There are only 2 different cases that may force the APP to change a user's password:
		 * - When 'needPass' var is '1'
		 * - When 'passExpiration' var is less than current date (when is a past date)
		 */
		//1st time user logs in or password reseted because user forgot its own password BUT not expirated password (In this case password length is 8 and not hashed)
		if(($userRow['needPass']) && (!($userRow['passExpiration'] <= date('Y-m-d'))) && (!checkSimplePassChange($_POST['newPassword'], $_POST['confirmNewPassword'], $userRow['language'], $keyError))){
			//echo 'error checkpasschange no pasado con needpass y sin passexpiration.';
			?>
			<div class="top-alert-container">
				<div class="alert alert-warning alert-error top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Opppsss!</strong> <?php echo $keyError; ?>
				</div>
			</div>

			<?php $wannaGoTo ='index.html';
		}
		//Expirated password BUT not first time user logs in neither password reseted
		elseif(!($userRow['needPass']) && ($userRow['passExpiration'] <= date('Y-m-d')) && (!checkHashedPassChange($_POST['newPassword'], $_POST['confirmNewPassword'], $userRow['pass'], $userRow['language'], $keyError))){
			//echo 'error checkpasschange no pasado sin needpass y con passexpiration.';
			?>
			<div class="top-alert-container">
				<div class="alert alert-warning alert-error top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Opppsss!</strong> <?php echo $keyError; ?>
				</div>
			</div>

			<?php $wannaGoTo ='index.html';
		}
		//Both 'needPass' == '1' and 'passExpiration' a past date. Password, in this case, is 8 characters length
		elseif(($userRow['needPass']) && ($userRow['passExpiration'] <= date('Y-m-d')) && (!checkSimplePassChangeDE($_POST['newPassword'], $_POST['confirmNewPassword'], $userRow['language'], $keyError))){
			//echo 'error checkpasschange no pasado con needpass y passexpiration.';
			?>
			<div class="top-alert-container">
				<div class="alert alert-warning alert-error top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Opppsss!</strong> <?php echo $keyError; ?>
				</div>
			</div>

			<?php $wannaGoTo ='index.html';
		}
		
		//If everything goes well, password is encrypted and saved in DB
		elseif(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0', `lastConnection` = CURRENT_TIMESTAMP, `passExpiration`='".addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'))."' WHERE `login`='".$_SESSION['loglogin']."'")){
			//echo 'error intentando actualizar la BBDD';
			?>
			<div class="top-alert-container">
				<div class="alert alert-danger alert-error top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Error!</strong> Es war nicht möglich ihr passwort zu aktualisieren.
					<!-- Could have also failed update of 'lastConnection' or 'passExpiration' fields -->
				</div>
			</div>

			<?php $wannaGoTo = 'index.html';
		}
		else{
			$_SESSION['logprofile'] = $userRow['profile'];
			$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
			$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
			?>

			<div class="top-alert-container">
				<div class="alert alert-success top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Erfolg!</strong> Aktualisierte passwort.
				</div>
			</div>					

			<?php $wannaGoTo = 'home.php';
		}
	}
	/***************************************     End of FORM validations     ****************************************/
	
	
	/******************************     Code initially read everytime user logs in     ******************************/
	else{
		//Firstly checks if both text fields (login + password) were fulfilled or not
		if (isset($_POST['loglogin']) && !empty($_POST['loglogin']) && isset($_POST['logpasswd']) && !empty($_POST['logpasswd'])){
			$checkedUser = $_POST["loglogin"];
			$userRow = getDBrow('users', 'login', $checkedUser);
			$profileRow = getDBrow('profiles', 'name', $userRow['profile']);
			
			//If there is no result when searching login in DB...
			if($userRow == 0){
				?>
				<div class="top-alert-container">
					<div class="alert alert-danger alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> Benutzer wurde nicht gefunden.
					</div>
				</div>

				<?php 	$wannaGoTo ='index.html'; 
			}
			
			//Then checks password for those users whose password was previously changed sometime BUT now they need to change again
			elseif((!(crypt($_POST['logpasswd'], $userRow['pass']) == $userRow['pass'])) && (!$userRow['needPass'])){
				?>
				<div class="top-alert-container">
					<div class="alert alert-danger alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> Falsches passwort.
					</div>
				</div>						
				<?php 	$wannaGoTo ='index.html'; $wannaExit = true;
			}
			
			//If user password was not previously changed (First time user logs in)
			elseif(($_POST['logpasswd'] != $userRow['pass']) && ($userRow['needPass'])){
				?>
				<div class="top-alert-container">
					<div class="alert alert-danger alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> Falsches passwort.
					</div>
				</div>						
				<?php 	$wannaGoTo ='index.html'; $wannaExit = true;
			}
			
			//Checks whether user profile is active
			elseif(!$profileRow['active']){
				?>
				<div class="top-alert-container">
					<div class="alert alert-warning alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Opppsss!</strong> Keine aktiven profil.
					</div>
				</div>						
				<?php $wannaGoTo ='index.html';
			}
			
			//Checks whether user account is active
			elseif(!$userRow['active']){
				?>
				<div class="top-alert-container">
					<div class="alert alert-warning alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Opppsss!</strong> Berntzer account nicht aktiviert.
					</div>
				</div>						
				<?php $wannaGoTo ='index.html'; 
			}
			
			else{
				if(!$wannaExit){
					//After all these checkings, user could be properly logged in. We start with procedure
					$_SESSION['loglogin'] = $checkedUser; 
					
					//This block of code indicates that user password has expired, or that is mandatory for user to change password for any matter
					if(($userRow['passExpiration'] <= date('Y-m-d')) || ($userRow['needPass'])){
					?>
					<script type="text/javascript">
						var changePasswordFlag = true;
					</script>	

					<div id='changePasswordModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='changePasswordModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<form id='changePasswordForm' class='form-horizontal center-block' action='validatefront.php' method='post' onsubmit='return equalPassword(newPassword, confirmNewPassword)'>
								<div class='modal-content panel-warning'>
									<div class='modal-header panel-heading'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title'>Sie müssen ihr passwort ändern bevor sie fortfahren</h4>
									</div>
									<div class='well encapsulated'>
										<!-- If "passwdRestrictionsES.txt" is changed function "checkXXXXXXPassChangeXX" will be needed to be also changed -->
										<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/passwdRestrictionsDE.txt' ?>
									</div>
									<div class='modal-body encapsulated'>
										<div class='form-group'>
											<label for='newPassword' class='control-label'>Neues passwort</label>
											<div class='center-block'>
												<input type='password' class='form-control' name='newPassword' id='newPassword' placeholder='' required data-toggle='tooltip' title='Enter new password' autocapitalize='off'>
											</div>
										</div>
										<div class='form-group'>
											<label for='confirmNewPassword' class='control-label'>Passwort wiederholen</label>
											<div class='center-block'>
												<input type='password' class='form-control' name='confirmNewPassword' id='confirmNewPassword' placeholder='' required data-toggle='tooltip' title='Confirm password' autocapitalize='off'>
											</div>
										</div>
									</div>
									<div class='modal-footer'>
										<button type='submit' class='btn btn-primary'>Änderung</button>
									</div>
								</div>
							</form><!-- id='changePasswordForm'  -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<?php
					}

					else{
						if(!executeDBquery("UPDATE `users` SET `lastConnection` = CURRENT_TIMESTAMP WHERE `login` = '".$checkedUser."'")){
							?>
							<div class="top-alert-container">
								<div class="alert alert-danger alert-error top-alert fade in">
									<a href="#" class="close" data-dismiss="alert">&times;</a>
									<strong>Error!</strong> Es gab ein problem beim aktualisieren des datum der letzten verbindung.
								</div>
							</div>								
							<?php $wannaGoTo ='index.html'; 
						}
						else{
							$_SESSION['logprofile'] = $userRow['profile'];
							$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
							$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
							?>
							<script type="text/javascript">
								window.location.href='home.php';
							</script>
							<?php
						}
					}
				} // Si no quiero salir...
			} // Else
		}

		//If any of the text fields (login/password) were not fulfilled...
		else{
			?>
			<div class="top-alert-container">
				<div class="alert alert-warning alert-error top-alert fade in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Opppsss!</strong> Sie haben vergessen, keines der felder ausfüllen.
				</div>
			</div>				
			<?php $wannaGoTo ='index.html'; 
		}
	}
	/**********************************     End of code initially read everytime user logs in     **********************************/
	?>


<!-- Footer bar & info
	================================================== -->
	<div id="footer">
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

	<!-- Own document functions -->
	<!-- Show modal if password has to be changed -->
	<script type="text/javascript">
		$(document).ready(function(){
			if (changePasswordFlag == true) {
				// Automatically show the password change alert
				$('#changePasswordModal').modal('show');
			}
			$('#changePasswordModal').on('hidden.bs.modal', function (e) {
 				window.location.href='index.html';
			});
		});  
	</script> 

	<!-- Go to validatefront.php when alert closed -->
	<script type="text/javascript">
		$(document).ready(function(){
			// Close the alert after 2 seconds.
			window.setTimeout(function() { $(".alert").alert('close'); }, 5000);
			$(".alert").bind('closed.bs.alert', function(){
				window.location.href='<?php echo $wannaGoTo ?>';
			});
		});  
	</script>

</body>
</html>
