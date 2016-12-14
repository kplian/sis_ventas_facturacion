<?php
//incluimos la libreria
//echo dirname(__FILE__);
//include_once(dirname(__FILE__).'/../PHPExcel/Classes/PHPExcel.php');
class RResumenVentasBoaXLS
{
	private $docexcel;
	private $objWriter;
	private $nombre_archivo;
	private $hoja;
	private $columnas=array();
	private $fila;
	private $equivalencias=array();
	
	private $indice, $m_fila, $titulo;	
	private $objParam;
	public  $url_archivo;	
	public $styleTitulos0;
	public $styleTitulos1;
	public $styleTitulos2;
	public $styleDetalle;
	public $styleTotal;
	
	
	function __construct(CTParametro $objParam){
		$this->objParam = $objParam;
		$this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
		//ini_set('memory_limit','512M');
		set_time_limit(400);
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize'  => '10MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$this->docexcel = new PHPExcel();
		$this->docexcel->getProperties()->setCreator("PXP")
							 ->setLastModifiedBy("PXP")
							 ->setTitle($this->objParam->getParametro('titulo_archivo'))
							 ->setSubject($this->objParam->getParametro('titulo_archivo'))
							 ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Report File");
		$this->docexcel->setActiveSheetIndex(0);
		$this->docexcel->getActiveSheet()->setTitle('Resumen');
		$this->equivalencias=array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
								9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
								18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
								26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
								34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
								42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
								50=>'AY',51=>'AZ',
								52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
								60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
								68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
								76=>'BY',77=>'BZ');
	   $this->styleTitulos0 = array(
				    'font'  => array(
				        'bold'  => true,
				        'size'  => 12,
				        'name'  => 'Calibri'
				    ),
				    'alignment' => array(
				        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				    )
					);	
		$this->styleTitulos1 = 	$this->styleTitulos0;
		$this->styleTitulos2 = 	$this->styleTitulos0;
		$this->styleDetalle = 	$this->styleTitulos0;
		
