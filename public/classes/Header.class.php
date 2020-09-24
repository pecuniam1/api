<?php
class Header
{
	/**
	 * Clean header removes an header in the buffer.
	 * @return void
	 */
	public static function cleanHeader() : void
	{
		ob_clean();
		header_remove();
	}
	/**
	 * Adds a JSON Header.
	 * @return void
	 */
	public static function addJSONHeader() : void
	{
		self::cleanHeader();
		header("Content-type: application/json; charset=utf-8");
	}
	/**
	 * Adds an HTML header.
	 * @return void
	 */
	public static function addHTMLHeader() : void
	{
		self::cleanHeader();
		header("Content-Type: text/html; charset=UTF-8");
	}
	
	/**
	 * Sends an email message to the webmaster.
	 * @param string $message The message to send to the webmaster.
	 * @return bool Whether send was successful.
	 */
	public static function sendTestMessage($message = "") : bool
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
