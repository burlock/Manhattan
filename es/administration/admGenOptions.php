<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Configuración General</title>

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
	else {
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


		<!-- Static navbar -->
		<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
			<div id="top_line" class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
				</div>
				<!-- <div class="navbar-collapse collapse"> -->
				<div class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<button type="button" class="navbar-toggle always-visible" data-toggle="dropdown">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Conectado como: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../home/personalData.php">Configuración personal</a></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Salir</a></li>
						</ul>
					</li>
				</div>
				<!-- </div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->


		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="../endsession.php">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="exitRequestLabel">Cerrar sesión</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro de que quieres salir?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Sí, cerrar sesión</button>
					</div>
				</form>
			</div>
		</div>


		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'administration';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();
		?>


		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
							$digitLang = getUserLangDigits($userRow['language']);
							$langDigitsName = $digitLang."Name";
							$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
							$mainNamesRow = getDBcompletecolumnID($langDigitsName, 'mainNames', 'id');
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
											if(($myFileProfileRow[$k] == 1) && ($subLevelMenu = getDBsinglefield2($langDigitsName, $namesTable, 'key', $colNamej, 'level', '2'))) {
												if(!getDBsinglefield2($langDigitsName, $namesTable, 'fatherKey', $colNamej, 'level', '3')){
													$level2File = getDBsinglefield('key', $namesTable, $langDigitsName, $subLevelMenu);
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
					<?php
					if(isset($_POST['hiddenPOST'])){
						switch ($_POST['hiddenPOST']){
							case 'hNewLangSubmit':
								if((empty($_POST['newLangenName'])) || (empty($_POST['newLangesName'])) || (empty($_POST['newLangdeName']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$auxKey = dropAccents($_POST['newLangenName']);
									$auxKey = ucwords($auxKey);
									$auxKey = str_replace(' ', '', $auxKey);
									//echo $auxKey;
									if($auxKey == getDBsinglefield('key', 'languages', 'key', $auxKey)){
										?>
										<script type="text/javascript">
											alert('Alguno de los datos introducidos ya existe en la BD.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
									elseif(!executeDBquery("INSERT INTO `languages` (`id`, `key`, `english`, `spanish`, `german`) VALUES
									(NULL, '".$auxKey."', '".ucwords($_POST['newLangenName'])."', '".ucwords($_POST['newLangesName'])."', '".ucwords($_POST['newLangdeName'])."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir el nuevo Idioma.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;

							case 'hNewCareerSubmit':
								if((empty($_POST['newCareerenName'])) || (empty($_POST['newCareeresName'])) || (empty($_POST['newCareerdeName']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$auxKey = dropAccents($_POST['newCareerenName']);
									$auxKey = ucwords($auxKey);
									$auxKey = str_replace(' ', '', $auxKey);
									if($auxKey == getDBsinglefield('key', 'careers', 'key', $auxKey)){
										?>
										<script type="text/javascript">
											alert('Alguno de los datos introducidos ya existe en la BD.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
									elseif(!executeDBquery("INSERT INTO `careers` (`id`, `key`, `english`, `spanish`, `german`) VALUES
									(NULL, '".$auxKey."', '".ucwords($_POST['newCareerenName'])."', '".ucwords($_POST['newCareeresName'])."', '".ucwords($_POST['newCareerdeName'])."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir la nueva Profesión.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;

							case 'hNewOptionSubmit':
								if((empty($_POST['newOptionKey'])) || strpos(trim($_POST['newOptionKey']), " ") > 0 || (empty($_POST['newOptionName'])) || (empty($_POST['newOptionComment'])) || (empty($_POST['newOptionValue']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$auxKey = dropAccents($_POST['newOptionKey']);
									$auxKey = ucwords($auxKey);
									$auxKey = str_replace(' ', '', $auxKey);
									if($auxKey == getDBsinglefield('key', 'careers', 'key', $auxKey)){
										?>
										<script type="text/javascript">
											alert('La clave introducida ya existe en la BD.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
									elseif(!executeDBquery("INSERT INTO `otherOptions` (`id`, `key`, `name`, `comment`, `value`) VALUES
									(NULL, '".$auxKey."', '".$_POST['newOptionName']."', '".$_POST['newOptionComment']."', '".$_POST['newOptionValue']."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir la nueva Opción.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;
							
							case 'hMassiveSubmit':
								$path = $_SERVER['DOCUMENT_ROOT'];
								$completePath = $path.'/'.$_POST['massiveFile'];
								
								if(!massiveUpload($completePath, '.', $_POST['destinyTableSel'])){
									?>
									<script type="text/javascript">
										alert('Error al cargar archivo.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									?>
									<script type="text/javascript">
										alert('Archivo cargado con éxito.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
							break;

						}
					}//del POST
					
					elseif(isset($_GET['hiddenGET'])){
						switch($_GET['hiddenGET']){
							case 'hDelLang':
								if(!deleteDBrow('languages', 'id', $_GET['codvalue'])){
									?>
									<script type="text/javascript">
										alert('Error al borrar Idioma.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
							break;
							
							case 'hDelCareer':
								if(!deleteDBrow('careers', 'id', $_GET['codvalue'])){
									?>
									<script type="text/javascript">
										alert('Error al borrar Profesión.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
							break;
							
							case 'hDelOptions':
								//De momento no hay botón de borrar Opciones
								if(!deleteDBrow('otherOptions', 'id', $_GET['codvalue'])){
									?>
									<script type="text/javascript">
										alert('Error al borrar Opción General.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
							break;
						}
						?>
						<script type="text/javascript">
							window.location.href='admGenOptions.php';
						</script>
						<?php 
					}//del GET
					/*****************************     End of FORM validations     *****************************/

					/*************************     Start of WebPage code as showed     *************************/
					?>

					<div class="bs-docs-section">
					<h2 class="page-header">Opciones Generales</h2>
					<?php 
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						?>
						<div class="panel panel-default"> <!-- Panel de Idiomas -->
							<div class="panel-heading">
								<h3 class="panel-title">Idiomas</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<?php if(getDBrowsnumber('languages') < 1){ ?>
									<h4>No hay idiomas. Por favor, incluya alguno.</h4>
									<?php } else{ ?>
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$langKeyRows = getDBcompletecolumnID('key', 'languages', 'id');
											$k = 1;
											foreach($langKeyRows as $i){
												$langRow = getDBrow('languages', 'key', $i);
												echo "<tr>";
												echo "<td>" . $k . "</td>";
												echo "<td>" . $langRow['key'] . "</td>";
												echo "<td>" . $langRow['english'] . "</td>";
												echo "<td>" . $langRow['spanish'] . "</td>";
												echo "<td>" . $langRow['german'] . "</td>";
												echo "<td><a href='admGenOptions.php?codvalue=" . $langRow['id'] . "&hiddenGET=hDelLang' onclick='return confirmLangDeletionES();'>Borrar</a></td>";
												$k++;
											}
											?>
										</tbody>
									</table>
									<?php } ?>
								</div>

								<div class="container-fluid center-block">
									<h4>Nuevo Idioma</h4>
									<form class="form-inline" role="form" name="newLanguage" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newLangenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newLangenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newLangesName">Nombre Español</label>
											<input type="text" class="form-control" name="newLangesName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLangdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newLangdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewLangSubmit" name="hiddenPOST">
										<button type="submit" class="btn btn-primary" name="newLangsubmit" value="Incluir">Incluir</button>
									</form>
								</div>

							</div>
						</div> <!-- Panel de Idiomas -->
						
						<div class="panel panel-default"> <!-- Panel de Profesiones (careers) -->		
							<div class="panel-heading">
								<h3 class="panel-title">Profesiones</h3>
								<!-- <input type="button" class="btn btn-primary" name="cleanCareers" value="Limpiar"> -->
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<?php if(getDBrowsnumber('careers') < 1){ ?>
									<h4>No hay profesiones. Por favor, incluya alguna.</h4>
									<?php } else{ ?>
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$careerKeyRows = getDBcompletecolumnID('key', 'careers', 'id');
											$k = 1;
											foreach($careerKeyRows as $i){
												$careerRow = getDBrow('careers', 'key', $i);
												echo "<tr>";
												echo "<td>" . $k . "</td>";
												echo "<td>" . $careerRow['key'] . "</td>";
												echo "<td>" . $careerRow['english'] . "</td>";
												echo "<td>" . $careerRow['spanish'] . "</td>";
												echo "<td>" . $careerRow['german'] . "</td>";
												echo "<td><a href='admGenOptions.php?codvalue=" . $careerRow['id'] . "&hiddenGET=hDelCareer' onclick='return confirmCareerDeletionES();'>Borrar</a></td>";
												$k++;
											}
											?>
										</tbody>
									</table>
									<?php } ?>
								</div>
								
								<div class="container-fluid center-block">
									<h4>Nueva Profesión</h4>
									<form class="form-inline" role="form" name="newCareer" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newCareerenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newCareerenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newCareeresName">Nombre Español</label>
											<input type="text" class="form-control" name="newCareeresName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newCareerdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newCareerdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewCareerSubmit" name="hiddenPOST">
										<button type="submit" class="btn btn-primary" name="newCareersubmit" value="Incluir">Incluir</button>
									</form>
								</div>
							</div>
						</div> <!-- Panel de Profesiones (careers) -->

						<div class="panel panel-default"> <!-- Panel Otras Opciones -->
							<div class="panel-heading">
								<h3 class="panel-title">Otras Opciones</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre</th>
												<th>Comentario</th>
												<th>Valor</th>
											</tr>
										</thead>

										<tbody>
											<?php 
											$oOptionsKeyRows = getDBcompletecolumnID('key', 'otherOptions', 'id');
											foreach($oOptionsKeyRows as $i){
												$oOptionsRow = getDBrow('otherOptions', 'key', $i);
												echo "<tr>";
												echo "<td>" . $oOptionsRow['id'] . "</td>";
												echo "<td>" . $oOptionsRow['key'] . "</td>";
												echo "<td>" . $oOptionsRow['name'] . "</td>";
												echo "<td>" . $oOptionsRow['comment'] . "</td>";
												echo "<td>" . $oOptionsRow['value'] . "</td>";
											}
											?>
										</tbody>
									</table>
									<div class="container-fluid center-block">
										<h4>Nueva Opción General</h4>
										<form class="form-inline" role="form" name="newOption" action="admGenOptions.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="newOptionKey">Clave</label>
												<input type="text" class="form-control" size="6" name="newOptionKey" placeholder="Clave" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newOptionName">Nombre</label>
												<input type="text" class="form-control" name="newOptionName" placeholder="Nombre" />
											</div>							
											<div class="form-group">
												<label class="sr-only" for="newOptionComment">Comentario</label>
												<input type="text" class="form-control" name="newOptionComment" placeholder="Comentario" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newOptionValue">Valor</label>
												<input type="text" class="form-control" name="newOptionValue" placeholder="Valor" />
											</div>	
											<input type="hidden" value="hNewOptionSubmit" name="hiddenPOST">
											<button type="submit" class="btn btn-primary" name="newOptionsubmit" value="Incluir">Incluir</button>
										</form>
									</div>
								</div>
							</div>
						</div> <!-- Panel Otras Opciones -->
					
						<div class="panel panel-default"> <!-- Panel Carga Masiva de datos -->
							<div class="panel-heading">
								<!-- <h3 class="panel-title">Carga Masiva (Cuidadito con lo que metes y dónde lo metes...)</h3> -->
								<h3 class="panel-title">Carga Masiva (El archivo debe estar en la ruta DOCUMENT_ROOT)</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<div class="container-fluid center-block">
										<h4>Elige el archivo a cargar y la tabla en la que hacerlo</h4>
										<form class="form-inline" role="form" name="newMassiveLoad" action="admGenOptions.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="massiveFile">Archivo</label>
												<input type="file" class="form-control" name="massiveFile" id="massiveFile" onchange="checkMassFileExtension(this.id)">
											</div>
											<div class="form-group">
												<label class="sr-only" for="destinyTable">Tabla</label>
												<select name="destinyTableSel" class="form-control">
													<option selected disabled value=''>Tabla</option>
													<?php 
													$tablesList = getDBTablesNames();
													foreach($tablesList as $i){
														echo "<option value=" . $i . ">" . $i . "</option>";
													}
													?>
												</select>
											</div>
											<input type="hidden" value="hMassiveSubmit" name="hiddenPOST">
											<button type="submit" class="btn btn-primary" name="newMassivesubmit" value="Cargar">Cargar</button>
										</form>
									</div>
								</div>
							</div>
						</div> <!-- Panel Carga Masiva de datos -->
					
					<?php 
					}
					elseif($_SESSION['logprofile'] == 'Administrador'){
					?>
						<div class="panel panel-default"> <!-- Panel de Idiomas -->
							<div class="panel-heading">
								<h3 class="panel-title">Idiomas</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<?php if(getDBrowsnumber('languages') < 1){ ?>
									<h4>No hay idiomas. Por favor, incluya alguno.</h4>
									<?php } else{ ?>
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$langKeyRows = getDBcompletecolumnID('key', 'languages', 'id');
											$k = 1;
											foreach($langKeyRows as $i){
												$langRow = getDBrow('languages', 'key', $i);
												echo "<tr>";
												echo "<td>" . $k . "</td>";
												echo "<td>" . $langRow['english'] . "</td>";
												echo "<td>" . $langRow['spanish'] . "</td>";
												echo "<td>" . $langRow['german'] . "</td>";
												echo "<td><a href='admGenOptions.php?codvalue=" . $langRow['id'] . "&hiddenGET=hDelLang' onclick='return confirmLangDeletionES();'>Borrar</a></td>";
												$k++;
											}
											?>
										</tbody>
									</table>
									<?php } ?>
								</div>

								<div class="container-fluid center-block">
									<h4>Nuevo Idioma</h4>
									<form class="form-inline" role="form" name="newLanguage" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newLangenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newLangenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newLangesName">Nombre Español</label>
											<input type="text" class="form-control" name="newLangesName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLangdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newLangdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewLangSubmit" name="hiddenPOST">
										<button type="submit" class="btn btn-primary" name="newLangsubmit" value="Incluir">Incluir</button>
									</form>
								</div>
							</div>
						</div> <!-- Panel de Idiomas -->
						
						<div class="panel panel-default"> <!-- Panel de Profesiones (careers) -->		
							<div class="panel-heading">
								<h3 class="panel-title">Profesiones</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<?php if(getDBrowsnumber('careers') < 1){ ?>
									<h4>No hay profesiones. Por favor, incluya alguna.</h4>
									<?php } else{ ?>
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$careerKeyRows = getDBcompletecolumnID('key', 'careers', 'id');
											$k = 1;
											foreach($careerKeyRows as $i){
												$careerRow = getDBrow('careers', 'key', $i);
												echo "<tr>";
												echo "<td>" . $k . "</td>";
												echo "<td>" . $careerRow['english'] . "</td>";
												echo "<td>" . $careerRow['spanish'] . "</td>";
												echo "<td>" . $careerRow['german'] . "</td>";
												echo "<td><a href='admGenOptions.php?codvalue=" . $careerRow['id'] . "&hiddenGET=hDelCareer' onclick='return confirmCareerDeletionES();'>Borrar</a></td>";
												$k++;
											}
											?>
										</tbody>
									</table>
									<?php } ?>
								</div>
								
								<div class="container-fluid center-block">
									<h4>Nueva Profesión</h4>
									<form class="form-inline" role="form" name="newCareer" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newCareerenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newCareerenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newCareeresName">Nombre Español</label>
											<input type="text" class="form-control" name="newCareeresName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newCareerdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newCareerdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewCareerSubmit" name="hiddenPOST">
										<button type="submit" class="btn btn-primary" name="newCareersubmit" value="Incluir">Incluir</button>
									</form>
								</div>
							</div>
						</div> <!-- Panel de Profesiones (careers) -->
					
					<?php 
					}
					//This will be used if any, with no permission, acceed to this page
					else{
					?>
					<script type="text/javascript">
						window.location.href='admGenOptions.php';
					</script>
					<?php 
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
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

</body>
</html>
