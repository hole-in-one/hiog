<?php
/**
*
* @package 			m.mg.co.za
* @version $Id:	mobiController.php 2012/02/15 02:37:43 PM
* @copyright		(c) 2012 Mail & Guardian
* @link					http://m.mg.co.za/
* @author				R P du Plessis <renierd@mg.co.za>
* @description	manage vistors
*
*/

class controller
{

		/**
		* define our class objects
		* public object: accessable outside of class
		* private object: only accessable inside class
		*/
		private $debug = false;
		private $logging = true;
		private $stats = false;	
		private $lang;
		private $expire;
		private $path;
		private $config;	
			

  /**
  * access 	- public
  * desc 		- constructor with our config settings
  * param 	- see top of index.php
  */
  public function __construct($config)
	{
			error_reporting(E_ALL);
			ini_set("display_errors", 1); //$config->debugMe); 
			ini_set("log_errors", 1); //$config->debugMe); 
	
			$this->debug 		= $config->debugMe;
			$this->logging 	= $config->keepLog;
			$this->stats 		= $config->keepStats;
			$this->lang 		= $config->langDefault;
			$this->path 		= $config->cookiePath;
			$this->expire 	= $config->cookieExpire;
			$this->config		= $config; 

			if ( empty($_SESSION['id']) )
			{
					$_SESSION['id'] = $this->create_session();
			}
	}


