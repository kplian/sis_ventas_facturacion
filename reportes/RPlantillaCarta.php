<?php

include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
Class RPlantillaCarta {

    private $dataSource;
    private $tipo_global;
    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;
    }
    public function tipoCarta($tipo){
        $this->tipo_global = $tipo;
    }
    function write($fileName) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        if ($this->tipo_global == 'sn'){
            $document = $phpWord->loadTemplate(dirname(__FILE__).'/plantilla_carta_sn.docx');
        }else{
            $document = $phpWord->loadTemplate(dirname(__FILE__).'/plantilla_carta_nc.docx');
        }
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        //var_dump($this->dataSource[0]); exit;
        if ($this->tipo_global == 'sn') {
            $f_actual = date('d-m-Y');
            $document->setValue('FECHA', $this->fechaLiteral($f_actual));
            $document->setValue('TRAMITE', $this->dataSource[0]['nro_venta']);
            $document->setValue('NOMBRE', $this->dataSource[0]['actividades']);
            $document->setValue('NRO_FAC', $this->dataSource[0]['numero_factura']);
            $document->setValue('MONEDA', $this->dataSource[0]['moneda_sucursal']);
            $document->setValue('IMPORTE', $this->dataSource[0]['total_venta']);
            $document->setValue('MONTO_LITERAL', $this->dataSource[0]['total_venta_msuc_literal']);
            $mes_literal = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
            $fecha_det = explode("/", $this->dataSource[0]['fecha_venta']);
            $mes = $mes_literal[$fecha_det[1] - 1] . '/' . $fecha_det[2];
            $document->setValue('MES', $mes);
        }else{
            $f_actual = date('d-m-Y');
            $document->setValue('FECHA', $this->fechaLiteral($f_actual));
            $document->setValue('TRAMITE', $this->dataSource[0]['nro_venta']);
            $document->setValue('MONTO', $this->dataSource[0]['sujeto_credito']);
            $document->setValue('FACTURA', $this->dataSource[0]['numero_factura']);
            $document->setValue('TOTAL', $this->dataSource[0]['total_venta']);
            $document->setValue('FE_FAC', $this->dataSource[0]['fecha_venta'] );
            $document->setValue('CHEQUE', '');
        }

        $document->saveAs($fileName);

    }

    function fechaLiteral($va){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $fecha = strftime("%d de %B de %Y", strtotime($va));
        return $fecha;
    }
}
?>