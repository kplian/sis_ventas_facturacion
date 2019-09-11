<?php

/*
 * 	ISSUE		FECHA		AUTHOR 		DESCRIPCION
 * 	#5			09/08/2019	EGS			Nuevo formato de factura 
 */
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf.php');
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
// Extend the TCPDF class to create custom MultiRow
class RFacturaReciboPdf extends  ReportePDF {
    var $cabecera;
    var $detalle;

    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    var $total;
    var $with_col;
    var $codigo_reporte;
    var $cadena_qr;
    var $barcodeobj;
    var $estado;

    var $factura_cabecera;
    var $factura_detalle;
    var $pagina;
    var $params;
    var $img_qr;
    var $im;
    var $png;
    var $nombre_archivo;
    var $moneda;
    var $texto_estado;
    var $img_texto_estado;
    var $count; ///variable para controlar el margin del header y que cabecera de la factura se dibuje en la primera hoja
    var $countUltimo;

    function datosHeader ( $datasource) {

        $this->codigo_reporte= $this->objParam->getParametro('formato_comprobante');
        $this->codigo_reporte = explode("-",$this->codigo_reporte);
        $this->codigo_reporte =$this->codigo_reporte [1];
        $this->cabecera = $datasource->getParameter('cabecera');
        $this->detalle = $datasource->getParameter('detalle');


        if ($this->cabecera['id_venta_fk'] !='' ) {
            $this->factura_cabecera =$datasource->getParameter('facturaCabecera');
            $this->factura_detalle =$datasource->getParameter('facturaDetalle');
        }

        ///importante que las variables  se ejecuten antes que el header para el cambio de tipos de copia
        if ( $this->cabecera['estado'] == 'finalizado') {

            if ($this->codigo_reporte =='NOTAFACMEDIACAR') {
                $this->SetFont ('helvetica', '', 10 , '', 'default', true );
                $this->pagina ='<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL CLIENTE</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;
            }
            else {
                $this->SetFont ('helvetica', '', 10 , '', 'default', true );
                $this->pagina ='<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;
            }

        }
        else {


            if ($this->cabecera['estado'] == 'borrador') {

                $this->pagina ='<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Borrador</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;
            }
            elseif ($this->cabecera['estado'] == 'anulado'){

                $this->pagina ='<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Anulado</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;
            }elseif ($this->cabecera['estado'] == 'emision'){

                $this->pagina ='<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Emision</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;

            } else {
                $this->estado = ' ';
            }

        }
        if ($this->codigo_reporte =='NOTAFACMEDIACAR') {

            $this->SetHeaderMargin(15); //margin de header de top
            $this->SetFooterMargin(60); //margin de Footer de botton
            $this->SetAutoPageBreak(true, 60);
        }
        elseif($this->codigo_reporte =='RECIBOETR') {//#5
            $this->SetHeaderMargin(7); //margin de header de top
            $this->SetFooterMargin(60); //margin de Footer de botton
            $this->SetAutoPageBreak(true, 60);
        }
        else{
            $this->SetHeaderMargin(15); //margin de header de top
            $this->SetFooterMargin(60); //margin de Footer de botton
            $this->SetAutoPageBreak(true, 60);
        }

    }



    function generarReporte() {
        //inicia la paginacion de la factura original
        $this->startPageGroup();
        $this->AddPage();

        if($this->cabecera['desc_moneda_sucursal'] = 'Boliviano' ){
            $this->moneda = 'Bolivianos';
        }
        else{
            $this->moneda =$this->cabecera['desc_moneda_sucursal'];
        }
        ///iniciar el qr y convertir a una imagen para podr colocarlo en html y writehtml lo reconozca
        $this->cadena_qr =
            $this->cabecera['nit_entidad'] .'|'.
            $this->cabecera['numero_factura'] . '|' .
            $this->cabecera['autorizacion'] . '|' .
            $this->cabecera['fecha_venta'] . '|' .
            $this->cabecera['total_venta'] . '|' .
            $this->cabecera['total_venta'] . '|' .
            $this->cabecera['codigo_control'] . '|' .
            $this->cabecera['nit_cliente'] . '|0.00|0.00|0.00|0.00';

        $this->barcodeobj = new TCPDF2DBarcode($this->cadena_qr,'QRCODE,H');

        //todo cambiar ese nombre por algo randon
        $this->nombre_archivo = md5($_SESSION["ss_id_usuario_ai"] . $_SESSION["_SEMILLA"]);
        $this->png = $this->barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $this->im = imagecreatefromstring( $this->png);
        header('Content-Type: image/png');
        imagepng($this->im, dirname(__FILE__) . "/../../reportes_generados/" .$this->nombre_archivo . ".png");
        imagedestroy($this->im);
        $this->img_qr = dirname(__FILE__) . "/../../reportes_generados/" . $this->nombre_archivo . ".png";

        //inicia originales y copias de la factura
        if ( $this->cabecera['estado'] == 'finalizado') {

            ob_start();
            include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
            $content = ob_get_clean();

            $this->writeHTML($content,false, false, true, false, '');

            //$this->revisarfinPagina($content);
            $this->countUltimo = $this->count;

            //inicia el original del emisor cuando es nota de credito
            if ($this->codigo_reporte =='NOTAFACMEDIACAR') {

                $this->pagina = '<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL EMISOR</strong></h3></td>
			  						
			  						</tr>';
                $this->count = 0;
                $this->originalEmisor();
            }

            //inicia copias de las facturas
            $this->pagina = '<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Copia Tesoreria</strong></h3></td>
			  						
			  						</tr>';
            $this->count = 0;
            $this->copiaTesoreria();
            $this->pagina = '<tr>
										<td style="text-align:left;" width="400px" >
										</td>
			  							<td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Copia Contabilidad</strong></h3></td>
			  						
			  						</tr>';
            $this->count = 0;
            $this->copiaContabilidad();


        }
        else {

            $this->SetFont ('helvetica', '', 10 , '', 'default', true );// tamaño de letras y tipo

            ob_start();
            include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
            $content = ob_get_clean();


            $this->writeHTML($content,false, false, true, false, '');

        }




    }

