<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Install
{	
	/**
	 * Schreibt die Configdatei
	 */
	public function writeConfig()
	{		
		global $config;
		
		$vars = array(
			"{language}"		=>	$_REQUEST["language"],
			"{databaseType}"	=>	$_REQUEST["database"],
			"{timeformat}"		=>	$_REQUEST["timeformat"],
		);		

		$tpl = new Template();		
		$newData = $tpl->output($config["system_path"] . "/temp/config.txt", $vars);
		$writeConfigFile = file_put_contents($config["system_path"] . "/config/config.inc.php", $newData);
		@unlink($config["system_path"] . "/temp/config.txt");
		@unlink($config["system_path"] . "/config/config.min.inc.php");
	}
	
	
	/**
	 * Installiert gblight auf der Datenbank
	 * @return void
	 */
	public function databaseInstall()
	{		
		global $config;
		
		// Alle alten Dateien löschen
		@unlink(realpath($config["system_path"] . "/sqlite/gblight.db"));
		@unlink(realpath($config["system_path"] . "/sqlite/user.log"));
				
		// Logdateien anlegen
		$put = file_put_contents($config["system_path"] . "/log/user.log", "");
		$chmod = chmod($config["system_path"] . "/log/user.log", 0777);
		
		// Gewählte Datenbank auswählen und anlegen
		if ($_REQUEST["database"] == "Sqlite") {
			// SQLite Datenbankdatei anlegen
			if (!file_exists($config["system_path"] . "/sqlite/gblight.db")) {
				$put = file_put_contents($config["system_path"] . "/sqlite/gblight.db", "");
				$chmod = chmod($config["system_path"] . "/sqlite/gblight.db", 0777);
			}			
			$sql = file_get_contents($config["system_path"] . "/temp/sqlite.sql");
			@unlink($config["system_path"] . "/temp/sqlite.sql");
			@unlink($config["system_path"] . "/temp/sqlite-update.sql");
		} else {
			exit("Unknown or incompatible database selected! Please go back and select a valid database!");
		}
		
		$dbCon = new Database();
		$part = explode("/* SPLIT */", trim($sql));
		
		foreach ($part as $value) {
			if ($value != "") {
				$query = $dbCon->query(trim($value));
			}
		}
		
		// Legt den Benutzer "admin" an
		$admin = new Admin();
		$admin->register($_REQUEST["username"], $_REQUEST["pass0"]);
		
		$this->createLanguageDB();
		@unlink($config["system_path"] . "/temp/UPDATE");
	}

	
	
	/**
	 * Aktualisiert die Gblight Datenbank
	 * @return boolean
	 */
	public function databaseUpdate()
	{	
		global $config;
		
		// Gewählte Datenbank auswählen und aktualisieren
		if (!isset($config["databaseType"]) || $config["databaseType"] == "Sqlite") {
			$updateFile = $config["system_path"] . "/temp/sqlite-update.sql";
			if (file_exists($updateFile))
				$sql = file_get_contents($updateFile);
			else
				return false;
		} else {
			exit("Unknown or incompatible database selected! Please go back and select a valid database!");
		}
	
		$dbCon = new Database();
		$part = explode("/* SPLIT */", trim($sql));
		
		foreach ($part as $value) {
			if ($value != "") {
				$query = $dbCon->query(trim($value));
			}
		}
		
		// Temporäre Dateien löschen
		@unlink($config["system_path"] . "/temp/sqlite.sql");
		@unlink($updateFile);
		@unlink($config["system_path"] . "/temp/config.txt");
		@unlink($config["system_path"] . "/temp/UPDATE");
		@unlink($config["system_path"] . "/config/config.min.inc.php");
		@unlink($config["basepath"] . "/admin/install.php");
		@unlink($config["basepath"] . "/admin/.install");
		
		// Alte Grafikdateien löschen
		@unlink($config["template_path"] . "/img/angryface.png");
		@unlink($config["template_path"] . "/img/blush.png");
		@unlink($config["template_path"] . "/img/gasp.png");
		@unlink($config["template_path"] . "/img/grin.png");
		@unlink($config["template_path"] . "/img/halo.png");
		@unlink($config["template_path"] . "/img/lipsaresealed.png");
		@unlink($config["template_path"] . "/img/smile.png");
		@unlink($config["template_path"] . "/img/undecided.png");
		@unlink($config["template_path"] . "/img/wink.png");
		@unlink($config["template_path"] . "/img/captcha1.png");
		@unlink($config["template_path"] . "/img/captcha2.png");
		@unlink($config["template_path"] . "/img/captcha3.png");
		@unlink($config["template_path"] . "/img/captcha4.png");
		@unlink($config["template_path"] . "/img/captcha5.png");
		@unlink($config["template_path"] . "/img/captcha6.png");
		@unlink($config["template_path"] . "/img/xfiles.ttf");
		
		// Language.db neu erstellen
		if (file_exists($config["system_path"] . "/temp/language.sql")) $this->createLanguageDB();
						
		return true;
	}
	
	
	/**
	 * Erstelle eine neue Sprachdatenbank
	 */
	public function createLanguageDB()
	{	
		global $config;
	
		@unlink($config["system_path"] . "/sqlite/language.db");
	
		if (!file_exists($config["system_path"] . "/sqlite/language.db")) {
			$put = file_put_contents($config["system_path"] . "/sqlite/language.db", "");
			$chmod = chmod($config["system_path"] . "/sqlite/language.db", 0777);		
	
			$sqlite = new Database($config["system_path"] . "/sqlite/language.db");
			$sql = file_get_contents($config["system_path"] . "/temp/language.sql");
			$part = explode("/* SPLIT */",trim($sql));
		
			foreach ($part as $value) {
				if ($value != "") {
					$query = $sqlite->query(trim($value));
				}
			}
			@unlink($config["system_path"] . "/temp/language.sql");
		}
	}
}
?>