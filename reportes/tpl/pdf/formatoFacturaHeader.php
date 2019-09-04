<?php
/*
 * 	ISSUE		FECHA		AUTHOR 		DESCRIPCION
 * 	#5			09/08/2019	EGS			Nuevo formato de factura 
 */
require_once(dirname(__FILE__).'/../../../../lib/tcpdf/tcpdf_barcodes_2d.php');


if ($this->codigo_reporte == 'FACMEDIACAR' || $this->codigo_reporte == 'FACMEDIACARVINTO') {



    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
    <html >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>sis_ventas_facturacion</title>
        <meta name="author" content="kplian">



    </head>


    <body>

    <table  width="645px">
        <tr >

            <td style="text-align:left;" rowspan="5" width="245px" height="100">
                <?php
                echo '<img src="../../../lib'.($this->codigo_reporte== 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_3.png').'" alt="logo" width="200" height="100" />';
                ?>
            </td>

            <td width="200px">NIT:</td>
            <td width="200px"><?php echo $this->cabecera['nit_entidad'] ; ?></td>

        </tr>
        <tr>


            <td>FACTURA:</td>
            <td><?php echo $this->cabecera['numero_factura'] ; ?></td>
        </tr>
        <tr>

            <td>AUTORIZACION:</td>
            <td><?php echo $this->cabecera['autorizacion'] ; ?></td>
        </tr>
        <?php


        echo $this->pagina;

        ?>
        <tr>

            <td style="text-align: center;"  colspan="2" ><?php echo $this->cabecera['actividades']; ?></td>

        </tr>

    </table>
    <br />
    <br />

    <table   >
        <tr>

            <td  width="645px" height="20px" style="text-align: center;"><strong><?php echo $this->cabecera['nombre_sucursal']; ?></strong></td>
        </tr>
        <tr >

            <td width="645px" height="20px" style="text-align: center;"><?php echo $this->cabecera['direccion_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td width="645px" height="20px" style="text-align: center;"><?php echo $this->cabecera['telefono_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td width="645px" height="20px" style="text-align: center;"><?php echo $this->cabecera['lugar_sucursal'] ; ?></td>
        </tr>

    </table>

    <?php
    if($this->count == 1){
        ?>
        <h2 style="text-align: center;">FACTURA </h2>
        <table style="border: thin solid black;" width="645">
            <tbody>
            <tr>
                <td width="30%">Lugar y Fecha</td>
                <td width="70%"><strong><?php echo $this->cabecera['departamento_sucursal'] ; ?> , <?php echo $this->cabecera['fecha_literal'] ; ?></strong></td>

            </tr>
            <tr>
                <td>Señor(es):</td>
                <td><strong><?php echo $this->cabecera['cliente'] ; ?></strong></td>

            </tr>
            <tr>
                <td>NIT/CI</td>
                <td><strong><?php echo $this->cabecera['nit_cliente'] ; ?></strong></td>

            </tr>
            <tr>
                <td>Observaciones</td>
                <td><strong><?php echo$this->cabecera['observaciones']	 ; ?></strong></td>

            </tr>


            </tbody>
        </table>
        <?php
    }
    ?>
    <table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="645">
        <tbody>
        <tr>
            <td style="border-top: thin solid black; text-align: center;" colspan="3">
                <strong>DETALLE&nbsp;</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; border: thin solid black;" width="10%"><strong>ITEM</strong></td>
            <td style="text-align: center; border: thin solid black;" width="40%"><strong>DESCRIPCION</strong></td>
            <td style="text-align: center; border: thin solid black;" width="15%"><strong>CANTIDAD</strong></td>
            <td style="text-align: center; border: thin solid black;" width="15%"><strong>PRECIO UNITARIO</strong></td>
            <td style="text-align: center; border: thin solid black;" width="20%"><strong>SUBTOTAL</strong></td>
        </tr>
    </table>

    </body>
    </html>


<?php }
elseif  ($this->codigo_reporte == 'NOTAFACMEDIACAR' || $this->codigo_reporte == 'FACMEDIACARVINTO')  {

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <title>sis_ventas_facturacion</title>
        <meta charset="UTF-8">
        <meta name="title" content="Título de la WEB">
        <meta name="description" content="Descripción de la WEB">


    </head>


    <body>
    <table  >
        <tr >
            <td style="text-align:left;" rowspan="5" width="45%">
                <?php
                echo '<img src="../../../lib'.($this->codigo_reporte== 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_3.png').'" alt="logo" width="200" height="100" />';
                ?>
            </td>
            <td width="30%">NIT:</td>
            <td width="30%"><?php echo $this->cabecera['nit_entidad'] ; ?></td>

        </tr>
        <tr>


            <td>Nº NOTA FISCAL:</td>
            <td><?php echo $this->cabecera['numero_factura'] ; ?></td>
        </tr>
        <tr>

            <td>AUTORIZACION:</td>
            <td><?php echo $this->cabecera['autorizacion'] ; ?></td>
        </tr>
        <?php


        echo $this->pagina;

        ?>
        <tr>

            <td style="text-align: center;"  colspan="2" ><?php echo $this->cabecera['actividades']; ?></td>

        </tr>

    </table>
    <br />
    <br />

    <table width="93%"  >
        <tr>

            <td  style="text-align: center;"><strong><?php echo $this->cabecera['nombre_sucursal']; ?></strong></td>
        </tr>
        <tr >

            <td  style="text-align: center;"><?php echo $this->cabecera['direccion_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td  style="text-align: center;"><?php echo $this->cabecera['telefono_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td  style="text-align: center;"><?php echo $this->cabecera['lugar_sucursal'] ; ?></td>
        </tr>

    </table>
    <?php
    if ($this->count == 1) {


        ?>
        <h2 style="text-align: center;">NOTA CREDITO/DEBITO</h2>

        <table style="border: thin solid black;" width="645">
            <tbody>
            <tr>
                <td width="30%">Lugar y Fecha</td>
                <td width="70%"><strong><?php echo $this->cabecera['departamento_sucursal'] ; ?> , <?php echo $this->cabecera['fecha_literal'] ; ?></strong></td>

            </tr>
            <tr>
                <td>Señor(es):</td>
                <td><strong><?php echo $this->cabecera['cliente'] ; ?></strong></td>

            </tr>
            <tr>
                <td>NIT/CI</td>
                <td><strong><?php echo $this->cabecera['nit_cliente'] ; ?></strong></td>

            </tr>
            <tr>
                <td>Observaciones</td>
                <td><strong><?php echo$this->cabecera['observaciones']	 ; ?></strong></td>

            </tr>


            </tbody>

        </table>
        <?php
    }
    ?>

    </body>
    </html>

    <?php
}


if ($this->codigo_reporte == 'RECIBOETR' ) {//#5



    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
    <html >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>sis_ventas_facturacion</title>
        <meta name="author" content="kplian">



    </head>


    <body>

    <table  width="645px">
        <tr >

            <td style="text-align:left;"  width="400px">

            </td>

            <td width="100px" style="font-size:20px"><strong>NIT:</strong></td>
            <td width="150px" style="font-size:20px"><?php echo $this->cabecera['nit_entidad'] ; ?></td>

        </tr>
        <tr>

            <td style="text-align:left;"  width="400px">

            </td>
            <td><strong>FACTURA:</strong></td>
            <td><?php echo $this->cabecera['numero_factura'] ; ?></td>
        </tr>
        <tr>

            <td style="text-align:left;" width="400px" >

            </td>
            <td><strong>AUTORIZACION:</strong></td>
            <td><?php echo $this->cabecera['autorizacion'] ; ?></td>
        </tr>
        <?php


        echo $this->pagina;

        ?>
        <tr>
            <td style="text-align:left;"  width="400px" height="50">
                <?php
                echo '<img src="../../../lib'.($this->codigo_reporte== 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_3.png').'" alt="logo" width="150" height="50" />';
                ?>
            </td>
            <td style="text-align: center;" colspan="2" ><?php echo $this->cabecera['actividades']; ?></td>


        </tr>

    </table>

    <table   >
        <tr>

            <td  width="645px" height="20px" style="text-align: left; font-size:20px"><strong><?php echo $this->cabecera['nombre_sucursal']; ?></strong></td>
        </tr>
        <tr >

            <td width="300px" height="20px" style="text-align: left;"><?php echo $this->cabecera['direccion_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td width="645px" height="20px" style="text-align: left;"><?php echo $this->cabecera['telefono_sucursal'] ; ?></td>
        </tr>
        <tr >

            <td width="645px" height="20px" style="text-align: left;"><?php echo $this->cabecera['lugar_sucursal'] ; ?></td>
        </tr>

    </table>

    <?php
    if($this->count == 1){
        ?>
        <h1 style="text-align: center;">RECIBO DE ALQUILER</h1>
        <table style="border: thin solid black;" width="660px">
            <tbody>
            <tr>
                <td width="30%" height="20px"><strong> Lugar y Fecha</strong></td>
                <td width="70%"  ><?php echo ucfirst(strtolower($this->cabecera['departamento_sucursal']) ); ?> , <?php echo $this->cabecera['fecha_literal'] ; ?></td>

            </tr>
            <tr>
                <td height="20px"><strong> Señor(es):</strong></td>
                <td><?php echo $this->cabecera['cliente'] ; ?></td>

            </tr>
            <tr>
                <td height="20px" ><strong> NIT/CI:</strong></td>
                <td><?php echo $this->cabecera['nit_cliente'] ; ?></td>

            </tr>

            </tbody>
        </table>
        <?php
    }
    ?>
    <table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="660px">
        <tbody>
        <tr>
            <td style="border-top: thin solid black; text-align: center" colspan="3">
                <strong>DETALLE</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; border: thin solid black;" width="30%"><strong>PERIODO</strong></td>
            <td style="text-align: center; border: thin solid black;" width="50%"><strong>ESPECIFICACION DEL INMUEBLE</strong></td>
            <!--td style="text-align: center; border: thin solid black;" width="15%"><strong>CANTIDAD</strong></td>
            <td style="text-align: center; border: thin solid black;" width="15%"><strong>PRECIO UNITARIO</strong></tds-->
            <td style="text-align: center; border: thin solid black;" width="20%"><strong>SUBTOTAL</strong></td>
        </tr>
    </table>

    </body>
    </html>


<?php }
?>

