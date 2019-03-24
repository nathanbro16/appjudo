<?php
include '../../conf.txt';
require 'class.php';
$events = new Events(BDD());
$errors = [];
if (!isset($_GET['id'])) {
  echo "pas id";
}
try {
	$event = $events->find($_GET['id']);
} catch (\Exception $e) {
	echo "<script>alert('Cet évenement n\'existe pas');</script> ";
	die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'):
  $data = $_POST;
  $errors = [];
  $Validator = new EventValidator();
  $errors = $Validator->validates($data);
  ?>
    <script>
    jQuery('.alert').remove();
    jQuery('.form').removeClass('is-invalid');
    jQuery('.form').addClass('is-valid');
    jQuery('#inscript').removeClass('is-invalid').addClass('is-valid');
  </script>
  <?php 
  
  if (!empty($errors)){
    echo "ok";
    dd($errors);
    
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
    $events->hydrate($event, $data);
    $events->update($event);
    ?>
    <script>
      jQuery('.alert').remove(); 
      jQuery('.modal-body').append('<div class="alert alert-success" role="alert"> l\'évènement a bien été modifier. </div>');
      jQuery('.form').removeClass('is-invalid');
      jQuery('.form').addClass('is-valid');
    </script>

    <?php
  }

elseif (isset($_GET['InterfaceEdit'])):
?>
<script>
  //Formulaire
jQuery(document).ready(function() {
    jQuery("#addmodif").click(function(e){addmodif('<?= $event->getid(); ?>');});
    jQuery("#delete").click(function(e){eventdel('<?= $event->getid(); ?>');});
});
function addmodif($id) {

    jQuery.post(
        'calendrier/edit.php?id='+ $id,
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
}
function eventdel($id) {

      jQuery.ajax({

      url : 'calendrier/delete.php',
      type : 'GET',
      data : 'id=' + $id,
      success : function(data){
        jQuery("#js").html(data);

      },
      error : function(resultat, statut, erreur){

      },

      complete : function(resultat, statut){

      }

      });
  
}
</script>


      <div class="modal-header">
        <h5 class="modal-title" >Modifier l'évènement  <small><?= htmlspecialchars($event->getName()); ?></small> </h5>
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
          <input type="text" required class="form-control form" value="<?= htmlspecialchars($event->getName()); ?>" maxlength="255" id="eventname">
        </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group"id="grdate">
          <label for="date">Date</label>
          <input type="date" required class="form-control form " value="<?= htmlspecialchars($event->getstart()->format('Y-m-d')); ?>" id="date">
        </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm">
            <div class="form-group" id="grstart">
              <label for="start">Démarrage</label>
              <input type="time" required class="form-control form" id="start" value="<?= htmlspecialchars($event->getstart()->format('H:i')); ?>" placeholder="HH:MM">
            </div>
          </div>
          <div class="col-sm">
            <div class="form-group" id="grend">
              <label for="end">Fin</label>
              <input type="time" required class="form-control form" id="end" value="<?= htmlspecialchars($event->getend()->format('H:i')); ?>" placeholder="HH:MM">
            </div>
          </div>
          <div class="col-sm">
            <div class="form-group" id="grinscript">
              <label for="insevent">Inscription a l'évènement</label>
              <select class="custom-select my-1 mr-sm-2" id="inscript">
                <option <?= $event->getinscp() === '1' ? 'selected' : null ; ?> value="1">Sans inscription</option>
                <option <?= $event->getinscp() === '2' ? 'selected' : null ; ?> value="2">Requeris un compte</option>
                <option <?= $event->getinscp() === '3' ? 'selected' : null ;?> value="3">Toute personne meme sans compte</option>
              </select>
            </div>
          </div>
        </div>
        <div>
          <div class="form-group" id="grdescription">
            <label for="description">Description</label>
            <textarea id="description" class="form-control" ><?= $event->getdescription(); ?></textarea>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="button" id="addmodif" class="btn btn-primary">Modifier l'évènement</button>
        <button type="button" id="delete" class="btn btn-danger">Suprimer l'évènement</button>
      </div>
   
<?php
endif;

