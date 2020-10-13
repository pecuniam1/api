<?php
include 'autoload.inc.php';

use Classes\Constants as Constants;
use Classes\Header as Header;
use Classes\IP as IP;
use Classes\SiteDB as SiteDB;
use Classes\EmailController as EmailController;

$db = new SiteDB(Constants::DB_HOST, Constants::DB_NAME, Constants::DB_USER, Constants::DB_PASSWORD);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if ($_GET['url'] == "users") {
		Header::addJSONHeader();
		echo json_encode(($db->query("SELECT * FROM users")));
		http_response_code(200);
	} else {
		Header::cleanHeader();
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
		include("not_found.html");
		die();
	}
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_GET['url'] == "auth") {
		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody, true);

		$username = $postBody["username"];
		$password = $postBody["password"];

		if ($db->query('SELECT username FROM users WHERE username=:username', array(':username' => $username))) {
			if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username' => $username))[0]['password'])) {
				$cstrong = true;
				$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
				$user_id = $db->query(
					'SELECT id FROM users WHERE username=:username',
					array(':username' => $username)
				)[0]['id'];
				$db->query(
					'INSERT INTO login_tokens VALUES (\'\', :token, :user_id)',
					array(':token' => sha1($token), ':user_id' => $user_id)
				);
				Header::addJSONHeader();
				echo '{ "Token": "' . $token . '" }';
				// 201 Created response code should be transmitted
			} else {
				http_response_code(401); // does not work as expected
			}
		} else {
			http_response_code(401); // does not work as expected
		}
	} elseif ($_GET['url'] == "contact") {
		// if given proper json data, this is working perfectly
		$postBody = file_get_contents("php://input");
		Header::addJSONHeader();
		$postBody = json_decode($postBody, true);
		$message = "Name: " . (!empty($postBody["name"]) ? $postBody["name"] : "empty") . "<br>";
		$message .= "Phone: " . (!empty($postBody["phone"]) ? $postBody["phone"] : "empty") . "<br>";
		$message .= "Email: " . (!empty($postBody["email"]) ? $postBody["email"] : "empty") . "<br>";
		$message .= "Subject: " . (!empty($postBody["subject"]) ? $postBody["subject"] : "empty") . "<br>";
		$message .= "IP Address: " . IP::getClientIpAddress();
		$success = EmailController::sendMessage($message);
		if ($success) {
			echo '{ "Status": "Success" }';
		} else {
			$error = error_get_last()['message'];
			// TODO I don't think the error will actually print
			echo '{ "Status": "ERROR -- email not sent", "Error message": $error }';
		}
	}
} elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	if ($_GET['url'] == "auth") {
		if (isset($_GET['token'])) { // needs to be cleaned
			if ($db->query(
				"SELECT token FROM login_tokens WHERE token=:token",
				array(':token' => sha1($_GET['token']))
			)) {
				$db->query('DELETE FROM login_tokens WHERE token=:token', array(':token' => sha1($_GET['token'])));
				Header::addJSONHeader();
				echo '{ "Status": "Success" }'; // Token successfully deleted.
				http_response_code(200);
			} else {
				echo '{ "Error": "Invalid token" }'; // Wrong token.
				http_response_code(400);
			}
		} else { // Token is not set.
			echo '{ "Error": "Bad Request" }';
			http_response_code(400);
		}
	} else {
		// do something here
	}
} else { // for anything other than post or get
	http_response_code(405);
}
