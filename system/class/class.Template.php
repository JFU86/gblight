<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Template
{	
	/**
	 * Läd ein Template in die aktuelle Seite
	 * @param string $tpl
	 * @return boolean
	 */
	public function getTemplate($tpl)
	{		
		global $config;
		
		$tpl = trim($tpl);
		
		if (file_exists($config["include_path"] . "/" . $tpl . ".inc.php")) {
			include_once($config["include_path"] . "/" . $tpl . ".inc.php");
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Gibt eine Datei aus
	 * @param string $filename
	 * @param array $vars
	 * @return string
	 */
	public function output($filename, $vars = array())
	{		
		global $config;
		
		$open = file_get_contents(realpath($filename));
		$lang = new Language();
		$vars["{templateName}"] = $config["templateName"];	// Globale Textmarke für alle Templates
      
		// Ersetzen aller Textmarken durch die in $vars zugewiesenen Werte
		foreach ($vars as $key => $value) {
   			$open = str_replace($key, $value, $open);
		}		
		
		// Sprach-Textmarken erkennen und behandeln
		$anzahl = (int) preg_match_all("/\{@(.*)\}/sU", $open, $array);
		// Übersetzen der Textmarke
		foreach ($array[1] as $key => $value) {
			$open = str_replace("{@".$value."}", $lang->translate($value), $open);
		}
		
		// Sprach-Textmarken zur sicheren Verwendung in JavaScript erkennen und behandeln (%@)
		$anzahl = (int) preg_match_all("/\{%@(.*)\}/sU", $open, $array);
		// Übersetzen der Textmarke
		foreach ($array[1] as $key => $value) {
			$open = str_replace("{%@".$value."}", addslashes($lang->translate($value)), $open);
		}

		// Infobox-Textmarken erkennen und behandeln
		$anzahl = (int) preg_match_all("/\{!(.*)\}/sU", $open, $array);
		// Übersetzen der Textmarke
		foreach ($array[1] as $key => $value) {
			$open = str_replace("{!".$value."}", $this->infobox($lang->translate($value), false, ""), $open);
		}
		
		// Errorbox-Textmarken erkennen und behandeln
		$anzahl = (int) preg_match_all("/\{#(.*)\}/sU", $open, $array);
		// Übersetzen der Textmarke
		foreach ($array[1] as $key => $value) {
			$open = str_replace("{#".$value."}", $this->errorbox($lang->translate($value), false, ""), $open);
		}
						
		return $open;
	}
	
	
	/**
	 * Gibt den Pfad einer Templatedatei aus
	 * @param string $file
	 * @param string $subdir Optional: falls die Dateien in einem Unterordner von '/templates' liegen
	 * @return string
	 */
	public function getFilePath($file, $subdir = "")
	{		
		global $config;
		
		$file = explode("/", str_replace("\\", "/", $file));
		$file = str_replace(".inc.php", ".html", $file[count($file)-1]);
		
		if ($subdir != "") {
			return $config["template_path"] . "/html/{$subdir}/{$file}";
		}
		
		return $config["template_path"] . "/html/{$file}";
	}
	
	
	/**
	 * Zeigt eine Infobox mit dem angegebenen Text an
	 * @param string $text
	 * @param Boolean $break (Default = false) Führe einen Zeilenumbruch durch
	 * @return string
	 */
	public function infobox($text, $break = false, $basepath = "../")
	{
		global $config;
		
		$output = "";
		
		if ($break) {
			$output .= "<br />";
		}
		
		$output .= "<div class=\"info\">\r\n";
		$output .= "\t<img src=\"{$basepath}templates/{$config["templateName"]}/img/b_tipp.png\" alt=\"item\" /> {$text}\r\n".
				  "\t</div>\r\n\r\n";
		
		if ($break) {
			$output .= "<br />";
		}
				
		return $output;		
	}
	
	
	/**
	 * Zeigt eine Errorbox mit dem angegebenen Text an
	 * @param string $text
	 * @param Boolean $break (Default = false) Führe einen Zeilenumbruch durch
	 * @return string
	 */
	public function errorbox($text, $break = false, $basepath = "../")
	{		
		global $config;
		
		$output = "";
		
		if ($break) {
			$output .= "<br />";
		}
		
		$output .= "<div class=\"errorbox\">\r\n";
		$output .= "\t<img src=\"{$basepath}templates/{$config["templateName"]}/img/s_error.png\" alt=\"error\" width=\"16\" height=\"16\" /> {$text}\r\n".
		  		  "\t</div>\r\n\r\n";
		
		if ($break) {
			$output .= "<br />";
		}
		
		return $output;
	}
	
	
	/**
	 * Überprüfen, ob es sich beim aufrufenden Computer um ein mobiles Gerät handelt
	 * @return Boolean
	 */
	public function isMobileDevice()
	{
		$agents = array(
			'Windows CE', 'Pocket', 'Mobile',
			'Portable', 'Smartphone', 'SDA',
			'PDA', 'Handheld', 'Symbian',
			'WAP', 'Palm', 'Avantgo',
			'cHTML', 'BlackBerry', 'Opera Mini',
			'Nokia', 'Fennec', 'Tablet', 'GT-P',
		);

		// Prüfen der Browserkennung
		for ($i = 0; $i < count($agents); $i++) {
			if (isset($_SERVER["HTTP_USER_AGENT"]) && strpos($_SERVER["HTTP_USER_AGENT"], $agents[$i]) !== false)
				return true;
  		}
  		
		return false;
	}
}
?>