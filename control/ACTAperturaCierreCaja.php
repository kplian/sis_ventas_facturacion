<?php
/**
*@package pXP
*@file gen-ACTAperturaCierreCaja.php
*@author  (jrivera)
*@date 07-07-2016 14:16:20
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

require_once(dirname(__FILE__).'/../reportes/RAperturaCierrePDF.php');
class ACTAperturaCierreCaja extends ACTbase{    
			
	function listarAperturaCierreCaja(){
		$this->objParam->defecto('ordenacion','id_apertura_cierre_caja');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if ($this->objParam->getParametro('pes_estado') != '') {
            $this->objParam->addFiltro(" apcie.estado = ''" .  $this->objParam->getParametro('pes_estado') . "''");
        }
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODAperturaCierreCaja','listarAperturaCierreCaja');
		} else{
			$this->objFunc=$this->create('MODAperturaCierreCaja');
			
			$this->res=$this->objFunc->listarAperturaCierreCaja($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarAperturaCierreCaja(){
		$this->objFunc=$this->create('MODAperturaCierreCaja');	
		if($this->objParam->insertar('id_apertura_cierre_caja')){
			$this->res=$this->objFunc->insertarAperturaCierreCaja($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarAperturaCierreCaja($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarAperturaCierreCaja(){
			$this->objFunc=$this->create('MODAperturaCierreCaja');	
		$this->res=$this->objFunc->eliminarAperturaCierreCaja($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function reporteAperturaCierreCaja()	{

        $this->objFunc=$this->create('MODAperturaCierreCaja');
        $this->res=$this->objFunc->reporteAperturaCierreCaja($this->objParam);


        //obtener titulo del reporte
        $titulo = 'AperturaCierreCaja';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);


        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER	');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf
        $this->objReporteFormato=new RAperturaCierrePDF($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
			
}

?>