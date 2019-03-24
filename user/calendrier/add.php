<?php
include 'class.php';
include 'validator.php';
include '../../conf.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
	$data = $_POST;
	$errors = [];
	$Validator = new EventValidator();
	$errors = $Validator->validates($_POST);
	?>
  <script>
    jQuery('.alert').remove();
    jQuery('.form').removeClass('is-invalid');
    jQuery('.form').addClass('is-valid');
    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
  </script>
	<?php 
	
	if (!empty($errors)){		
			foreach ($errors as $k => $error) {
			?>
			<script>
				jQuery('#error<?= $k ?>').remove(); 
				jQuery('#<?= $k ?>').removeClass('is-valid').addClass('is-invalid');
				jQuery('#gr<?= $k ?>').append('<div class="alert alert-danger" id="error<?= $k ?>" role="alert"><i class="fas fa-times"></i> <?= $error ?> </div>');
			</script>
			
			<?php
			}
			

	}else{

		$event = new Event();
  	$events = new Events(BDD());
    $events->hydrate($event, $data);
		$events->create($event);
		?>
		<script>
			jQuery('.alert').remove(); 
			jQuery('.modal-body').append('<div class="alert alert-success" role="alert"> l\'évènement a bien été enregistré. </div>');
			jQuery('.form').removeClass('is-invalid');
			jQuery('.form').addClass('is-valid');
      custom-select
		</script>

		<?php
	}

	

elseif (isset($_GET['interfaceadd'])):
?>

<script>
	//Formulaire
	jQuery(document).ready(function() {
    jQuery("#addevent").click(function(e){addevent();});
});

function addevent() {

    jQuery.post(
        'calendrier/add.php', // Un script PHP que l'on va créer juste après
        { 
        	eventname : jQuery("#eventname").val(),
            date : jQuery("#date").val(),
            start : jQuery("#start").val(),
            end : jQuery("#end").val(),
            description : jQuery("#description").val(),
            inscript : jQuery("#inscript").val(),
        },
        function(data){
        	jQuery("#js").html(data);

		},
        
     );   
};
</script>

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un évènement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="js">
	
		</div>
      	<div class="row">
      		<div class="col-sm-6">
      			 <div class="form-group" id="greventname">
 					<label for="eventname">Titre</label>
 					<input type="text" required class="form-control form" maxlength="255" id="eventname">
 				</div>
      		</div>
      		<div class="col-sm-6">
      			 <div class="form-group"id="grdate">
 					<label for="date">Date</label>
 					<input type="date" required class="form-control form " id="date">
 				</div>
      		</div>
      	</div>
      	<div class="row">
      		<div class="col-sm">
      			<div class="form-group" id="grstart">
 					    <label for="start">Démarrage</label>
 					    <input type="time" required class="form-control form" id="start" placeholder="HH:MM">
 				    </div>
      		</div>
      		  <div class="col-sm">
              <div class="form-group" id="grend">
 					      <label for="end">Fin</label>
 					      <input type="time" required class="form-control form" id="end" placeholder="HH:MM">
              </div>
      		  </div>
            <div class="col-sm">
              <div class="form-group" id="grinscript">
                <label for="insevent">Inscription a l'évènement</label>
                <select class="custom-select my-1 mr-sm-2" id="inscript">
                  <option selected value="1">Sans inscription</option>
                  <option value="2">Requeris un compte</option>
                  <option value="3">Toute personne meme sans compte</option>
                </select>
              </div>
            </div>
      	</div>
      	<div>
      		<div class="form-group" id="grdescription">
      			<label for="description">Description</label>
      			<textarea id="description" class="form-control"></textarea>
      		</div>

      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="button" id="addevent" class="btn btn-primary">Ajouter l'évènement</button>
      </div>
    </div>
  </div>
</div>
<?php
endif;