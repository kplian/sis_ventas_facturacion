<?php
/**
*@package pXP
*@file gen-ACTPuntoVenta.php
*@author  (jrivera)
*@date 07-10-2015 21:02:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTPuntoVenta extends ACTbase{    
			
	function listarPuntoVenta(){
		$this->objParam->defecto('ordenacion','id_punto_venta');
		if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro(" puve.id_sucursal = " .  $this->objParam->getParametro('id_sucursal'));
        }

        if($this->objParam->getParametro('tipo_factura') != '') {
            $this->objParam->addFiltro(" ''".$this->objParam->getParametro('tipo_factura')."'' =ANY (suc.tipo_interfaz)");
        }
		
		if($this->objParam->getParametro('tipo_usuario') == 'vendedor') {
                $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where puve.id_punto_venta = sucusu.id_punto_venta and
                                                    sucusu.tipo_usuario = ''vendedor''))) ");
        }

		if($this->objParam->getParametro('lugar') != ''){
			$this->objParam->addFiltro(" puve.id_sucursal in (select suc.id_sucursal from vef.tsucursal suc
						  inner join param.tlugar lug on lug.id_lugar = suc.id_lugar where lug.codigo=''".
					$this->objParam->getParametro('lugar')."'')");
		}

        if($this->objParam->getParametro('tipo_usuario') == 'administrador') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where puve.id_punto_venta = sucusu.id_punto_venta and
                                                    sucusu.tipo_usuario = ''administrador''))) ");
        }

        if($this->objParam->getParametro('tipo_usuario') == 'cajero') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where puve.id_punto_venta = sucusu.id_punto_venta and
                                                    sucusu.tipo_usuario = ''cajero''))) ");
        }

        if($this->objParam->getParametro('tipo_usuario') == 'todos') {
            $this->objParam->addFiltro(" (1 in (select id_rol from segu.tusuario_rol ur where ur.id_usuario = " . $_SESSION["ss_id_usuario"] . " ) or (
                                                " . $_SESSION["ss_id_usuario"] .  " in (select id_usuario from
                                                vef.tsucursal_usuario sucusu where puve.id_punto_venta = sucusu.id_punto_venta
                                                    ))) ");
        }
		
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODPuntoVenta','listarPuntoVenta');
		} else{
			$this->objFunc=$this->create('MODPuntoVenta');
			
			$this->res=$this->objFunc->listarPuntoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarPuntoVenta(){
		$this->objFunc=$this->create('MODPuntoVenta');	
		if($this->objParam->insertar('id_punto_venta')){
			$this->res=$this->objFunc->insertarPuntoVenta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarPuntoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarPuntoVenta(){
			$this->objFunc=$this->create('MODPuntoVenta');	
		$this->res=$this->objFunc->eliminarPuntoVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarPuntoVentaCombo(){
		$this->objParam->defecto('ordenacion','id_punto_venta');
        $this->objParam->defecto('dir_ordenacion','asc');
		$this->objParam->defecto('cantidad',10000000);
		$this->objParam->defecto('puntero', 0);	
		
		
		if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro("puve.id_sucursal = " .  $this->objParam->getParametro('id_sucursal'));
        }

		//var_dump($this->objParam);
	
		$this->objFunc=$this->create('MODPuntoVenta');
			
		$this->res=$this->objFunc->listarPuntoVentaCombo($this->objParam);

		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>