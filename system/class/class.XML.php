<?php
/*
	XML Class
	from Common Framework
	by Scripthosting.net

	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
*/

class XML
{	
	/**
	 * Gibt ein Object mit allen XML-Elementen zurück
	 * @param string $xml XML-Content oder Dateipfad
	 * @param Boolean $isXmlFile Handelt es sich um einen Dateipfad? (true:false)
	 * @return array
	 */
	public function fetchObject($xml, $isXmlFile = true)
	{		
		// Wenn die Datenverschlüsselung aktiviert ist, wird der XML-string zunächst von Crypto entschlüsselt !!
		if ($isXmlFile == true) {			
			$xml = file_get_contents($xml);
		}

		try {
			return new SimpleXMLElement($xml);
		} catch (Exception $e) {
			$logfile = new Logfile();		
			$xml = ($xml == "") ? "(Empty string)" : $xml;
			$logfile->addLog("xml.log","[RECEIVE]: \"<b>".$xml."</b>\" erzeugte einen Fehler: ". $e->__toString());
			return array();
		}
	}
	
	
	/**
	 * Wandelt eine XHTML-Ausgabe in ein gültiges XML-Objekt um
	 * @param $string
	 * @return string
	 */
	public function getValidAjaxRequest($string)
	{
		foreach ($this->xml_translation_table() as $key => $value) {
			$string = str_replace($key, $value, $string);
		}

		$output = "";
		$output .= '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";

		$output .= '<output>' . "\r\n";
		$output .= $string . "\r\n";
		$output .= '</output>';
		
		return $output;
	}

	
	/**
	 * Übersetzt Entitäten von einem Format in ein anderes
	 * @param string $string
	 * @param string $from ("plain","html","xml")
	 * @param string $to ("plain","html","xml")
	 * @return string
	 */
	public function translateEntities($string, $from = "plain", $to = "xml")
	{
		// Wenn Klartext umgewandelt werden soll:
		if (strtolower($from) == "plain") {			
			if ($to == "html" || $to == "xml") {
				$data = $this->html_translation_table();
				foreach ($data as $key => $value) {
					$string = str_replace($key, $value, $string);
				}
			}			
			if ($to == "xml") {
				$data = $this->xml_translation_table();	
				foreach ($data as $key => $value) {
					$string = str_replace($key, $value, $string);
				}
			}
		}		
		// Wenn HTML-Entities umgewandelt werden sollen:
		elseif (strtolower($from) == "html") {			
			if ($to == "plain") {
				$data = $this->html_translation_table();
				foreach ($data as $key => $value) {
					$string = str_replace($value, $key, $string);
				}
			}			
			if ($to == "xml") {
				$data = $this->xml_translation_table();	
				foreach ($data as $key => $value) {
					$string = str_replace($key, $value, $string);
				}
			}			
		}		
		// Wenn XML-Entities umgewandelt werden sollen:
		elseif (strtolower($from) == "xml") {			
			if ($to == "html" || $to == "plain") {
				$data = $this->xml_translation_table();	
				foreach ($data as $key => $value) {
					$string = str_replace($value, $key, $string);
				}
			}			
			if ($to == "plain") {
				$data = $this->html_translation_table();
				foreach ($data as $key => $value) {
					$string = str_replace($value, $key, $string);
				}
			}			
		}
		
		return $string;
	}
	
	
	/**
	 * Wandle UTF8-Zeichen der Form %uXXXX in das entsprechende Zeichen um
	 * @param string $string
	 * @return string
	 */
	public function xmlChr($string)
	{
		// UTF-8 Zeichen umwandeln
		preg_match_all("/\%u[A-F0-9]+/s", $string, $data);
	
		foreach ($data[0] as $key => $value) {
			$string = str_replace($value, mb_convert_encoding(pack('n', 
					  hexdec(substr($value,2))), 'UTF-8', 'UTF-16BE'), $string);
		}
		return $string;
	}
	
	
	/**
	 * Wandelt alle HTML special chars in XML-Entitäten um
	 * @param string $string
	 * @return string
	 */
	public function xmlSpecialChars($string)
	{
		$string = htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
		$string = $this->translateEntities($string, "html", "xml");
		
		return $string;
	}
	
