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

define('INPUT', json_decode(file_get_contents('php://input'), TRUE));

if (INPUT['API_ACCESS_KEY'] !== ENV['API_ACCESS_KEY']) {
	header('HTTP/1.0 403 Forbidden');
	die("Access Denied");
}

require_once "../mysql.php";

$result = $mysql->query(INPUT['query']);
if ($result === TRUE) {
	echo "{success: true}";
} else if ($result !== FALSE && $result->num_rows !== 0) {
	$return = array("success" => TRUE);
	while ($row = $result->fetch_assoc()) {
		$return[] = $row;
	}

	echo json_encode($return);
	if (!$result->close()) {
		die;
	}
} else {
	echo "{success: false}";
}

if (!$mysql->close()) {
	die;
}
