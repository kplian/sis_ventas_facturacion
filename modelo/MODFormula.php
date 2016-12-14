<?php
/**
*@package pXP
*@file gen-MODFormula.php
*@author  (admin)
*@date 21-04-2015 09:14:49
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFormula extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFormula(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_formula_sel';
		$this->transaccion='VF_FORM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_formula','int4');
		$this->captura('id_tipo_presentacion','int4');
		$this->captura('id_unidad_medida','int4');
		$this->captura('id_medico','int4');
		$this->captura('nombre','varchar');
		$this->captura('cantidad','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('descripcion','text');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('desc_unidad_medida','text');
        $this->captura('desc_medico','text');
        $this->captura('precio','numeric');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFormula(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_ime';
		$this->transaccion='VF_FORM_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_presentacion','id_tipo_presentacion','int4');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('id_medico','id_medico','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('cantidad_form','cantidad','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    
    function insertarFormulaCompleta() {
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
            $this->procedimiento = 'vef.ft_formula_ime';            
            $this->tipo_procedimiento = 'IME';
			
			if ($this->aParam->getParametro('id_formula') != '') {
				
								
				//Eliminar detalles
				$this->transaccion = 'VF_FORALLDET_ELI';
				$this->setParametro('id_formula','id_formula','int4');
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
				$this->transaccion = 'VF_FORM_MOD';
			} else {
				$this->transaccion = 'VF_FORM_INS';
			}
            
            //Define los parametros para la funcion
            $this->setParametro('id_tipo_presentacion','id_tipo_presentacion','int4');
            $this->setParametro('id_unidad_medida','id_unidad_medida','int4');
            $this->setParametro('id_medico','id_medico','int4');
            $this->setParametro('nombre','nombre','varchar');
			if ($this->aParam->getParametro('cantidad') != '') {
            	$this->setParametro('cantidad_form','cantidad','int4');
			}
            $this->setParametro('estado_reg','estado_reg','varchar');
            $this->setParametro('descripcion','descripcion','text');
            
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
            
            $id_formula = $respuesta['id_formula'];
                       
            //decodifica JSON  de detalles 
            $json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));           
            
            //var_dump($json_detalle)   ;
            foreach($json_detalle as $f){
                
                $this->resetParametros();
                //Definicion de variables para ejecucion del procedimiento
                $this->procedimiento='vef.ft_formula_detalle_ime';
                $this->transaccion='VF_FORDET_INS';
                $this->tipo_procedimiento='IME';
                //modifica los valores de las variables que mandaremos
                $this->arreglo['id_producto'] = $f['id_producto'];
				$this->arreglo['tipo'] = $f['tipo'];
                $this->arreglo['cantidad'] = $f['cantidad'];
                $this->arreglo['id_formula'] = $id_formula;                
                
                //Define los parametros para la funcion
                $this->setParametro('id_producto', 'id_producto', 'int4');
                $this->setParametro('id_formula', 'id_formula', 'int4');
                $this->setParametro('cantidad_det', 'cantidad', 'numeric'); 
				$this->setParametro('tipo', 'tipo', 'varchar');                
                
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
			
	function modificarFormula(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_ime';
		$this->transaccion='VF_FORM_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_formula','id_formula','int4');
		$this->setParametro('id_tipo_presentacion','id_tipo_presentacion','int4');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('id_medico','id_medico','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('cantidad_form','cantidad','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');
        $this->setParametro('duplicar','duplicar','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFormula(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_formula_ime';
		$this->transaccion='VF_FORM_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_formula','id_formula','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>