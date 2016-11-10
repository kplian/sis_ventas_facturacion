<?php
// Extend the TCPDF class to create custom MultiRow
class RAperturaCierrePDF extends  ReportePDF {
    var $datos;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    function Header() {
        //cabecera del reporte
        $this->Image(dirname(__FILE__).'/../../lib'.$_SESSION['_DIR_LOGO'], 20, 8, 30, 12);
        $this->SetFont('','B',12);
        $this->Ln(4);
        $this->Cell(0,5,'DECLARACIÓN DE VENTAS DIARIAS',0,1,'C');

        $this->SetFont('','B',10);
        $this->Cell(0,5,'Y ARQUEO DE EFECTIVO' ,0,1,'C');
        $this->Ln(1);

    }
    function setDatos($datos) {
        $this->datos = $datos;
    }
    function generarReporte() {
        $this->AddPage();
        $this->SetFillColor(192,192,192, true);
        $this->SetFont('','B',8);
        $this->Cell(0,5,'DATOS GENERALES' ,1,1,'C',1);
        $this->SetFont('','',7);
        $this->Ln(3);
        $this->Cell(35,5,'NOMBRE CAJERO:' ,0,0,'R');
        $this->Cell(70,5,$this->datos[0]['cajero'] ,1,0,'L');
        $this->Cell(30,5,'FECHA DE VENTA:' ,0,0,'R');
        $this->Cell(45,5,$this->datos[0]['fecha'] ,1,1,'L');

        $this->Ln(2);
        $this->Cell(35,5,'PAIS:' ,0,0,'R');
        $this->Cell(70,5,$this->datos[0]['pais'] ,1,1,'L');

        $this->Ln(2);
        $this->Cell(35,5,'ESTACIÓN:' ,0,0,'R');
        $this->Cell(70,5,$this->datos[0]['estacion'] ,1,1,'L');

        $this->Ln(2);
        $this->Cell(35,5,'PUNTO DE VENTA / AGT:' ,0,0,'R');
        $this->Cell(145,5,$this->datos[0]['punto_venta'] ,1,1,'L');

        $this->Ln(3);
        $this->SetFont('','B',8);
        $this->Cell(0,5,'INFORMACIÓN DE VENTA' ,1,1,'C',1);
        $this->Ln(3);

        $this->SetFont('','',7);
        $this->Cell(80,5,'TIPO DE VENTA' ,1,0,'C');
        $this->Cell(50,5,'IMPORTE EN '. $this->datos[0]['cod_moneda_local'] ,1,0,'C');
        $this->Cell(50,5,'IMPORTE EN '. $this->datos[0]['cod_moneda_extranjera'] ,1,1,'C');

        $this->Cell(80,5,'Efectivo Venta de Servicios' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_boletos_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_boletos_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Efectivo Otros Cargos' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_ventas_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_ventas_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Tarjetas de Crédito Venta de Servicios' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['tarjeta_boletos_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['tarjeta_boletos_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Tarjetas de Crédito Otros Cargos' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['tarjeta_ventas_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['tarjeta_ventas_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Comisiones AGTS' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['comisiones_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['comisiones_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Cuentas Corrientes' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['cuenta_corriente_boletos_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['cuenta_corriente_boletos_me'],2) ,1,1,'R');

        $this->Cell(80,5,'MCO' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['mco_boletos_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['mco_boletos_me'],2) ,1,1,'R');

        $this->Cell(80,5,'Otros' ,1,0,'L');
        $this->Cell(50,5,number_format($this->datos[0]['otros_boletos_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['otros_boletos_me'],2) ,1,1,'R');

        $total_venta_ml =   $this->datos[0]['otros_boletos_ml'] + $this->datos[0]['mco_boletos_ml'] + $this->datos[0]['cuenta_corriente_boletos_ml'] +
                            $this->datos[0]['comisiones_ml'] + $this->datos[0]['tarjeta_ventas_ml'] + $this->datos[0]['tarjeta_boletos_ml'] +
                            $this->datos[0]['efectivo_boletos_ml'] + $this->datos[0]['efectivo_ventas_ml'];

        $total_venta_me =   $this->datos[0]['otros_boletos_me'] + $this->datos[0]['mco_boletos_me'] + $this->datos[0]['cuenta_corriente_boletos_me'] +
                            $this->datos[0]['comisiones_me'] + $this->datos[0]['tarjeta_ventas_me'] + $this->datos[0]['tarjeta_boletos_me'] +
                            $this->datos[0]['efectivo_boletos_me'] + $this->datos[0]['efectivo_ventas_me'];

        $this->Cell(80,5,'TOTAL VENTA' ,1,0,'C');
        $this->Cell(50,5,number_format($total_venta_ml,2) ,1,0,'R');
        $this->Cell(50,5,number_format($total_venta_me,2) ,1,1,'R');

        $this->Cell(80,5,'TOTAL EFECTIVO' ,1,0,'C');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_boletos_ml'] + $this->datos[0]['efectivo_ventas_ml'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['efectivo_boletos_me'] + $this->datos[0]['efectivo_ventas_me'],2) ,1,1,'R');

        $this->Ln(3);
        $this->SetFont('','B',8);
        $this->Cell(0,5,'ARQUEO EFECTIVO' ,1,1,'C',1);
        $this->Ln(3);

        $this->SetFont('','',7);
        $this->Cell(40,5,'MONEDA' ,1,0,'C');
        $this->Cell(40,5,'IMPORTES' ,1,0,'C');
        $this->Cell(50,5,'TIPO DE CAMBIO' ,1,0,'C');
        $this->Cell(50,5,'TOTAL '. $this->datos[0]['cod_moneda_local'] ,1,1,'C');

        $this->Cell(40,5,$this->datos[0]['moneda_local'] ,1,0,'L');
        $this->Cell(40,5,number_format($this->datos[0]['arqueo_moneda_local'],2) ,1,0,'R');
        $this->Cell(50,5, number_format(1,2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['arqueo_moneda_local'],2) ,1,1,'R');

        $this->Cell(40,5,$this->datos[0]['moneda_extranjera'] ,1,0,'L');
        $this->Cell(40,5,number_format($this->datos[0]['arqueo_moneda_extranjera'],2) ,1,0,'R');
        $this->Cell(50,5, number_format($this->datos[0]['tipo_cambio'],2) ,1,0,'R');
        $this->Cell(50,5,number_format($this->datos[0]['arqueo_moneda_extranjera'] * $this->datos[0]['tipo_cambio'] ,2) ,1,1,'R');

        $this->Cell(130,5,'TOTAL EFECTIVO' ,1,0,'C');
        $this->Cell(50,5,number_format(($this->datos[0]['arqueo_moneda_extranjera'] * $this->datos[0]['tipo_cambio']) + $this->datos[0]['arqueo_moneda_local'] ,2) ,1,1,'R');
        $this->Ln();

        $this->Cell(25,5,'OBSERVACIÓN:' ,0,0,'R');
        $this->Cell(155,5,$this->datos[0]['obs_cierre'] ,1,1,'R');

        $this->Ln();

        $this->Cell(60,5,'CAJERO' ,1,0,'C',1);
        $this->Cell(60,5,'' ,1,0,'C',1);
        $this->Cell(60,5,'' ,1,1,'C',1);

        $this->Cell(60,20,'' ,1,0,'C',0);
        $this->Cell(60,20,'' ,1,0,'C',0);
        $this->Cell(60,20,'' ,1,1,'C',0);

        $this->Cell(60,5,$this->datos[0]['cajero'] ,1,0,'C',0);
        $this->Cell(60,5,'' ,1,0,'C',0);
        $this->Cell(60,5,'' ,1,1,'C',0);

        $this->SetFont('','',6);
        $this->Cell(60,5,'* El presente documento debe ser firmado y sellado por el cajero' ,0,0,'L',0);



    }


}
?>