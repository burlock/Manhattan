<?php
session_start();

if (!$_SESSION['loglogin']){
	?>
	<script type="text/javascript">
		window.location.href='/de/index.html';
	</script>
	<?php
}
else {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	
	$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
	
	//Identifying the name of the folder this script is in it can be later shown the rest of level 1 menus as the user navigates through them, knowing what of them is active (id='onlink')
	$myFile = getMyFile($_SERVER['SCRIPT_NAME']);
	
	$lastUpdate = $_SESSION['lastupdate'];
	$curUpdate = date('Y-m-d H:i:s');
	$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
	//URL direct navigation for loggedin users with no granted access is limited here, as session expiration
	if(($elapsedTime > $_SESSION['sessionexpiration']) || (!accessGranted($_SERVER['SCRIPT_NAME'], $myFile, $userRow['profile']))){
		?>
		<script type="text/javascript">
			window.location.href='/de/endsession.php';
		</script>
		<?php
	}
	else{
		$_SESSION['lastupdate'] = $curUpdate;
		unset($lastUpdate);
		unset($curUpdate);
		unset($elapsedTime);
	}
	//Checks whether logged user has selected language or not, redirecting him to its proper language page/file if needed
	if((strlen($userRow['language']) < 1)){
		$userLang = getCurrentLanguage($_SERVER['SCRIPT_NAME']);
		if(!executeDBquery("UPDATE `users` SET `language`='".$userLang."' WHERE `login`='".$_SESSION['loglogin']."'")){
			?>
			<script type="text/javascript">
				alert('Fehler beim aktualisieren neutrale sprache.');
				window.location.href='/de/endsession.php';
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript">
				window.location.href='/de/home.php';
			</script>
			<?php
		}
	}
	elseif(getCurrentLanguage($_SERVER['SCRIPT_NAME']) != $userRow['language']){
		$userRootLang = getUserRoot($userRow['language']);
		$noRootPath = getNoRootPath($_SERVER['SCRIPT_NAME']);
		?>
		<script type="text/javascript">
			window.location.href='<?php echo $userRootLang.$noRootPath ?>';
		</script>
		<?php
	}
}
?>

	<!-- Static navbar -->
	<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
		<div id="top_line" class="top-page-color"></div>
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
					<img src="/common/img/logo.png" alt="Perspectiva Alemania">
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
						<li class="dropdown-header">Angemeldet als: <?php echo $_SESSION['loglogin']; ?></li>
						<li class="divider"></li>
						<li><a href="/de/home/personalData.php">Persönliche Einstellungen</a></li>
						<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Aussteigen</a></li>
					</ul>
				</li>
			</div>
			<?php if($userRow['employee'] == '1'){ ?>
				<a href="/common/files/CV Managing Tool - User Guide.pdf" style="float: right; margin-right: 60px; margin-top: 15px">Benutzerhandbuch</a>
			<?php }?>
		</div><!--/.container-fluid -->
	</div>	<!--/Static navbar -->
	
	
	<!-- exitRequest Modal -->
	<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="modal-content" action="/de/endsession.php">
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
	</div>
