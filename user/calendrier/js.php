<?php
require_once '../../conf.php';
require_once '../../functions/auth.php';
require_once '../../functions/user.php';
require_once 'class.php';
$user = new user(BDD(isset($_GET['DEBUG'])), '../index.php');
$user->find_user_info();
try {
	$month = new Month(null, null, null, $_GET['start'] ?? null, $_GET['end'] ?? null);
} catch (\Exception $e) {
	echo $e->getMessage();
	die();
}
	$events = new Events(BDD(false));
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