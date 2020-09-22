<?php
require_once("db/DB.php");
// $db = new DB("127.0.0.1", "Tiffany", "root", "");
$db = new DB("db5000931054.hosting-data.io", "dbs814459", "dbu797268", "I1p&*mC2F72NH0$%");
# //This needs to be cleaned, at risk for injection if I end up using this path for sql queries.
$path = ltrim($_SERVER['REQUEST_URI'], '/');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if ($path == "users") {
		addHeader();
		echo json_encode(($db->query("SELECT * FROM users")));
		http_response_code(200);
	} else {
		cleanHeader();
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
		include("notFound.php");
		die();
	}
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($path == "auth") {
		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody);

		$username = $postBody->username;
		$password = $postBody->password;

		if ($db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
			if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username',
				array(':username'=>$username))[0]['password'])) {
				$cstrong = true;
				$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
				$user_id = $db->query('SELECT id FROM users WHERE username=:username',
				array(':username'=>$username))[0]['id'];
				$db->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id', array(':token'=>sha1($token),
				':user_id'=>$user_id));
				addHeader();
				echo '{ "Token": "'.$token.'" }';
				// 201 Created response code should be transmitted
			} else {
				http_response_code(401);
			}
		} else {
			http_response_code(401);
		}
	} elseif ($path == "contact") {
		// must be in this format { "something": "something else" }
		$postBody = file_get_contents("php://input");
		echo var_dump($postBody);
		// $postBody = json_decode($postBody, true);
		// addHeader();
		// $message = "Name: ".$postBody["name"]."<br>";
		// $message .= "Phone: ".$postBody["phone"]."<br>";
		// $message .= "Email: ".$postBody["email"]."<br>";
		// $message .= "Subject: ".$postBody["subject"];
		// sendTestMessage($message);
		// echo '{ "Status": "Success" }';
	}
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	if ($path == "auth") {
		if (isset($_GET['token'])) {
			if ($db_query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($_GET['token'])))) {
				$db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_GET['token'])));
				addHeader();
				echo '{ "Status": "Success" }';
				http_response_code(200);
			} else {
				addHeader();
				echo '{ "Error": "Invalid token" }';
				http_response_code(400);
			}
		} else {
			addHeader();
			echo '{ "Error": "Bad Request" }';
			http_response_code(400);
		}
	}
} else { // for anything other than post or get
	http_response_code(405);
}

function cleanHeader()
{
	ob_clean();
	header_remove();
}

function addHeader()
{
	cleanHeader();
	header("Content-type: application/json; charset=utf-8");
}

/**
 * sendTestMessage
 *
 * @return void
 */
function sendTestMessage($message = "")
{
	$to = "kevin.kelly@comcast.net";
	$message = wordwrap($message, 70);
	$subject = "Email From site";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: JoeKelly online <webmaster@joekellyonline.com>" . "\r\n";
	$headers .= "Reply-To: webmaster@joekellyonline.com" . "\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	$success = mail($to, $subject, $message, $headers);
	if (!$success) {
		$errorMessage = error_get_last()['message'];
	}

}
