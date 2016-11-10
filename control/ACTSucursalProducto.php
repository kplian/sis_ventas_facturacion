<?php
/**
*@package pXP
*@file gen-ACTSucursalProducto.php
*@author  (admin)
*@date 21-04-2015 03:18:44
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSucursalProducto extends ACTbase{    
			
	function listarSucursalProducto(){
		$this->objParam->defecto('ordenacion','id_sucursal_producto');

		$this->objParam->defecto('dir_ordenacion','asc');
        
        if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro("sprod.id_sucursal = ". $this->objParam->getParametro('id_sucursal'));
        }   
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalProducto','listarSucursalProducto');
		} else{
			$this->objFunc=$this->create('MODSucursalProducto');
			
			$this->res=$this->objFunc->listarSucursalProducto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarProductoServicioItem(){		
        
        
		if ($this->objParam->getParametro('tipo') != '') {
            $this->objParam->addFiltro("todo.tipo = ''". $this->objParam->getParametro('tipo')."''");
        }  
        
         
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalProducto','listarProductoServicioItem');
		} else{
			$this->objFunc=$this->create('MODSucursalProducto');
			
			$this->res=$this->objFunc->listarProductoServicioItem($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarItemsFormula(){		
        
        
		if ($this->objParam->getParametro('tipo') != '') {
            $this->objParam->addFiltro("todo.tipo = ''". $this->objParam->getParametro('tipo')."''");
        }  
        
         
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalProducto','listarItemsFormula');
		} else{
			$this->objFunc=$this->create('MODSucursalProducto');
			
			$this->res=$this->objFunc->listarItemsFormula($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSucursalProducto(){
		$this->objFunc=$this->create('MODSucursalProducto');	
		if($this->objParam->insertar('id_sucursal_producto')){
			$this->res=$this->objFunc->insertarSucursalProducto($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSucursalProducto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSucursalProducto(){
			$this->objFunc=$this->create('MODSucursalProducto');	
		$this->res=$this->objFunc->eliminarSucursalProducto($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>