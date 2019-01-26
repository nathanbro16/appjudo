<?php
session_start();
include '../class.php';
include '../conf.txt';

try {
	$bdd = BDD();
	$user = new user($_SESSION['id'] ?? null ,$bdd);
} catch (\Exception $e) {
	http_response_code(403);
	echo $e->getMessage();
	die();
}
try{
	if (empty($_GET)) {
		throw new Exception("Invalide paramètre.");
	}
	if ($_GET['calendrier'] ?? null && $_GET['menu'] ?? null && $_GET['calendrier-ins'] ?? null) {
		throw new Exception("cette page n'existe pas.");
	}

}catch (\Exception $e){
	echo $e->getMessage();
	die();
}
	$userinfo = $user->infosuer();
	$jugrd = $user->grdjudo($userinfo['grdjudo']);
?>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
	<div class="navbar-brand">
		<?= $userinfo['name']?> <?= $userinfo['surname']?>
	</div>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item <?php if (isset($_GET['menu'])) echo 'active'; ?>">
	        <a class="nav-link" href="javascript:page('menu')">Menu</a>
	      </li>
	      <li class="nav-item dropdown <?php if (isset($_GET['calendrier']) || isset($_GET['calendrier-ins'])) echo 'active'; ?>">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-offset="flip" aria-haspopup="true" aria-expanded="false">Calendrier</a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item <?php if (isset($_GET['calendrier'])) echo 'active'; ?>" href="javascript:page('calendrier');" >Evènements</a>
	          <a class="dropdown-item <?php if (isset($_GET['calendrier-ins'])) echo 'active'; ?>" href="javascript:page('calendrier-ins')">Inscription</a>
	          <div class="dropdown-divider"></div>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          Profil
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="#">Action</a>
	          <a class="dropdown-item" href="#">Another action</a>
	          <div class="dropdown-divider"></div>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	    <li class="nav-item">
	        <a class="nav-link disabled" href="#">Disabled</a>
	      </li>
	     <li class="nav-item">
	       <a class="btn btn-outline-danger" href="../deconnect.php"> <i class="fas fa-sign-out-alt"></i> Déconnection</a>
	    </li>
	    </ul>
	</div>
	<div class="navbar-brand text-white my-2 my-lg-0">
		<img src="../grade/<?= $jugrd['html']?>.png" width="60" height="30" class="d-inline-block align-top" alt="">
		/ <?= $jugrd['name']?> / <?=$user->nameyear($user->age($userinfo['birth']), $userinfo['sexe'])?>
	</div>
</nav>
<?php
if (isset($_GET['calendrier'])):
$script = ''
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
			jQuery("#datavent").html(data);
			jQuery('#event').modal('show');   

		},
		error : function(resultat, statut, erreur){

		},

		complete : function(resultat, statut){

		}

		});
	}
	jQuery(function() {

	    jQuery('#calendar').fullCalendar({
			themeSystem: 'bootstrap4',
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay',
			},

			weekNumbers: true,
			nowIndicator: true,
			locale: 'fr',
			eventLimit: true, // allow "more" link when too many events
			eventSources: ['calendrier/js.php'],
			views: {
				month: {
					columnFormat: 'ddd',
				},
				week: {
					columnFormat: 'ddd DD'
				}

			},
			eventColor: '#2980b9'
	    });

	});</script>

<div class="container-fluid" style="padding-top: 2%; padding-bottom:5%; " >
	<div class="card" >
  		<div class="card-body" id="calendar">
  		</div>
	</div>
	<div id="datavent"></div>
</div>

<?php
elseif (isset($_GET['menu'])) :

	//$userinfo = $user->infosuer('surname');
	//$jugrd = $user->grdjudo($userinfo['grdjudo']);
	$script = '';
?>

<div class="container-fluid">
	
</div>

<?php
elseif (isset($_GET['calendrier-ins'])):
		$id = $_GET['calendrier-ins'];

	include 'ins_event/index.php';
	$script = '';

endif;
$scripts = '
<script>
		jQuery(document).ready(function() {
			jQuery("#acc").click(function(e){page("menu");});
	  		jQuery("#calendrier").click(function(e){page("calendrier");});
	  		'.$script.'
		});
</script>
';
echo $scripts ;


?>
