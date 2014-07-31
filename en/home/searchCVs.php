<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Search CVs</title>
	
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
							<li class="dropdown-header">Logged in as: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../home/personalData.php">Personal Settings</a></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Exit</a></li>
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
						<h4 class="modal-title" id="exitRequestLabel">Close Session</h4>
					</div>
					<div class="modal-body">
						Are you sure you want to close session?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Yes, close session</button>
					</div>
				</form>
			</div>
		</div> <!-- exitRequest Modal -->


		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
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
				
				
				<!--  ****************************************   Start of displayed Modal HTML   ****************************************  -->
				<div class="col-md-9" role="main"> 
					<div class="bs-docs-section">
						<h2 class="page-header">Search CVs</h2>
						<div class="panel panel-default scrollable">
							<div class="panel-heading">
								<h2 class="panel-title">Enter Search Criteria</h2>
							</div>
							<div class="panel-body scrollable">
								
								<form id="searchForm" name="searchForm" class="form-horizontal" method="post" action="searchResult.php" autocomplete="off" autocapitalize="off" enctype="multipart/form-data" onsubmit="return comprobar()";>
									<div id="form_WordKey" class="form-group">
										<label for="blankWordKey" class="control-label col-xs-3">Key word</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankWordKey" id="blankWordKey" maxlength="12" placeholder="Max. 12 characters" autofocus>
										</div>
									</div> <!-- id="form_WordKey" -->
									
									<div id="form_NIE" class="form-group">
										<label for="blankNIE" class="control-label col-xs-3">NIE</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankNIE" id="blankNIE" maxlength="12" placeholder="Max. 12 characters" autofocus>
										</div>
									</div> <!-- id="form_NIE" -->

									<div id="form_Driving" class="form-group">
										<label for="drivingType" class="control-label col-xs-3">Driving license</label>
										<div class="col-xs-2">
											<select name="drivingType" class="form-control">
													<option selected disabled value=''>Type</option>
													<option value="1">AM</option>
													<option value="2">A</option>
													<option value="3">A1</option>
													<option value="4">A2</option>
													<option value="5">B</option>
													<option value="6">C</option>
													<option value="7">C1</option>
													<option value="8">D</option>
													<option value="9">D1</option>
													<option value="10">E</option>
													<option value="11">BTP</option>
												</select>
										</div>
										<div class="col-xs-7">
											<input type="date" class="form-control" name="drivingDate" name="drivingDate" />
										</div>
									</div> <!-- id="form_Driving" -->
									
									<div id="form_Nationality" class="form-group">
										<label for="blankNationality" class="control-label col-xs-3">Nationality</label> 
										<div class="col-xs-9">
											<select name="blankNationality" class="form-control">
												<option value="Spain"> Spain </option>
												<?php
												$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
												$countryName = getDBcompletecolumnID($userLang, 'countries', $userLang);
												foreach($countryName as $i){
													echo '<option value="' . getDBsinglefield('key', 'countries', $userLang, $i) . '">' . $i . '</option>';
												}
												?>
											</select>
										</div>
									</div> <!-- id="form_Nationality" -->
									
									<div id="form_Status" class="form-group">
										<label for="civilStatus" class="control-label col-xs-3">Marital status</label>
										<div class="col-xs-9">
											<select name="civilStatus" class="form-control">
												<option selected disabled value="">-- Status --</option>
												<option value="1">Single</option>
												<option value="2">Married</option>
												<option value="3">Divorced</option>
												<option value="4">Widowed</option>
												<option value="5">Separated</option>
											</select>
										</div>
									</div> <!-- id="form_Status" -->			

									<div id="form_genre" class="form-group">
										<label for="blankSex" class="control-label col-xs-3">Gender</label>
										<div class="col-xs-3" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="0"> Male</label>
										</div>
										<div class="col-xs-3" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="1"> Female</label>
										</div>
									</div> <!-- id="form_genre" -->		

									<div id="form_childrens" class="form-group">
										<label for="blankSons" class="control-label col-xs-3">Sons</label>
										<div class="col-xs-9">
											<input type="number" class="form-control" name="blankSons" id="blankSons" maxlength="2">
										</div>
									</div> <!-- id="form_NIE" -->

									<div id="form_Languages" class="form-group">
										<label for="blankLanguages" class="control-label col-xs-3">Language level</label>
										<div class="col-xs-4">
											<select name="blankLanguages" class="form-control">
												<option selected disabled value=''>Choose language</option>
												<?php
												$langNames = getDBcompletecolumnID($userRow['language'], 'languages', $userRow['language']);
												foreach ($langNames as $i){
													echo '<option value="' . getDBsinglefield('key', 'languages', $userRow['language'], $i) . '">' . $i . '</option>';
												}
												?>
											</select>
										</div>
										<div class="col-xs-5">
											<select name="languagelevel" class="form-control">
												<option selected value="null">Choose level</option>
												<option value="A1">A1</option>
												<option value="A2">A2</option>
												<option value="B1">B1</option>
												<option value="B2">B2</option>
												<option value="C1">C1</option>
												<option value="C2">C2</option>
												<option value="mothertongue">Mother tongue</option>
											</select>
										</div>
									</div> <!-- id="form_languages" -->		

									<div id="form_Profession" class="form-group">
										<label for="blankJob" class="control-label col-xs-3">Career</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankJob" id="blankJob" maxlength="12" placeholder="Current Profession">
										</div>
									</div> <!-- id="form_Profession" -->

									<div id="form_Title" class="form-group">
										<label for="titleType" class="control-label col-xs-3">Education</label>
										<div class="col-xs-3">
											<select name="tittletype" class="form-control">
												<option selected disabled value="">Without studies</option>
												<option value="1">Mandatory education</option>
												<option value="2">High school degree</option>
												<option value="3">Vocational training</option>
												<option value="4">University degree</option>
												<option value="5">Degree</option>
											</select>
										</div>
										<div class="col-xs-6">
											<input type="text" class="form-control" name="tittles" placeholder="Studies" />
										</div>
									</div> <!-- id="form_Title" -->

									<div id="report_set" class="panel panel-default">
  										<div class="panel-body">
											<div id="form_report" class="form-group">
												<label for="reportType" class="control-label col-xs-3">Report type</label>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="full_report" onclick="test(2);" checked> Full</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="blind_report" onclick="test(2);"> Blind</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="custom_report" onclick="test(1);"> Personalized</label>
												</div>										
											</div> <!-- id="form_report" -->
											
											<hr>
											
											<div id="form_custom_report" class="form-group">
												<table>
													<tr>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="name" disabled> Name</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="surname" disabled> Surnames</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="addrName" disabled> Address</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="mobile" disabled> Mobile</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="phone" disabled> Other phone</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="mail" disabled> E-mail</td>
													</tr>
													<tr>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="drivingType" disabled> Driving license</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="marital" disabled> Marital status</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="sons" disabled> Sons</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="language" disabled> Languages</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="occupation" disabled> Career</td>
														<td style="padding: 10px; font-size: 14px;"><input type="checkbox" name="per[]" value="experDesc" disabled> Work experience</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								
									<div id="form_submit" class="form-group pull-right" style="margin: 1px;">
										<button type="submit" name="Buscar" class="btn btn-primary" >Search <span class="glyphicon glyphicon-search"> </span></button>
									</div>

								</form> <!-- id="searchForm" -->

							</div> <!-- class="panel-body" -->
						</div> <!-- class="panel panel-default" -->
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

	<!-- Page own functions -->
	<script type="text/javascript">
		function test (temp){
			switch (temp){
				case 1    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = false ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				case 2    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = true ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				default    :
				alert('What to do?');
			}
		}
	</script>
	
	<script type="text/javascript">
		function comprobar(){
			var x = document.getElementById("searchForm");
			var texto = "";
			for (var i=0;i<x.length;i++){
				texto = texto + x.elements[i].name + "<br>";
			}
		}
	</script>

</body>
</html>