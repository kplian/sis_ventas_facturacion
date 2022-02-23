<?php
/**
*@package pXP
*@file gen-MODActividadEconomica.php
*@author  (jrivera)
*@date 06-10-2015 21:23:23
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/
include dirname(__FILE__).'/../../sis_siat/lib/SiatClassWs.inc';
class MODActividadEconomica extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='vef.ft_actividad_economica_sel';
		$this->transaccion='VF_ACTECO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_actividad_economica','int4');
		$this->captura('codigo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('descripcion','text');
		$this->captura('nombre','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('descripcion','descripcion','text');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarActividadEconomica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='vef.ft_actividad_economica_ime';
		$this->transaccion='VF_ACTECO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function sincronizarActividad(){
		$cone = new conexion();
		$link = $cone->conectarpdo(); 
		$urlMetodo = $this->getUrlMetodoSincronizacion($link, 'sincronizacion', 'actividad');
		$cuis = $this->getCuis($link);
		try {
			$wsOperaciones= new WsFacturacionSincroniza(
				$urlMetodo[0],
				MODFunBasicas::getVariableGlobal('siat_token'),//get config token
				MODFunBasicas::getVariableGlobal('siat_ambiente'),//get config ambiente
				MODFunBasicas::getVariableGlobal('siat_codigo_sistema'), //get config codigo sistema 
				MODFunBasicas::getVariableGlobal('siat_nit'), //get config nit
				$cuis, // get cuis
				0,//sucursal
				0);
			$resultop = $wsOperaciones->{$urlMetodo[1]}();
			
			$rop = $wsOperaciones->ConvertObjectToArray($resultop);
			$this->insertaTablaSincronizacion($link, 'vef.tactividad_economica', $rop['RespuestaListaActividades']['listaActividades']);
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'Procesamiento exitoso ','Procesamiento exitoso ','modelo',$this->nombre_archivo,'procesarServices','IME','');
		} catch (Exception $e) {			
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
		}
		return $this->respuesta;
	}

	function getUrlMetodoSincronizacion($link, $tipo, $subtipo){
		$resArray = array();
		$sql = "SELECT  url, recepcion
				FROM siat.tdireccion_servicio 
				WHERE tipo = '{$tipo}' and subtipo = '{$subtipo}'";

        foreach ($link->query($sql) as $row) {
            $resArray = array($row['url'],$row['recepcion']);
		}
		if (empty($resArray)) {
			throw new Exception("No existe direccion configurada para la sincronizacion: {$tipo} , {$subtipo} ");
	   	}
		return $resArray;
	}
	function getCuis($link){
		$codigo = '';
		$sql = "SELECT  codigo
				FROM siat.tcuis 
				WHERE now() between fecha_inicio and fecha_fin and estado_reg = 'activo'";

        foreach ($link->query($sql) as $row) {
            $codigo = $row['codigo'];
		}
		if ($codigo == '') {
			throw new Exception("No existe codigo cuis valido en este momento");
	   	}
		return $codigo;
	}
	function insertaTablaSincronizacion($link, $tabla, $datos) {
		if ($this->isAssoc($datos)) {
			$datos2 = [$datos];
		} else {
			$datos2 = $datos;
		}
		foreach ($datos2 as $key => $value) {
			$sql = "INSERT INTO {$tabla} (id_usuario_reg,codigo,nombre, descripcion)
					VALUES ({$_SESSION["ss_id_usuario"]}, '{$value['codigoCaeb']}','{$value['descripcion']}','{$value['descripcion']}')
					ON CONFLICT (codigo) DO UPDATE SET nombre = EXCLUDED.nombre, descripcion = EXCLUDED.descripcion;";			
							
			$stmt = $link->prepare($sql);
			$stmt->execute();
		}
		
	}
	function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
			
}
?>