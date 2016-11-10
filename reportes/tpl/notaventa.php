<?php  
$cabecera = $dataSource->getParameter('cabecera');
$detalle = $dataSource->getParameter('detalle');
?>
<html>
	 <head>
	 	<style>
	 		tr.border_bottom td {
			  border-left:0pt solid black;
			  border-top: none !important;
			}
			
			tr.border_top td {
			  border-left:0pt solid black;
			  border-bottom: none !important;
			}
	 		
	 	</style>
	 	
	 </head>
<body>
	
	<table width="100%" style="width: 100%; text-align: center;" cellspacing="0" cellpadding="1" border="1">
	<tbody>
	<tr>
		
		<td style="width: 23%; color: #444444;" rowspan="2"><img  style="width: 150px;" src="<?php echo dirname(__FILE__) . "/" . $_SESSION['_DIR_LOGO']?>" alt="Logo"></td>		
		<td style="width: 54%; color: #444444;" rowspan="2"><h1>Nota de Venta </h1><?php  echo $cabecera[0]['nombre_sucursal'];?>, <?php  echo $cabecera[0]['direccion']; ?>, Tel <?php  echo $cabecera[0]['telefono']; ?></td>
		<td style="width: 23%; height: 50%; color: #444444;"><b>N°:</b> <?php  echo $cabecera[0]['nro_tramite']; ?></td>
	</tr>
	<tr>
	     <td style="width: 23%; height: 50%; color: #444444;"><b>Fecha:</b> <?php  echo $newDate = date("d-m-Y", strtotime($cabecera[0]['fecha_reg']));  ?></td>
	</tr>
	
</tbody></table>
<br>
<br>

<table width="100%" cellspacing="1" cellpadding="1" border="0">
	
		<tr>
			<td><b>Cliente:</b>&nbsp;&nbsp;&nbsp;&nbsp;  <?php  echo $cabecera[0]['nombre_completo']; ?></td>
		</tr>
		
		<tr>
			<td><b>Fecha Entrega:</b>&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $newDate = date("d-m-Y", strtotime($cabecera[0]['fecha_estimada_entrega']));  ?></td>
		</tr>
		
		
</table>
<br>
<br>

<table width="100%"    cellpadding="5px"  border="1">
	<tbody>
		<tr>
			<td width="10%" align="center"><b>Tipo</b></td>
			<td width="55%" align="center"><b>Detalle</b></td>
			<td width="10%" align="center"><b>Cant</b></td>
			<td width="12.5%" align="center"><b>P/U</b></td>
			<td width="12.5%" align="center"><b>P/T</b></td>
	     </tr>
	     
