<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

// Klassen instanzieren 
$template = new Template();
$guestbook = new Gblight();
$mail = new Mail();
$settings = new Settings();
$cfg = $settings->fetch();
$fehler = "";


////////////////////////////////////////////
// Prüfen, ob das Gästebuch aktiviert ist //
////////////////////////////////////////////

if ($cfg["Guestbook.Enabled"] == 0) {	
	echo $template->output($config["template_path"] . "/html/disabled.html");
	$template->getTemplate("copyright");
	$template->getTemplate("overall_footer");
	exit;
}


////////////////////////
// Eintrag hinzufügen //
////////////////////////

// Beim Absenden alle Felder in der Session speichern
if (isset($_REQUEST["submit"])) {
	$_SESSION["name"] = $guestbook->clean($_REQUEST["name"], false, true);
	$_SESSION["email"] = $guestbook->clean($_REQUEST["email"], false, true);
	$_SESSION["text"] = $guestbook->clean($_REQUEST["text"], false, true);
}
// Neuen Eintrag hinzufügen, falls Submit betätigt wurde und alle Felder korrekt gefüllt wurden
if (isset($_REQUEST["submit"]) && $_REQUEST["name"] != null && $_REQUEST["text"] != null && ($_REQUEST["email"] == "" || $_REQUEST["email"] != "" && $mail->validate($_REQUEST["email"]))	&& $_REQUEST["accept_agreement"] == "1" && ($_REQUEST["captcha"] == $_SESSION['captcha_spam'] || $cfg["Captcha.Enabled"] == 0)){
	$add = $guestbook->add($_REQUEST["name"],$_REQUEST["text"],$_REQUEST["email"]);
	unset($_SESSION["text"]);
	if ($_REQUEST["email"] == "")	unset($_SESSION["email"]);
	
	// Wenn der Eintrag nicht sofort aktiviert wird, leite auf eine Seite mit Meldung weiter
	if ($cfg["AutoActivate.Enabled"] == 0) {
		header("Location: ./?error=MUST_BE_ACTIVATED#error"); // Um Doppeleinträge zu verhindern, ruft sich die Datei selbst auf
		exit;
	} else {
		header("Location: ./"); // Um Doppeleinträge zu verhindern, ruft sich die Datei selbst auf
		exit;
	}
}
// Falls nicht alle Felder gefüllt wurden
elseif (isset($_REQUEST["submit"]) && (trim($_REQUEST["name"]) == "" || trim($_REQUEST["text"]) == "" || trim($_REQUEST["captcha"]) == "")) {	
	header("Location: ./?error=fill_all_fields#error");
	exit;
}
// Falls eine ungültige E-Mailadresse angegeben wurde
elseif (isset($_REQUEST["submit"]) && $_REQUEST["email"] != "" && !$mail->validate($_REQUEST["email"])) {
	header("Location: ./?error=invalid_email_address#error");
	exit;
}
// Falls das Agreement nicht angenommen wurde
elseif (isset($_REQUEST["submit"]) && $_REQUEST["accept_agreement"] != "1") {
	header("Location: ./?error=agreement_not_accepted#error");
	exit;
}
// Falls das Captcha aktiviert ist und nicht korrekt übermittelt wurde
elseif (isset($_REQUEST["submit"]) && $cfg["Captcha.Enabled"] == 1 && $_REQUEST["captcha"] != $_SESSION['captcha_spam']) {
	sleep(3); // Ausführung um (3) Sekunden verzögern, um übermäßigen Captcha Spam zu verhindern
	header("Location: ./?error=captcha_missing_or_wrong#error");
	exit;
}

// Fehlerbehandlung
if (isset($_REQUEST["error"])) {
	switch(trim($_REQUEST["error"])){
		case "fill_all_fields":
			$fehler = "{@Bitte alle Pflichtfelder ausfüllen}.";
			break;
		case "invalid_email_address":
			$fehler = "{@Ungültige E-Mail Adresse}!";
			break;
		case "agreement_not_accepted":
			$fehler = "{@Bitte akzeptieren Sie die Nutzungsbedingungen}!";
			break;
		case "captcha_missing_or_wrong":
			$fehler = "{@Bitte übertragen Sie den Sicherheitscode in das Textfeld}!";
			break;
		case "MUST_BE_ACTIVATED":
			$fehler = "{@Ihr Beitrag wurde eingetragen, muss aber noch vom Administrator freigeschaltet werden}!";
			break;			
	}
}


///////////////////////////
// Gästebuchinhalt lesen //
///////////////////////////

$start = (isset($_REQUEST["page"])) ? (($_REQUEST["page"]*$cfg["Guestbook.EntriesPerPage"])-$cfg["Guestbook.EntriesPerPage"]) : 0;
$data = $guestbook->fetch($start,$cfg["Guestbook.EntriesPerPage"]);
$output = "";
$i = 1;

foreach ($data as $row) {
	$date = date($config["timeFormat"] . " T",strtotime($row["time"]));
	$text = ($cfg["Texteditor.Enabled"] == 0) ? nl2br($row["text"]) : $row["text"];
	$td = ($i%2)+1;

	$output .= "<tr class='tr{$td}'><td class='entry'>#{$row["id"]} {@von} <b>{$row["name"]}</b> {@am} {$date}<br /><hr />{$text}</td></tr>" . "\r\n\t";
	$i++;
}

if (count($data) == 0) {
	$output .= 
		"<tr class='tr1'><td class='noentry'><img src=\"templates/{templateName}/img/b_tipp.png\" alt=\"info\" />" . 
		"{@Es wurden noch keine Beiträge verfasst}.</td></tr>";
}


////////////////////////////////
// Verfügbare Seiten auslesen //
////////////////////////////////

$anzahl = $guestbook->getCount();
for ($i = 0; $i < ($anzahl/$cfg["Guestbook.EntriesPerPage"]); $i++) {
	if ($i > 0) {
		$seiten .= " | ";
	}
	$page = ($i+1);
	$curPage = (isset($_REQUEST["page"]) && (int)(trim($_REQUEST["page"])) != 1) ? trim($_REQUEST["page"]) : 1;
	if ($curPage != $page) {
		$seiten .= "<a href='?page={$page}'>" . $page . "</a>";
	} else {
		$seiten .= "<span style='font-weight:bold;color:#008000;'>" . $page . "</span>";
	}		
}


// Text-Variablen ersetzen
$vars = array(
	"{guestbook}" => $output,
	"{pages}"	=>	$seiten,
	"{name}" => $_SESSION["name"],
	"{email}" => $_SESSION["email"],
	"{text}" => $_SESSION["text"],
	"{fehler}" => $fehler,
	"{catpcha}" => ($cfg["Captcha.Enabled"] == 1) 
		? '<tr class="tr2" id="trCaptcha"><td style="border-left:0;"><b>{@Sicherheitscode}:</b></td><td style="border-right:0;">' . 
		  '<img src="captcha.php" alt="captcha" /><br /><input type="text" name="captcha" style="width:135px;" autocomplete="off" /></td></tr>' 
		: "",
	"{maxTextLength}" => $cfg["Guestbook.MaxTextLength"],
);

################################################################
#### AB HIER NICHTS ÄNDERN !!! 
#### Teamplate einbinden und definierte Variablen ersetzen
################################################################

echo $template->output($template->getFilePath(__FILE__), $vars);

?>