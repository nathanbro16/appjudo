<?php
require_once 'functions/auth.php';
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