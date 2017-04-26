<?php
  // session_start();

  
  // if(!isset($_SESSION['clicked_doc'])){
  //   echo "session was not set";
  //   $_SESSION['clicked_doc'] = "";
  // //  echo "session set to ".$_SESSION['clicked_doc'];
  // }
  // else{
  // echo "session set to ".$_SESSION['clicked_doc'];
//  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Papaer Recommender</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <link href="css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">

  <!-- <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>

<body>
  <!-- Header -->
  <section class="banner">
    <div class="header main_header">
     <h1><a href="index.php">Automatic Paper Recommender </a></h1>
   </div>

   <!-- Main jumbotron for a primary marketing message or call to action -->
   <div class=" ">
    <center>
      <div class="container main_container">
        <form class="" action='save_action.php' method='get'>
          <div class="">                        
            <div class="row">
              <div class= "form-group">
                <input size = 100 type="text" name='search_keyword' placeholder="Search Keyword" class="form-control input-md">
              </div>
            </div>       <!--               
            <div class="row form-inline">
              <label for="num_pages"># of pages to crawl: </label>

              <div class= "form-group">
                <div class="input-group spinner">
                  <input type="text" name="num_pages" class="form-control" value="1">
                  <div class="input-group-btn-vertical">
                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                  </div>
                </div>
              </div> -->

              <!--         <input size =30 type="text" placeholder="# of pages to crawl" name="num_pages" class="form-control input-sm">  -->

            </div>
          </div>
          <div class="row">
            <div class= "form-group">
              <button type="submit" class="btn btn-primary search_button">Search</button>
            </div>
          </div>
        </form>
      </div>
    </center>
  </div>
</section>
<center>
  <footer>
    <p>&copy; 2017 <a href='http://www.cs.virginia.edu/~hw5x/'>Prof. Hongning Wang</a> - <a href='http://www.cs.virginia.edu'>Department of Computer Science</a> - <a href='http://www.virginia.edu'>University of Virginia</a></p>
  </footer>
</center>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="js/ui.js"></script>
          <!-- 
          <script src="bootstrap-3.3.7-dist/js/bootstrap.js"></script>
        -->	
</body>
</html>