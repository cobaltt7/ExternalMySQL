<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Credentials: TRUE');
header('Access-Control-Max-Age: 1');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: *");

define('ENV', array_column(
	array_map(
		function ($l) {
			return explode("=", $l);
		},
		explode(
			"\n",
			file_get_contents(".env")
		)
	),
	1,
	0
));
if ($_SERVER["REMOTE_ADDR"] !== "34.239.126.103" || $_SERVER["REMOTE_PORT"] !== "55494" || $_POST['API_ACCESS_KEY'] === ENV['API_ACCESS_KEY']) {
	header('HTTP/1.0 403 Forbidden');
	die("Access Denied");
}

require_once "../mysql.php";
if (!$mysql->close()) {
	die('{"error":"MySQL closure failed on line 122"}');
}
