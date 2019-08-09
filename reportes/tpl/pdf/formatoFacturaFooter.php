<?php
/*
 * 	ISSUE		FECHA		AUTHOR 		DESCRIPCION
 * 	#5			09/08/2019	EGS			Nuevo formato de factura 
 */
require_once(dirname(__FILE__).'/../../../../lib/tcpdf/tcpdf_barcodes_2d.php');


if ($this->codigo_reporte == 'FACMEDIACAR' || $this->codigo_reporte == 'FACMEDIACARVINTO') {
		

				
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
?>

              <footer>
 
				<table   width="645px" >
						<tr>
							<td height="25px" width="445px">  </td>
							<td width="200px">   </td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="445px" height="25px">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="100px" rowspan="2" >
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="140px" height="140px">';
							
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
							<td width="445px" height="25px">Monto Efectivo del Credito o debito (13% del importe Total Devuelto)</td>
							<td width="200px"><?php echo number_format($this->cabecera['total_venta_msuc']*0.13, 2, '.', ',') ;echo ' '; echo $this->cabecera['moneda_venta'] ; ?></td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="445px"  height="25px">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="100px" rowspan="2" align="center">
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="140" height="140">';
							
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

if ($this->codigo_reporte == 'RECIBOETR') {//#5
			
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
?>

              <footer>
 
				<table   width="660px" >
						<tr>
							<td height="25px" width="445px">  </td>
							<td width="200px">   </td>
						</tr>
					
						<tr>
							<td style="text-align:left;" width="445px" height="25px">
							&nbsp;C&oacute;digo de Control : <strong><?php echo $this->cabecera['codigo_control'] ; ?></strong>
							
							</td>
							<td width="100px" rowspan="2" >
							<?php
						   				 echo  '<img src = "'.$this->img_qr.'" width="140px" height="140px">';
							
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

	
<?php } ?>

