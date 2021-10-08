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

if ($_REQUEST["update"] == "1") {
	include_once("../system/version.inc.php");
	$current_version = @file_get_contents("http://download.scripthosting.net/gblight/update/index.php");
	
	if ($current_version == "") {
		$info = "{@Die Onlineversion konnte nicht überprüft werden!}&nbsp;&nbsp;&nbsp;<img src=\"../templates/{templateName}/img/item.png\" alt=\"item\" /> <a href=\"http://www.scripthosting.net/?site=gblight\" target=\"_blank\">{@Selber prüfen!}</a>";	
	}
	elseif (BUILD < $current_version) {
		$info = "{@Es ist online eine neuere Version verfügbar!}&nbsp;&nbsp;&nbsp;<img src=\"../templates/{templateName}/img/item.png\" alt=\"item\" /> <a href=\"http://www.scripthosting.net/?site=gblight\" target=\"_blank\">{@Jetzt herunterladen!}</a>";
	}
	else {
		$info = "{@Sie besitzen bereits die aktuellste Version! Es ist kein Update notwendig.}";
	}
} else {
	$info = "{@Wenn Sie nach Updates suchen, wird eine Verbindung zum Updateserver hergestellt. Dies dient lediglich der Versionsprüfung und es werden dabei keine Benutzer-Informationen übermittelt oder gespeichert.}<br /><br /><div style='text-align:center;'><input type='button' value='{@Updates suchen}' onclick=\"document.location.href='?action=update&amp;update=1'\" /></div>";
}


$vars = array(
	"{info}" => $info,
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);

?>