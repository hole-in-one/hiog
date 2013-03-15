<?php 
/**
*
* @package 			m.mg.co.za
* @version $Id:	css.php 2012/02/15 05:14:57 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description 	dynamic css
*
*/

header('Content-type: text/css'); 

if ( empty($conf) )
{
	require_once('../includes/mobiSetup.php');
	$conf = new Config('../config.php');
}
?>

body{	margin: 0; padding: 0;}
a {font-size:small; text-decoration:none}
.small storylink{font-size:small;}
.table_sub {font-size:smaller;}

ul {list-style-type: none;}
li {padding-left: 1EM;}

#formWrapper{margin-left: 1EM; color:<?php echo $conf->bodyFontColor; ?>; font-size:<?php echo $conf->bodyFontSize; ?>; font-family:<?php echo $conf->bodyFontFamily; ?>; background-color:<?php echo $conf->bodyBackgroundColor; ?>;}
#mast {height:<?php echo $conf->mastHeight; ?>; background-image:url(<?php echo '../images/' . $conf->mastBackgroundImage; ?>); background-repeat:repeat-x; margin:0EM 0EM 0EM 0EM; padding:0EM 0EM 0EM 0EM;}
#menu a {text-decoration:none; color:<?php echo $conf->menuLinkColor; ?>; font-size:<?php echo $conf->menuLinkFontSize; ?>; }
#menu {font-size:<?php echo $conf->menuFontSize; ?>; text-decoration:none; border-bottom-width:<?php echo $conf->menuBorderBottomWidth; ?>; border-bottom-style: solid; border-bottom-color:<?php echo $conf->menuBorderBottomColor; ?>; padding-bottom:<?php echo $conf->menuPaddingBottom; ?>; padding-left:<?php echo $conf->menuPaddingLeft; ?>;}
#output{text-align: left;}
#formDiv{padding: 1EM;}
.heading {color:<?php echo $conf->headingFontColor; ?>; font-family:<?php echo $conf->headingFontFamily; ?>; font-size:<?php echo $conf->headingFontSize; ?>; font-weight:<?php echo $conf->headingFontWeight; ?>; padding-top:<?php echo $conf->headingPaddingTop; ?>; padding-right:<?php echo $conf->headingPaddingRight; ?>; padding-bottom:<?php echo $conf->headingPaddingBottom; ?>; padding-left:<?php echo $conf->headingPaddingLeft; ?>;}
#footer {	padding-top:<?php echo $conf->footerPaddingTop; ?>; border-top-width:<?php echo $conf->footerBorderTopWidth; ?>; border-top-style: <?php echo $conf->footerBorderTopStyle; ?>; border-top-color:<?php echo $conf->footerBorderTopColor; ?>; }