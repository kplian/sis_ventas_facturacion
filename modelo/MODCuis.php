<?php
/**
*@package pXP
*@file gen-MODCuis.php
*@author  (admin)
*@date 21-01-2019 15:18:39
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCuis extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCuis(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_cuis_sel';
		$this->transaccion='VF_CUIS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cuis','int4');
		$this->captura('codigo','varchar');
		$this->captura('fecha_fin','timestamp');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_inicio','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCuis(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuis_ime';
		$this->transaccion='VF_CUIS_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('fecha_fin','fecha_fin','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','timestamp');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCuis(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuis_ime';
		$this->transaccion='VF_CUIS_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuis','id_cuis','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('fecha_fin','fecha_fin','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','timestamp');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCuis(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuis_ime';
		$this->transaccion='VF_CUIS_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuis','id_cuis','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>