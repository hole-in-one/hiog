<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	Memcacher.php 2012/02/13 02:17:33 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	Minimal Memcached class
*
*/
	

class Memcacher 
{
	protected $memcached;
	protected $servers;
	
	public function __construct($config) 
	{
		$this->memcached = new Memcached;
		$servers = explode(",", $config);

		foreach($servers as $server) 
		{
				$this->memcached->addServer($server, 11211);
		}
	}
	
	public function add($key, $data, $ttl=0) 
	{
		$this->memcached->set($key, $data, $ttl);
	}
	
	public function clear($key) 
	{
		$this->memcached->delete($key);
	}
	
	public function get($key) 
	{
		return $this->memcached->get($key);
	}
}
?>