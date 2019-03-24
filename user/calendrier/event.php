<?php
include '../../conf.php';
require 'class.php';
$events = new Events(BDD());
if (!isset($_GET['id'])) {

}
try {
	$event = $events->find($_GET['id']);
} catch (\Exception $e) {
	echo "<script>alert('Cet évenement n\'existe pas');</script> ";
	die();
}
$month = new Month($event->getstart()->format('m') ?? null,$event->getstart()->format('Y') ?? null, $event->getstart()->format('d') ?? null);
?>

<div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= htmlspecialchars($event->getName()); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 style="text-align: center;" ><?= $month->toStringday(); ?> de <?= htmlspecialchars($event->getstart()->format('H:i')); ?> a <?= htmlspecialchars($event->getend()->format('H:i')); ?></h5>
		    <?= $event->getdescription(); ?>
	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnedit" onclick="getedit('<?=$event->getid();?>');" class="btn btn-primary">Modifier</button>
      </div>
    </div>
  </div>
</div>

