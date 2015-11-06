<?php
/**
*@package pXP
*@file gen-MODPuntoVentaProducto.php
*@author  (jrivera)
*@date 07-10-2015 21:02:03
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODPuntoVentaProducto extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarPuntoVentaProducto(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_punto_venta_producto_sel';
		$this->transaccion='VF_PUVEPRO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_punto_venta_producto','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_sucursal_producto','int4');
		$this->captura('id_punto_venta','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('nombre_producto','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarPuntoVentaProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_producto_ime';
		$this->transaccion='VF_PUVEPRO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_sucursal_producto','id_sucursal_producto','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarPuntoVentaProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_producto_ime';
		$this->transaccion='VF_PUVEPRO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_punto_venta_producto','id_punto_venta_producto','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_sucursal_producto','id_sucursal_producto','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarPuntoVentaProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_producto_ime';
		$this->transaccion='VF_PUVEPRO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_punto_venta_producto','id_punto_venta_producto','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>