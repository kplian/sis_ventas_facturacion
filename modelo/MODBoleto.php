<?php
/**
*@package pXP
*@file gen-MODBoleto.php
*@author  (jrivera)
*@date 26-11-2015 22:03:32
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODBoleto extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarBoleto(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_boleto_sel';
		$this->transaccion='VF_BOL_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_boleto','int4');
		$this->captura('id_punto_venta','int4');
		$this->captura('numero','varchar');
		$this->captura('ruta','varchar');
		$this->captura('estado_reg','varchar');
		
		$this->captura('fecha','date');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		$this->captura('id_forma_pago','int4');
		$this->captura('forma_pago','varchar');
		$this->captura('monto','numeric');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	
			
	function insertarBoleto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_ime';
		$this->transaccion='VF_BOL_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('numero','numero','varchar');
		$this->setParametro('ruta','ruta','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('fecha','fecha','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarBoleto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_ime';
		$this->transaccion='VF_BOL_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_boleto','id_boleto','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('numero','numero','varchar');
		$this->setParametro('ruta','ruta','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('fecha','fecha','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarBoleto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_ime';
		$this->transaccion='VF_BOL_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_boleto','id_boleto','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>