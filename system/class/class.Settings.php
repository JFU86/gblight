<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Settings extends Database
{	
	/**
	 * Fetch all settings from sql
	 * @return array
	 */
	public function fetch()
	{		
		// Initialize settings array
		$settings = array();
		
		// Read all settings from sqlite
		$result = $this->result("SELECT config_name,config_value FROM settings");
		
		while ($row = $this->fetchAssoc($result)) {
			$settings[$row["config_name"]] = $row["config_value"];
		}
		
		return $settings;		
	}
	
	
	/**
	 * Edit a config value
	 * @param string $config_name
	 * @param string $config_value
	 * @return int
	 */
	public function edit($config_name, $config_value)
	{		
		$config_name = $this->clean($config_name);
		$config_value = $this->clean($config_value);
		
		$query = $this->query("UPDATE settings SET config_value = '{$config_value}' WHERE config_name = '{$config_name}'");
		return $this->affectedRows($query);
	}	
}
?>