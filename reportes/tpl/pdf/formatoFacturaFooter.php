<?php
require_once(dirname(__FILE__).'/../../../../lib/tcpdf/tcpdf_barcodes_2d.php');


if ($this->codigo_reporte == 'FACMEDIACAR' || $this->codigo_reporte == 'FACMEDIACARVINTO') {
		

				
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
?>

              <footer>
 
				<table   width="645px" >
						<tr>
							<td height="25px" >  </td>
							<td >   </td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="400px" height="25px">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="245px" rowspan="2" >
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="130px" height="130px">';
							
										 //echo  $this->cadena_qr;
						  		?>
						  </td>
						
						
						</tr>
						<tr>
							<td	height="100px" >
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
              </footer>

	
<?php }
elseif  ($this->codigo_reporte == 'NOTAFACMEDIACAR' || $this->codigo_reporte == 'FACMEDIACARVINTO')  {
	
?>

              <footer>
              	
				
				<table width="645px" >
						<tr>
							<td width="400px" height="25px">Monto Efectivo del Credito o debito (13% del importe Total Devuelto)</td>
							<td width="250px"><?php echo number_format($this->cabecera['total_venta_msuc']*0.13, 2, '.', ',') ;echo ' '; echo $this->cabecera['moneda_venta'] ; ?></td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="400px"  height="25px">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="250px" rowspan="2">
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="130" height="130">';
							
										 //echo  $this->cadena_qr;
						  		?>
						    </td>
						
						
						</tr>
						<tr>
							<td  height="100px">
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
              	
              </footer>

<?php
}

?>

