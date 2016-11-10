<?php
/**
*@package pXP
*@file gen-MODTipoDescripcion.php
*@author  (admin)
*@date 23-04-2016 02:03:14
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODTipoDescripcion extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTipoDescripcion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_tipo_descripcion_sel';
		$this->transaccion='VF_TDE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_descripcion','int4');
		$this->captura('fila','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('columna','numeric');
		$this->captura('nombre','varchar');
		$this->captura('obs','varchar');
		$this->captura('codigo','varchar');
		$this->captura('id_sucursal','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarTipoDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_descripcion_ime';
		$this->transaccion='VF_TDE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('fila','fila','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('columna','columna','numeric');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('obs','obs','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('id_sucursal','id_sucursal','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTipoDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_descripcion_ime';
		$this->transaccion='VF_TDE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_descripcion','id_tipo_descripcion','int4');
		$this->setParametro('fila','fila','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('columna','columna','numeric');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('obs','obs','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('id_sucursal','id_sucursal','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTipoDescripcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_tipo_descripcion_ime';
		$this->transaccion='VF_TDE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_descripcion','id_tipo_descripcion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>