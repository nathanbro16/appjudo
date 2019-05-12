<?php
require_once 'functions/auth.php';
require_once 'conf.php';

BDD(isset($_GET['DEBUG']));


if (is_connect()){
  ?> <script>
  document.location.href="user/";
  </script> <?php
}elseif(!empty($_SESSION)){
  session_unset();
  header('location: index.php');
}elseif(empty($_SESSION)){
  require_once 'connect.html';
}

?>