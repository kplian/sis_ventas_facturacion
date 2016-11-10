<?php
/**
*@package pXP
*@file gen-ACTFormaPago.php
*@author  (jrivera)
*@date 08-10-2015 13:29:06
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFormaPago extends ACTbase{    
			
	function listarFormaPago(){
		$this->objParam->defecto('ordenacion','id_forma_pago');
		
		if($this->objParam->getParametro('id_entidad') != '') {
                $this->objParam->addFiltro(" forpa.id_entidad = " . $this->objParam->getParametro('id_entidad'));
        }
		
		if($this->objParam->getParametro('defecto') == 'si') {
                $this->objParam->addFiltro(" forpa.defecto = ''si''");
				//$filtro_adicional = " and tipo_moneda = ''moneda_base''";
				
        }
		
		if($this->objParam->getParametro('id_sucursal') != '') {
                $this->objParam->addFiltro(" forpa.id_moneda in (select id_moneda 
                												from vef.tsucursal_moneda
                												where id_sucursal = " . $this->objParam->getParametro('id_sucursal') ." $filtro_adicional)");
				$this->objParam->addFiltro(" forpa.id_entidad in (select id_entidad 
                												from vef.tsucursal
                												where id_sucursal = " . $this->objParam->getParametro('id_sucursal') ." )");
        }
		
		if($this->objParam->getParametro('id_punto_venta') != '') {
                $this->objParam->addFiltro(" forpa.id_moneda in (select id_moneda 
                												from vef.tsucursal_moneda sm
                												inner join vef.tpunto_venta pv on pv.id_sucursal = sm.id_sucursal
                												where id_punto_venta = " . $this->objParam->getParametro('id_punto_venta') ." $filtro_adicional)");

                $this->objParam->addFiltro(" forpa.id_entidad in (select id_entidad
                												from vef.tpunto_venta pv
                												inner join vef.tsucursal s on s.id_sucursal = pv.id_sucursal
                												where id_punto_venta = " . $this->objParam->getParametro('id_punto_venta') ." )");
        }
		
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFormaPago','listarFormaPago');
		} else{
			$this->objFunc=$this->create('MODFormaPago');
			
			$this->res=$this->objFunc->listarFormaPago($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFormaPago(){
		$this->objFunc=$this->create('MODFormaPago');	
		if($this->objParam->insertar('id_forma_pago')){
			$this->res=$this->objFunc->insertarFormaPago($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFormaPago($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFormaPago(){
			$this->objFunc=$this->create('MODFormaPago');	
		$this->res=$this->objFunc->eliminarFormaPago($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>