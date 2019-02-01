<?php
/**
*@package pXP
*@file gen-ACTCufd.php
*@author  (admin)
*@date 22-01-2019 02:23:54
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

include "../../../sis_siat/control/SiatClassWs.inc";

class ACTCufd extends ACTbase{    
			
	function listarCufd(){
			
		$this->objParam->defecto('ordenacion','id_cufd');
		
		if($this->objParam->getParametro('id_cuis') != '') {
                $this->objParam->addFiltro(" cufd.id_cuis = " . $this->objParam->getParametro('id_cuis'));
        }
		

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCufd','listarCufd');
		} else{
			$this->objFunc=$this->create('MODCufd');
			
			$this->res=$this->objFunc->listarCufd($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
		
	function verificarCufd(){
		if($this->objParam->getParametro('id_cuis') != '') {
                $this->objParam->addFiltro(" cf.id_cuis = " . $this->objParam->getParametro('id_cuis'));
        }	
		$this->objFunc=$this->create('MODCufd');	
		$this->respuesta=$this->objFunc->verificarCufd($this->objParam);
		
		$this->respuesta->imprimirRespuesta($this->respuesta->generarJson());		
	}
			
	function insertarCufd(){
		$this->objFunc=$this->create('MODCufd');	
		if($this->objParam->insertar('id_cufd')){
			$this->res=$this->objFunc->insertarCufd($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCufd($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function registrarCufd(){
				
		$wsOperaciones= new WsFacturacionOperaciones($_SESSION["_URLWS_OPERACIONES"],2,'2E07180BA7E',1,1009393025,'713E32B4',0);
		$resultop = $wsOperaciones->solicitudCufdOp();
		$rop = $wsOperaciones->ConvertObjectToArray($resultop);
		//$codigo_siat = $rop[2];
		$this->respuesta=new Mensaje();	
		foreach ($rop as $campos){
			$this->respuesta->setDatos($campos);									
		};
			$this->respuesta->imprimirRespuesta($this->respuesta->generarJson());		
	}
						
	function eliminarCufd(){
			$this->objFunc=$this->create('MODCufd');	
		$this->res=$this->objFunc->eliminarCufd($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>