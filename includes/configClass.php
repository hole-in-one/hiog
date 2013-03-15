<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	mobiSetup.php 2012/02/15 05:07:02 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	read and write configuration parameters to file
*
*/

class Config
{

    /**
    * define our class objects
    * public object: accessable outside of class
    * private object: only accessable inside class
    */
	  private $configFile = 'config.php';
	  public $param = array();


		/**
		* access	- public
		* desc		- construct config class
		* params	- $file with config settings
		*/
	  function __construct( $file ) 
	  { 
				if ( !is_writable($file) )
				{
						die('Startup error: please CHMod ' . $file . ' to be writable');
				}
		  	$this->configFile = $file;
		  	$this->parse();

				// Check all params are set
				foreach( $this->param as $name => $value)
				{
					if ( empty($value) AND $value != 0 )
					{
						die('Startup error: ' . $name . ' not set in config!');
					}  
				}
	  }


		/**
		* access 	- public
		* desc 		- gets the value of a config parameter
		* params 	- $name of param
		*/ 
	  function __get($name) 
	  { 
	  	return $this->param[ $name ]; 
	  }
	

		/**
		* access 	- public
		* desc 		- sets the value of a config parameter
		* params 	- $name of param
		* params 	- $value of param		
		*/ 
	  function __set($name,$value) 
	  { 
	  	$this->param[ $name ] = $value; 
	  }


		/**
		* access 	- public
		* desc 		- read config file and sets $param array to parsed content
		* params 	- none
		*/ 
	  function parse()
	  {
	    $fh = fopen( $this->configFile, 'r' );
	    $ofset = 1;
	
	    while( $l = fgets( $fh ) )
	    { 
					if (strlen($l) > 5 AND $ofset > 12 )
					{	
							$pos = strpos($l, "//");
							if($pos === false OR $pos > 2 )
							{
									$l = str_replace(';', '', $l); 
									preg_match( '/^(.*?)=(.*?)$/', $l, $found );
		        			$this->param[ trim(str_replace(' ', '', $found[1])) ] = trim(str_replace(' ', '', $found[2]));
							}
					}
		      $ofset++;
	    }
	    fclose( $fh );
	  }


		/**
		* access 	- public
		* desc 		- save $param array to file preserving comments
		* params 	- $backup option save copy of file
		*/ 
	  function save( $backup = false )
	  {
	    $nf = '';
	    $ofset = 1;
	
	    $fh = fopen( $this->configFile, 'r' );
	
	    while( $l = fgets( $fh ) )
	    {
					$paramline = false;
	
					if (strlen($l) > 5 AND $ofset > 12 )
					{	
							$pos = strpos($l, "//");
							if($pos === false OR $pos > 2 )
							{
									$paramline = true;
							}
					}
		      if ( $paramline )
		      {
		        preg_match( '/^(.*?)=(.*?)$/', $l, $found );
	  	      $nf .= $found[1]."=".$this->param[$found[1]]."\n";
		      }
		      else
		      {
		        $nf .= $l;
		      }
		      $ofset++;
	    }
	    fclose( $fh );

			if ( $backup )
			{
	    	copy( $this->configFile, $this->configFile.'.bak' );
	    }

	    $fh = fopen( $this->configFile, 'w' );
	    fwrite( $fh, $nf );
	    fclose( $fh );
	    return true;
	  }


		/**
		* access 	- public
		* desc 		- remove a param from file
		* params 	- $backup option save copy of file
		*/ 
	  function remove($name)
	  {
	    $nf = '';
	    $ofset = 1;
	
	    $fh = fopen( $this->configFile, 'r' );
	
	    while( $l = fgets( $fh ) )
	    {
					$paramline = false;
	
					if (strlen($l) > 5 AND $ofset > 12 )
					{	
							$pos = strpos($l, "//");
							if($pos === false OR $pos > 2 )
							{
									$paramline = true;
							}
					}
		      if ( $paramline )
		      {
		        preg_match( '/^(.*?)=(.*?)$/', $l, $found );
						if ($found[1] == $name)
						{	
											unset($this->param[$name]);
						} else {
	  	      					$nf .= $found[1]."=".$this->param[$found[1]]."\n";
	  	      }
		      }
		      else
		      {
		        $nf .= $l;
		      }
		      $ofset++;
	    }
	    fclose( $fh );

	    $fh = fopen( $this->configFile, 'w' );
	    fwrite( $fh, $nf );
	    fclose( $fh );
	    return true;
	  }


		/**
		* access 	- public
		* desc 		- add a param to file
		* params 	- $name of param
		* params 	- $value of param		
		*/ 
	  function add($name, $value)
	  {
	    $fh = fopen( $this->configFile, 'a' );
	    $nf = $name."=".$value."\n";
	    fwrite( $fh, $nf );
	    fclose( $fh );
	    return true;
	  }
}

?>