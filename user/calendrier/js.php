<?php
session_start();
include '../../conf.txt';
include '../../class.php';
include 'class.php';
try {
	$bdd = BDD();
$user = new user($_SESSION['id'] ?? null ,$bdd);
} catch (\Exception $e) {
	http_response_code(403);
	echo $e->getMessage();
	die();
}

$user->infosuer();
try {
	$month = new Month(null, null, null, $_GET['start'] ?? null, $_GET['end'] ?? null);
} catch (\Exception $e) {
	echo $e->getMessage();
	die();
}
	$events = new Events($bdd);
	$events = $events->getEeventsBetween($month->getstart(), $month->getend());
	foreach ($events as $event) {
		$data[] = array(
		  'id'   => $event["id"],
		  'title'   => $event["eventname"],
		  'start'   => $event["start"],
		  'end'   => $event["end"],
		  'url' => 'javascript:getevent('.$event["id"].');'
		 );
		//$data[] = '{"name":"'.$event['eventname'];
	}
	//dd($data);
	echo json_encode($data);
?>