    function Header() {
        $this->count = $this->count +1; //controla la aparicion de los datos del cliente en la 1 hoja sea solo una vez

        if ($this->codigo_reporte =='NOTAFACMEDIACAR') { //margenes para notas de credito
            if($this->count == 1 ){
                $this->SetMargins(15, 105, 15); //margenes contenido de la primera hoja
            }else{
                $this->SetMargins(15,70, 15);//margenes contenido de las demas hojas
            }
        }
        else{
            if($this->count == 1 ){
                $this->SetMargins(15, 118, 15); //margenes contenido de la primera hoja
            }else{

                $this->SetMargins(15,85, 15);//margenes contenido de las demas hojas
            }
        }

        //genera el header de de la factura
        $this->SetFont ('helvetica', '', 10 , '', 'default', true );
        ob_start();
        include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFacturaHeader.php');
        $content = ob_get_clean();
        $this->writeHTML($content,false, false, true, false, '');

        $this->noValido();//sobrepone la imagen de no valido
    }

    function Footer() {
        //reescribiendo footer para que se paginacion grupal
        $this->footerInmovil(); //posiciona footer
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);

        $this->SetXY(15, 270); // posicion del pie de pagina
        $this->Cell($ancho, 0, '', '', 0, 'L');

        ////Imprime las paginaciones segun configuracion de inicio para lasa copias u original
        $pagenumtxt = 'Página'.' '.$this->getPageNumGroupAlias().' de '.$this->getPageGroupAlias();

        $this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
        $this->Cell($ancho, 0,'', '', 0, 'R');

    }

    function revisarfinPagina($a){
        $dimensions = $this->getPageDimensions();

        $hasBorder = false;
        $startY = $this->GetY();

        $this->getNumLines($row['cell1data'], 90);
        /*
        if ($startY < 200) {

        }
        else{

        }*/
    }

    function footerInmovil(){

        $this->SetFont ('helvetica', '', 10 , '', 'default', true );
        ob_start();
        include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFacturaFooter.php');
        $content = ob_get_clean();
        $this->writeHTML($content,false, false, true, false, '');
    }
    function posicionFooter(){

        $this->SetXY(15, 213);
        $this->Cell(0, 0,  $this->footerInmovil(), '', 0, 'C');
    }

    //funcion que sobrepone la imagen no valido sobre la factura y controla en que solo en la emision no se muestre
    function noValido(){
        if ($this->objParam->getParametro('nombre_vista') !='VentaEmisor' or $this->objParam->getParametro('nombre_vista') !='VentaEmisor' && $this->cabecera ['estado'] == 'finalizado') {
            $this->SetAlpha(0.50);
            //link
            $this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 50, 70, 100,100, '', '', '', true, 72);

            $this->SetAlpha(1);
        };

    }
    function originalEmisor(){

        ///inicia la paginacion de la original Emisor
        $this->startPageGroup();
        $this->AddPage();

        $this->SetFont ('helvetica', '', 10 , '', 'default', true );
        ob_start();
        include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
        $content = ob_get_clean();
        $this->writeHTML($content,false, false, true, false, '');
        //$this->revisarfinPagina($content);

    }

    function copiaContabilidad(){

        ///inicia la paginacion de la copia Contabilidad
        $this->startPageGroup();
        $this->AddPage();

        $this->SetFont ('helvetica', '', 10 , '', 'default', true );
        ob_start();
        include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
        $content = ob_get_clean();
        $this->writeHTML($content,false, false, true, false, '');
        //$this->revisarfinPagina($content);

    }

    function copiaTesoreria(){

        ///inicia la paginacion de la copia Tesoreria

        $this->startPageGroup();
        $this->AddPage();


        $this->SetFont ('helvetica', '', 10, '', 'default', true );
        ob_start();
        include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
        $content = ob_get_clean();

        $this->writeHTML($content,false, false, true, false, '');
        //$this->revisarfinPagina($content);

    }


}
?>