<?php
/**
*@package pXP
*@file gen-MODVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODReportesVentas extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarReporteDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_REPDETBOA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);

		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');

		//Definicion de la lista del resultado del query
		$this->captura('fecha','date');
		$this->captura('correlativo','varchar');
		$this->captura('pasajero','varchar');
		$this->captura('boleto','varchar');
		$this->captura('ruta','varchar');
		$this->captura('concepto','varchar');
		$this->captura('monto_tarjeta','numeric');
		$this->captura('monto_cash','numeric');
		$this->captura('monto','numeric');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarReporteResumen(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_REPRESBOA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);

		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');

		//Definicion de la lista del resultado del query
		$this->captura('fecha','date');		
		$this->captura('concepto','varchar');
		$this->captura('monto_tarjeta','numeric');
		$this->captura('monto_cash','numeric');
		$this->captura('monto','numeric');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarConceptosSucursal(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_CONSUC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion		
		$this->count =false;
		
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
		
		//Definicion de la lista del resultado del query
		$this->captura('nombre','varchar');
				
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>