	/**
	* access 	- private
	* desc 		- create new session
	* param 	- none
	*/
	private function create_session ()
	{
		$dir = str_replace("includes", '', dirname(__FILE__));
		require_once($dir . 'includes/deviceDetails.php');

		$_SESSION = array(); 
		$_SESSION['device'] = getDeviceDetails();
		$_SESSION['time'] = $_SESSION['totaltime'] = time();
		$_SESSION['hits']	= $_SESSION['totalhits'] = 1; 
		$_SESSION['referer'] = (empty($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_HOST"] : $_SERVER["HTTP_REFERER"];
	
		$id = session_id();

		if ($this->logging) mobiLog( 'VISITOR->CREATE_SESSION id : ' . $id . ' for visitor from: ' . $_SESSION['referer'], 2);
		if ($this->debug) mobiLog( 'VISITOR->CREATE_SESSION atlas : ' . $_SESSION['device']['model'], 1);
		return $id;
	}


	/**
	* access 	- public
	* desc 		- Issued Vouchers
	*/
	public function issued() 
	{
			$Model = new Model($this->config);
			$rows = $Model->getIssued();
			$output = '';

			foreach ($rows as $data)
			{
				$output .= '<tr>
											<td>' . $data->serial . '</td>
											<td>' . $data->days . '</td>
											<td>' . date("d-m-Y", $data->from) . '</td>
											<td>' . date("d-m-Y", $data->to) . '</td>
											<td>' . $data->playerid . '</td>
											<td>' . $data->twoclub . '</td>';
				$output .= '</tr>';
			}
			return $output;
	}


	/**
	* access 	- public
	* desc 		- View Claims
	*/
	public function claims() 
	{
			$Model = new Model($this->config);
			$rows = $Model->getClaims();
			$output = '';

			foreach ($rows as $data)
			{
				$tod = ($data->tod) ? 'AM' : 'PM';
				$output .= '<tr>
											<td>' . $data->voucher . '</td>
											<td>' . $data->club . '</td>
											<td>' . $data->captain . '</td>
											<td>' . $data->hole . '</td>
											<td>' . date("d-m-Y", $data->time) . '</td>
											<td>' . $tod . '</td>
											<td>' . $data->success . '</td>
											<td>' . $data->value . '</td>';
				$output .= '</tr>';
			}
			return $output;
	}


	/**
	* access 	- public
	* desc 		- View Registered Players
	* param 	- urlid
	*/
	public function players() 
	{
			$Model = new Model($this->config);
			$rows = $Model->getPlayers();
			$output = '';
			
			foreach ($rows as $data)
			{
				$sex = ($data->sex) ? 'M' : 'F';
				$output .= '<tr>
											<td>' . $data->id . '</td>
											<td>' . $data->name . '</td>
											<td>' . $data->club . '</td>
											<td>' . $data->email . '</td>
											<td>' . $data->msisdn . '</td>
											<td>' . $sex . '</td>
											<td>' . $data->handicap . '</td>
											<td>' . date("d-m-Y", $data->joined) . '</td>
											<td>' . $data->saga . '</td>';
				$output .= '</tr>';
			}
			return $output;
	}


	/**
	* access 	- public
	* desc 		- View Sponsor
	* param 	- urlid
	*/
	public function sponsor() 
	{
			$Model = new Model($this->config);
			$row = $Model->sponsor();

			return $row[0];
	}
	

	/**
	* access 	- public
	* desc 		- View Bonus Prize Sponsors
	* param 	- urlid
	*/
	public function sponsors() 
	{
			$Model = new Model($this->config);
			$rows = $Model->getSponsors();
			$output = '';
			
			foreach ($rows as $data)
			{
				$output .= '<tr>
											<td>' . date("d-m-Y", $data->start) . '</td>
											<td>' . date("d-m-Y", $data->end) . '</td>
											<td>' . $data->description . '</td>
											<td>' . $data->link . '</td>
											<td align="right">' . $data->amount . '</td>';
				$output .= '</tr>';
			}
			return $output;
	}


	/**
	* access 	- public
	* desc 		- save Sponsor
	*/
	public function savesponsor($data)
	{

		$error = false;

		foreach($data AS $var => $value)
		{
			$data[$var] = trim($value);
		}
		
		if (empty($data['from']) OR empty($data['to']) OR empty($data['amount']) OR $data['amount'] == 0 OR empty($data['link']) OR empty($data['description']))
		{
							$msg = '<div class="output" style="color:red;">Invalid Sponsor</div>';
							$error = true;
							echo "here<pre>"; print_r($data);
		} else {
							$data['from'] = strtotime($data['from']);
							$data['to'] = strtotime($data['to']);

							$Model = new Model($this->config);
							$result = $Model->saveSponsor($data);
							if (!$result) return false;
		}
	}


	/**
	* access 	- public
	* desc 		- save transaction
	*/
	public function savetrans($data)
	{

		$error = false;

		foreach($data AS $var => $value)
		{
			$data[$var] = trim($value);
		}
		
		if (empty($data['strokes']) OR empty($data['amount']) OR $data['amount'] == 0 OR empty($data['date']) OR empty($data['debit']) OR empty($data['description']))
		{
							$msg = '<div class="output" style="color:red;">Invalid Transactio</div>';
							$error = true;
		} else {
							$data['date'] = strtotime($data['date']);
							if (strtolower($data['debit']) == 'dt') $data['debit'] = 1;

							$Model = new Model($this->config);
							$result = $Model->saveTrans($data);
							if (!$result) return false;
		}
	}


	/**
	* access 	- public
	* desc 		- View Cashbook
	* param 	- urlid
	*/
	public function prizemoney($strokes) 
	{
			$Model = new Model($this->config);
			$value = $Model->prizeMoney($strokes);

			return $value;
	}
	

	/**
	* access 	- public
	* desc 		- View Cashbook
	* param 	- urlid
	*/
	public function cashbook() 
	{
			$Model = new Model($this->config);
			$rows = $Model->getCashbook($days = 15);
			$output = '';
			$sum = 0;
			
			foreach ($rows as $data)
			{
				$sum = ($data->debit == 0 ) ? $sum - $data->amount : $sum + $data->amount ;
				$debit = ($data->debit == 0 ) ? 'Cr' : 'Dt';
				$output .= '<tr>
											<td>' . $data->strokes . '</td>
											<td>' . date("d-m-Y", $data->date) . '</td>
											<td>' . $data->description . '</td>
											<td>' . $debit . '</td>
											<td align="right">' . $data->amount . '</td>
											<td align="right">' . $sum . '</td>';
				$output .= '</tr>';
			}
			return $output;
	}
				

	/**
	* access 	- public
	* desc 		- player
	* param 	- urlid
	*/
	public function registerplayer($sagaId) 
	{
				$Model = new Model($this->config);
				$player = false;
				$sagaId = trim($sagaId);
				if (strlen($sagaId) > 5) $player = $Model->getPlayer($sagaId);

				$sex = '<div class="form-labels"><input type="radio" name="sex" checked="checked" value="male">Male&nbsp;<input type="radio" name="sex" value="female">Female</div>';
				$handicap = '<div class="form-labels"><input type="text" name="handicap" size="5" maxlength="2" />Handicap</div>';
				$contact = '<div class="form-labels"><input type="text" name="msisdn" size="15" maxlength="10" />Cellphone Number</div>
									<div class="form-labels"><input type="text" name="email" size="25" maxlength="30" />Email Address</div>';
				$name = '<div class="form-labels"><input type="text" name="name" size="25" maxlength="30" />Player Name</div>';
				$club = '<div class="form-labels"><input type="text" name="club" size="25" maxlength="30" />Nearest Club</div>';
				$saga = '<input type="hidden" name="id" value="0">';

				if($player) 
				{
					$sex = '<div class="form-labels"><input type="radio" name="sex" ';
					if ($player['sex'] == 1) $sex .= 'checked="checked" ';
					$sex .= 'value="male">Male&nbsp;<input type="radio" name="sex" ';
					if ($player['sex'] == 0) $sex .= 'checked="checked" ';
					$sex .= 'value="female">Female</div>';
					$handicap = '<div class="form-labels"><input type="text" name="handicap" size="5" maxlength="2" value = "' . $player['handicap'] . '"/>Handicap</div>';        
					$name = '<div class="form-labels"><input type="text" name="name" size="25" maxlength="30" value = "' . $player['name'] . '"/>Player Name</div>';
					$club = '<div class="form-labels"><input type="text" name="club" size="25" maxlength="30" value = "' . $player['club'] . '"/>Home Club</div>';
					$saga = '<input type="hidden" name="id" value="' . $player['id'] . '">';
				}
				$terms = '<div class="form-labels">I have read and accept the competition<br>rules, terms and conditions&nbsp;';
				$terms .= '<input type="radio" name="terms" value="yes">Yes&nbsp;<input type="radio" name="terms" value="no">No<br></div>';
				
				$output = $sex . $handicap . $contact . $name . $club . $terms . $saga;

				return $output;
	}


	/**
	* access 	- public
	* desc 		- save player details
	*/
	public function saveplayer($player)
	{
		$error = false;

		foreach($player AS $var => $value)
		{
			$player[$var] = trim($value);
		}
		
		if (empty($player['handicap']) OR $player['handicap'] < 0 OR $player['handicap'] > 40)
		{
			$msg = '<div class="output" style="color:red;">Invalid Handicap</div>';
			$error = true;
		}

		if (empty($player['msisdn']) OR $player['msisdn'][0] !== '0' OR strlen($player['msisdn']) <> 10)
		{
			$msg = '<div class="output" style="color:red;">Invalid Phone Number</div>';
			$error = true;
		}		

		if(empty($player['email']) OR !filter_var($player['email'], FILTER_VALIDATE_EMAIL))
		{
			$msg = '<div class="output" style="color:red;">Invalid Email Address</div>';
			$error = true;
		}		

		if (empty($player['name']) OR strlen($player['name']) < 5)
		{
			$msg = '<div class="output" style="color:red;">Invalid Player Name</div>';
			$error = true;
		}

		if (empty($player['club']) OR strlen($player['club']) < 5)
		{
			$msg = '<div class="output" style="color:red;">Invalid Club Name</div>';
			$error = true;
		}

		if ( !isset($player['terms']) OR $player['terms'] !== 'yes' )
		{
			$msg = '<div class="output" style="color:red;">You have to accept the T&C\'s to participate</div>';
			$error = true;
		}

		if (!$error)
		{		
							$player['sex'] = ($player['sex'] == 'male') ? 1 : 0;
							$player['club'] = ucwords($player['club']);
							$player['name'] = ucwords($player['name']);

							$Model = new Model($this->config);
							$result = $Model->savePlayer($player);
							if (!$result) return false;
		} else {
							$contact = '<div class="form-labels"><input type="text" name="msisdn" size="15" maxlength="10" value = "' . $player['msisdn'] . '"/>Cellphone Number</div>
												<div class="form-labels"><input type="text" name="email" size="25" maxlength="30" value = "' . $player['email'] . '"/>Email Address</div>';
							$sex = '<div class="form-labels"><input type="radio" name="sex" ';
							if ($player['sex'] == 'male') $sex .= 'checked="checked" ';
							$sex .= 'value="male">Male&nbsp;<input type="radio" name="sex" ';
							if ($player['sex'] == 'female') $sex .= 'checked="checked" ';
							$sex .= 'value="female">Female</div>';
							$handicap = '<div class="form-labels"><input type="text" name="handicap" size="5" maxlength="2" value = "' . $player['handicap'] . '"/>Handicap</div>';        
							$name = '<div class="form-labels"><input type="text" name="name" size="25" maxlength="30" value = "' . $player['name'] . '"/>Player Name</div>';
							$club = '<div class="form-labels"><input type="text" name="club" size="25" maxlength="30" value = "' . $player['club'] . '"/>Home Club</div>';
							$terms = '<div class="form-labels">I have read and accept the competition<br>rules, terms and conditions&nbsp;';
							$terms .= '<input type="radio" name="terms" value="yes" ';
							if (isset($player['terms']) AND $player['terms'] == 'yes') $terms .= 'checked="checked"';
							$terms .= '>Yes&nbsp;<input type="radio" name="terms" value="no" ';
							if (isset($player['terms']) AND $player['terms'] == 'no') $terms .= 'checked="checked"';
							$terms .= '>No<br></div>';
							$saga = '<input type="hidden" name="id" value="' . $player['id'] . '">';
							$output = $msg . $sex . $handicap . $contact . $name . $club . $terms . $saga;
			
							return $output;				
		}
		return true;
	}


	/**
	* access 	- public
	* desc 		- claim
	* param 	- none
	*/
	public function claim() 
	{
				$tournament = '<div class="form-labels"><input type="radio" name="twoclub" checked="checked" value="1">Two Club&nbsp;<input type="radio" name="twoclub" value="0">Hole In One</div>';
				$voucher = '<div class="form-labels"><input type="text" name="voucher" size="8" maxlength="6" />Voucher Number</div>';
				$cptnumber = '<div class="form-labels"><input type="text" name="captain" size="15" maxlength="25" />Club Captain Cellnumber</div>';
				$clubname = '<div class="form-labels"><input type="text" name="club" size="25" maxlength="30" />Club Name</div>';
				$time = '<div class="form-labels">Time of Day<input type="radio" name="time" checked="checked" value="am">AM&nbsp;<input type="radio" name="time" value="pm">PM</div>';
				$hole = '<div class="form-labels"><input type="text" name="hole" size="3" maxlength="2" />Hole Number</div>';

				$output = $hole . $voucher . $cptnumber . $clubname . $tournament . $time;
				return $output;
	}


	/**
	* access 	- public
	* desc 		- save claim details
	*/
	public function saveclaim($claim)
	{

		$error = false;

		foreach($claim AS $var => $value)
		{
			$claim[$var] = trim($value);
		}
		
		if (empty($claim['voucher']) OR $claim['voucher'] < 100000 OR $claim['voucher'] > 200000)
		{
			$msg = '<div class="output" style="color:red;">Invalid Voucher Number</div>';
			$error = true;
		} else {
			 				// Validate voucher 
							$Model = new Model($this->config);
							$result = $Model->validateVoucher($claim['voucher']);

							if ($result < 1) 
							{
								$msg = '<div class="output" style="color:red;">Invalid Voucher Number</div>';
								$error = true;
							}
		}

		if (empty($claim['captain']) OR strlen($claim['captain']) <> 10)
		{
			$msg = '<div class="output" style="color:red;">Invalid Captains Phone Number</div>';
			$error = true;
		}		

		if (empty($claim['club']) OR strlen($claim['club']) < 5)
		{
			$msg = '<div class="output" style="color:red;">Invalid Club Name</div>';
			$error = true;
		}

		if (empty($claim['hole']) OR $claim['hole'] < 1 OR $claim['hole'] > 18)
		{
			$msg = '<div class="output" style="color:red;">Invalid Hole Number</div>';
			$error = true;
		}

		if (!$error)
		{		
							$claim['club'] = ucwords($claim['club']);

							// ToDo value lookup here
							$claim['value'] = '300000'; 

							$result = $Model->saveClaim($claim);
							if (!$result) return false;
		} else {
							$tournament = '<div class="form-labels"><input type="radio" name="twoclub" value="1" ';
							if (isset($claim['twoclub']) AND $claim['twoclub'] == 1) $tournament .= 'checked="checked"';
							$tournament .= '>Two Club&nbsp;<input type="radio" name="terms" value="0" ';
							if (isset($claim['twoclub']) AND $claim['twoclub'] == 0) $tournament .= 'checked="checked"';
							$tournament .= '>Hole In One</div>';							
							$voucher = '<div class="form-labels"><input type="text" name="voucher" size="8" maxlength="6" value="' . $claim['voucher'] . '"/>Voucher Number</div>';
							$cptnumber = '<div class="form-labels"><input type="text" name="captain" size="15" maxlength="25" value="' . $claim['captain'] . '"/>Club Captain Cellnumber</div>';
							$clubname = '<div class="form-labels"><input type="text" name="club" size="25" maxlength="30" value="' . $claim['club'] . '"/>Club Name</div>';
							$time = '<div class="form-labels">Time of Day<input type="radio" name="time" value="am" ';
							if (isset($claim['time']) AND $claim['time'] == 'am') $time .= 'checked="checked"';
							$time .= '>AM&nbsp;<input type="radio" name="time" value="pm" ';
							if (isset($claim['time']) AND $claim['time'] == 'pm') $time .= 'checked="checked"';
							$time .= '>PM</div>';							
							$hole = '<div class="form-labels"><input type="text" name="hole" size="3" maxlength="2" value="' . $claim['hole'] . '"/>Hole Number</div>';		

							$output = $msg . $hole . $voucher . $cptnumber . $clubname . $tournament . $time;
							return $output;				
		}
		return true;
	}
	
	
	/**
	* access 	- public
	* desc 		- search
	* param 	- none
	*/
	public function search($string) 
	{

				$output = '';
				$Model = new Model($this->config);
				$string = clean($string, 'txt');
				$data = $Model->search($string);

				if($data) 
				{
							$output .= "<div class='heading'>Search results for <strong>" . $string . "</strong></div>";
	
							foreach($data as $article) 
							{
									$output .= '<div class="small"><a  class="small" href="index.php?view=article&urlid=' . $article['urlid'] . '" >' . mb_convert_encoding($article['title'], 'UTF-8', 'WINDOWS-1252') . '</a></div>';
							}
		} else {
							$output .= 'No results found. Try again.';
		}
		return $output;
	}


	/**
	* access	- private
	* desc		- set cookie on client (cookies must be set before any output like headers or whitespace)
	* param		- none   
	*/
	private function set_cookie ()
	{
		$accept = false;

		if( !isset($_COOKIE["MGMOBI"]) )
		{
			if ( setcookie("MGMOBI",$_SESSION['id'], $expire=time()+36000000, $path='', $domain='', $secure=false)  )
			{
				$accept = true;

				if ($this->stats)
				{
						$sql = 'UPDATE stats SET users = users+1;';
						try { 
																	    		$result = $this->query( $sql );
						} catch (Exception $e) {
								    											die('<br>E210: ' . $e->getMessage());
						}		
				}
			}
		}
		if ($this->logging)  mobiLog('VISITOR->SET_COOKIE : ' . $accept, 2);
		return $accept;
	}


	/**
	* access 	- private
	* desc 		- detect and read stored cookies
	* param 	- none
	*/
	private function read_cookie ()
	{
		$found = false;	

		if ( !empty($_COOKIE["MGMOBI"]) ) 
		{																				
			$found = $_COOKIE["MGMOBI"];
		}
		if ($this->logging) mobiLog( 'VISITOR->READ_COOKIE found : ' . $found, 2);
		return false;
	}



	/**
	* access	- public
	* desc		- store language table in ram
	* param		- selected language
	*/
	public function load_lang($language)
	{
		$dir = str_replace("includes", '', dirname(__FILE__));

		if (($handle = fopen($dir . '/lang/language.csv', "r")) !== FALSE) 
		{
							$row = 0;
							while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
							{
									if ($row == 0) 																								// Extract available languages from CSV table header
									{
														$langCodes = array_slice($data, 3);
														$langKey = array_keys($langCodes, $language);
									} else {
														$code = $data[1];
														foreach($langCodes as $message)
														{
															if ($message == $data[2])
															{
																$_SESSION['languages'][$message] = $code;					// populate available languages
															}
														}  
														$data = array_slice($data, 3); 
														$_SESSION['lang_table'][$code] = $data[$langKey[0]];	// populate lang table with specified lang
									}
									$row++;
							}
							fclose($handle);
							ksort($_SESSION['languages']);
							
							if ($this->logging) mobiLog( 'VISITOR->LOAD_LANG : rows ' . $row, 2);
		} else {
							if ($this->logging) mobiLog( 'VISITOR->LOAD_LANG : failed', 2);
							die("Translation table not available");
		}
	}	

	
	/**
	* access	- public
	* desc		- email support request
	* param		- $comment from visitor
	* param 	- $email support address   
	*/
	public function sendEmail( $comment, $email )
	{
		$comment = stripcslashes($comment);
		$comment = clean($comment, 'txt');

		if ( empty($_SESSION['visitor'] ) or empty($comment) )
		{
				return false;
		}
		$todayis = date("l, F j, Y, g:i a");
		$subject = 'New visitor response ' . $_SESSION['referer'];
		$device = implode(",", $_SESSION['device']);
		$message = $todayis . "[EST]" . "\n\n";
		$message .= "ID: " . $_SESSION['visitor'] . "\n\n";
		$message .= "Enquiry: " . $comment . "\n\n";
		$message .= "IP Address: " . $_SESSION['device']['ip']  . "\n\n";
		$message .= "Device: " . $device;
		$from = "From: noreply@mg.co.za" . "\r\n";
		$sent = mail($email, $subject, $message, $from);
		return $sent;
	}
}

?>