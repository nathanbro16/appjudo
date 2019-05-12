<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item <?= isset($_GET['menu']) ? 'active' : null ;?>">
	        <a class="nav-link" href="<?= isset($_GET['menu']) ? null : 'javascript:page(\'menu\')' ;?>">Menu</a>
	      </li>
	      <li class="nav-item dropdown <?= (isset($_GET['calendrier']) || isset($_GET['calendrier-ins'])) ? 'active' : null; ?>">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-offset="flip" aria-haspopup="true" aria-expanded="false">Calendrier</a>
	        <div class="dropdown-menu animate slideIn" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item <?= isset($_GET['calendrier']) ? 'active' : null ; ?>" href="javascript:page('calendrier');" >Evènements</a>
	          <a class="dropdown-item <?= isset($_GET['calendrier-ins']) ? 'active' : null ; ?>" href="javascript:page('calendrier-ins')">Inscription</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown <?= isset($_GET['profil-info']) ? 'active' : null; ?>">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          Profil
	        </a>
	        <div class="dropdown-menu animate slideIn" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item <?= isset($_GET['profil-info']) ? 'active' : null; ?>" href="javascript:page('profil-info');">Informations</a>
	          <a class="dropdown-item" href="#">Another action</a>
	          <div class="dropdown-divider"></div>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	     	<li class="nav-item">
	       <a class="btn btn-outline-danger" href="../deconnect.php"> <i class="fas fa-sign-out-alt"></i> Déconnection</a>
	    	</li>
	    </ul>
	</div>

</nav>

