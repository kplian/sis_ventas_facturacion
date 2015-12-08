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
		$this->styleTotal = 	$this->styleDetalle;
		$this->styleTotal['font']['bold'] = true;						
	}
			
	function imprimeDatos(){
		$datos = $this->objParam->getParametro('datos');
		
		$config = $this->objParam->getParametro('conceptos');
		$conceptos = array();
		for ($i = 0;$i < count($config);$i++) {
									
			$conceptos[$i] = $config [$i]['nombre'];
		}
		$fila = 5;
		$sheetId = 0;
		$fecha = '';
		$boleto = '';
		$totales = array();
		foreach ($datos as $key => $value) {
			//si es distinta creamos una nueva hoja	
			if ($value['fecha'] != $fecha) {
				$objFecha = DateTime::createFromFormat('Y-m-d', $value['fecha']);
				$totales[$objFecha->format('dMy')] = array();	
				if ($fila != 5){
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
					//TOTALES
					$this->docexcel->getActiveSheet()->getStyle('A'.($fila + 2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
					$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
					$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
					
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 4].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 4].'6:' . $this->equivalencias[count($conceptos) + 4] . $fila . ')');
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 5] . $fila . ')');
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
				
					for ($i = 4;$i < 4 + count($conceptos);$i++) {
						$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
					}
				
				}
				$total_boleto = 0;
				$total_boleto_cc = 0;
				$total_boleto_cash = 0;
				
				
				$fila = 5;
				$fecha = $value['fecha'];
				
				$sheetId++;
				$this->docexcel->createSheet(NULL, $sheetId);	
				$this->docexcel->setActiveSheetIndex($sheetId);	
				$this->docexcel->getActiveSheet()->setTitle($objFecha->format('d M y'));	
				$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
				$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
				for ($i = 4;$i < 4 + count($conceptos);$i++) {
									
					$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(10);
				}
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(12);
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+1])->setWidth(12);
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+2])->setWidth(15);
				
				$this->docexcel->getActiveSheet()->getStyle('B2:D2')->applyFromArray($this->styleTitulos0);
				if ($this->objParam->getParametro('punto_venta') != '') {
					$this->docexcel->getActiveSheet()->setCellValue('B2','OFICINA ' . $this->objParam->getParametro('punto_venta'));
				} else {
					$this->docexcel->getActiveSheet()->setCellValue('B2','SUCURSAL ' . $this->objParam->getParametro('sucursal'));
				}
				$this->docexcel->getActiveSheet()->setCellValue('C2','FECHA');
				$this->docexcel->getActiveSheet()->setCellValue('D2',$objFecha->format('d/m/Y'));
				
				$this->docexcel->getActiveSheet()->mergeCells('A3:'.$this->equivalencias[$i+2].'3');
				$this->docexcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
									
				$this->docexcel->getActiveSheet()->getStyle('A3')->applyFromArray($this->styleTitulos1);				
				$this->docexcel->getActiveSheet()->setCellValue('A3','REPORTE DIARIO DETALLADO DE VENTAS');
				
				$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i+2].'5')->applyFromArray($this->styleTitulos2);
				$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i+2].'5')->getAlignment()->setWrapText(true); 
				$this->docexcel->getActiveSheet()->setCellValue('A5','NRO.');
				$this->docexcel->getActiveSheet()->setCellValue('B5','NOMBRE PAX');
				$this->docexcel->getActiveSheet()->setCellValue('C5','No TKT');
				$this->docexcel->getActiveSheet()->setCellValue('D5','RUTA');
				
				for ($i = 4;$i < 4 + count($conceptos);$i++) {
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].'5',$conceptos[$i-4]);
				}
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i] . '5','TOTAL');
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i+1] . '5','CASH');
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i+2] . '5','CREDIT CARD');
			}
			if ($value['boleto'] != $boleto) {
				if ($fila != 5){
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
				}
				
				$total_boleto = 0;
				$total_boleto_cc = 0;
				$total_boleto_cash = 0;
				$fila++;
				
				$this->docexcel->getActiveSheet()->getStyle("A$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->applyFromArray($this->styleDetalle);
				$this->docexcel->getActiveSheet()->getStyle("E$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$this->docexcel->getActiveSheet()->getStyle("E$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$boleto = $value['boleto'];
				$this->docexcel->getActiveSheet()->setCellValue('A'.$fila,$value['correlativo']);
				$this->docexcel->getActiveSheet()->setCellValue('B'.$fila,$value['pasajero']);
				$this->docexcel->getActiveSheet()->setCellValue('C'.$fila,$value['boleto']);
				$this->docexcel->getActiveSheet()->setCellValue('D'.$fila,$value['ruta']);
				
				
			}
			$pos = array_search($value['concepto'], $conceptos);
			$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$pos + 4].$fila,$value['monto']);
			$total_boleto += $value['monto'];
			$total_boleto_cc += $value['monto_tarjeta'];
			$total_boleto_cash += $value['monto_cash'];
		}
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
		
		//TOTALES
		$this->docexcel->getActiveSheet()->getStyle('A'.($fila+2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
		$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
		$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
		
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 4].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 4].'6:' . $this->equivalencias[count($conceptos) + 4] . $fila . ')');
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 5] . $fila . ')');
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
	
		for ($i = 4;$i < 4 + count($conceptos);$i++) {
			$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
		}
		
	}

	function imprimeDatosResumen(){
		$datos = $this->objParam->getParametro('resumen');
		
		$config = $this->objParam->getParametro('conceptos');
		$conceptos = array();
		for ($i = 0;$i < count($config);$i++) {
									
			$conceptos[$i] = $config [$i]['nombre'];
		}
		$fila = 5;
		$sheetId = 0;
		$fecha = '';
		$boleto = '';
		$totales = array();
		foreach ($datos as $key => $value) {
			//si es distinta creamos una nueva hoja	
			if ($value['fecha'] != $fecha) {
				$objFecha = DateTime::createFromFormat('Y-m-d', $value['fecha']);
				$totales[$objFecha->format('dMy')] = array();	
				if ($fila != 5){
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
					//TOTALES
					$this->docexcel->getActiveSheet()->getStyle('A'.($fila + 2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
					$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
					$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
					
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 4].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 4].'6:' . $this->equivalencias[count($conceptos) + 4] . $fila . ')');
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 5] . $fila . ')');
					$this->docexcel->getActiveSheet()->
							setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
				
					for ($i = 4;$i < 4 + count($conceptos);$i++) {
						$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
					}
				
				}
				$total_boleto = 0;
				$total_boleto_cc = 0;
				$total_boleto_cash = 0;
				
				
				$fila = 5;
				$fecha = $value['fecha'];
				
				$sheetId++;
				$this->docexcel->createSheet(NULL, $sheetId);	
				$this->docexcel->setActiveSheetIndex($sheetId);	
				$this->docexcel->getActiveSheet()->setTitle($objFecha->format('d M y'));	
				$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
				$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
				for ($i = 4;$i < 4 + count($conceptos);$i++) {
									
					$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(10);
				}
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i])->setWidth(12);
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+1])->setWidth(12);
				$this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$i+2])->setWidth(15);
				
				$this->docexcel->getActiveSheet()->getStyle('B2:D2')->applyFromArray($this->styleTitulos0);
				if ($this->objParam->getParametro('punto_venta') != '') {
					$this->docexcel->getActiveSheet()->setCellValue('B2','OFICINA ' . $this->objParam->getParametro('punto_venta'));
				} else {
					$this->docexcel->getActiveSheet()->setCellValue('B2','SUCURSAL ' . $this->objParam->getParametro('sucursal'));
				}
				$this->docexcel->getActiveSheet()->setCellValue('C2','FECHA');
				$this->docexcel->getActiveSheet()->setCellValue('D2',$objFecha->format('d/m/Y'));
				
				$this->docexcel->getActiveSheet()->mergeCells('A3:'.$this->equivalencias[$i+2].'3');
				$this->docexcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
									
				$this->docexcel->getActiveSheet()->getStyle('A3')->applyFromArray($this->styleTitulos1);				
				$this->docexcel->getActiveSheet()->setCellValue('A3','REPORTE DIARIO DETALLADO DE VENTAS');
				
				$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i+2].'5')->applyFromArray($this->styleTitulos2);
				$this->docexcel->getActiveSheet()->getStyle('A5:'.$this->equivalencias[$i+2].'5')->getAlignment()->setWrapText(true); 
				$this->docexcel->getActiveSheet()->setCellValue('A5','NRO.');
				$this->docexcel->getActiveSheet()->setCellValue('B5','NOMBRE PAX');
				$this->docexcel->getActiveSheet()->setCellValue('C5','No TKT');
				$this->docexcel->getActiveSheet()->setCellValue('D5','RUTA');
				
				for ($i = 4;$i < 4 + count($conceptos);$i++) {
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].'5',$conceptos[$i-4]);
				}
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i] . '5','TOTAL');
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i+1] . '5','CASH');
				$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i+2] . '5','CREDIT CARD');
			}
			if ($value['boleto'] != $boleto) {
				if ($fila != 5){
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
					$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
				}
				
				$total_boleto = 0;
				$total_boleto_cc = 0;
				$total_boleto_cash = 0;
				$fila++;
				
				$this->docexcel->getActiveSheet()->getStyle("A$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->applyFromArray($this->styleDetalle);
				$this->docexcel->getActiveSheet()->getStyle("E$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$this->docexcel->getActiveSheet()->getStyle("E$fila:" . $this->equivalencias[count($conceptos) + 6] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$boleto = $value['boleto'];
				$this->docexcel->getActiveSheet()->setCellValue('A'.$fila,$value['correlativo']);
				$this->docexcel->getActiveSheet()->setCellValue('B'.$fila,$value['pasajero']);
				$this->docexcel->getActiveSheet()->setCellValue('C'.$fila,$value['boleto']);
				$this->docexcel->getActiveSheet()->setCellValue('D'.$fila,$value['ruta']);
				
				
			}
			$pos = array_search($value['concepto'], $conceptos);
			$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$pos + 4].$fila,$value['monto']);
			$total_boleto += $value['monto'];
			$total_boleto_cc += $value['monto_tarjeta'];
			$total_boleto_cash += $value['monto_cash'];
		}
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 4].$fila,$total_boleto);
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 5].$fila,$total_boleto_cash);
		$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[count($conceptos) + 6].$fila,$total_boleto_cc);
		
		//TOTALES
		$this->docexcel->getActiveSheet()->getStyle('A'.($fila+2) .':'.$this->equivalencias[count($conceptos) + 6].($fila+2))->applyFromArray($this->styleTotal);
		$this->docexcel->getActiveSheet()->getStyle("E". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$this->docexcel->getActiveSheet()->getStyle("A". ($fila + 2).":" . $this->equivalencias[count($conceptos) + 6] . ($fila+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->docexcel->getActiveSheet()->mergeCells('A'.($fila + 2) .':D'.($fila+2));
		$this->docexcel->getActiveSheet()->setCellValue('A'.($fila + 2),'TOTAL');
		
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 4].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 4].'6:' . $this->equivalencias[count($conceptos) + 4] . $fila . ')');
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 5].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 5].'6:' . $this->equivalencias[count($conceptos) + 5] . $fila . ')');
		$this->docexcel->getActiveSheet()->
				setCellValue($this->equivalencias[count($conceptos) + 6].($fila+2),'=SUM(' . $this->equivalencias[count($conceptos) + 6].'6:' . $this->equivalencias[count($conceptos) + 6] . $fila . ')');
	
		for ($i = 4;$i < 4 + count($conceptos);$i++) {
			$this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$i].($fila + 2),'=SUM(' . $this->equivalencias[$i]. "6:" . $this->equivalencias[$i].$fila . ')');
		}
		
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