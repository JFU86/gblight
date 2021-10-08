<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

if (file_exists("system/config/config.inc.php")) {
	include_once("system/config/config.inc.php");
} else {
	exit;
}

$tpl = new Template();

$tpl->getTemplate("overall_header");
$tpl->getTemplate("gfxheader");
$tpl->getTemplate("terms");
$tpl->getTemplate("copyright");
$tpl->getTemplate("overall_footer");

?>