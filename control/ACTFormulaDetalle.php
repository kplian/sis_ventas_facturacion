<?php
/**
*@package pXP
*@file gen-ACTFormulaDetalle.php
*@author  (admin)
*@date 21-04-2015 13:16:56
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFormulaDetalle extends ACTbase{    
			
	function listarFormulaDetalle(){
		$this->objParam->defecto('ordenacion','id_formula_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
       
        if ($this->objParam->getParametro('id_formula') != '') {
            $this->objParam->addFiltro("fordet.id_formula = ". $this->objParam->getParametro('id_formula'));
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFormulaDetalle','listarFormulaDetalle');
		} else{
			$this->objFunc=$this->create('MODFormulaDetalle');
			
			$this->res=$this->objFunc->listarFormulaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarFormulaDetalleParaInsercion (){
		$this->objParam->defecto('ordenacion','id_producto');

		$this->objParam->defecto('dir_ordenacion','asc');
       
        if ($this->objParam->getParametro('id_formula') != '') {
            $this->objParam->addFiltro("fd.id_formula = ". $this->objParam->getParametro('id_formula'));
        }  
		
		$this->objFunc=$this->create('MODFormulaDetalle');			
		$this->res=$this->objFunc->listarFormulaDetalleParaInsercion($this->objParam);
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFormulaDetalle(){
		$this->objFunc=$this->create('MODFormulaDetalle');	
		if($this->objParam->insertar('id_formula_detalle')){
			$this->res=$this->objFunc->insertarFormulaDetalle($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFormulaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFormulaDetalle(){
			$this->objFunc=$this->create('MODFormulaDetalle');	
		$this->res=$this->objFunc->eliminarFormulaDetalle($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>