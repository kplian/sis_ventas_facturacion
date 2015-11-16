<?php
/**
*@package pXP
*@file gen-ACTFormula.php
*@author  (admin)
*@date 21-04-2015 09:14:49
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFormula extends ACTbase{    
			
	function listarFormula(){
		$this->objParam->defecto('ordenacion','id_formula');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFormula','listarFormula');
		} else{
			$this->objFunc=$this->create('MODFormula');
			
			$this->res=$this->objFunc->listarFormula($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFormula(){
		$this->objFunc=$this->create('MODFormula');	
		if($this->objParam->insertar('id_formula')){
			$this->res=$this->objFunc->insertarFormula($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFormula($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    
    function insertarFormulaCompleta(){
        $this->objFunc=$this->create('MODFormula'); 
        
        $this->res=$this->objFunc->insertarFormulaCompleta($this->objParam);           
        
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
						
	function eliminarFormula(){
			$this->objFunc=$this->create('MODFormula');	
		$this->res=$this->objFunc->eliminarFormula($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>