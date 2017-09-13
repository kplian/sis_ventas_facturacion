<?php
/**
*@package pXP
*@file gen-MODCliente.php
*@author  (admin)
*@date 20-04-2015 08:41:29
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCliente extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCliente(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_cliente_sel';
		$this->transaccion='VF_CLI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cliente','int4');
		$this->captura('correo','varchar');
		$this->captura('telefono_fijo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('segundo_apellido','varchar');
		$this->captura('nombre_factura','varchar');
		$this->captura('primer_apellido','varchar');
		$this->captura('telefono_celular','varchar');
		$this->captura('nit','varchar');
		$this->captura('otros_correos','varchar');
		$this->captura('otros_telefonos','varchar');
		$this->captura('nombres','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('direccion','varchar');
		$this->captura('lugar','varchar');
		$this->captura('observaciones','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento = 'vef.ft_cliente_ime';
		$this->transaccion = 'VF_CLI_INS';
		$this->tipo_procedimiento = 'IME';
				
		//Define los parametros para la funcion
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('telefono_fijo','telefono_fijo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('segundo_apellido','segundo_apellido','varchar');
		$this->setParametro('nombre_factura','nombre_factura','varchar');
		$this->setParametro('primer_apellido','primer_apellido','varchar');
		$this->setParametro('telefono_celular','telefono_celular','varchar');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('otros_correos','otros_correos','varchar');
		$this->setParametro('otros_telefonos','otros_telefonos','varchar');
		$this->setParametro('nombres','nombres','varchar');
		$this->setParametro('direccion','direccion','varchar');

		$this->setParametro('lugar','lugar','varchar');

		$this->setParametro('observaciones','observaciones','varchar');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cliente_ime';
		$this->transaccion='VF_CLI_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('telefono_fijo','telefono_fijo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('segundo_apellido','segundo_apellido','varchar');
		$this->setParametro('nombre_factura','nombre_factura','varchar');
		$this->setParametro('primer_apellido','primer_apellido','varchar');
		$this->setParametro('telefono_celular','telefono_celular','varchar');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('otros_correos','otros_correos','varchar');
		$this->setParametro('otros_telefonos','otros_telefonos','varchar');
		$this->setParametro('nombres','nombres','varchar');
		$this->setParametro('direccion','direccion','varchar');

		$this->setParametro('lugar','lugar','varchar');


		$this->setParametro('observaciones','observaciones','varchar');
		

		//Ejecuta la instruccion
		$this->armarConsulta();
        
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_cliente_ime';
		$this->transaccion='VF_CLI_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>