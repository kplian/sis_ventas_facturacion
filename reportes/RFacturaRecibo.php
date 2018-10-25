<?php
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RFacturaRecibo
{
	function generarHtml ($codigo_reporte,$datos) {
			//var_dump($datos);
			
		if ($codigo_reporte == 'RECPAPELTERM' || $codigo_reporte == 'RECPAPELMATRI') {	
			setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				
			
			$html.='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print.css" type="text/css" media="print" charset="utf-8">
					  
					</head>';
					if ($codigo_reporte == 'RECPAPELMATRI') {
						$html.='<body style="font-size: 11pt;">';
					}else {
						$html.='<body style="font-size: 10pt;">';
					}	
					
				$html .= '<center>
					<p style="text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;' . $datos['nombre_entidad'] . '</br>
					    &nbsp;&nbsp;Sucursal ' . $datos['nombre_sucursal'] . '</br>
					    &nbsp;&nbsp;' . $datos['direccion_sucursal'] . ' <br />
					    
					    TELF. ' . $datos['telefono_sucursal'] . ' <br />
					    &nbsp;&nbsp;' . $datos['lugar_sucursal'] . ' <br />
					</p>
					</center>
					<hr />
					<p style="text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;RECIBO OFICIAL
					</p>
					<hr />
					<p style="text-align: center;">
					    No RECIBO: ' . $datos['nro_venta'] . '
					</p>
					<hr />';
					
					$html.='<p>
					    ' . $datos['departamento_sucursal'] . ', '.strftime("%d de %B de %Y", strtotime($datos['fecha_venta_recibo'])).'<br/>
					    Senor(es): '.trim($datos['cliente']).'					    
					</p>
					<hr/>
					
					
					<table style="width: 295px;">	
					
					<thead>
					
						<tr><th style="width: 11px;">Ca</th><th style="width:150px;">Concepto</th><th align="center">PU</th><th>Total</th></tr>
					</thead>
					<tbody>';					
					
					foreach ($datos['detalle'] as $item_detalle) {
					    $html .= '<tr>
							<td style="width:11px;">'.number_format($item_detalle['cantidad'], 2, '.', '').'</td>
							<td style="width:150px;"> '.str_replace( "/", " / ", $item_detalle['concepto'] ).'</td>
							<td align="right">'.number_format($item_detalle['precio_unitario'], 2, '.', '').'</td>
							<td align="right">'.number_format($item_detalle['precio_total'], 2, '.', '').'</td>
							</tr>';				   
					
					}

					$html.='<tr><td colspan="4"></td></tr>';
					$html.='</tbody>
					    <tfoot>
					    <tr><td colspan="4" align="right">Total  '.$datos['moneda_sucursal'].' '.number_format($datos['total_venta'], 2, '.', '').' &nbsp;&nbsp;&nbsp;</td></tr>
					    </tfoot>
					</table>
					
					
					
					
					<p>
					    Son: ' . $datos['total_venta_literal'] . ' '.$datos['moneda_sucursal'].'
					</p>
					<hr/>
					<p>
					    
					    OBS: '.$item['observaciones'].' <br/>
					</p>
									
								
					
					<p style="text-align: center;">&nbsp;&nbsp;&nbsp;GRACIAS POR SU PREFERENCIA !<br/> 
					    &nbsp;&nbsp;&nbsp;' . $datos['pagina_entidad'] . '</p>
					    
						<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						setTimeout(function(){
							 self.print();
							 
							}, 1000);					
						
						setTimeout(function(){
							 self.close();							 
							}, 2000);	
						</script> 
											
				</body>
				</html>';
			}

			elseif ($codigo_reporte == 'FACPAPELTERM' || $codigo_reporte == 'FACPAPELMATRI') {
				$cadena_qr = 	$datos['nit_entidad'] . '|' . 
						$datos['numero_factura'] . '|' . 
						$datos['autorizacion'] . '|' . 
						$datos['fecha_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['codigo_control'] . '|' . 
						$datos['nit_cliente'] . '|0.00|0.00|0.00|0.00';
						
				$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,H');	
				
				//$barcodeobj->getBarcodeSVGcode(3, 3, 'black')
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
					
				
				$html.='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print.css" type="text/css"  charset="utf-8">
					  
					</head>';
				if ($codigo_reporte == 'FACPAPELMATRI') {
					$html.='<body style="font-size: 11pt;">';
				}else {
					$html.='<body style="font-size: 10pt;">';
				}	
					
				$html .= '<center>
					<table style="width:295px;">
				<thead>
				<tr   >
						<td colspan="2" style=" text-align: center;" align="center" >
							' . $datos['nombre_entidad'] . '<br />
							Sucursal ' . $datos['nombre_sucursal'] . '<br />
							' . $datos['direccion_sucursal'].'<br />
							Telf: ' . $datos['telefono_sucursal'].'<br />
							' . $datos['lugar_sucursal'].'<br />
						<hr/>
						</td>
				</tr>
				
				<tr>
					<td colspan="2" align="center" style="text-align: center;"><strong>FACTURA</strong><hr/></td>
				</tr>
				
				<tr>
					<td style="width: 200px;" colspan="2"  align="center">
						NIT : ' . $datos['nit_entidad'] . '<br/>
						FACTURA : '.$datos['numero_factura'] . '<br/> 
						N&#176; AUTORIZACION : ' . $datos['autorizacion'] . '<hr/>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						'.$datos['actividades'].'				
					</td>
				</tr>
				<tr>
					<td colspan="2">						
 						Fecha: '.$datos['fecha_venta'].'<br/>
					    Senor(es): '.trim($datos['cliente']).'<br/>
					    NIT/CI: '.$datos['nit_cliente'].'<hr/>
					</td>
				</tr>
					
					
					<table style="width: 295px;">				
					
					<thead>
					
						<tr><th style="width: 11px;">Ca</th><th style="width:150px;">Concepto</th><th align="center">PU</th><th>SubTotal</th></tr>
					</thead>
					<tbody>';					
					
					foreach ($datos['detalle'] as $item_detalle) {
					    $html .= '<tr>
							<td style="width: 11px;">'.number_format($item_detalle['cantidad'], 2, '.', '').'</td>
							<td style="width:150px;"> '.str_replace( "/", " / ", $item_detalle['concepto'] ).'</td>
							<td align="right">'.number_format($item_detalle['precio_unitario'], 2, '.', '').'</td>
							<td align="right">'.number_format($item_detalle['precio_total'], 2, '.', '').'</td>
							</tr>';				   
					
					}

					$html.='<tr><td colspan="4"></td></tr>';
					$html.='</tbody>
					    <tfoot>
					    <tr>
					    	<td colspan="2" align="left"><b>Total General</b> <hr/></td>
					    	<td colspan="2" align="right"> <b>' .$datos['moneda_sucursal'].' '.number_format($datos['total_venta'], 2, '.', ',').'</b><hr/></td>
					    </tr>';
						
					if ($datos['total_venta'] > $datos['sujeto_credito']) {
						$html .= '<tr>
					    	<td colspan="2" align="left"><b>Sujeto a credito fiscal</b> <hr/></td>
					    	<td colspan="2" align="right"> <b>' .$datos['moneda_sucursal'].' '.number_format($datos['sujeto_credito'], 2, '.', ',').'</b><hr/></td>
					    </tr>';
					}
					
					    
					    
					$html .=' <tr>
						    <td colspan="4">Son: '.$datos['total_venta_literal']. ' '.$datos['moneda_sucursal'].' </td>						    
						</tr>
						<tr>
						    <td colspan="4">OBS: '.$datos['observaciones'].' </td>						    
						</tr>
						<tr>
						    <td colspan="4"><b>Código de control: '.$datos['codigo_control'].'</b></td>
						 </tr>
						  <tr>
						    <td colspan="4"><b>Fecha limite de Emisión: '.$datos['fecha_limite_emision'].'</b></td>						    
						  </tr>
						  
						  <tr>
						    
						    <td colspan="4">
						    	<div align="center">
								    '.$barcodeobj->getBarcodeSVGcode(3, 3, 'black').'
								</div>
							</td>
						  </tr>
						  <tr>
				  			
						    <td colspan="4" style=" text-align: center;" align="center">&quot;' . $datos['glosa_impuestos'] . '&quot;
						    <br/>					    
						    &quot;' . $datos['glosa_empresa'] . '&quot;
						    </td>
						  </tr>
						  
						  <tr>
				  			
						    <td colspan="4" style=" text-align: center;" align="center">Cajero: ' . $_SESSION["_LOGIN"] . '  Id: ' . $datos['id'] . '  Hora: ' . $datos['hora'] . '
						    <br/>
						    GRACIAS POR SU PREFERENCIA	
						    <br/>						    				    
						    ' . $datos['pagina_entidad'] .'
						    </td>
						  </tr>
					    </tfoot>
					</table>					
					    
						<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						setTimeout(function(){
							 self.print();							 
							}, 1000);
						
						setTimeout(function(){
							 self.close();							 
							}, 2000);						
						</script> 
											
				</body>
				</html>';
			} else if ($codigo_reporte == 'FACEXPORTCARTA' || $codigo_reporte == 'FACEXPORTCARTAVINTO') {
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_carta.css" type="text/css"  charset="utf-8">
					  
					</head>';
				
					
				$html .= '<body>
				<center>';
				
				if ($datos['estado'] == 'borrador') {
					$pagina = '	<div id="watermark-borrador"></div>';
				} else if ($datos['estado'] == 'anulado') {
					$pagina = '	<div id="watermark-anulado"></div>';
				} else {
					$pagina = '';
				}	

				$pagina .= '
				<table style="height: 130px;" width="645">
					<tbody>
						<tr>
							<td>
								<table style="height: 130px;" width="180">
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib' . ($codigo_reporte == 'FACEXPORTCARTAVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_reporte.png') .'" alt="logo" width="60" height="60" /></td>
												
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['nombre_sucursal'] . '</strong></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td>
								<table style="height: 130px;" width="285">
									<tbody>
										<tr>
											<td style="text-align:center;">
												<h2 style="text-align: center;">FACTURA COMERCIAL DE EXPORTACION</h2>
												<h4 style="text-align: center;">EXPORT COMMERCIAL INVOICE</h4>
												<h4 style="text-align: center;">Sin Derecho a Credito Fiscal</h3>
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td style="text-align: left;" width="180">
								<table style="height: 74px;" width="172">
									<tbody>
										<tr>
											<td style="text-align: left;"><strong>NIT:</strong></td>
											<td style="text-align: left;">' . $datos['nit_entidad'] . '</td>
										</tr>
										<tr>
											<td><strong>FACTURA:</strong></td>
											<td>' . $datos['numero_factura'] . '</td>
										</tr>
										<tr>
											<td><strong>AUTORIZACION:</strong></td>
											<td>' . $datos['autorizacion'] . '</td>
										</tr>
										<tr>
											<td style="text-align: center;" colspan="2">
												<h3>&nbsp;<strong>ORIGINAL</strong></h3>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="border: thin solid black;background-color: #e1e1d0;" width="645">
				<tbody>
						<tr>
							<td style="font-size: 8pt;">' . $datos['direccion_sucursal'] . '</td>
						</tr>
				</tbody>
				</table>
				<br>
				<table style="border: thin solid black;" width="645">
					<tbody>
						<tr>
							<td>Lugar y Fecha/Place and Date</td>
							<td><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . ';</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Nombre/Name</td>
							<td><strong>' . $datos['cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Direccion del Importador/Address</td>
							<td><strong>' .nl2br($datos['direccion_cliente']) . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>NIT</td>
							<td><strong>' . $datos['nit_cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Puerto Destino/Incoterm</td>
							<td><strong>' . $datos['observaciones'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Moneda de la Transaccion Comercial/Currency</td>
							<td><strong>' . $datos['desc_moneda_venta'] . '</strong></td>
							<td>Tipo de Cambio<strong>:' . $datos['tipo_cambio_venta'] . '</strong></td>
						</tr>
						<tr>
							<td>Cantidad y Descripcion de Bultos</td>
							<td><strong>' . $datos['descripcion_bulto'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="border-top: thin solid black;" colspan="3">
								<h3 style="text-align: center;">DETALLE&nbsp;</h3>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="border-collapse: collapse; height: 33px;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center; border: thin solid black;" width="8%"><strong>ITEM</strong></td>
							<td style="text-align: center; border: thin solid black;" width="14%"><strong>NANDINA</strong></td>
							<td style="text-align: center; border: thin solid black;" width="32%"><strong>DESCRIPCION</strong></td>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>CANTIDAD</strong></td>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>UNIDAD DE MEDIDA</strong></td>
							<td style="text-align: center; border: thin solid black;" width="12%"><strong>PRECIO UNITARIO</strong></td>
							<td style="text-align: center; border: thin solid black;" width="14%"><strong>TOTAL</strong></td>
						</tr>';
					$valor_bruto = 0;
					
					foreach ($datos['detalle'] as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						$pagina .= '<tr>
							<td style="text-align: right; border: thin solid black;">1</td>
							<td style="border: thin solid black;">' . $item_detalle['nandina'] . '</td>
							<td style="border: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['cantidad'], 6, '.', ',') . '</td>
							<td style="border: thin solid black;">' . $item_detalle['unidad_medida'] . '</td>
							<td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['precio_unitario'], 6, '.', ',') . '</td>
							<td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
					}
					if ($codigo_reporte == 'FACEXPORTCARTAVINTO') {
						$pagina .= '<tr>
							<td style="text-align: right; border: thin solid black;" colspan="6">TOTAL</td>
							<td style="text-align: right; border: thin solid black;">' . number_format($valor_bruto, 2, '.', ',') . '</td>
						</tr>';
						$valor_bruto = $datos['valor_bruto'];
					}
					if ($datos['estado'] == 'borrador') {
						$estado = 'BORRADOR';
					} else if ($datos['estado'] == 'anulado') {
						$estado = 'ANULADO';
					} else {
						$estado = '';
					}
					$pagina .= '	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center;" width="33.5%" rowspan="3"><br><h1><strong>' . $estado . '</strong></h1></td>
							<td width="52.5%"><strong>Valor del Material/Material Value :</strong></td>
							<td style="text-align: right;" width="14%"><strong>' . number_format($valor_bruto, 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							
							<td>Gastos de Transporte FOB/FOB Transport Costs</td>
							<td style="text-align: right;">' . number_format($datos['transporte_fob'], 2, '.', ',') . '</td>
						</tr>
						<tr>
							
							<td>Gatos de Seguro FOB/FOB Insurance</td>
							<td style="text-align: right;">' . number_format($datos['seguros_fob'], 2, '.', ',') . '</td>
						</tr>';
						if ($datos['otros_fob'] > 0) {
							$pagina .= '<tr>
								<td>&nbsp;</td>
								<td>Otros FOB/Other FOB Costs</td>
								<td style="text-align: right;">' . number_format($datos['otros_fob'], 2, '.', ',') . '</td>
							</tr>';
						}
						$pagina .= '<tr>
							<td>&nbsp;</td>
							<td><strong>Total FOB Frontera</strong></td>
							<td style="text-align: right;"><strong>' . number_format($valor_bruto + $datos['transporte_fob'] + $datos['seguros_fob'] + $datos['otros_fob'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Transporte Internacional/International Transport</td>
							<td style="text-align: right;">' . number_format($datos['transporte_cif'], 2, '.', ',') . '</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Seguros Internacional/International Insurance</td>
							<td style="text-align: right;">' . number_format($datos['seguros_cif'], 2, '.', ',') . '</td>
						</tr>';
						if ($datos['otros_cif'] > 0) {
							$pagina .= '<tr>
								<td>&nbsp;</td>
								<td>Gastos Portuarios/Port Charges</td>
								<td style="text-align: right;">' . number_format($datos['otros_cif'], 2, '.', ',') . '</td>
							</tr>';
						}
						$pagina .= '<tr>
							<td>&nbsp;</td>
							<td style="border: thin solid black;"><strong>TOTAL ' . $datos['moneda_venta'] . '</strong></td>
							<td style="text-align: right; border: thin solid black;"><strong>' . number_format($datos['total_venta'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="border: thin solid black;"><strong>TOTAL ' . $datos['moneda_sucursal'] . '</strong></td>
							<td style="text-align: right; border: thin solid black;"><strong>' . number_format($datos['total_venta_msuc'], 2, '.', ',') . '</strong></td>
						</tr>
					</tbody>
				</table>
				<p>Son : <strong>' . $datos['total_venta_literal'] . ' ' . $datos['desc_moneda_venta'] . '</strong></p>
				<p>Son : <strong>' . $datos['total_venta_msuc_literal'] . ' ' . $datos['desc_moneda_sucursal'] . '</strong></p>
				<table width="605">
					<tbody>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Control : <strong>' . $datos['codigo_control'] . '</strong></td>
							<td style="text-align:right; width="50%">&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong>' . $datos['fecha_limite_emision'] . '</strong></td>
						</tr>
					</tbody>
				</table>
				<p style="text-align: center;"><strong>' . $datos['glosa_impuestos'] . '<br>
				' . $datos['glosa_empresa'] . '</strong></p>';
				
				$html .= $pagina;
				if ($datos['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					//copia archivo adicionales
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}
				$html .= '<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
				if ($datos['estado'] == 'finalizado') {
					$html .= '
							setTimeout(function(){
								 self.print();							 
								}, 1000);
							
							setTimeout(function(){
								 self.close();							 
								}, 2000);
							
							';
				}
				$html .= '				
						</script>                                                                                   
                                </body>
                                </html>';
				
				
			} else if ($codigo_reporte == 'FACEXPORTMIN') {
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_carta.css" type="text/css"  charset="utf-8">
					  
					</head>
					<center><body>';
				
				if ($datos['estado'] == 'borrador') {
					$pagina = '	<div id="watermark-borrador"></div>';
				} else if ($datos['estado'] == 'anulado') {
					$pagina = '	<div id="watermark-anulado"></div>';
				} else {
					$pagina = '';
				}
				$pagina .= '
				
				<table style="height: 130px;" width="750">
					<tbody>
						<tr>
							<td>
								<table style="height: 130px;" width="230">
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib/imagenes/logos/logo_reporte.png" alt="logo" width="75" height="65" /></td>
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['nombre_sucursal'] . '</strong></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td>
								<table style="height: 130px;" width="340">
									<tbody>
										<tr>
											<td style="text-align:center;">
												<h2 style="text-align: center;">FACTURA COMERCIAL DE EXPORTACION</h2>
												<h4 style="text-align: center;">EXPORT COMMERCIAL INVOICE</h4>
												<h4 style="text-align: center;">Sin Derecho a Credito Fiscal</h3>
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td style="text-align: left;" width="180">
								<table style="height: 74px;" width="180">
									<tbody>
										<tr>
											<td style="text-align: left;"><strong>NIT:</strong></td>
											<td style="text-align: left;">' . $datos['nit_entidad'] . '</td>
										</tr>varchar
										<tr>
											<td><strong>FACTURA:</strong></td>
											<td>' . $datos['numero_factura'] . '</td>
										</tr>
										<tr>
											<td><strong>AUTORIZACION:</strong></td>
											<td>' . $datos['autorizacion'] . '</td>
										</tr>
										<tr>
											<td style="text-align: center;" colspan="2">
												<h3>&nbsp;<strong>ORIGINAL</strong></h3>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="border: thin solid black;background-color: #e1e1d0;" width="750">
				<tbody>
						<tr>
							<td style="font-size: 8pt;">' . $datos['direccion_sucursal'] . '</td>
						</tr>
				</tbody>
				</table>
				<br>
				
				<table style="border: thin solid black;" width="750">
					<tbody>
						<tr>
							<td>Lugar y Fecha/Place and Date</td>
							<td><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . ';</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Nombre/Name</td>
							<td><strong>' . $datos['cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Direccion del Importador/Address</td>
							<td><strong>' . nl2br($datos['direccion_cliente']) . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>NIT</td>
							<td><strong>' . $datos['nit_cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Puerto Destino/Incoterm</td>
							<td><strong>' . $datos['observaciones'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Moneda de la Transaccion comercial/Currency</td>
							<td><strong>' . $datos['desc_moneda_venta'] . '</strong></td>
							<td>Tipo de Cambio<strong>:' . $datos['tipo_cambio_venta'] . '</strong></td>
						</tr>
						<tr>
							<td>Cantidad y Descripcion de Bultos</td>
							<td><strong>' . $datos['descripcion_bulto'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="border-top: thin solid black;" colspan="3">
								<h3 style="text-align: center;">DESCRIPCION&nbsp;</h3>
							</td>
						</tr>						
					</tbody>
				</table>
				<table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="750">
					<tbody>
						<tr>
							<td width="35%">'; 
							$i = 1;
							$pagina.= $datos['detalle_descripcion'][0]['nombre'];
							while ($datos['detalle_descripcion'][$i]['columna'] == 1){
								$pagina.= '<br>' . $datos['detalle_descripcion'][$i]['nombre'];
								$i++;
							}
							$pagina .= '</td>
							<td style="text-align: right;" width="15%">'; 
							$i = 1;
							$pagina.= $datos['detalle_descripcion'][0]['valor'].'&nbsp;';
							while ($datos['detalle_descripcion'][$i]['columna'] == 1){
								$pagina.= '<br>' . $datos['detalle_descripcion'][$i]['valor'].'&nbsp;';
								$i++;
							}
							$pagina .= '</td>
							<td  width="35%">'; 
							$j = $i;
							$pagina.= $datos['detalle_descripcion'][$i]['nombre'];
							$i++;
							while ($datos['detalle_descripcion'][$i]['columna'] == 2){
								$pagina.= '<br>' . $datos['detalle_descripcion'][$i]['nombre'];
								$i++;
							}
							$pagina .= '</td>
							<td style="text-align: right;" width="15%">'; 
							$pagina.= $datos['detalle_descripcion'][$j]['valor'].'&nbsp;';
							$i = $j + 1;
							
							while ($datos['detalle_descripcion'][$i]['columna'] == 2){
								$pagina.= '<br>' . $datos['detalle_descripcion'][$i]['valor'].'&nbsp;';
								$i++;
							}
							$pagina .= '</td>
						</tr>
					</tbody>
					</table>
					<table style="border:thin solid black ;border-collapse: collapse; height: 33px;" width="750">
					<tbody>
						<tr>							
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>Partida</strong></td>
							<td style="text-align: center; border: thin solid black;" width="30%"><strong>Mineral</strong></td>
							
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>Ley Mineral</strong></td>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>Peso Fino[Kg]</strong></td>
							<td style="text-align: center; border: thin solid black;" width="16%"><strong>Peso Fino</strong></td>
							<td style="text-align: center; border: thin solid black;" width="12%"><strong>Cotizacion Mineral</strong></td>
																					
							<td style="text-align: center; border: thin solid black;" width="12%"><strong>Subtotal</strong></td>
						</tr>';
					$valor_bruto = 0;
					
					foreach ($datos['detalle'] as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						$pagina .= '<tr>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['nandina'] . '</td>
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] . '</td>							
							<td style="text-align: right;border-bottom: thin solid black;">' . $item_detalle['ley'] . '</td>
							<td style="text-align: right;border-bottom: thin solid black;">' . $item_detalle['kg_fino'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 6, '.', ',') . ' ' . $item_detalle['unidad_medida'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 6, '.', ',') . '</td>							
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
					}
					if ($datos['estado'] == 'borrador') {
						$estado = 'BORRADOR';
					} else if ($datos['estado'] == 'anulado') {
						$estado = 'ANULADO';
					} else {
						$estado = '';
					}
					$pagina .= '	
					</tbody> 
				</table>
				<table style="border-collapse: collapse;" width="750">
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td width="52.5%"><strong>VALOR BRUTO/Gross Value :</strong></td>
							<td style="text-align: right;" width="14%"><strong>' . number_format($valor_bruto, 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Gastos de Transporte FOB/FOB Transport Costs</td>
							<td style="text-align: right;">' . number_format($datos['transporte_fob'] + $datos['seguros_fob'], 2, '.', ',') . '</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Gatos de Seguro FOB/FOB Insurance</td>
							<td style="text-align: right;">' . number_format($datos['seguros_fob'] + $datos['seguros_fob'], 2, '.', ',') . '</td>
						</tr>';
						if ($datos['otros_fob'] > 0) {
							$pagina .= '<tr>
								<td>&nbsp;</td>
								<td>Otros FOB/Other FOB Costs</td>
								<td style="text-align: right;">' . number_format($datos['otros_fob'], 2, '.', ',') . '</td>
							</tr>';
						}
						$pagina .= '<tr>
							<td style="text-align: center;" width="33.5%" rowspan="3"><br><h1><strong>' . $estado . '</strong></h1></td>
							<td><strong>Total FOB Frontera</strong></td>
							<td style="text-align: right;"><strong>' . number_format($valor_bruto + $datos['transporte_fob'] + $datos['seguros_fob'] + $datos['otros_fob'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							
							<td>Transporte Internacional/International Transport</td>
							<td style="text-align: right;">' . number_format($datos['transporte_cif'], 2, '.', ',') . '</td>
						</tr>
						<tr>
							
							<td>Seguros Internacional/International Insurance</td>
							<td style="text-align: right;">' . number_format($datos['seguros_cif'], 2, '.', ',') . '</td>
						</tr>';
						if ($datos['otros_cif'] > 0) {
							$pagina .= '<tr>
								<td>&nbsp;</td>
								<td>Gastos Portuarios/Port Charges</td>
								<td style="text-align: right;">' . number_format($datos['otros_cif'], 2, '.', ',') . '</td>
							</tr>';
						}
						$pagina .= '<tr>
							<td>&nbsp;</td>
							<td style="border: thin solid black;"><strong>TOTAL ' . $datos['moneda_venta'] . '</strong></td>
							<td style="text-align: right; border: thin solid black;"><strong>' . number_format($datos['total_venta'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="border: thin solid black;"><strong>TOTAL ' . $datos['moneda_sucursal'] . '</strong></td>
							<td style="text-align: right; border: thin solid black;"><strong>' . number_format($datos['total_venta_msuc'], 2, '.', '') . '</strong></td>
						</tr>
					</tbody>
				</table>
				<p>Son : <strong>' . $datos['total_venta_literal'] . ' ' . $datos['desc_moneda_venta'] . '</strong></p>
				<p>Son : <strong>' . $datos['total_venta_msuc_literal'] . ' ' . $datos['desc_moneda_sucursal'] . '</strong></p>
				<table width="605">
					<tbody>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Control : <strong>' . $datos['codigo_control'] . '</strong></td>
							<td style="text-align:right; width="50%">&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong>' . $datos['fecha_limite_emision'] . '</strong></td>
						</tr>
					</tbody>
				</table>				
				<p style="text-align: center;"><strong>' . $datos['glosa_impuestos'] . '<br>
				' . $datos['glosa_empresa'] . '</strong></p>';
				
				$html .= $pagina;
				if ($datos['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}
				$html .= '<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
				if ($datos['estado'] == 'finalizado') {
					$html .= '
							setTimeout(function(){
								 self.print();							 
								}, 1000);
							
							setTimeout(function(){
								 self.close();							 
								}, 2000);
							
							';
				}
				$html .= '				
						</script>                                                                                   
                                </body>
                                </html>';
			} else if ($codigo_reporte == 'FACMEDIACAR' || $codigo_reporte == 'FACMEDIACARVINTO') {
				
				////////FACTURA USADA 	PARA EL ERP
				
				
				$cadena_qr = 	$datos['nit_entidad'] . '|' . 
						$datos['numero_factura'] . '|' . 
						$datos['autorizacion'] . '|' . 
						$datos['fecha_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['codigo_control'] . '|' . 
						$datos['nit_cliente'] . '|0.00|0.00|0.00|0.00';
						
				$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,H');
				
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_media_carta.css" type="text/css"  charset="utf-8">
					
					<style type="text/css"> 
					
					@page 
						    {
						        size:  auto;   /* auto es el valor inicial */
						       
							   margin: 4mm;
							   margin-top: 5mm;  /* afecta el margen en la configuración de impresión */
						        margin-right: 5mm;
								margin-left: 5mm;  
								
						    	@bottom {
								content: "Page " counter(page) " of " counter(pages)
							    }
						       
						    }
							
					
					</style> 
					</head>';
				
					
				$html .= '<center><body>';
				
				if ($datos['estado'] == 'borrador') {
					$pagina = '	<div id="watermark-borrador"></div>';
				} else if ($datos['estado'] == 'anulado') {
					$pagina = '	<div id="watermark-anulado"></div>';
				} else {
					$pagina = '';
				}	
				
				$pagina .= ' 
			
				<table style="height: 130px;" width="605">  
					<tbody>
						<tr>
							<td>
								<table style="height: 130px;" width="230">   
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib' . ($codigo_reporte == 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_3.jpg') .'" alt="logo" width="200" height="200" /></td>
												
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['nombre_sucursal'] . '</strong><br />' . $datos['direccion_sucursal'] . '<br />' . $datos['telefono_sucursal'] . '<br />' . $datos['lugar_sucursal'] . '</td>
										</tr>
									</tbody> 
								</table>
							</td>
							<td style="text-align: left;" width="172">
								<table style="height: 74px;" width="172">
									<tbody>
										<tr>
											<td style="text-align: left;"><strong>NIT:</strong></td>
											<td style="text-align: left;">' . $datos['nit_entidad'] . '</td>
										</tr>
										<tr>
											<td><strong>FACTURA:</strong></td>
											<td>' . $datos['numero_factura'] . '</td>
										</tr>
										<tr>
											<td><strong>AUTORIZACION:</strong></td>
											<td>' . $datos['autorizacion'] . '</td>
										</tr>
										<tr>
											<td style="text-align: center;" colspan="2">
												<h3>&nbsp;<strong>ORIGINAL</strong></h3></br>
												'.$datos['actividades'].'	
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<h2 style="text-align: center;">FACTURA</h2>				
				<table style="border: thin solid black;" width="645">
					<tbody>
						<tr>
							<td>Lugar y Fecha</td>
							<td><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Señor(es):</td>
							<td><strong>' . $datos['cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>						
						<tr>
							<td>NIT/CI</td>
							<td><strong>' . $datos['nit_cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td><strong>' . $datos['observaciones']	 . '</strong></td>
							<td>&nbsp;</td>
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
						</tr>';
					$valor_bruto = 0;
					$i = 1;
					foreach ($datos['detalle'] as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						$pagina .= '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
					if ($datos['estado'] == 'borrador') {
						$estado = 'BORRADOR';
					} else if ($datos['estado'] == 'anulado') {
						$estado = 'ANULADO';
					} else {
						$estado = '';
					}
					$pagina .= '	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					<tbody>
						<tr>
							<td width="53.5%" style="text-align:center;"><br><h1><strong>' . $estado . '</strong></h1></td>
							<td width="32.5%"><strong>TOTAL  ' . $datos['moneda_venta'] . ':</strong></td>
							<td style="text-align: right;" width="14%"><strong>' . number_format($datos['total_venta_msuc'], 2, '.', ',') . '</strong></td>
						</tr>						
					</tbody>
				</table>
				<p>Son : <strong>' . $datos['total_venta_msuc_literal'] . ' ' . $datos['desc_moneda_sucursal'] . '</strong></p>
				<table width="605">
					<tbody>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Control : <strong>' . $datos['codigo_control'] . '</strong></br>
							&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong>' . $datos['fecha_limite_emision'] . '</strong>
							</td>
							<td width="50%"><div align="center">
								    '.$barcodeobj->getBarcodeSVGcode(2, 2, 'black').'
								</div></td>
						</tr>
					</tbody>
				</table>
				
				<p style="text-align: center;"><strong>' . $datos['glosa_impuestos'] . '</strong></p>
				<p style="text-align: center;"><strong>' . $datos['glosa_empresa'] . '</strong></p>';
				
				
				$html .= $pagina;
				if ($datos['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}

				$html .= '
				<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
						if ($datos['estado'] == 'finalizado') {
							$html .= '
									setTimeout(function(){
										 self.print();							 
										}, 1000);
									
									setTimeout(function(){
										 self.close();							 
										}, 2000);';
						}
						$html .= '					
						</script>                                                                                   
                                </body>
                                </html>';
			} else if ($codigo_reporte == 'RECECOFARMA') {
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_medio_oficio.css" type="text/css"  charset="utf-8">
					  
					</head>';
				
					
				$html .= '<body>
				<center>';				
				

				$pagina .= '
				<table width="645">
					<tbody>
						<tr>
							<td>
								<table style="border-spacing: 0;" width="200">
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib/images/logo.png" alt="logo" width="120" height="60" /></td>
										
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['direccion_sucursal'] . '<br> Telf. '. $datos['telefono_sucursal'] . '</strong></td>

										</tr>
									</tbody>
								</table>
							</td>
							<td>
								<table width="265">
									<tbody>
										<tr>
											<td style="text-align:center;">
												<h2 style="text-align: center;">PROFORMA</h2>												
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td style="text-align: left;" width="180">
								<h4 style="text-align: center;">' . $datos['nro_venta'] . '</h4>
							</td>
						</tr>
					</tbody>
				</table>				
				<table style="border: thin solid black;" width="645">
					<tbody>
						<tr>
							<td width="25%">Lugar y Fecha</td>
							<td width="40%"><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="25%">Nombre Cliente</td>
							<td width="40%"> <strong>' . $datos['cliente'] . '</strong></td>
							<td>Med/Ven : <strong>' . $datos['medico_vendedor'] . '</strong></td>
						</tr>
						<tr>
							<td width="25%">Telefono Cliente</td>
							<td width="40%"><strong>' . $datos['telefono_cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="25%">Fecha y Hora de Entrega</td>
							<td width="40%"><strong>' . $datos['fecha_hora_entrega'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>												
						<tr>
							<td style="border-top: thin solid black;" colspan="3">
								<h3 style="text-align: center;">DETALLE&nbsp;</h3>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="border-collapse: collapse; height: 33px;" width="645">
					<tbody>
						<tr>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>No</strong></td>
							<td style="text-align: center; border: thin solid black;" width="44%"><strong>DESCRIPCION</strong></td>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>CANTIDAD</strong></td>
							<td style="text-align: center; border: thin solid black;" width="10%"><strong>UNIDAD DE MEDIDA</strong></td>
							<td style="text-align: center; border: thin solid black;" width="12%"><strong>PRECIO UNITARIO</strong></td>
							<td style="text-align: center; border: thin solid black;" width="14%"><strong>TOTAL</strong></td>
						</tr>';
                    $descripcion = '';
                    $numero = 1;
					
					foreach ($datos['detalle'] as $item_detalle) {

                        if ($item_detalle['descripcion'] == '') {
                            $pagina .= '<tr>
                                <td style="border: thin solid black;"><b>' . $numero . '</b></td>
                                <td style="border: thin solid black;">' . $item_detalle['concepto'].'</td>
                                <td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['cantidad'], 6, '.', ',') . '</td>
                                <td style="border: thin solid black;">' . $item_detalle['unidad_concepto'] . '</td>
                                <td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['precio_unitario'], 6, '.', ',') . '</td>
                                <td style="text-align: right; border: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
                            </tr>';
                            $numero ++;
                            $descripcion = '';

                        } else {
                            if ($item_detalle['descripcion'] == $descripcion) {
                                $pagina .= '<tr>
                                    <td style="border-left: thin solid black;"></td>
                                    <td style="border-left: thin solid black;">&nbsp;&nbsp;' . $item_detalle['concepto'].'</td>
                                    <td style="text-align: right; border-left: thin solid black;">' . number_format($item_detalle['cantidad'], 6, '.', ',') . '</td>
                                    <td style="border-left: thin solid black;">' . $item_detalle['unidad_concepto'] . '</td>
                                    <td style="text-align: right; border-left: thin solid black;"></td>
                                    <td style="text-align: right; border-left: thin solid black;border-right: thin solid black;"></td>
                                </tr>';

                            } else {
                                $pagina .= '<tr>
                                    <td style="border-top: thin solid black;border-left: thin solid black;"><b>' . $numero . '</b></td>
                                    <td style="border-top: thin solid black;border-left: thin solid black;">&nbsp;&nbsp;' . $item_detalle['concepto'].'</td>
                                    <td style="text-align: right; border-top: thin solid black;border-left: thin solid black;">' . number_format($item_detalle['cantidad'], 6, '.', ',') . '</td>
                                    <td style="border-top: thin solid black;border-left: thin solid black;">' . $item_detalle['unidad_concepto'] . '</td>
                                    <td style="text-align: right; border-top: thin solid black;border-left: thin solid black;">' . number_format($item_detalle['precio_grupo'], 2, '.', ',') . '</td>
                                    <td style="text-align: right; border-top: thin solid black;border-left: thin solid black;border-right: thin solid black;">' . number_format($item_detalle['precio_grupo'], 2, '.', ',') . '</td>
                                </tr>';

                                $numero ++;
                                $descripcion = $item_detalle['descripcion'];

                            }
                        }
						

					}
					
					
					$pagina .= '	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					<tbody>
						<tr>
							<td width="60%" style="border-top: thin solid black;">&nbsp;</td>
							<td width="20%" style="border-top: thin solid black;"> <strong>TOTAL</strong></td>
							<td width="20%" style="text-align: right; border-top: thin solid black;"><strong>' . number_format($datos['total_venta'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><strong>A CUENTA</strong></td>
							<td style="text-align: right;"><strong>' . number_format($datos['a_cuenta'], 2, '.', ',') . '</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><strong>SALDO</strong></td>
							<td style="text-align: right;"><strong>' . number_format($datos['total_venta'] - $datos['a_cuenta'], 2, '.', ',') . '</strong></td>
						</tr>
					</tbody>
				</table>';
				
								
				$html .= $pagina;
				
				$html .= '<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
				if ($datos['estado'] == 'elaboracion') {
					$html .= '
							setTimeout(function(){
								 self.print();							 
								}, 1000);
							
							setTimeout(function(){
								 self.close();							 
								}, 2000);
							
							';
				}
				$html .= '				
						</script>                                                                                   
                                </body>
                                </html>';
				
				
			}

else if ($codigo_reporte == 'FACMEDIACAR' || $codigo_reporte == 'FACMEDIACARVINTO') {
				
				$cadena_qr = 	$datos['nit_entidad'] . '|' . 
						$datos['numero_factura'] . '|' . 
						$datos['autorizacion'] . '|' . 
						$datos['fecha_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['total_venta'] . '|' . 
						$datos['codigo_control'] . '|' . 
						$datos['nit_cliente'] . '|0.00|0.00|0.00|0.00';
						
				$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,H');
				
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_media_carta.css" type="text/css"  charset="utf-8">
					  
					</head>';
				
					
				$html .= '<center><body>';
				
				if ($datos['estado'] == 'borrador') {
					$pagina = '	<div id="watermark-borrador"></div>';
				} else if ($datos['estado'] == 'anulado') {
					$pagina = '	<div id="watermark-anulado"></div>';
				} else {
					$pagina = '';
				}	
				
				$pagina .= ' 
			
				<table style="height: 130px;" width="605">  
					<tbody>
						<tr>
							<td>
								<table style="height: 130px;" width="230">   
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib' . ($codigo_reporte == 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_reporte.png') .'" alt="logo" width="60" height="60" /></td>
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['nombre_sucursal'] . '</strong><br />' . $datos['direccion_sucursal'] . '<br />' . $datos['telefono_sucursal'] . '<br />' . $datos['lugar_sucursal'] . '</td>
										</tr>
									</tbody> 
								</table>
							</td>
							<td style="text-align: left;" width="172">
								<table style="height: 74px;" width="172">
									<tbody>
										<tr>
											<td style="text-align: left;"><strong>NIT:</strong></td>
											<td style="text-align: left;">' . $datos['nit_entidad'] . '</td>
										</tr>
										<tr>
											<td><strong>FACTURA:</strong></td>
											<td>' . $datos['numero_factura'] . '</td>
										</tr>
										<tr>
											<td><strong>AUTORIZACION:</strong></td>
											<td>' . $datos['autorizacion'] . '</td>
										</tr>
										<tr>
											<td style="text-align: center;" colspan="2">
												<h3>&nbsp;<strong>ORIGINAL</strong></h3></br>
												'.$datos['actividades'].'	
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<h2 style="text-align: center;">FACTURA</h2>				
				<table style="border: thin solid black;" width="645">
					<tbody>
						<tr>
							<td>Lugar y Fecha</td>
							<td><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Señor(es):</td>
							<td><strong>' . $datos['cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>						
						<tr>
							<td>NIT/CI</td>
							<td><strong>' . $datos['nit_cliente'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td><strong>' . $datos['observaciones']	 . '</strong></td>
							<td>&nbsp;</td>
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
						</tr>';
					$valor_bruto = 0;
					$i = 1;
					foreach ($datos['detalle'] as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						$pagina .= '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
					if ($datos['estado'] == 'borrador') {
						$estado = 'BORRADOR';
					} else if ($datos['estado'] == 'anulado') {
						$estado = 'ANULADO';
					} else {
						$estado = '';
					}
					$pagina .= '	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					<tbody>
						<tr>
							<td width="53.5%" style="text-align:center;"><br><h1><strong>' . $estado . '</strong></h1></td>
							<td width="32.5%"><strong>TOTAL  ' . $datos['moneda_venta'] . ':</strong></td>
							<td style="text-align: right;" width="14%"><strong>' . number_format($datos['total_venta_msuc'], 2, '.', ',') . '</strong></td>
						</tr>						
					</tbody>
				</table>
				<p>Son : <strong>' . $datos['total_venta_msuc_literal'] . ' ' . $datos['desc_moneda_sucursal'] . '</strong></p>
				<table width="605">
					<tbody>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Control : <strong>' . $datos['codigo_control'] . '</strong></br>
							&nbsp;Fecha L&iacute;mite de Emisi&oacute;n : <strong>' . $datos['fecha_limite_emision'] . '</strong>
							</td>
							<td width="50%"><div align="center">
								    '.$barcodeobj->getBarcodeSVGcode(2, 2, 'black').'
								</div></td>
						</tr>
					</tbody>
				</table>
				
				<p style="text-align: center;"><strong>' . $datos['glosa_impuestos'] . '</strong></p>
				<p style="text-align: center;"><strong>' . $datos['glosa_empresa'] . '</strong></p>';
				
				
				$html .= $pagina;
				if ($datos['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}

				$html .= '
				<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
						if ($datos['estado'] == 'finalizado') {
							$html .= '
									setTimeout(function(){
										 self.print();							 
										}, 1000);
									
									setTimeout(function(){
										 self.close();							 
										}, 2000);';
						}
						$html .= '					
						</script>                                                                                   
                                </body>
                                </html>';
			}

			else if ($codigo_reporte == 'PEDIDOMEDIACAR') {
				
				$cadena_qr = 	$datos['codigo_cliente'];
						
				$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,H');
				
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print_media_carta.css" type="text/css"  charset="utf-8">
					  
					</head>';
				
					
				$html .= '<center><body>';
				
				if ($datos['estado'] == 'borrador') {
					$pagina = '	<div id="watermark-borrador"></div>';
				} else if ($datos['estado'] == 'anulado') {
					$pagina = '	<div id="watermark-anulado"></div>';
				} else {
					$pagina = '';
				}	
				
				$pagina .= ' 
			
				<table style="height: 130px;" width="605">  
					<tbody>
						<tr>
							<td>
								<table style="height: 130px;" width="230">   
									<tbody>
										<tr>
											<td style="text-align:center;"><img src="../../../lib' . ($codigo_reporte == 'FACMEDIACARVINTO'?'/imagenes/logos/logo_vinto.png':'/imagenes/logos/logo_reporte.png') .'" alt="logo" width="60" height="60" /></td>
										</tr>
										<tr>
											<td style="text-align: center;"><strong>' . $datos['nombre_sucursal'] . '</strong><br />' . $datos['direccion_sucursal'] . '<br />' . $datos['telefono_sucursal'] . '<br />' . $datos['lugar_sucursal'] . '</td>
										</tr>
									</tbody> 
								</table>
							</td>
							<td style="text-align: left;" width="172">
								<table style="height: 74px;" width="172">
									<tbody>
										<tr>
											<td style="text-align: left;"><strong>NIT:</strong></td>
											<td style="text-align: left;">' . $datos['nit_entidad'] . '</td>
										</tr>
										<tr>
											<td><strong>Nº:</strong></td>
											<td>' . $datos['nro_venta'] . '</td>
										</tr>
										
										<tr>
											<td style="text-align: center;" colspan="2">
												<h3>&nbsp;<strong>ORIGINAL</strong></h3></br>
												'.$datos['actividades'].'	
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<h2 style="text-align: center;">ORDEN DE COMPRA</h2>				
				<table style="border: thin solid black;" width="645">
					<tbody>
						<tr>
							<td>Lugar y Fecha</td>
							<td><strong>' . $datos['departamento_sucursal'] . ', ' . $datos['fecha_literal'] . '</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Origen:</td>
							<td><strong>' . $datos['cliente'].'  ('.strtoupper ($datos['lugar_cliente']).')</strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Destino:</td>
							<td><strong>' . $datos['cliente_destino'] .' (' . strtoupper ($datos['lugar_destino']) .')</strong></td>
							<td>&nbsp;</td>
						</tr>							
						
						<tr>
							<td>Observaciones</td>
							<td><strong>' . $datos['observaciones']	 . '</strong></td>
							<td>&nbsp;</td>
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
						</tr>';
					$valor_bruto = 0;
					$i = 1;
					foreach ($datos['detalle'] as $item_detalle) {
						$valor_bruto += $item_detalle['precio_total']; 
						$pagina .= '<tr>
							<td style="text-align: right; border-bottom: thin solid black;">'.$i.'</td>							
							<td style="border-bottom: thin solid black;">' . $item_detalle['concepto'] .' '.$item_detalle['descripcion'] . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['cantidad'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_unitario'], 2, '.', ',') . '</td>
							<td style="text-align: right; border-bottom: thin solid black;">' . number_format($item_detalle['precio_total'], 2, '.', ',') . '</td>
						</tr>';
						$i++;
					}
					if ($datos['estado'] == 'borrador') {
						$estado = 'BORRADOR';
					} else if ($datos['estado'] == 'anulado') {
						$estado = 'ANULADO';
					} else {
						$estado = '';
					}
					$pagina .= '	
					</tbody>
				</table>
				<table style="border-collapse: collapse;" width="645">
					<tbody>
						<tr>
							<td width="53.5%" style="text-align:center;"><br><h1><strong>' . $estado . '</strong></h1></td>
							<td width="32.5%"><strong>TOTAL  ' . $datos['moneda_venta'] . ':</strong></td>
							<td style="text-align: right;" width="14%"><strong>' . number_format($datos['total_venta_msuc'], 2, '.', ',') . '</strong></td>
						</tr>						
					</tbody>
				</table>
				<p>Son : <strong>' . $datos['total_venta_msuc_literal'] . ' ' . $datos['desc_moneda_sucursal'] . '</strong></p>
				<table width="605">
					<tbody>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Seguimiento : <strong>' . $datos['nro_tramite'] . '</strong>
							</td>
							    <td rowspan="2" width="50%">
							      <div align="center">
								    '.$barcodeobj->getBarcodeSVGcode(2, 2, 'black').'
								  </div>
							</td>
						</tr>
						<tr>
							<td style="text-align:left;" width="50%">&nbsp;C&oacute;digo de Cliente : <strong>' . $datos['codigo_cliente'] . '</strong>
						    </td>
							
						</tr>
						
						
					</tbody>
				</table>
				
				<p style="text-align: center;"><strong>' . $datos['glosa_impuestos'] . '</strong></p>
				<p style="text-align: center;"><strong>' . $datos['glosa_empresa'] . '</strong></p>';
				
				
				$html .= $pagina;
				if ($datos['estado'] == 'finalizado') {
					$pagina = str_replace('<h3>&nbsp;<strong>ORIGINAL</strong></h3>', '<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA CONTABILIDAD</strong></h3>', '<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
					
					$pagina = str_replace('<h3>&nbsp;<strong>COPIA TESORERIA</strong></h3>', '<h3>&nbsp;<strong>COPIA ARCHIVO</strong></h3>', $pagina);
					$html .= '<p style="page-break-after:always;"></p>' . $pagina;
				}

				$html .= '
				<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
						</script>
						
						<script type="text/javascript"> 
						';
						if ($datos['estado'] == 'finalizado') {
							$html .= '
									setTimeout(function(){
										 self.print();							 
										}, 1000);
									
									setTimeout(function(){
										 self.close();							 
										}, 2000);';
						}
						$html .= '					
						</script>                                                                                   
                                </body>
                                </html>';
			}


			
			return $html;
	}
}
?>
