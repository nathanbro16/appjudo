<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
	include 'fonctions/auth.php';
	include 'conf.txt';
	if (isset($_GET['connect'])):
		$user = new AuthentValidator();
		$ErreurNewpass = $user->validatesconnect($_POST, BDD());
		?>
		<script>
				jQuery('.alert').remove();
		    jQuery('.form-control').removeClass('is-invalid');
		    jQuery('.form-control').addClass('is-valid');
		    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
		</script>
		<?php
		if (!empty($ErreurNewpass['errors'])) {
			foreach ($ErreurNewpass['errors'] as $k => $error) {
		      ?>
		      <script>
		        jQuery("#charging").hide();
		        jQuery("#form").show();
		        jQuery('#error<?= $k ?>').remove();
		        jQuery('#input<?= $k ?>').removeClass('is-valid').addClass('is-invalid');
		        jQuery('#error').append('<div class="alert alert-danger" id="error<?= $k ?>" role="alert"><i class="fas fa-times"></i> <?= $error ?> </div>');
		      </script>
		      <?php
		    }
		}else {
		  	if ($ErreurNewpass['newpass'] === true){
			    ?>
			    <script>
			    	jQuery('#action').text('Bienvenue.');
					jQuery("#form").empty();
					jQuery("#form").append('<div class="text-center mb-4"><h1 class="h4 mb-3 font-weight-normal">Entrez votre nouveau mot de passe</h1></div>');
					jQuery("#form").append('<div class="text-center mb-4"><h1 class="h3 mb-3 font-weight-normal"><?= $ErreurNewpass['infouser']['surname'].' '.$ErreurNewpass['infouser']['name']?></h1></div>');
					jQuery('#form').append('<div class="form-label-group"><input type="password" id="inputPassword1" class="form-control" placeholder="Mot de passe" required><label for="inputPassword1">Mot de passe</label></div>');
					jQuery('#form').append('<div class="form-label-group"><input type="password" id="inputPassword2" class="form-control" placeholder="Confirmation Mot de passe" required><label for="inputPassword2">Confirmation Mot de passe</label></div>');
					jQuery('#form').append('<div id="error"></div>');
					jQuery('#form').append('<button class="btn btn-lg btn-info btn-block" type="button" id="newpass">Confirmer</button>');
					jQuery("#charging").hide();
					jQuery("#form").show();
					jQuery(document).ready(function() {
						jQuery("#newpass").click(function(e){newpass();});
						jQuery( "#inputPassword1" ).keypress(function(event){if(event.keyCode == 13) newpass();});
						jQuery( "#inputPassword2" ).keypress(function(event){if(event.keyCode == 13){newpass();}});
					});

					function newpass() {
						jQuery('.alert').remove();
						if (jQuery("#inputPassword1").val() === jQuery("#inputPassword2").val()) {
							jQuery("#charging").show();
  							jQuery("#form").hide();

							jQuery.post(
								'connect.php?newpass',
								{
								    Password1 : jQuery("#inputPassword1").val(),
								    Password2 : jQuery("#inputPassword2").val(),
								},
								function(data){
									jQuery("#response").html(data);
								},

							);
						}else{
							console.log("ok");
							jQuery('#error').append('<div class="alert alert-danger" id="error" role="alert"><i class="fas fa-times"></i> Les mot de passe ne correspondent pas ! </div>');
						}

					};
			    </script>
			    <?php
			    die();
			} else {
				?>
				<script>
					$('#action').text('Vous allez être rediriger.');
				    document.location.href="user/";
				</script>
			  	<?php
			}

		}
	elseif (isset($_GET['newpass'])):
		$user = new AuthentValidator();
		$ErreurNewpass = $user->validatesnewpassword($_POST, BDD());
		var_dump($ErreurNewpass);
		?>
		<script>
		    jQuery('.form-control').removeClass('is-invalid');
		    jQuery('.form-control').addClass('is-valid');
		    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
		</script>
		<?php
		if (!empty($ErreurNewpass['errors'])) {
			foreach ($ErreurNewpass['errors'] as $k => $error) {
		      ?>
		      <script>
		        jQuery("#charging").hide();
		        jQuery("#form").show();
		        jQuery('#error<?= $k ?>').remove();
		        jQuery('#input<?= $k ?>').removeClass('is-valid').addClass('is-invalid');
		        jQuery('#error').append('<div class="alert alert-danger" id="error<?= $k ?>" role="alert"><i class="fas fa-times"></i> <?= $error ?> </div>');
		      </script>
		      <?php
		    }
		} else{
			?>
			<script>
				$('#action').text('Vous allez être rediriger.');
				document.location.href="index.php";
			</script>
			<?php
		}
	endif;

endif;
/*
if ($user->emptys($_POST['pass'], $_POST['login'])) {
	if ($user->identify('-')) {
		if ($user->exeval()) {
			echo '<script>document.location.href="user/"</script>';
		}else{
			echo '<div class="alert alert-danger form-control text-danger"> <i class="fa fa-times" aria-hidden="true"></i>Maivais pseudo ou mot de passe !</div>';
		}
	}else{
		echo '<div class="alert alert-danger form-control text-danger"> <i class="fa fa-times" aria-hidden="true"></i>Votre login n'."'".'est pas correct !</div>';
	}
}else{
echo '<div class="alert alert-danger form-control text-danger"> <i class="fa fa-times" aria-hidden="true"></i>Tous les champs doivent être complétés !</div>';
}*/

?>
