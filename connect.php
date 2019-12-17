<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
	require_once 'functions/auth.php';
	require_once 'functions/DBB.php';
	$DB = new DB(isset($_GET['DEBUG']), '');
	if (isset($_GET['connect'])):
		$user = new AuthentValidator();
		$ErrorAuth = $user->validatesconnect($_POST, $DB);
		?>
		<script>
				jQuery('.alert').remove();
		    jQuery('.form-control').removeClass('is-invalid');
		    jQuery('.form-control').addClass('is-valid');
		    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
		</script>
		<?php
		if (!empty($ErrorAuth['errors'])) {
			?><script>Get_load('hide');</script><?php
			foreach ($ErrorAuth['errors'] as $k => $error) {
		      ?>
		      <script>
						
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
					setTimeout(function() {
						document.location.href="user/";
					}, 5000);
				</script>
			  <?php
			}

		}
	elseif (isset($_GET['newpass'])):
		$user = new AuthentValidator();
		$ErrorAuth = $user->validatesnewpassword($_POST, $DB);
		require_once 'connect/VerifyNewPassword.php';
		
	endif;
elseif($_SERVER['REQUEST_METHOD'] === 'GET' and isset($_GET['NewPassword'])):
	echo 'ok';
else:
	http_response_code(406);
endif;

