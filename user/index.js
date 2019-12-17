function heure(id){
date = new Date;

h = date.getHours();
    if(h<10)
    {
    h = "0"+h;
    }
    m = date.getMinutes();
    if(m<10)
    {
    m = "0"+m;
    }
    s = date.getSeconds();
    if(s<10)
    {
    s = "0"+s;
    }
    resultat = ''+h+':'+m+':'+s;
    document.getElementById(id).innerHTML = resultat;
    setTimeout('heure("'+id+'");','1000');
    return true;
}
function bonjs() {
    today=new Date()
    if(today.getHours() >= 0 && today.getHours() < 18){     
    document.getElementById('bonjour').innerHTML='Bonjour';
    setTimeout('bonjs();','1000');
    }else{     
    document.getElementById('bonjour').innerHTML='Bonsoir';
    setTimeout('bonjs();','1000');
    }
}
function TransformJSONToURLParams(params) {
    var result = '';
    for (const [key, value] of Object.entries(params)) {
        if (result !== '') {
            result += '&';
        }
        if (value !== undefined) {
            result += key + '=' + value ;
        } else {
            result += key ;
        }
      
    }
    return result;
}
function TransformParamsURLtoJOSON(get){
    var t = get.split('&');
    var f = [];
    for (var i=0; i<t.length; i++){
        var x = t[ i ].split('=');
        f[x[0]]=x[1];
    }
    return f;
}
function assemblevars(onearray, towarray){
    var f = [];
    for (const [key, value] of Object.entries(onearray)) {
        f[key]=value;

    }
    for (const [key, value] of Object.entries(towarray)) {
        f[key]=value;
    }
    return f;
}
function ChangeGet(NameNewPage){
    var get = $_GET();
    Params = TransformParamsURLtoJOSON(ParamsRemove);
    for (const [key] of Object.entries(Params)) {
        if(typeof(get[key]) !== undefined){
            delete get[key];
        }
    }
    var prams = TransformParamsURLtoJOSON(NameNewPage);
    get = TransformJSONToURLParams(assemblevars(prams, get));
    return get
}
function page(NameNewPage) {
    
    var page = '';
    if (NameNewPage !== undefined) {
        page = ChangeGet(NameNewPage);
        console.log('Get of new page:'+page);
    } else if (location.search.substring(1) !== '') {
        page = location.search.substring(1);
    } else {
        page = "menu";
    }
    var xmlRequest =  jQuery.ajax({
        url : 'user.php',
        type : 'GET',
        dataType : 'html',
        data : page,
    });
    xmlRequest.done( function(html){ 
        jQuery("body").html(html);
    });
    xmlRequest.fail( function(textStatus){
        jQuery("body").html(textStatus.responseText);
    });
    history.pushState({ path: this.path }, '', '?'+page);
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
function ChangeJudoka(){
    $("#Judoka").change(function(){
        var JudokaSelected = jQuery("#Judoka").val();
        console.log('Judoka Selected:'+ JudokaSelected);
        ParamsRemove = ParamsRemove + '&judoka';
        page( ParamsRemove + '&judoka=' + JudokaSelected);
    });
}

$(document).ready(function() {
    page();
 
});


