<?php
/**
*@package pXP
*@file gen-MODPuntoVenta.php
*@author  (jrivera)
*@date 07-10-2015 21:02:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODPuntoVenta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarPuntoVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_punto_venta_sel';
		$this->transaccion='VF_PUVE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_punto_venta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_sucursal','int4');
		$this->captura('nombre','varchar');
		$this->captura('descripcion','text');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('codigo','varchar');
		$this->captura('habilitar_comisiones','varchar');
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarPuntoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_ime';
		$this->transaccion='VF_PUVE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('habilitar_comisiones','habilitar_comisiones','varchar');
		$this->setParametro('tipo','tipo','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarPuntoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_ime';
		$this->transaccion='VF_PUVE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('habilitar_comisiones','habilitar_comisiones','varchar');
		$this->setParametro('tipo','tipo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarPuntoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_punto_venta_ime';
		$this->transaccion='VF_PUVE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_punto_venta','id_punto_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function listarPuntoVentaCombo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_punto_venta_sel';
		$this->transaccion='VF_PUVECOMBO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_punto_venta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_sucursal','int4');
		$this->captura('nombre','varchar');
		$this->captura('descripcion','text');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('codigo','varchar');
		$this->captura('habilitar_comisiones','varchar');
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>