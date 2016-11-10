<?php
/**
*@package pXP
*@file gen-MODActividadEconomica.php
*@author  (jrivera)
*@date 06-10-2015 21:23:23
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODActividadEconomica extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_actividad_economica_sel';
		$this->transaccion='VF_ACTECO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_actividad_economica','int4');
		$this->captura('codigo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('descripcion','text');
		$this->captura('nombre','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>