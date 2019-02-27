jQuery(document).ready(function() {
  jQuery("#connect").click(function(e){connect();});
  jQuery("#inputlogin").keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      connect();
    }
  });
  jQuery("#inputPassword").keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      connect();
    }
  });
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
