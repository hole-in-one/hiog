<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	index.php 2012/02/13 11:58:58 AM
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
		include('includes/mobiSetup.php');
		$conf = new Config('config.php');
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
require_once('includes/mobiModel.php');
require_once('includes/mobiView.php');
require_once('includes/mobiController.php');
require_once('includes/mobiFunctions.php');
require_once('views/header.html');


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
	case 'year':			$view = new view( 'year.html' );
										$view->set('output', '' );
	break;
	case 'month':			$view = new view( 'month.html' );
										$view->set('output', '' );
	break;
	case 'week':			$view = new view( 'week.html' );
										$view->set('output', '' );
	break;
	case 'day':				$view = new view( 'day.html' );
										$view->set('output', '' );
	break;
	case 'vouchers':	$view = new view( 'vouchers.html' );
										$one = $mobi->prizemoney(1);
										$two = $mobi->prizemoney(2);
										$total = $one + $two;
										$sponsor = $mobi->sponsor();
										$view->set('bonus', $sponsor->description);
										$view->set('value', $sponsor->amount);
										$view->set('link', $sponsor->link);   
										$view->set('hiopot', 'R ' . $one); 
										$view->set('twoclubpot', 'R ' . $two);
										$view->set('totalpot', 'R ' . $total);
										$view->set('output', '' );										
	break;
	case 'about':			$view = new view( 'about.html' );
										$view->set('output', '' );
	break;
	case 'rules':			$view = new view( 'rules.html' );
										$view->set('output', 'Tournament Rules' );
	break;
	case 'affiliated':$view = new view( 'affiliated.html' );
										$view->set('output', 'Player Registration' );
	break;
	case 'register':	$view = new view( 'register.html' );
										$view->set('output', $mobi->registerplayer($_REQUEST['saganumber']));
	break;
	case 'claim':			$view = new view( 'claim.html' );
										$view->set('output', $mobi->claim());
	break;
	case 'claimsave': $saved = $mobi->saveclaim($_REQUEST);

										if ($saved === true) 
										{
															$view = new view( 'claimsuccess.html' );
										} 
										elseif ($saved === false)
										{
										 					$view = new view( 'failed.html' );
										} else {
															$view = new view( 'claim.html' );
															$view->set('output', $saved);
										}
	break;
	case 'regsave': 	$saved = $mobi->saveplayer($_REQUEST);
										if ($saved === true) 
										{
															$view = new view( 'success.html' );
										} 
										elseif ($saved === false)
										{
										 					$view = new view( 'failed.html' );
										} else {
															$view = new view( 'register.html' );
															$view->set('output', $saved);
										}
	break;
	default:					$view = new view( 'home.html' );
}	


// Render our view
echo $view->render();

if ( $conf->keepLog ) 
{
		$page = ( empty($_SESSION['view']) ) ? 'home' : $_SESSION['view']; 
		mobiLog('DISPLAY ' . strtoupper($page) . ' PAGE' , 2);
}

?>