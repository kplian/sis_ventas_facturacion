<?php
/**
*@package pXP
*@file gen-MODVentaDetalle.php
*@author  (admin)
*@date 01-06-2015 09:21:07
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODVentaDetalle extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarVentaDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_detalle_sel';
		$this->transaccion='VF_VEDET_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta_detalle','int4');
		$this->captura('id_venta','int4');
		$this->captura('id_producto','int4');		
		$this->captura('tipo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('cantidad','numeric');
		$this->captura('precio_unitario','numeric');		
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('precio_total','numeric');        
        $this->captura('nombre_producto','varchar');
        $this->captura('porcentaje_descuento','numeric');       
        $this->captura('precio_total_sin_descuento','numeric');
        $this->captura('id_vendedor_medico','varchar');
        $this->captura('nombre_vendedor_medico','varchar');
		$this->captura('requiere_descripcion','varchar');
		$this->captura('descripcion','text');		
		$this->captura('bruto','varchar');  
		$this->captura('ley','varchar');  
		$this->captura('kg_fino','varchar');		
		$this->captura('id_unidad_medida','integer');
		$this->captura('codigo_unidad_medida','varchar');
		$this->captura('ruta_foto','varchar');
		$this->captura('codigo_unidad_cig','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

   function listarVentaDetalleVb(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_detalle_sel';
		$this->transaccion='VF_VEDETVB_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta_detalle','int4');
		$this->captura('id_venta','int4');
		$this->captura('id_producto','int4');		
		$this->captura('tipo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('cantidad','numeric');
		$this->captura('precio_unitario','numeric');		
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('precio_total','numeric');        
        $this->captura('nombre_producto','varchar');
        $this->captura('porcentaje_descuento','numeric');       
        $this->captura('precio_total_sin_descuento','numeric');
        $this->captura('id_vendedor_medico','varchar');
        $this->captura('nombre_vendedor_medico','varchar');
		$this->captura('requiere_descripcion','varchar');
		$this->captura('descripcion','text');		
		$this->captura('bruto','varchar');  
		$this->captura('ley','varchar');  
		$this->captura('kg_fino','varchar');		
		$this->captura('id_unidad_medida','integer');
		$this->captura('codigo_unidad_medida','varchar');
		$this->captura('ruta_foto','varchar');
		
		$this->captura('estado','varchar');
		$this->captura('obs','varchar');
		$this->captura('serie','varchar');
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}



			
	function insertarVentaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_detalle_ime';
		$this->transaccion='VF_VEDET_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('id_item','id_item','int4');
		$this->setParametro('id_sucursal_producto','id_sucursal_producto','int4');
		$this->setParametro('id_formula','id_formula','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('cantidad_det','cantidad','numeric');
		$this->setParametro('precio','precio','numeric');
		$this->setParametro('sw_porcentaje_formula','sw_porcentaje_formula','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarVentaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_detalle_ime';
		$this->transaccion='VF_VEDET_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta_detalle','id_venta_detalle','int4');

		$this->setParametro('descripcion','descripcion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function actulizarVentaDetallePedido(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_detalle_ime';
		$this->transaccion='VF_VEDETACT_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta_detalle','id_venta_detalle','int4');		
		$this->setParametro('serie','serie','varchar');
		$this->setParametro('obs','obs','varchar');
		$this->setParametro('estado','estado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	
			
	function eliminarVentaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_detalle_ime';
		$this->transaccion='VF_VEDET_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta_detalle','id_venta_detalle','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarPedidoDetalleCliente(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_detalle_sel';
		$this->transaccion='VF_PEDDETCLI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('fecha','date');
		$this->captura('nombre_completo','text');
		$this->captura('producto','varchar');
        	$this->captura('cantidad','numeric');  
        	$this->captura('id_estado_wf','int4');
        	$this->captura('estado_gral','varchar');
        	$this->captura('estado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>
