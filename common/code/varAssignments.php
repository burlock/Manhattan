<?php
	//File retrieved from 'upload.php'
	
	//At the very beggining I will ensure that no local var has a previous value
	unset($key);
	unset($entry);
	unset($arrayLanguages);
	unset($arrayLangLevels);
	unset($securedEducTittle);
	unset($securedEducCenter);
	unset($arrayEducStart);
	unset($arrayEducEnd);
	unset($arrayOccupations);
	unset($securedExperCompany);
	unset($securedExperPosition);
	unset($arrayExperStart);
	unset($arrayExperEnd);
	unset($securedExperCity);
	unset($securedExperCountry);
	unset($securedExperDescription);
	unset($arrayCountries);
	unset($outName);
	unset($outSurname);
	unset($checkError);
	unset($outAddrName);
	unset($outAddrNumber);
	unset($securedOther);
	unset($securedSkill1);
	unset($securedSkill2);
	unset($securedSkill3);
	unset($securedSkill4);
	unset($securedSkill5);
	unset($securedSkill6);
	unset($securedSkill7);
	unset($securedSkill8);
	unset($securedSkill9);
	unset($securedSkill10);
	unset($userDir);
	unset($insertCVQuery);
	unset($photoUploadFile);
	unset($image);
	unset($strCountry);
	
	//The very first validation will be LOPD checkbox
	foreach ($_POST as $key => $entry){
		if(is_array($entry)){
			if($key == idiomas){
				$arrayLanguages = $entry;
			}
			if($key == nidiomas){
				$arrayLangLevels = $entry;
			}
			if($key == educ){
				$securedEducTittle = securizeArray($entry);
			}
			if($key == educCenter){
				$securedEducCenter = securizeArray($entry);
			}
			if($key == educStart){
				$arrayEducStart = $entry;
			}
			if($key == educEnd){
				$arrayEducEnd = $entry;
			}
			if($key == prof){
				$arrayOccupations = $entry;
			}
			if($key == empr){
				$securedExperCompany = securizeArray($entry);
			}
			if($key == categ){
				$securedExperPosition = securizeArray($entry);
			}
			if($key == expstart){
				$arrayExperStart = $entry;
			}
			if($key == expend){
				$arrayExperEnd = $entry;
			}
			if($key == expcity){
				$securedExperCity = securizeArray($entry);
			}
			if($key == expcountry){
				$securedExperCountry = securizeArray($entry);
			}
			if($key == desc){
				$securedExperDescription = securizeArray($entry);
			}
			if($key == nat){
				$arrayCountries = $entry;
			}
		 }
	}
	
	$securedOther = securizeString($_POST['blankother']);
	$securedSkill1 = securizeString($_POST['blankskill1']);
	$securedSkill2 = securizeString($_POST['blankskill2']);
	$securedSkill3 = securizeString($_POST['blankskill3']);
	$securedSkill4 = securizeString($_POST['blankskill4']);
	$securedSkill5 = securizeString($_POST['blankskill5']);
	$securedSkill6 = securizeString($_POST['blankskill6']);
	$securedSkill7 = securizeString($_POST['blankskill7']);
	$securedSkill8 = securizeString($_POST['blankskill8']);
	$securedSkill9 = securizeString($_POST['blankskill9']);
	$securedSkill10 = securizeString($_POST['blankskill10']);
	
	//If Candidate introduced a Postal code, as it only permits "España", we change it to "Spanien", just to be in german when showing CV
	if($_POST['blankaddrcountry'] == 'España'){
		$strCountry = 'Spanien';
	}
	else{
		$strCountry = $_POST['blankaddrcountry'];
	}
?>