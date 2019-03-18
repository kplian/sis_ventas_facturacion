<?php
//incluimos la libreria
//echo dirname(__FILE__);
//include_once(dirname(__FILE__).'/../PHPExcel/Classes/PHPExcel.php');
class RReporteXProducto
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
		$this->docexcel->getActiveSheet()->setTitle('Detalle LV');
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
	}
			
	function imprimeDatos(){
		$this->docexcel->setActiveSheetIndex(0);
		$datos = $this->objParam->getParametro('datos');
		$this->docexcel->getActiveSheet()->getStyle('B1:B2')->applyFromArray($this->styleTitulos0);
		
		
		//poner titulos
		//Imprimir Sucursal
		$this->docexcel->getActiveSheet()->setCellValue('B1','Sucursal : '.$this->objParam->getParametro('sucursal'));
		$this->docexcel->getActiveSheet()->setCellValue('B2','Desde : '.$this->objParam->getParametro('fecha_desde') . ' Hasta : ' . $this->objParam->getParametro('fecha_hasta'));
		 $this->styleTitulos0['font']['size'] = 11;
		 
		 $this->docexcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray($this->styleTitulos0);
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		
		//poner cabeceras
		$this->docexcel->getActiveSheet()->setCellValue('A4','Estado');
		$this->docexcel->getActiveSheet()->setCellValue('B4','Tipo Documento');
		$this->docexcel->getActiveSheet()->setCellValue('C4','Fecha');
		$this->docexcel->getActiveSheet()->setCellValue('D4','Autorizacion');
		$this->docexcel->getActiveSheet()->setCellValue('E4','NIT');
		$this->docexcel->getActiveSheet()->setCellValue('F4','Razon Social');
		$this->docexcel->getActiveSheet()->setCellValue('G4','Productos');
		$this->docexcel->getActiveSheet()->setCellValue('H4','Nro Doc');
		$this->docexcel->getActiveSheet()->setCellValue('I4','Monto');
		$this->docexcel->getActiveSheet()->setCellValue('J4','Neto');
		$this->docexcel->getActiveSheet()->setCellValue('K4','IVA');
		$this->docexcel->getActiveSheet()->setCellValue('L4','IT');
		$this->docexcel->getActiveSheet()->setCellValue('M4','Ingreso');
		$fila = 5;
		foreach ($datos as $dato) {
			$this->docexcel->getActiveSheet()->getStyle("A$fila:M$fila")->getAlignment()->setWrapText(true); 
			$this->docexcel->getActiveSheet()->setCellValue("A$fila",$dato['estado']);
			$this->docexcel->getActiveSheet()->setCellValue("B$fila",$dato['tipo_documento']);
			$this->docexcel->getActiveSheet()->setCellValue("C$fila",$dato['fecha']);
			$this->docexcel->getActiveSheet()->setCellValue("D$fila",$dato['autorizacion']);
			$this->docexcel->getActiveSheet()->setCellValue("E$fila",$dato['nit']);
			$this->docexcel->getActiveSheet()->setCellValue("F$fila",$dato['razon_social']);
			$this->docexcel->getActiveSheet()->setCellValue("G$fila",$dato['productos']);
			$this->docexcel->getActiveSheet()->setCellValue("H$fila",$dato['nro_doc']);
			$this->docexcel->getActiveSheet()->setCellValue("I$fila",$dato['monto']);
			$this->docexcel->getActiveSheet()->setCellValue("J$fila",$dato['neto']);
			$this->docexcel->getActiveSheet()->setCellValue("K$fila",$dato['iva']);
			$this->docexcel->getActiveSheet()->setCellValue("L$fila",$dato['it']);
			$this->docexcel->getActiveSheet()->setCellValue("M$fila",$dato['ingreso']);
			$fila++;
		}
		$this->styleTitulos0['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
		$this->docexcel->getActiveSheet()->getStyle("A$fila:M$fila")->applyFromArray($this->styleTitulos0);
		$fila_suma = $fila - 1;
		$this->docexcel->getActiveSheet()->setCellValue("G$fila","TOTALES");
		
		
		$this->docexcel->getActiveSheet()->setCellValue("I$fila","=SUM(I5:I$fila_suma)");
		$this->docexcel->getActiveSheet()->setCellValue("J$fila","=SUM(J5:J$fila_suma)");
		$this->docexcel->getActiveSheet()->setCellValue("K$fila","=SUM(K5:K$fila_suma)");
		$this->docexcel->getActiveSheet()->setCellValue("L$fila","=SUM(L5:L$fila_suma)");
		$this->docexcel->getActiveSheet()->setCellValue("M$fila","=SUM(M5:M$fila_suma)");
		
		
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