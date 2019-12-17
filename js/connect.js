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
function $_GET() {
  var t = location.search.substring(1).split('&');
  var f = [];
  for (var i=0; i<t.length; i++){
      var x = t[ i ].split('=');
      f[x[0]]=x[1];
  }
  return f;
}
function NewPassword(){
  var get = $_GET();
  if(typeof(get['NewPassword']) !== undefined){
    var xmlRequest =  jQuery.ajax({
        url : 'connect.php',
        type : 'GET',
        dataType : 'html',
        data : 'NewPassword='+get['NewPassword'],
    });
    xmlRequest.done( function(html){ 
       
    });
    xmlRequest.fail( function(textStatus){
        
    });
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
