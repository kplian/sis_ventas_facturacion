<?php
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RFacturaRecibo
{
	function generarHtml ($codigo_reporte,$datos) {
			
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
					    </tr>
					    <tr>
						    <td colspan="4">Son: '.$datos['total_venta_literal']. ' '.$datos['moneda_sucursal'].' </td>						    
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
			}
			
			return $html;
	}
}
?>