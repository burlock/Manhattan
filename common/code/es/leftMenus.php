<?php
session_start();

if (!$_SESSION['loglogin']){
	?>
	<script type="text/javascript">
		window.location.href='/es/index.html';
	</script>
	<?php
}
else {
	//Obtains number of pending CVs to be showed in leftbox (just circled at the right side of 'Pending CVs' link)
	$pendingCVs = getPendingCVs();
	
	$digitLang = getUserLangDigits($userRow['language']);
	$LangDigitsName = $digitLang."Name";
	$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
	$mainNamesRow = getDBcompletecolumnID($LangDigitsName, 'mainNames', 'id');
	$j = 0;
	foreach($mainKeysRow as $i){
		if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
			if($myFile == $i){
				echo "<li class='active'><a href=/$digitLang/$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
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
							if ($level2File == 'pendingCVs') {
								echo "<li><span class='badge'>$pendingCVs</span><a href=/$digitLang/$myFile/$level2File.php>" . $subLevelMenu . " </a></li>";
							}
							else 
								echo "<li><a href=/$digitLang/$myFile/$level2File.php>" . $subLevelMenu . " </a></li>";
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
							echo "<li><a href=/$digitLang/$myFile/$level3File.php>" . $subLevelMenu . "</a></li>";
						}
					}
				}
				echo "</ul> <!-- class='nav' -->";
				echo "</li> <!-- class='active' -->";
			}
			else{
				echo "<li><a href=/$digitLang/$i.php>" . $mainNamesRow[$j] . "</a></li>";
				$j++;
			}
		}
	}
}

	/* ASI ES COMO ESTABA EL admCurUsers.php ORIGINAL. POR SI ME PUDIERA AYUDAR A AVERIGUAR COMO HACER QUE APAREZCA EL Nº DE CVs PENDIENTES
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
	*/
?>