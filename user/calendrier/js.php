<?php
require_once 'class.php';
require_once '../../functions/auth.php';
require_once '../../functions/user.php';
require_once '../../functions/DBB.php';
$DB = new DB(isset($_GET['DEBUG']), '../../');
$user = new user($DB, '../index.php', '../../');
try {
	$month = new Month(null, null, null, $_GET['start'] ?? null, $_GET['end'] ?? null);
} catch (\Exception $e) {
	echo $e->getMessage();
	die();
}
	$events = new Events($DB);
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