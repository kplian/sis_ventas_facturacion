<?php
/**
*@package pXP
*@file gen-MODVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODVenta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VEN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_cliente','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('nro_tramite','varchar');
		$this->captura('a_cuenta','numeric');
		$this->captura('total_venta','numeric');
		$this->captura('fecha_estimada_entrega','date');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('estado','varchar');
        $this->captura('nombre_completo','text');
        $this->captura('nombre_sucursal','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');		
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('a_cuenta','a_cuenta','numeric');
		$this->setParametro('total_venta','total_venta','numeric');
		$this->setParametro('fecha_estimada_entrega','fecha_estimada_entrega','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('a_cuenta','a_cuenta','numeric');
		$this->setParametro('total_venta','total_venta','numeric');
		$this->setParametro('fecha_estimada_entrega','fecha_estimada_entrega','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>