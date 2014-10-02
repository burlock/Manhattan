<?php session_start();

if (!$_SESSION['loglogin']){
	?>
	<script type="text/javascript">
		window.location.href='index.html';
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
			window.location.href='endsession.php';
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

	$value = intval($_GET['value']);

	$xmlPostalCodes = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/common/data/postal_codes.xml');
	$nodes = $xmlPostalCodes->xpath('//CodigoPostal[@value="'.$_GET['value'].'"]');
	$villageSet = $nodes[0];

	if($villageSet->count() > 1){
		echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcity" style="padding-right: 10px;">Localidad: </label><select class="form-control" name="blankaddrcity" id="blankaddrcity" style="margin-top:5px; width:60%">';
			echo'<option value="">Su localidad...</option>';
			foreach($villageSet->municipio as $j){
				echo "<option value='" . $j['nombre'] . "'>".$j['nombre']."</option>";
			}
		echo '</select><br>';
	}
	else{
		echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcity" style="padding-right: 10px;">Localidad: </label><input class="form-control" type="text" name="blankaddrcity" size="50" value="' . $villageSet->municipio['nombre'] . '" readonly style="margin-top:5px;"><br>';
	}
	// De la población seleccionada por el CP, extraemos su nodo padre en el árbol XML. El pop es para extraer el primer y único elemento.
	$provinceNode = array_pop($villageSet->xpath('parent::*'));
	// Ahora, del nodo padre de nuestro municipio (provincia) extraemos su nombre
	$provinceName = $provinceNode['nombre'];
	echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrprovince" style="padding-right: 10px;">Provincia: </label><input class="form-control" type="text" name="blankaddrprovince" size="20" value="' . $provinceName . '" readonly style="margin-top:5px;"><br>';

	echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcountry" style="padding-right: 10px;">País: </label><input class="form-control" type="text" name="blankaddrcountry" size="20" value="España" readonly style="margin-top:5px;"><br>';
}
?>
