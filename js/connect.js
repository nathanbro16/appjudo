jQuery(document).ready(function() {
  jQuery("#connect").click(function(e){connect();});
  jQuery( "#inputEmail" ).keypress(function(event){if(event.keyCode == 13) connect();});
  jQuery( "#inputPassword" ).keypress(function(event){if(event.keyCode == 13){connect();}});
});

function connect() {
  jQuery("#charging").show();
  jQuery("#form").hide();

  jQuery.post(
      'connect.php?connect', // Un script PHP que l'on va créer juste après
      { 
          login : jQuery("#inputlogin").val(),
          Password : jQuery("#inputPassword").val(),
      },
      function(data){
        jQuery("#response").html(data);
      },
      
  ); 
};