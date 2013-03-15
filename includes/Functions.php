<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	mobiFunctions.php 2012/02/15 05:03:23 PM
* @copyright		(c) 2011 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	general functions
*
*/


	/**
	* Access 	- public
	* Desc 		- get IP Address of client device
	* Params 	- none
	*/
	function getIP()
	{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))   					// check if from share internet
			{
			  	$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   // check if pass from proxy
			{
			  	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{	
			  	$ip=$_SERVER['REMOTE_ADDR'];
			}
			$_SESSION['device']['ip'] = $ip;

			return $ip;
	}


	/**
	* Access 	- public
	* Desc 		- keeps a log for debugging
	* Params 	- $action - perfomed
	* Params 	- $type 1=debug system errors 2=general interface logging
	*/
	function mobiLog ( $action, $type )
	{
			$time = date("d-m-Y H:i:s");
			$filename = str_replace("includes", '', dirname(__FILE__));
			$filename .='/log/log.csv';

			$_SESSION['device']['ip'] = (empty($_SESSION['device']['ip'] ) ) ? getIP() : $_SESSION['device']['ip'];
			$id = ( empty($_SESSION['id'] ) ) ? 'unknown' : $_SESSION['id'];
			$vendor = ( empty($_SESSION['vendor'] ) ) ? 'unknown' : $_SESSION['vendor'];
			$array = array($_SESSION['device']['ip'], $time, $action, $type, $id, $vendor);
	
			if (is_writable($filename))
			{
								if (($handle = fopen($filename, "a")) !== FALSE) 
								{
									fputcsv($handle , $array);
								}
							 	fclose($handle);
			} else {
			    			die('Please CHMOD log file to be writable');
			}
	}


	/**
	* Access 	- public
	* Desc 		- clean and validate form input
	* params 	- $var form data
	* params 	- $type of check to perform
	*/ 
	function clean($var, $type)
	{ 
			$var = str_replace("\r\n", " ", $var);
			$var = str_replace("\n", " ", $var);
			$var = str_replace("\r", " ", $var);
			$var = str_replace("\t", " ", $var);
			$var = str_replace("  ", " ", $var);
			$var = trim($var);
	    $var = stripslashes($var);
			$var = htmlspecialchars($var);
	
			switch ( $type ) 
			{ 
					case 'int': 				$var = (int) $var; break; 													// integer
					case 'txt': 				$var = htmlentities ($var, ENT_NOQUOTES ); break;		// trim string, no HTML allowed, plain text 
					case 'alpha': 			$size = strlen($var); $text = ''; for($x=0;$x<$size;$x++) { if( ctype_alpha($var[$x]) ){ $text .= $var[$x]; } }	$var = $text; break;		// alphabetic in the current locale drop spaces
					case 'alphaspace': 	$size = strlen($var); $text = ''; for($x=0;$x<$size;$x++) { if ($var[$x] == ' ') { $text .= ' '; } else { if( ctype_alpha($var[$x]) ){ $text .= $var[$x]; } } }	$var = $text; break;		// alphabetic in the current locale preserve spaces
					case 'upword': 			$var = ucwords(strtolower($var)); break;						// trim string, upper case words 
					case 'ucfirst': 		$var = ucfirst(strtolower($var)); break; 						// trim string, upper case first word 
					case 'lower': 			$var = strtolower($var); break;											// trim string, lower case words 
					case 'urlen': 			$var = urlencode($var); break; 											// trim string, url encoded 
					case 'urlde': 			$var = urldecode($var); break; 											// trim string, url decoded 
					case 'email':				$var = strtolower(str_replace(" ", '', $var)); 
															if( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)¦(^\.)/', $var)) OR (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$var)) ) 
															{
																$host = explode('@', $var);
																if(checkdnsrr($host[1].'.', 'MX') ) return $var;
																if(checkdnsrr($host[1].'.', 'A') ) return $var;
																if(checkdnsrr($host[1].'.', 'CNAME') ) return $var;
															}
															return false;
					 										break;
					case 'pin': 				if ((strlen($var)!= 5) || (ctype_digit($var)!=true) ) {return false;}	break;
					case 'rand':				if ((strlen($var) > 4) || (ctype_digit($var)!=true) ) {return false;}	break;
					case 'cent':				if ((strlen($var) > 2) || (ctype_digit($var)!=true) ) {return false;}	break;
					case 'id':					if ( ctype_alnum($var)!=true ) {return false;}	break;	// id number
					case 'branch':			if ((strlen($var) > 6) || (ctype_digit($var)!=true) ) {return false;}	break;
					case 'card':				if ((strlen($var) > 4) || (ctype_digit($var)!=true) ) {return false;}	break;
			}
			$var = str_replace("  ", " ", $var);
			return $var;
	}


	/**
	* Access 	- public
	* Desc 		- micro time keeping
	* params 	- $start time for duration calc
	*/  
	function stamp($start=false)
	{
			$mtime = microtime(); 
			$mtime = explode(" ",$mtime); 
			$mtime = $mtime[1] + $mtime[0]; 
			if ($start) $mtime = ($mtime - $start); 
			return $mtime;
	}

?>