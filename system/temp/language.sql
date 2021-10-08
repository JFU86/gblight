BEGIN TRANSACTION;
/* SPLIT */
CREATE TABLE [language] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[de] TEXT  UNIQUE NOT NULL,
[en] TEXT  NULL
);
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Menü','Menu');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Update','Update');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Abmelden','Logout');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Optionen','Options');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Name','Name');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('absenden','send');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Allgemeine Informationen','General Information');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bei Fragen, Anregungen und Problemen können Sie das','For questions, suggestions or problems, you can visit the');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Support-Forum besuchen','Support-Forums');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Nach Updates suchen','Check for updates');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Option','Option');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Wert','Value');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Daten Schreibrechte','Data write access');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Datenbank','Database');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Wenn Sie nach Updates suchen, wird eine Verbindung zum Updateserver hergestellt. Dies dient lediglich der Versionsprüfung und es werden dabei keine Benutzer-Informationen übermittelt oder gespeichert.','When you are looking for updates, a connection to our update server is established. This is only for the version check and there will be no user information transmitted or stored.');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Updates suchen','Update check');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Sie besitzen bereits die aktuellste Version! Es ist kein Update notwendig.','You already have the latest version! There is no update needed.');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Gästebuch Zugang','Guestbook Access');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Administrator Name','Administrator name');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Administrator Passwort','Administrator password');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('anmelden','login');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Fehler','Error');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('In Ihrem Browser ist JavaScript deaktiviert. Arlight ist somit nicht voll funktionsfähig.','Your JavaScript is disabled. Arlight is therefore not fully functional.');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte aktivieren Sie JavaScript dauerhaft für diese Seite und versuchen es erneut.','Please enable JavaScript permanently for this page and try again.');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Es ist online eine neuere Version verfügbar!','There is a newer version available online!');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Jetzt herunterladen!','Download now!');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte alle Felder ausfüllen','Please fill all fields');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('löschen','delete');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Status','Status');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Details','Details');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Legende','Legend');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Beiträge','Entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Seite','Page');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Eintrag hinzufügen','Write new entry');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('E-Mail','Email');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Text','Text');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Sicherheitscode','Security Code');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Ich habe die <b>Nutzungsbedingungen</b> gelesen und akzeptiert','I read the <b>terms of use</b> and I accept it');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Eintrag absenden','Submit entry');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte alle Pflichtfelder ausfüllen','Please fill out all required fields');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Ungültige E-Mail Adresse','Invalid email address');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte akzeptieren Sie die Nutzungsbedingungen','Please accept the terms of use');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte übertragen Sie den Sicherheitscode in das Textfeld','Please enter the security code in the text box');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Ihr Beitrag wurde eingetragen, muss aber noch vom Administrator freigeschaltet werden','Your entry has been registered, but it must be enabled by the administrator');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Es wurden noch keine Beiträge verfasst','There are no public entries yet');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('von','from');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('am','on');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Home','Home');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Einträge verwalten','Manage entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Einstellungen','Settings');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Willkommen bei Guestbook Light','Welcome to Guestbook Light');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Es gibt keine inaktiven Beiträge','There are no inactive entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Es gibt keine aktiven Beiträge','There are no active entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Einstellung','Setting');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Gästebuch aktivieren','Enable guestbook');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Aktiviert das Gästebuch und lässt neue Einträge zu','Enables the guestbook and allows new entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Sicherheitscode aktivieren','Enable security code');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Gäste müssen einen Sicherheitscode eingeben, um einen Eintrag absenden zu können','Guests have to enter a security code in order to submit a new entry');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Beiträge automatisch freischalten','Activate Entries automatically');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Beiträge werden automatisch freigeschaltet und müssen nicht zunächst vom Administrator freigeschaltet werden, bevor Sie im Gästebuch sichtbar sind','Entries are automatically activated and do not have to be enabled by the administrator before they are visible in the guestbook');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Richtext Editor aktivieren','Enable the richtext editor');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Texte können mithilfe des Editors formatiert werden','Text can be edited by using the editor');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Einträge pro Seite','Entries per page');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Auf einer Seite werden maximal so viele Einträge angezeigt','One page is limited to this number of entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Einstellungen speichern','Save settings');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Inaktive Beiträge','Inactive entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Aktive Beiträge','Active entries');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Anpassen der Gästebuch Einstellungen','Change the guestbook settings');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Systeminfo','Systeminfo');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte diese Daten bei Supportanfragen mit angeben','Please specify these information on to every support inquiry');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Das Gästebuch ist derzeit deaktiviert','The guestbook is currently offline');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Bitte versuchen Sie es später erneut','Please come back at a later time');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Maximale Textlänge pro Beitrag','Maximum text length for each entry');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Limitiert die Beiträge auf eine bestimmte Textlänge','Limits the text for each entry to this length');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Status des Eintrags wirklich Ändern?','Do you really want to change the status of this entry?');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Soll der Eintrag wirklich unwiderruflich gelöscht werden?','Do you really want to delete this entry?');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Benutzerverwaltung','User control');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Nach Software-Aktualisierungen suchen','Check for software updates');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('weiter','next');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Soll der Benutzerstatus wirklich geändert werden?','Do you really want to change the user status?');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Der Hauptbenutzer darf nicht deaktiviert werden!','The main user cannot be deactivated!');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Soll der Benutzer wirklich unwiderruflich gelöscht werden?','Do you really want to permanently delete the user?');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Der Hauptbenutzer darf nicht gelöscht werden!','The main user cannot be deleted!');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Benutzer','User');
/* SPLIT */
INSERT INTO [language] ([de],[en]) VALUES ('Benutzer hinzufügen','Add user');
/* SPLIT */
COMMIT TRANSACTION;