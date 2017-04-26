<?php
  session_start();

  
if(!isset($_SESSION['clicked_doc'])){

    echo "session was not set";
    $_SESSION['clicked_doc'] = "";

    echo "session set to ".$_SESSION['clicked_doc'];
  }

    $_SESSION['clicked_doc'] = $_SESSION['clicked_doc'].",".$_POST['clickthrough'];
    echo "session set to ".$_SESSION['clicked_doc'];


?>

