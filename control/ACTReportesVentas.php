<?php
/**
*@package pXP
*@file gen-ACTVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 * 		ISSUE 			Fecha				Autor				Descripcion
*		#2	endeEtr			23/01/2019			EGS					se agrego reporte con lista de productos activos por puntos de venta	
*/
require_once(dirname(__FILE__).'/../reportes/RResumenVentasBoaXLS.php');
require_once(dirname(__FILE__).'/../reportes/RReporteXProducto.php');


require_once(dirname(__FILE__).'/../reportes/RVentasXls.php');
require_once(dirname(__FILE__).'/../reportes/RProductoAcPuntoVXls.php');

class ACTReportesVentas extends ACTbase{    
			
	function reporteResumenVentasBoa()	{
		
		$this->objFunc=$this->create('MODReportesVentas');	
		
		$this->res=$this->objFunc->listarConceptosSucursal($this->objParam);		
		
		$this->objFunc=$this->create('MODReportesVentas');	
		$this->res2=$this->objFunc->listarReporteDetalle($this->objParam);
		
			
		//$this->objFunc=$this->create('MODReportesVentas');	
		//$this->res3=$this->objFunc->listarReporteResumen($this->objParam);
		//obtener titulo del reporte
		$titulo = 'Resumen de Ventas';
		//Genera el nombre del archivo (aleatorio + titulo)
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		$this->objParam->addParametro('conceptos',$this->res->datos);
		$this->objParam->addParametro('datos',$this->res2->datos);
		//$this->objParam->addParametro('resumen',$this->res3->datos);
			
		//Instancia la clase de excel
		$this->objReporteFormato=new RResumenVentasBoaXLS($this->objParam);
		$this->objReporteFormato->imprimeDatos();
		//$this->objReporteFormato->imprimeDatosResumen();
		$this->objReporteFormato->generarReporte();
		
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
										'Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
				
	}

