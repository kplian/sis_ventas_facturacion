<?php
/**
*@package pXP
*@file gen-ACTDosificacion.php
*@author  (jrivera)
*@date 07-10-2015 13:00:56
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTDosificacion extends ACTbase{    
			
	function listarDosificacion(){
		$this->objParam->defecto('ordenacion','id_dosificacion');
		
		if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro(" dos.id_sucursal = " .  $this->objParam->getParametro('id_sucursal'));
        }
		
		if ($this->objParam->getParametro('tipo') == 'manual') {
            $this->objParam->addFiltro(" dos.tipo = ''F'' ");
			$this->objParam->addFiltro(" dos.tipo_generacion = ''manual'' ");
        }
		
		if ($this->objParam->getParametro('fecha') != '') {
            $this->objParam->addFiltro(" dos.fecha_limite >= ''" .  $this->objParam->getParametro('fecha')."''");
        }
		
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODDosificacion','listarDosificacion');
		} else{
			$this->objFunc=$this->create('MODDosificacion');
			
			$this->res=$this->objFunc->listarDosificacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function insertarDosificacion(){
        $this->objFunc=$this->create('MODDosificacion');


        if($this->objParam->insertar('id_dosificacion')){
            $this->res=$this->objFunc->insertarDosificacion($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarDosificacion($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
						
	function eliminarDosificacion(){
			$this->objFunc=$this->create('MODDosificacion');	
		$this->res=$this->objFunc->eliminarDosificacion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>