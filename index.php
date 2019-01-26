<?php
include 'class.php';
echo alreadyconect('user/');
function alreadyconect($redirect)
{
  session_start();
  if (!empty($_SESSION['id']) and !empty($_SESSION['name'])) return '<script>document.location.href="'.$redirect.'"</script>';
  else return include 'connect.html';
}
?>