<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	mobiModel.php 2012/02/13 02:17:33 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	Model type class for for mobi
*
*/

class Model
{
	protected $debug;
	protected $config;
	
	public function __construct($config) 
	{
		$this->config = $config;
		$this->debug = $config->debugMe;	
	}


	/**
	* Issued Vouchers
	*/	
	function getIssued()
	{
			$query = 'SELECT * FROM vouchers ORDER BY serial DESC;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	
	/**
	* Registered Players
	*/	
	function getClaims()
	{
			$query = 'SELECT * FROM claims ORDER BY id DESC;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	
	/**
	* Registered Players
	*/	
	function getPlayers()
	{
			$query = 'SELECT * FROM players ORDER BY id DESC;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	
	/**
	* Sponsor
	*/	
	function sponsor()
	{
			$query = 'SELECT * FROM sponsors WHERE end > ' . time() . ' ORDER BY id DESC LIMIT 1;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}


	/**
	* Sponsors
	*/	
	function getSponsors()
	{
			$query = 'SELECT * FROM sponsors ORDER BY id DESC;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	
	/**
	* Prizemoney
	*/	
	function prizeMoney($strokes)
	{
			$query = 'SELECT SUM(amount) - (SELECT SUM(amount) FROM funds WHERE strokes = ' . $strokes . ' AND debit = false)
								FROM funds WHERE strokes = ' . $strokes . ' AND debit = true;';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
			    while ($row = $result->fetch_array()) 
			    {
			        return $row[0];
			    }		
					$result->free();
					$mysqli->close();					
			}



			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}


	/**
	* Save sponsor
	*/	
	function saveSponsor($data)
	{
			$query = 'INSERT INTO sponsors (`start`, `end`, `description`, `amount`, `link`)
								VALUES ("' . $data['from'] . '", "' . $data['to'] . '", "' . $data['description'] . '", "' . $data['amount'] . '", "' . $data['link'] . '");';			

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	


			if ($result = $mysqli->query($query))
			{
				return true;
			} 


			/**
			* Oops
			*/	
			return false;
	}
	
	
	/**
	* Save transaction
	*/	
	function saveTrans($data)
	{
			$query = 'INSERT INTO funds (`date`, `debit`, `description`, `amount`, `strokes`)
								VALUES ("' . $data['date'] . '", "' . $data['debit'] . '", "' . $data['description'] . '", "' . $data['amount'] . '", "' . $data['strokes'] . '");';			

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	


			if ($result = $mysqli->query($query))
			{
				return true;
			} 


			/**
			* Oops
			*/	
			return false;
	}


	/**
	* Cashbook
	*/	
	function getCashbook($days)
	{
			$limit = time() - ($days * (60 * 60 * 24));
			$query = 'SELECT * FROM funds WHERE date > ' .  $limit . ';';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
					$data = false;
			
					if ($mysqli->warning_count) 								// Catch cluster warnings
					{
							if ($this->debug) echo $mysqli->get_warnings;
			   			die('<br>E204: Service unavailable');
					}
			
			    while ($row = $result->fetch_object()) 
			    {
			        $data[] = $row;
			    }		
					$result->free();
					$mysqli->close();
			
					return $data;
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	

	/**
	* Issue a Voucher
	*/	
	function issueVoucher($days, $from, $to, $player, $type)
	{
			$query = 'INSERT INTO vouchers (`days`, `from`, `to`, `playerid`, `twoclub`)
					VALUES (' . $days . ', ' . $from . ', ' . $to . ', ' . $player . ', ' . $type . ');';			

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
				return $mysqli->insert_id;
			} 


			/**
			* Oops
			*/	
			return false;
		
	}


	/**
	* Check voucher
	*/	
	function validateVoucher($serial)
	{
			$query = 'SELECT count(*) FROM vouchers WHERE serial = ' . $serial . ';';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
			    if ($row = $result->fetch_array()) 
			    {
			        return $row[0];
			    }		
					$result->free();
					$mysqli->close();					
			}

	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	
	/**
	* Check Player is registered
	*/	
	function checkPlayer($msisdn)
	{
			$query = 'SELECT id, name FROM players WHERE msisdn = ' . $msisdn . ';';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
			    if ($row = $result->fetch_array()) 
			    {
			        return $row;
			    }		
					$result->free();
					$mysqli->close();					
			}


	
			/**
			* Ok - no data exist
			* Have the controller switch to the 404 view
			*/	
			return false;
	}
	
	

	/**
	* Save registered player
	*/	
	function savePlayer($player)
	{
			$query = 'INSERT INTO players (`name`, `club`, `email`, `msisdn`, `sex`, `handicap`, `joined`, `saga`)
								VALUES ("' . $player['name'] . '", "' . $player['club'] . '", "' . $player['email'] . '", "' . $player['msisdn'] . '", "' . $player['sex'] . '", "' . $player['handicap'] . '", ' . time() . ', ' . $player['id'] . ');';			

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
				return true;
			} 


			/**
			* Oops
			*/	
			return false;
	}


	/**
	* Save claim
	*/	
	function saveClaim($claim)
	{
			$query = 'INSERT INTO claims (`voucher`, `club`, `captain`, `twoclub`, `hole`, `time`, `tod`, `success`, `value`)
								VALUES ("' . $claim['voucher'] . '", "' . $claim['club'] . '", "' . $claim['captain'] . '", ' . $claim['twoclub'] . ', ' . $claim['hole'] . ', ' . time() . ', "' . $claim['time'] . '", 0, "' . $claim['value'] . '");';		

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
				return $mysqli->insert_id;
			} 


			/**
			* Oops
			*/	
			return false;

	}


	/**
	* Fetch SAGA Player Data
	*/	
	function getPlayer($sagaId)
	{
			$query = 'SELECT * FROM handicaps WHERE id = ' . $sagaId . ';';

			try { 
												    		$mysqli = new mysqli($this->config->mobiHost, $this->config->mobiUser, $this->config->mobiPwrd	, $this->config->mobiDb);
												    		if ($mysqli->connect_errno) 
																{
																		if ($this->debug) echo $mysqli->connect_error;
																		die('<br>E202: Service unavailable');
																}
			} catch (Exception $e) {
			    											die('<br>E201: Service unavailable');
			}	

			if ($result = $mysqli->query($query))
			{
			    if ($row = $result->fetch_array()) 
			    {
			        return $row;
			    }		
					$result->free();
					$mysqli->close();					
			}


			/**
			* Oops
			*/	
			return false;

	}
		
		
}

?>