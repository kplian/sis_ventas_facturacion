<?php
/**
*@package pXP
*@file gen-ACTVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RResumenVentasBoaXLS.php');
require_once(dirname(__FILE__).'/../reportes/RReporteXProducto.php');

class ACTReportesVentas extends ACTbase{    
			
	function reporteResumenVentasBoa()	{
		
		$this->objFunc=$this->create('MODReportesVentas');	
		
		$this->res=$this->objFunc->listarConceptosSucursal($this->objParam);
		
		
		$this->objFunc=$this->create('MODReportesVentas');	
		$this->res2=$this->objFunc->listarReporteDetalle($this->objParam);
		
		$this->objFunc=$this->create('MODReportesVentas');	
		$this->res3=$this->objFunc->listarReporteResumen($this->objParam);
		//obtener titulo del reporte
		$titulo = 'Resumen de Ventas';
		//Genera el nombre del archivo (aleatorio + titulo)
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		$this->objParam->addParametro('conceptos',$this->res->datos);
		$this->objParam->addParametro('datos',$this->res2->datos);
		$this->objParam->addParametro('resumen',$this->res3->datos);
			
		//Instancia la clase de excel
		$this->objReporteFormato=new RResumenVentasBoaXLS($this->objParam);
		$this->objReporteFormato->imprimeDatos();
		$this->objReporteFormato->imprimeDatosResumen();
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

	
			
}

?>