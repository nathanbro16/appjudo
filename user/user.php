<?php
require_once '../functions/user.php';
//include '../class.php';
include '../conf.php';
$user = new user(BDD(), '../index.php');

$userinfo = $user->infosuer();
$jugrd = $user->grdjudo($userinfo['grdjudo']);
require 'header.php';

?>
<!-- container fluid start -->
<div class="container-fluid" style="padding-top: 2%; padding-bottom:5%; " >
	<!-- Information utilisateur -->
	<div style="padding-bottom:2%; ">
		<div class="card" >
			<div class="card-body row">
				<h5 class="col" style="margin-bottom:0;"><span id="bonjour">Bonjour</span>, <?= $userinfo['name']?> <?= $userinfo['surname']?>. Il est <span class="badge badge-secondary" id='heure' ></span></h5>
				<h5 class="col"></h5>
				<h5 class="col text-right" style="margin-bottom:0;"> <img src="../grade/<?= $jugrd['html']?>.png" width="60" height="30" class="d-inline-block align-top" alt=""> / <?= $jugrd['name']?> / <?=$user->nameyear($user->age($userinfo['birth']), $userinfo['sexe'])?></h5>
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
elseif (isset($_GET['profil-info'])):
	$script='';

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
