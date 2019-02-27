<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item <?php if (isset($_GET['menu'])) echo 'active'; ?>">
	        <a class="nav-link" href="javascript:page('menu')">Menu</a>
	      </li>
	      <li class="nav-item dropdown <?php if (isset($_GET['calendrier']) || isset($_GET['calendrier-ins'])) echo 'active'; ?>">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-offset="flip" aria-haspopup="true" aria-expanded="false">Calendrier</a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item <?php if (isset($_GET['calendrier'])) echo 'active'; ?>" href="javascript:page('calendrier');" >Evènements</a>
	          <a class="dropdown-item <?php if (isset($_GET['calendrier-ins'])) echo 'active'; ?>" href="javascript:page('calendrier-ins')">Inscription</a>
	          <div class="dropdown-divider"></div>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          Profil
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="#">Action</a>
	          <a class="dropdown-item" href="#">Another action</a>
	          <div class="dropdown-divider"></div>
	          <a class="dropdown-item" href="#">Something else here</a>
	        </div>
	      </li>
	    <li class="nav-item">
	        <a class="nav-link disabled" href="#">Disabled</a>
	      </li>
	     <li class="nav-item">
	       <a class="btn btn-outline-danger" href="../deconnect.php"> <i class="fas fa-sign-out-alt"></i> Déconnection</a>
	    </li>
	    </ul>
	</div>

</nav>
