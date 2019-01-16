<?php
/**
*@package pXP
*@file gen-MODProveedorCuentaBancoCobro.php
*@author  (m.mamani)
*@date 22-11-2018 22:19:44
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODProveedorCuentaBancoCobro extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarProveedorCuentaBancoCobro(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_proveedor_cuenta_banco_cobro_sel';
		$this->transaccion='VF_PCC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_proveedor_cuenta_banco_cobro','int4');
		$this->captura('id_proveedor','int4');
        $this->captura('id_institucion','int4');
        $this->captura('id_moneda','int4');
        $this->captura('desc_proveedor','varchar');
        $this->captura('tipo','varchar');
        $this->captura('desc_nombre','varchar');
        $this->captura('desc_moneda','varchar');
        $this->captura('fecha_alta','date');
        $this->captura('fecha_baja','date');
        $this->captura('nro_cuenta_bancario','text');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
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
			
	function insertarProveedorCuentaBancoCobro(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proveedor_cuenta_banco_cobro_ime';
		$this->transaccion='VF_PCC_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
        $this->setParametro('id_institucion','id_institucion','int4');
		$this->setParametro('id_proveedor','id_proveedor','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('nro_cuenta_bancario','nro_cuenta_bancario','text');
		$this->setParametro('fecha_alta','fecha_alta','date');
        $this->setParametro('id_moneda','id_moneda','int4');
        $this->setParametro('fecha_baja','fecha_baja','date');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarProveedorCuentaBancoCobro(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proveedor_cuenta_banco_cobro_ime';
		$this->transaccion='VF_PCC_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proveedor_cuenta_banco_cobro','id_proveedor_cuenta_banco_cobro','int4');
        $this->setParametro('id_institucion','id_institucion','int4');
        $this->setParametro('id_proveedor','id_proveedor','int4');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('tipo','tipo','varchar');
        $this->setParametro('nro_cuenta_bancario','nro_cuenta_bancario','text');
        $this->setParametro('fecha_alta','fecha_alta','date');
        $this->setParametro('id_moneda','id_moneda','int4');
        $this->setParametro('fecha_baja','fecha_baja','date');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarProveedorCuentaBancoCobro(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_proveedor_cuenta_banco_cobro_ime';
		$this->transaccion='VF_PCC_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proveedor_cuenta_banco_cobro','id_proveedor_cuenta_banco_cobro','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>