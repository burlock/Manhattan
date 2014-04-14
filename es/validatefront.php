<?php session_start(); error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING); $wannaExit = false; $wannaGoTo ='index.html'; ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Gestión de contraseña</title>

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

		//Part of the code read when user is forced to change his/her password	
		if($_POST['confirmNewPassword']){
			if(!checkPassChange($_POST['newPassword'], $_POST['confirmNewPassword'], $keyError)){
				?>
				<div class="top-alert-container">
					<div class="alert alert-warning alert-error top-alert fade in">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Opppsss!</strong> <?php echo $keyError; ?>
					</div>
				</div>

				<?php $wannaGoTo ='index.html';
			}
			//That's when system generates new Blowfish password
			else{
				$newCryptedPass = blowfishCrypt($_POST['newPassword']);
				if($newCryptedPass == getDBsinglefield('pass', 'users', 'login', $_SESSION['loglogin'])){
					?>
					<div class="top-alert-container">
						<div class="alert alert-danger alert-error top-alert fade in">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong> La nueva contraseña debe ser distinta a la anterior.
						</div>
					</div>

					<?php 	$wannaGoTo = 'index.html';
				}
				elseif(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0' WHERE `login`='".$_SESSION['loglogin']."'")){
					//session_destroy(); DEBERIA DESTRUIR LA SESSION
					?>
					<div class="top-alert-container">
						<div class="alert alert-danger alert-error top-alert fade in">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong> No fue posible actualizar su contraseña.
						</div>
					</div>

					<?php 	$wannaGoTo = 'index.html';
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `lastConnection` = CURRENT_TIMESTAMP WHERE `login` = '".$_SESSION['loglogin']."'")){
						?>
						<div class="top-alert-container">
							<div class="alert alert-danger alert-error top-alert fade in">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Error!</strong> No se pudo actualizar la fecha de última conexión.
							</div>
						</div>								
						<?php $wannaGoTo ='index.html'; 
					}
					else{
						$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
						$_SESSION['logprofile'] = $userRow['profile'];
						$_SESSION['lastupdate'] = date('Y-m-d H:i:s');
						$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
						?>
	
						<div class="top-alert-container">
							<div class="alert alert-success top-alert fade in">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Éxito!</strong> Contraseña actualizada.
							</div>
						</div>					
	
						<?php $wannaGoTo = 'home.php';
					}
				}
			}
		}
		/**************************************************************************************************************************/
		
		//Part of the code read when user tries to loggin
		else{
			//Firstly checks if both text fields were fulfilled or not
			if (isset($_POST['loglogin']) && !empty($_POST['loglogin']) && isset($_POST['logpasswd']) && !empty($_POST['logpasswd'])){
				$checkedUser = $_POST["loglogin"];
				//$checkedPasswd = $_POST["logpasswd"];
				$userRow = getDBrow('users', 'login', $checkedUser);
				$profileRow = getDBrow('profiles', 'name', $userRow['profile']);
				if($userRow == 0){
					?>
					<div class="top-alert-container">
						<div class="alert alert-danger alert-error top-alert fade in">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong> Usuario no encontrado.
						</div>
					</div>

					<?php 	$wannaGoTo ='index.html'; 
				}

				//Then checks password for those users that have been previously changed their password
				elseif((!(crypt($_POST['logpasswd'], $userRow['pass']) == $userRow['pass'])) && (!$userRow['needPass'])){
					?>
					<div class="top-alert-container">
						<div class="alert alert-danger alert-error top-alert fade in">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong> Contraseña incorrecta.
						</div>
					</div>						
					<?php 	$wannaGoTo ='index.html'; $wannaExit = true;
				}
				elseif(($_POST['logpasswd'] != $userRow['pass']) && ($userRow['needPass'])){
					?>
					<div class="top-alert-container">
						<div class="alert alert-danger alert-error top-alert fade in">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong> Contraseña incorrecta.
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
							<strong>Opppsss!</strong> Perfil no activo.
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
							<strong>Opppsss!</strong> Cuenta de usuario no activa.
						</div>
					</div>						
					<?php $wannaGoTo ='index.html'; 
				}
				else {
					if (!$wannaExit) {
						//After all these checkings, user could be properly logged in. We start with procedure
						$_SESSION['loglogin'] = $checkedUser; 

						//if(($userRow['passExpiration'] <= date('Y-m-j')) || ($userRow['needPass'])){
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
											<h4 class='modal-title'>Debe cambiar la contraseña antes de continuar</h4>
										</div>
										<div class='well encapsulated'>
											<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/passwdRestrictionsES.txt' ?>
										</div>
										<div class='modal-body encapsulated'>
											<div class='form-group'>
												<label for='newPassword' class='control-label'>Nueva contraseña</label>
												<div class='center-block'>
													<input type='password' class='form-control' name='newPassword' id='newPassword' placeholder='' required data-toggle='tooltip' title='Enter new password' autocapitalize='off'>
												</div>
											</div>
											<div class='form-group'>
												<label for='confirmNewPassword' class='control-label'>Repita contraseña</label>
												<div class='center-block'>
													<input type='password' class='form-control' name='confirmNewPassword' id='confirmNewPassword' placeholder='' required data-toggle='tooltip' title='Confirm password' autocapitalize='off'>
												</div>
											</div>
										</div>
										<div class='modal-footer'>
											<button type='submit' class='btn btn-primary'>Cambiar</button>
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
										<strong>Error!</strong> No se pudo actualizar la fecha de última conexión.
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
						<strong>Opppsss!</strong> Ha olvidado rellenar alguno de los campos.
					</div>
				</div>				
				<?php $wannaGoTo ='index.html'; 
			}
		}
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
