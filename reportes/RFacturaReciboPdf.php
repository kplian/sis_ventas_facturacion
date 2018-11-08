<?php
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf.php');
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
// Extend the TCPDF class to create custom MultiRow
class RFacturaReciboPdf extends  ReportePDF {
	var $cabecera;
	var $detalle;
	var $ancho_hoja;

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
	
	
	function datosHeader ( $datasource) {
		
		//var_dump($datasource);
		//var_dump('pdf',$this->objParam);
		
			$this->codigo_reporte= $this->objParam->getParametro('formato_comprobante');
			$this->codigo_reporte = explode("-",$this->codigo_reporte);
			$this->codigo_reporte =$this->codigo_reporte [1];
			//var_dump('formato_comprobante',$this->codigo_reporte);
		 	
		 	$this->cabecera = $datasource->getParameter('cabecera');
			
			//var_dump('cabecera',$this->cabecera);
	        
	        $this->detalle = $datasource->getParameter('detalle');
			//var_dump($this->cabecera['id_venta_fk']);
			
			if ($this->cabecera['id_venta_fk'] !='' ) {
				$this->factura_cabecera =$datasource->getParameter('facturaCabecera');
				$this->factura_detalle =$datasource->getParameter('facturaDetalle');
				//var_dump($this->factura_cabecera);
			}	
					
			//var_dump('detalle',  $this->detalle );

			$this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT-10;
			$this->datos_detalle = $detalle;
		 	$this->SetMargins(15, 30, 5);

			
	
	}
	
	function Header() {
	
	}
	

	 function generarReporte() {
	 //inicia la paginacion de la factura original
		$this->startPageGroup();
	/////
		$this->AddPage();
		if($this->cabecera['desc_moneda_sucursal'] = 'Boliviano' ){
			$this->moneda = 'Bolivianos';
			}
			else{
			$this->moneda =$this->cabecera['desc_moneda_sucursal'];
		}
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
		$with_col = $this->with_col;
		
		
	    //todo cambiar ese nombre por algo randon
        $this->nombre_archivo = md5($_SESSION["ss_id_usuario_ai"] . $_SESSION["_SEMILLA"]);
        $this->png = $this->barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $this->im = imagecreatefromstring( $this->png);
        header('Content-Type: image/png');
        imagepng($this->im, dirname(__FILE__) . "/../../reportes_generados/" .$this->nombre_archivo . ".png");
        imagedestroy($this->im);
        $this->img_qr = dirname(__FILE__) . "/../../reportes_generados/" . $this->nombre_archivo . ".png";
	
		if ( $this->cabecera ['estado'] == 'finalizado') {
			
				
					if ($this->codigo_reporte =='NOTAFACMEDIACAR') {
					$this->SetFont ('helvetica', '', 10 , '', 'default', true );
					$this->pagina ='<tr><td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL CLIENTE</strong></h3></td></tr>';
					}
					else {
					$this->SetFont ('helvetica', '', 10 , '', 'default', true );
					$this->pagina ='<tr><td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL</strong></h3></td></tr>';
					}
					
					if ($this->objParam->getParametro('nombre_vista') !='VentaEmisor' ) {
						$this->SetAlpha(0.50);
		
						$this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 30, 50, 150,150, '', 'http://www.tcpdf.org', '', true, 72);
					
						$this->SetAlpha(1);
					};
					
					
					ob_start();
					include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
		       		 $content = ob_get_clean();

					$this->writeHTML($content,false, false, true, false, '');
					//$this->write2DBarcode($this->cadena_qr, 'QRCODE,H', 130,138,30,30, $style,'T',true);
					
					$this->revisarfinPagina($content);
					
				    if ($this->codigo_reporte =='NOTAFACMEDIACAR') {
					   $this->originalEmisor();
				    }

			        $this->copiaContabilidad();
				
					$this->copiaTesoreria();

		}
		else {
			
			
					if ($this->cabecera['estado'] == 'borrador') {
					$this->pagina ='<tr><td style="text-align: center;" colspan="2" > <h3>&nbsp;<strong>Borrador</strong></h3></td></tr>';
					} 
					elseif ($this->cabecera['estado'] == 'anulado'){
					$this->pagina ='<tr><td style="text-align: center;" colspan="2" > <h3>&nbsp;<strong>Anulado</strong></h3></td></tr>';
					}elseif ($this->cabecera['estado'] == 'emision'){
					$this->pagina ='<tr><td style="text-align: center;" colspan="2" > <h3>&nbsp;<strong>Emision</strong></h3></td></tr>';
					} else {
						$this->estado = ' ';
					}
		
		$this->SetAlpha(0.50);
		
		$this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 30, 50, 150,150, '', 'http://www.tcpdf.org', '', true, 72);
	
