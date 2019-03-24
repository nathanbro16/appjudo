<?php
include 'class.php';
// Récupération udes infos utilisateur

try {
	$insevent = new inscripevent($id ?? null, BDD());
	$infoevent = $insevent->validins();
} catch (Exception $e) {
	echo $e->getMessage();
	die();
}
//traitement interface et post
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
dd($_POST);
$Validator = new insValidator();
$errors = $Validator->validates($_POST, $bdd, $user);
if (!empty($errors)) {
	dd($errors);
}else{
	$insevent->create($_POST, $_SESSION['id']);
}
endif;

$jobs = $insevent->getjobs();
?>

<script>
  //Formulaire
jQuery(document).ready(function() {
    jQuery("#validate").click(function(e){insjobs()});
});
function insjobs() {
    jQuery.post(
        '',
        { 
            selectjob : jQuery("#selectjob").val(),

        },
        function(data){
          jQuery("#js").html(data);

    	},
        
    );   
}

</script>

<div class="container-fluid" style="padding-top: 1%; padding-bottom:5%; ">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item text-primary">Calendrier</li>
			<li class="breadcrumb-item active" aria-current="page">Inscription</li>
		</ol>
	</nav>
	<div class="card" >
			<div class="card-body">
				<h3 class="col"><?= $infoevent['eventname'] ?><?= $infoevent['start']; ?></h3>
				<table class="table table-hover table-info">
					<thead>
						<th>#</th>
						<th>Nom du post</th>
						<th>Nombre maximum</th>
						<th>Nombre restant</th>
						<th>Age minimun</th>
						<th>Actions</th>
					</thead>
					<tbody>
					<?php
					$i = 0 ;
					if (count($jobs) === 0) {
						echo "Il n'y a pas de poste disponible veuiller contacter un référent du club ou patienter.";
					}
					foreach ($jobs as $job):
						if (($job['Max_person']-$insevent->getperson($job['id'])) != 0) {
							$inscrips[] = $job;
						}
						echo "<tr>\n";
						echo "<td>". ($i ++)."</td>\n" ;
						echo "<td>".$job['name']."</th>\n";
						echo "<td>".$job['Max_person']."</th>\n";
						echo "<td>".($job['Max_person']-$insevent->getperson($job['id']))."</th>\n";
						echo "<td>".$insevent->age($job['Max_old'])." ans </th>\n";
						echo "</tr>\n";

					endforeach;

					?>				
					</tbody>
				</table>
			</div>
	</div>
</div>
<div id="js"></div>
	<?php
