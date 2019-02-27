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
	require 'header.php';

?>
<!-- container fluid start -->
<div class="container-fluid" style="padding-top: 2%; padding-bottom:5%; " >
	<!-- Information utilisateur -->
	<div style="padding-bottom:2%; ">
		<div class="card " >
			<div class="card-body row">
				<h5 class="col"><span id="bonjour">Bonjour</span>, <?= $userinfo['name']?> <?= $userinfo['surname']?>. Il est <span class="badge badge-secondary" id='heure' ></span></h5>
				<h5 class="col text-right"> <img src="../grade/<?= $jugrd['html']?>.png" width="60" height="30" class="d-inline-block align-top" alt=""> / <?= $jugrd['name']?> / <?=$user->nameyear($user->age($userinfo['birth']), $userinfo['sexe'])?></h5>
			</div>
		</div>
			<script type="text/javascript">window.onload = bonjs(); heure('heure');</script>
	</div>
	

<?php

if (isset($_GET['calendrier'])):
	$script = '';
	require_once 'calendrier/index.php';
elseif (isset($_GET['menu'])) :
	$script = '';

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
</div>
<!-- container fluid end -->
