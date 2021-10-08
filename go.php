<?php
/*
	AdRotator Light
	by Scripthosting.net

	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net/viewforum.php?f=28
	Don't send emails asking for support!!
*/

include_once("system/config/config.inc.php");

if (isset($_REQUEST["id"])) {
	$banner_id = (int)trim($_REQUEST["id"]);
}
elseif (isset($_SESSION["R".$_REQUEST["rid"]]["id"])) { // Arlight >= 1.5.0
	$banner_id = (int) $_SESSION["R".$_REQUEST["rid"]]["id"];
}
elseif (isset($_SESSION["id"])) { // Arlight < 1.5.0
	$banner_id = (int) $_SESSION["id"];
}
else {
	exit;
}

$ar = new Adrotator();
$ar->goToUrl($banner_id);
?>