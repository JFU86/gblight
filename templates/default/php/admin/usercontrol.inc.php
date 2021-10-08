<?php
/*
	AdRotator Light
	by Scripthosting.net

	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net/viewforum.php?f=28
	Don't send emails asking for support!!
*/

$template = new Template();
$admin = new Admin();

// Admin Erstellanfrage
if (isset($_REQUEST["newuser"]) && isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
	$admin->register($_REQUEST["username"], $_REQUEST["password"]);
	header("Location: ./?action={$_REQUEST["action"]}");
	exit;
}

// Admin Löschanfrage
if (isset($_REQUEST["drop"]) && isset($_REQUEST["id"])) {
	$admin->delete($_REQUEST["id"]);
	header("Location: ./?action={$_REQUEST["action"]}");
	exit;
}

// Admin Statusanfrage
if (isset($_REQUEST["status"]) && isset($_REQUEST["id"])) {
	$admin->status($_REQUEST["id"]);
	header("Location: ./?action={$_REQUEST["action"]}");
	exit;
}

// Admin Benutzerliste
$list = "";
$i = 0;
foreach ($admin->fetch() as $value) {
	$name = ($value["status"] == 1) ? $value["name"] : "<span style='color:#FF0000;'>" . $value["name"] . "</span>"; // Inaktive Benutzer rot einfärben
	$name = ($value["user_id"] != 1) ? $name : "<b>". $name . "</b>"; // Hauptbenutzer fett markieren 
	$list .= "<tr><td class=\"td". $i % 2 ."\" style=\"width:80px;\">".
		"<a href='#' onclick='statusUser({$value["user_id"]})'><img src=\"../templates/{templateName}/img/b_off.gif\" alt=\"{@status}\" /> {@Status}</a><br />".
		"<a href='#' onclick='deleteUser({$value["user_id"]})'>" .
		"<img src=\"../templates/{templateName}/img/b_drop.png\" alt=\"{@löschen}\" /> {@löschen}</a></td>". "\r\n\t\t\t". 
		"<td class=\"td". $i % 2 ."\">" . $name . "</td></tr>";
	$i++;
}

$vars = array(
	"{userlist}"	=>	$list,
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);

?>