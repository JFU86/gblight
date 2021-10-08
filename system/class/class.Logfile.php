<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Logfile
{	
	/**
	 * Einen neuen Eintrag in einer Logdatei schreiben
	 * @param $logfile
	 * @param $text
	 * @return void
	 */
	public function addLog($logfile, $text)
	{		
		global $config;
		
		if (is_writable($config["system_path"] . "/log/")) {		
			// Logeintrag schreiben
			$open = $this->lopen($config["system_path"] . "/log/" . $logfile, "a");
			$write = $this->lwrite($open, $text);
			$close = $this->lclose($open);
		} else {
			exit("Logfile konnte nicht geschrieben werden!");
		}
	}
	
	
	/**
	 * Eine Logdatei leeren
	 * @param $logfile
	 * @return void
	 */
	protected function cleanLog($logfile)
	{		
		global $config;
		
		// Logdatei öffnen und leeren, falls sie existiert!
		if (file_exists($config["system_path"] . "/log/ ". $logfile . ".log")) {
			$open = $this->lopen($logfile, "w");
			$close = $this->lclose($open);
		}
	}
	
	
	
	
	#######################
	### PRIVATE METHODS ###
	#######################
	
	
	/**
	 * Logdatei öffnen:	Wenn die Datei noch nicht existiert, dann wird sie angelegt!
	 * @param $logfile
	 * @param $mode
	 * @return fopen handler
	 */
	private function lopen($logfile, $mode = "a")
	{
		$open = fopen($logfile . ".log", $mode);
		return $open;
	}
	
	
	/**
	 * Text in eine Logdatei schreiben
	 * @param $open
	 * @param $text
	 * @return void
	 */
	private function lwrite($open, $text)
	{
		// Aktuelles Datum erzeugen
		$time = date("d.m.Y H:i:s");
		$ip = $_SERVER["REMOTE_ADDR"];
		$host = gethostbyaddr($ip);
		$write = fwrite($open, "<{$time}>: {$text} (Client: {$ip}/{$host})\r\n");
	}
	
	
	/**
	 * Schließt eine offene Logdatei
	 * @param $open
	 * @return void
	 */
	private function lclose($open)
	{
		$close = fclose($open);
	}
}
?>