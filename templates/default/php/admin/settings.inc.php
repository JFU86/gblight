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


// Beim Ändern
if (isset($_REQUEST["submit"])) {
	$settings->edit("Guestbook.Enabled", (int) $_REQUEST["chkGbActive"]);
	$settings->edit("Captcha.Enabled", (int) $_REQUEST["chkCaptchaActive"]);
	$settings->edit("AutoActivate.Enabled", (int) $_REQUEST["chkAutoActive"]);
	$settings->edit("Texteditor.Enabled", (int) $_REQUEST["chkTexteditorActive"]);
	$settings->edit("Guestbook.EntriesPerPage", (int) $_REQUEST["entriesPerPage"]);
	$settings->edit("Guestbook.MaxTextLength", (int) $_REQUEST["maxTextLength"]);
	header("Location: ./?action=settings");
	exit;
}


$vars = array(
	"{chkGbActiveChecked}"			=>	($cfg["Guestbook.Enabled"] == 1) ? "checked='checked'" : "",
	"{chkCaptchaActiveChecked}"		=>	($cfg["Captcha.Enabled"] == 1) ? "checked='checked'" : "",
	"{chkAutoActiveChecked}"		=>	($cfg["AutoActivate.Enabled"] == 1) ? "checked='checked'" : "",
	"{chkTexteditorActiveChecked}"	=>	($cfg["Texteditor.Enabled"] == 1) ? "checked='checked'" : "",	
	"{entriesPerPage}"				=>	$cfg["Guestbook.EntriesPerPage"],
	"{maxTextLength}"				=>	$cfg["Guestbook.MaxTextLength"],
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);

?>