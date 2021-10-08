<?php
/*
	SQLite3 Connection Class
	from Database Framework
	by Scripthosting.net
	
	Licensed under the GPLv3 
	http://www.gnu.org/copyleft/gpl.html
*/

class Sqlite
{	
	private $isConnected = false;
	private $queryCounter = 0;
	private $queryArray = array();
	private $resultArray = array();
	private $db = null;
	private $key = null;
	private $dbCon = null;	
	
	/**
	 * Create a new sqlite connection object.
	 * @param $db absolute database path and filename
	 * @param $key (optional) password
	 * @throws SQLException
	 * @return void
	 */
	public function __construct($db, $key = "")
	{		
		if ($db == "") {
			throw new SQLException("No database selected!");
			exit;			
		} elseif (!class_exists('SQLite3', false)) {
			throw new Exception("SQLite3 is not enabled in this PHP Version!");
			exit;
		} else {
			$this->db = $db;
			$this->key = $key;
		}
	}
	
	/**
	 * Connect to the SQLite Database
	 * @throws SQLException
	 * @return SQLite3
	 */
	private function connect()
	{
		$sqlite = null;
		
		if (($this->dbCon == null || !$this->isConnected) && $this->db != null) {
			if (($sqlite = new SQLite3($this->db,SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE,$this->key)) == false) {
				$this->isConnected = false;
				throw new SQLException("Error while connecting to SQLite3!");
			} else {
				$sqlite->busyTimeout(3000);
				$this->isConnected = true;
				$this->dbCon = $sqlite;
			}
		} elseif ($this->dbCon != null && $this->isConnected) {
			$sqlite = $this->dbCon;
		} elseif ($this->db == null) {
			throw new SQLException("No database selected! Please use __construct(\$db) first!");
			return null;
		}

		return $sqlite;
	}
	
	/**
	 * Create a new sql statement.
	 * @param string $sql
	 * @return SQLite3Stmt
	 */
	private function preparedStatement($sql)
	{
		$sqlite = $this->connect();
		return $sqlite->prepare($sql);		
	}
	
	/**
	 * Send an SQLite command and return the sqlite connection.
	 * This is used for INSERT, UPDATE, ALTER, DELETE, CREATE and DROP statements.
	 * @param string $sql
	 * @param boolean $error
	 * @return SQLite3
	 */
	public function query($sql, $error = true)
	{
		$sqlite = $this->connect();
		$stmt = $this->preparedStatement($sql);
		
		if ($error) {			
			if (($query = $stmt->execute()) === false) {
				$err = $sqlite->lastErrorMsg();
				$this->logError(
					"SQLite threw an error: '{$err}' (". $sqlite->lastErrorCode() .") in SQL-Statement: {$sql}"
				);
				exit(
					"<p><b>SQLitet threw an error:</b> <span style=\"color:#FF0000;\">". 
						$err ." (". $sqlite->lastErrorCode() .
							")</span> in <b>SQL-Statement:</b> <span style=\"color:#000080;\">{$sql}</span></p>"
				);
			}
		} else {
			$query = $stmt->execute();
		}
		
		$this->queryCounter += 1;
		$this->queryArray[] = $sql;
		$query->finalize();
		$stmt->close();
		
		return $sqlite;
	}	
	
	/**
	 * Send an SQLite query and return a SQLite result array.
	 * This method should be used for SELECT statements only.
	 * @param string $sql
	 * @param boolean $error
	 * @return SQLite3Result
	 */
	public function result($sql, $error = true)
	{
		$sqlite = $this->connect();
 
		if ($error) {
			if (($query = @$sqlite->query($sql)) === false) {
				$err = $sqlite->lastErrorMsg();
				$this->logError(
					"SQLite threw an error: '{$err}' (". $sqlite->lastErrorCode() .") in SQL-Statement: {$sql}"
				);
				exit(
					"<p><b>SQLite threw an error:</b> <span style=\"color:#FF0000;\">". 
					$err ." (". $sqlite->lastErrorCode() .
					")</span> in <b>SQL-Statement:</b> <span style=\"color:#000080;\">{$sql}</span></p>"
				);
			}
		} else {
			$query = @$sqlite->query($sql);
		}
		
		$this->queryCounter += 1;
		$this->queryArray[] = $sql;
		$this->resultArray[] = $result;
		
		return $query;
	}	
	
	/**
	 * Return the first sql result row.
	 * @param string $sql
	 * @return SQLite3Result
	 */
	public function resultRow($sql)
	{
		return $this->fetchAssoc($this->result($sql));
	}	
	
