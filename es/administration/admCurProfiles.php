<?php session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Gestión de Perfiles</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../common/js/functions.js"></script>
	<script type="text/javascript" src="../../common/js/jquery-1.10.1.min.js"></script>
	
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
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-d H:i:s');
		$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
		if($elapsedTime > $_SESSION['sessionexpiration']){
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
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
			
		//Checks whether loaded php page/file corresponds to logged user's language
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		
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
		<div id="topbar" class="azul">
			<a style="float:left;" href="#">Opciones</a>
			<a style="float:center">Conectado como: <?php echo $_SESSION['loglogin']; ?></a>
			<a href="../endsession.php" style="float:right">Salir</a>
		</div>
		<?php 
		$myFile = 'administration';
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		?>
		<div id="mainmenu">
		<ul class="navbar1">
			<?php 
			$digitLang = getUserLangDigits($userRow['language']);
			$LangDigitsName = $digitLang."Name";
			$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
			$mainNamesRow = getDBcompletecolumnID($LangDigitsName, 'mainNames', 'id');
			$j = 0;
			foreach($mainKeysRow as $i){
				if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
					if($myFile == $i){
						echo "<li><a href=../$i.php id='onlink'>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
						$j++;
					}
					else{
						echo "<li><a href=../$i.php>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
						$j++;
					}
				}
			}
			?>
		</ul>
		</div>
	
		<div class="workspace">
			<div class="leftbox">
				<!-- Este 'class' sirve para mostrar los submenús alineados a la izquierda en el nivel 2 -->
				<ul>
				<?php
				$namesTable = $myFile.'Names';
				$numCols = getDBnumcolumns($myFile);
				$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
				for($j=3;$j<$numCols;$j++){
					$colNamej = getDBcolumnname($myFile, $j);
					if(($myFileProfileRow[$j] == 1) && ($subLevelMenu = getDBsinglefield2($LangDigitsName, $namesTable, 'key', $colNamej, 'level', '2'))){
						if(!getDBsinglefield2($LangDigitsName, $namesTable, 'fatherKey', $colNamej, 'level', '3')){
							$level2File = getDBsinglefield('key', $namesTable, $LangDigitsName, $subLevelMenu);
							echo "<li><a href=./$level2File.php>" . $subLevelMenu . "</a></li>";
						}
						else{
							$arrayKeys = array();
							$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
							$checkFinished = 0;
							$l = 1;
							foreach($arrayKeys as $k){
								if($checkFinished == 0){
									if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($k, $myFile, 'profile', $userRow['profile']))){
										$level3File = $k;
										$checkFinished = 1;
									}
									else{
										$l++;
									}
								}
							}
							echo "<li><a href=./$level3File.php>" . $subLevelMenu . "</a></li>";
						}
					}
				}
				?>
				</ul>
			</div>
	
			<div class="rightbox">
			<?php 
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
							alert('Error al crear el nuevo perfil');
							window.location.href='admCurProfiles.php';
						</script>
						<?php
					}
					else{
						?>
						<script type="text/javascript">
							alert('Perfil creado con éxito');
							window.location.href='admCurProfiles.php';
						</script>
						<?php
					}
				}
			}
			?>
			<p><span id="leftmsg">Perfiles Existentes</span></p><hr><br>
			<table class="tabla1">
				<tr>
					<th>Id</th>
					<th>Perfil</th>
					<th>Activo</th>
					<th>Creado</th>
					<th>Usuarios</th>
					<?php 
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						echo "<th>Acción</th>";
					}
					?>
				</tr>
				<?php
				$profileNumRows = getDBrowsnumber('profiles');
				for($i=1;$i<=$profileNumRows;$i++){
					$showedProfileRow = getDBrow('profiles', 'id', $i);
					echo "<tr>";
					echo "<td>" . $showedProfileRow['id'] . "</td>";
					echo "<td><a href='editProfile.php?codvalue=" . $i . "'>" . $showedProfileRow['name'] . "</a></td>";
					if($showedProfileRow['active']){
						echo "<td>Si</td>";
					}
					else{
						echo "<td>No</td>";
					}
					echo "<td>" . $showedProfileRow['created'] . "</td>";
					echo "<td>" . $showedProfileRow['numUsers'] . "</td>";
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						echo '<td><a href="#" onclick="confirmProfileDeletion(' . $showedProfileRow['id'] . ')">Borrar</a></td>';
					}
				}
				?>
			</table>
			<?php 
			if($_SESSION['logprofile'] == 'SuperAdmin'){
				?>
				<fieldset id="auto1">
					<legend>Nuevo Perfil</legend>
					<form name="newProfile" action="admCurProfiles.php" method="post" onsubmit="return confirmProfileCreation()">
						<input type="text" id="newPName" name="newPName" size="25" placeholder="Nombre del perfil" />
						<!-- Por defecto queda activado, por lo que no incluyo la posibilidad de crearlo desactivado. Así lo he decidido -->
						<input type="hidden" value="hNewPsubmit" name="hiddenfield">
						<input type="submit" value="Añadir" name="newPsubmit">
					</form>
				</fieldset>
				<?php
			}
			?>
			</div><!-- Fin del "rightbox" -->
		</div><!-- Fin del "workspace" -->
		<?php
	}//del "else" de $_SESSION.

?>

</body>
</html>
