
<script>

	function getevent($id){
		jQuery('#event').modal('hide');   
		jQuery.ajax({

		url : 'calendrier/event.php',
		type : 'GET',
		data : 'id=' + $id,
		statusCode: {
	       		403 : function(){
			       	jQuery.ajax({

				       url : '../error/403.php',

				       type : 'GET',
				       dataType : 'html', // On désire recevoir du HTML
				       success : function(data, statut){ // code_html contient le HTML renvoyé
						    jQuery("body").html(data);
				       },
				       error : function(data, statut, erreur){ // code_html contient le HTML renvoyé
				

				       },
				       


				    });
				},
					
		},
		success : function(data){
			jQuery("#datavent").html(data);
			jQuery('#event').modal('show');   

		},
		error : function(resultat, statut, erreur){

		},

		complete : function(resultat, statut){

		}

		});
	}
	jQuery(function() {

	    jQuery('#calendar').fullCalendar({
			themeSystem: 'bootstrap4',
			contentHeight: 700,
			weekNumbers: true,
			nowIndicator: true,
			locale: 'fr',
			eventLimit: true, // allow "more" link when too many events
			eventSources: ['calendrier/js.php'],
			views: {
				month: {
					columnFormat: 'ddd',
				},
				week: {
					columnFormat: 'ddd DD'
				}

			},
			eventColor: '#2980b9'
	    });

	});
	
</script>
<script>
	function getadd(){
	jQuery.ajax({
	url : 'calendrier/add.php',
	type : 'GET',
	data : 'interfaceadd',
	success : function(data){
		jQuery('#actionJS').html(data);
		jQuery('#add').modal('show');   
		$('#description').summernote({dialogsInBody: true,});
	},
	error : function(resultat, statut, erreur){
	},
	complete : function(resultat, statut){
	}
	});
}
function getedit($id){
	jQuery.ajax({
	url : 'calendrier/edit.php',
	type : 'GET',
	data : 'id=' + $id + '&InterfaceEdit=1',
	success : function(data){
		jQuery('.modal-content').html(data);
		$('#description').summernote({dialogsInBody: true,});
	},
	error : function(resultat, statut, erreur){
	},
	complete : function(resultat, statut){
	}
	});
}
</script>
<style>
 	.calendar__button{
 		display: block;
 		width: 55px;
 		height: 55px;
 		line-height: 55px;
 		text-align: center;
 		color: #FFF;
 		font-size: 30px;
 		background-color: #007bff;
 		border-radius: 50%;
 		box-shadow: 0 6px 10px 0 #0000001a,0 1px 18px 0 #0000001a,0 3px 5px -1px #0003;
 		position: absolute;
 		bottom: 30px;
 		right: 30px;
 		text-decoration: none;
		transition: transform 0.3s;
		z-index:10;
 	}
 	.calendar__button:hover{
 		 color: #FFF;
 		text-decoration: none;
 		transform: scale(1.2);
 	}
</style>
<a href="javascript:getadd();" class="calendar__button">+</a>




	<div class="card" >
  		<div class="card-body" id="calendar">
  		</div>
	</div>
	<div id="datavent"></div>
	<div id="actionJS"></div>
