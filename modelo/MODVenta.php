<?php
/**
*@package pXP
*@file gen-MODVenta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
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
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function getVariablesBasicas(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_venta_sel';
		$this->transaccion='VF_VENCONF_SEL';
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
                
                //Define los parametros para la funcion
                $this->setParametro('id_venta','id_venta','int4');
                $this->setParametro('id_item','id_item','int4');
                $this->setParametro('id_producto','id_producto','int4');
                $this->setParametro('id_formula','id_formula','int4');
                $this->setParametro('tipo','tipo','varchar');
                $this->setParametro('estado_reg','estado_reg','varchar');
                $this->setParametro('cantidad_det','cantidad','int4');
                $this->setParametro('precio','precio','numeric');
                $this->setParametro('sw_porcentaje_formula','sw_porcentaje_formula','varchar');  
                $this->setParametro('porcentaje_descuento','porcentaje_descuento','int4');             
                $this->setParametro('id_vendedor_medico','id_vendedor_medico','varchar');
				$this->setParametro('descripcion','descripcion','text'); 
                
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
		$this->transaccion='VF_NOTAVEND_SEL';
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
		$this->transaccion='VF_NOTVEN_SEL';
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
			
}
?>