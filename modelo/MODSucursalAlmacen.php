<?php
/**
*@package pXP
*@file gen-MODSucursalAlmacen.php
*@author  (admin)
*@date 21-04-2015 07:33:41
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODSucursalAlmacen extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarSucursalAlmacen(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_almacen_sel';
		$this->transaccion='VF_SUCALM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_sucursal_almacen','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_almacen','int4');
		$this->captura('tipo_almacen','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('nombre_almacen','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarSucursalAlmacen(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_almacen_ime';
		$this->transaccion='VF_SUCALM_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_almacen','id_almacen','int4');
		$this->setParametro('tipo_almacen','tipo_almacen','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarSucursalAlmacen(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_almacen_ime';
		$this->transaccion='VF_SUCALM_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_almacen','id_sucursal_almacen','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_almacen','id_almacen','int4');
		$this->setParametro('tipo_almacen','tipo_almacen','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarSucursalAlmacen(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_almacen_ime';
		$this->transaccion='VF_SUCALM_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_almacen','id_sucursal_almacen','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>