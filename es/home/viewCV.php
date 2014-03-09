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
	
	<title>Visualización de CV</title>
	
	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">
	<!-- <link href="../../common/css/styles.css" rel="stylesheet"> -->
	<!-- <link href="../common/css/docs.css" rel="stylesheet"> -->

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../../common/img/apple-touch-icon.png">
	

</head>

<body>
	<?php
	
	$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
	if (!$_SESSION['loglogin']){
		?>
		<script type="text/javascript">
			window.location.href='../index.html';
		</script>
		<?php
	}
	else {
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-j H:i:s');
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
		</div> <!-- exitRequest Modal -->




		<!-- /* En $myFile guardo el nombre del fichero php que WC est?tratando en ese instante. Necesario para mostrar
		* el resto de men?s de nivel 1 cuando navegue por ellos, y saber cu? es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();
		?>


		<?php
		$nota=$_POST['nota'];

		$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
		$imagen_o=$output_dir.$fila['userLogin']."/fotor.jpg";
		$report=$_GET[reportType];
		$id_ac=$_GET["id_b"];
		$id_aco=$_GET["id_bb"];
		$id =unserialize($_SESSION["id"]);
		$id_o =unserialize($_SESSION["id_o"]);
		$custom_elements =unserialize($_SESSION["custom"]);
		$n_custom_elements = count($custom_elements);
		if(strlen($id_ac)>0){$actual=$id[$id_ac];$ida=$id_ac;}
		if(strlen($id_aco)>0){$actual=$id_o[$id_aco];$ida=$id_aco;}
		$i=0;
		foreach ($id_o as $valor){
			if($valor == $actual){
				$ind_a=$i;
				$h=$i-1;
				$ind_p=$h;
				$j=$i+1;
				$ind_n=$j;
			}
			$i++;
		}
		$enlace = connectDB();
		$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";

		$consulta = "SELECT * from cvitaes where nie like '$actual'" ;
		if ($resultado = mysqli_query($enlace, $consulta)) {
			$texto = "";
			while ($fila = $resultado->fetch_assoc()) {
				$pagetext=$fila['userLogin'];
				$currentName = $fila['name']." ".$fila[surname];
				$id[$fila['id']] = $fila['nie'];
				if ($fila['sex']==0){ $fila['sex'] = "hombre"; }
				if ($fila['sex']==1){ $fila['sex'] = "mujer"; }

				// Añadido tras el merge de Miguel Hita
				$texto_pdf="<html>
	<head>
	<style>
    @page { 
		margin-bottom:80px;
		margin-right:20px;
		margin-left:20px; }
    #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 150px;  text-align: center; }
    #footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 150px; }
    #footer .page:after { content: counter(page, upper-roman); }
	#foto { position: fixed; left: 650px; top: 170px; right: 0px;} 
	#cuerpo { position: fixed; left: 50px; top: 90px;}
	.cuadronegro { background-position: 10px center;   background-repeat: no-repeat;   font-family: Tahoma;   font-size: 14px;   margin: 10px 0px;   padding: 15px 10px 15px 55px; } .cuadronegro { background-color: #A4A4A4;  color: #000000; border:2px solid #242424; border-radius: 2px 2px 2px 2px; }
  </style>";
		if($report == "full_report"){
				$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='100px'/></div>";
				$imagen=$fila['userLogin']."/fotor.jpg";
				$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='150px'/></div>";
				$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='100px' height='100px'/><br>";

				//print"<img src=../../cvs/".$imagen." width=\"100px\" height=\"100px\"\/>";
					
				// Añadido tras el merge de Miguel Hita
				$texto_pdf=$texto_pdf."<div id=cuerpo>";
				$texto_pdf=$texto_pdf."<h2>".dropAccents($fila[name])." ".dropAccents($fila[surname])."</h2>";
				$texto_pdf=$texto_pdf."<b>Fecha de Nacimiento</b>: ".$fila[birthdate]."<br>";
				$texto_pdf=$texto_pdf."<b>Nacionalidad:</b> ".$fila[nationalities]."<br>";
				$texto_pdf=$texto_pdf."<b>NIF/NIE:</b> ".$fila[nie]."<br>";
				$texto_pdf=$texto_pdf."<b>Direccion:</b> ".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum]." ".$fila[portal]." ".$fila[stair]."<br>";
				$texto_pdf=$texto_pdf."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto_pdf=$texto_pdf."<b>Telefono Fijo:</b> ".$fila[phone]."<br>";
				$texto_pdf=$texto_pdf."<b>Telefono Movil:</b> ".$fila[mobile]."<br>";
				$texto_pdf=$texto_pdf."<b>Correo Electronico:</b> ".$fila[mail]."<br><br>";
				$texto=$texto."<h2>".$fila[name]." ".$fila[surname]."</h2>";
				$texto=$texto."<b>Fecha de Nacimiento</b>: ".$fila[birthdate]."<br>";
				$texto=$texto."<b>Nacionalidad:</b> ".$fila[nationalities]."<br>";
				$texto=$texto."<b>NIF/NIE:</b> ".$fila[nie]."<br>";
				$texto=$texto."<b>Direccion:</b> ".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum]." ".$fila[portal]." ".$fila[stair]."<br>";
				$texto=$texto."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto=$texto."<b>Telefono Fijo:</b> ".$fila[phone]."<br>";
				$texto=$texto."<b>Telefono Movil:</b> ".$fila[mobile]."<br>";
				$texto=$texto."<b>Correo Electronico:</b> ".$fila[mail]."<br><br>";
				$texto=$texto."<img src='../../common/img/experiencia_laboral.jpg' /><br>";
				$exp_start_a = explode("|", $fila[experStart]);
				$exp_end_a = explode("|", $fila[experEnd]);
				$exp_pos_a = explode("|", $fila[experPos]);
				$exp_desc_a = explode("|", $fila[experDesc]);
				$exp_comp_a = explode("|", $fila[experCompany]);
				$tot=count($exp_start_a);
				$texto_pdf=$texto_pdf."<table>";
				$texto = $texto . "<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td></tr>";
				}
				$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$texto = $texto."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$educ_a = explode("|", $fila[education]);
				$tot=count($educ_a);
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
				}
				$lang_a = explode("|", $fila[language]);
				$langT_a = explode("|", $fila[langLevel]);
				$tot=count($lang_a);
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto = $texto."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<table>";
				$texto=$texto."<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				$texto= $texto."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				}
				$texto_pdf=$texto_pdf."</table><br><br>";
				$texto=$texto."</table><br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<b>-Tipo de carne de conducir y Fecha :</b>  ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto_pdf=$texto_pdf."<b>-Hijos: </b> ".$fila[sons]."<br>";
				$texto= $texto."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto=$texto."<b>-Tipo de carne de conducir y Fecha :</b> ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto=$texto."<b>-Hijos: </b> ".$fila[sons]."<br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				$texto= $texto."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				for($i=0;$i<10;$i++){
				$skill="skill".$i;
				$texto_pdf = $texto_pdf."<br>".dropAccents($fila[$skill]);
				$texto= $texto."<br>".dropAccents($fila[$skill]);
				}
                                $texto = $texto."<br><br><img src='../../common/img/archivos.jpg' /><br>";
                                $files  = scandir($output_dir.$fila[userLogin]);
                                foreach ($files as $value){
                                if (preg_match("/\w+/i", $value)) {
                                $texto=$texto."<br><a href=downloadFileSingle.php?doc=".$value.">$value</a>";
                                }
                                }
				if (strlen($nota)>0){$texto=$texto."<div class=cuadronegro><h3>EVALUACION PERSPECTIVA ALEMANIA </h3><br>".$nota."</div>";$texto_pdf=$texto_pdf."<div class=cuadronegro><h3>EVALUACION POR PERSPECTIVA ALEMANIA</h3> <br><br>".$nota."</div>";}
				$texto_pdf=$texto_pdf."</div>";
											$dompdf = new DOMPDF();
						require_once "dompdf_config.inc.php";
										$dompdf->load_html($texto_pdf);
										$dompdf->render();
									
				$font = Font_Metrics::get_font("helvetica", "bold");
				$canvas = $dompdf->get_canvas();
				$canvas->page_text(355, 750,$pagetext, $font, 10, array(0,0,0));

				$output = $dompdf->output();
				file_put_contents($output_dir.$pagetext.".pdf", $output);
				}
				if($report == "blind_report"){
				$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='100px'/></div>";
				$imagen=$fila['userLogin']."/fotor.jpg";
				$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='150px'/></div>";
				$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='100px' height='100px'/><br>";

				//print"<img src=../../cvs/".$imagen." width=\"100px\" height=\"100px\"\/>";
					
				// Añadido tras el merge de Miguel Hita
				$texto_pdf=$texto_pdf."<div id=cuerpo>";
				$texto_pdf=$texto_pdf."<b>Fecha de Nacimiento:</b><br>";
				$texto_pdf=$texto_pdf."<b>Nacionalidad:</b><br>";
				$texto_pdf=$texto_pdf."<b>NIF/NIE:</b><br>";
				$texto_pdf=$texto_pdf."<b>Direccion:</b><br>";
				$texto_pdf=$texto_pdf."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto_pdf=$texto_pdf."<b>Telefono Fijo:</b><br>";
				$texto_pdf=$texto_pdf."<b>Telefono Movil:</b><br>";
				$texto_pdf=$texto_pdf."<b>Correo Electronico:</b><br><br>";
				$texto=$texto."<h2>".$fila[name]." ".$fila[surname]."</h2>";
				$texto=$texto."<b>Fecha de Nacimiento</b>: ".$fila[birthdate]."<br>";
				$texto=$texto."<b>Nacionalidad:</b> ".$fila[nationalities]."<br>";
				$texto=$texto."<b>NIF/NIE:</b> ".$fila[nie]."<br>";
				$texto=$texto."<b>Direccion:</b> ".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum]." ".$fila[portal]." ".$fila[stair]."<br>";
				$texto=$texto."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto=$texto."<b>Telefono Fijo:</b> ".$fila[phone]."<br>";
				$texto=$texto."<b>Telefono Movil:</b> ".$fila[mobile]."<br>";
				$texto=$texto."<b>Correo Electronico:</b> ".$fila[mail]."<br><br>";
				$texto=$texto."<img src='../../common/img/experiencia_laboral.jpg' /><br>";
				$exp_start_a = explode("|", $fila[experStart]);
				$exp_end_a = explode("|", $fila[experEnd]);
				$exp_pos_a = explode("|", $fila[experPos]);
				$exp_desc_a = explode("|", $fila[experDesc]);
				$exp_comp_a = explode("|", $fila[experCompany]);
				$tot=count($exp_start_a);
				$texto_pdf=$texto_pdf."<table>";
				$texto = $texto . "<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td></tr>";
				}
				$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$texto = $texto."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$educ_a = explode("|", $fila[education]);
				$tot=count($educ_a);
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
				}
				$lang_a = explode("|", $fila[language]);
				$langT_a = explode("|", $fila[langLevel]);
				$tot=count($lang_a);
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto = $texto."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<table>";
				$texto=$texto."<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				$texto= $texto."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				}
				$texto_pdf=$texto_pdf."</table><br><br>";
				$texto=$texto."</table><br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<b>-Tipo de carne de conducir y Fecha :</b>  ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto_pdf=$texto_pdf."<b>-Hijos: </b> ".$fila[sons]."<br>";
				$texto= $texto."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto=$texto."<b>-Tipo de carne de conducir y Fecha :</b> ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto=$texto."<b>-Hijos: </b> ".$fila[sons]."<br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				$texto= $texto."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				for($i=0;$i<10;$i++){
				$skill="skill".$i;
				$texto_pdf = $texto_pdf."<br>".dropAccents($fila[$skill]);
				$texto= $texto."<br>".dropAccents($fila[$skill]);
				}
                                $texto = $texto."<br><br><img src='../../common/img/archivos.jpg' /><br>";
                                $files  = scandir($output_dir);
                                foreach ($files as $value){
                                if (preg_match("/\w+/i", $value)) {
                                echo "<a href=downloadFileSingle.php?doc=".$value.">$value</a><br>";
                                }
                                }
				if (strlen($nota)>0){$texto=$texto."<div class='cuadronegro'><h3>EVALUACION PERSPECTIVA ALEMANIA </h3><br>".$nota."</div>";$texto_pdf=$texto_pdf."<div class='cuadronegro'><h3>EVALUACION POR PERSPECTIVA ALEMANIA</h3> <br><br>".$nota."</div>";}
				$texto_pdf=$texto_pdf."</div>";
										$dompdf = new DOMPDF();
						require_once "dompdf_config.inc.php";
										$dompdf->load_html($texto_pdf);
										$dompdf->render();
									
				$font = Font_Metrics::get_font("helvetica", "bold");
				$canvas = $dompdf->get_canvas();
				$canvas->page_text(355, 750,$pagetext, $font, 10, array(0,0,0));

				$output = $dompdf->output();
				file_put_contents($output_dir.$pagetext.".pdf", $output);
				}

				if ($report == "custom_report"){

							$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
		include 'Cezpdf.php';
							class Creport extends Cezpdf{
								function Creport($p,$o){
									$this->__construct($p, $o,'none',array());
								}
							}
				$reportType=custom_report;
			
				$pdf = new Cezpdf('A4'); // Seleccionamos tipo de hoja para el informe
				$pdf->selectFont('fonts/Helvetica.afm'); //seleccionamos fuente a utilizar
				$pdf->ezImage("../common/img/logo.png",0,200,'none','center');
				$pdf_file_name = "";
				$pdf_file_name = $fila['userLogin'];
				$imagen_o=$output_dir.$fila['userLogin']."/fotor.jpg";
				$logo=$output_dir."/logo.png";
				$id[$fila['id']] = $fila['nie'];
				if ($fila['sex']==0){ $fila['sex'] = "hombre"; }
				if ($fila['sex']==1){ $fila['sex'] = "mujer"; }
				$pdf->ezImage("$imagen_o",0,200,'none','right');
				while (list($clave, $valor) = each($fila)) {
				
				foreach( $custom_elements  as $value ) {
				if($clave == $value){
				$texto=$texto."<b>$clave</b> $valor<br>";
						$pdf->ezText("<b>$clave</b> $valor");}
				}
				}
					
				$documento_pdf = $pdf->ezOutput();
									
									$nf=$output_dir.$pdf_file_name.".pdf";
									$fichero = fopen($nf,'wb') or die ("No se abrio $nf") ;
									fwrite ($fichero, $documento_pdf);
									fclose ($fichero);
				$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='100px'/></div>";
				$imagen=$fila['userLogin']."/fotor.jpg";
				$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='150px'/></div>";
				$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='100px' height='100px'/><br>";

				//print"<img src=../../cvs/".$imagen." width=\"100px\" height=\"100px\"\/>";
					
				// Añadido tras el merge de Miguel Hita
				$texto_pdf=$texto_pdf."<div id=cuerpo>";
				$texto_pdf=$texto_pdf."<b>Fecha de Nacimiento:</b><br>";
				$texto_pdf=$texto_pdf."<b>Nacionalidad:</b><br>";
				$texto_pdf=$texto_pdf."<b>NIF/NIE:</b><br>";
				$texto_pdf=$texto_pdf."<b>Direccion:</b><br>";
				$texto_pdf=$texto_pdf."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto_pdf=$texto_pdf."<b>Telefono Fijo:</b><br>";
				$texto_pdf=$texto_pdf."<b>Telefono Movil:</b><br>";
				$texto_pdf=$texto_pdf."<b>Correo Electronico:</b><br><br>";
				$texto=$texto."<h2>".$fila[name]." ".$fila[surname]."</h2>";
				$texto=$texto."<b>Fecha de Nacimiento</b>: ".$fila[birthdate]."<br>";
				$texto=$texto."<b>Nacionalidad:</b> ".$fila[nationalities]."<br>";
				$texto=$texto."<b>NIF/NIE:</b> ".$fila[nie]."<br>";
				$texto=$texto."<b>Direccion:</b> ".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum]." ".$fila[portal]." ".$fila[stair]."<br>";
				$texto=$texto."<b>Poblacion:</b> ".$fila[province]."<br>";
				$texto=$texto."<b>Telefono Fijo:</b> ".$fila[phone]."<br>";
				$texto=$texto."<b>Telefono Movil:</b> ".$fila[mobile]."<br>";
				$texto=$texto."<b>Correo Electronico:</b> ".$fila[mail]."<br><br>";
				$texto=$texto."<img src='../../common/img/experiencia_laboral.jpg' /><br>";
				$exp_start_a = explode("|", $fila[experStart]);
				$exp_end_a = explode("|", $fila[experEnd]);
				$exp_pos_a = explode("|", $fila[experPos]);
				$exp_desc_a = explode("|", $fila[experDesc]);
				$exp_comp_a = explode("|", $fila[experCompany]);
				$tot=count($exp_start_a);
				$texto_pdf=$texto_pdf."<table>";
				$texto = $texto . "<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".$exp_start_a[$i]."/".$exp_end_a[$i]."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td></tr>";
				}
				$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$texto = $texto."<br><br></table><img src='../../common/img/formacion.jpg' /><br><br>";
				$educ_a = explode("|", $fila[education]);
				$tot=count($educ_a);
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
				}
				$lang_a = explode("|", $fila[language]);
				$langT_a = explode("|", $fila[langLevel]);
				$tot=count($lang_a);
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto = $texto."<br><br><img src='../../common/img/idiomas.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<table>";
				$texto=$texto."<table class='table table-striped table-hover'>";
				for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				$texto= $texto."<tr><td><center><b>-".$lang_a[$i].".</b></td><td><b>".$langT_a[$i]."</b><br></center></td></tr>";
				}
				$texto_pdf=$texto_pdf."</table><br><br>";
				$texto=$texto."</table><br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto_pdf=$texto_pdf."<b>-Tipo de carne de conducir y Fecha :</b>  ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto_pdf=$texto_pdf."<b>-Hijos: </b> ".$fila[sons]."<br>";
				$texto= $texto."<br><br><img src='../../common/img/interes.jpg' /><br><br>";
				$texto=$texto."<b>-Tipo de carne de conducir y Fecha :</b> ".$fila[drivingType]."/".$fila[drivingDate]."<br>";
				$texto=$texto."<b>-Hijos: </b> ".$fila[sons]."<br><br>";
				$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				$texto= $texto."<br><br><img src='../../common/img/palabras.jpg' /><br>";
				for($i=0;$i<10;$i++){
				$skill="skill".$i;
				$texto_pdf = $texto_pdf."<br>".dropAccents($fila[$skill]);
				$texto= $texto."<br>".dropAccents($fila[$skill]);
				}
                                $texto = $texto."<br><br><img src='../../common/img/archivos.jpg' /><br>";
                                $files  = scandir($output_dir.$fila[userLogin]);
                                foreach ($files as $value){
                                if (preg_match("/\w+/i", $value)) {
                                echo "<a href=downloadFileSingle.php?doc=".$value.">$value</a><br>";
                                }
                                }
				if (strlen($nota)>0){$texto=$texto."<div class='cuadronegro'><h3>EVALUACION PERSPECTIVA ALEMANIA </h3><br>".$nota."</div>";$texto_pdf=$texto_pdf."<div class='cuadronegro'><h3>EVALUACION POR PERSPECTIVA ALEMANIA</h3> <br><br>".$nota."</div>";}
				$texto_pdf=$texto_pdf."</div>";
				}
			}

	
		}
		

		


					
}					
						
		
		
		?>


		<div id="main-content" class="cvViewer bs-docs-container">
			<div class="row container-fluid cvViewer">
				<div class="panel panel-default cvViewer tooltip-demo col-md-8" role="main"> <!-- Panel -->
					<div class="btn-group pull-right">
						<?php 	if(strlen($id_o[$ind_p])>0) 
									echo "<a href='viewCV.php?id_bb=$ind_p&reportType=".$report."' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Anterior'><span class='glyphicon glyphicon-chevron-left'></span></a>";
								else 
									echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-left'></span></a>";
						?>
							<a href="<?php echo "../../cvs/".$pagetext.".pdf" ?>" target="_blank" class="btn btn-default btn-sm" data-toggle='tooltip' data-original-title='Descargar CV en PDF'><span class='glyphicon glyphicon-download-alt'></span></a>
						<?php 	if(strlen($id_o[$ind_n])>0) 
									echo "<a href='viewCV.php?id_bb=$ind_n&reportType=".$report."' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Siguiente'><span class='glyphicon glyphicon-chevron-right'></span></a>";
								else 
									echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-right'></span></a>";

							$_SESSION["id_o"] = serialize($id_o);
							$_SESSION["id"] = serialize($id);
						?>
					</div>
					<div class="panel-heading">
						<h3 class="panel-title">CV de <?php echo $currentName;?></h3>
					</div>
					<div class="panel-body scrollable" > <!-- panel-body -->
						<?php echo $texto; ?>
					</div> <!-- panel-body -->
				</div> <!-- Panel -->

				<div class="panel panel-default col-md-3">
					<div class="panel-heading">
						<h3 class="panel-title">Añadir nota</h3>
					</div>
					<div class="panel-body" > <!-- panel-body -->
						<?php
							echo "<form name='formu' id='formu' class='form-horizontal' action='viewCV.php?id_b=".$ida."&reportType=".$report."' method='post' enctype='multipart/form-data'>";
							echo "<textarea class='form-control' name='nota' rows='10' cols='40'></textarea>";

							echo "<div id='form_submit' class='form-group pull-right' style='margin: 1px; margin-top: 10px;'>";
							echo "		<button type='submit' name='enviar' class='btn btn-primary' onclick='insert();'>Insertar nota   <span class='glyphicon glyphicon-pencil'> </span></button>";
							echo "</div>";
							
						
						
						?>
					</div> <!-- panel-body -->
				</div>
			</div>
		</div>


	


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


