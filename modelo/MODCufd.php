<?php
/**
*@package pXP
*@file gen-MODCufd.php
*@author  (admin)
*@date 22-01-2019 02:23:54
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCufd extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCufd(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_cufd_sel';
		$this->transaccion='VF_CUFD_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cufd','int4');
		$this->captura('codigo','varchar');
		$this->captura('fecha_inicio','timestamp');
		$this->captura('fecha_fin','timestamp');
		$this->captura('estado_reg','varchar');
		$this->captura('id_cuis','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
		function verificarCufd(){
				
		$this->procedimiento='vef.ft_cufd_sel';
		$this->transaccion='VF_VERCUFD_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion	
		 $this->setCount(false);
		 
		$this->captura('alerta', 'varchar');
		$this->captura('fecha', 'varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;	
	}
		
	
	function insertarCufd(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cufd_ime';
		$this->transaccion='VF_CUFD_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','timestamp');
		$this->setParametro('fecha_fin','fecha_fin','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_cuis','id_cuis','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCufd(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cufd_ime';
		$this->transaccion='VF_CUFD_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cufd','id_cufd','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','timestamp');
		$this->setParametro('fecha_fin','fecha_fin','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_cuis','id_cuis','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCufd(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cufd_ime';
		$this->transaccion='VF_CUFD_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cufd','id_cufd','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>