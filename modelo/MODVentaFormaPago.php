<?php
/**
*@package pXP
*@file gen-MODVentaFormaPago.php
*@author  (jrivera)
*@date 22-10-2015 14:49:46
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODVentaFormaPago extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarVentaFormaPago(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_forma_pago_sel';
		$this->transaccion='VF_VENFP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta_forma_pago','int4');
		$this->captura('id_forma_pago','int4');
		$this->captura('id_venta','int4');
		$this->captura('monto_mb_efectivo','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('cambio','numeric');
		$this->captura('monto_transaccion','numeric');
		$this->captura('monto','numeric');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarVentaFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_forma_pago_ime';
		$this->transaccion='VF_VENFP_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('monto_mb_efectivo','monto_mb_efectivo','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('cambio','cambio','numeric');
		$this->setParametro('monto_transaccion','monto_transaccion','numeric');
		$this->setParametro('monto','monto','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarVentaFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_forma_pago_ime';
		$this->transaccion='VF_VENFP_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta_forma_pago','id_venta_forma_pago','int4');
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('monto_mb_efectivo','monto_mb_efectivo','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('cambio','cambio','numeric');
		$this->setParametro('monto_transaccion','monto_transaccion','numeric');
		$this->setParametro('monto','monto','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarVentaFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_forma_pago_ime';
		$this->transaccion='VF_VENFP_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta_forma_pago','id_venta_forma_pago','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>