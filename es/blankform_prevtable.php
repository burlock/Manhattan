<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Utilizamos fuentes desde Google Fonts API, sin necesidad de descargarlas -->
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Formulario</title>
	<!-- <link href="./teststyle.css" rel="stylesheet" type="text/css"> -->
	<script src="../library/functions.js" type="text/javascript"></script>
</head>

<body>

	<?php
	
	require_once ('../library/functions.php');
	
	?>
	<!-- Podemos poner un asteristo para los campos que sean obligatorios y un comentario en alg�n lado indic�ndolo -->
	<div id="stylized" class="myform">
		<h1>Formulario</h1>
		<form id="form" name="form" method="post" action="checkCVform.php" autocomplete="off" enctype="multipart/form-data">
			
			<table>
				<tr>
					<td>Nombre</td>
					<td><input type="text" name="blankname" size="20" maxlength="20" /></td>
				</tr>
				<td>Apellidos</td>
				<td><input type="text" name="blanksurname" size="20" maxlength="30" /></td>
				
				<!-- �No deber�an incluir un campo llamado "Sexo"? -->
				
				<td>DNI/Pasaporte</td>
				<td><input type="text" name="blankdni" size="10" maxlength="10" /></td>
				
				<td>Direcci�n</td>
				<td><select name="blankstreettype">
					<option value="0">-- Tipo --</option>
					<option value="1">Acceso</option>
					<option value="2">Acera</option>
					<option value="3">Alameda</option>
					<option value="4">Autopista</option>
					<option value="5">Autov�a</option>
					<option value="6">Avenida</option>
					<option value="7">C. Comercial</option>
					<option value="8">Calle</option>
					<option value="9">Callej�n</option>
					<option value="10">Camino</option>
					<option value="11">Ca�ada</option>
					<option value="12">Carrer</option>
					<option value="13">Carrera</option>
					<option value="14">Carretera</option>
					<option value="15">Cuesta</option>
					<option value="16">Glorieta</option>
					<option value="17">Pasadizo</option>
					<option value="18">Pasaje</option>
					<option value="19">Paseo</option>
					<option value="20">Plaza</option>
					<option value="21">Rambla</option>
					<option value="22">Ronda</option>
					<option value="23">Sendero</option>
					<option value="24">Traves�a</option>
					<option value="25">Urbanizaci�n</option>
					<option value="26">V�a</option>
				</select>
				<input type="text" name="blankstreetname" size="50" maxlength="50" placeholder="Nombre" />
				<input type="text" name="blankstreetnum" size="5" maxlength="10" placeholder="Num" />
				<input type="text" name="blankstreetfloor" size="5" maxlength="10" placeholder="Piso" /><br>
				
				<td>Tel�fono Fijo</td>
				<input type="text" name="blankphone" size="15" maxlength="12" /><br>
				
				<td>Tel�fono M�vil</td>
				<input type="text" name="blankmobile" size="15" maxlength="12" /><br>
				
				<td>Correo Electr�nico</td>
				<input type="email" name="blankmail" size="30" 	placeholder="correo@ejemplo.com" /><br>
				
				<td>Carn� de Conducir</td>
				<select name="blankdrivingtype">
					<option value="0"> Tipo </option>
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
				<input type="date" name="blankdrivingdate" /><br>
				
				<td>Estado Civil</td>
				<select name="blankcivil">
					<option value="0">-- Estado --</option>
					<option value="1">Soltero/a</option>
					<option value="2">Casado/a</option>
					<option value="3">Divorciado/a</option>
					<option value="4">Viudo/a</option>
					<option value="5">Separado/a</option>
				</select><br>
				
				<td>Hijos</td>
				<!-- 0<input type="range" name="blanksons" min="0" max="10" />10<br> -->
				<select name="blanksons">
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select><br>
				<!-- Hemos interpretado que no es posible tener m�s de 10 hijos, aunque es modificable -->
				<!-- Este tipo no est� soportado por IE -->
				
				<td>Foto</td>
				<!--  <input type="text" name="blankphoto" size="10" placeholder="desplegable" /><br> -->
				<input type="file" name="blankphoto"><br>
				
				<td>Curriculum en Word</td>
				<!-- <input type="text" name="blankcv" size="10" placeholder="desplegable" /><br> -->
				<input type="file" name="blankcv"><br>
				
				<td>Nivel de Idiomas</td>
				<input type="text" name="blanklanguage" size="10" placeholder="desplegable" />
				<a href="javascript:newMail();">Agregar</a><br>
				
				<td>Profesi�n</td>
				<input type="text" name="blankjob" size="10" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
				<!-- Aunque para este campo tambi�n piden que sea m�ltiple y sin l�mite, no tiene sentido tener 2 profesiones a la vez. Preguntar. -->
				
				<td>Formaci�n</td>
				<input type="text" name="blanktittles" size="10" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
				
				<td>Experiencia Laboral</td>
				<input type="text" name="blankexptime" size="10" placeholder="desplegable" />
				<input type="text" name="blankexppos" size="30" placeholder="desplegable" />
				<input type="text" name="blankexpdesc" size="50" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
				
				<td>Otros Detalles de Inter�s</td>
				<input type="text" name="blankother" size="50" placeholder="desplegable" /><br>
				
				<td>Las 10 palabras que mejor me definen son...</td><br>
				<input type="text" name="blankword1" size="20" /><br>
				<input type="text" name="blankword2" size="20" /><br>
				<input type="text" name="blankword3" size="20" /><br>
				<input type="text" name="blankword4" size="20" /><br>
				<input type="text" name="blankword5" size="20" /><br>
				<input type="text" name="blankword6" size="20" /><br>
				<input type="text" name="blankword7" size="20" /><br>
				<input type="text" name="blankword8" size="20" /><br>
				<input type="text" name="blankword9" size="20" /><br>
				<input type="text" name="blankword10" size="20" /><br>
			
			<input type="checkbox" name="blanklopd" /> He le�do y acepto las condiciones de uso y pol�tica de privacidad<br>
			
			<button name="senduser" type="submit">Enviar solicitud</button>
			
			</table>
		</form>
		
	</div>
	
	</body>
</html>