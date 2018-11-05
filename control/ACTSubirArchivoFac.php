<?php
/**
*@package pXP
*@file gen-ACTMemoriaCalculo.php
*@author  (admin)
*@date 01-03-2016 14:22:24
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
include_once(dirname(__FILE__).'/../../lib/lib_general/ExcelInput.php');
class ACTSubirArchivoFac extends ACTbase{
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
		                $conteo = 1;
						
		                foreach ($arrayArchivo as $fila) {
		                	//var_dump($fila)	;echo"<br>";
					
		                    $this->objParam->addParametro('razon_social', $fila['razon_social']);
		                    $this->objParam->addParametro('nit', $fila['nit']);
		                    $this->objParam->addParametro('cantidad_det', $fila['cantidad']);
							$this->objParam->addParametro('unidad', $fila['unidad']);
						 	$this->objParam->addParametro('detalle', $fila['detalle']);
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
							
							$this->objParam->addParametro('conteo', $conteo);

		                    //var_dump($this->objParam);
		                    $this->objFunc = $this->create('MODSubirArchivoFac');
		                    $this->res = $this->objFunc->subirArchivoFac($this->objParam);
		                    
		                    if ($this->res->getTipo() == 'ERROR') {
		                    	
								$this->res->imprimirRespuesta($this->res->generarJson());
					            exit;
								
		                        $error = 'error';
		                        $mensaje_completo = "Error al guardar el fila en tabla :  " . $this->res->getMensajeTec();
		                        break;
		                    }
							$conteo ++;
		                }
						$this->res = $this->objFunc->insertarVentaExcel($this->objParam);	 
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