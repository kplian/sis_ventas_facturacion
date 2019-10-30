<?php
/**
*@package pXP
*@file gen-ACTMemoriaCalculo.php
*@author  (admin)
*@date 01-03-2016 14:22:24
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
ISSUE				FECHA		    AUTOR				DESCRIPCION
 #6	endeETR	 	    25/10/2019	    EGS					Se agrega descripcion al xls para subir archivos

*/
include_once(dirname(__FILE__).'/../../lib/lib_general/ExcelInput.php');
class ACTSubirArchivoFac extends ACTbase{
		var $objFuncD ;
		var $resD;
	
		function SubirArchivoFactura(){
			
				$this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]); 
				//var_dump($this->objParam);
		        $arregloFiles = $this->objParam->getArregloFiles();
		        $ext = pathinfo($arregloFiles['archivo']['name']);
		        $extension = $ext['extension'];
		        $error = 'no';
		        $mensaje_completo = '';
		        
		        if(isset($arregloFiles['archivo']) && is_uploaded_file($arregloFiles['archivo']['tmp_name'])) {
		            if (!in_array($extension, array('xls', 'xlsx', 'XLS', 'XLSX'))) {
		                $mensaje_completo = "La extensión del archivo debe ser XLS o XLSX";
		                $error = 'error_fatal';
		            } else {
		                //procesa Archivo
		                $archivoExcel = new ExcelInput($arregloFiles['archivo']['tmp_name'], 'SUBFACTURA');
		                $archivoExcel->recuperarColumnasExcel();
		                $arrayArchivo = $archivoExcel->leerColumnasArchivoExcel();
						$tamano=sizeof($arrayArchivo);
						//var_dump($arrayArchivo);
		                $conteo = 0;
						
		                foreach ($arrayArchivo as $fila) {
		                	//var_dump($fila)	;echo"<br>";
					
		                    $this->objParam->addParametro('razon_social', $fila['razon_social']);
		                    $this->objParam->addParametro('nit', $fila['nit']);
		                    $this->objParam->addParametro('cantidad_det', $fila['cantidad']);
							$this->objParam->addParametro('unidad', $fila['unidad']);
						 	$this->objParam->addParametro('codigo', $fila['codigo']);
		                    $this->objParam->addParametro('precio_uni_usd', $fila['precio_uni_usd']);
		                    $this->objParam->addParametro('precio_uni_bs', $fila['precio_uni_bs']);
							$this->objParam->addParametro('precio_total_usd', $fila['precio_total_usd']);
		                    $this->objParam->addParametro('precio_total_bs', $fila['precio_total_bs']);
		                    $this->objParam->addParametro('centro_costo', $fila['centro_costo']);
							$this->objParam->addParametro('clase_costo', $fila['clase_costo']);
							$this->objParam->addParametro('nro', $fila['nro']);
		                    $this->objParam->addParametro('observaciones', $fila['observaciones']);
                            $this->objParam->addParametro('descripcion', $fila['descripcion']);//#6
		                    $this->objParam->addParametro('fecha', $fila['fecha']);
							$this->objParam->addParametro('nro_contrato', $fila['nro_contrato']);
							$this->objParam->addParametro('forma_pago', $fila['forma_pago']);
							$this->objParam->addParametro('aplicacion', $fila['aplicacion']);
							
							
							$this->objParam->addParametro('conteo', $conteo);
							
							$this->objParam->addParametro('bandera', 'FALSE');
							
							$conteo ++;
							
							if($tamano == $conteo ){
								
								$this->objParam->addParametro('bandera', 'TRUE');
							}

		                    //var_dump($this->objParam);
		                    $this->objFunc = $this->create('MODSubirArchivoFac');
		                   //$this->res = $this->objFunc->subirArchivoFac($this->objParam);
		                    $this->res = $this->objFunc->insertarVentaCompletoXLS($this->objParam);
		                    if ($this->res->getTipo() == 'ERROR'){
		                    	
								$this->res->imprimirRespuesta($this->res->generarJson());
					            exit;
								
		                        $error = 'error';
		                        $mensaje_completo = "Error al guardar el fila en tabla :  " . $this->res->getMensajeTec();
		                        break;
		                    }
							
		                }
/*
						$this->objFunc = $this->create('MODSubirArchivoFac');
						$this->res = $this->objFunc->insertarVentaExcel($this->objParam);	*/
		            }
		        } else {
		            $mensaje_completo = "No se subio el archivo";
		            $error = 'error_fatal';
		        }
		        
				
		
		        if ($error == 'error_fatal') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('ERROR','ACTIntTransaccion.php',$mensaje_completo,
		                $mensaje_completo,'control');
		            //si no es error fatal proceso el archivo
		        }
		
		        if ($error == 'error') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('ERROR','ACTIntTransaccion.php','Ocurrieron los siguientes errores : ' . $mensaje_completo,
		                $mensaje_completo,'control');
		
		        } else if ($error == 'no') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('EXITO','ACTIntTransaccion.php','El archivo fue ejecutado con éxito',
		                'El archivo fue ejecutado con éxito','control');
		        }
				//var_dump('hola');
				
				
		
		        //devolver respuesta
		        $this->mensajeRes->imprimirRespuesta($this->mensajeRes->generarJson());
		  }
	function listarExcelEliminado(){
		
		$this->objParam->defecto('ordenacion','id_temporal_data');

		$this->objParam->defecto('dir_ordenacion','asc');
		if ($this->objParam->getParametro('id_punto_venta') != '') {
            $this->objParam->addFiltro(" puve.id_punto_venta = " .  $this->objParam->getParametro('id_punto_venta'));
        }
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSubirArchivoFac','listarTemporalData');
		} else{
			$this->objFunc=$this->create('MODSubirArchivoFac');
			
			$this->res=$this->objFunc->listarTemporalData($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	function SubirArchivoNota(){

				$this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]); 
				//var_dump($this->objParam);
		        $arregloFiles = $this->objParam->getArregloFiles();
		        $ext = pathinfo($arregloFiles['archivo']['name']);
		        $extension = $ext['extension'];
		        $error = 'no';
		        $mensaje_completo = '';
		        
		        if(isset($arregloFiles['archivo']) && is_uploaded_file($arregloFiles['archivo']['tmp_name'])) {
		            if (!in_array($extension, array('xls', 'xlsx', 'XLS', 'XLSX'))) {
		                $mensaje_completo = "La extensión del archivo debe ser XLS o XLSX";
		                $error = 'error_fatal';
		            } else {
		                //procesa Archivo
		                $archivoExcel = new ExcelInput($arregloFiles['archivo']['tmp_name'], 'SUBNOTA');
		                $archivoExcel->recuperarColumnasExcel();
		                $arrayArchivo = $archivoExcel->leerColumnasArchivoExcel();
						$tamano=sizeof($arrayArchivo);
						//var_dump($arrayArchivo);
		                $conteo = 0;
						
		                foreach ($arrayArchivo as $fila) {
		                	//var_dump($fila)	;echo"<br>";
					
		                    $this->objParam->addParametro('razon_social', $fila['razon_social']);
		                    $this->objParam->addParametro('nit', $fila['nit']);
		                    $this->objParam->addParametro('cantidad_det', $fila['cantidad']);
							$this->objParam->addParametro('unidad', $fila['unidad']);
						 	$this->objParam->addParametro('codigo', $fila['codigo']);
		                    $this->objParam->addParametro('precio_uni_usd', $fila['precio_uni_usd']);
		                    $this->objParam->addParametro('precio_uni_bs', $fila['precio_uni_bs']);
							$this->objParam->addParametro('precio_total_usd', $fila['precio_total_usd']);
		                    $this->objParam->addParametro('precio_total_bs', $fila['precio_total_bs']);
		                    $this->objParam->addParametro('centro_costo', $fila['centro_costo']);
							$this->objParam->addParametro('clase_costo', $fila['clase_costo']);
							$this->objParam->addParametro('nro_factura', $fila['nro_factura']);
		                    $this->objParam->addParametro('observaciones', $fila['observaciones']);
		                    $this->objParam->addParametro('fecha', $fila['fecha']);
							$this->objParam->addParametro('nro_contrato', $fila['nro_contrato']);
							$this->objParam->addParametro('forma_pago', $fila['forma_pago']);
							$this->objParam->addParametro('aplicacion', $fila['aplicacion']);
							$this->objParam->addParametro('nro', $fila['nro']);
							$this->objParam->addParametro('codigo_factura', $fila['codigo_factura']);
							$this->objParam->addParametro('precio_uni_bs_fac', $fila['precio_uni_bs_fac']);
							$this->objParam->addParametro('nro_autori_fac', $fila['nro_autori_fac']);
							
							$this->objParam->addParametro('conteo', $conteo);
							
							$this->objParam->addParametro('bandera', 'FALSE');
							
							$conteo ++;
							
							if($tamano == $conteo ){
								
								$this->objParam->addParametro('bandera', 'TRUE');
							}

		                    //var_dump($this->objParam);
		                    $this->objFunc = $this->create('MODSubirArchivoFac');
		                   //$this->res = $this->objFunc->subirArchivoFac($this->objParam);
		                    $this->res = $this->objFunc->insertarNotaCompletoXLS($this->objParam);
		                    if ($this->res->getTipo() == 'ERROR'){
		                    	
								$this->res->imprimirRespuesta($this->res->generarJson());
					            exit;
								
		                        $error = 'error';
		                        $mensaje_completo = "Error al guardar el fila en tabla :  " . $this->res->getMensajeTec();
		                        break;
		                    }
							
		                }
/*
						$this->objFunc = $this->create('MODSubirArchivoFac');
						$this->res = $this->objFunc->insertarVentaExcel($this->objParam);	*/
		            }
		        } else {
		            $mensaje_completo = "No se subio el archivo";
		            $error = 'error_fatal';
		        }
		        
				
		
		        if ($error == 'error_fatal') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('ERROR','ACTIntTransaccion.php',$mensaje_completo,
		                $mensaje_completo,'control');
		            //si no es error fatal proceso el archivo
		        }
		
		        if ($error == 'error') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('ERROR','ACTIntTransaccion.php','Ocurrieron los siguientes errores : ' . $mensaje_completo,
		                $mensaje_completo,'control');
		
		        } else if ($error == 'no') {
		            $this->mensajeRes=new Mensaje();
		            $this->mensajeRes->setMensaje('EXITO','ACTIntTransaccion.php','El archivo fue ejecutado con éxito',
		                'El archivo fue ejecutado con éxito','control');
		        }
				//var_dump('hola');
				
				
		
		        //devolver respuesta
		        $this->mensajeRes->imprimirRespuesta($this->mensajeRes->generarJson());
		  }	

}

?>