<?php
/*
	Crypto Security Class
	from Security Framework
	by Scripthosting.net
	
	Licensed under the GPLv3 
	http://www.gnu.org/copyleft/gpl.html
*/

class Crypto
{	
	private $key, $iv, $cryptoPath;
	
	/**
	 * Initialize Crypto with optional secret key and iv
	 * @param string $key 
	 * 		Optional Crypto Key
	 * @param string $iv 
	 * 		Optional INIT_VECTOR
	 * @throws InvalidKeyException
	 * @return void
	 */
	public function __construct($key = '', $iv = '0000000000000000')
	{	
   		global $config; // use from the global config array
   		
   		// set crypto path
   		if (isset($config["system_path"])) {
   			$this->setCryptoPath($config["system_path"] . "/config");
   		} else {
   			$this->setCryptoPath(dirname(__FILE__));
   		}
		
   		// set key
		if ($key != '') {
    		$this->key = $key;
    	} elseif (($key = $this->readKeyFile()) != "") {
    		$this->key = $key;
    	} else {
    		throw new InvalidKeyException("No key entered in contructor Crypto( \$key ).");
    	}
    	
    	// set iv
   		$this->iv = $iv;
 	}
	
	
	/**
	 * Encrypt a string with Rijndael-128
	 * @param string $text
	 * @param int $mode (0=binary, 1=base64, 2=hexadecimal)
	 * @return string
	 */
	public function encrypt($text, $mode = 0)
	{		
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
      
		// Add padding to string
		$text = $this->pkcs5Pad("CONTENT=" . $text, $size);
		
		mcrypt_generic_init($cipher, $this->key, $this->iv);
		$text = mcrypt_generic($cipher, $text);
		
		mcrypt_generic_deinit($cipher);
		
		if ($mode == 1) { // Base64
			$text = base64_encode($text);
		} elseif ($mode == 2) { // Hexadezimal
			$text = bin2hex($text);
		}

		return $text;
	}
	
	
	/**
	 * Decrypt a string with Rijndael-128
	 * @param string $encrypted
	 * @param int $mode (0=binary, 1=base64, 2=hexadecimal)
	 * @throws InvalidKeyException
	 * @return string
	 */
	public function decrypt($encrypted, $mode = 0)
	{		
		if ($encrypted != null) {		
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
			$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	      
			mcrypt_generic_init($cipher, $this->key, $this->iv);	
			
			if ($mode == 1) { // Base64
				$encrypted = base64_decode($encrypted);
			} elseif ($mode == 2) { // Hexadezimal
				$encrypted = pack('H*', $encrypted);
			}

			$decrypted = mdecrypt_generic($cipher, $encrypted);
			mcrypt_generic_deinit($cipher);
			
			// Check valid string
			if (substr($this->pkcs5Unpad($decrypted), 0, 8) == "CONTENT=") {
				return substr($this->pkcs5Unpad($decrypted), 8);
			} else {
				throw new InvalidKeyException("Invalid password entered for decrypt operation");
			}
		}
		return "";
	}
	
	
	/**
	 * Defines the ".crypto" filepath
	 * @param string $string
	 * 		Crypto-filepath
	 */
	private function setCryptoPath($string)
	{
		$this->cryptoPath = realpath(trim($string));
	}
	
	
	/**
     * Adds pkcs5 padding
     * @return Given text with pkcs5 padding
     * @param string $data
     *   string to pad
     * @param integer $blocksize
     *   Blocksize used by encryption
     */
	private function pkcs5Pad($data, $blocksize)
	{        
        $pad = $blocksize - (strlen($data) % $blocksize);
        $returnValue = $data . str_repeat(chr($pad), $pad);
        
        return $returnValue;
    }
    
    
    /**
     * Removes padding
     * @return Given text with removed padding characters
     * @param string $data
     *   string to unpad
     */
    private function pkcs5Unpad($data)
    {      
		$pad = ord($data{strlen($data)-1});
		
		if ($pad > strlen($data)) {
			return false;
		}
		if (strspn($data, chr($pad), strlen($data) - $pad) != $pad) {
			return false;
		}

		return substr($data, 0, -1 * $pad);
    }
    
    
    /**
     * Read the .crypto keyfile and return the key
     * @return string
     */
    private function readKeyFile()
    {    	
    	$filename = $this->cryptoPath . "/.crypto";
    	$key = "";
    	
    	if (file_exists($filename)) {
    		return trim(file_get_contents($filename));
    	} else {
    		$this->writeKeyFile();
    		return $this->readKeyFile();
    	}   		
    }
    
    
    /**
     * Write a new ".crypto" keyfile
     * @throws CryptoIOException
     * @throws Exception
     * @return void
     */
    private function writeKeyFile()
    {    	
   		$filename = $this->cryptoPath . "/.crypto";
    	
    	if (!file_exists($filename)) {
	    	$key = $this->randomString(16, true);
	    	if (!@file_put_contents($filename, $key))
	    		throw new CryptoIOException("Error: Unable to write keyfile '{$filename}'! Make sure the path exists and is writable!");
	    	return;
    	} else {
    		throw new Exception("Error: Key file already exists!");
    		return;
    	}
    }
    
    
    /**
     * Read the .salt keyfile and return the salt
     * @return string
     */
    private function readSaltFile()
    {    	
    	$filename = $this->cryptoPath . "/.salt";
    	$salt = "";
    	 
    	if (file_exists($filename)) {
    		$salt = file_get_contents($filename);
    	} else {
    		$this->writeSaltFile();
    		return $this->readSaltFile();
    	}
    	return trim($salt);
    }
    
    
    /**
     * Write a new ".salt" keyfile
     * @throws CryptoIOException
     * @throws Exception
     * @return void
     */
    private function writeSaltFile()
    {    	
    	$filename = $this->cryptoPath . "/.salt";
    	 
    	if (!file_exists($filename)) {
    		$salt = $this->encrypt("SALTEDHASH64", 2);
    		if (!@file_put_contents($filename, $salt)) {
    			throw new CryptoIOException("Error: Unable to write saltfile '{$filename}'! Make sure the path exists and is writable!");
    		}
    	} else {
    		throw new Exception("Error: Saltfile already exists!");
    	}
    }
    
    
	/**
	 * Generates a random string
	 * @param int $len 
	 * 		Random string length
	 * @param boolean $complexity
	 * 		Using complexity adds .-!_ to the possible output (default false)
	 * @return string
	 */
	public function randomString($len, $complexity = false)
	{
		$possible = "";
		$str = "";
		
		if ($complexity)
			$possible = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789.-!_";
		else
			$possible = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
				
		while (strlen($str) < $len) {
			$str .= substr($possible, mt_rand() % (strlen($possible)), 1);
		}

		return $str;
	}
	
	
	/**
	 * Generates a crypto salted passwort hash
	 * @param string $plainPassword
	 * @return string
	 */
	public function saltedPassword($plainPassword)
	{		
		// Hash password with salt and SHA256 sum
		return hash("SHA256", trim($plainPassword) . $this->readSaltFile());
	}	
}


if (!class_exists('InvalidKeyException', false)) {
	class InvalidKeyException extends Exception
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

if (!class_exists('CryptoIOException', false)) {
	class CryptoIOException extends Exception
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