	/**
	 * Wandelt einen string in einen gültigen HTML Output um.
	 * @param string $string
	 * @return string
	 */
	public function htmlOutput($string)
	{
		$string = $this->xmlSpecialChars($string);
		$string = $this->replaceBBCode($string);
		return trim($string);
	}
	
	
	/**
	 * Wandelt BBCode in das HTML-Pendant um
	 * @param string $string
	 * @return string
	 */
	private function replaceBBCode($string)
	{
		// BB-Code [b] und [i] ins das HTML-Pendant umwandeln
		$bbcodes = array(
				"[b]"	=>	"<b>",
				"[/b]"	=>	"</b>",
				"[i]"	=>	"<i>",
				"[/i]"	=>	"</i>",
				"[...]" =>	"~~~",
		);
	
		foreach ($bbcodes as $key => $value) {
			$string = str_replace($key, $value, $string);
		}
	
		return $string;
	}
	
	
	/**
	 * Gibt ein array mit HTML-Entities zurück
	 * @return array
	 */
	private function html_translation_table()
	{		
		$array = array(
			"&" => "&amp;",
	    	"ÿ" => "&yuml;",
	    	"þ" => "&thorn;",
	    	"ý" => "&yacute;",
			"ü" => "&uuml;",
	    	"û" => "&ucirc;",
	    	"ú" => "&uacute;",
	    	"ù" => "&ugrave;",
	    	"ø" => "&oslash;",	    
		    "÷" => "&divide;",
		    "ö" => "&ouml;",
		    "õ" => "&otilde;",
		    "ô" => "&ocirc;",
		    "ó" => "&oacute;",
		    "ò" => "&ograve;",	
		    "ñ" => "&ntilde;",
		    "ð" => "&eth;",
		    "ï" => "&iuml;",
		    "î" => "&icirc;",
		    "í" => "&iacute;",
		    "ì" => "&igrave;",	
		    "ë" => "&euml;",
		    "ê" => "&ecirc;",
		    "é" => "&eacute;",
		    "è" => "&egrave;",
		    "ç" => "&ccedil;",
		    "æ" => "&aelig;",	
		    "å" => "&aring;",
		    "ä" => "&auml;",
		    "ã" => "&atilde;",
		    "â" => "&acirc;",
		    "á" => "&aacute;",
		    "à" => "&agrave;",	
		    "ß" => "&szlig;",
		    "Þ" => "&THORN;",
		    "Ý" => "&Yacute;",
		    "Ü" => "&Uuml;",
		    "Û" => "&Ucirc;",
		    "Ú" => "&Uacute;",	
		    "Ù" => "&Ugrave;",
		    "Ø" => "&Oslash;",
		    "×" => "&times;",
		    "Ö" => "&Ouml;",
		    "Õ" => "&Otilde;",
		    "Ô" => "&Ocirc;",	
		    "Ó" => "&Oacute;",
		    "Ò" => "&Ograve;",
		    "Ñ" => "&Ntilde;",
		    "Ð" => "&ETH;",
		    "Ï" => "&Iuml;",
		    "Î" => "&Icirc;",	
		    "Í" => "&Iacute;",
		    "Ì" => "&Igrave;",
		    "Ë" => "&Euml;",
		    "Ê" => "&Ecirc;",
		    "É" => "&Eacute;",
		    "È" => "&Egrave;",	
		    "Ç" => "&Ccedil;",
		    "Æ" => "&AElig;",
		    "Å" => "&Aring;",
		    "Ä" => "&Auml;",
		    "Ã" => "&Atilde;",
		    "Â" => "&Acirc;",	
		    "Á" => "&Aacute;",
		    "À" => "&Agrave;",
		    "¿" => "&iquest;",
		    "¾" => "&frac34;",
		    "½" => "&frac12;",
		    "¼" => "&frac14;",	
		    "»" => "&raquo;",
		    "º" => "&ordm;",
		    "¹" => "&sup1;",
		    "¸" => "&cedil;",
		    "·" => "&middot;",
		    "¶" => "&para;",	
		    "µ" => "&micro;",
		    "´" => "&acute;",
		    "³" => "&sup3;",
		    "²" => "&sup2;",
		    "±" => "&plusmn;",
		    "°" => "&deg;",	
		    "¯" => "&macr;",
		    "®" => "&reg;",
		    "­" => "&shy;",
		    "¬" => "&not;",
		    "«" => "&laquo;",
		    "ª" => "&ordf;",	
		    "©" => "&copy;",
		    "¨" => "&uml;",
		    "§" => "&sect;",
		    "¦" => "&brvbar;",
		    "¥" => "&yen;",
		    "¤" => "&curren;",	
		    "£" => "&pound;",
		    "¢" => "&cent;",
		    "¡" => "&iexcl;",
			"€" => "&euro;",
		);
	
		return $array;
	}
	
	
	/**
	 * Gibt ein array mit XML-Entities zurück
	 * @return array
	 */
	private function xml_translation_table()
	{		
		$xml = array('&#34;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;',
				'&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;',
				'&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;',
				'&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;',
				'&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;',
				'&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;',
				'&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;',
				'&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;',
				'&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;',
				'&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;',
				'&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;',
				'&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;',
				'&#251;','&#252;','&#253;','&#254;','&#255;','&#8364;');
		
	    $html = array('&quot;','&amp;','&lt;','&gt;','&#160;','&iexcl;','&cent;',
	    		'&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;',
	    		'&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;',
	    		'&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;',
	    		'&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;',
	    		'&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;',
	    		'&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;',
	    		'&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;',
	    		'&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;',
	    		'&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;',
	    		'&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;',
	    		'&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;',
	    		'&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;',
	    		'&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;','&euro;');
	    
	    $data = array();
	    
	    for ($i = 0; $i < count($xml); $i++) {
	    	$data[$html[$i]] = $xml[$i];
	    }
	    
	    return $data;
	}
}

if (!class_exists('InvalidXMLException', false)) {
	class InvalidXMLException extends Exception
	{
		public function __construct($message, $code = 0)
		{
			parent::__construct($message, $code);
		}
		public function __toString()
		{
			return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
		}
	}
}
?>