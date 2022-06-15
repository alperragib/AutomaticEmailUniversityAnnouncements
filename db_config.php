<?php
require 'mail/vendor/autoload.php';

define ('DB_USER',"root");
define ('DB_PASSWORD',"");
define ('DB_DATABASE',"announcements");
define ('DB_SERVER',"localhost");

function getDatetimeNow()
{
	$tz_object = new DateTimeZone('Europe/Istanbul');
	$datetime = new DateTime();
	$datetime->setTimezone($tz_object);
	return $datetime->format('Y-m-d H:i:s');
}

function getDateNow()
{
	$tz_object = new DateTimeZone('Europe/Istanbul');
	$datetime = new DateTime();
	$datetime->setTimezone($tz_object);
	return $datetime->format('Y-m-d');
}

function sendMail($mail_adress,$subject,$content){
	$email = new \SendGrid\Mail\Mail(); 
	$email->setFrom("", "");
	$email->setSubject($subject);
	$email->addTo($mail_adress);
	$email->addContent("text/html", $content);
	$sendgrid = new \SendGrid('');
	try {
		$response = $sendgrid->send($email);

		if($response->statusCode() == 202){
			return true;
		}
		else{
			return false;
		}
	} 
	catch (Exception $e) {
		return false;
	}
}


?>
