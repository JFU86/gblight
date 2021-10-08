<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

$template = new Template();
$install = new Install();

// Installation - Step 1
if ($_REQUEST["submit"] != "" && $_REQUEST["username"] != "" && $_REQUEST["pass0"] != "" && $_REQUEST["pass0"] == $_REQUEST["pass1"]) {
	// Config Datei schreiben
	$install->writeConfig();
	header(
		"Location: install.php?submit=true&noheader=true&username=". base64_encode($_REQUEST["username"]) ."&pass0=". base64_encode($_REQUEST["pass0"]) .
		"&database={$_REQUEST["database"]}&step=2");
	exit;	
}
// Installation - Step 2 
elseif ($_REQUEST["submit"] != "" && $_REQUEST["username"] != "" && $_REQUEST["pass0"] != "" && $_REQUEST["step"] == "2") {
	// Installation der Datenbank
	$_REQUEST["username"] = base64_decode($_REQUEST["username"]);
	$_REQUEST["pass0"] = base64_decode($_REQUEST["pass0"]);
	$install->databaseInstall();
	header("Location: install.php?submit=true&step=3");
	exit;
}
// Installation - Step 3
elseif ($_REQUEST["submit"] != "" && $_REQUEST["step"] == "3") {
	// Installationsdatei entfernen
	@unlink($config["basepath"] . "/admin/install.php");
	$_SESSION["admin_id"] = 1;
	echo "<br /><div class=\"info\" align=\"center\">" .
		 "<img src=\"../templates/{$config["templateName"]}/img/s_success.png\" width=\"16\" height=\"16\" alt=\"success\" /> " .
		 "<b>Installation completed.</b> <u><a href=\"./?action=settings\">Admin Control Panel</a></u></div><br />";
	exit;
}
// Eingabeformular
else {
	// If there is something missing
	if ($_REQUEST["submit"] != "") {
		if (isset($_REQUEST["noheader"])) {
			header("Location: ./install.php?submit=true");
			exit;
		}
		echo "<br /><div class=\"errorbox\" align=\"center\">" . 
			 "<img src=\"../templates/{$config["templateName"]}/img/s_error.png\" width=\"16\" height=\"16\" alt=\"error\" /> " .
			 "<b>Please enter a <u>name</u> and a <u>password</u> !</b></div>";
	}
	
	// SQLite Version ermitteln
	if (class_exists('SQLite3', false)) {
		$data = SQLite3::version();
		$sqliteversion = $data["versionString"];
	} elseif (class_exists('SQLiteDatabase', false)) {
		$sqliteversion = sqlite_libversion();
	} else {
		$sqliteversion = "n/a";
	}

	$isWritable = (is_writable($config["system_path"])
					&& is_writable($config["system_path"] . "/config")
					&& is_writable($config["system_path"] . "/sqlite")
					&& is_writable($config["system_path"] . "/temp")
					&& is_writable($config["system_path"] . "/log") 
					) ? "Yes" : "No";
	$safemode = (ini_get('safe_mode')) ? "On" : "Off" ;
	
	$img_phpversion = (version_compare(PHP_VERSION, '5.3.3') < 0) ? "s_error.png" : "s_okay.png";
	$img_sqliteversion = (version_compare($sqliteversion, '3.6.0') < 0) ? "s_error.png" : "s_okay.png";
	$img_writable = ($isWritable == "No") ? "s_error.png" : "s_okay.png";
	$img_safemode = ($safemode == "On") ? "s_error.png" : "s_okay.png";
	
	if ($isWritable == "No" || $safemode == "On" || version_compare(PHP_VERSION, '5.3.3') < 0 || version_compare($sqliteversion, '3.6.0') < 0) {
		$script = 
			"<script type=\"text/javascript\">document.getElementById('submit').disabled=true;document.getElementById('admin_registration').style.display='none';</script>";
	} else {
		$script = "";
	}
	
	$vars = array(
		"{img_phpversion}" 		=>	$img_phpversion,
		"{img_sqliteversion}"	=>	$img_sqliteversion,
		"{phpversion}"			=>	PHP_VERSION,
		"{sqliteversion}"		=>	$sqliteversion,
		"{img_writable}"		=>	$img_writable,
		"{writable}"			=>	$isWritable,
		"{img_safemode}"		=>	$img_safemode,
		"{safemode}"			=>	$safemode,
		"{script}"				=>	$script,
		"{option_sqlite3}"		=>	(class_exists('SQLite3', false)) ? "<option value=\"Sqlite\">SQLite 3.x</option>" : "",	
	);
	
	################################################################
	#### AB HIER NICHTS ÄNDERN !!! 
	#### Teamplate einbinden und definierte Variablen ersetzen
	################################################################
	
	echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);
}
?>