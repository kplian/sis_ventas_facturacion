<?php
/**
*@package pXP
*@file gen-MODTemporalData.php
*@author  (eddy.gutierrez)
*@date 06-11-2018 20:39:08
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODTemporalData extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTemporalData(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_temporal_data_sel';
		$this->transaccion='VF_dad_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_temporal_data','int4');
		$this->captura('razon_social','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('nro_factura','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('id_punto_venta','int4');
		$this->captura('nombre_punto_venta','varchar');				
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarTemporalData(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_temporal_data_ime';
		$this->transaccion='VF_dad_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('razon_social','razon_social','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_factura','nro_factura','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTemporalData(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_temporal_data_ime';
		$this->transaccion='VF_dad_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_temporal_data','id_temporal_data','int4');
		$this->setParametro('razon_social','razon_social','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_factura','nro_factura','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTemporalData(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_temporal_data_ime';
		$this->transaccion='VF_dad_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_temporal_data','id_temporal_data','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>