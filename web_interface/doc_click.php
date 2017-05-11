<?php
  session_start();

  
if(!isset($_SESSION['clicked_doc'])){

    echo "session was not set";	//to be deleted
    $_SESSION['clicked_doc'] = "";//to be deleted

    $_SESSION['clicked_docs'] = array();
    $_SESSION['displayed_docs'] = array();

    echo "session set to ".$_SESSION['clicked_doc'];//to be deleted
  }

    $_SESSION['clicked_doc'] = $_SESSION['clicked_doc'].",".$_POST['clickthrough'];	//to be deleted
//    $_SESSION['clicked_docs'][0] = $_POST['time_spent']
    $_SESSION['clicked_docs'][$_POST['position']] = $_POST['time_spent'];	// Set clicked time to the clicked_docs array



    echo "session set to ".$_SESSION['clicked_doc'];		//to be deleted


?>

