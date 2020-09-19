<?php
require_once("db/DB.php");
// $db = new DB("127.0.0.1", "Tiffany", "root", "");
$db = new DB("db5000931054.hosting-data.io", "dbs814459", "dbu797268", "I1p&*mC2F72NH0$%");
$path = ltrim($_SERVER['REQUEST_URI'], '/');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if ($path == "auth") {

	} elseif ($path == "users") {
		echo json_encode(($db->query("SELECT * FROM users")));
		http_response_code(200);
	} else {
		echo "What are you trying to do? Page not found.";
		http_response_code(404);
	}
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($path == "auth") {
		$postBody = file_get_contents("php://input");
		$postBody = json_decode($postBody);

		$username = $postBody->username;
		$password = $postBody->password;

		if ($db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
			if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
				$cstrong = TRUE;
				$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
				$user_id = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
				$db->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id', array(':token'=>sha1($token), ':user_id'=>$user_id));
				echo '{ "Token": "'.$token.'" }';
			} else {
				http_response_code(401);
			}
		} else {
			http_response_code(401);
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	if ($path == "auth") {
		if (isset($_GET['token'])) {
			if ($db_query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($_GET['token'])))) {
				$db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_GET['token'])));
				echo '{ "Status": "Success" }';
				http_response_code(200);
			} else {
				echo '{ "Error": "Invalid token" }';
				http_response_code(400);
			}
		} else {
			echo '{ "Error": "Bad Request" }';
			http_response_code(400);
		}
	}
} else { // for anything other than post or get
	http_response_code(405);
}
