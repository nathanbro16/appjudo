<style type="text/css">
    body,
    html{
    height: 100%;
    }
    body {
    background: #007bff;
    background: -moz-linear-gradient(-45deg, #ff2483 0%, #19b7f0 100%);

    }
    .container-fluid {
    position : relative;
    top: 50%;
    transform: translateY(-50%);}
</style>
<div class="container-fluid">
    <div class="jumbotron jumbotron-fluid" data="gh">
  		<div class="container">
        <div class="row">
          <div class="col-1"></div>
          <div class="col-3">
            <div class="fas fa-ban" style="font-size: 200px"></div>
          </div>
          <div class="col">
            <h1 class="display-3">Oups! Erreur!</h1>
            <hr class="my-4">
            <p class="lead">La page souhaiter n'as pas été trouver.</p>
            <a class="btn btn-primary btn-lg btn-sm" href="../">Menu</a>
          </div>
        </div>
  		</div>
	</div>
</div>
<script>
$("title").text('Erreur 404');
</script>
