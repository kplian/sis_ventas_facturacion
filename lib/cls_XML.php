<?php
class convert_xml
{
		/**
     * Funcion que crea un archivo XML (/tmp/facturaEstandar.xml)validado a partir de 2 arreglos, cabecera y detalle
     * @param arrayCab Arreglo con los datos que se usarán para generar la cabecera de la factura
     * @param arrayDet Arreglo con los datos que se usarán para generar el detalle de la factura
	 *  asignacion de spacio de nombres para el XML (URI y prefijo), estatico.en el nodo cabecera
	 * Cargar el archivo XSD (/tmp/facturaEstandar.xsd)para la validacion
     */
	
	//function array_xml($arrayCab) {
	function array_xml($arrayCab,$arrayDet) {
		// var_dump($id);
		// exit;
		//iso2utf ::Método que codifica el string como UTF-8 si es que fue pasado como
        //ISO-8859-1 
        //regla que permitira que los datos ingresados sean codificados 
		$arrayCab = json_decode($arrayCab, true);		
		$arrayCab=array_merge(array_merge(array_merge($arrayCab)));
		//arrayDet
		
		$arrayDet = json_decode($arrayDet,true);		
		$arrayDet=array_merge(array_merge(array_merge($arrayDet)));
		
		//
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arrayCab));
		$listado = iterator_to_array($it,true);
		unset($listado['total']);
		
		//arrayDet
		$idet = new RecursiveIteratorIterator(new RecursiveArrayIterator($arrayDet));
		$listadod = iterator_to_array($idet,true);
		unset($listadod['total']);
		
		//
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><facturaEstandar></facturaEstandar>");
        $xml->addAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $xml->addAttribute("xsi:noNamespaceSchemaLocation", "facturaEstandar.xsd"); 
        $nod=$xml->addChild('cabecera'); 

        $nododet=$xml->addChild('detalle');
		
		foreach($listado as $key => $val) {		
			$nod->addChild((string)$key, $val);
		}
		//arrayDet
		foreach($listadod as $key => $val) {		
			$nododet->addChild((string)$key, $val);
		}
		
		$dom = new DOMDocument();       
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xml->asXML());
        $dom->formatOutput = TRUE;
        $dom->save("/tmp/facturaEstandar.xml");
		
		libxml_use_internal_errors(true);     
        $dom->load('/tmp/facturaEstandar.xml');
        
		
		//validacion XSD
        /*if (!$dom->schemaValidate('/tmp/facturaEstandar.xsd')) {
            print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
            $this->libxml_display_errors();
        }else{
            $a=1;        
        }*/
		return $xml->asXML();
	}
}





















































class convert_xml2
{
	/**
	* Funcion que crea un archivo XML (/tmp/facturaEstandar.xml)validado a partir de 2 arreglos, cabecera y detalle
	* @param arrayCab Arreglo con los datos que se usarán para generar la cabecera de la factura
	* @param arrayDet Arreglo con los datos que se usarán para generar el detalle de la factura
	*  asignacion de spacio de nombres para el XML (URI y prefijo), estatico.en el nodo cabecera
	* Cargar el archivo XSD (/tmp/facturaEstandar.xsd)para la validacion
	*/

	public $xmlString;
	public $xmlFisico;
	public $base64Fisico;
	public $gzipFisico;
	public $nombre;
	public $dirArchivoXSD;

	public function __construct($name, $dirTemp)
	{
		$this->dirArchivoXSD   = dirname(__FILE__).'/../archivos_xsd/facturaEstandar.xsd';
		$this->nombre       = $name; 
		$this->xmlString    = "";
		$this->xmlFisico    = $dirTemp.$this->nombre.".xml";		
		$this->base64Fisico = $dirTemp.$this->nombre.".txt";		
		$this->gzipFisico   = $dirTemp.$this->nombre.".txt.gz";		
	}

