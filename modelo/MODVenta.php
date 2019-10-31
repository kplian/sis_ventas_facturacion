<?php
/**
*@package pXP
*@file gen-MODVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 * 
 
 *  HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              01-06-2015          RAC            Creacion
 #123            25/09/2018          RAC            se adciona manejo de proveedor para facturas de ETR
 #1            11/10/2018          EGS               se adiciono el campo id_venta_fk
 #7				31/10/2019			EGS					 Se agrega validacion y siguiente estado multiple
*/

class MODVenta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VEN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		

		$this->setParametro('historico','historico','varchar');
		$this->setParametro('tipo_factura','tipo_factura','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
        $this->setParametro('tipo_usuario','tipo_usuario','varchar');


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
        $this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		$this->captura('nit','varchar');
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
        $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('id_cliente_destino','integer');
		$this->captura('cliente_destino','varchar');
		
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo_factura','varchar');
		
		
		
      
		
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarVentaETR(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENETR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		

		$this->setParametro('historico','historico','varchar');
		$this->setParametro('tipo_factura','tipo_factura','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
        $this->setParametro('tipo_usuario','tipo_usuario','varchar');


		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_proveedor','int4');
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
        $this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		$this->captura('nit','varchar');
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
        $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('contrato_numero','varchar');
		$this->captura('objeto','text');
		$this->captura('id_cliente_destino','integer');		
		$this->captura('cliente_destino','varchar');
		
		
		$this->captura('id_contrato','integer');			
		$this->captura('desc_contrato','varchar');		
		$this->captura('id_centro_costo','integer');
		$this->captura('desc_centro_costo','varchar');	
		$this->captura('codigo_aplicacion','varchar');
		
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo_factura','varchar');
				
		
		 
                     
		
	
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarVentaNCETR(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENNCETR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		

		$this->setParametro('historico','historico','varchar');
		$this->setParametro('tipo_factura','tipo_factura','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
        $this->setParametro('tipo_usuario','tipo_usuario','varchar');


		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_proveedor','int4');
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
        $this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		$this->captura('nit','varchar');
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
        $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('contrato_numero','varchar');
		$this->captura('objeto','text');
		$this->captura('id_cliente_destino','integer');		
		$this->captura('cliente_destino','varchar');
		
		
		$this->captura('id_contrato','integer');			
		$this->captura('desc_contrato','varchar');		
		$this->captura('id_centro_costo','integer');
		$this->captura('desc_centro_costo','varchar');	
		$this->captura('codigo_aplicacion','varchar');
		
		$this->captura('id_venta_fk','integer');
		$this->captura('nro_factura_vo','integer');
		$this->captura('id_dosificacion_vo','integer');
		$this->captura('nroaut_vo','varchar');
		$this->captura('total_venta_vo','numeric');
		
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo_factura','varchar');
		
		
		
		
			
		
		 
                     
		
	
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}


   function listarVentaEmisor(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENEMISOR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		

		$this->setParametro('historico','historico','varchar');
		$this->setParametro('tipo_factura','tipo_factura','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
        $this->setParametro('tipo_usuario','tipo_usuario','varchar');


		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_proveedor','int4');
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
        $this->captura('nombre_factura','varchar');
        $this->captura('nombre_sucursal','varchar');
		$this->captura('nit','varchar');
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
        $this->captura('vendedor_medico','varchar');
		$this->captura('forma_pedido','varchar'); 
		$this->captura('contrato_numero','varchar');
		$this->captura('objeto','text');
		$this->captura('id_cliente_destino','integer');		
		$this->captura('cliente_destino','varchar');
		
		
		$this->captura('id_contrato','integer');			
		$this->captura('desc_contrato','varchar');		
		$this->captura('id_centro_costo','integer');
		$this->captura('desc_centro_costo','varchar');	
		$this->captura('codigo_aplicacion','varchar');	
		
		
		$this->captura('id_venta_fk','integer');
		$this->captura('nro_factura_vo','integer');
		$this->captura('id_dosificacion_vo','integer');
		$this->captura('nroaut_vo','varchar');
		$this->captura('total_venta_vo','numeric');
		
		
		$this->captura('formato_comprobante','varchar');
		$this->captura('tipo_factura','varchar');
		
		      
	
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}




    function listarVentaCombosETR(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_FACTCMB_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion		

		
		$this->setParametro('tipo_factura','tipo_factura','varchar');
		$this->setParametro('id_sucursal','id_sucursal','integer');
		$this->setParametro('id_punto_venta','id_punto_venta','integer');
        $this->setParametro('tipo_usuario','tipo_usuario','varchar');
		
		
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_proveedor','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('total_venta','NUMERIC');
		$this->captura('estado','VARCHAR');
		$this->captura('nombre_factura','VARCHAR');
		$this->captura('nit','varchar');
		$this->captura('id_moneda','INTEGER');
		$this->captura('total_venta_msuc','NUMERIC');
		$this->captura('tipo_cambio_venta','NUMERIC');
		$this->captura('desc_moneda','varchar');
		$this->captura('contrato_numero','VARCHAR');
		$this->captura('objeto','TEXT');
		$this->captura('id_contrato','int4');
		$this->captura('desc_contrato','VARCHAR');
		$this->captura('id_centro_costo','int4');
		$this->captura('desc_centro_costo','VARCHAR');
		$this->captura('codigo_aplicacion','VARCHAR');
		$this->captura('fecha','date');
		$this->captura('nro_factura','int4');
		$this->captura('nroaut','VARCHAR');
		$this->captura('observaciones','TEXT');
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}




	function getVariablesBasicas(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENCONFBAS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion		
		$this->setCount(false);

		
		//Definicion de la lista del resultado del query
		$this->captura('variable','varchar');
		$this->captura('valor','varchar');		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');		
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('a_cuenta','a_cuenta','numeric');
		$this->setParametro('total_venta','total_venta','numeric');
		$this->setParametro('fecha_estimada_entrega','fecha_estimada_entrega','date');
		$this->setParametro('observaciones','observaciones','text');
		$this->setParametro('id_proveedor','id_proveedor','int4'); #1 manda id_proveedor

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    
    function insertarVentaCompleta() {
        //Abre conexion con PDO
        $cone = new conexion();
        $link = $cone->conectarpdo();
        $copiado = false;           
        try {
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
            $link->beginTransaction();
            
            /////////////////////////
            //  inserta cabecera de la solicitud de compra
            ///////////////////////
            
            //Definicion de variables para ejecucion del procedimiento
            $this->procedimiento = 'vef.ft_venta_ime';
            
            $this->tipo_procedimiento = 'IME';
			
			if ($this->aParam->getParametro('id_venta') != '') {
				//Eliminar formas de pago
				$this->transaccion = 'VF_VEALLFORPA_ELI';
				$this->setParametro('id_venta','id_venta','int4');
				//Ejecuta la instruccion
	            $this->armarConsulta();
	            $stmt = $link->prepare($this->consulta);          
	            $stmt->execute();
	            $result = $stmt->fetch(PDO::FETCH_ASSOC);               
	            
	            //recupera parametros devuelto depues de insertar ... (id_formula)
	            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
	            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
	                throw new Exception("Error al ejecutar en la bd", 3);
	            }
				
				
				//Eliminar detalles
				$this->transaccion = 'VF_VEALLDET_ELI';
				
				//Ejecuta la instruccion
	            $this->armarConsulta();
	            $stmt = $link->prepare($this->consulta);          
	            $stmt->execute();
	            $result = $stmt->fetch(PDO::FETCH_ASSOC);               
	            
	            //recupera parametros devuelto depues de insertar ... (id_formula)
	            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
	            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
	                throw new Exception("Error al ejecutar en la bd", 3);
	            }
				$this->transaccion = 'VF_VEN_MOD';
			} else {
				$this->transaccion = 'VF_VEN_INS';
			}
            
            //Define los parametros para la funcion
            $this->setParametro('id_cliente','id_cliente','varchar');
			$this->setParametro('nit','nit','varchar');
            $this->setParametro('id_sucursal','id_sucursal','int4');        
            $this->setParametro('nro_tramite','nro_tramite','varchar');
            $this->setParametro('a_cuenta','a_cuenta','numeric');
            $this->setParametro('total_venta','total_venta','numeric');
            $this->setParametro('fecha_estimada_entrega','fecha_estimada_entrega','date');
			$this->setParametro('id_punto_venta','id_punto_venta','int4'); 
			$this->setParametro('id_forma_pago','id_forma_pago','int4');
			$this->setParametro('monto_forma_pago','monto_forma_pago','numeric'); 
			
			$this->setParametro('numero_tarjeta','numero_tarjeta','varchar'); 
			$this->setParametro('codigo_tarjeta','codigo_tarjeta','varchar'); 
			$this->setParametro('tipo_tarjeta','tipo_tarjeta','varchar'); 
            $this->setParametro('porcentaje_descuento','porcentaje_descuento','integer'); 
            $this->setParametro('id_vendedor_medico','id_vendedor_medico','varchar'); 
			$this->setParametro('comision','comision','numeric'); 
			$this->setParametro('observaciones','observaciones','text');
			
			$this->setParametro('tipo_factura','tipo_factura','varchar'); 
			$this->setParametro('fecha','fecha','date'); 
            $this->setParametro('nro_factura','nro_factura','varchar'); 
			$this->setParametro('id_dosificacion','id_dosificacion','integer'); 
			$this->setParametro('excento','excento','numeric');
			
			$this->setParametro('id_moneda','id_moneda','int4');
			$this->setParametro('tipo_cambio_venta','tipo_cambio_venta','numeric');
			$this->setParametro('total_venta_msuc','total_venta_msuc','numeric');
			$this->setParametro('transporte_fob','transporte_fob','numeric');
			$this->setParametro('seguros_fob','seguros_fob','numeric');
			$this->setParametro('otros_fob','otros_fob','numeric');
			$this->setParametro('transporte_cif','transporte_cif','numeric');
			$this->setParametro('seguros_cif','seguros_cif','numeric');
			$this->setParametro('otros_cif','otros_cif','numeric');
			$this->setParametro('valor_bruto','valor_bruto','numeric');
			$this->setParametro('descripcion_bulto','descripcion_bulto','varchar');
			$this->setParametro('id_cliente_destino','id_cliente_destino','varchar');
			$this->setParametro('hora_estimada_entrega','hora_estimada_entrega','varchar');
			$this->setParametro('forma_pedido','forma_pedido','varchar');
			$this->setParametro('id_proveedor','id_proveedor','varchar');
			
			
			$this->setParametro('id_centro_costo','id_centro_costo','int4'); // #123  
			$this->setParametro('id_contrato','id_contrato','int4');// #123 
			$this->setParametro('codigo_aplicacion','codigo_aplicacion','varchar');// #123 
			$this->setParametro('id_venta_fk','id_venta_fk','int4');// #123 
			
			
			
             
			 
            
            //Ejecuta la instruccion
            $this->armarConsulta();
            $stmt = $link->prepare($this->consulta);          
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);               
            
            //recupera parametros devuelto depues de insertar ... (id_formula)
            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                throw new Exception("Error al ejecutar en la bd", 3);
            }
            
            $respuesta = $resp_procedimiento['datos'];
            
            $id_venta = $respuesta['id_venta'];
                       
            //decodifica JSON  de detalles 
            $json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));           
            
            //var_dump($json_detalle)   ;
            foreach($json_detalle as $f){
                
                $this->resetParametros();
                //Definicion de variables para ejecucion del procedimiento
                $this->procedimiento='vef.ft_venta_detalle_ime';
                $this->transaccion='VF_VEDET_INS';
                $this->tipo_procedimiento='IME';
                //modifica los valores de las variables que mandaremos
                $this->arreglo['id_item'] = $f['id_item'];
                $this->arreglo['id_producto'] = $f['id_producto'];
                $this->arreglo['id_formula'] = $f['id_formula'];
                $this->arreglo['tipo'] = $f['tipo'];
                $this->arreglo['estado_reg'] = $f['estado_reg'];
                $this->arreglo['cantidad'] = $f['cantidad'];
                $this->arreglo['precio'] = $f['precio_unitario'];
                $this->arreglo['sw_porcentaje_formula'] = $f['sw_porcentaje_formula'];
                $this->arreglo['porcentaje_descuento'] = $f['porcentaje_descuento'];
                $this->arreglo['id_vendedor_medico'] = $f['id_vendedor_medico'];
				$this->arreglo['descripcion'] = $f['descripcion'];
                $this->arreglo['id_venta'] = $id_venta;  
				
				$this->arreglo['bruto'] = $f['bruto'];
				$this->arreglo['ley'] = $f['ley'];
				$this->arreglo['kg_fino'] = $f['kg_fino'];
				$this->arreglo['id_unidad_medida'] = $f['id_unidad_medida'];               
                
                //Define los parametros para la funcion
                $this->setParametro('id_venta','id_venta','int4');
                $this->setParametro('id_item','id_item','int4');
                $this->setParametro('id_producto','id_producto','int4');
                $this->setParametro('id_formula','id_formula','int4');
                $this->setParametro('tipo','tipo','varchar');
                $this->setParametro('estado_reg','estado_reg','varchar');
                $this->setParametro('cantidad_det','cantidad','numeric');
                $this->setParametro('precio','precio','numeric');
                $this->setParametro('sw_porcentaje_formula','sw_porcentaje_formula','varchar');  
                $this->setParametro('porcentaje_descuento','porcentaje_descuento','int4');             
                $this->setParametro('id_vendedor_medico','id_vendedor_medico','varchar');
				$this->setParametro('descripcion','descripcion','text');
				$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
				$this->setParametro('bruto','bruto','varchar');
				$this->setParametro('ley','ley','varchar');
				$this->setParametro('kg_fino','kg_fino','varchar');
				$this->setParametro('tipo_factura','tipo_factura','varchar'); 
				$this->setParametro('id_venta_fk','id_venta_fk','int4');// #123  
				
                
                //Ejecuta la instruccion
                $this->armarConsulta();
                $stmt = $link->prepare($this->consulta);          
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);               
                
                //recupera parametros devuelto depues de insertar ... (id_formula)
                $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
                if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                    throw new Exception("Error al insertar detalle  en la bd", 3);
                }
                        
            }
			if ($this->aParam->getParametro('id_forma_pago') == '0') {
				//decodifica JSON  de forma de pago 
	            $json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('formas_pago'));           
	            
	            //var_dump($json_detalle)   ;
	            foreach($json_detalle as $f){
	                
	                $this->resetParametros();
	                //Definicion de variables para ejecucion del procedimiento
	                $this->procedimiento='vef.ft_venta_forma_pago_ime';
	                $this->transaccion='VF_VENFP_INS';
	                $this->tipo_procedimiento='IME';
	                //modifica los valores de las variables que mandaremos
	                $this->arreglo['id_forma_pago'] = $f['id_forma_pago'];
	                $this->arreglo['valor'] = $f['valor'];  
					$this->arreglo['numero_tarjeta'] = $f['numero_tarjeta'];  
					$this->arreglo['codigo_tarjeta'] = $f['codigo_tarjeta'];  
					$this->arreglo['tipo_tarjeta'] = $f['tipo_tarjeta'];                
	                $this->arreglo['id_venta'] = $id_venta;                
	                
	                //Define los parametros para la funcion
	                $this->setParametro('id_venta','id_venta','int4');
	                $this->setParametro('id_forma_pago','id_forma_pago','int4');
					$this->setParametro('numero_tarjeta','numero_tarjeta','varchar'); 
					$this->setParametro('codigo_tarjeta','codigo_tarjeta','varchar'); 
					$this->setParametro('tipo_tarjeta','tipo_tarjeta','varchar'); 
	                $this->setParametro('valor','valor','numeric');
					$this->setParametro('tipo_factura','tipo_factura','varchar');                                
	                
	                //Ejecuta la instruccion
	                $this->armarConsulta();
	                $stmt = $link->prepare($this->consulta);          
	                $stmt->execute();
	                $result = $stmt->fetch(PDO::FETCH_ASSOC);               
	                
	                //recupera parametros devuelto depues de insertar ... (id_formula)
	                $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
	                if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
	                    throw new Exception("Error al insertar detalle  en la bd", 3);
	                }
	                        
	            }
	        }

			$this->resetParametros();
			//Validar que todo este ok
			$this->procedimiento = 'vef.ft_venta_ime';
			$this->transaccion = 'VF_VENVALI_MOD';
			$this->setParametro('id_venta','id_venta','int4');
			$this->setParametro('tipo_factura','tipo_factura','varchar'); 
			$this->setParametro('id_venta_fk','id_venta_fk','int4');// #123  
			//Ejecuta la instruccion
            $this->armarConsulta();
            $stmt = $link->prepare($this->consulta);          
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);               
            
            //recupera parametros devuelto depues de insertar ... (id_formula)
            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			$respuesta = $resp_procedimiento['datos'];            
            
			
            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                throw new Exception("Error al ejecutar en la bd", 3);
            }
			

            //si todo va bien confirmamos y regresamos el resultado
            $link->commit();
            $this->respuesta=new Mensaje();			
            $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
            $this->respuesta->setDatos($respuesta);
        } 
        catch (Exception $e) {          
                $link->rollBack();
                $this->respuesta=new Mensaje();
                if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
                    $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
                } else if ($e->getCode() == 2) {//es un error en bd de una consulta
                    $this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
                } else {//es un error lanzado con throw exception
                    throw new Exception($e->getMessage(), 2);
                }
                
        }    
        
        return $this->respuesta;
    }
			
	function modificarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('a_cuenta','a_cuenta','numeric');
		$this->setParametro('total_venta','total_venta','numeric');
		$this->setParametro('fecha_estimada_entrega','fecha_estimada_entrega','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VEN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function anularVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VF_VENANU_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_venta','id_venta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

    function setContabilizable(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='vef.ft_venta_ime';
        $this->transaccion='VF_VENCONTA_MOD';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_venta','id_venta','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function verificarRelacion(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='vef.ft_venta_ime';
        $this->transaccion='VF_VENVERELA_MOD';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_punto_venta','id_punto_venta','int4');
        $this->setParametro('id_sucursal','id_sucursal','int4');
        $this->setParametro('tipo_factura','tipo_factura','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	
    function siguienteEstadoVenta(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='vef.ft_venta_ime';
        $this->transaccion='VEF_SIGEVE_IME';
        $this->tipo_procedimiento='IME';
        
        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
        $this->setParametro('id_estado_wf_act','id_estado_wf_act','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
        $this->setParametro('id_depto_wf','id_depto_wf','int4');
        $this->setParametro('obs','obs','text');
        $this->setParametro('json_procesos','json_procesos','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    
    function anteriorEstadoVenta(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='vef.ft_venta_ime';
        $this->transaccion='VEF_ANTEVE_IME';
        $this->tipo_procedimiento='IME';
                
        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('operacion','operacion','varchar');
        
        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('obs','obs','text');
        
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	
	function listarNotaVentaDet(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_NOTAVENDV_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		
		$this->setParametro('id_venta','id_venta','int4');
		//captura parametros adicionales para el count
		$this->capturaCount('suma_total','numeric');		
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		
		$this->captura('id_venta_detalle','int4');
		$this->captura('precio','numeric');
		$this->captura('tipo','varchar');
		$this->captura('cantidad','int4');
		$this->captura('precio_total','numeric');		
		$this->captura('codigo_nombre','varchar');
		$this->captura('item_nombre','varchar');
		$this->captura('nombre_producto','varchar');
		$this->captura('id_formula','int4');
		$this->captura('id_formula_detalle','int4');
		$this->captura('cantidad_df','NUMERIC');
		$this->captura('item_nombre_df','varchar');	
		$this->captura('nombre_formula','varchar');
		
		
		 
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarNotaVenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_NOTVENV_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
		$this->setParametro('id_venta','id_venta','int4');
		
			
				
		//Definicion de la lista del resultado del query
		$this->captura('id_venta','int4');
		$this->captura('id_cliente','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('nro_tramite','varchar');
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
        $this->captura('nombre_completo','text');
        $this->captura('nombre_sucursal','varchar');
		
		$this->captura('direccion','varchar');
		$this->captura('correo','varchar');
		$this->captura('telefono','varchar');
		$this->captura('total_string','varchar');
				
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarReciboFactura(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENREP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
		
		$this->setParametro('id_venta','id_venta','integer');
		$this->setParametro('tipo_factura','tipo_factura','varchar');

		//Definicion de la lista del resultado del query
		$this->captura('nombre_entidad','varchar');		
		$this->captura('direccion_sucursal','varchar');
		$this->captura('telefono_sucursal','varchar');
		$this->captura('lugar_sucursal','varchar');
		$this->captura('departamento_sucursal','varchar');
		$this->captura('fecha_venta','varchar');
		$this->captura('nro_venta','varchar');

		$this->captura('moneda_sucursal','varchar');
		$this->captura('total_venta','numeric');
		$this->captura('sujeto_credito','numeric');		
		$this->captura('total_venta_literal','varchar');
		$this->captura('observaciones','text');	
		$this->captura('cliente','varchar');	
		
		$this->captura('nombre_sucursal','varchar');//nuevo	
		$this->captura('numero_factura','integer');//nuevo
		$this->captura('autorizacion','varchar');//nuevo
		$this->captura('nit_cliente','varchar');//nuevo	
		$this->captura('codigo_control','varchar');//nuevo	
		$this->captura('fecha_limite_emision','text');//nuevo
		$this->captura('glosa_impuestos','varchar');//nuevo	
		$this->captura('glosa_empresa','varchar');//nuevo	
		$this->captura('pagina_entidad','varchar');//nuevo			
		$this->captura('id','integer');//nuevo
		$this->captura('id_venta_fk','integer');   ///#1            11/10/2018          EGS 
		$this->captura('hora','text');//nuevo
		$this->captura('nit_entidad','varchar');//nuevo	
		$this->captura('actividades','varchar');
		$this->captura('fecha_venta_recibo','varchar');
		
		$this->captura('direccion_cliente','varchar');
		$this->captura('tipo_cambio_venta','numeric');
		$this->captura('total_venta_msuc','numeric');
		$this->captura('total_venta_msuc_literal','varchar');
		$this->captura('moneda_venta','varchar');//codigo
		$this->captura('desc_moneda_sucursal','varchar');//nombre
		$this->captura('desc_moneda_venta','varchar');//nombre
		
		$this->captura('transporte_fob','numeric');
		$this->captura('seguros_fob','numeric');
		$this->captura('otros_fob','numeric');
		
		$this->captura('transporte_cif','numeric');
		$this->captura('seguros_cif','numeric');
		$this->captura('otros_cif','numeric');
		
		$this->captura('fecha_literal','varchar');
		
		$this->captura('cantidad_descripciones','integer');
		$this->captura('estado','varchar');
		
		$this->captura('valor_bruto','numeric');
		$this->captura('descripcion_bulto','varchar');
        $this->captura('telefono_cliente','varchar');
        $this->captura('fecha_hora_entrega','varchar');
        $this->captura('a_cuenta','numeric');
        $this->captura('medico_vendedor','varchar');


        $this->captura('nro_tramite','varchar');
		$this->captura('codigo_cliente','varchar');
		
			
		$this->captura('lugar_cliente','varchar');	
		$this->captura('cliente_destino','varchar');
		$this->captura('lugar_destino','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		//var_dump($this->consulta);exit;
		
	     
		$this->ejecutarConsulta();
		
		
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function listarReciboFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENDETREP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
				
		$this->setParametro('id_venta','id_venta','integer');

		//Definicion de la lista del resultado del query
		$this->captura('id_venta_detalle','integer');
		$this->captura('id_venta_detalle_fk','integer');
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
	
	function listarReciboFacturaDescripcion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENDESREP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
				
		$this->setParametro('id_venta','id_venta','integer');

		//Definicion de la lista del resultado del query
		$this->captura('nombre','varchar');
		$this->captura('columna','numeric');
		$this->captura('fila','numeric');
		$this->captura('valor','varchar');			
		
		//Ejecuta la instruccion
		$this->armarConsulta();

		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	function validacionMultiple(){//#7
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VEF_VALIMUL_IME';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('data_json','data_json','json_text');
		//$this->captura('id_tipo_estado','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		//var_dump('$this->respuesta',$this->respuesta);exit;
		return $this->respuesta;

	}

	function siguienteEstadoMultiple(){//#7
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_venta_ime';
		$this->transaccion='VEF_SIGESTMUL_IME';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('data_json','data_json','json_text');
		$this->setParametro('id_funcionario_wf','id_funcionario_wf','integer');
		$this->setParametro('id_tipo_estado','id_tipo_estado','integer');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		//var_dump('$this->respuesta',$this->respuesta);exit;
		return $this->respuesta;

	}
			
}
?>
