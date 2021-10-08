BEGIN TRANSACTION;
/* SPLIT */
INSERT OR IGNORE INTO [settings] ([config_name],[config_value]) VALUES ('Guestbook.MaxTextLength','2000');
/* SPLIT */
REPLACE INTO [settings] (config_name,config_value) VALUES ('Gblight.dbVersion', '1200');
/* SPLIT */
UPDATE [guestbook] SET text = replace(text,"/default/img","/global/img");
/* SPLIT */
ALTER TABLE [login] RENAME TO [login_backup];
/* SPLIT */
CREATE TABLE [login] (
[user_id] INTEGER  PRIMARY KEY NOT NULL,
[name] VARCHAR(50)  NOT NULL,
[pass] VARCHAR(64)  NOT NULL,
[status] INTEGER DEFAULT '1' NOT NULL
);
/* SPLIT */
INSERT INTO [login] SELECT * FROM [login_backup];
/* SPLIT */
DROP TABLE IF EXISTS [login_backup];
/* SPLIT */
CREATE VIEW IF NOT EXISTS [vwUsers] AS 
SELECT user_id,name,status FROM login ORDER BY LOWER(name);
/* SPLIT */
COMMIT TRANSACTION;