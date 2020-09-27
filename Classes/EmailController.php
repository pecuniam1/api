<?php
namespace Classes;
class EmailController
{
	/**
	 * Sends an email message to the webmaster.
	 * @param string $message The message to send to the webmaster.
	 * @return bool Whether send was successful.
	 */
	public static function sendMessage($message = "") : bool
	{
		$to = Constants::WEBMASTER_ADDRESS;
		$message = wordwrap($message, 70);
		$subject = "JOEKELLYONLINE";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: " . Constants::WEBSITE_NAME . "<" . Constants::WEBMASTER_ADDRESS . ">" . "\r\n";
		$headers .= "Reply-To: " . Constants::WEBMASTER_ADDRESS . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		$success = mail($to, $subject, $message, $headers);
		return $success;
		// if (!$success) {
		// 	$errorMessage = error_get_last()['message'];
		// }
	}
}