<?php 
	       
		   $sw_primera_form = 1;
		   for($i = 0;$i < count($detalle); $i++ ){
		  
		   	
			   $val = $detalle[$i];
			   $desc = "";
			   
			   //identifica tipo de item
			   if($val['tipo'] == 'formula'){
					   	  $desc = $val['nombre_formula'];
						  $tipo = 'Form';
						  
						   $valtem = $detalle[$i+1];
						 
						  //definir si se abre cierra o continua
						   
						   //si es la primera formula abre
						   if($sw_primera_form == 1){
							 	$sw = 0; //abrimos
							    $sw_primera_form = 0;
						        if(isset($valtem)){
						            if($valtem['tipo'] != 'formula' ||  $valtem['id_venta_detalle'] !=  $val['id_venta_detalle']){
							   	   		$sw = 4; //abrir y cerrar
							   	   	    $sw_primera_form = 1;
							   	   	} 
								}else{
									$sw = 4; //abrir y cerrar
								}
						   
						   }
						   else{
							   	if(isset($valtem)){
							   	   //si el siguiente no es formula , o tiene un id de formula diferente cierra
							   	   if($valtem['tipo'] != 'formula' ||  $valtem['id_venta_detalle'] !=  $val['id_venta_detalle']){
							   	   	   $sw = 2; //cerramos
							   	   	   $sw_primera_form = 1;
							   	   	   
							   	   }else{
							   	   	  $sw = 1;
									  $sw_primera_form = 0;
							   	   }
							   	   
							   }
							   else{
							   	
								 //si no hay un registro siguiente tenemos que cerrar
							   	 $sw = 2;
								 $sw_primera_form = 1;
							   }
						   }
				   
				}
			   if($val['tipo'] == 'producto_terminado'){
			   	 $desc = $val['item_nombre'];
				 $tipo = 'Prod';
				  $sw_primera_form = 1;
				 
			   }
			   if($val['tipo'] == 'servicio'){
			   	 $desc = $val['nombre_producto'];
				 $tipo = 'Serv';
				  $sw_primera_form = 1;
				
			   }
			   
			   if($val['tipo'] != 'formula'){
			?>
	              <tr class="border_bottom">
					<td width="10%" align="left"><?php echo  $tipo;?></td>
					<td width="55%" align="left"><?php echo  $desc;?></td>
					<td width="10%" align="center"><?php echo  $val['cantidad'];?></td>
					<td width="12.5%" align="center"><?php echo  $val['precio'];?></td>
					<td width="12.5%" align="right"><?php echo  $val['precio_total'];?></td>
			     </tr class="border_bottom">
	     
	      <?php }
	            else { //si es formula
	            	
					 //Abrimos 
	            	 if($sw == 0 || $sw == 4){  ?>
	            	 	<tr class="border_bottom">
					    <td width="10%" align="left"><?php echo  $tipo;?></td>
						<td width="55%" align="center"><table width="100%" border="0"> 
						  	 <tr class="border_bottom">
						  	  	<td width="100%"  colspan="2" align="left"><b><?php echo  $desc;?></b></td>
						  	  </tr>
						  	  
						  	  <tr>
						  	  	<td width="80%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['item_nombre_df']?>;</td>
						  	  	<td width="20%" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['cantidad_df']?>;</td>
						  	  </tr>
	           <?php }
					 
					 //continuamos
					 if($sw == 1){ ?>
					 	
	            	 	     <tr>
						  	  	<td width="80%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['item_nombre_df']?>;</td>
						  	  	<td width="20%" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['cantidad_df']?>;</td>
						  	  </tr>
						
	          <?php }
					 
					 //cerramos 
					 if($sw == 2 || $sw == 4){ ?>
					 	
					 	      <tr>
						  	  	<td width="80%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['item_nombre_df']?>;</td>
						  	  	<td width="20%" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $val['cantidad_df']?>;</td>
						  	  </tr>
	            	 	  </table></td>
	            	      <td width="10%" align="center"><?php echo  $val['cantidad'];?></td>
					      <td width="12.5%" align="center"><?php echo  $val['precio'];?></td>
					      <td width="12.5%" align="right"><?php echo  $val['precio_total'];?></td>
			          </tr>	
	            	 	
	            	 	  
	          <?php } 
					 
	     }
	      
	} ?>
	

	
	
    </tbody>
</table><br><br><br>
<table width="100%"   rules="cols" border="0">
	<tbody>
	<tr>
		
		<td width="74%"  rowspan="3" align="left" ><b>SON: <?php echo  $cabecera[0]['total_string'];?></b> </td>
		<td width="12.5%" align="right" ><b>Total</b> </td>
		<td width="12.5%" align="right"><b><?php echo  $cabecera[0]['total_venta'];?></b></td>	
	</tr>
	<tr>
		<td width="12.5%" align="right" ><b>A cuenta </b></td>
		<td width="12.5%" align="right"><b><?php echo  $cabecera[0]['a_cuenta'];?></b></td>	
	</tr>
	<tr>
		<td width="12.5%" align="right" ><b>Saldo </b></td>
		<td width="12.5%" align="right"><b><?php echo  ($cabecera[0]['total_venta'] - $cabecera[0]['a_cuenta']);?></b></td>	
	</tr>
	</tbody>
</table>

<br><br>
NOTA: Si el producto no se recoge en un tiempo de 5 después de estipulada la fecha de entrega, el producto  se descarta y no se dará lugar a reclamos

</body></html>