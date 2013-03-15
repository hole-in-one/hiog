<?php
	function getDeviceDetails()
	{

		$dir = str_replace("includes", '', dirname(__FILE__));
		require 'Mobi/Mtld/DA/Api.php';
		$tree = Mobi_Mtld_DA_Api::getTreeFromFile($dir . 'includes/Mobi/Sample.json');
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		$res = array();
		try
		{
			$res['vendor'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'vendor');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['vendor'] = 'Unknown ';
		}
		
		try
		{
			$res['model'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'model');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['model'] = "Unknown ";
		}
		
		try
		{
			$res['displayWidth'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'displayWidth');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['displayWidth'] = "Unknown";
		}
		
		try
		{
			$res['displayHeight'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'displayHeight');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['displayHeight'] = "Unknown";
		}
		
		try
		{
			$res['displayColorDepth'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'displayColorDepth');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['displayColorDepth'] = "Unknown";
		}
		
		try
		{
			$res['mobileDevice'] = Mobi_Mtld_DA_Api::getProperty($tree, $ua, 'mobileDevice');
		}
		catch (Mobi_Mtld_Da_Exception_InvalidPropertyException $e)
		{
			$res['mobileDevice'] = false;
		}
		
		$res['ua'] = $_SERVER['HTTP_USER_AGENT'];
		
		$res['ip'] = $_SERVER['REMOTE_ADDR'];
		
		return $res;
	}
?>