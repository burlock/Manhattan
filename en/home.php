<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Home</title>

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
	else {
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/en/staticHeader.php';
		?>
				
		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php
							include $_SERVER['DOCUMENT_ROOT'] . '/common/code/en/leftMenus.php';
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->
				
				
				<!--  ***********************************   Start of Web Page as showed for User   ***********************************  -->
				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">
						<?php 
						//Conditional block for 'Administrador' or 'SuperAdmin' profiles
						if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
							echo "<h1 class='page-header'>News <br></h1>";
							echo "<div class='clearfix'>";
							if((getDBrowsnumber('cvitaes') == 0) || ($pendingCVs == 0)){
								echo "<h1 class='page-header'><small>There are no CVs to check.</small></h1>";
							}
							else{
								echo "<h2><small>There is/are <a href=./home/pendingCVs.php>" . $pendingCVs . " </a> CVs to check. </small></h2><br>";
							}

							if(suggestPassword(date('Y-m-d'), $userRow['passExpiration'], $days)){
								echo "<h4 class='text-danger'>&nbsp;Your password expires in " . $days . " days. <a href=./home/personalData.php>Change</a><span class='label label-danger notice-label pull-left'>Attention</span></h4>";
								echo "</div>";
							}
						}
						//Conditional block for 'Lector' profile
						elseif($userRow['profile'] == 'Lector'){
							if(suggestPassword(date('Y-m-d'), $userRow['passExpiration'], $days)){
								echo "<h1 class='page-header'>News <br></h1>";
								echo "<div class='clearfix'>";
								echo "<h4 class='text-danger'>&nbsp;Your password expires in " . $days . " days. <a href=./home/personalData.php>Change</a><span class='label label-danger notice-label pull-left'>Attention</span></h4>";
								echo "</div>";
							}
							else{
								echo "<h1 class='page-header'>News <br></h1>";
								echo "<div class='clearfix'>";
							}
						}
						//Conditional block for any other profile (which in fact is only 'Candidato')
						else{
							echo "<h1 class='page-header'>Introduce your CV... <small>" . $userRow['login'] . "</small></h1>";
							include 'upload.php';
						}
						//This part of code lets the system show a previously written message (SAVED IN FILE "broadcasting.txt")
						if(($userRow['employee'] == '1') && (file_exists($_SERVER['DOCUMENT_ROOT'].'/broadcasting.txt'))){
							echo "<br><h4 class='text-danger'><span class='label label-warning notice-label pull-left'>Important information</span></h4><br>";
							echo "<h4 class='text-warning'>";
							include '../broadcasting.txt';
							echo "</h4>";
						}
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
	<script src="../common/js/functions.js"></script>
	<script src="../common/js/application.js"></script>
	<script src="../common/js/docs.min.js"></script>

</body>
</html>
