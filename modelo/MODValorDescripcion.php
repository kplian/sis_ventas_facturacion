<?php
/**
*@package pXP
*@file gen-MODValorDescripcion.php
*@author  (admin)
*@date 23-04-2016 14:24:45
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODValorDescripcion extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarValorDescripcion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_valor_descripcion_sel';
		$this->transaccion='VF_vald_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_valor_descripcion','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('valor','varchar');
		$this->captura('id_tipo_descripcion','int4');
		$this->captura('obs','varchar');
		$this->captura('id_venta','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');		
		$this->captura('codigo','varchar');
		$this->captura('nombre','varchar');
		$this->captura('columna','numeric');
		$this->captura('fila','numeric');
		$this->captura('obs_tipo','varchar');
		$this->captura('valor_label','varchar');
		
		
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarValorDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_valor_descripcion_ime';
		$this->transaccion='VF_vald_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('valor','valor','varchar');
		$this->setParametro('id_tipo_descripcion','id_tipo_descripcion','int4');
		$this->setParametro('obs','obs','varchar');
		$this->setParametro('id_venta','id_venta','int4');		
		$this->setParametro('valor_label','valor_label','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarValorDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_valor_descripcion_ime';
		$this->transaccion='VF_vald_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_valor_descripcion','id_valor_descripcion','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('valor','valor','varchar');
		$this->setParametro('id_tipo_descripcion','id_tipo_descripcion','int4');
		$this->setParametro('obs','obs','varchar');
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('valor_label','valor_label','varchar');
		
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarValorDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_valor_descripcion_ime';
		$this->transaccion='VF_vald_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_valor_descripcion','id_valor_descripcion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>