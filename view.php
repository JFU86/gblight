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

if (isset($_REQUEST["cid"])) {
		
	$template = new Template();
	$ar = new Adrotator();
	$get = trim($_REQUEST["get"]);
	
	switch($get){
		default: 
				$template->getTemplate("code_header"); 
				echo $ar->printRandomBanner($_REQUEST["cid"]);
				$template->getTemplate("overall_footer");
				break;
		case "image":
 				echo $ar->printRandomImage($_REQUEST["cid"],$_REQUEST["rid"]);
 				break;
		case "php":
				echo $ar->printRandomBanner($_REQUEST["cid"]);
				break;
	}
}
?>