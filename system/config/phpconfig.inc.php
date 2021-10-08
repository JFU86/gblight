<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

// PHP Reporting auf Default einstellen
error_reporting(E_ALL & ~E_NOTICE);

// Die folgende Zeile konvertiert alles einheitlich in UTF-8
header('Content-type: text/html; charset=UTF-8');
// Frames und iFrames von Fremden Seiten blockieren
header('X-Frame-Options: SAMEORIGIN');

// Lege die Standard-Zeitzone fest
if (function_exists('date_default_timezone_set')) 
date_default_timezone_set("Europe/Berlin");

// Starte eine neue Session bzw. setze eine bestehende fort
// Zusätzliche Session-Einstellungen werden gesetzt
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', 900);

// GZIP-Komprimierung aktivieren
//ini_set('zlib.output_compression', '1');
//ini_set('zlib.output_compression_level', '-1');
?>