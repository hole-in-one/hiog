<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	mobiView.php 2012/02/15 04:57:46 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	mini template class
*
*/

class  view
{

    /**
    * define our class objects
    * public object: accessable outside of class
    * private object: only accessable inside class
    */
    private $pageVars = array();
    private $template;
 

		/**
		* access 	- public
		* desc 		- construct template class
		* params 	- $template name
		*/ 
    public function __construct( $template )
    {
        // We setup our action directory
        $actionsDirectory = str_replace("includes", '', dirname(__FILE__));
        $actionsDirectory .= 'views/';
        $this->template = $actionsDirectory . $template;
    }

 
 		/**
		* access 	- public
		* desc 		- Create method to set variables
		* params 	- $var iable name
		* params 	- $val ue
		*/ 
    public function set( $var, $val )
    {
        $this->pageVars[$var] = $val;
    }
 
 
		/**
		* access 	- public
		* desc 		- Render page after populating variables
		* params 	- $var iable name
		* params 	- $val ue
		*/ 
    public function render()
    {
        // Extract pageVars becomes normal $Vars
        extract($this->pageVars);
 
        // Start output buffer and return buffer with template
        ob_start();
        require($this->template);
        return ob_get_clean();
    }
}
?>