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
$settings = new Settings();
$cfg = $settings->fetch();

$vars = array(
	"{date}"	=>	date("r"),
	"{script}"	=>	($cfg["Texteditor.Enabled"] == 1 && !$template->isMobileDevice()) 
		? "window.addEvent('domready',function(){ \$('text').mooEditable();});" 
		: "",
	"{lang}"	=>	$config["defaultLanguage"],
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__), $vars);

?>