<?php
        session_start();
        error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
        require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
$file=$_GET[doc];
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
readfile($file);
?>
