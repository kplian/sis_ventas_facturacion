<?php

// clase base con propiedades y métodos miembro
//autor: Ariel Lopez Gutierrez
//fecha: 23/01/2019 17:00

class SiatClassWs {

   var $wsurl;
   var $parametros=array();
   var $client;
   //parametros
   var $codigoAmbiente;
   var $codigoSistema;
   var $codigoModalidad;
   var $nit;
   var $cuis;
   var $sucursal;


   function SiatClassWs($wsurl,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal)
   {
       $this->wsurl = $wsurl;
       $this->parametros = compact("codigoAmbiente","codigoSistema","codigoModalidad","nit","cuis","sucursal");
     
	   
   }
    
   
   /* function SiatClassWs($wsurl,$codigoSistema,$codigoAmbiente,$codigoModalidad,$nit,$cuis,$cufd,$codigoDocumentoFiscal,$codigoDocumentoSector,$sucursal,$codigoPuntoVenta,
                                  $pinAnulacion,$numeroDocumentoFiscal,$cuf,$email,$celular,$codigoMotivo,$codigoRecepcion)
   {
       $this->wsurl = $wsurl;
       $this->parametros = compact("codigoSistema","codigoAmbiente","codigoModalidad","nit","cuis","cufd","codigoDocumentoFiscal","codigoDocumentoSector","codigoSucursal","codigoPuntoVenta",
                                  "pinAnulacion","numeroDocumentoFiscal","cuf","email","celular","codigoMotivo","codigoRecepcion");
     
	   
   }*/
	//establece la conexion con el ws
   function Conexion()
   {
   	
	$parametros=$this->parametros; //parametros de la llamada
	$client = new SoapClient($this->wsurl);
    return $client;
   }
   
   //convierte el objeto devuelto por el ws en array
   function ConvertObjectToArray($data) {
         if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__METHOD__, $data);
    }
    else {
        return $data;
    }
    }

} // fin de la clase Madre

// ampliar la clase De web services para operaciones
class WsFacturacionOperaciones extends SiatClassWs {

   var $clientFO;
   var $result;
   var $wsurlFO;
   var $parametrosFO;
   function WsFacturacionOperaciones($wsurlFO,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal)
   {
        $this->SiatClassWs($wsurlFO,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal);
		$this->clientFO = $this->Conexion();
   }

   function estableceConexion($wsurlFO,$parametrosFO)
   {
       $result = $client->Conexion();
   }


   function verificarConexionwsOp()
   {
       $result = $this->clientFO->verificarConexion();
	   return $result;
   }
  
   function solicitudCufdOp()
   {
       $result = $this->clientFO->solicitudCufd($this->parametros);
	   return $result;
   }
   

  
} // fin de la clase de web services para operaciones

class WsFacturacionSincroniza extends SiatClassWs {

   var $clientSINC;
   var $result;
   var $wsurlSINC;
   var $parametrosSINC;
   function WsFacturacionSincroniza($wsurlSINC,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal)
   {
        $this->SiatClassWs($wsurlSINC,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal);
		$this->clientSINC = $this->Conexion();
   }

   function estableceConexion($wsurlSINC,$parametrosSINC)
   {
       $result = $client->Conexion();
   }


   function verificarConexionwsSINC()
   {
 	   $result = $this->clientSINC->verificarConexion();
      
   }
   function ParametricaTipoMoneda()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoMoneda($this->parametros);
	   return $result;
	   //var_dump($result);die();
   }

  function ParametricaEventosSignificativos()
   {
       $result = $this->clientSINC->sincronizarParametricaEventosSignificativos($this->parametros);
	   return $result;
	   //var_dump($result);die();
   }
   function ParametricaMotivoAnulacion()
   {
       $result = $this->clientSINC->sincronizarParametricaMotivoAnulacion($this->parametros);
	   return $result;
	  
   }
   function ParametricaPaisOrigen()
   {
       $result = $this->clientSINC->sincronizarParametricaPaisOrigen($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoDepartamento()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoDepartamento($this->parametros);
	   return $result;
	  
   }
   function ParametricaMensajesServicios()
   {
       $result = $this->clientSINC->sincronizarListaMensajesServicios($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoAmbiente()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoAmbiente($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoEmision()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoEmision($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoModalidad()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoModalidad($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoDocumentoIdentidad()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoDocumentoIdentidad($this->parametros);
	   return $result;
	  
   }
   function ParametricaTipoDocumentoFiscal()
   {
       $result = $this->clientSINC->sincronizarParametricaTipoDocumentoFiscal($this->parametros);
	   return $result;
	  
   }
   function ParametricaMetodoPago()
   {
       $result = $this->clientSINC->sincronizarParametricaMetodoPago($this->parametros);
	   return $result;
	  
   }
   } // fin de la clase de web services para operaciones

   class WsFacturacionEstandar extends SiatClassWs {

   var $clientFac;
   var $result;
   var $wsurlFac;
   var $parametrosFac;
   function WsFacturacionEstandar($wsurlFac,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal)
   {
        $this->SiatClassWs($wsurlFac,$codigoAmbiente,$codigoSistema,$codigoModalidad,$nit,$cuis,$sucursal);
		$this->clientFac = $this->Conexion();
   }

   function estableceConexion($wsurlFac,$parametrosFO)
   {
       $result = $client->Conexion();
   }


   function verificarConexionwsFac()
   {
       $result = $this->clientFac->verificarComunicacion();
	   return $result;
   }
  
   function anulacionFacturaElectronicaEstandar($cufd,$codigoDocumentoFiscal,$codigoDocumentoSector,$codigoPuntoVenta,$pinAnulacion,$numeroDocumentoFiscal,$cuf,$email,$celular,$codigoMotivo,$codigoRecepcion,$codigoSucursal)
   {
	   $this->parametrosFac = compact("cufd","codigoDocumentoFiscal","codigoDocumentoSector","codigoPuntoVenta","pinAnulacion","numeroDocumentoFiscal","cuf","email","celular","codigoMotivo","codigoRecepcion","codigoSucursal");
	   $result = $this->clientFac->anulacionFacturaElectronicaEstandar(array_merge($this->parametros, $this->parametrosFac));
	   return $result;
   }
   

  
} // fin de la clase de web services para facturacion estandar

?> 