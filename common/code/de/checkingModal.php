<?php
session_start();

if(!$_SESSION[loglogin]){
	?>
	<script type="text/javascript">
		window.location.href='/<?php echo getUserLangDigits($userRow[language]); ?>/index.html';
	</script>
	<?php
}
else{
	?>
	
	<div class="modal-body">

		<div class="form-group"> <!-- Nombre -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVname">Namen: * </label> 
			<div class="col-sm-10">
				<input class="form-control" type='text' name='eCCVname' value="<?php echo ($editedCVRow['name']) ?>" autocomplete="off" />
			</div>
		</div> <!-- Fin Nombre -->

		<div class="form-group"> <!-- Apellidos -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsurname">Nachnamen: * </label>
			<div class="col-sm-10">
				<input class="form-control" type='text' name='eCCVsurname' value="<?php echo ($editedCVRow['surname']) ?>" autocomplete="off"/>
			</div>
		</div> <!-- Fin Apellidos -->
		
		<div class="form-group"> <!-- Fecha de Nacimiento & NIE -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVbirthdate">Geburtsdatum: * </label>
			<div class="col-sm-4">
				<input class="form-control" type='date' name='eCCVbirthdate' id='eCCVbirthdate' value="<?php echo ($editedCVRow['birthdate']) ?>" onChange="jsIsAdult(this.id, 18)" required>
			</div>

			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnie">Personalausweis: * </label>
			<div class="col-sm-4">
				<input class="form-control" type='text' name='eCCVnie' value="<?php echo ($editedCVRow['nie']) ?>" onkeyup='this.value=this.value.toUpperCase();' readonly/>
			</div>
		</div> <!-- Fin Fecha de Nacimiento & NIE -->
		
		<div class="form-group tooltip-demo"> <!-- Nacionalidad -->
			<?php
			//$nationalityQueryResult = getDBDistCompleteColID("english", "countries", "english");
			$nationalityQueryResult = getDBDistCompleteColID("german", "countries", "german");
			$nationalities_string = implode(', ', $nationalityQueryResult);
			?>
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnationalities"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title='Spanien, <?php echo $nationalities_string; ?>'></span> Nationalität: * </label>
			<div class="col-sm-10">
				<input class="form-control" type='text' name='eCCVnationalities' value="<?php echo implode("|", getDBcolumnvalue('keyCountry', 'userNationalities', 'userNIE', $editedCVRow['nie'])); ?>" data-role='tagsinput' />
			</div>
		</div> <!-- Fin Nacionalidad -->
		
		<div class="form-group"> <!-- Sexo -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsex">Geschlecht: * </label>
			<div class="col-sm-10">
				<div class='radio-inline'>
					<?php
						if(($editedCVRow['sex']) == 0){
							echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0' checked>Mann</label>";
							echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1'>Frau</label>";
						}
						else {
							echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0'>Mann</label>";
							echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1' checked>Frau</label>";
						}
					?>
				</div>
			</div>
		</div> <!-- Fin Sexo -->
		
		<div class="form-group"> <!-- Tipo Dirección & Nombre Dirección -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrtype">Adresstyp: </label>
			<div class="col-sm-3">
				<select class="form-control" name="eCCVaddrtype">
					<?php
					$addressTypes = getDBcompletecolumnID('key', 'addressTypes', 'id');
					echo "<option value=''>-- wählen --</option>";
					if(strlen($editedCVRow['addrType']) > 0){
						foreach($addressTypes as $i){
							if($i == $editedCVRow['addrType']){
								echo '<option value='.$i.' selected>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
							}
							else{
								echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
							}
						}
					}
					else{
						foreach($addressTypes as $i){
							echo '<option value='.$i.'>'.getDBsinglefield(getCurrentLanguage($_SERVER['SCRIPT_NAME']), 'addressTypes', 'key', $i).'</option>';
						}
					}
					?>
				</select>
			</div>
		
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrName">Adresse namen: </label>
			<div class="col-sm-5">
				<input class="form-control" type='text' name='eCCVaddrName' value="<?php echo ($editedCVRow['addrName']) ?>">
			</div>
		</div> <!-- Fin Tipo Dirección & Nombre Dirección -->
		
		<div class="form-group"> <!-- Número, Portal y Escalera -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrNum">Nummer: </label>
			<div class="col-sm-2">
				<input class="form-control" type='text' name='eCCVaddrNum' maxlength='4' value="<?php echo ($editedCVRow['addrNum']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
			</div>
			
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrPortal">Halle: </label>
			<div class="col-sm-2">
				<input class="form-control" type='text' name='eCCVaddrPortal' maxlength='4' value="<?php echo ($editedCVRow['portal']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
			</div>

			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrStair">Aufgang: </label>
			<div class="col-sm-2">
				<input class="form-control" type='text' name='eCCVaddrStair' maxlength='4' value="<?php echo ($editedCVRow['stair']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
			</div>
		</div> <!-- Fin Número, Portal y Escalera -->
		
		<div class="form-group"> <!-- Piso y Puerta -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrFloor">Stockwerk: </label>
			<div class="col-sm-3">
				<input class="form-control" type='text' name='eCCVaddrFloor' maxlength='4' value="<?php echo ($editedCVRow['addrFloor']) ?>">
			</div>
			
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrDoor">Tor: </label>	
			<div class="col-sm-3">
				<input class="form-control" type='text' name='eCCVaddrDoor' maxlength='4' value="<?php echo ($editedCVRow['addrDoor']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
			</div>
		</div> <!-- Fin Piso y Puerta -->
		
		<div class="form-group"> <!-- Código Postal y Localidad -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVpostal">Postleitzahl: </label>
			<div class="col-sm-3">
				<input class="form-control" type='text' name='eCCVpostal' maxlength='5' value="<?php echo $editedCVRow['postalCode'] ?>" onkeypress="return checkOnlyNumbers(event)">
			</div>
			
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcity">Stadt: </label>
			<div class="col-sm-5">
				<input class="form-control" type='text' name='eCCVcity' value="<?php echo ($editedCVRow['city']) ?>">										
			</div>
		</div> <!-- Fin Código Postal y Localidad -->	
		
		<div class="form-group"> <!-- Provincia y País -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVprovince">Kreis: </label>
			<div class="col-sm-4">
				<input class="form-control" type='text' name='eCCVprovince' value="<?php echo ($editedCVRow['province']) ?>">										
			</div>
			
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcountry">Staat: </label>
			<div class="col-sm-4">
				<input class="form-control" type='text' name='eCCVcountry' value="<?php echo ($editedCVRow['country']) ?>">										
			</div>
		</div> <!-- Fin Provincia y País -->
		
		<div class="form-group" >  <!-- Teléfono Móvil y Teléfono adicional -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmobile">Handy: * </label>
			<div class="col-sm-4">
				<!-- <input class="form-control" type='text' name='eCCVmobile' maxlength='9' value="< ?php echo $editedCVRow['mobile'] ?>" onkeypress="return checkOnlyNumbers(event)"> -->
				<!-- Relajación de las Restricciones del Móvil, según correo -->
				<input class="form-control" type='text' name='eCCVmobile' maxlength='18' placeholder='Bei. 0034-699000000' value="<?php echo $editedCVRow['mobile'] ?>" onkeypress="return checkDashedNumbers(event)">
			</div>

			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVphone">Andere telefon: </label>
			<div class="col-sm-4">
				<input class="form-control" type='text' name='eCCVphone' maxlength='18' placeholder='Bei. 0034-910000000' value="<?php echo $editedCVRow['phone'] ?>" onkeypress="return checkDashedNumbers(event)">
			</div>
		</div>
		
		<div class="form-group" >  <!-- Correo Electrónico -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmail">E-mail: * </label>										
			<div class="col-sm-10">
				<input class="form-control" type='mail' name='eCCVmail' value="<?php echo ($editedCVRow['mail']) ?>">										
			</div>
		</div>
		
		<div class="form-group" >  <!-- Carnet de Conducir -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVdrivingType">Führerschein: </label>										
			<div class="col-sm-4">
				<select class="form-control" name="eCCVdrivingType">
					<?php
					$drivingTypes = getDBcompletecolumnID('key', 'drivingTypes', 'id');
					echo "<option value=''>-- wählen --</option>";
					if(strlen($editedCVRow['drivingType']) > 0){
						foreach($drivingTypes as $i){
							if($i == $editedCVRow['drivingType']){
								echo '<option value='.$i.' selected>'.$i.'</option>';
							}
							else{
								echo '<option value='.$i.'>'.$i.'</option>';
							}
						}
					}
					else{
						foreach($drivingTypes as $i){
							echo '<option value='.$i.'>'.$i.'</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="col-sm-6">
				<input class='form-control' type='date' name='eCCVdrivingDate' placeholder='aaaa-mm-dd' onChange="jsIsPreviousDate(this.id, '<?php echo getCurrentLanguage($_SERVER['SERVER_NAME']); ?>')" value="<?php echo ($editedCVRow['drivingDate']) ?>">
			</div>
		</div>
		
		<div class="form-group" >  <!-- Estado Civil e Hijos -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmarital">Familienstand: </label>
			<div class="col-sm-4">
				<select class="form-control" name="eCCVmarital" >
					<?php 
					$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
					$maritalStatus = getDBcompletecolumnID($userLang, 'maritalStatus', $userLang);
					echo "<option selected value=''>-- wählen --</option>";
					foreach($maritalStatus as $i){
						$keyMarital = getDBsinglefield('key', 'maritalStatus', $userLang, $i);
						if($keyMarital == $editedCVRow['marital']){
							echo "<option selected value=" . $keyMarital . ">" . $i . "</option>";
						}
						else{
							echo "<option value=" . $keyMarital . ">" . $i . "</option>";
						}
					}
					?>
				</select>
			</div>

			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsons">Kinder: </label>
			<div class="col-sm-4">
				<input class="form-control" type='number' name='eCCVsons' maxlength='2' min='0' value="<?php echo $editedCVRow['sons'] ?>">
			</div>
		</div>
		
		<div class="form-group" >  <!-- Idiomas -->
			<?php
			$languageKeys = getDBcompletecolumnID('key', 'languages', 'id');
			$languageString = implode(', ', $languageKeys);
			
			$hashedLanguages = array_combine(getDBcolumnvalue('keyLanguage', 'userLanguages', 'userNIE', $editedCVRow['nie']), getDBcolumnvalue('level', 'userLanguages', 'userNIE', $editedCVRow['nie']));
			?>
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVlanguagesMerged"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title='<?php echo $languageString; ?>'></span> Sprachkenntnisse: * </label>
			<div class="col-sm-10">
				<input class="form-control" type='text' name='eCCVlanguagesMerged' value="<?php foreach ($hashedLanguages as $lang => $lv) { echo ($lang) . ':' . ($lv) . '|'; } ?>" data-role='tagsinput'>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Educación -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducation">
				Ausbildung: *
				<br><a href=<?php echo $_SERVER[SCRIPT_NAME] ?>?codvalue=<?php echo $editedCVRow[nie] ?>&hiddenGET=hAddEduc class="btn btn-info btn-xs">+ Ausbildung</a>
			</label>
			<div class="col-sm-10">
				<?php
				$educIDs = getDBcolumnvalue('idEdu', 'userEducations', 'userNIE', $editedCVRow['nie']);
				if(count($educIDs) == 0){
					echo 'Dieser Kandidat hat keine schriftliche Ausbildung. Wie ist das möglich?<br>';
				}
				else{
					$j = 0;
					foreach($educIDs as $i){
						?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<a class="btn btn-danger btn-xs pull-right glyphicon glyphicon-trash" href=<?php echo $_SERVER[SCRIPT_NAME] ?>?codvalue=<?php echo $editedCVRow[nie] ?>&hiddenGET=hDelEduc&hiddenID=<?php echo $i; ?> ></a>
								<h3 class="panel-title">Ausbildung # <?php echo ($j+1); ?></h3>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducTittle<?php echo $j; ?>">Titel</label>
									<div class="col-sm-10">
										<input class="form-control" type="text" name="eCCVeducTittle<?php echo $j; ?>" value="<?php echo getDBsinglefield(educTittle, userEducations, idEdu, $i); ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducCenter<?php echo $j; ?>">Anstalt: </label>
									<div class="col-sm-10">
										<input class="form-control" type="text" name="eCCVeducCenter<?php echo $j; ?>" value="<?php echo getDBsinglefield(educCenter, userEducations, idEdu, $i) ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducStart<?php echo $j; ?>">Beginn: </label>
									<div class="col-sm-4">
										<input class="form-control" type="date" name="eCCVeducStart<?php echo $j; ?>" value="<?php echo getDBsinglefield(educStart, userEducations, idEdu, $i); ?>">
									</div>
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducEnd<?php echo $j; ?>">Ende: </label>
									<div class="col-sm-4">
										<input class="form-control" type="date" name="eCCVeducEnd<?php echo $j; ?>" value="<?php echo getDBsinglefield(educEnd, userEducations, idEdu, $i); ?>">
									</div>
								</div>
							</div>
						</div>
						<?php 
						$j++;
					}//Foreach
					?>
					<input type="hidden" name="eCCVcontEduc" value="<?php echo $j; ?>" />
					<?php 
				}
				?>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Profesión -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcareer">Durchgeführten berufe: * </label>	<!-- Se puede omitir -->
			<div class="col-sm-10">
				<input class="form-control" type='text' name='eCCVcareer' value='<?php echo implode("|", getDBcolumnvalue('keyOccupation', 'userOccupations', 'userNIE', $editedCVRow['nie'])); ?>' data-role='tagsinput'>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Experiencia -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperience">
				Letzten jahren:
				<br><a href=<?php echo $_SERVER[SCRIPT_NAME] ?>?codvalue=<?php echo $editedCVRow[nie] ?>&hiddenGET=hAddExp class="btn btn-info btn-xs">+ Erfahrung</a>
			</label>
			<div class="col-sm-10">
				<?php
				$experIDs = getDBcolumnvalue('idExp', 'userExperiences', 'userNIE', $editedCVRow['nie']);
				if(count($experIDs) == 0){
					echo 'Dieser Kandidat hat keine schriftliche Erfahrungen.<br>';
				}
				else{
					$j = 0;
					foreach($experIDs as $i){
						?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<a class="btn btn-danger btn-xs pull-right glyphicon glyphicon-trash" href=<?php echo $_SERVER[SCRIPT_NAME] ?>?codvalue=<?php echo $editedCVRow[nie] ?>&hiddenGET=hDelExp&hiddenID=<?php echo $i; ?> ></a>
								<h3 class="panel-title">Erfahrung #<?php echo ($j+1); ?></h3>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperCompany<?php echo $j; ?>">Unternehmen: </label>
									<div class="col-sm-10">
										<input class="form-control" type="text" name="eCCVexperCompany<?php echo $j; ?>" value="<?php echo getDBsinglefield(expCompany, userExperiences, idExp, $i); ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperPos<?php echo $j; ?>">Stellung: </label>
									<div class="col-sm-10">
										<input class="form-control" type="text" name="eCCVexperPos<?php echo $j; ?>" value="<?php echo getDBsinglefield(expPosition, userExperiences, idExp, $i); ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperCity<?php echo $j; ?>">Stadt: </label>
									<div class="col-sm-4">
										<input class="form-control" type="text" name="eCCVexperCity<?php echo $j; ?>" value="<?php echo getDBsinglefield(expCity, userExperiences, idExp, $i); ?>">
									</div>
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperCountry<?php echo $j; ?>">Staat: </label>
									<div class="col-sm-4">
										<input class="form-control" type="text" name="eCCVexperCountry<?php echo $j; ?>" value="<?php echo getDBsinglefield(expCountry, userExperiences, idExp, $i); ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperStart<?php echo $j; ?>">Beginn: </label>
									<div class="col-sm-4">
										<input class="form-control" type="date" name="eCCVexperStart<?php echo $j; ?>" value="<?php echo getDBsinglefield(expStart, userExperiences, idExp, $i); ?>">
									</div>
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperEnd<?php echo $j; ?>">Ende: </label>
									<div class="col-sm-4">
										<input class="form-control" type="date" name="eCCVexperEnd<?php echo $j; ?>" value="<?php echo getDBsinglefield(expEnd, userExperiences, idExp, $i); ?>">
									</div>
								</div>
								<div class="form-group">
									<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperDesc<?php echo $j; ?>">Beschreibung: </label>
									<div class="col-sm-10">
										<input class="form-control" type="text" name="eCCVexperDesc<?php echo $j; ?>" value="<?php echo getDBsinglefield(expDescription, userExperiences, idExp, $i); ?>">
									</div>
								</div>
							</div>
						</div>
						<?php 
						$j++;
					}//Foreach
					?>
					<input type="hidden" name="eCCVcontExp" value=<?php echo $j; ?>" />
					<?php 
				}
				?>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Salario Deseado -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsalary">Gehaltsvorstellung: </label>
			<div class="col-sm-10 input-group">
				<input class="form-control" type='text' name='eCCVsalary' maxlength='7' value="<?php echo ($editedCVRow['salary']) ?>" onkeypress="return checkOnlyNumbers(event)">
				<span class="input-group-addon">€ net/year</span>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Otros Detalles -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVotherDetails">Zusatzinfo: </label>
			<div class="col-sm-10">
				<textarea class="form-control" name='eCCVotherDetails'><?php echo ($editedCVRow['otherDetails']); ?></textarea>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Ficheros -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVfiles">zusätzliche Dokumente: </label>		
			<div class="col-sm-10">
				<?php
				$userFilesArray  = scandir($userFilesDir);
				foreach($userFilesArray as $value){
					if(preg_match("/\w+/i", $value)){
						echo "<a class='btn btn-danger btn-xs' href='$_SERVER[SCRIPT_NAME]?codvalue=" . $editedCVRow[userLogin] . "&dFile=" . $value . "&hiddenGET=hDelCVFile' onclick='return confirmDelCVFile(\"" . getCurrentLanguage($_SERVER['SCRIPT_NAME']) . "\");'><span class='glyphicon glyphicon-remove'></span></a>&nbsp";
						echo "<a href=/de/home/downloadFileSingle.php?doc=".$userFilesDir.$value.">$value</a><br>";
					}
				}
				?>
				<div id="uploadFiles" class="col-sm9">
					<input class="form-control" type="file" name="candidatFiles[]" multiple="multiple">
				</div>
			</div>
		</div>
		
		<div class="panel panel-default"> <!-- 10 Habilidades -->
			<div class="panel-heading">
				<h3 class="panel-title">Wesentliche berufliche Inhalte</h3>
			</div>
			<div class="panel-body">
				<?php
				for ($i=1; $i <= 10; $i++) { 
					echo "<div class='form-group' >  <!-- Habilidad ".$i." -->";
					echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVskill".$i."'>#".$i.": </label>";
					echo "	<div class='col-sm-10'>";
					echo "		<input class='form-control' type='text' name='eCCVskill".$i."' value='".($editedCVRow["skill$i"])."'>";
					echo "	</div>";
					echo "</div>";
				}
				?>
			</div>
		</div>
		
		<div class="form-group"> <!-- Comentarios -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcomments">Kommentare: </label>	
			<div class="col-sm-10">
				<textarea class="form-control" type='text' rows='5' name='eCCVcomments' value="<?php echo ($editedCVRow['comments']) ?>"><?php echo ($editedCVRow['comments']) ?></textarea>
			</div>
		</div>
		
		<div class="form-group" >  <!-- Estado del Candidato y Fecha de CV -->
			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcandidateStatus">Zustand des kandidaten: * </label>	
			<div class="col-sm-4">
				<select class="form-control" name="eCCVcandidateStatus" >
					<?php 
					$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
					$candStatus = getDBcompletecolumnID($userLang, 'candidateStatus', $userLang);
					echo "<option value=''>-- wählen --</option>";
					foreach($candStatus as $i){
						$keyCandidate = getDBsinglefield('key', 'candidateStatus', $userLang, $i);
						if($keyCandidate == $editedCVRow['candidateStatus']){
							echo "<option selected value=" . $keyCandidate . ">" . $i . "</option>";
						}
						else{
							echo "<option value=" . $keyCandidate . ">" . $i . "</option>";
						}
					}
					?>
				</select>
			</div>

			<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcvDate">Datum CV: </label>
			<div class="col-sm-4">
				<input class="form-control" type='text' name='eCCVcvDate' value="<?php echo ($editedCVRow['cvDate']) ?>" readonly>
			</div>
		</div>
		
	</div> <!-- class="modal-body" -->
	
	<?php 
}
?>