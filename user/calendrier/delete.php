<?php
session_start();
include '../../conf.php';
include '../../class.php';
require 'class.php';
try {
	$bdd = BDD();
$user = new user($_SESSION['id'] ?? null ,$bdd);
} catch (\Exception $e) {
	http_response_code(403);
	echo $e->getMessage();
	die();
}
$events = new Events(BDD());
if (!empty($_GET['id'])) {
	try {
		$event = $events->find($_GET['id']);
	} catch (\Exception $e) {
		echo "<script>alert('Cet évenement n\'existe pas');</script> ";
		die();
	}
	$events->delete($event);
	?>
	<div class="alert alert-success" role="alert"> l\'évènement a bien été supprimer. </div>
	<script>
		jQuery('#event').on('hidden.bs.modal', function (e) {
			calendar('<?= 'month='.$event->getstart()->format('m').'&year='.$event->getstart()->format('Y')?>');
		})
	</script>
	<?php
	
}else{
	echo "aucun id n'as été préciser";
}
