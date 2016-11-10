<?php
/**
*@package pXP
*@file gen-MODFormulaDetalle.php
*@author  (admin)
*@date 21-04-2015 13:16:56
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFormulaDetalle extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFormulaDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_formula_detalle_sel';
		$this->transaccion='VF_FORDET_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_formula_detalle','int4');
		$this->captura('id_producto','int4');
		$this->captura('id_formula','int4');
		$this->captura('cantidad','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('nombre_producto','varchar');
		$this->captura('tipo','varchar');
        $this->captura('unidad_medida','varchar');
        
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarFormulaDetalleParaInsercion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_formula_detalle_sel';
		$this->transaccion='VF_FORDETINS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setCount(false);
		
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_punto_venta','id_punto_venta','int4');
		$this->setParametro('id_vendedor_medico','id_vendedor_medico','varchar');
		$this->setParametro('porcentaje_descuento','porcentaje_descuento','int4');
				
		//Definicion de la lista del resultado del query
		$this->captura('id_producto','int4');
		$this->captura('tipo','varchar');
		$this->captura('nombre_producto','varchar');
		$this->captura('descripcion','text');
		$this->captura('cantidad','numeric');
		$this->captura('precio_unitario','numeric');
		$this->captura('precio_total_sin_descuento','numeric');
		$this->captura('porcentaje_descuento','integer');
		$this->captura('precio_total','numeric');
		$this->captura('id_vendedor_medico','varchar');
		$this->captura('nombre_vendedor_medico','varchar');
		$this->captura('contabilizable','varchar');
		$this->captura('excento','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFormulaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_detalle_ime';
		$this->transaccion='VF_FORDET_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_item','id_item','int4');
		$this->setParametro('id_formula','id_formula','int4');
		$this->setParametro('cantidad_det','cantidad','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFormulaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_detalle_ime';
		$this->transaccion='VF_FORDET_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_formula_detalle','id_formula_detalle','int4');
		$this->setParametro('id_item','id_item','int4');
		$this->setParametro('id_formula','id_formula','int4');
		$this->setParametro('cantidad_det','cantidad','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFormulaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_detalle_ime';
		$this->transaccion='VF_FORDET_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_formula_detalle','id_formula_detalle','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>