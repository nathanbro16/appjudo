
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
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay',
			},

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


	<div class="card" >
  		<div class="card-body" id="calendar">
  		</div>
	</div>
	<div id="datavent"></div>
