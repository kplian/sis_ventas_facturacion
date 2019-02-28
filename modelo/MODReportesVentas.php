<?php
/**
*@package pXP
*@file gen-MODVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
* 		ISSUE 			Fecha				Autor				Descripcion
*		#2	endeEtr		23/01/2019			EGS					se agrego reporte con lista de productos activos por puntos de venta
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
		$this->captura('moneda_emision','varchar');
		$this->captura('tipo','varchar');
		$this->captura('fecha','date');
		$this->captura('correlativo','varchar');
		$this->captura('pasajero','varchar');
		$this->captura('boleto','varchar');
		$this->captura('ruta','varchar');
		$this->captura('conceptos','varchar');
		$this->captura('forma_pago','text');		
		$this->captura('monto_cash_usd','numeric');
        $this->captura('monto_otro_usd','numeric');
        $this->captura('monto_cash_mb','numeric');
        $this->captura('monto_otro_mb','numeric');
		$this->captura('neto','numeric');
		$this->captura('precios_detalles','varchar');
        $this->captura('mensaje_error','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();

		$this->ejecutarConsulta();

		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarReporteXProducto () {
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_REPXPROD_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
		
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_productos','id_productos','varchar');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		
		$this->captura('estado','varchar');		
		$this->captura('tipo_documento','varchar');		
		$this->captura('fecha','varchar');		
		$this->captura('autorizacion','varchar');		
		$this->captura('nit','varchar');
		$this->captura('razon_social','varchar');
		$this->captura('productos','varchar');
		$this->captura('nro_doc','varchar');
		$this->captura('monto','numeric');
		$this->captura('neto','numeric');
		$this->captura('iva','numeric');
		$this->captura('it','numeric');
		$this->captura('ingreso','numeric');
		
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
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		
		//Definicion de la lista del resultado del query
		$this->captura('nombre','varchar');
		$this->captura('tipo','varchar');
				
		
		//Ejecuta la instruccion
		$this->armarConsulta();

		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarVentaReporte(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_REPVEN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('tipo_reporte','tipo_reporte','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');

		
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_cliente','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('correlativo_venta','varchar');
		$this->captura('a_cuenta','numeric');
		$this->captura('total_venta','numeric');
		$this->captura('fecha_estimada_entrega','date');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('estado','varchar');
        //$this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		//$this->captura('nit','varchar');
		$this->captura('id_punto_venta','int4');
		$this->captura('nombre_punto_venta','varchar');
		$this->captura('id_forma_pago','int4');
		$this->captura('forma_pago','varchar');
		$this->captura('monto_forma_pago','numeric');
		$this->captura('numero_tarjeta','varchar');
		$this->captura('codigo_tarjeta','varchar');
		$this->captura('tipo_tarjeta','varchar');
        $this->captura('porcentaje_descuento','numeric');
        $this->captura('id_vendedor_medico','varchar');
		$this->captura('comision','numeric');
		$this->captura('observaciones','text');		
		$this->captura('fecha','date');
		$this->captura('nro_factura','integer');
		$this->captura('excento','numeric');
		$this->captura('cod_control','varchar');		
		$this->captura('id_moneda','integer');
        $this->captura('total_venta_msuc','numeric');
        $this->captura('transporte_fob','numeric');
        $this->captura('seguros_fob','numeric');
        $this->captura('otros_fob','numeric');
        $this->captura('transporte_cif','numeric');
        $this->captura('seguros_cif','numeric');
        $this->captura('otros_cif','numeric');
		$this->captura('tipo_cambio_venta','numeric');		
		$this->captura('desc_moneda','varchar');		
		$this->captura('valor_bruto','numeric');
		$this->captura('descripcion_bulto','varchar');
		$this->captura('contabilizable','varchar');
		$this->captura('hora_estimada_entrega','varchar');		
       // $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('id_cliente_destino','integer');
		$this->captura('cliente_destino','varchar');
		
		$this->captura('nro_tramite','varchar');
		$this->captura('id_proveedor','integer');
		$this->captura('id_contrato','integer');
		$this->captura('id_doc_compra_venta','int4');
		$this->captura('id_venta_fk','integer');
		$this->captura('ncd','varchar');
		
		$this->captura('desc_proveedor','varchar');
		$this->captura('codigo_auxiliar','varchar');
		$this->captura('importe_doc','numeric');
		$this->captura('importe_pendiente','numeric');
		$this->captura('importe_anticipo','numeric');
		$this->captura('importe_retgar','numeric');
		$this->captura('nit','varchar');
		$this->captura('nro_factura_fk','integer');
		
		
		
      
		
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function listarVentaReporteGrid(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_VENGRID_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('tipo_reporte','tipo_reporte','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('fecha_hasta','fecha_hasta','date');

		
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_cliente','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('correlativo_venta','varchar');
		$this->captura('a_cuenta','numeric');
		$this->captura('total_venta','numeric');
		$this->captura('fecha_estimada_entrega','date');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('estado','varchar');
        //$this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		//$this->captura('nit','varchar');
		$this->captura('id_punto_venta','int4');
		$this->captura('nombre_punto_venta','varchar');
		$this->captura('id_forma_pago','int4');
		$this->captura('forma_pago','varchar');
		$this->captura('monto_forma_pago','numeric');
		$this->captura('numero_tarjeta','varchar');
		$this->captura('codigo_tarjeta','varchar');
		$this->captura('tipo_tarjeta','varchar');
        $this->captura('porcentaje_descuento','numeric');
        $this->captura('id_vendedor_medico','varchar');
		$this->captura('comision','numeric');
		$this->captura('observaciones','text');		
		$this->captura('fecha','date');
		$this->captura('nro_factura','integer');
		$this->captura('excento','numeric');
		$this->captura('cod_control','varchar');		
		$this->captura('id_moneda','integer');
        $this->captura('total_venta_msuc','numeric');
        $this->captura('transporte_fob','numeric');
        $this->captura('seguros_fob','numeric');
        $this->captura('otros_fob','numeric');
        $this->captura('transporte_cif','numeric');
        $this->captura('seguros_cif','numeric');
        $this->captura('otros_cif','numeric');
		$this->captura('tipo_cambio_venta','numeric');		
		$this->captura('desc_moneda','varchar');		
		$this->captura('valor_bruto','numeric');
		$this->captura('descripcion_bulto','varchar');
		$this->captura('contabilizable','varchar');
		$this->captura('hora_estimada_entrega','varchar');		
       // $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('id_cliente_destino','integer');
		$this->captura('cliente_destino','varchar');
		
		$this->captura('nro_tramite','varchar');
		$this->captura('id_proveedor','integer');
		$this->captura('id_contrato','integer');
		$this->captura('id_doc_compra_venta','int4');
		$this->captura('id_venta_fk','integer');
		$this->captura('ncd','varchar');
		
		$this->captura('desc_proveedor','varchar');
		$this->captura('codigo_auxiliar','varchar');
		$this->captura('importe_doc','numeric');
		$this->captura('importe_pendiente','numeric');
		$this->captura('importe_anticipo','numeric');
		$this->captura('importe_retgar','numeric');
		$this->captura('nit','varchar');
		$this->captura('nro_factura_fk','integer');
		
		
		
      
		
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarReciboFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_VENDET_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//$this->setCount(false);
				
		$this->setParametro('id_venta','id_venta','integer');

		//Definicion de la lista del resultado del query
		//$this->captura('id','integer');
		
		$this->captura('id_venta_detalle','int4');
		$this->captura('id_venta','int4');
		$this->captura('concepto','varchar');
		$this->captura('cantidad','numeric');
		$this->captura('precio_unitario','numeric');
		$this->captura('precio_total','numeric');	
		$this->captura('unidad_medida','varchar');
		$this->captura('nandina','varchar');	
		$this->captura('bruto','varchar');	
		$this->captura('ley','varchar');	
		$this->captura('kg_fino','varchar');	
		$this->captura('descripcion','text');	
		$this->captura('unidad_concepto','varchar');
        $this->captura('precio_grupo','numeric');
		
	
		
		//Ejecuta la instruccion
		$this->armarConsulta();

		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	//#2
	function listarProductoActivoPuntoV(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_repventa_sel';
		$this->transaccion='VF_VENINGASPRO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//$this->setCount(false);
				
		$this->setParametro('id_venta','id_venta','integer');

		//Definicion de la lista del resultado del query
		//$this->captura('id','integer');
		
		$this->captura('id_punto_venta','int4');
		$this->captura('codigo_punto_de_venta','varchar');
		$this->captura('nombre_punto_de_venta','varchar');
		$this->captura('id_punto_venta_producto','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('nombre_sucursal','varchar');
		$this->captura('id_sucursal_producto','int4');
		$this->captura('id_concepto_ingas','int4');
		$this->captura('codigo_ingas','varchar');
		$this->captura('desc_ingas','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();

		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}	//#2
			
}
?>