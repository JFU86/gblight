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
$guestbook = new Gblight();
$settings = new Settings();
$cfg = $settings->fetch();


/////////////////////////////////////////////
// Einträge löschen oder den Status ändern //
/////////////////////////////////////////////

if (isset($_REQUEST["status"]) && isset($_REQUEST["id"])) {
	$guestbook->setStatus($_REQUEST["id"]);
	header("Location: ./?action=overview");
	exit;
}

if (isset($_REQUEST["drop"]) && isset($_REQUEST["id"])) {
	$guestbook->delete($_REQUEST["id"]);
	header("Location: ./?action=overview");
	exit;
}


////////////////////////////////
// Inaktive Beiträge auslesen //
////////////////////////////////

$all_inactive = "";
$i = 1;
$data = $guestbook->fetchInactiv();
foreach ($data as $row) {
	$date = date($config["timeFormat"], strtotime($row["time"]));
	$text = ($cfg["Texteditor.Enabled"] == 0) ? nl2br($row["text"]) : $row["text"];
	$text = str_replace("src=\"templates","src=\"../templates", $text);
	$td = ($i % 2) + 1;
	$all_inactive .= "<tr class=\"tr{$td}\">
			<td>
				#{$row["id"]} {@von} <a href='mailto:{$row["email"]}'><b>{$row["name"]}</b></a> ({$row["ipaddress"]}) {@am} {$date}
				<hr />
				<a href='javascript:void(0);' onclick='dropEntry({$row["id"]});'><img src=\"../templates/{templateName}/img/b_drop.png\" alt=\"drop\" /></a>
				<a href='javascript:void(0);' onclick='setStatus({$row["id"]});'><img src=\"../templates/{templateName}/img/s_success.png\" alt=\"status\" /></a>&nbsp;&nbsp;
				{$text}
			</td>
		</tr>";
	$i++;
}
if (count($data) == 0) {
	$all_inactive = "<tr class='tr2'><td>{@Es gibt keine inaktiven Beiträge}.</td></tr>";
}



//////////////////////////////
// Aktive Beiträge auslesen //
//////////////////////////////

$start = (isset($_REQUEST["page"])) ? (($_REQUEST["page"] * $cfg["Guestbook.EntriesPerPage"]) - $cfg["Guestbook.EntriesPerPage"]) : 0;
$all_active = "";
$i = 1;
$data = $guestbook->fetch($start,$cfg["Guestbook.EntriesPerPage"]);
foreach ($data as $row) {
	$date = date($config["timeFormat"], strtotime($row["time"]));
	$text = ($cfg["Texteditor.Enabled"] == 0) ? nl2br($row["text"]) : $row["text"];
	$text = str_replace("src=\"templates","src=\"../templates", $text);
	$td = ($i % 2) + 1;
	$all_active .= "<tr class=\"tr{$td}\">
	<td>
		#{$row["id"]} {@von} <a href='mailto:{$row["email"]}'><b>{$row["name"]}</b></a> ({$row["ipaddress"]}) {@am} {$date}
		<hr />
		<a href='javascript:void(0);' onclick='dropEntry({$row["id"]});'><img src=\"../templates/{templateName}/img/b_drop.png\" alt=\"drop\" /></a>
		<a href='javascript:void(0);' onclick='setStatus({$row["id"]});'><img src=\"../templates/{templateName}/img/b_off.gif\" alt=\"status\" /></a>&nbsp;
		{$text}
	</td>
	</tr>";
	$i++;
}
if (count($data) == 0) {
	$all_active = "<tr class='tr2'><td>{@Es gibt keine aktiven Beiträge}.</td></tr>";
}


////////////////////////////////
// Verfügbare Seiten auslesen //
////////////////////////////////

$anzahl = $guestbook->getCount();
for ($i = 0; $i < ($anzahl/$cfg["Guestbook.EntriesPerPage"]); $i++) {
	if ($i > 0) {
		$seiten .= " | ";
	}
	$page = ($i + 1);
	$curPage = (isset($_REQUEST["page"]) && (int)(trim($_REQUEST["page"])) != 1) ? trim($_REQUEST["page"]) : 1;
	if ($curPage != $page) {
		$seiten .= "<a href='?action=overview&amp;page={$page}'>". $page . "</a>";
	} else {
		$seiten .= "<span style='font-weight:bold;color:#008000;'>". $page . "</span>";
	}
}


$vars = array(
	"{all_inactive}"	=>	$all_inactive,
	"{all_active}"		=>	$all_active,
	"{pages}"			=>	$seiten,		
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__, "admin"), $vars);

?>