<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Gblight extends Database
{	
	/**
	 * Liest alle Datensätze aus
	 * @return array
	 */
	public function fetch($start = 0, $limit = 10)
	{
		$start = (int) $this->clean($start);
		$limit = (int) $this->clean($limit);
		$result = $this->result("SELECT * FROM guestbook WHERE status = 1 ORDER BY time DESC LIMIT {$start},{$limit}");
		$data = array();
		
		while ($row = $this->fetchAssoc($result)) {
			$data[] = $row;
		}
		
		return $data;
	}	
	
	
	/**
	 * Liest alle inaktiven Datensätze aus
	 * @return array
	 */
	public function fetchInactiv($limit = 10)
	{		
		$result = $this->result("SELECT * FROM guestbook WHERE status = 0 ORDER BY time DESC LIMIT 0,{$limit}");
		$data = array();
		
		while ($row = $this->fetchAssoc($result)) {
			$data[] = $row;
		}
		
		return $data;
	}	
	
	
	/**
	 * Gibt die Anzahl aller aktiven Einträge aus
	 * @return int
	 */
	public function getCount()
	{		
		$resultRow = $this->resultRow("SELECT count(*) as count FROM guestbook WHERE status = 1");
		return (int) $resultRow["count"];
	}	
	
	
	/**
	 * Fügt einen neuen Eintrag hinzu
	 * @param $name
	 * @param $text
	 * @param $email
	 * @return int
	 */
	public function add($name, $text, $email = "")
	{		
		// Settings laden
		$settings = new Settings();
		$cfg = $settings->fetch();
		
		$name = $this->clean(strip_tags($name));
		$text = ($cfg["Texteditor.Enabled"] == 1) 
			? $this->clean(strip_tags(trim($text), "<br><a><ul><li><strong><em><span><img>")) 
			: $this->clean(strip_tags(trim($text), "<br>"));
		$maxTextLength = (int) $cfg["Guestbook.MaxTextLength"];
		
		if ($maxTextLength > 0 && mb_strlen(strip_tags(trim($text))) > $maxTextLength)
			$text = mb_substr($text, 0, $cfg["Guestbook.MaxTextLength"]);
		
		$email = $this->clean(strip_tags($email));
		$ipaddress = $_SERVER["REMOTE_ADDR"];
		$hostname = gethostbyaddr($ipaddress);
		$status = (int) $cfg["AutoActivate.Enabled"];
		
		if ($name != "" && $text != "") {
			$datetime = date("Y-m-d H:i:s");
			$query = $this->query(
				"INSERT INTO guestbook (name,email,text,time,ipaddress,hostname,status) ".
				"VALUES ('{$name}','{$email}','{$text}','{$datetime}','{$ipaddress}','{$hostname}',{$status})"
			);
		}
		
		return $this->insertID($query);
	}
	
	
	/**
	 * Löscht einen vorhandenen Eintrag und gibt bei Erfolg true zurück
	 * @param $id
	 * @return boolean
	 */
	public function delete($id)
	{		
		if (is_numeric($id) && $id != 0) {
			$query = $this->query("DELETE FROM guestbook WHERE id = {$id}");
			return true;
		}
		
		return false;
	}
	

	/**
	 * Ändert den Status eines Eintrags
	 * @param int $id
	 * @return boolean
	 */
	public function setStatus($id)
	{		
		if (is_numeric($id) && $id != 0) {
			$resultRow = $this->resultRow("SELECT status FROM guestbook WHERE id = {$id}");
			$newStatus = ($resultRow["status"] == 0) ? 1 : 0;
			$query = $this->query("UPDATE guestbook SET status = {$newStatus} WHERE id = {$id}");
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Leert das Gästebuch
	 * @return void
	 */
	public function truncate()
	{
		$this->query("DELETE FROM guestbook");
	}
}
?>