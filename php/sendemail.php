<?php
date_default_timezone_set('America/New_York');

require_once("class.phpmailer.php");
require_once("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail = new PHPMailer();

// EDIT THE LINES BELOW AS REQUIRED
$email_to = "reed@hoffmanlawncare.com";
$email_subject = "Website Contact/Quote Request";

$mail->AddAddress($email_to);
$mail->Subject = $email_subject;
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 1;
    
$useLive = $_POST['use_live'];
if($useLive == true)
{
    $mail->Port       = 25;                  			          // set the SMTP port
    $mail->Host       = 'relay-hosting.secureserver.net';   // Use GoDaddy's relay SMTP server
} else {
    $mail->SMTPAuth   = 'true';           				          // enable SMTP authentication
    $mail->Port       = 25;                  			          // set the SMTP port
    $mail->Host       = 'smtpout.secureserver.net';         // SMTP server
    $mail->Username   = 'contact@hoffmanlawncare.com';      // SMTP account username
    $mail->Password   = 'jeffgordon24';      			          // SMTP account password
}

if(isset($_POST['email'])) {

	$full_name = $_POST['full_name']; // required
	$phone = $_POST['phone']; // not required
	$email_from = $_POST['email']; // required
	$message = $_POST['message']; // not required
	
	function clean_string($string) {
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}
	
	function to_service_list($array){
		$result = "";
		for($i=0;$i<count($array);$i++){
			if(strlen($result)>0){
				$result .= ", ";
			}
			$result .= $array[$i];
		}
		return $result;
	}

	$email_message .=  "<table>";
	$email_message .=  "<tr>";
	$email_message .=  "<td style='width: 200px'><strong>Full Name:</strong></td>";
	$email_message .=  "<td>".clean_string($full_name)."</td>";
	$email_message .=  "</tr>";
	$email_message .=  "<tr>";
	$email_message .=  "<td style='width: 200px'><strong>Phone Number:</strong></td>";
	$email_message .=  "<td>".clean_string($phone)."</td>";
	$email_message .=  "</tr>";
	$email_message .=  "<tr>";
	$email_message .=  "<td style='width: 200px'><strong>Email:</strong</td>";
	$email_message .=  "<td>".clean_string($email_from)."</td>";
	$email_message .=  "</tr>";
	$email_message .=  "<tr>";
	$email_message .=  "<td style='width: 200px'><strong>Services Needed:</strong></td>";
	$email_message .=  "<td>".to_service_list($_POST['services'])."</td>";
	$email_message .=  "</tr>";
	$email_message .=  "<tr>";
	$email_message .=  "<td valign='top' style='width: 200px'><strong>Message:</strong></td>";
	$email_message .=  "<td>".clean_string($message)."</td>";
	$email_message .=  "</tr>";
	$email_message .=  "</table>";
	

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($email_message);

	$mail->AddReplyTo($_POST['email'],$_POST['full_name']);
  $mail->From       = 'contact@hoffmanlawncare.com';
	$mail->FromName   = 'HoffmanLawnCare.com';

	if(!$mail->Send()) {
		echo "false";
	} else {
		echo "true";
	}
	
} else {
	echo "false";
}
?>