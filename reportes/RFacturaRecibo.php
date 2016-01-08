<?php
class RFacturaRecibo
{
	function generarHtml ($codigo_reporte,$datos) {
			
		if ($codigo_reporte == 'RECPAPELTERM') {	
			setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				
			
			$html.='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>sis_ventas_facturacion</title>
						<meta name="author" content="kplian">
						    
					
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print.css" type="text/css" media="print" charset="utf-8">
					  
					</head>
					<body >
					<center>
					<p text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;' . $datos['nombre_entidad'] . '</br>
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
					    ' . $datos['departamento_sucursal'] . ', '.strftime("%d de %B de %Y", strtotime($datos['fecha_venta'])).'<br/>
					    Senor(es): '.trim($datos['cliente']).'					    
					</p>
					<hr/>
					
					
					<table>				
					
					<thead>
					
						<tr><th>Ca</th><th>Concepto</th><th>PU</th><th>SubTotal</th></tr>
					</thead>
					<tbody>';					
					
					foreach ($datos['detalle'] as $item_detalle) {
					    $html .= '<tr>
							<td>'.number_format($item_detalle['cantidad'], 2, '.', '').'</td>
							<td>'.str_replace( "/", " / ", $item_detalle['concepto'] ).'</td>
							<td>'.number_format($item_detalle['precio_unitario'], 2, '.', '').'</td>
							<td align="center">'.number_format($item_detalle['precio_total'], 2, '.', '').'</td>
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
									
								
					
					<p>GRACIAS POR SU PREFERENCIA !
					    <br/> www.boa.bo</p>
					    
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
							 window.close();
							}, 2000);
						</script> 
											
				</body>
				</html>';
			}
			return $html;
	}
}
?>