	public function array_xml($arrayCab,$arrayDet) {

		// var_dump($arrayCab);exit;
		$arrayCab = json_decode($arrayCab, true);		
		$arrayCab=array_merge(array_merge(array_merge($arrayCab)));
		//arrayDet
		
		$arrayDet = json_decode($arrayDet,true);		
		$arrayDet=array_merge(array_merge(array_merge($arrayDet)));
		
		//
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arrayCab));
		$listado = iterator_to_array($it,true);
		unset($listado['total']);
		
		//arrayDet
		$idet = new RecursiveIteratorIterator(new RecursiveArrayIterator($arrayDet));
		$listadod = iterator_to_array($idet,true);
		unset($listadod['total']);
		
		//
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><facturaElectronicaEstandar></facturaElectronicaEstandar>', 0, false, 'xmlns', true);
        // $xml->addAttribute("xsi:noNamespaceSchemaLocation", "facturaElectronicaEstandar.xsd", 'xsi'); 
        $xml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xsi:xsi:noNamespaceSchemaLocation', 'facturaElectronicaEstandar.xsd');
        // $xml->registerXPathNamespace('e', 'http://www.webex.com/schemas/2002/06/service/event');
        // $xml->addAttribute('http://www.webex.com/schemas/2002/06/service/event');
        // $xml->addAttribute("xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $nod=$xml->addChild('cabecera'); 

        $nododet=$xml->addChild('detalle');
		
		foreach($listado as $key => $val) {		
			$nod->addChild((string)$key, $val);
		}
		//arrayDet
		foreach($listadod as $key => $val) {		
			$nododet->addChild((string)$key, $val);
		}        

		 $this->xmlString = $xml->asXML();
	}

	/**
	 * Crea el archivo xml y la cadena xml en el objeto "convert_xml"
	 * @return void
	 */
	public function crearArchivoXML()
	{
		$xml = new SimpleXMLElement($this->xmlString);
		
		$dom = new DOMDocument();   
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xml->asXML());
        $dom->formatOutput = TRUE;
        $dom->save($this->xmlFisico);
		
		libxml_use_internal_errors(true);     
        $dom->load($this->xmlFisico);
	}

	/**
	 * Crea el archivo base64.txt
	 * @return void
	 */
	public function crearArchivoBase64()
	{
		$base64 = $this->convertirArchivoABase64();
		if (file_exists($this->base64Fisico))
		{
			unlink($this->base64Fisico);
			$archivo = fopen($this->base64Fisico, "a");
			fwrite($archivo, $base64);
			fclose($archivo);
		}
		else{
			$archivo = fopen($this->base64Fisico, "w");
			fwrite($archivo, $base64);
			fclose($archivo);
		}
	}

	/**
	 * Crea el archivo gzip.txt.gz
	 * @return void
	 */
	public function crearArchivoGZIP()
	{
		$data = implode("", file($this->base64Fisico));
		$gzdata = gzencode($data, 9);
		$fp = fopen($this->gzipFisico, "w");
		fwrite($fp, $gzdata);
		fclose($fp);
	}

	/**
	 * Agarra el archivo GZIP y lo convierte en base64
	 * @return string Devuelve la cadena del base del archivo GZIP
	 */
	public function convertirArchivoGZIPABase64()
	{
		$base64 = '';
		$path   = $this->gzipFisico;
		$type   = pathinfo($path, PATHINFO_EXTENSION);
		$data   = file_get_contents($path);
		$base64 = base64_encode($data);

		return $base64;
	}

	/**
	 * Compara el archivo fisico XML con el XSD para validar
	 * @return integer Devuelve 1 si es correcto y 0 si no pasa la validacion
	 */
	public function validarXmlConXSD()
	{
		$dom = new DOMDocument();       
		libxml_use_internal_errors(false);     

        $dom->load($this->xmlFisico);
		$res = false;
		if (!$dom->schemaValidate($this->dirArchivoXSD) && !is_file($this->dirArchivoXSD)) {
            print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
            // $this->libxml_display_errors();
        }else{
            $res = true;        
        }
		
        return $a;
	}

	/**
	 * Llena los demas datos faltantes para el xml
	 * @return void
	 */
	public function llenarSignature()
	{
		$xmlData = new SimpleXMLElement($this->xmlString);

		$xmlData->addChild("Signature","");
		$xmlData->Signature->addAttribute("xmlns","http://www.w3.org/2000/09/xmldsig#");

		/** SignedInfo **/
		$xmlData->Signature->addChild("SignedInfo","");
		
		$xmlData->Signature->SignedInfo->addChild("CanonicalizationMethod","");
		$xmlData->Signature->SignedInfo->CanonicalizationMethod->addAttribute("Algorithm","http://www.w3.org/TR/2001/REC-xml-c14n-20010315");

		$xmlData->Signature->SignedInfo->addChild("SignatureMethod","");
		$xmlData->Signature->SignedInfo->SignatureMethod->addAttribute("Algorithm","http://www.w3.org/2001/04/xmldsig-more#rsa-sha256");

		$xmlData->Signature->SignedInfo->addChild("Reference","");
		$xmlData->Signature->SignedInfo->Reference->addAttribute("URI","");

		$xmlData->Signature->SignedInfo->Reference->addChild("Transforms","");

		$xmlData->Signature->SignedInfo->Reference->Transforms->addChild("Transform","");
		$xmlData->Signature->SignedInfo->Reference->Transforms->Transform->addAttribute("Algorithm","http://www.w3.org/2000/09/xmldsig#enveloped-signature");
		// $xmlData->Signature->SignedInfo->Reference->Transforms->TransformaddAttribute("Algorithm","http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments");

		$xmlData->Signature->SignedInfo->Reference->addChild("DigestMethod","");
		$xmlData->Signature->SignedInfo->Reference->DigestMethod->addAttribute("Algorithm","http://www.w3.org/2001/04/xmlenc#sha256");

		$xmlData->Signature->SignedInfo->Reference->addChild("DigestValue","");
		
		/** SignatureValue **/
		$xmlData->Signature->addChild("SignatureValue","");

		/** KeyInfo **/
		$xmlData->Signature->addChild("KeyInfo","");
		$xmlData->Signature->KeyInfo->addChild("X509Data","");
		$xmlData->Signature->KeyInfo->X509Data->addChild("X509Certificate","");
		$xmlData->Signature->KeyInfo->X509Data->addChild("X509SubjectName","");
		$xmlData->Signature->KeyInfo->X509Data->addChild("X509IssuerSerial","");
		$xmlData->Signature->KeyInfo->X509Data->X509IssuerSerial->addChild("X509IssuerName","");
		$xmlData->Signature->KeyInfo->X509Data->X509IssuerSerial->addChild("X509SerialNumber","113429176675655193968");

		
		$this->xmlString = $xmlData->asXML();
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function getXmlString()
	{
		return $this->xmlString;
	}

	

	


	



	private function convertBase64($base64='')
	{
		$res = '';
		$base64 = trim($base64);

		if(isset($base64) && $base64!='')
		{
			$res = base64_encode($base64);
		} 
		else{
			$res = "ERROR";
		}		
		return $res;
	}

	private function convertirArchivoABase64()
	{
		$base64 = '';
		$path   = $this->xmlFisico;
		$type   = pathinfo($path, PATHINFO_EXTENSION);
		$data   = file_get_contents($path);
		$base64 = base64_encode($data);

		return $base64;
	}
}
?>
