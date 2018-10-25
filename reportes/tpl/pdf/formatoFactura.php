<?php
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
						    
					
					  <!--link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_media_carta.css" type="text/css"  charset="utf-8"-->
					
				</head>
				
					
				<body>

			  <table style="margin-top: 1px;">
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
				
				<h2 style="text-align: center;">FACTURA</h2>		
						
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
										
						<tr>
							<td style="border-top: thin solid black;" colspan="3">
								<h3 style="text-align: center;">DETALLE&nbsp;</h3>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>ITEM</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="40%"><strong>DESCRIPCION</strong></td>
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>CANTIDAD</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>PRECIO UNITARIO</strong></td>
							<td style="text-align: center; border: thin solid black;" width="20%"><strong>SUBTOTAL</strong></td>
						</tr>
<?php
					$valor_bruto = 0;
					$i = 1;
					foreach ($this->detalle as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						echo '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
					//var_dump('hola',$this->cabecera['estado']);
					
					if ($this->cabecera['estado'] == 'borrador') {
						$this->estado = 'BORRADOR';
					} 
					elseif ($this->cabecera['estado'] == 'anulado'){
						$this->estado = 'ANULADO';
					} else {
						$this->estado = ' ';
					}
?>	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					
						<tr>
							<td width="53%" style="text-align:center;"><br></td>
							<td width="32.5%"><strong>TOTAL  <?php echo $this->cabecera['moneda_venta'] ; ?>:</strong> </td>
							<td style="text-align: right;" width="14%"><strong><?php echo number_format($this->cabecera['total_venta_msuc'], 2, '.', ',') ; ?></strong></td>
						</tr>						
					
				</table>
	
				<p>Son : <strong><?php echo $this->cabecera['total_venta_msuc_literal'] ; ?> <?php echo $this->moneda; ?></strong></p>
				<table  >
						<tr>
							<td width="65%">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								
							</td>
							<td width="35%" rowspan="3">
							<?php
							
							//echo $this->write2DBarcode($this->cadena_qr, 'QRCODE,H', 10,10,30,0, $style,'T',true);
							
							//echo $this->barcodeobj->getBarcodeSVG(2, 30, 'black');
							//echo  '<img src = "'.$this->barcodeobj->getBarcodePNG(2, 30, array(0,128,0)).'.png ">';
							//echo '<img src = "'. $this->barcodeobj->getBarcodePngData (10, 10, array (0,0,0)).'.png ">';
						    echo  '<img src = "'.$this->img_qr.'" width="130" height="130">';
							
					
						  ?>
						  </td>
						
							
						</tr>
					
						<tr>
							
							<td style="text-align:left;" >
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							
						</tr>
						<tr>
							<td>
								&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong><?php echo $this->cabecera['fecha_limite_emision'] ; ?></strong>
							</td>
							
							
						</tr>
					
				</table>
				<table >
					
					<tr>
						 <td style="text-align:center;  font-size: '10px';"  ><strong><?php echo $this->cabecera['glosa_empresa'] ; ?></strong> </td>
					</tr>
					<tr>
					  <td style="text-align:center;  font-size: '10px';"  ><strong><?php echo $this->cabecera['glosa_impuestos'] ; ?></strong>	</td>
					</tr>
					
					
					
				</table>
				
				 
				
<?php
/*
				if ($this->cabecera['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}*/

				
	
?>						
				<script type="text/javascript"> 

<?php
						if ($datos['estado'] == 'finalizado') {
							echo'setTimeout(function(){
										 self.print();							 
										}, 1000);
									
									setTimeout(function(){
										 self.close();							 
										}, 2000);';
						}
?>					
				</script>                                                                                   
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
		 <table >
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
			<table>
				<tr>
					<td style="text-align: center;" colspan="6"> <strong>Datos de la Transaccion Original</strong> </td>
					
				</tr>
				<tr>
					<td width="15%">Factura Nro</td>
					<td width="10%"> <?php echo $this->factura_cabecera['numero_factura'];?></td>
					<td  width="20%">Autorizacion Nro</td>
					<td width="25%"> <?php echo $this->factura_cabecera['autorizacion'];?></td>
					<td  width="10%">Fecha</td>
					<td width="15%"> <?php echo $this->factura_cabecera['fecha_venta'];?></td>
				</tr>
				
				
			</table>	
			<br />
			<table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>ITEM</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="40%"><strong>DESCRIPCION</strong></td>
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>CANTIDAD</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>PRECIO UNITARIO</strong></td>
							<td style="text-align: center; border: thin solid black;" width="20%"><strong>SUBTOTAL</strong></td>
						</tr>
