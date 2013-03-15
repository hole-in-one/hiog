<?php
/*
* ******************************************************************************************************
*
* HTTP Callback
*
* Called everytime an SMS is submitted 
* to the shortcode 39007
*
* ******************************************************************************************************
*/


/**
* set timezone
*/
date_default_timezone_set('Africa/Johannesburg');	


/**
* Wait for incoming SMS
*/
if (!isset($_REQUEST['from'])) {echo "Go away!"; exit;}
$content = implode(",", $_REQUEST);
$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Incoming SMS: " . $content . "\n", FILE_APPEND);

$ping = trim($_REQUEST['text']);
$msisdn = trim($_REQUEST['from']);
$shortcode = trim($_REQUEST['to']);

if (empty($ping) OR empty($msisdn) OR empty($shortcode) )
{
		$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Missing data: " . $content . "\n", FILE_APPEND);
		exit;
}



/**
* Issue voucher
*/
require_once('includes/mobiSetup.php');
$conf = new Config('config.php');
require_once('includes/mobiModel.php');	
$Model = new Model($conf);

$one = strpos(strtolower($ping), 'one');

// Is this a registered player
$country = substr($msisdn, 0, 2);
if ($country !== '27') {echo 'Wrong country'; exit;} 
$number = substr($msisdn, 2);
$player = $Model->checkPlayer('0' . $number);	
$cr = chr(13); 

if (!$player[0])
{
					$sms = 'You have not yet register as a player' . $cr . 'Please use the link below to register:' . $cr . 'http://bit.ly/122ihPF' . $cr . ' then try again.';
} else {
					$from = $to = time();
					$type = ($one !== false) ? 0 : 1;
					$serial = $Model->issueVoucher(1, $from, $to, $player[0], $type);

					if (empty($serial))
					{
						 				$sms = 'Service currently not available, please try again later';
					} else {
										$format = ($type == 0) ? 'Hole In One' : 'Two Club';
										$sms = $format . ' Voucher' . $cr . 'Player: ' . $player[1] . $cr . 'Voucher Serial: ' . $serial . $cr . 'Valid From: ' . date("d-m-Y", $from) . $cr . 'Valid To: ' . date("d-m-Y", $to) . $cr . 'Help: http://hioc.mobi';
					}
}
	
reply($msisdn, $sms);



/**
* Send a reply SMS
*/
function reply($msisdn, $text)
{
	$token = token();

	if ($token)
	{

		$content = file_get_contents('http://api.smssapi.com/uv1.svc/SendSMS/' . $token . '?mobile=' . $msisdn . '&message=' . urlencode($text));
		$xml = new SimpleXmlElement($content);

		if(!$xml->Result)
		{
			$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Failed to reply: " . $xml->Result . "\n", FILE_APPEND);
			return false; 
		}
		$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Success sending reply no: " . $xml->Comment . "\n", FILE_APPEND);
		return $xml->Comment;
	}
}


/**
* Get token
*/
function token()
{
	$content = file_get_contents('http://api.smssapi.com/uv1.svc/GetToken?username=one2345&password=golf2345');
	$xml = new SimpleXmlElement($content);
	if(!$xml->Result)
	{
		$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Failed to get token: " . $xml->Result . "\n", FILE_APPEND);
		return false; 
	}
	$log = file_put_contents("smslog.txt", date("Y-m-d H:i:s ") . "Success getting token: " . $xml->Comment . "\n", FILE_APPEND);
	return $xml->Comment;
}


?>