		$this->SetAlpha(1);
		ob_start();
		include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
        $content = ob_get_clean();

		
		$this->writeHTML($content,false, false, true, false, '');
		$this->SetFont ('helvetica', '', 5 , '', 'default', true );
		
		//$this->write2DBarcode($this->cadena_qr, 'QRCODE,H', 130,138,30,30, $style,'T',true);
		}
	  
		
  	
		
	} 
	 
function Footer() {
     
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
		//set style for cell border
		$line_width = 0.85 / $this->getScaleFactor();
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
		$this->Ln(2);
		$cur_y = $this->GetY();
		//$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
		$this->Cell($ancho, 0, '', '', 0, 'L');
		//$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
		
		
		////Imprime las paginaciones segun configuracion de inicio para lasa copias u original
		$pagenumtxt = 'Página'.' '.$this->getPageNumGroupAlias().' de '.$this->getPageGroupAlias();
		$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
		$this->Cell($ancho, 0,'', '', 0, 'R');
	
		
	}

	
	function revisarfinPagina($a){
		$dimensions = $this->getPageDimensions();
		//var_dump($dimensions);
		
		$hasBorder = false;
		$startY = $this->GetY();
		$this->getNumLines($row['cell1data'], 90);
		//$this->calcularMontos($a);			
		if ($startY > 235) {
						
		//$this->cerrarCuadro();	
		//$this->cerrarCuadroTotal();	//cuanto se usa total	
		if($this->total!= 0){
				$this->AddPage();
				//$this->generarCabecera();
			}				
		}
	}
	
	function originalEmisor(){
		
		///inicia la paginacion de la copia Contabilidad
			$this->startPageGroup();
			$this->AddPage();

			$with_col = $this->with_col;
			$this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT-10;
			$this->datos_detalle = $detalle;
		 	$this->SetMargins(15, 30, 5);	
			
		    $this->pagina = '<tr><td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>ORIGINAL EMISOR</strong></h3></td></tr>';
			
				if ($this->objParam->getParametro('nombre_vista') !='VentaEmisor' && $this->cabecera ['estado'] == 'finalizado') {
						$this->SetAlpha(0.50);
		
						$this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 30, 50, 150,150, '', 'http://www.tcpdf.org', '', true, 72);
					
						$this->SetAlpha(1);
					};
					
					$this->SetFont ('helvetica', '', 10 , '', 'default', true );
					ob_start();
					include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
			        $content = ob_get_clean();
					$this->writeHTML($content,false, false, true, false, '');
					$this->revisarfinPagina($content);
				
	}
	
	function copiaContabilidad(){
		
		///inicia la paginacion de la copia Contabilidad
			$this->startPageGroup();
			$this->AddPage();

			$with_col = $this->with_col;
			$this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT-10;
			$this->datos_detalle = $detalle;
		 	$this->SetMargins(15, 30, 5);	
			
			$this->pagina = '<tr><td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Copia Contabilidad</strong></h3></td></tr>';
					
				 if ($this->objParam->getParametro('nombre_vista') !='VentaEmisor' ) {
						$this->SetAlpha(0.50);
		
						$this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 30, 50, 150,150, '', 'http://www.tcpdf.org', '', true, 72);
					
						$this->SetAlpha(1);
					};
					
					$this->SetFont ('helvetica', '', 10 , '', 'default', true );
					ob_start();
					include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
			        $content = ob_get_clean();
					$this->writeHTML($content,false, false, true, false, '');
					$this->revisarfinPagina($content);
				
	}
		
	function copiaTesoreria(){
		
		///inicia la paginacion de la copia Tesoreria
		
			$this->startPageGroup();
			$this->AddPage();

			$with_col = $this->with_col;
		
			$this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT-10;
			$this->datos_detalle = $detalle;
		 	$this->SetMargins(15, 30, 5);
			
			$this->pagina = '<tr><td style="text-align: center;" colspan="2" ><h3>&nbsp;<strong>Copia Tesoreria</strong></h3></td></tr>';
			
			if ($this->objParam->getParametro('nombre_vista') !='VentaEmisor') {
						$this->SetAlpha(0.50);
		
						$this->Image(dirname(__FILE__) . "/../../lib/imagenes/estados/".$this->cabecera['estado'].".png", 30, 50, 150,150, '', 'http://www.tcpdf.org', '', true, 72);
					
						$this->SetAlpha(1);
			};
		
					$this->SetFont ('helvetica', '', 10, '', 'default', true );
					ob_start();
					include(dirname(__FILE__).'/../reportes/tpl/pdf/formatoFactura.php');
			        $content = ob_get_clean();

					$this->writeHTML($content,false, false, true, false, '');

					$this->revisarfinPagina($content);
				
	}


}
?>