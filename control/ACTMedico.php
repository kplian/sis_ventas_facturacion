<?php
/**
*@package pXP
*@file gen-ACTMedico.php
*@author  (admin)
*@date 20-04-2015 11:17:42
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTMedico extends ACTbase{    
			
	function listarMedico(){
		$this->objParam->defecto('ordenacion','id_medico');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODMedico','listarMedico');
		} else{
			$this->objFunc=$this->create('MODMedico');
			
			$this->res=$this->objFunc->listarMedico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    
    function listarVendedorMedico(){
        $this->objParam->defecto('ordenacion','id_vendedor_medico');

        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODMedico','listarVendedorMedico');
        } else{
            $this->objFunc=$this->create('MODMedico');
            
            $this->res=$this->objFunc->listarVendedorMedico($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
				
	function insertarMedico(){
		$this->objFunc=$this->create('MODMedico');	
		if($this->objParam->insertar('id_medico')){
			$this->res=$this->objFunc->insertarMedico($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarMedico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarMedico(){
			$this->objFunc=$this->create('MODMedico');	
		$this->res=$this->objFunc->eliminarMedico($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>