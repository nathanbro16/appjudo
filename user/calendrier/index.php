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
	$month = new Month($_GET['month'] ?? null, $_GET['year'] ?? null, $_GET['day'] ?? null);
} catch (\Exception $e) {
	echo $e->getMessage();
	die();
}
?>
<script>
		function getevent($id){
			jQuery('#event').modal('hide');   
			jQuery.ajax({

			url : 'calendrier/event.php',
			type : 'GET',
			data : 'id=' + $id,
			statusCode: {
		       		403 : function(){
				       	jQuery.ajax({

					       url : '../error/403.php',

					       type : 'GET',
					       dataType : 'html', // On désire recevoir du HTML
					       success : function(data, statut){ // code_html contient le HTML renvoyé
							    jQuery("body").html(data);
					       },
					       error : function(data, statut, erreur){ // code_html contient le HTML renvoyé
					

					       },
					       


					    });
					},
						
			},
			success : function(data){
				jQuery("#contenu").html(data);
				jQuery('#event').modal('show');   

			},
			error : function(resultat, statut, erreur){

			},

			complete : function(resultat, statut){

			}

			});
		}

</script>

<?php
// script
$adminsys = new gradesysteme($user->getgrdsite());
if ($adminsys->admincalendar()):
	?>
<script>
	function getadd(){

	jQuery.ajax({

	url : 'calendrier/add.php',
	type : 'GET',
	data : 'interfaceadd=1',
	success : function(data){
		jQuery('#contenu').html(data);
		jQuery('#add').modal('show');   
			$('#description').summernote({dialogsInBody: true,});

	},
	error : function(resultat, statut, erreur){

	},

	complete : function(resultat, statut){

	}

	});
}
function getedit($id){

	jQuery.ajax({

	url : 'calendrier/edit.php',
	type : 'GET',
	data : 'id=' + $id + '&InterfaceEdit=1',
	success : function(data){
		jQuery('.modal-content').html(data);
		//jQuery_3_2_1('#add').modal('show');   
			jQuery('#description').summernote({dialogsInBody: true,});

	},
	error : function(resultat, statut, erreur){

	},

	complete : function(resultat, statut){

	}

	});
}
</script>
<a href="javascript:getadd();" class="calendar__button">+</a>

	<?php
endif;
// /script
if (empty($_GET['day'])):
$start = $month->getstartingDay();
$start = $start->format('N') === '1' ? $start : $month->getstartingDay()->modify('last monday');
/* events */
$events = new Events($bdd);
$weeks = $month->getWeeks();
$end = (clone $start)->modify('+' . (6 + 7 * ($weeks -1)) . ' days');
$events = $events->getEeventsBetweenByDay($start, $end);

 ?>
 <style>
 	.calendar__table{
 	 width: 100%;
 	 height: calc(100vh - 128px);
 	}

 	.calendar__table td{
 		padding: 10px;
 		border: 1px solid #ccc;
 		vertical-align: top;
 		width: 14.29%;
 		height: 20%;
 	}
 	.calendar__table--6weeks td{
 		height: 16.66%;
 	}
 	.calendar__button{
 		display: block;
 		width: 55px;
 		height: 55px;
 		line-height: 55px;
 		text-align: center;
 		color: #FFF;
 		font-size: 30px;
 		background-color: #007bff;
 		border-radius: 50%;
 		box-shadow: 0 6px 10px 0 #0000001a,0 1px 18px 0 #0000001a,0 3px 5px -1px #0003;
 		position: absolute;
 		bottom: 30px;
 		right: 30px;
 		text-decoration: none;
 		transition: transform 0.3s;
 	}
 	.calendar__button:hover{
 		 color: #FFF;
 		text-decoration: none;
 		transform: scale(1.2);
 	}

 </style>
<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
	<h1><?= $month->toString(); ?></h1>
	<div>
		<a href="javascript:calendar('month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>')" class="btn btn-primary">&lt;</a>
		<a href="javascript:calendar('month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>')" class="btn btn-primary">&gt;</a>
	</div>
</div>

<table class="calendar__table calendar__table--<?= $weeks; ?>weeks">
	<?php for ($i=0; $i < $weeks; $i++):?>
	<tr>
		<?php foreach ($month->days as $k => $days):
			$date = (clone $start)->modify("+" . ($k + $i * 7) . "days");
			$events1 = $events;
			$eventsForDay = $events1[$date->format('Y-m-d')] ?? [];
			$isToday = date('Y-m-d') === $date->format('Y-m-d');
		?>
		<td class="<?= $month->withinMonth($date) ? '' : 'text-secondary' ?><?= $isToday ? 'bg-light' : '' ?>">
			<?php if ($i === 0 ): ?>
			<div class="calendar__weekday"><?= $days; ?></div>
			<?php endif; ?>
			<a herf="javascript:calendar('<?= 'day='.$date->format('d').'&month='.$date->format('m').'&year='.$date->format('Y') ?>')" class="calendar__day"><?= $date->format('d'); ?></a><br>
			<?php foreach ($eventsForDay as $events1):?>
				<a class="" href="javascript:getevent(<?= $events1['id'] ?>);"><?= (new DateTime($events1['start']))->format('H:i') ?>-<?= htmlspecialchars($events1['eventname']) ?></a><br>
			<?php endforeach; ?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endfor;?>
</table>


<div id="contenu">
	
</div>
<?php

else:
	setlocale(LC_TIME, "fr_FR");
	$events = new Events($bdd);
	$day = $month->getDay();
	$events = $events->getEeventsBetweenByDay($day, $day);
	$events = $events[$day->format('Y-m-d')] ?? [];
	$date = $month->toStringday();
	$wathdayname = $month->wathdayname($date);
	
	?>
	<h1><?= $date;?></h1>
	<div id="contenu"></div>
	<?php
	
	if (empty($events)) echo "Il y a aucun évènement pour " .$wathdayname. "."; else echo "Il y a ".count($events)." évènement pour " .$wathdayname. ".";	
	foreach ($events as $event):
		?>
		<div class="card text-white bg-primary mb-3" style="max-width: 18rem;" onclick="getevent('<?= $event['id'] ?>')">
		  <div class="card-header"><?= (new DateTime($event['start']))->format('H:i').' - '.(new DateTime($event['end']))->format('H:i') ?></div>
		  <div class="card-body">
		    <h5 class="card-title"><?= $event['eventname']; ?></h5>
		    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
		  </div>
		</div>

		<?php
	endforeach;
?>

<?php
endif;