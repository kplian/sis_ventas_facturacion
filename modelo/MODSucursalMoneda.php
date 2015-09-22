<?php
/**
*@package pXP
*@file gen-MODSucursalMoneda.php
*@author  (admin)
*@date 22-09-2015 06:11:27
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODSucursalMoneda extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarSucursalMoneda(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_moneda_sel';
		$this->transaccion='VF_SUCMON_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_sucursal_moneda','int4');
		$this->captura('id_moneda','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('tipo_moneda','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('desc_moneda','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarSucursalMoneda(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_moneda_ime';
		$this->transaccion='VF_SUCMON_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_moneda','tipo_moneda','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarSucursalMoneda(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_moneda_ime';
		$this->transaccion='VF_SUCMON_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_moneda','id_sucursal_moneda','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_moneda','tipo_moneda','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarSucursalMoneda(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_moneda_ime';
		$this->transaccion='VF_SUCMON_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_moneda','id_sucursal_moneda','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>