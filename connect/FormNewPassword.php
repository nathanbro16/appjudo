<script>
jQuery('#action').text('Bienvenue.');
jQuery("#form").empty();
jQuery("#form").append('<div class="text-center mb-4"><h1 class="h4 mb-3 font-weight-normal">Entrez votre nouveau mot de passe</h1></div>');
jQuery("#form").append('<div class="text-center mb-4"><h1 class="h3 mb-3 font-weight-normal"><?= $ErrorAuth['infouser']['surname'].' '.$ErrorAuth['infouser']['name']?></h1></div>');
jQuery('#form').append('<div class="form-label-group"><input type="password" id="inputPassword1" class="form-control" placeholder="Mot de passe" required><label for="inputPassword1">Mot de passe</label></div>');
jQuery('#form').append('<div class="form-label-group"><input type="password" id="inputPassword2" class="form-control" placeholder="Confirmation Mot de passe" required><label for="inputPassword2">Confirmation Mot de passe</label></div>');
jQuery('#form').append('<div id="error"></div>');
jQuery('#form').append('<button class="btn btn-lg btn-primary btn-block" type="button" id="newpass">Confirmer</button>');
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