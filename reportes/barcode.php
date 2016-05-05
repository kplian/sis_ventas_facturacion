<?php
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');

	$barcodeobj = new TCPDF2DBarcode(base64_decode($_GET["cadena"]), 'QRCODE,H');
	$barcodeobj->getBarcodePNG(3,3);
?>
