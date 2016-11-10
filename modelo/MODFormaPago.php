<?php
/**
*@package pXP
*@file gen-MODFormaPago.php
*@author  (jrivera)
*@date 08-10-2015 13:29:06
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFormaPago extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFormaPago(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_forma_pago_sel';
		$this->transaccion='VF_FORPA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('id_venta','id_venta','int4');
				
		//Definicion de la lista del resultado del query
		$this->captura('id_forma_pago','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('codigo','varchar');
		$this->captura('nombre','varchar');
		$this->captura('id_entidad','int4');
		$this->captura('id_moneda','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_moneda','varchar');
		$this->captura('defecto','varchar');
		
		$this->captura('registrar_tarjeta','varchar');
		$this->captura('registrar_tipo_tarjeta','varchar');
		$this->captura('registrar_cc','varchar');
		$this->captura('valor','numeric');
		$this->captura('numero_tarjeta','varchar');
		$this->captura('codigo_tarjeta','varchar');
		$this->captura('tipo_tarjeta','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_forma_pago_ime';
		$this->transaccion='VF_FORPA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('id_entidad','id_entidad','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('defecto','defecto','varchar');
		$this->setParametro('registrar_tarjeta','registrar_tarjeta','varchar');
		$this->setParametro('registrar_cc','registrar_cc','varchar');
		$this->setParametro('registrar_tipo_tarjeta','registrar_tipo_tarjeta','varchar');
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_forma_pago_ime';
		$this->transaccion='VF_FORPA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('id_entidad','id_entidad','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('defecto','defecto','varchar');
		$this->setParametro('registrar_tarjeta','registrar_tarjeta','varchar');
		$this->setParametro('registrar_cc','registrar_cc','varchar');
		$this->setParametro('registrar_tipo_tarjeta','registrar_tipo_tarjeta','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFormaPago(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_forma_pago_ime';
		$this->transaccion='VF_FORPA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_forma_pago','id_forma_pago','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>