<?php
 
class RVentasXls
{
	private $docexcel;
	private $objWriter;
	private $numero;
	private $equivalencias=array();
	private $objParam;
	public  $url_archivo;
	var $liquido;
	var $descuento;
	var $importe;
	var $fila1;
	
	var $sucursal;
	var $punto_venta;
	var $venta;
	var $cont;
	function __construct(CTParametro $objParam)
	{
		//var_dump($objParam);
		$this->objParam = $objParam;
		$this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
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
		$this->equivalencias=array( 0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
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

	}
	function imprimeCabecera($shit,$tipo) {
		//var_dump('datos',$this->objParam);
		
		$this->sucursal = $this->objParam->getParametro('sucursal');
		$this->punto_venta = $this->objParam->getParametro('punto_venta');
		$this->venta = $this->objParam->getParametro('venta');
  		
  		//var_dump('sucursal',$this->sucursal);
		//var_dump('punto venta',$this->punto_venta);
		//var_dump('venta',$this->venta);
		
        $this->docexcel->createSheet($shit);
        $this->docexcel->setActiveSheetIndex($shit);
        $this->docexcel->getActiveSheet()->setTitle($tipo);
	
		$styleTitulos2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => '2D83C5'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$styleTitulos3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => '707A82'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
	$styleTitulos4 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => 'DB9E91'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		
		
		//
		if($shit==1){
		/*	$this->docexcel->getActiveSheet()->getStyle('D1:J1')->applyFromArray($styleTitulos3);				
			$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,'FACTURAS Y SUS COBROS');	
		 * 
		 * 
				*/
					$this->cont = 0;
					foreach ($this->venta as $value){
						 if ($value['id_venta_fk'] != null) {
							 $this->cont++;
						 }
						
					}
				
					/////para todo
					if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') == '' ) {
						
					$this->docexcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleTitulos3);				
					$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'VENTAS');						
					//aplica estilo a una linea a una fila de celdas
					$this->docexcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleTitulos4);
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleTitulos4);
				    //$this->docexcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleTitulos4);
					
					$this->docexcel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($styleTitulos4);
					$this->docexcel->getActiveSheet()->getStyle('A4:N4')->applyFromArray($styleTitulos2);
						
						
					}				
					else{
					$this->docexcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleTitulos3);				
					$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'VENTAS');						
					//aplica estilo a una linea a una fila de celdas
					$this->docexcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($styleTitulos4);
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleTitulos4);
				    //$this->docexcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleTitulos4);
					
					$this->docexcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($styleTitulos4);
					$this->docexcel->getActiveSheet()->getStyle('A4:K4')->applyFromArray($styleTitulos2);
					//$this->docexcel->getActiveSheet()->getStyle('A3:V3')->applyFromArray($styleTitulos2);
					
					}
					
					if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') != '' ) {
					$this->docexcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleTitulos3);				
					$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'VENTAS');						
					//aplica estilo a una linea a una fila de celdas
					$this->docexcel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($styleTitulos4);
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleTitulos4);
				    //$this->docexcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleTitulos4);
					
					$this->docexcel->getActiveSheet()->getStyle('A3:L3')->applyFromArray($styleTitulos4);
					$this->docexcel->getActiveSheet()->getStyle('A4:L4')->applyFromArray($styleTitulos2);
					//$this->docexcel->getActiveSheet()->getStyle('A3:V3')->applyFromArray($styleTitulos2);
					
					 }
					if ($this->cont  > 0) {
					$this->docexcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleTitulos3);				
					$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'VENTAS');						
					//aplica estilo a una linea a una fila de celdas
					$this->docexcel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($styleTitulos4);
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleTitulos4);
				    //$this->docexcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleTitulos4);
					
					$this->docexcel->getActiveSheet()->getStyle('A3:L3')->applyFromArray($styleTitulos4);
					$this->docexcel->getActiveSheet()->getStyle('A4:L4')->applyFromArray($styleTitulos2);
					//$this->docexcel->getActiveSheet()->getStyle('A3:V3')->applyFromArray($styleTitulos2);
					}
					
					//SE COLOCA LAS DIMENSIONES QUE TENDRA LAS CELDAS
					$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
					$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
					$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
					$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
					$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
					$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
					$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
					$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
					
					$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
					$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
					$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(18);
					$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
					$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(18);
					$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
					$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(18);	
					$this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
					$this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);						
					//*************************************Cabecera************************//
					//automaticamente selecciona el campo en las celdas
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setWrapText(true);
					//$this->docexcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setWrapText(true);
					
					 //une celdas 
				
					 
					 $this->docexcel->getActiveSheet()->mergeCells('A1:K1');
					
					 
					//$this->docexcel->getActiveSheet()->mergeCells('B2:C2');
					//$this->docexcel->getActiveSheet()->mergeCells('A3:B3');
					//$this->docexcel->getActiveSheet()->mergeCells('A4:B4');
					
					/*
					$this->docexcel->getActiveSheet()->mergeCells('B2:B3');
					$this->docexcel->getActiveSheet()->mergeCells('C2:C3');
					$this->docexcel->getActiveSheet()->mergeCells('D2:D3');
					$this->docexcel->getActiveSheet()->mergeCells('E2:E3');
					$this->docexcel->getActiveSheet()->mergeCells('F2:F3');
					$this->docexcel->getActiveSheet()->mergeCells('G2:G3');
					
					$this->docexcel->getActiveSheet()->mergeCells('P2:P3');								
					$this->docexcel->getActiveSheet()->mergeCells('Q2:Q3');
					$this->docexcel->getActiveSheet()->mergeCells('R2:R3');				
					$this->docexcel->getActiveSheet()->mergeCells('S2:S3');
					$this->docexcel->getActiveSheet()->mergeCells('T2:T3');
					$this->docexcel->getActiveSheet()->mergeCells('U2:U3');
					$this->docexcel->getActiveSheet()->mergeCells('V2:V3');
									
					
					$this->docexcel->getActiveSheet()->mergeCells('H2:I2');
					$this->docexcel->getActiveSheet()->mergeCells('J2:K2');
					$this->docexcel->getActiveSheet()->mergeCells('L2:M2');
					$this->docexcel->getActiveSheet()->mergeCells('N2:O2');*/
	
					
					//
					if ($this->objParam->getParametro('id_sucursal') != '') {
						
							$this->docexcel->getActiveSheet()->setCellValue('C2','SUCURSAL:');
							$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3,2,$this->sucursal[0]['nombre']);

					} 
					
					if ($this->objParam->getParametro('id_punto_venta')!= '' ) {
						
						$this->docexcel->getActiveSheet()->setCellValue('C3','PUNTO VENTA');
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3,3,$this->punto_venta[0]['nombre']);					
					}
						
							
					
					$this->docexcel->getActiveSheet()->setCellValue('A4','Nº');
					$this->docexcel->getActiveSheet()->setCellValue('B4','FECHA VENTA');
					$this->docexcel->getActiveSheet()->setCellValue('C4','NRO TRAMITE');
					$this->docexcel->getActiveSheet()->setCellValue('D4','CLIENTE');
					$this->docexcel->getActiveSheet()->setCellValue('E4','NIT');
					$this->docexcel->getActiveSheet()->setCellValue('F4','NRO FACTURA');
					$this->docexcel->getActiveSheet()->setCellValue('G4','IMPORTE TOTAL');
					$this->docexcel->getActiveSheet()->setCellValue('H4','MONEDA');
					$this->docexcel->getActiveSheet()->setCellValue('I4','IMPORTE POR COBRAR');
					$this->docexcel->getActiveSheet()->setCellValue('J4','IMPORTE RET. GAR');
					$this->docexcel->getActiveSheet()->setCellValue('K4','IMPORTE ANTICIPO');
					
					if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') == '' ) {
						
					$this->docexcel->getActiveSheet()->setCellValue('L4','FACTURA RELACIONADA');
					$this->docexcel->getActiveSheet()->setCellValue('M4','SUCURSAL');
					$this->docexcel->getActiveSheet()->setCellValue('N4','PUNTO DE VENTA');
					}
					if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') != '' ) {
						$this->docexcel->getActiveSheet()->setCellValue('L4','PUNTO DE VENTA');
					}
				
					if ($this->cont  > 0) {
						$this->docexcel->getActiveSheet()->setCellValue('L4','NRO FACTURA RELACIONADA');	
					}
					


		}		
	}

	function generarDatos()
	{
		$styleTitulos3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => '2D83C5'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$styleTitulos4 = array(
			'font'  => array(
				'bold'  => false,
				'size'  => 9,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => '000000'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
		'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$styleTitulos5 = array(
			'font'  => array(
				'bold'  => false,
				'size'  => 9,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => '000000'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$styleTitulos6 = array(
			'font'  => array(
				'bold'  => false,
				'size'  => 9,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => '000000'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		
		
		$this->numero = 1;
		$fila = 5;
	
		$this->imprimeCabecera(1,'Ventas');
	
		foreach ($this->venta as $value){
					 $a=1;
					 $b=3;
				
						 $originalDate = $value['fecha'];
					     $newDate = date("d/m/Y", strtotime($originalDate));
						
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
				     	$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila,$newDate);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nro_tramite']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['desc_proveedor']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nit']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nro_factura']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['total_venta']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['desc_moneda']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['importe_pendiente']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['importe_retgar']);
						$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['importe_anticipo']);
						
						 if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') == '' ) {
						 	
						 	$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['nro_factura_fk']);
							$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['nombre_sucursal']);
							$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['nombre_punto_venta']);
							
						 }
						if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') != '' ) {
						 	
						 	$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['nombre_punto_venta']);
								
						 }
						 
						 if ($this->cont  > 0) {
							$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['nro_factura_fk']);
							}


						$this->numero++;

					$fila++;
					
					
	
					
		}

		   $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5,$fila,'TOTALES:');
           $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6,$fila,'=SUM(G4:G'.($fila-1).')');
		   $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila,'=SUM(I4:I'.($fila-1).')');
		   $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila,'=SUM(J4:J'.($fila-1).')');
		   $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila,'=SUM(K4:K'.($fila-1).')');
		   
		   $this->docexcel->getActiveSheet()->getStyle('A'.(5).':A'.($fila-1).'')->applyFromArray($styleTitulos5);
		   $this->docexcel->getActiveSheet()->getStyle('B'.(5).':B'.($fila-1).'')->applyFromArray($styleTitulos4);
		   $this->docexcel->getActiveSheet()->getStyle('C'.(5).':C'.($fila-1).'')->applyFromArray($styleTitulos4);
		   $this->docexcel->getActiveSheet()->getStyle('D'.(5).':D'.($fila-1).'')->applyFromArray($styleTitulos6);
		   $this->docexcel->getActiveSheet()->getStyle('E'.(5).':E'.($fila-1).'')->applyFromArray($styleTitulos4);
		   $this->docexcel->getActiveSheet()->getStyle('F'.(5).':F'.($fila-1).'')->applyFromArray($styleTitulos4);
		   $this->docexcel->getActiveSheet()->getStyle('G'.(5).':G'.($fila).'')->applyFromArray($styleTitulos5);
		   $this->docexcel->getActiveSheet()->getStyle('H'.(5).':H'.($fila-1).'')->applyFromArray($styleTitulos5);
		   $this->docexcel->getActiveSheet()->getStyle('I'.(5).':I'.($fila).'')->applyFromArray($styleTitulos5);
		   $this->docexcel->getActiveSheet()->getStyle('J'.(5).':J'.($fila).'')->applyFromArray($styleTitulos5);
		   $this->docexcel->getActiveSheet()->getStyle('K'.(5).':K'.($fila).'')->applyFromArray($styleTitulos5);
		 
		 if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') == '' ) {
		   
		   $this->docexcel->getActiveSheet()->getStyle('L'.(5).':L'.($fila-1).'')->applyFromArray($styleTitulos4);
		   $this->docexcel->getActiveSheet()->getStyle('M'.(5).':M'.($fila-1).'')->applyFromArray($styleTitulos6);
		   $this->docexcel->getActiveSheet()->getStyle('N'.(5).':N'.($fila-1).'')->applyFromArray($styleTitulos6);
		   
		 }
		 
		 if ($this->objParam->getParametro('id_punto_venta') == '' && $this->objParam->getParametro('id_sucursal') != '' ) {
		 	   $this->docexcel->getActiveSheet()->getStyle('L'.(5).':L'.($fila-1).'')->applyFromArray($styleTitulos4);
		 }
		 
		 if ($this->cont  > 0) {
		 	  $this->docexcel->getActiveSheet()->getStyle('L'.(5).':L'.($fila-1).'')->applyFromArray($styleTitulos4);
		 }
		   
		   
		  // $this->docexcel->getActiveSheet()->getStyle('D'.(5).':D'.($fila-1).'')->getNumberFormat()->setFormatCode('#,##0.00');
		   $this->docexcel->getActiveSheet()->getStyle('G'.(5).':G'.($fila).'')->getNumberFormat()->setFormatCode('#,##0.00');///EGS-27/08/2018
		   $this->docexcel->getActiveSheet()->getStyle('I'.(5).':I'.($fila).'')->getNumberFormat()->setFormatCode('#,##0.00');//EGS-27/08/2018
		   $this->docexcel->getActiveSheet()->getStyle('J'.(5).':J'.($fila).'')->getNumberFormat()->setFormatCode('#,##0.00');	
		   $this->docexcel->getActiveSheet()->getStyle('K'.($fila).':K'.($fila).'')->getNumberFormat()->setFormatCode('#,##0.00');
		   
		   
		  	
					

				//Marca desde una celda y una columna 
				/*
				$this->docexcel->getActiveSheet()->getStyle('D'.(3).':D'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('E'.(3).':E'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('F'.(3).':F'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('G'.(3).':G'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				/*
				$this->docexcel->getActiveSheet()->getStyle('K'.(3).':K'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('L'.(3).':L'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				
				
				$this->docexcel->getActiveSheet()->getStyle('M'.(3).':M'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('N'.(3).':N'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('O'.(3).':O'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				
				$this->docexcel->getActiveSheet()->getStyle('P'.(3).':P'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('Q'.(3).':Q'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('R'.(3).':R'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				$this->docexcel->getActiveSheet()->getStyle('S'.(3).':S'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				//*/
				/*
				$this->docexcel->getActiveSheet()->getStyle('A'.($fila+1).':S'.($fila+1).'')->applyFromArray($styleTitulos3);				
				//
				$this->docexcel->getActiveSheet()->getStyle('G'.($fila+1).':S'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
				//
						
				//$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5,$fila+1,'TOTAL');
				
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6,$fila+1,'=SUM(G4:G'.($fila-1).')');				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7,$fila+1,'=SUM(H4:H'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8,$fila+1,'=SUM(I4:I'.($fila-1).')');				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9,$fila+1,'=SUM(J4:J'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10,$fila+1,'=SUM(K4:K'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11,$fila+1,'=SUM(L4:L'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12,$fila+1,'=SUM(M4:M'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13,$fila+1,'=SUM(N4:N'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14,$fila+1,'=SUM(O4:O'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15,$fila+1,'=SUM(P4:P'.($fila-1).')');
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16,$fila+1,'=SUM(Q4:Q'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17,$fila+1,'=SUM(R4:R'.($fila-1).')');
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18,$fila+1,'=SUM(S4:S'.($fila-1).')');
											
				$formula = '=SUM(G1:G'.($fila-1).')';
				
				$sum = PHPExcel_Calculation::getInstance($this->docexcel)->calculateFormula($formula, 'A1', $this->docexcel->getActiveSheet()->getCell('A1'));
		
		$this->generarResultado(1,$sum);*/
		
	}
	//
	function generarReporte(){
		$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
		$this->objWriter->save($this->url_archivo);
	}
	
	
	function generarResultado ($sheet,$a){
		$this->docexcel->createSheet($sheet);
		$this->docexcel->setActiveSheetIndex(0);
		$this->imprimeCabecera($sheet,'TOTAL');
		$this->docexcel->getActiveSheet()->setTitle('TOTALES');
		$this->docexcel->getActiveSheet()->setCellValue('E5','TOTAL');
		$this->docexcel->getActiveSheet()->setCellValue('F5',$a);
		
	}
	
}
?>
