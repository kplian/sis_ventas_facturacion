<?php
/**
*@package pXP
*@file gen-MODMemoriaCalculo.php
*@author  (admin)
*@date 01-03-2016 14:22:24
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 ISSUE				FECHA		AUTOR				DESCRIPCION
 	#4	endeETR	 	21/02/2019	EGS					Se agregaron campos para punto de venta
 * #6	endeETR	 	25/10/2019	EGS					Se agrega descripcion al xls para subir archivos
 */

class MODSubirArchivoFac extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}


function subirArchivoFac(){
		//Definicion de variables para ejecucion del procedimiento
		//$this->procedimiento='vef.f_inserta_factura';
		$this->procedimiento='vef.f_inserta_factura';
		$this->transaccion='VF_INSTEM_INS';
		$this->tipo_procedimiento='IME';		
		//Define los parametros para la funcion	
		
		$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
		$this->setParametro('razon_social', 'razon_social','varchar');
	    $this->setParametro('nit', 'nit','varchar');
		$this->setParametro('cantidad_det','cantidad_det','integer');
		$this->setParametro('unidad','unidad','varchar');
		$this->setParametro('detalle', 'detalle','varchar');
		$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
		$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
		$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
		$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
		$this->setParametro('centro_costo', 'centro_costo','varchar');
		$this->setParametro('clase_costo', 'clase_costo','varchar');
		$this->setParametro('nro_factura', 'nro_factura','varchar');
		$this->setParametro('observaciones', 'observaciones','varchar');
		$this->setParametro('fecha', 'fecha','date');
		$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
		$this->setParametro('tipo_factura', 'tipo_factura','varchar');
		$this->setParametro('nro_contrato', 'nro_contrato','varchar');
		
		$this->setParametro('conteo', 'conteo','int4');
		
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarVentaExcel(){
		//Definicion de variables para ejecucion del procedimiento
		//$this->procedimiento='vef.f_inserta_factura';
		
		//var_dump('mod');
		$this->procedimiento='vef.f_inserta_factura';
		$this->transaccion='VF_INSFAC_INS';
		$this->tipo_procedimiento='IME';		
		//Define los parametros para la funcion	
		
		$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
		$this->setParametro('razon_social', 'razon_social','varchar');
	    $this->setParametro('nit', 'nit','varchar');
		$this->setParametro('cantidad_det','cantidad_det','integer');
		$this->setParametro('unidad','unidad','varchar');
		$this->setParametro('detalle', 'detalle','varchar');
		$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
		$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
		$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
		$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
		$this->setParametro('centro_costo', 'centro_costo','varchar');
		$this->setParametro('clase_costo', 'clase_costo','varchar');
		$this->setParametro('nro_factura', 'nro_factura','varchar');
		$this->setParametro('observaciones', 'observaciones','varchar');
		$this->setParametro('fecha', 'fecha','date');
		$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
		$this->setParametro('tipo_factura', 'tipo_factura','varchar');
		$this->setParametro('nro_contrato', 'nro_contrato','varchar');
		
		$this->setParametro('conteo', 'conteo','int4');
		
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	  function insertarVentaCompletoXLS(){
		
		//Abre conexion con PDO
		$cone = new conexion();
		$link = $cone->conectarpdo();
		$copiado = false;			
		try {
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		  	$link->beginTransaction();
			//var_dump($this->objParam);
			/////////////////////////
			//  inserta cabecera de la solicitud de compra
			///////////////////////
			
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='vef.f_inserta_factura';
			$this->transaccion='VF_INSTEM_INS';
			$this->tipo_procedimiento='IME';
			
			$this->setParametro('nombreVista', 'nombreVista','varchar');
					
			//Define los parametros para la funcion
			$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
			$this->setParametro('razon_social', 'razon_social','varchar');
		    $this->setParametro('nit', 'nit','varchar');
			$this->setParametro('cantidad_det','cantidad_det','integer');
			$this->setParametro('unidad','unidad','varchar');
			$this->setParametro('codigo', 'codigo','varchar');
			$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
			$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
			$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
			$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
			$this->setParametro('centro_costo', 'centro_costo','varchar');
			$this->setParametro('clase_costo', 'clase_costo','varchar');
			$this->setParametro('nro', 'nro','varchar');
			$this->setParametro('observaciones', 'observaciones','varchar');
            $this->setParametro('descripcion', 'descripcion','varchar');//#6
            $this->setParametro('fecha', 'fecha','date');
			$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
			$this->setParametro('tipo_factura', 'tipo_factura','varchar');
			$this->setParametro('nro_contrato', 'nro_contrato','varchar');
			
			$this->setParametro('forma_pago', 'forma_pago','varchar');
			$this->setParametro('aplicacion', 'aplicacion','varchar');
			
			$this->setParametro('conteo', 'conteo','int4');

			//Ejecuta la instruccion
            $this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);				
			
			//recupera parametros devuelto depues de insertar ... (id_solicitud)
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			}
			
			//$respuesta = $resp_procedimiento['datos'];
			//var_dump($respuesta);
			//$id_factura_excel= $respuesta['id_factura_excel'];
			
			
			//////////////////////////////////////////////
			//inserta detalle de la compra o venta
			/////////////////////////////////////////////
			
			
			if($this->aParam->getParametro('bandera') == 'TRUE'){
					
					$this->procedimiento='vef.f_inserta_factura';
					$this->transaccion='VF_VALIFAC_INS';
					$this->tipo_procedimiento='IME';
							
					//Define los parametros para la funcion
					//$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
		
					//Ejecuta la instruccion
		            $this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);				
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al ejecutar en la bd", 3);
					}
				
			

					$this->resetParametros();
					//Definicion de variables para ejecucion del procedimiento
				    $this->procedimiento='vef.f_inserta_factura';
					$this->transaccion='VF_INSFAC_INS';
					$this->tipo_procedimiento='IME';
					
				
							
					//Define los parametros para la funcion
					$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
					$this->setParametro('razon_social', 'razon_social','varchar');
				    $this->setParametro('nit', 'nit','varchar');
					$this->setParametro('cantidad_det','cantidad_det','integer');
					$this->setParametro('unidad','unidad','varchar');
					$this->setParametro('codigo', 'codigo','varchar');
					$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
					$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
					$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
					$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
					$this->setParametro('centro_costo', 'centro_costo','varchar');
					$this->setParametro('clase_costo', 'clase_costo','varchar');
					$this->setParametro('nro', 'nro','varchar');
					$this->setParametro('observaciones', 'observaciones','varchar');
					$this->setParametro('fecha', 'fecha','date');
					$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
					$this->setParametro('tipo_factura', 'tipo_factura','varchar');
					$this->setParametro('nro_contrato', 'nro_contrato','varchar');
					
					$this->setParametro('conteo', 'conteo','int4');
					
					//Ejecuta la instruccion
		            $this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);				
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al insertar venta  en la bd", 3);
					}
					
				
					

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
	function listarTemporalData(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.f_inserta_factura';
		$this->transaccion='VF_ELIEXC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_temporal_data','int4');
		$this->captura('razon_social','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('nro','varchar');
		$this->captura('nro_factura','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		
		$this->captura('total_venta','numeric');
		$this->captura('total_detalle','numeric');
		$this->captura('error','varchar');
		$this->captura('id_punto_venta','integer');//#4
		$this->captura('nombre_punto_venta','varchar');//#4
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	 function insertarNotaCompletoXLS(){
		
		//Abre conexion con PDO
		$cone = new conexion();
		$link = $cone->conectarpdo();
		$copiado = false;			
		try {
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		  	$link->beginTransaction();
			//var_dump($this->objParam);
			/////////////////////////
			//  inserta cabecera de la solicitud de compra
			///////////////////////
			
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='vef.f_inserta_nota';
			$this->transaccion='VF_INSNOT_INS';
			$this->tipo_procedimiento='IME';
					
			//Define los parametros para la funcion
			$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
			$this->setParametro('razon_social', 'razon_social','varchar');
		    $this->setParametro('nit', 'nit','varchar');
			$this->setParametro('cantidad_det','cantidad_det','integer');
			$this->setParametro('unidad','unidad','varchar');
			$this->setParametro('codigo', 'codigo','varchar');
			$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
			$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
			$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
			$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
			$this->setParametro('centro_costo', 'centro_costo','varchar');
			$this->setParametro('clase_costo', 'clase_costo','varchar');
			$this->setParametro('nro', 'nro','varchar');
			$this->setParametro('observaciones', 'observaciones','varchar');
			$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
			$this->setParametro('tipo_factura', 'tipo_factura','varchar');
			$this->setParametro('nro_contrato', 'nro_contrato','varchar');
			
			$this->setParametro('forma_pago', 'forma_pago','varchar');
			$this->setParametro('aplicacion', 'aplicacion','varchar');
			
			$this->setParametro('conteo', 'conteo','int4');
			$this->setParametro('nro_factura', 'nro_factura','varchar');
			$this->setParametro('codigo_factura', 'codigo_factura','varchar');
			$this->setParametro('precio_uni_bs_fac', 'precio_uni_bs_fac','varchar');
			$this->setParametro('nro_autori_fac', 'nro_autori_fac','varchar');

			//Ejecuta la instruccion
            $this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);				
			
			//recupera parametros devuelto depues de insertar ... (id_solicitud)
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			}
			
			//$respuesta = $resp_procedimiento['datos'];
			//var_dump($respuesta);
			//$id_factura_excel= $respuesta['id_factura_excel'];
			
			
			//////////////////////////////////////////////
			//inserta detalle de la compra o venta
			/////////////////////////////////////////////
			
			
			if($this->aParam->getParametro('bandera') == 'TRUE'){
					
					$this->procedimiento='vef.f_inserta_nota';
					$this->transaccion='VF_VALINOT_INS';
					$this->tipo_procedimiento='IME';
							
					//Define los parametros para la funcion
					//$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
		
					//Ejecuta la instruccion
		            $this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);				
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al ejecutar en la bd", 3);
					}
				
			

					$this->resetParametros();
					//Definicion de variables para ejecucion del procedimiento
				    $this->procedimiento='vef.f_inserta_nota';
					$this->transaccion='VF_INSFACNOT_INS';
					$this->tipo_procedimiento='IME';
					
				
							
					//Define los parametros para la funcion
					$this->setParametro('id_funcionario_usu', 'id_funcionario_usu','int4');
					$this->setParametro('razon_social', 'razon_social','varchar');
				    $this->setParametro('nit', 'nit','varchar');
					$this->setParametro('cantidad_det','cantidad_det','integer');
					$this->setParametro('unidad','unidad','varchar');
					$this->setParametro('codigo', 'codigo','varchar');
					$this->setParametro('precio_uni_usd', 'precio_uni_usd','numeric');
					$this->setParametro('precio_uni_bs', 'precio_uni_bs','numeric');
					$this->setParametro('precio_total_usd', 'precio_total_usd','numeric');
					$this->setParametro('precio_total_bs', 'precio_total_bs','numeric');
					$this->setParametro('centro_costo', 'centro_costo','varchar');
					$this->setParametro('clase_costo', 'clase_costo','varchar');
					$this->setParametro('nro_factura', 'nro_factura','varchar');
					$this->setParametro('observaciones', 'observaciones','varchar');
					$this->setParametro('fecha', 'fecha','date');
					$this->setParametro('id_punto_venta', 'id_punto_venta','int4');
					$this->setParametro('tipo_factura', 'tipo_factura','varchar');
					$this->setParametro('nro_contrato', 'nro_contrato','varchar');
					
					$this->setParametro('forma_pago', 'forma_pago','varchar');
					$this->setParametro('aplicacion', 'aplicacion','varchar');
					
					$this->setParametro('conteo', 'conteo','int4');
					
					//Ejecuta la instruccion
		            $this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);				
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al insertar venta  en la bd", 3);
					}
					
				
					

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
	
}
?>