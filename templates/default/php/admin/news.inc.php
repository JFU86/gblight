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

include_once("../system/version.inc.php");
$news = @file_get_contents("http://download.scripthosting.net/gblight/news/index.php?build=" . BUILD);

if ($news == "") {
	$info = "{@Aufgrund Ihrer Serverkonfiguration können Sie die aktuellen News nicht direkt empfangen.}";	
} else {
	$info = $news;
}

$vars = array(
	"{news}" => $info,
	"{build}" => BUILD,
	"{version}" => VERSION,		
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);

?>