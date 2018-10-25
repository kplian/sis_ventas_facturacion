<?php
/**
*@package pXP
*@file gen-MODSucursalProducto.php
*@author  (admin)
*@date 21-04-2015 03:18:44
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODSucursalProducto extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarSucursalProducto(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_producto_sel';
		$this->transaccion='VF_SPROD_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_sucursal_producto','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_item','int4');		
		$this->captura('precio','numeric');		
		$this->captura('estado_reg','varchar');
		$this->captura('tipo_producto','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('nombre_item','varchar');
		$this->captura('id_concepto_ingas','integer');
		$this->captura('nombre_producto','varchar');
		$this->captura('descripcion_producto','text');
		$this->captura('id_actividad_economica','integer');
		$this->captura('nombre_actividad','varchar');
		$this->captura('requiere_descripcion','varchar');
		$this->captura('id_moneda','integer');
		$this->captura('desc_moneda','varchar');
		$this->captura('contabilizable','varchar');
		$this->captura('excento','varchar');
		
		$this->captura('id_unidad_medida','integer');
		$this->captura('desc_unidad_medida','varchar');
		$this->captura('nandina','VARCHAR');
		$this->captura('ruta_foto','VARCHAR');
		$this->captura('codigo','VARCHAR');
		
		
		
		
		
		 
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarProductoServicioItem(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_producto_sel';
		$this->transaccion='VF_PRODITEFOR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		
		$this->setParametro('tipo_cambio_venta','tipo_cambio_venta','numeric');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('id_venta_fk','id_venta_fk','int4');
				
		//Definicion de la lista del resultado del query
		$this->captura('id_producto','integer');
		$this->captura('tipo','varchar');
		$this->captura('nombre_producto','varchar');		
		$this->captura('descripcion','text');
		$this->captura('precio','numeric');			
		$this->captura('medico','varchar');
		$this->captura('requiere_descripcion','varchar');
		$this->captura('contabilizable','varchar');
		$this->captura('excento','varchar');
		$this->captura('id_unidad_medida','integer');
		$this->captura('codigo_unidad_medida','varchar');		
		$this->captura('ruta_foto','varchar'); 
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function listarItemsFormula(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_sucursal_producto_sel';
		$this->transaccion='VF_PRODFORMU_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('tipo','tipo','varchar');
				
		//Definicion de la lista del resultado del query
		$this->captura('id_producto','integer');
		$this->captura('tipo','varchar');
		$this->captura('nombre_producto','varchar');		
		$this->captura('descripcion','text');
		$this->captura('unidad_medida','varchar'); 
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarSucursalProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_producto_ime';
		$this->transaccion='VF_SPROD_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_item','id_item','int4');
		$this->setParametro('descripcion_producto','descripcion_producto','text');
		$this->setParametro('precio','precio','numeric');
		$this->setParametro('nombre_producto','nombre_producto','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_producto','tipo_producto','varchar');
		$this->setParametro('id_actividad_economica','id_actividad_economica','integer');
		$this->setParametro('requiere_descripcion','requiere_descripcion','varchar');
		$this->setParametro('id_moneda','id_moneda','integer');

		$this->setParametro('contabilizable','contabilizable','varchar');
		$this->setParametro('excento','excento','varchar');

		
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('desc_unidad_medida','desc_unidad_medida','varchar');
		$this->setParametro('nandina','nandina','varchar');
		$this->setParametro('codigo','codigo','VARCHAR');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarSucursalProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_producto_ime';
		$this->transaccion='VF_SPROD_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_producto','id_sucursal_producto','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_item','id_item','int4');
		$this->setParametro('descripcion_producto','descripcion_producto','text');
		$this->setParametro('precio','precio','numeric');
		$this->setParametro('nombre_producto','nombre_producto','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_producto','tipo_producto','varchar');
		$this->setParametro('id_actividad_economica','id_actividad_economica','integer');
		$this->setParametro('requiere_descripcion','requiere_descripcion','varchar');
		$this->setParametro('id_moneda','id_moneda','integer');

		$this->setParametro('contabilizable','contabilizable','varchar');
		$this->setParametro('excento','excento','varchar');

		
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('desc_unidad_medida','desc_unidad_medida','varchar');
		$this->setParametro('nandina','nandina','varchar');
		$this->setParametro('codigo','codigo','VARCHAR');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarSucursalProducto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_sucursal_producto_ime';
		$this->transaccion='VF_SPROD_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal_producto','id_sucursal_producto','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>