<?php
/**
*@package pXP
*@file gen-ACTVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../../pxp/pxpReport/DataSource.php');
include(dirname(__FILE__).'/../reportes/RFacturaRecibo.php');
class ACTVenta extends ACTbase{    
			
	function listarVenta(){
		$this->objParam->defecto('ordenacion','id_venta');

		$this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('pes_estado') != '') {
            if ($this->objParam->getParametro('pes_estado') == 'proceso_elaboracion') {
                $this->objParam->addFiltro(" ven.estado in( ''revision'', ''elaboracion'') ");
            }elseif ($this->objParam->getParametro('pes_estado') == 'finalizado') {
                $this->objParam->addFiltro(" ven.estado in( ''finalizado'', ''anulado'') ");
				if ($this->objParam->getParametro('interfaz') == 'vendedor') {
					$this->objParam->addFiltro(" ven.fecha_reg::date = now()::date");
				}
            } else {
                if ($this->objParam->getParametro('historico') != 'si') {
                    $this->objParam->addFiltro(" ven.estado = ''". $this->objParam->getParametro('pes_estado') . "'' ");
                }
            }
            
        } 
		
		if ($this->objParam->getParametro('id_sucursal') != '') {
			$this->objParam->addFiltro(" ven.id_sucursal = ". $this->objParam->getParametro('id_sucursal'));
		}
		
		if ($this->objParam->getParametro('id_punto_venta') != '') {
			$this->objParam->addFiltro(" ven.id_punto_venta = ". $this->objParam->getParametro('id_punto_venta'));
		}
		
		 
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODVenta','listarVenta');
		} else{
			$this->objFunc=$this->create('MODVenta');
			
			$this->res=$this->objFunc->listarVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function getVariablesBasicas() {	        
		
		$this->objFunc=$this->create('MODVenta');
		$this->res=$this->objFunc->getVariablesBasicas($this->objParam);
		$newDatos = array();
		foreach ($this->res->datos as $valor) {
			$newDatos[$valor['variable']] = $valor['valor'];
		} 		
		$this->res->datos = $newDatos;		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarVenta(){
		$this->objFunc=$this->create('MODVenta');	
		if($this->objParam->insertar('id_venta')){
			$this->res=$this->objFunc->insertarVenta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    
    function insertarVentaCompleta(){
        $this->objFunc=$this->create('MODVenta');        
        $this->res=$this->objFunc->insertarVentaCompleta($this->objParam);        
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
						
	function eliminarVenta(){
			$this->objFunc=$this->create('MODVenta');	
		$this->res=$this->objFunc->eliminarVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function anularVenta(){
			$this->objFunc=$this->create('MODVenta');	
		$this->res=$this->objFunc->anularVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    
    function siguienteEstadoVenta(){
        $this->objFunc=$this->create('MODVenta');  
        
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]); 
        
        $this->res=$this->objFunc->siguienteEstadoVenta($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    
     function anteriorEstadoVenta(){
        $this->objFunc=$this->create('MODVenta');  
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]); 
        $this->res=$this->objFunc->anteriorEstadoVenta($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	 
	 function recuperarDatosNotaVenta(){
    	$dataSource = new DataSource();	
		$this->objFunc = $this->create('MODVenta');
		$cbteHeader = $this->objFunc->listarNotaVenta($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){
				 	
				$dataSource->putParameter('cabecera',$cbteHeader->getDatos());
						
				$this->objFunc=$this->create('MODVenta');
				$cbteTrans = $this->objFunc->listarNotaVentaDet($this->objParam);
				if($cbteTrans->getTipo()=='EXITO'){
					$dataSource->putParameter('detalle', $cbteTrans->getDatos());
				}
		        else{
		            $cbteTrans->imprimirRespuesta($cbteTrans->generarJson());
				}
			return $dataSource;
		}
        else{
		    $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
		}              
		
    }

    function reporteNotaVenta(){
   	    	
   	    $dataSource = $this->recuperarDatosNotaVenta(); 
   	   	
   	    // get the HTML
	    ob_start();
	    include(dirname(__FILE__).'/../reportes/tpl/notaventa.php');
		//exit;
        $content = ob_get_clean();
	    try
	    {
	    	
			//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf = new TCPDF();			
			$pdf->SetDisplayMode('fullpage');
			
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
			// set default header data
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);			
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			// set font
			$pdf->SetFont('helvetica', '', 10);
			// add a page
            $pdf->AddPage();
			$pdf->writeHTML($content, true, false, true, false, '');
			$nombreArchivo = uniqid(md5(session_id()).'NotaVenta') . '.pdf'; 
			$pdf->Output(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo, 'F');
			
			$mensajeExito = new Mensaje();
            $mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado', 'Se generó con éxito el reporte: '.$nombreArchivo,'control');
            $mensajeExito->setArchivoGenerado($nombreArchivo);
            $this->res = $mensajeExito;
            $this->res->imprimirRespuesta($this->res->generarJson());
			
			
			
			
	    }
	    catch(exception $e) {
	        echo $e;
	        exit;
	    }
    }
	function reporteFacturaRecibo(){
		$this->objFunc = $this->create('MODVenta');
		$datos = array();
		$this->res = $this->objFunc->listarReciboFactura($this->objParam);
		$datos = $this->res->getDatos();
		$datos = $datos[0];
		
		$this->objFunc = $this->create('MODVenta');
		$this->res = $this->objFunc->listarReciboFacturaDetalle($this->objParam);
		$datos['detalle'] = $this->res->getDatos();
		
		$reporte = new RFacturaRecibo();
		$temp = array();
		
		$temp['html'] = $reporte->generarHtml($this->objParam->getParametro('formato_comprobante'),$datos);
		$this->res->setDatos($temp);
		$this->res->imprimirRespuesta($this->res->generarJson());
		

	}
			
}

?>