	/**
	 * Prepare an sql string.
	 * @param string $string
	 * @param boolean $decode
	 * @param boolean $htmlOutput
	 * @return string
	 */
	public function clean($string, $decode = false, $htmlOutput = false)
	{
		$string = trim($string);

		// Is the string base64 encoded?
		if ($decode) $string = trim(base64_decode($string));

		// Is the old 'magic_quotes' functionality active?
		if (!function_exists('get_magic_quotes_gpc') || (
				function_exists('get_magic_quotes_gpc') && (int) get_magic_quotes_gpc() == 0)) {
			$string = SQLite3::escapestring($string);
		} else {
			$string = stripslashes(SQLite3::escapestring($string));
		}
		
		// Do we need an html secure output?
		if ($htmlOutput) { 
			$xml = new XML();
			$string = $xml->htmlOutput($string);
		}

		return $string;
	}	
	
	/**
	 * Return the number of rows in this result set.
	 * @param SQLite3Result $result
	 * @return int
	 */
	public function numRows(SQLite3Result $result)
	{
		// numRows() is not implemented in libsqlite3 !! 
		// see https://bugs.php.net/bug.php?id=49303
		$i = 0;
		if (@get_class($result) == "SQLite3Result") {
			while ($row = $this->fetchAssoc($result)) {
				$i++;
			}
		}
		return (int) $i;
	}	
	
	/**
	 * Return an array of the result set.
	 * @param SQLite3Result $result
	 * @return array
	 */
	public function fetchAssoc(SQLite3Result $result)
	{
		return $result->fetcharray(SQLITE3_ASSOC);
	}	
	
	/**
	 * Return the last inserted identity.
	 * @param SQLite3 $query
	 * @return int
	 */
	public function insertID(SQLite3 $query)
	{
		return $query->lastInsertRowID();
	}	
	
	/**
	 * Return the number of affected rows by a query.
	 * @param SQLite3 $query
	 * @return int
	 */
	public function affectedRows(SQLite3 $query)
	{
		return (int) $query->changes();
	}	
	
	/**
	 * Return the current database datetime string.
	 * @return string
	 */
	public function getSQLDateTime()
	{
		$resultRow = $this->resultRow("SELECT DATETIME('NOW') as datum");
		return $resultRow["datum"];
	}	
	
	/**
	 * Return the name of the sql random() function
	 * @return string
	 */	
	public function getRandomFunctionName()
	{
		return "RANDOM";
	}
	
	/**
	 * Return a new or the existing database connection.
	 * @return SQLite3
	 */
	public function getConnection()
	{
		return $this->connect();
	}
	
	/**
	 * Try to log an error to a file.
	 * @param string $error
	 * @return void
	 */
	private function logError($error)
	{		
		$logPath = realpath(dirname(__FILE__));
		
		if (is_writable($logPath)) {
			$datum = date("Y-m-d H:i:s O");
			$open = @fopen($logPath."/class.Sqlite.log","a");
			$write = @fwrite($open,"[{$datum}]: {$error}\r\n\r\n");
			$close = @fclose($open);
		}
	}
	
	public function __destruct()
	{		
		if ($this->isConnected) {			
			if ($this->config["debug"] == true) {
				$class = get_class($this);
				$_SESSION["db"]["SQLite3queryCount"] += $this->queryCounter;
				$_SESSION["db"]["SQLite3queryExec"][$class] = $this->queryArray;
				$_SESSION["db"]["SQLite3isConnected"] = true;
			}

			foreach ($this->resultArray as $value) {
				if ($value != null && $value !== false && get_class($value) == "SQLite3Result") {
					@$value->finalize();
				}
			}		

			$this->dbCon->close();
		}
	}
}

if (!class_exists('SQLException', false)) {
	class SQLException extends Exception 
	{
		public function __construct($message, $code = 0) 
		{
	        parent::__construct($message, $code);
	    }
	    public function __toString() 
	    {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }
	}
}

if (!class_exists('SQLQueryException', false)) {
	class SQLQueryException extends Exception 
	{
		public function __construct($message, $code = 0) 
		{
	        parent::__construct($message, $code);
	    }
	    public function __toString() 
	    {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }
	}	
}

if (!class_exists('SQLResultException', false)) {
	class SQLResultException extends Exception 
	{
		public function __construct($message, $code = 0) 
		{
	        parent::__construct($message, $code);
	    }
	    public function __toString() 
	    {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }	
	}
}
?>