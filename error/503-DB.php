<head>
    <meta charset="utf-8">
    <title>Erreur 500</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
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
    

</head>
<body>
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
            <p class="lead">Le serveur contient des erreurs. Il est donc indisponible pour le moment.</p>
            <p> <?= ($DEBUG) ? 'Informations : ['.$e->getCode().'] '.$e->getMessage() : null ?></p>
          </div>
        </div>
  		</div>
	</div>
</div>

</body>