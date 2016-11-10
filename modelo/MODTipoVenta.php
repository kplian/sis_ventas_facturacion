<?php
/**
*@package pXP
*@file gen-MODTipoVenta.php
*@author  (jrivera)
*@date 22-03-2016 15:29:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODTipoVenta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTipoVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_tipo_venta_sel';
		$this->transaccion='VF_TIPVEN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_venta','int4');
		$this->captura('codigo_relacion_contable','varchar');
		$this->captura('nombre','varchar');
		$this->captura('tipo_base','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('codigo','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('id_plantilla','int4');
		$this->captura('desc_plantilla','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarTipoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_venta_ime';
		$this->transaccion='VF_TIPVEN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo_relacion_contable','codigo_relacion_contable','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('tipo_base','tipo_base','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('id_plantilla','id_plantilla','integer');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTipoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_venta_ime';
		$this->transaccion='VF_TIPVEN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_venta','id_tipo_venta','int4');
		$this->setParametro('codigo_relacion_contable','codigo_relacion_contable','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('tipo_base','tipo_base','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('id_plantilla','id_plantilla','integer');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTipoVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_venta_ime';
		$this->transaccion='VF_TIPVEN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_venta','id_tipo_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>