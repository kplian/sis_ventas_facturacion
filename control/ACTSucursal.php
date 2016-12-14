<?php
/**
*@package pXP
*@file gen-ACTSucursal.php
*@author  (admin)
*@date 20-04-2015 15:07:50
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSucursal extends ACTbase{    
			
	function listarSucursal(){
		$this->objParam->defecto('ordenacion','id_sucursal');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if($this->objParam->getParametro('tipo_factura') != '') {
                $this->objParam->addFiltro(" ''".$this->objParam->getParametro('tipo_factura')."'' =ANY (tipo_interfaz)");
        }
        
        
        if($this->objParam->getParametro('tipo_usuario') == 'vendedor') {
                $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where suc.id_sucursal = sucusu.id_sucursal and
                                                    sucusu.tipo_usuario = ''vendedor''))) ");
        }

        if($this->objParam->getParametro('tipo_usuario') == 'administrador') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where suc.id_sucursal = sucusu.id_sucursal and
                                                    sucusu.tipo_usuario = ''administrador''))) ");
        }

        if($this->objParam->getParametro('tipo_usuario') == 'cajero') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where suc.id_sucursal = sucusu.id_sucursal and
                                                    sucusu.tipo_usuario = ''cajero''))) ");
        }

        if($this->objParam->getParametro('tipo_usuario') == 'todos') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where suc.id_sucursal = sucusu.id_sucursal))) ");
        }
        
        if($this->objParam->getParametro('id_entidad') != '') {
                $this->objParam->addFiltro(" suc.id_entidad = " . $this->objParam->getParametro('id_entidad'));
        }
		
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursal','listarSucursal');
		} else{
			$this->objFunc=$this->create('MODSucursal');
			
			$this->res=$this->objFunc->listarSucursal($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSucursal(){
		$this->objFunc=$this->create('MODSucursal');	
		if($this->objParam->insertar('id_sucursal')){
			$this->res=$this->objFunc->insertarSucursal($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSucursal($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSucursal(){
			$this->objFunc=$this->create('MODSucursal');	
		$this->res=$this->objFunc->eliminarSucursal($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>