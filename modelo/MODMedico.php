<?php
/**
*@package pXP
*@file gen-MODMedico.php
*@author  (admin)
*@date 20-04-2015 11:17:42
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODMedico extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarMedico(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_medico_sel';
		$this->transaccion='VF_MED_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_medico','int4');
		$this->captura('correo','varchar');
		$this->captura('telefono_fijo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('segundo_apellido','varchar');
		$this->captura('porcentaje','int4');
		$this->captura('telefono_celular','varchar');
		$this->captura('primer_apellido','varchar');
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
		$this->captura('fecha_nacimiento','date');
		$this->captura('especialidad','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarVendedorMedico(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='vef.ft_medico_sel';
        $this->transaccion='VF_VENMED_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        
        $this->setParametro('id_sucursal','id_sucursal','integer');
                
        //Definicion de la lista del resultado del query
        $this->captura('id_vendedor_medico','varchar');
        $this->captura('nombre_vendedor_medico','varchar');
        $this->captura('tipo','varchar');
        
        
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        
        //Devuelve la respuesta
        return $this->respuesta;
    }
			
	function insertarMedico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_medico_ime';
		$this->transaccion='VF_MED_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('telefono_fijo','telefono_fijo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('segundo_apellido','segundo_apellido','varchar');
		$this->setParametro('porcentaje','porcentaje','int4');
		$this->setParametro('telefono_celular','telefono_celular','varchar');
		$this->setParametro('primer_apellido','primer_apellido','varchar');
		$this->setParametro('otros_correos','otros_correos','varchar');
		$this->setParametro('otros_telefonos','otros_telefonos','varchar');
		$this->setParametro('nombres','nombres','varchar');
		$this->setParametro('fecha_nacimiento','fecha_nacimiento','date');
		$this->setParametro('especialidad','especialidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarMedico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_medico_ime';
		$this->transaccion='VF_MED_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_medico','id_medico','int4');
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('telefono_fijo','telefono_fijo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('segundo_apellido','segundo_apellido','varchar');
		$this->setParametro('porcentaje','porcentaje','int4');
		$this->setParametro('telefono_celular','telefono_celular','varchar');
		$this->setParametro('primer_apellido','primer_apellido','varchar');
		$this->setParametro('otros_correos','otros_correos','varchar');
		$this->setParametro('otros_telefonos','otros_telefonos','varchar');
		$this->setParametro('nombres','nombres','varchar');
		$this->setParametro('fecha_nacimiento','fecha_nacimiento','date');
		$this->setParametro('especialidad','especialidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarMedico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_medico_ime';
		$this->transaccion='VF_MED_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_medico','id_medico','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>