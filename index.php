<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

// (1) Load configuration
if (file_exists("system/config/config.inc.php")) {
	require_once("system/config/config.inc.php");
	$tpl = new Template();
} elseif (file_exists("admin/install.php") && !file_exists("system/config/config.inc.php")) {
	header("Location: admin/install.php");
	exit;
}

// (2) check if gblight is not installed (if so, the install.php must be removed)
if (file_exists($config["basepath"] . "/admin/install.php") && file_exists("system/config/config.inc.php") && !$config["debug"]) {
	$tpl->getTemplate("overall_header");
	$tpl->getTemplate("gfxheader");
	echo $tpl->errorbox("<b>Please delete 'admin/install.php' first before using Guestbook Light!</b>", true, "");
	$tpl->getTemplate("copyright");
	$tpl->getTemplate("overall_footer");
	exit;
}

// (3) run updates if possible
if (file_exists($config["system_path"] . "/temp/UPDATE") && !$config["debug"]) {
	$install = new Install();
	$install->databaseUpdate();
}

// (4) load guestbook
if (!isset($_REQUEST["noheader"])) { 
	$tpl->getTemplate("overall_header");
	$tpl->getTemplate("gfxheader");
}

$tpl->getTemplate("guestbook");
$tpl->getTemplate("copyright");
$tpl->getTemplate("overall_footer");
?>