		$this->styleTitulos1['font']['size'] = 20;	
		$this->styleTitulos1['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;	
		$this->styleTitulos2['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
		
		
		$this->styleTitulos2['fill'] = array(
        		'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
            		'rgb' => 'c6d9f1'
            	)
        	);
		$this->styleTitulos2['borders'] = array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN
	        )
	    );	
		
		$this->styleDetalle['borders'] = array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN
	        )
	    );
		
		$this->styleDetalle['font']['bold'] = false;
		
		$this->styleDetalle['font']['size'] = 11;
        $this->styleDetalleRojo = $this->styleDetalle;
        $this->styleDetalleRojo['font']['color'] = array('rgb' => 'CE0000');
		$this->styleTotal = 	$this->styleDetalle;
		$this->styleTotal['font']['bold'] = true;						
	}
			
	function imprimeDatos(){
		$datos = $this->objParam->getParametro('datos');
		
		$config = $this->objParam->getParametro('conceptos');
		$conceptos = array();
		for ($i = 0;$i < count($config);$i++) {									
			$conceptos[$config [$i]['nombre']] = $this->equivalencias[$i + 7];
		}
		
		//Imprime cabecera de resumen
		$this->imprimeCabecera(0,'si');
		
		$fila = 5;
		$fila_general = 5;
		$sheetId = 0;
		$fecha = '';
		$boleto = '';
		$totales = array();
		$correlativo_hoja = 1;
		$correlativo_general = 1;
		foreach ($datos as $key => $value) {
			//si es distinta creamos una nueva hoja	
			if ($value['fecha'] != $fecha) {
				$objFecha = DateTime::createFromFormat('Y-m-d', $value['fecha']);
				$totales[$objFecha->format('dMy')] = array();	
				if ($fila != 5){
					
					//TOTALES
					$this->docexcel->getActiveSheet()->getStyle('A'.($fila + 2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
					$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
					$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
					
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 7] . $fila . ')');
							
					for ($i = 7;$i < 7 + count($conceptos);$i++) {
						$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
					}
				
				}				
				$correlativo_hoja = 1;		
				
				$fila = 5;
				$fecha = $value['fecha'];
				
				$sheetId++;
				$this->docexcel->createSheet(NULL, $sheetId);									
				$this->imprimeCabecera($sheetId,'no',$objFecha);
			}

			$fila++;
			$fila_general++;
			
			if ($value['tipo'] == 'boleto') {
				$boleto = $value['boleto'];
				$recibo = '';
			} else {
				$boleto = $value['boleto'];
				$recibo = $value['correlativo'];
			}

            if ($value['mensaje_error'] != '') {
                $this->docexcel->getActiveSheet()->getStyle('C'.$fila .':D'.$fila)->applyFromArray($this->styleDetalleRojo);
            }
			$this->docexcel->getActiveSheet()->setCellValue('A'.$fila,$correlativo_hoja);
			$this->docexcel->getActiveSheet()->setCellValue('B'.$fila,$objFecha->format('d-M'));
			$this->docexcel->getActiveSheet()->setCellValue('C'.$fila,$value['pasajero']);
			$this->docexcel->getActiveSheet()->setCellValue('D'.$fila,$boleto);
			$this->docexcel->getActiveSheet()->setCellValue('E'.$fila,$recibo);
			$this->docexcel->getActiveSheet()->setCellValue('F'.$fila,$value['ruta']);
			$this->docexcel->getActiveSheet()->setCellValue('G'.$fila,$value['moneda_emision']);
			$this->docexcel->getActiveSheet()->setCellValue('H'.$fila,$value['neto']);

            if ($value['mensaje_error'] != '') {
                $this->docexcel->setActiveSheetIndex(0)->getStyle('C'.$fila_general .':D'.$fila_general)->applyFromArray($this->styleDetalleRojo);
            }
			
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('A'.$fila_general,$correlativo_general);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('B'.$fila_general,$objFecha->format('d-M'));
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('C'.$fila_general,$value['pasajero']);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('D'.$fila_general,$boleto);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('E'.$fila_general,$recibo);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('F'.$fila_general,$value['ruta']);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('G'.$fila_general,$value['moneda_emision']);
			$this->docexcel->setActiveSheetIndex(0)->setCellValue('H'.$fila_general,$value['neto']);
			$this->docexcel->setActiveSheetIndex($sheetId);
			
			//imprimir conceptos e impuestos en hoja actual y en el resume
			$conceptos_array = explode('|', $value['conceptos']);
			$importes_array = explode('|', $value['precios_detalles']);
			
			for($i=0 ; $i<count($conceptos_array); $i++) {
				if ($conceptos_array[$i] != '') {
					$this->docexcel->getActiveSheet()->setCellValue($conceptos[$conceptos_array[$i]].$fila,$importes_array[$i]);
					$this->docexcel->setActiveSheetIndex(0)->setCellValue($conceptos[$conceptos_array[$i]].$fila_general,$importes_array[$i]);
					$this->docexcel->setActiveSheetIndex($sheetId);
				}
			}

			//imprimir totales y ofmr ade pago
			if ($config[count($config)-4]['nombre'] == 'CASH USD') {
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 3].$fila,$value['monto_cash_usd']);
                $this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 4].$fila,$value['monto_otro_usd']);
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 5].$fila,$value['monto_cash_mb']);
                $this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 6].$fila,$value['monto_otro_mb']);
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 7].$fila,$value['forma_pago']);

                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 3].$fila_general,$value['monto_cash_usd']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 4].$fila_general,$value['monto_otro_usd']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 5].$fila_general,$value['monto_cash_mb']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 6].$fila_general,$value['monto_otro_mb']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 7].$fila_general,$value['forma_pago']);
				$this->docexcel->setActiveSheetIndex($sheetId);
			} else {
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 3].$fila,$value['monto_cash_usd']);
                $this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 4].$fila,$value['monto_otro_usd']);
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($config) + 5].$fila,$value['forma_pago']);

                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 3].$fila_general,$value['monto_cash_usd']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 4].$fila_general,$value['monto_otro_usd']);
                $this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[count($config) + 5].$fila_general,$value['forma_pago']);
				$this->docexcel->setActiveSheetIndex($sheetId);
			}
			
			
			
			$correlativo_hoja++;
			$correlativo_general++;
			
		}

		//TOTALES ULTIMA HOJA
		$this->docexcel->getActiveSheet()->getStyle('A'.($fila + 2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
		$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
		$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
		
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 7] . $fila . ')');
		
		for ($i = 7;$i < 7 + count($conceptos);$i++) {
			$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
		}
		
		//TOTALES RESUMEN
		$this->docexcel->setActiveSheetIndex(0)->getStyle('A'.($fila_general + 2) .':'.$this->equivalencias[count($conceptos) + 6].($fila_general+2))->applyFromArray($this->styleTotal);
		$this->docexcel->setActiveSheetIndex(0)->getStyle("E". ($fila_general + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila_general+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$this->docexcel->setActiveSheetIndex(0)->getStyle("A". ($fila_general + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila_general+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->docexcel->setActiveSheetIndex(0)->mergeCells('A'.($fila_general + 2) .':D'.($fila_general+2));
		$this->docexcel->setActiveSheetIndex(0)->setCellValue('A'.($fila_general + 2),'TOTAL');
		
		$this->docexcel->setActiveSheetIndex(0)->
				setCellValue($this->equivalencias[count($conceptos) + 5].($fila_general+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila_general . ')');
		$this->docexcel->setActiveSheetIndex(0)->
				setCellValue($this->equivalencias[count($conceptos) + 6].($fila_general+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 7] . $fila_general . ')');
		
		for ($i = 7;$i < 7 + count($conceptos);$i++) {
			$this->docexcel->setActiveSheetIndex(0)->setCellValue($this->equivalencias[$i].($fila_general + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila_general . ')');
		}
		
	}

	function imprimeCabecera ($sheet, $resumen = 'no',$objFecha = '') {
		$config = $this->objParam->getParametro('conceptos');	
		$this->docexcel->setActiveSheetIndex($sheet);	
		if ($resumen == 'si'){
			$this->docexcel->getActiveSheet()->setTitle('Resumen');
		} else {
			$this->docexcel->getActiveSheet()->setTitle($objFecha->format('d-M'));	
		}
		
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(7);
		$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		
		for ($i = 7;$i < 7 + count($conceptos);$i++) {
							
			$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(12);
		}
		
		$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+1])->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+2])->setWidth(15);
		$this->docexcel->getActiveSheet()->getStyle('B1:F1')->applyFromArray($this->styleTitulos0);
		$this->docexcel->getActiveSheet()->getStyle('B2:F2')->applyFromArray($this->styleTitulos0);
		if ($this->objParam->getParametro('punto_venta') != '') {
			$this->docexcel->getActiveSheet()->setCellValue('B1','OFICINA ' . $this->objParam->getParametro('punto_venta'));
		} else {
			$this->docexcel->getActiveSheet()->setCellValue('B1','SUCURSAL ' . $this->objParam->getParametro('sucursal'));
		}
		if ($resumen == 'si'){
			$this->docexcel->getActiveSheet()->setCellValue('C2','DESDE');
			$this->docexcel->getActiveSheet()->setCellValue('D2',$this->objParam->getParametro('fecha_desde'));
			$this->docexcel->getActiveSheet()->setCellValue('E2','HASTA');
			$this->docexcel->getActiveSheet()->setCellValue('F2',$this->objParam->getParametro('fecha_hasta'));
		} else {
			$this->docexcel->getActiveSheet()->setCellValue('C2','FECHA');
			$this->docexcel->getActiveSheet()->setCellValue('D2',$objFecha->format('d/m/Y'));
		}
		$this->docexcel->getActiveSheet()->mergeCells('A3:'.$this->equivalencias[$i+2].'3');
		$this->docexcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
							
		$this->docexcel->getActiveSheet()->getStyle('A3')->applyFromArray($this->styleTitulos1);				
		if ($resumen == 'si'){
			$this->docexcel->getActiveSheet()->setCellValue('A3','RESUMEN DE VENTAS');
		} else {
			$this->docexcel->getActiveSheet()->setCellValue('A3','REPORTE DIARIO DETALLADO DE VENTAS');
		}
		
		
		$this->docexcel->getActiveSheet()->setCellValue('A5','NRO.');
		$this->docexcel->getActiveSheet()->setCellValue('B5','FECHA');
		$this->docexcel->getActiveSheet()->setCellValue('C5','NOMBRE PAX');
		$this->docexcel->getActiveSheet()->setCellValue('D5','No TKT');
		$this->docexcel->getActiveSheet()->setCellValue('E5','No RECIBO');
		$this->docexcel->getActiveSheet()->setCellValue('F5','RUTA');
		$this->docexcel->getActiveSheet()->setCellValue('G5','MONEDA');
		
		
		
		for ($i = 7;$i < 7 + count($config);$i++) {
			if (substr( $config[$i-7]['tipo'], 0, 7 ) == '4MONEDA') {
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].'5', $config[$i-7]['nombre']);
			} else {
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].'5',$config[$i-7]['nombre']);
			
			}
		}
		
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i] . '5','FORMA PAGO');
		$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(25);
		$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i].'5')->applyFromArray($this->styleTitulos2);
		$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i].'5')->getAlignment()->setWrapText(true); 
	
		
	}

	
	
	function generarReporte(){
		//echo $this->nombre_archivo; exit;
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->docexcel->setActiveSheetIndex(0);
		$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
		$this->objWriter->save($this->url_archivo);	
		
	}	
	

}

?>