<?php
	session_start();

	set_time_limit(1800);
	set_include_path('../../common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
	set_include_path(get_include_path() . PATH_SEPARATOR . "../../common/cppdf");

	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
	
	require_once "dompdf_config.inc.php";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');

?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Visualisierung von CV</title>
	
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
	else{
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
				</div><!--/.nav-collapse -->
				<?php if($userRow['employee'] == '1'){ ?>
					<a href="/common/files/CV Managing Tool - User Guide.pdf" style="float: right; margin-right: 60px; margin-top: 15px">Benutzerhandbuch</a>
				<?php }?>
			</div><!--/.container-fluid -->
		</div><!--/Static navbar -->
		
		
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
		</div><!-- exitRequest Modal -->
		
		
		<!---------------------------------- *****   Start of PDF and HTML docs composition   ***** ----------------------------------->
		<?php
		
		include $_SERVER['DOCUMENT_ROOT'] . '/common/code/cvCreation.php';
		
		?>
		<!--  *************************************   End of PDF and HTML docs composition   **************************************  -->
	
	
	<div id="main-content" class="cvViewer bs-docs-container">
		<div class="row container-fluid cvViewer">
			<div class="panel panel-default cvViewer tooltip-demo col-md-8" role="main"> <!-- Panel -->
				<div class="btn-group pull-right">
					<?php
					if(strlen($id_o[$ind_p])>0){
						echo "<a href='viewCV.php?id_bb=$ind_p&reportType=".$report."' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Vorherige'><span class='glyphicon glyphicon-chevron-left'></span></a>";
					}
					else{
						echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-left'></span></a>";
					}
					?>
						<a href="<?php echo "../../cvs/".$pagetext.".pdf" ?>" target="_blank" class="btn btn-default btn-sm" data-toggle='tooltip' data-original-title='Laden CV in PDF'><span class='glyphicon glyphicon-download-alt'></span></a>
					<?php
					if(strlen($id_o[$ind_n])>0){
						echo "<a href='viewCV.php?id_bb=$ind_n&reportType=".$report."' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Folgende'><span class='glyphicon glyphicon-chevron-right'></span></a>";
					}
					else{
						echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-right'></span></a>";
					}
					$_SESSION["id_o"] = serialize($id_o);
					$_SESSION["id"] = serialize($id);
					?>
				</div>
				<div class="panel-heading">
					<!-- <h3 class="panel-title">Lebenslauf von < ?php echo $currentName;?></h3> -->
					<h3 class="panel-title">CV des Kandidaten mit code <?php echo $curUserLogin;?></h3>
				</div>
				<div class="panel-body scrollable" > <!-- panel-body -->
					<?php echo ($texto); ?>
				</div> <!-- panel-body -->
			</div> <!-- Panel -->
			
			<div class="panel panel-default col-md-3">
				<div class="panel-heading">
					<h3 class="panel-title">Notiz hinzufügen</h3>
				</div>
				<div class="panel-body" > <!-- panel-body -->
					<?php
					echo "<form name='formu' id='formu' class='form-horizontal' action='viewCV.php?id_b=".$ida."&reportType=".$report."' method='post' enctype='multipart/form-data'>";
						echo "<textarea class='form-control' name='nota' rows='10' cols='40'></textarea>";
						echo "<div id='form_submit' class='form-group pull-right' style='margin: 1px; margin-top: 10px;'>";
							echo "<button type='submit' name='enviar' class='btn btn-primary' onclick='insert();'>Hinzufügen <span class='glyphicon glyphicon-pencil'> </span></button>";
						echo "</div>";
					echo "</form>";
					?>
				</div> <!-- panel-body -->
			</div>
		</div>
	</div> <!-- class="cvViewer bs-docs-container" -->
	
	<?php
	
	}//initial "$_SESSION elseif"
	
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
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

</body>
</html>