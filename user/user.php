<?php
require_once '../functions/auth.php';
require_once '../functions/user.php';
require_once '../functions/Judoka.php';
require_once '../conf.php';

if (isset($_GET['calendrier'])):

	$ParamsRemove = "'calendrier'";
	$script = '';
	$body = 'calendrier/index.php';

elseif (isset($_GET['menu'])) :

	$ParamsRemove = "'menu'";
	$script = '';
	$body = 'menu.php';
	
elseif (isset($_GET['calendrier-ins'])):

	$ParamsRemove = "'calendrier-ins'";
	$id = $_GET['calendrier-ins'];
	$body = 'ins_event/index.php';
	$script = '';

elseif (isset($_GET['profil-info'])):

	$ParamsRemove = "'profil-info'";
	$body= 'profil/profil-info.php';
	$script='';
else:
	require_once '../error/404.php';
	die();
endif;


$user = new user(BDD(isset($_GET['DEBUG'])), '../index.php');
$userinfo = $user->find_user_info();
require 'header.php';
$judokas = new Judoka(BDD(isset($_GET['DEBUG'])));
?>
<script>
		jQuery(document).ready(function() {
			jQuery("#acc").click(function(e){page("menu");});
	  		jQuery("#calendrier").click(function(e){page("calendrier");});
	  		<?= $script ?>
		});
		var ParamsRemove = <?= $ParamsRemove ?> ;
</script>
<!-- container fluid start -->
<div id='container' class="container-fluid" style="padding-top: 2%; padding-bottom:5%; " >
	<!-- Information utilisateur -->
	<div style="padding-bottom:2%;">
	<nav class="navbar navbar-expand-lg navbar-dark rounded shadow" style="<?= $judokas->Get_css_navbar();?>">
		<h5 class="col" style="margin-bottom:0;">
			<span id="bonjour">Bonjour</span>, <?= $userinfo->getname();?> <?= $userinfo->getsurname();?>. Il est 
			<span class="badge badge-secondary" id='heure'></span>
		</h5>
		<form class="form-inline">
			<?php $judokas->Get_list_Judokas($userinfo);?>
		</form>
  	</nav>

			<script type="text/javascript">window.onload = bonjs(); heure('heure');</script>
	</div>


<?php

require_once $body;

?>

</div>
<!-- container fluid end -->
