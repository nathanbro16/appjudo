<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
	include 'functions/auth.php';
	include 'conf.php';
	if (isset($_GET['connect'])):
		$user = new AuthentValidator();
		$ErrorAuth = $user->validatesconnect($_POST, BDD());
		?>
		<script>
				jQuery('.alert').remove();
		    jQuery('.form-control').removeClass('is-invalid');
		    jQuery('.form-control').addClass('is-valid');
		    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
		</script>
		<?php
		if (!empty($ErrorAuth['errors'])) {
			foreach ($ErrorAuth['errors'] as $k => $error) {
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
		  	if ($ErrorAuth['newpass'] === true){
					require_once 'connect/FormNewPassword.php';
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
		$ErrorAuth = $user->validatesnewpassword($_POST, BDD());
		require_once 'connect/VerifyNewPassword.php';
		
	endif;
else:
	http_response_code(406);
endif;

?>
