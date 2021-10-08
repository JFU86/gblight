<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

// DIESE DATEI NICHT ANPASSEN! SIE WIRD NUR FÜR DIE INSTALLATION BENÖTIGT!
// DO NOT CHANGE THIS FILE! IT IS USED ONLY FOR INSTALLATION!

// PHP Reporting auf Default einstellen
error_reporting(E_ALL & ~E_NOTICE);

// Die folgende Zeile konvertiert alles einheitlich in UTF-8
header('Content-type: text/html; charset=UTF-8');

// Lege die Standard-Zeitzone fest
if (function_exists('date_default_timezone_set')) 
date_default_timezone_set("Europe/Berlin");

// Starte eine neue Session bzw. setze eine bestehende fort
// Zusätzliche Session-Einstellungen werden gesetzt
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', 900);
session_name("gblightsid");
if (!isset($_SESSION)) session_start();

##########################################
$config = array();
$config["templateName"] = "default";
$config["basepath"] = substr(__FILE__,0,-33);
$config["scriptpath"] = "http://".$_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
$config["template_path"] = $config["basepath"] . "/templates/". $config["templateName"];
$config["include_path"] = $config["basepath"] . "/templates/{$config["templateName"]}/php";
$config["system_path"] = $config["basepath"] . "/system";
$config["class_path"] = $config["basepath"] . "/system/class";
$config["defaultLanguage"] = "de";
$config["debug"] = false;
##########################################

// Aktuelle Sprache festlegen
$config["language"] = (!isset($_SESSION["language"])) ? $config["defaultLanguage"] : $_SESSION["language"];

////////////////////////////////////////////////////////////////////////
// Teile PHP das Standardverzeichnis für alle Klassen und Interfaces mit
////////////////////////////////////////////////////////////////////////
spl_autoload_register(function ($class_name) {
	global $config;
	
    if (file_exists($config["system_path"] . "/class/class.". $class_name .".php")) {
		include_once($config["system_path"] . "/class/class.". $class_name .".php");
	}
	elseif (file_exists($config["system_path"] . "/class/interface.". $class_name .".php")) {
		include_once($config["system_path"] . "/class/interface.". $class_name .".php");
	}
	elseif (file_exists($config["system_path"] . "/class/". str_replace("\\","/",$class_name) .".class.php")) {
		include_once($config["system_path"] . "/class/". str_replace("\\","/",$class_name) .".class.php");
	}
	else {
		throw new Exception("Class or Interface {$class_name} not found!");
	}
});
?>