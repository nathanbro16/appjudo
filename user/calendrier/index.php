
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
			jQuery('#event').on('hidden.bs.modal', function (e) {
  			jQuery('#event').remove();
			});

		},
		error : function(resultat, statut, erreur){

		},

		complete : function(resultat, statut){

		}

		});
	}
	jQuery(function() {

	    jQuery('#calendar').fullCalendar({
			plugins: [ 'dayGrid', 'timeGrid' ],
			header: {
	      left: 'month,agendaWeek',
	      center: 'title',
	      right: 'MyButtonAdd prev,next'
    	},
			customButtons: {
		    MyButtonAdd: {
		      text: 'Ajouter',
		      click: function() {
		        getadd();
		      }
		    }
		  },
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
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
		$('#description').froalaEditor({
			enter: $.FroalaEditor.ENTER_P,
      zIndex: 8000,
			language: 'fr'
		})
	},
	error : function(resultat, statut, erreur){
	},
	complete : function(resultat, statut){
	}
	});
}
$('#event').on('hide.bs.modal', function (e) {
	console.log('lala0');
  $('#description').summernote('destroy');
})
</script>

	<div class="card" >
  		<div class="card-body" id="calendar">
  		</div>
	</div>
	<div id="datavent"></div>
	<div id="actionJS"></div>
