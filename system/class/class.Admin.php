<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Admin extends Database
{	
	//------------------
	//- ADMIN METHODEN -
	//------------------

	/**
	 * Prüft die Logindaten eines Admins
	 * @param string $password
	 * @return boolean
	 */
	public function login($name, $password)
	{		
		$name = $this->clean($name);
		$password = md5($password);
		$resultRow = $this->resultRow("SELECT user_id FROM login WHERE name = '{$name}' AND pass = '{$password}' AND status = 1 LIMIT 1");
		$logfile = new Logfile();
		
		if ((int) $resultRow["user_id"] > 0) {
			$_SESSION["admin_id"] = $resultRow["user_id"];
			
			// Schreibe die Aktion ins Logfile "user"			
			$logfile->addLog("user","[LOGIN] completed login: Admin {$name} (ID: {$resultRow["user_id"]})");			
			return true;
		} else {			
			// Schreibe die Aktion ins Logfile "user"
			$logfile->addLog("user","[FAILURE] failed login: Admin '{$name}'");
			// Session entfernen		
			unset($_SESSION);
			sleep(3);
			return false;
		}
	}
	
	
	/**
	 * Admin logout
	 * @return void
	 */
	public function logout()
	{
		// Schreibe die Aktion ins Logfile "user"
		$logfile = new Logfile();
		$logfile->addLog("user", "[LOGOUT] Admin ID '{$_SESSION["admin_id"]}'");
		
		// Session entfernen
		unset($_SESSION);
		session_destroy();		
	}
	
	
	/**
	 * Registriert einen neuen Admin Benutzer
	 * @param string $name
	 * @param string $password
	 * @return boolean
	 */
	public function register($name, $password)
	{		
		$name = $this->clean($name);
		$password = (!empty($password)) ? $this->clean(md5($password)) : "";

		// Prüfen, ob ein Benutzer mit dem Namen bereits existiert
		$resultRow = $this->resultRow("SELECT count(*) as count FROM login WHERE name = '{$name}'");
		
		if ((int) $resultRow["count"] > 0 || $name == "" || $password == "") {
			// Der Benutzername ist bereits in der Datenbank vorhanden oder es fehlen Daten - Abbruch
			return false;
		} else {
			// Der Benutzer existiert noch nicht - Registrieren
			$query = $this->query("INSERT INTO login (name,pass,status) VALUES ('{$name}','{$password}',1)");
			
			// Schreibe die Aktion ins Logfile "user"
			$logfile = new Logfile();
			$logfile->addLog("user","[REGISTER] completed: {$name} (ID: {$query->lastInsertRowid()})");			
			return true;
		}
	}
	
	
	/**
	 * Gibt alle Benutzer aus
	 * @return array
	 */
	public function fetch()
	{
		$result = $this->result("SELECT * FROM vwUsers");
		$data = array();
		
		while ($row = $this->fetchAssoc($result)) {
			$data[] = $row;
		}
		
		return $data;
	}
	
	
	/**
	 * Ändert den Status eines Benutzers
	 * @param int $id
	 * @return boolean
	 */
	public function status($id)
	{
		$id = (int) $this->clean($id);
		
		$query = $this->query("UPDATE login SET status = 0 WHERE status = 1 AND user_id = {$id} AND user_id != 1");
		if ($this->affectedRows($query) > 0) 
			return true;

		$query = $this->query("UPDATE login SET status = 1 WHERE status = 0 AND user_id = {$id} AND user_id != 1");		
		if ($this->affectedRows($query) > 0) 
			return true;

		return false;
	}
	
	
	/**
	 * Löscht den Admin mit der angegebenen ID
	 * @param int $id
	 * @return boolean
	 */
	public function delete($id)
	{
		$id = (int) $this->clean($id);
		
		$query = $this->query("DELETE FROM login WHERE user_id = {$id} AND user_id != 1");
		if ($this->affectedRows($query) == 1)
			return true;
		
		return false;
	}
}
?>