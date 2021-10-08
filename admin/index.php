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
if (file_exists("../system/config/config.inc.php")) {
	require_once("../system/config/config.inc.php");
} else {
	require_once("../system/config/config.min.inc.php");
}

// (2) run updates if possible
if (file_exists("../system/config/config.inc.php") && file_exists($config["system_path"] . "/temp/UPDATE") && !$config["debug"]) {
	$install = new Install();
	$install->databaseUpdate();
}

$template = new Template();

/*************************************************************************************
 * (3) check if gblight is not installed yet or if so, the install.php must be removed
 *************************************************************************************/
if (file_exists($config["basepath"] . "/admin/install.php") && file_exists($config["system_path"] . "/sqlite/gblight.db") && !$config["debug"]) {
	$template->getTemplate("admin_header");
	$template->getTemplate("gfxheader");
	echo "<br />";
	echo $template->errorbox("<b>Please delete 'admin/install.php' first before using Gblight !</b>");
	echo "<br />";
	$template->getTemplate("copyright");
	$template->getTemplate("overall_footer");
	exit;
} elseif (file_exists($config["basepath"] . "/admin/install.php") && !file_exists($config["system_path"] . "/sqlite/gblight.db")) {
	header("Location: install.php");
	exit;
}


/***************************
 * (4) User login validation
 ***************************/
// Login
if ($_REQUEST["submit"] != "" && isset($_REQUEST["name"]) && isset($_REQUEST["pass0"])) {
	$admin = new Admin();
	$admin->login($_REQUEST["name"],$_REQUEST["pass0"]);
	header("Location: ./");
	exit;
}
// Login-Check
if ($_SESSION["admin_id"] == null) {
	$template->getTemplate("admin/admin_header");
	$template->getTemplate("admin/gfxheader");
	$template->getTemplate("admin/login");
	$template->getTemplate("copyright");
	$template->getTemplate("overall_footer");
	exit;
}


/***********************
 * (5) Request templates
 ***********************/
if (!isset($_REQUEST["noheader"])) {
	$template->getTemplate("admin/admin_header");
	$template->getTemplate("admin/gfxheader");
	$template->getTemplate("admin/menu");
}

switch ($_REQUEST["action"]) {
	default: 
		$template->getTemplate("admin/".trim($_REQUEST["action"])); 
		break;
	case "": 
		$template->getTemplate("admin/news"); 
		break;
	case "logout": 
		$admin = new Admin(); 
		$admin->logout(); 
		$template->getTemplate("admin/login"); 
		break;
}

$template->getTemplate("copyright");
$template->getTemplate("overall_footer");
?>