<?php
/**
*@package pXP
*@file gen-MODMemoriaCalculo.php
*@author  (admin)
*@date 01-03-2016 14:22:24
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
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
}
?>