<?php
/**
*@package pXP
*@file gen-MODCuf.php
*@author  (admin)
*@date 21-01-2019 15:18:42
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCuf extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCuf(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_cuf_sel';
		$this->transaccion='VF_CUF_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cuf','int4');
		$this->captura('nro_factura','int4');
		$this->captura('codigo_documento_fiscla','int4');
		$this->captura('nit','int4');
		$this->captura('base11','int4');
		$this->captura('sucursal','numeric');
		$this->captura('punto_venta','int4');
		$this->captura('fecha_emision','timestamp');
		$this->captura('modalidad','int4');
		$this->captura('codigo_autoverificador','int4');
		$this->captura('tipo_documento_sector','int4');
		$this->captura('tipo_emision','int4');
		$this->captura('base16','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('concatenacion','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
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
			
	function insertarCuf(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuf_ime';
		$this->transaccion='VF_CUF_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('nro_factura','nro_factura','int4');
		$this->setParametro('codigo_documento_fiscla','codigo_documento_fiscla','int4');
		$this->setParametro('nit','nit','int4');
		$this->setParametro('base11','base11','int4');
		$this->setParametro('sucursal','sucursal','numeric');
		$this->setParametro('punto_venta','punto_venta','int4');
		$this->setParametro('fecha_emision','fecha_emision','timestamp');
		$this->setParametro('modalidad','modalidad','int4');
		$this->setParametro('codigo_autoverificador','codigo_autoverificador','int4');
		$this->setParametro('tipo_documento_sector','tipo_documento_sector','int4');
		$this->setParametro('tipo_emision','tipo_emision','int4');
		$this->setParametro('base16','base16','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('concatenacion','concatenacion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCuf(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuf_ime';
		$this->transaccion='VF_CUF_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuf','id_cuf','int4');
		$this->setParametro('nro_factura','nro_factura','int4');
		$this->setParametro('codigo_documento_fiscla','codigo_documento_fiscla','int4');
		$this->setParametro('nit','nit','int4');
		$this->setParametro('base11','base11','int4');
		$this->setParametro('sucursal','sucursal','numeric');
		$this->setParametro('punto_venta','punto_venta','int4');
		$this->setParametro('fecha_emision','fecha_emision','timestamp');
		$this->setParametro('modalidad','modalidad','int4');
		$this->setParametro('codigo_autoverificador','codigo_autoverificador','int4');
		$this->setParametro('tipo_documento_sector','tipo_documento_sector','int4');
		$this->setParametro('tipo_emision','tipo_emision','int4');
		$this->setParametro('base16','base16','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('concatenacion','concatenacion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCuf(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cuf_ime';
		$this->transaccion='VF_CUF_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuf','id_cuf','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>