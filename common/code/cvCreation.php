<?php
$nota=$_POST['nota'];

$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
$imagen_o=$output_dir.$fila['userLogin']."/fotor.jpg";
$report=$_GET[reportType];
$id_ac=$_GET["id_b"];
$id_aco=$_GET["id_bb"];
$id =unserialize($_SESSION["id"]);
$id_o =unserialize($_SESSION["id_o"]);
if(strlen($id_ac)>0){
	$actual=$id[$id_ac];
	$ida=$id_ac;
}
if(strlen($id_aco)>0){
	$actual=$id_o[$id_aco];
	$ida=$id_aco;
}
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
//Here is being recovered ALL the information from 1 CV. Then it will be treated as Full, Blind or Custom report
if ($resultado = mysqli_query($enlace, $consulta)) {
	$texto = "";
	while ($fila = $resultado->fetch_assoc()) {
		$pagetext=$fila['userLogin'];
		$curUserLogin = $fila['userLogin'];
		$id[$fila['id']] = $fila['nie'];
		if ($fila['sex']==0){
			$fila['sex'] = "hombre";
		}
		if ($fila['sex']==1){
			$fila['sex'] = "mujer";
		}
		
		// Añadido tras el merge de Miguel Hita
		$texto_pdf="<html>
			<head>
			<meta charset=UTF-8>
			<style>
			@page {
				margin-bottom:80px;
				margin-right:20px;
				margin-left:20px;
				margin-top:10px;
			}
			#header { position: fixed; left: 0px; top: 0px; right: 0px; height: 150px;  text-align: center; }
			#footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 150px; }
			#footer .page:after { content: counter(page, upper-roman); }
			#foto { position: fixed; left: 570px; top: 110px; right: 0px;}
			#cuerpo { position: fixed; left: 50px; top: 90px;}
			.cuadronegro { background-position: 10px center;   background-repeat: no-repeat;   font-family: Tahoma;   font-size: 14px;   margin-right: 50px;   padding: 15px 10px 15px 15px; }
			.cuadronegro { background-color: #D8D8D8;  color: #000000; border:2px solid #242424; border-radius: 10px 2px 2px 2px; }
			</style>";
		
		
		/* *********************************************
		 * *****  Block of code for Full Report  ***** *
		 * *********************************************/
		if($report == "full_report"){
			$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='80px'/></div>";
			$imagen=$fila['userLogin']."/fotor.jpg";
			$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='120px'/></div>";
			$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='140px'/><br>";
			
			$texto_pdf=$texto_pdf."<div id=cuerpo>";
			
			/* *****  Generating PDF & HTML Personal Info paragraph  ***** */
			$texto_pdf=$texto_pdf."<h2>".dropAccents($fila[name])." ".dropAccents($fila[surname])."</h2>";
			$texto_pdf = $texto_pdf."<table>";
				//$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".$fila[birthdate]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Staatsangehörigkeit:</b></td><td>".$fila[nationalities]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Personalausweis:</b></td><td>".$fila[nie]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Derzeitige Anschrift:</b></td><td>".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum].", ".$fila[portal]." ".$fila[stair]." ".$fila[addrFloor]." ".$fila[addrDoor]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Postleitzahl, Stadt:</b></td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Provinz:</b></td><td>".$fila[province]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Telefon:</b></td><td>".$fila[phone]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Mobiltelefon:</b></td><td>".$fila[mobile]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>E-Mail:</b></td><td>".$fila[mail]."</td></tr>";
			$texto_pdf = $texto_pdf."</table><br><br>";
			
			$texto=$texto."<h2>".$fila[name]." ".$fila[surname]."</h2>";
			$texto = $texto."<table>";
				//$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[birthdate]."</td></tr>";
				$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
				$texto=$texto."<tr><td><b>Staatsangehörigkeit:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[nationalities]."</td></tr>";
				$texto=$texto."<tr><td><b>Personalausweis:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[nie]."</td></tr>";
				$texto=$texto."<tr><td><b>Derzeitige Anschrift:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum].", ".$fila[portal]." ".$fila[stair]." ".$fila[addrFloor]." ".$fila[addrDoor]."</td></tr>";
				$texto=$texto."<tr><td><b>Postleitzahl, Stadt:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
				$texto=$texto."<tr><td><b>Provinz:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[province]."</td></tr>";
				$texto=$texto."<tr><td><b>Telefon:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[phone]."</td></tr>";
				$texto=$texto."<tr><td><b>Mobiltelefon:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[mobile]."</td></tr>";
				$texto=$texto."<tr><td><b>E-Mail:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[mail]."</td></tr>";
			$texto = $texto."</table><br><br>";
			
			/* *****  Generating PDF & HTML Experience paragraph  ***** */
			$texto_pdf=$texto_pdf."<img src='../../common/img/Berufserfahrung.jpg' />";
			$texto=$texto."<img src='../../common/img/Berufserfahrung.jpg' />";
			
			//Creating 'Experience' array from string
			$exp_comp_a = explode("|", $fila[experCompany]);
			$exp_start_a = explode("|", $fila[experStart]);
			$exp_end_a = explode("|", $fila[experEnd]);
			$exp_pos_a = explode("|", $fila[experPos]);
			$expCity = explode("|", $fila[experCity]);
			$expCountry = explode("|", $fila[experCountry]);
			$exp_desc_a = explode("|", $fila[experDesc]);
			$tot=count($exp_start_a);
			
			$texto_pdf=$texto_pdf."<table>";
			$texto = $texto . "<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td><td>".$expCity[$i].", ".$expCountry[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td><td>".dropAccents($expCity[$i]).", ".dropAccents($expCountry[$i])."</td></tr>";
			}
			$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			$texto = $texto."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			
			/* *****  Generating PDF & HTML Education paragraph  ***** */
			/*
			$educ_a = explode("|", $fila[education]);
			
			$tot=count($educ_a);
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
			}
			*/
			$educTittles = explode("|", $fila['educTittle']);
			$educCenters = explode("|", $fila['educCenter']);
			$educStarts = explode("|", $fila['educStart']);
			$educEnds = explode("|", $fila['educEnd']);
			$numTittles = count($educTittles);
			
			$texto_pdf=$texto_pdf."<table>";
			$texto = $texto . "<table class='table table-striped table-hover'>";
			for($i=0; $i<$numTittles; $i++){
			/*			
			*/
				$texto_pdf = $texto_pdf."<tr><td><b>".date("m-Y", strtotime($educStarts[$i]))." / ".date("m-Y", strtotime($educEnds[$i]))."</b></td><td><b>".$educTittles[$i]."</b><br>".$educCenters[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".date("m-Y", strtotime($educStarts[$i]))." / ".date("m-Y", strtotime($educEnds[$i]))."</b></td><td><b>".dropAccents($educTittles[$i])."</b><br>".dropAccents($educCenters[$i])."</td></tr>";
			}
			$texto_pdf = $texto_pdf."<br></table>";//To ensure there are 2 <br> between paragraphs
			$texto = $texto."<br></table>";
			
			/* *****  Generating PDF & HTML Language paragraph  ***** */
			$lang_a = explode("|", $fila[language]);
			$langT_a = explode("|", $fila[langLevel]);
			$tot=count($lang_a);
			$texto_pdf = $texto_pdf."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			$texto = $texto."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			
			$texto_pdf=$texto_pdf."<table>";
			$texto=$texto."<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				/*
				$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b>&nbsp</td><td>".$langT_a[$i]."</td></tr>";
				$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
				*/
				if($langT_a[$i] == 'mothertongue'){
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
				}
				else{
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
				}
			}
			$texto_pdf=$texto_pdf."</table><br><br>";
			$texto=$texto."</table>";
			
			//Other interesting information
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Angaben.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
				/*
				$texto_pdf = $texto_pdf."<tr><td style='font-size:150%'><b>·</b></td><td>Führerschein und Ausstellungsdatum: </td><td> ".$fila[drivingType]." / ".$fila[drivingDate]."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td style='font-size:150%'><b>·</b></td><td>Familienstand: </td><td> ".$fila[marital]."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td style='font-size:150%'><b>·</b></td><td>Kinder: </td><td> ".$fila[sons]."</td></tr>";
				*/
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: </b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Familienstand: </b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Kinder: </b></td><td> ".$fila[sons]."</td></tr>";
			$texto_pdf = $texto_pdf."</table><br>";
			
			$texto = $texto."<br><br><img src='../../common/img/Angaben.jpg' />";
			$texto = $texto."<table class='table table-striped table-hover'>";
				/*
				$texto = $texto."<tr><td style='font-size:150%'><b>·</b></td><td>Führerschein und Ausstellungsdatum: &nbsp</td><td> ".$fila[drivingType]." / ".$fila[drivingDate]."</td></tr>";
				$texto = $texto."<tr><td style='font-size:150%'><b>·</b></td><td>Familienstand: &nbsp</td><td> ".$fila[marital]."</td></tr>";
				$texto = $texto."<tr><td style='font-size:150%'><b>·</b></td><td>Kinder: &nbsp</td><td> ".$fila[sons]."</td></tr>";
				*/
				$texto = $texto."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: &nbsp</b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Familienstand: &nbsp</b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Kinder: &nbsp</b></td><td> ".$fila[sons]."</td></tr>";
			$texto = $texto."</table><br>";
			
			//10 key points from my personal experience
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto= $texto."<img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
			$texto = $texto."<table class='table table-striped table-hover'>";
			for($i=0;$i<10;$i++){
				$skill="skill".$i;
				/*
				$texto_pdf = $texto_pdf."<br>".dropAccents($fila[$skill]);
				$texto= $texto."<br>".dropAccents($fila[$skill]);
				*/
				if(strlen($fila[$skill]) > 0){
					$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
					$texto = $texto."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
				}
			}
			$texto_pdf = $texto_pdf."</table>";
			$texto = $texto."</table>";
			
			//Perspectiva Alemania personal notes
			if (strlen($nota)>0){
				$texto=$texto."<div class=cuadronegro><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
				$texto_pdf=$texto_pdf."<div class=cuadronegro><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
			}
			
			$texto_pdf=$texto_pdf."</div>";
			
			$dompdf = new DOMPDF();
			
			require_once "dompdf_config.inc.php";
			
			$dompdf->load_html($texto_pdf);
			$dompdf->render();
			
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas = $dompdf->get_canvas();
			$canvas->page_text(280, 750, $pagetext, $font, 10, array(0,0,0));
			
			$output = $dompdf->output();
			file_put_contents($output_dir.$pagetext.".pdf", $output);
		}//End for "Full Report" block of code
		
		
		/* **********************************************
		 * *****  Block of code for Blind Report  ***** *
		 * **********************************************/
		if($report == "blind_report"){
			$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='80px'/></div>";
			$imagen=$fila['userLogin']."/fotor.jpg";
			$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='120px'/></div>";
			$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='140px'/><br>";
			
			$texto_pdf=$texto_pdf."<div id=cuerpo>";
			
			/* *****  Generating PDF & HTML Personal Info paragraph  ***** */
			$texto_pdf = $texto_pdf."<table>";
				//$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".$fila[birthdate]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Staatsangehörigkeit:</b></td><td>".$fila[nationalities]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Postleitzahl, Stadt:</b></td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
				$texto_pdf=$texto_pdf."<tr><td><b>Provinz:</b></td><td>".$fila[province]."</td></tr>";
			$texto_pdf = $texto_pdf."</table><br><br>";
			
			$texto = $texto."<table>";
				//$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[birthdate]."</td></tr>";
				$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
				$texto=$texto."<tr><td><b>Staatsangehörigkeit:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[nationalities]."</td></tr>";
				$texto=$texto."<tr><td><b>Postleitzahl, Stadt:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
				$texto=$texto."<tr><td><b>Provinz:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[province]."</td></tr>";
			$texto = $texto."</table><br><br>";
			
			/* *****  Generating PDF & HTML Experience paragraph  ***** */
			$texto_pdf=$texto_pdf."<img src='../../common/img/Berufserfahrung.jpg' />";
			$texto=$texto."<img src='../../common/img/Berufserfahrung.jpg' />";
			
			//Creating 'Experience' array from string
			$exp_comp_a = explode("|", $fila[experCompany]);
			$exp_start_a = explode("|", $fila[experStart]);
			$exp_end_a = explode("|", $fila[experEnd]);
			$exp_pos_a = explode("|", $fila[experPos]);
			$expCity = explode("|", $fila[experCity]);
			$expCountry = explode("|", $fila[experCountry]);
			$exp_desc_a = explode("|", $fila[experDesc]);
			$tot=count($exp_start_a);
			
			$texto_pdf=$texto_pdf."<table>";
			$texto = $texto . "<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td><td>".$expCity[$i].", ".$expCountry[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td><td>".dropAccents($expCity[$i]).", ".dropAccents($expCountry[$i])."</td></tr>";
			}
			$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			$texto = $texto."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			
			/* *****  Generating PDF & HTML Education paragraph  ***** */
			$educ_a = explode("|", $fila[education]);
			$tot=count($educ_a);
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
			}
			$texto_pdf = $texto_pdf."<br>";//To ensure there are 2 <br> between paragraphs
			$texto = $texto."<br>";
			
			/* *****  Generating PDF & HTML Language paragraph  ***** */
			$lang_a = explode("|", $fila[language]);
			$langT_a = explode("|", $fila[langLevel]);
			$tot=count($lang_a);
			$texto_pdf = $texto_pdf."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			$texto = $texto."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			
			$texto_pdf=$texto_pdf."<table>";
			$texto=$texto."<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				if($langT_a[$i] == 'mothertongue'){
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
				}
				else{
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
				}
			}
			$texto_pdf=$texto_pdf."</table><br><br>";
			$texto=$texto."</table>";
			
			//Other interesting information
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Angaben.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: </b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Familienstand: </b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Kinder: </b></td><td> ".$fila[sons]."</td></tr>";
			$texto_pdf = $texto_pdf."</table><br>";
			
			$texto = $texto."<br><br><img src='../../common/img/Angaben.jpg' />";
			$texto = $texto."<table class='table table-striped table-hover'>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: &nbsp</b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Familienstand: &nbsp</b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Kinder: &nbsp</b></td><td> ".$fila[sons]."</td></tr>";
			$texto = $texto."</table><br>";
			
			//10 key points from my personal experience
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto= $texto."<img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
			$texto = $texto."<table class='table table-striped table-hover'>";
			for($i=0;$i<10;$i++){
				$skill="skill".$i;
				if(strlen($fila[$skill]) > 0){
					$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
					$texto = $texto."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
				}
			}
			$texto_pdf = $texto_pdf."</table>";
			$texto = $texto."</table>";
			
			//Perspectiva Alemania personal notes
			if (strlen($nota)>0){
				$texto=$texto."<div class='cuadronegro'><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
				$texto_pdf=$texto_pdf."<div class='cuadronegro'><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
			}
			
			$texto_pdf=$texto_pdf."</div>";
			
			$dompdf = new DOMPDF();
			
			require_once "dompdf_config.inc.php";
			
			$dompdf->load_html($texto_pdf);
			$dompdf->render();
			
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas = $dompdf->get_canvas();
			$canvas->page_text(280, 750, $pagetext, $font, 10, array(0,0,0));
			
			$output = $dompdf->output();
			file_put_contents($output_dir.$pagetext.".pdf", $output);
		}//End for "Blind Report" block of code
		
		
		/* ***********************************************
		 * *****  Block of code for Custom Report  ***** *
		 * ***********************************************/
		if($report == "custom_report"){
			$texto_pdf=$texto_pdf."<div id=header><img center src='../../common/img/logo.jpg' width='300px' height='80px'/></div>";
			$imagen=$fila['userLogin']."/fotor.jpg";
			$texto_pdf = $texto_pdf."<div id=foto><img src='../../cvs/".$imagen."' width='120px' height='120px'/></div>";
			$texto = $texto . "<img class='pull-right img-circle img-thumbnail' src='../../cvs/".$imagen."' width='140px'/><br>";
			
			$texto_pdf=$texto_pdf."<div id=cuerpo>";
			
			/* *****  Generating PDF & HTML Personal Info paragraph  ***** */
			//Checks that user has selected one or more of the checkbox fields
			if(isset($_SESSION['customReportChecks'])){
				$customArray = array();
				$customArray = $_SESSION['customReportChecks'];
				$texto_pdf = $texto_pdf."<table>";
				$texto = $texto."<table><br>";
				foreach($customArray as $i){
					switch ($i){
						case 'name':
							//As we really don't want to show Name+Surname as part of a table, we make it closing before it and restarting once again after showing it
							$texto_pdf = $texto_pdf."</table><br>";
							$texto = $texto."</table><br>";
							
							$texto_pdf=$texto_pdf."<h2>".dropAccents($fila[name])." ".dropAccents($fila[surname])."</h2>";
							$texto=$texto."<h2>".$fila[name]." ".$fila[surname]."</h2>";
							
							$texto_pdf = $texto_pdf."<table><br>";
							$texto = $texto."<table><br>";
						break;
						
						case 'birthdate':
							//$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".$fila[birthdate]."</td></tr>";
							//$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[birthdate]."</td></tr>";
							$texto_pdf=$texto_pdf."<tr><td><b>Geburtsdatum:</b></td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
							$texto=$texto."<tr><td><b>Geburtsdatum:</b></td><td>&nbsp;&nbsp;</td><td>".date("d-m-Y", strtotime($fila[birthdate]))."</td></tr>";
							
						break;
						
						case 'nationalities':
							$texto_pdf=$texto_pdf."<tr><td><b>Staatsangehörigkeit:</b></td><td>".$fila[nationalities]."</td></tr>";
							$texto=$texto."<tr><td><b>Staatsangehörigkeit:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[nationalities]."</td></tr>";
						break;
						
						case 'nie':
							$texto_pdf=$texto_pdf."<tr><td><b>Personalausweis:</b></td><td>".$fila[nie]."</td></tr>";
							$texto=$texto."<tr><td><b>Personalausweis:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[nie]."</td></tr>";
						break;
						
						case 'addrName':
							$texto_pdf=$texto_pdf."<tr><td><b>Derzeitige Anschrift:</b></td><td>".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum].", ".$fila[portal]." ".$fila[stair]." ".$fila[addrFloor]." ".$fila[addrDoor]."</td></tr>";
							$texto=$texto."<tr><td><b>Derzeitige Anschrift:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[addrType]." ".$fila[addrName]." ".$fila[addrNum].", ".$fila[portal]." ".$fila[stair]." ".$fila[addrFloor]." ".$fila[addrDoor]."</td></tr>";
						break;
						
						case 'city':
							$texto_pdf=$texto_pdf."<tr><td><b>Postleitzahl, Stadt:</b></td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
							$texto=$texto."<tr><td><b>Postleitzahl, Stadt:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[postalCode].", ".$fila[city]."</td></tr>";
						break;
						
						case 'province':
							$texto_pdf=$texto_pdf."<tr><td><b>Provinz:</b></td><td>".$fila[province]."</td></tr>";
							$texto=$texto."<tr><td><b>Provinz:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[province]."</td></tr>";
						break;
						
						case 'phone':
							$texto_pdf=$texto_pdf."<tr><td><b>Telefon:</b></td><td>".$fila[phone]."</td></tr>";
							$texto=$texto."<tr><td><b>Telefon:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[phone]."</td></tr>";
						break;
						
						case 'mobile':
							$texto_pdf=$texto_pdf."<tr><td><b>Mobiltelefon:</b></td><td>".$fila[mobile]."</td></tr>";
							$texto=$texto."<tr><td><b>Mobiltelefon:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[mobile]."</td></tr>";
						break;
						
						case 'mail':
							$texto_pdf=$texto_pdf."<tr><td><b>E-Mail:</b></td><td>".$fila[mail]."</td></tr>";
							$texto=$texto."<tr><td><b>E-Mail:</b></td><td>&nbsp;&nbsp;</td><td>".$fila[mail]."</td></tr>";
						break;
					}
				}//foreach
				$texto_pdf = $texto_pdf."</table><br><br>";
				$texto = $texto."</table><br><br>";
			}//isset
			else{
				$texto_pdf = $texto_pdf.'<h2>There is no chosen personal fields</h2>';
			}
			
			/* *****  Generating PDF & HTML Experience paragraph  ***** */
			$texto_pdf=$texto_pdf."<img src='../../common/img/Berufserfahrung.jpg' />";
			$texto=$texto."<img src='../../common/img/Berufserfahrung.jpg' />";
			
			//Creating 'Experience' array from string
			$exp_comp_a = explode("|", $fila[experCompany]);
			$exp_start_a = explode("|", $fila[experStart]);
			$exp_end_a = explode("|", $fila[experEnd]);
			$exp_pos_a = explode("|", $fila[experPos]);
			$expCity = explode("|", $fila[experCity]);
			$expCountry = explode("|", $fila[experCountry]);
			$exp_desc_a = explode("|", $fila[experDesc]);
			$tot=count($exp_start_a);
			
			$texto_pdf=$texto_pdf."<table>";
			$texto = $texto . "<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".$exp_comp_a[$i]."<br>".$exp_pos_a[$i]."</b><br>".$exp_desc_a[$i]."</td><td>".$expCity[$i].", ".$expCountry[$i]."</td></tr>";
				$texto = $texto."<tr><td><b>".date("m-Y", strtotime($exp_start_a[$i]))." / ".date("m-Y", strtotime($exp_end_a[$i]))."</b></td><td><b>".dropAccents($exp_comp_a[$i])."<br>".dropAccents($exp_pos_a[$i])."</b><br>".dropAccents($exp_desc_a[$i])."</td><td>".dropAccents($expCity[$i]).", ".dropAccents($expCountry[$i])."</td></tr>";
			}
			$texto_pdf = $texto_pdf."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			$texto = $texto."<br><br></table><img src='../../common/img/Ausbildung.jpg' /><br>";
			
			/* *****  Generating PDF & HTML Education paragraph  ***** */
			$educ_a = explode("|", $fila[education]);
			$tot=count($educ_a);
			for ($i=0;$i<$tot;$i++){
				$texto_pdf = $texto_pdf.$educ_a[$i]."<br>";
				$texto = $texto.$educ_a[$i]."<br>";
			}
			$texto_pdf = $texto_pdf."<br>";//To ensure there are 2 <br> between paragraphs
			$texto = $texto."<br>";
			
			/* *****  Generating PDF & HTML Language paragraph  ***** */
			$lang_a = explode("|", $fila[language]);
			$langT_a = explode("|", $fila[langLevel]);
			$tot=count($lang_a);
			$texto_pdf = $texto_pdf."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			$texto = $texto."<br><img src='../../common/img/Sprachkenntnisse.jpg' /><br>";
			
			$texto_pdf=$texto_pdf."<table>";
			$texto=$texto."<table class='table table-striped table-hover'>";
			for ($i=0;$i<$tot;$i++){
				if($langT_a[$i] == 'mothertongue'){
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>Muttersprache</td></tr>";
				}
				else{
					$texto_pdf = $texto_pdf."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
					$texto= $texto."<tr><td><b> - ".getDBsinglefield(german, languages, key, $lang_a[$i])."</b></td><td>".$langT_a[$i]."</td></tr>";
				}
			}
			$texto_pdf=$texto_pdf."</table><br><br>";
			$texto=$texto."</table>";
			
			//Other interesting information
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Angaben.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: </b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Familienstand: </b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td><b>Kinder: </b></td><td> ".$fila[sons]."</td></tr>";
			$texto_pdf = $texto_pdf."</table><br>";
			
			$texto = $texto."<br><br><img src='../../common/img/Angaben.jpg' />";
			$texto = $texto."<table class='table table-striped table-hover'>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Führerschein und Ausstellungsdatum: &nbsp</b></td><td> ".$fila[drivingType]." / ".date("d-m-Y", strtotime($fila[drivingDate]))."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Familienstand: &nbsp</b></td><td> ".getDBsinglefield(german, maritalStatus, key, $fila[marital])."</td></tr>";
				$texto = $texto."<tr><td><b> - </b></td><td><b>Kinder: &nbsp</b></td><td> ".$fila[sons]."</td></tr>";
			$texto = $texto."</table><br>";
			
			//10 key points from my personal experience
			$texto_pdf = $texto_pdf."<br><br><img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto= $texto."<img src='../../common/img/Wesentliche.jpg' /><br>";
			$texto_pdf = $texto_pdf."<table>";
			$texto = $texto."<table class='table table-striped table-hover'>";
			for($i=0;$i<10;$i++){
				$skill="skill".$i;
				if(strlen($fila[$skill]) > 0){
					$texto_pdf = $texto_pdf."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
					$texto = $texto."<tr><td><b> - </b></td><td>".dropAccents($fila[$skill])."</td></tr>";
				}
			}
			$texto_pdf = $texto_pdf."</table>";
			$texto = $texto."</table>";
			
			//Perspectiva Alemania personal notes
			if (strlen($nota)>0){
				$texto=$texto."<div class='cuadronegro'><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
				$texto_pdf=$texto_pdf."<div class='cuadronegro'><h3>BEWERTUNG DURCH PERSPECTIVA ALEMANIA</h3><br>".$nota."</div>";
			}
			
			$texto_pdf=$texto_pdf."</div>";
			
			$dompdf = new DOMPDF();
			
			require_once "dompdf_config.inc.php";
			
			$dompdf->load_html($texto_pdf);
			$dompdf->render();
			
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas = $dompdf->get_canvas();
			$canvas->page_text(280, 750, $pagetext, $font, 10, array(0,0,0));
			
			$output = $dompdf->output();
			file_put_contents($output_dir.$pagetext.".pdf", $output);
		}
	}
}//End of block where all the CV information is recovered
?>
