<?php
/**
*@package pXP
*@file gen-MODBoletoFp.php
*@author  (jrivera)
*@date 26-11-2015 22:03:35
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODBoletoFp extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarBoletoFp(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_boleto_fp_sel';
		$this->transaccion='VF_BOLFP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_boleto_fp','int4');
		$this->captura('id_boleto','int4');
		$this->captura('id_forma_pago','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('monto','numeric');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('forma_pago','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarBoletoFp(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_fp_ime';
		$this->transaccion='VF_BOLFP_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_boleto','id_boleto','int4');
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('monto','monto','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarBoletoFp(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_fp_ime';
		$this->transaccion='VF_BOLFP_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_boleto_fp','id_boleto_fp','int4');
		$this->setParametro('id_boleto','id_boleto','int4');
		$this->setParametro('id_forma_pago','id_forma_pago','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('monto','monto','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarBoletoFp(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_boleto_fp_ime';
		$this->transaccion='VF_BOLFP_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_boleto_fp','id_boleto_fp','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>