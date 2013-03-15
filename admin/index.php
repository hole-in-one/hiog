<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	cashhbook.php 2012/02/13 11:58:58 AM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	http://ready.mobi compliant site
*
*/



// Start the session
session_start();


// Mobile browsers should not cache pages
header("Content-type: text/html; charset=utf-8");
//header ("Cache-Control: max-age=0 ");


// Runtime configuration
if ( empty($conf) )
{
		$conf = new stdClass();

	    $fh = fopen('../config.php', 'r' );
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
									$key = trim(str_replace(' ', '', $found[1]));
		        			$conf->$key = trim(str_replace(' ', '', $found[2]));
							}
					}
		      $ofset++;
	    }
	    fclose( $fh );
}



// Set timezone
date_default_timezone_set( $conf->timeZone );	


// Set error output
error_reporting(E_ALL);
ini_set("display_errors", true); //$conf->debugMe); 
ini_set("log_errors", $conf->debugMe); 


// Absolute Root
$root = '';


// Includes
require_once('../includes/mobiModel.php');
require_once('../includes/mobiView.php');
require_once('../includes/mobiController.php');
require_once('../includes/mobiFunctions.php');
require_once('../views/adminheader.html');


// Start controller
$mobi = new Controller($conf);


// Navigation
$_SESSION['view'] = '';

if ( isset($_REQUEST['view']) )
{
		$_SESSION['view'] = $_REQUEST['view'];
}
if ( isset($_REQUEST['load']) )
{
		$_SESSION['view'] = 'load';
}


// Main interface logic
switch ( $_SESSION['view'] )
{

	case 'sponsors':	$view = new view( 'sponsors.html' );
										$view->set('output', $mobi->sponsors());
	break;
	case 'sponsorsave': $view = new view( 'sponsors.html' );
										$saved = $mobi->savesponsor($_REQUEST);
										$view->set('output', $mobi->sponsors());
	break;
	case 'cashbook':	$view = new view( 'cashbook.html' );
										$view->set('output', $mobi->cashbook());
	break;
	case 'transsave': $view = new view( 'cashbook.html' );
										$saved = $mobi->savetrans($_REQUEST);
										$view->set('output', $mobi->cashbook());
	break;
	case 'players':		$view = new view( 'players.html' );
										$view->set('output', $mobi->players());
	break;
	case 'claims':		$view = new view( 'claims.html' );
										$view->set('output', $mobi->claims());
	break;
	case 'issued':		$view = new view( 'issued.html' );
										$view->set('output', $mobi->issued());
	break;	
	default:					$view = new view( 'adminhome.html' );
										$view->set('navigate', '<a href="index.php?view=section&urlid=news-national">SA NEWS</a> | <a href="index.php?view=section&urlid=news-africa">AFRICA</a> | <a href="index.php?view=section&urlid=opinion">OPINION</a> | <a href="index.php?view=search">SEARCH</a>');
}	


// Render our view
echo $view->render();

if ( $conf->keepLog ) 
{
		$page = ( empty($_SESSION['view']) ) ? 'home' : $_SESSION['view']; 
		mobiLog('DISPLAY ' . strtoupper($page) . ' PAGE' , 2);
}

?>