	function reporteXProducto()	{
		
		$this->objFunc=$this->create('MODReportesVentas');	
		
		$this->res=$this->objFunc->listarReporteXProducto($this->objParam);
		
		
		
		//obtener titulo del reporte
		$titulo = 'Ventas por Producto';
		//Genera el nombre del archivo (aleatorio + titulo)
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);		
		$this->objParam->addParametro('datos',$this->res->datos);
		
			
		//Instancia la clase de excel
		$this->objReporteFormato=new RReporteXProducto($this->objParam);
		$this->objReporteFormato->imprimeDatos();		
		$this->objReporteFormato->generarReporte();
		
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
										'Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
				
	}
	
	function reporteVentas(){
			
		//var_dump($this->objParam);
	   
        $this->objParam->defecto('dir_ordenacion','asc');
		$this->objParam->defecto('cantidad',10000000);
		$this->objParam->defecto('puntero', 0);	
		
		if($this->objParam->getParametro('tipo_reporte') == 'sucursal'){
			
			$this->objParam->addParametro('id_punto_venta','');	
		}
	
		if($this->objParam->getParametro('id_sucursal')!=''){

		$this->objParam->addFiltro("suc.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	

		$this->objParam->addParametroConsulta('ordenacion','id_sucursal');	
		$this->objFunc=$this->create('sis_ventas_facturacion/MODSucursal');	
		$this->res=$this->objFunc->listarSucursal($this->objParam);		
		
		$this->objParam->addParametroConsulta('filtro',' 0 = 0 ');
		
		//var_dump('sucursal',$this->res);	
		//var_dump($this->objParam);
		}
		
		if($this->objParam->getParametro('id_punto_venta')!=''){
		
		$this->objParam->addParametroConsulta('ordenacion','id_punto_venta');	
		
		$this->objParam->addFiltro("puve.id_punto_venta = ".$this->objParam->getParametro('id_punto_venta'));	
		
		$this->objFunc=$this->create('sis_ventas_facturacion/MODPuntoVenta');		
		$this->res2=$this->objFunc->listarPuntoVenta($this->objParam);
		
		$this->objParam->addParametroConsulta('filtro',' 0 = 0 ');
		
		//var_dump('punto venta',$this->res2);
		//var_dump($this->objParam);
		}
		
		$this->objParam->addParametroConsulta('ordenacion','desc_proveedor');	
		
		if($this->objParam->getParametro('id_sucursal')!=''){

		$this->objParam->addFiltro("ven.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	
		}
		
		if($this->objParam->getParametro('id_punto_venta')!=''){
			$this->objParam->addFiltro("ven.id_punto_venta = ".$this->objParam->getParametro('id_punto_venta'));	
		
		}
		if($this->objParam->getParametro('id_gestion')!=''){
			$this->objParam->addFiltro("per.id_gestion = ".$this->objParam->getParametro('id_gestion'));	
		}
		
		 if($this->objParam->getParametro('fecha_desde')!='' && $this->objParam->getParametro('fecha_hasta')!=''){
			$this->objParam->addFiltro("( ven.fecha::date  BETWEEN ''%".$this->objParam->getParametro('fecha_desde')."%''::date  and ''%".$this->objParam->getParametro('fecha_hasta')."%''::date)");	
		}
		
		if($this->objParam->getParametro('desc_proveedor')!=''){
            $this->objParam->addFiltro("vpro.id_proveedor = ''".$this->objParam->getParametro('desc_proveedor')."''");    
        }
	   if($this->objParam->getParametro('nro_documento')!=''){
            $this->objParam->addFiltro("  dcv.nro_documento = ''".$this->objParam->getParametro('nro_documento')."''");     
	   
	   	    }
	
		$this->objFunc=$this->create('MODReportesVentas');		
		$this->res3=$this->objFunc->listarVentaReporte($this->objParam);
		
		
		
		
		
		//var_dump($this->res3);	
		//$this->objFunc=$this->create('MODReportesVentas');	
		//$this->res3=$this->objFunc->listarReporteResumen($this->objParam);
		//obtener titulo del reporte
		$titulo = 'Resumen de Ventas';
		//Genera el nombre del archivo (aleatorio + titulo)
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		$this->objParam->addParametro('sucursal',$this->res->datos);
		$this->objParam->addParametro('punto_venta',$this->res2->datos);
		$this->objParam->addParametro('venta',$this->res3->datos);
		//$this->objParam->addParametro('resumen',$this->res3->datos);
	
		//var_dump($this->objParam);
		
		//Instancia la clase de excel
		$this->objReporteFormato=new RVentasXls($this->objParam);
		$this->objReporteFormato->generarDatos();
		$this->objReporteFormato->generarReporte();
		
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
										'Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

	}
	
	function reporteVentasGrid(){
			
		$this->objParam->defecto('ordenacion','id_doc_compra_venta');

		$this->objParam->defecto('dir_ordenacion','asc');
		
			
		if($this->objParam->getParametro('id_doc_compra_venta')!=''){
			$this->objParam->addFiltro("dcv.id_doc_compra_venta = ".$this->objParam->getParametro('id_doc_compra_venta'));	
		}
		if($this->objParam->getParametro('id_sucursal')!=''){

		$this->objParam->addFiltro("ven.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	
		}
		
		if($this->objParam->getParametro('id_punto_venta')!=''){
			$this->objParam->addFiltro("ven.id_punto_venta = ".$this->objParam->getParametro('id_punto_venta'));	
		
		}
		
			
		if($this->objParam->getParametro('id_gestion')!=''){
			$this->objParam->addFiltro("per.id_gestion = ".$this->objParam->getParametro('id_gestion'));	
		}
		
		if($this->objParam->getParametro('id_depto')!=''){
			$this->objParam->addFiltro(" dcv.id_depto_conta = ".$this->objParam->getParametro('id_depto'));	
		}
		
		
		if($this->objParam->getParametro('id_periodo')!=''){
            $this->objParam->addFiltro("dcv.id_periodo =".$this->objParam->getParametro('id_periodo'));    
        }
		
		if($this->objParam->getParametro('nro_documento')!=''){
            $this->objParam->addFiltro("  dcv.nro_documento = ''".$this->objParam->getParametro('nro_documento')."''");     
	   
	   	    }
		
		if($this->objParam->getParametro('nit')!=''){
            $this->objParam->addFiltro(" dcv.nit = ''".$this->objParam->getParametro('nit')."''");    
        }
		
		if($this->objParam->getParametro('nombre_auxiliar')!=''){
            $this->objParam->addFiltro("aux.nombre_auxiliar = ''".$this->objParam->getParametro('nombre_auxiliar')."''");    
        }
		
		if($this->objParam->getParametro('desc_proveedor')!=''){
            $this->objParam->addFiltro("vpro.desc_proveedor = ''".$this->objParam->getParametro('desc_proveedor')."''");    
        }
		
		
		if($this->objParam->getParametro('razon_social')!=''){
            $this->objParam->addFiltro(" dcv.razon_social = ''".$this->objParam->getParametro('razon_social')."''");    
        }
	
		
		
        if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("( dcv.fecha::date  BETWEEN ''%".$this->objParam->getParametro('desde')."%''::date  and ''%".$this->objParam->getParametro('hasta')."%''::date)");	
		}
		
		if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')==''){
			$this->objParam->addFiltro("( dcv.fecha::date  >= ''%".$this->objParam->getParametro('desde')."%''::date)");	
		}
		
		if($this->objParam->getParametro('desde')=='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("( dcv.fecha::date  <= ''%".$this->objParam->getParametro('hasta')."%''::date)");	
		}
		

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCobroRecibo','listarDocCompraVentaCobro');
		} else{
			
			
			$this->objFunc=$this->create('MODReportesVentas');			
			$this->res=$this->objFunc->listarVentaReporteGrid($this->objParam);

		}
			
		
		$this->res->imprimirRespuesta($this->res->generarJson());

	}
   
    function reporteVentaDetalle(){
    	
				
		if($this->objParam->getParametro('id_venta')!=''){
			$this->objParam->addFiltro("vedet.id_venta = ".$this->objParam->getParametro('id_venta'));	
		}
		
	if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODReportesVentas','listarReciboFacturaDetalle');
		} else{
			
    	$this->objFunc = $this->create('MODReportesVentas');
		$this->res = $this->objFunc->listarReciboFacturaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
		
    	
    }
	//#2  
	 function listarProductoActivoPuntoV(){	
		/*		
		if($this->objParam->getParametro('id_venta')!=''){
			$this->objParam->addFiltro("vedet.id_venta = ".$this->objParam->getParametro('id_venta'));	
		}*/		
    	$this->objFunc = $this->create('MODReportesVentas');
		$this->res = $this->objFunc->listarProductoActivoPuntoV($this->objParam);
		//$this->res->imprimirRespuesta($this->res->generarJson());
	   
	        $titulo = 'Productos Activos Por Punto de Venta';
			
			$nombreArchivo=uniqid(md5(session_id()).$titulo);
			$nombreArchivo.='.xls';
			$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
			$this->objParam->addParametro('datos',$this->res->datos);			
			$this->objReporteFormato=new RProductoAcPuntoVXls($this->objParam);
			$this->objReporteFormato->generarDatos();
			$this->objReporteFormato->generarReporte();
			$this->mensajeExito=new Mensaje();
			$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se genero con éxito el reporte: '.$nombreArchivo,'control');
			$this->mensajeExito->setArchivoGenerado($nombreArchivo);
			$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    	
    }//#2  
	
	
			
}

?>