<?php
					//var_dump($this->factura_cabecera['numero_factura']);
					$valor_bruto = 0;
					$i = 1;
					foreach ($this->factura_detalle as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						echo '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
				
?>	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					
						<tr>
							<td width="53.5%" style="text-align:center;"><br></td>
							<td width="32.5%"><strong>TOTAL  <?php echo $this->factura_cabecera['moneda_venta'] ; ?>:</strong> </td>
							<td style="text-align: right;" width="14%"><strong><?php echo number_format($this->factura_cabecera['total_venta_msuc'], 2, '.', ',') ; ?></strong></td>
						</tr>						
					
				</table>
		
				<hr style="color: black; background-color: black; width:93%;"  />
				<br/>
					
			
			<table>
				<tr>
					<td style="text-align: center;" colspan="6"> <strong>Detalle de la devolucion o rescision de servicio</strong> </td>
					
				</tr>
							
				
			</table>
				<table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>ITEM</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="40%"><strong>DESCRIPCION</strong></td>
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>CANTIDAD</strong></td>							
							<td style="text-align: center; border: thin solid black;" width="15%"><strong>PRECIO UNITARIO</strong></td>
							<td style="text-align: center; border: thin solid black;" width="20%"><strong>SUBTOTAL</strong></td>
						</tr>
<?php
					$valor_bruto = 0;
					$i = 1;
					foreach ($this->detalle as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						echo '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
					//var_dump('hola',$this->cabecera['estado']);
					
					if ($this->cabecera['estado'] == 'borrador') {
						$this->estado = 'BORRADOR';
					} 
					elseif ($this->cabecera['estado'] == 'anulado'){
						$this->estado = 'ANULADO';
					} else {
						$this->estado = ' ';
					}
?>	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					
						<tr>
							<td width="53%" style="text-align:center;"><br></td>
							<td width="32.5%"><strong>Importe Total Devuelto <?php echo $this->cabecera['moneda_venta'] ; ?>:</strong> </td>
							<td style="text-align: right;" width="14%"><strong><?php echo number_format($this->cabecera['total_venta_msuc'], 2, '.', ',') ; ?></strong></td>
						</tr>						
					
				</table>
				<p>Son : <strong><?php echo $this->cabecera['total_venta_msuc_literal'] ; ?> <?php echo $this->moneda; ?></strong></p>
				
				<table >
						<tr>
							<td width="85%">Monto Efectivo del Credito o debito (13% del importe Total Devuelto)</td>
							<td><?php echo number_format($this->cabecera['total_venta_msuc']*0.13, 2, '.', ',') ;echo ' '; echo $this->cabecera['moneda_venta'] ; ?></td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="65%">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="35%" rowspan="2">
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="130" height="130">';
							
										 //echo  $this->cadena_qr;
						  		?>
						  </td>
						
						
						</tr>
						<tr>
							<td>
								&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong><?php echo $this->cabecera['fecha_limite_emision'] ; ?></strong>
							</td>
							
							
						</tr>
					
				</table>

				<table >
					
					<tr>
						 <td style="text-align:center;  font-size: '10px';" ><strong><?php echo $this->cabecera['glosa_empresa'] ; ?></strong> </td>
					</tr>
					<tr>
					  <td style="text-align:center;  font-size: '10px';"  ><strong><?php echo $this->cabecera['glosa_impuestos'] ; ?></strong>	</td>
					</tr>
					
					
					
				</table>
				
<?php
/*
				if ($this->cabecera['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}

				
			echo'<script language="VBScript">
					
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
				</script>';*/
?>						
				<script type="text/javascript"> 

<?php
						if ($datos['estado'] == 'finalizado') {
							echo'setTimeout(function(){
										 self.print();							 
										}, 1000);
									
									setTimeout(function(){
										 self.close();							 
										}, 2000);';
						}
?>					
				</script>                                                                                   
                 </body>
            </html>



<?php
}

?>

