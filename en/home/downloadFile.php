<?php
	session_start();
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	$id_ac=$_GET["id_b"];
	$id_aco=$_GET["id_bb"];
	$id =unserialize($_SESSION["id"]);
	$id_o =unserialize($_SESSION["id_o"]);	
	$zip = new ZipArchive();
	$numero = date("YmdHis");
	$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
	$filezip = $output_dir . $numero . ".zip";
	$enlace = connectDB();


	foreach ($id_o as $valor) {
		$consulta = "SELECT * from cvitaes where nie like '$valor'" ;
		if ($resultado = mysqli_query($enlace, $consulta)) {

			while ($fila = $resultado->fetch_assoc()) {
				
				

				if($zip->open($filezip,ZIPARCHIVE::CREATE)===true) {
					chdir("$output_dir");
					$nf = $fila['userLogin'] . ".pdf";
					$zip->addFile($nf);
					$zip->close();
					unlink($nf);
					$nf="";	
					
					
					
				}
			}
		}
	}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$numero.".zip\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filezip));
ob_end_flush();
@readfile($filezip);
?>
