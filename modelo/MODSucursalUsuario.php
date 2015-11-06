<?php
/**
*@package pXP
*@file gen-MODSucursalUsuario.php
*@author  (admin)
*@date 21-04-2015 07:33:37
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODSucursalUsuario extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarSucursalUsuario(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_usuario_sel';
		$this->transaccion='VF_SUCUSU_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_sucursal_usuario','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_punto_venta','int4');
		$this->captura('id_usuario','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('tipo_usuario','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('cuenta','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarSucursalUsuario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_usuario_ime';
		$this->transaccion='VF_SUCUSU_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('id_usuario','id_usuario','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_usuario','tipo_usuario','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarSucursalUsuario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_usuario_ime';
		$this->transaccion='VF_SUCUSU_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_usuario','id_sucursal_usuario','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('id_usuario','id_usuario','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_usuario','tipo_usuario','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarSucursalUsuario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_usuario_ime';
		$this->transaccion='VF_SUCUSU_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_usuario','id_sucursal_usuario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>