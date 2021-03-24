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

define('INPUT', stream_get_contents(STDIN));

if ($_SERVER["REMOTE_ADDR"] !== "34.239.126.103" || $_SERVER["REMOTE_PORT"] !== "55494" || INPUT['API_ACCESS_KEY'] === ENV['API_ACCESS_KEY']) {
	header('HTTP/1.0 403 Forbidden');
	die(<<<_END
Checks:
	{$_SERVER["REMOTE_ADDR"]}
	{$_SERVER["REMOTE_PORT"]}
	{$_POST['API_ACCESS_KEY']}
Access Denied
_END);
}

require_once "../mysql.php";
if (!$mysql->close()) {
	die('Closing MySQL failed');
}
