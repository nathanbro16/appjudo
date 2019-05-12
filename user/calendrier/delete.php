<?php
require_once '../../conf.php';
require_once '../../functions/auth.php';
require_once '../../functions/user.php';
require_once 'class.php';
$user = new user(BDD(isset($_GET['DEBUG'])), '../index.php');
$user->find_user_info();
$events = new Events(BDD(false));

if (!empty($_GET['id'])):
	try {
		$event = $events->find($_GET['id']);
	} catch (\Exception $e) {
		echo '<div class="alert alert-danger" role="alert"> L\'évènement n\'as pas pus être supprimés. </div>';
		die();
	}
	$events->delete($event);
	?>
	<div class="alert alert-success" role="alert"> l'évènement a bien été supprimer. </div>
	<script>
		$('#calendar').fullCalendar('refetchEvents');
		jQuery('#event').on('hidden.bs.modal', function (e) {
			calendar('<?= 'month='.$event->getstart()->format('m').'&year='.$event->getstart()->format('Y')?>');
		})
	</script>
	<?php

else:
	echo "aucun id n'as été préciser";
endif;
