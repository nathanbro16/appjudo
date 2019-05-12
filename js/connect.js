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
function AnimateShow(){
  animate = false;
  $("#con").animate({
    opacity : 0,
    left: '+=50',
  }, 1000, function(){
    jQuery("#con").hide();
    $("#load").animate({
    opacity : 0,
    left: '-=50',
    }, function () {
      jQuery("#load").show();
    });
    $("#load").animate({
        opacity : 1,
        left: '+=50',
    }, 1000 );
    $("#con").animate({
        opacity : 1,
        left: '-=50',
    });
    animate = true;
  });
}
function AnimateHide(){
  animate = false;
  $("#load").animate({
    opacity : 0,
    left: '+=50',
  }, 1000, function(){
    jQuery("#load").hide();
    $("#con").animate({
    opacity : 0,
    left: '-=50',
    }, function () {
      jQuery("#con").show();
    });
    $("#con").animate({
        opacity : 1,
        left: '+=50',
    }, 1000 );
    $("#load").animate({
        opacity : 1,
        left: '-=50',
    });
    animate = true;
  });
}
var animate = true;

function Get_load(params) {
  console.log('ttt');
    if (animate === true){
      if (params === 'hide') {
        AnimateHide();
      } else if (params === 'show'){
        AnimateShow();
      }
    } else {
      if (params === 'hide') {
        setTimeout(AnimateHide, 2500);
      } else if (params === 'show'){
        setTimeout(AnimateShow, 2000);
      }
    }

}
function connect() {
  Get_load('show');

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
