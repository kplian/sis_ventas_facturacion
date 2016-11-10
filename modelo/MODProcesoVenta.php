<?php
/**
*@package pXP
*@file gen-MODProcesoVenta.php
*@author  (jrivera)
*@date 22-03-2016 21:50:14
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODProcesoVenta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarProcesoVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_proceso_venta_sel';
		$this->transaccion='VF_PROCON_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_proceso_venta','int4');
		$this->captura('tipos','_varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_desde','date');
		$this->captura('id_int_comprobante','int4');
		$this->captura('fecha_hasta','date');
		$this->captura('estado','varchar');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
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
			
	function insertarProcesoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proceso_venta_ime';
		$this->transaccion='VF_PROCON_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('tipos','tipos','_varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('id_int_comprobante','id_int_comprobante','int4');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		$this->setParametro('estado','estado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarProcesoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proceso_venta_ime';
		$this->transaccion='VF_PROCON_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proceso_venta','id_proceso_venta','int4');
		$this->setParametro('tipos','tipos','_varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('id_int_comprobante','id_int_comprobante','int4');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		$this->setParametro('estado','estado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarProcesoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proceso_venta_ime';
		$this->transaccion='VF_PROCON_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proceso_venta','id_proceso_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>