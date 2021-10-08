BEGIN TRANSACTION;
/* SPLIT */
CREATE TABLE [guestbook] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[name] VARCHAR(30)  NOT NULL,
[email] VARCHAR(100) NULL,
[text] TEXT  NOT NULL,
[time] DATETIME  NOT NULL,
[ipaddress] VARCHAR(32)  NULL,
[hostname] VARCHAR(100)  NULL,
[status] INTEGER DEFAULT '1' NOT NULL
);
/* SPLIT */
INSERT INTO [guestbook] ([name],[text],[time]) VALUES ('Scripthosting.net','<b>Willkommen bei Guestbook Light!</b><br /><br />Dies ist der erste Eintrag, der jederzeit über die geschützte <b>Administrationsebene</b> gelöscht werden kann. Bei Fragen oder Vorschlägen kannst du unser <a href="http://board.scripthosting.net" target="_blank"><b>Support-Forum</b></a> besuchen!<br /><br />Viel Spaß mit dem neuen Gästebuch!',DATETIME('NOW'));
/* SPLIT */
CREATE TABLE [login] (
[user_id] INTEGER  PRIMARY KEY NOT NULL,
[name] VARCHAR(50)  NOT NULL,
[pass] VARCHAR(64)  NOT NULL,
[status] INTEGER DEFAULT '1' NOT NULL
);
/* SPLIT */
CREATE TABLE [settings] (
[config_name] VARCHAR(32)  UNIQUE NOT NULL,
[config_value] VARCHAR(255)  NULL,
PRIMARY KEY ([config_name],[config_value])
);
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('AutoActivate.Enabled','1');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Captcha.Enabled','1');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Guestbook.Enabled','1');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Guestbook.EntriesPerPage','10');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Guestbook.MaxTextLength','2000');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Texteditor.Enabled','0');
/* SPLIT */
INSERT INTO [settings] ([config_name],[config_value]) VALUES ('Gblight.dbVersion', '1200');
/* SPLIT */
CREATE VIEW IF NOT EXISTS [vwUsers] AS 
SELECT user_id,name,status FROM login ORDER BY LOWER(name);
/* SPLIT */
COMMIT TRANSACTION;