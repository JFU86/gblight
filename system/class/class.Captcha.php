<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Captcha
{
	/**
	 * Generiert einen Random-String
	 * @param $len Integer String-Length
	 * @return string
	 */
	public function randomString($len)
	{ 		
		//Der String $possible enthält alle Zeichen, die verwendet werden sollen 
		$possible = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789"; 
		$str = ""; 
		
		while (mb_strlen($str) < $len) { 
			$str .= substr($possible, (rand() % (strlen($possible))), 1); 
		}
		 
		return $str;
	}
}
?>