<?php
  // session_start();

  
  // if(!isset($_SESSION['clicked_doc'])){
  //   echo "session was not set";
  //   $_SESSION['clicked_doc'] = "";
  // //  echo "session set to ".$_SESSION['clicked_doc'];
  // }
  // echo "session set to ".$_SESSION['clicked_doc'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="css/style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">

	<link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>

	<title>Search Result</title>
</head>
<body>
	<div class="search_bar_top_header">
		<div class="search_bar_top">
			<form class="form-horizontal" role="form" action='save_action.php' method='get'>
				<div class="row">						
					<div class="col-lg-7 banner_label"><h4><a href="index.php">Automatic Paper Recommender </a></h4></div>
<!--					<div class="col-lg-4">
 						<label class= "control-label col-sm-6 blue_label" for="num_pages"># of pages to crawl: </label>
						<div class="input-group spinner ">
							<input type="text" name="num_pages" class="form-control" value="1">
							<div class="input-group-btn-vertical">
								<button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
								<button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
							</div>
						</div> 
					</div>-->
					<div class="col-lg-5 search_bar_top_button">
						<div class="input-group">
							<input type="text" class="form-control" name='search_keyword' placeholder="Search for...">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-primary search_button_top" type="button">Search</button>
							</span>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>

	<div class="containter table_background">
		<?php
		

		$search_keyword = str_replace(" ", "%20", $_GET["search_keyword"]);
//		$num_pages = $_get['num_pages'];

		// function get_url($request_url) {
		//   $ch = curl_init();
		//   curl_setopt($ch, CURLOPT_URL, $request_url);
		//   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//   $response = curl_exec($ch);
		//   curl_close($ch);

		//   return $response;
		// }

		// $request_url = 'http://localhost:8080/IR_Base/MainServlet';

		// ?search_keyword=	+$search_keyword+'num_pages='+$num_pages

		//	'http://timan100.cs.uiuc.edu:8080/MedForums/MedForumSearch?query=open';
		// $response = get_url($request_url);

		// echo $response;

		// $(document).ready(function() {
		// 	 $.ajax({
	 //            url : 'http://localhost:8080/IR_Base/MainServlet',
	 //            data : {search_keyword= $search_keyword ,num_pages=$num_pages},
	 //            type : 'GET',
	 //            success : function(response) {
	 //                alert("Success");
	 //                // create an empty div in your page with some id
	 //            },
	 //            error: function(){ alert("error");

		// 	            }
		// 	        });
		// 	);
		// });


	// echo "Search url:<br/>\n".$search_keyword;
	// echo "<br/>\n";
	// echo "Number of pages go crawl: ".$num_pages;
	// echo "<br/>\n";

		$date = date('y-m-d h:i:s');

	//	$command = "java -jar IR_Base.jar ";
		$url = "http://localhost:8080/IR_Base/SearchQuery?search=".$search_keyword;

	//  Example hospital search result
	//	$url = "http://timan100.cs.uiuc.edu:8080/MedForums/MedForumSearch?query=".$search_keyword;

		$json = file_get_contents($url);
	//	echo $json;
		$json = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
	//	echo $json;
		$obj = json_decode($json, true)["Reviews"];
	//	$obj = json_decode($json, true);		//original hospital form
	//	echo $obj;
		$length= count($obj);
	//	echo $length;
		if($length>0){
			for ($index = 0; $index < $length; $index++) {

				if ($index >= 100)
					break;

				$position = $index + 1;
				$json_attr = $obj[$index];
				$content = preg_replace('/((http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1" target="_new">\1</a>', $json_attr['abstrct']);
		//		$snippet = $json_attr['Snippet'];
				$author = $json_attr['authors'];
				$author_array = explode(";",$author);
				if(sizeof($author_array)>5){		//Only display first 5 authors
					$author = "";
					for($i = 0; $i < 5; $i++){
						$author = $author."; ".$author_array[$i];
					}
					$author = substr($author, 1);
					$author = $author." et. al. ";
				}
				$journalInfo = $json_attr['journalInfo'];
				$journalName = explode("Volume:",$json_attr['journalInfo'])[0];
				$journalIssue = explode("Published:",explode("Volume:",$json_attr['journalInfo'])[1])[0];
				$date = explode("Published:",$json_attr['journalInfo'])[1];


				$docInfo = $json_attr['documentInfo'];
				$WOS_Num = explode(" ",explode("WOS:",$docInfo)[1])[0];
		//		$URL = $json_attr['URL'];
				$title = $json_attr['title'];
				$categories = $json_attr['categories'];
				// $authorID = preg_replace('/((http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1" target="_new">\1</a>', $json_attr['authorID']);

				// $date_pieces = explode ("-", $date);
				// $year =  substr ($date_pieces[0], 0, 4);
				// $month =  substr ($date_pieces[0], 4, 2);
				// $day =  substr ($date_pieces[0], 6, 2);
/****************	attributes used for exmaple hospital code		***************/
				// $position = $index + 1;
				// $json_attr = $obj[$index];
				// $content = preg_replace('/((http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1" target="_new">\1</a>', $json_attr['content']);
				// $snippet = $json_attr['snippet'];
				// $author = $json_attr['author'];
				// $replyTo = $json_attr['replyTo'];
				// $postID = $json_attr['postID'];
				// $URL = $json_attr['URL'];
				// $date = $json_attr['date'];
				// $authorID = preg_replace('/((http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1" target="_new">\1</a>', $json_attr['authorID']);

				// $date_pieces = explode ("-", $date);
				// $year =  substr ($date_pieces[0], 0, 4);
				// $month =  substr ($date_pieces[0], 4, 2);
				// $day =  substr ($date_pieces[0], 6, 2);


		//		$sql = "SELECT COUNT(*) FROM annotation WHERE COMP_ID = '$ir_uid' AND DOCUMENT_ID = '$postID' AND QUERY = '$search_keyword'";
		//		$res = mysql_query ($sql);
		//		$rows = mysql_fetch_row ($res);

				if ( 0) {
					$display_eval_form = "none";
					$display_eval_done = "block";
				} else {
					$display_eval_form = "block";
					$display_eval_done = "none";
				}
				echo "

				
				<div>
					<table class='search_results_table'>
						<tr>
							<td class=\"search_results_number\">$position
							</td>
							<td class='search_results_content col-lg-12'>
								<table>
									<tr><td class='search_results_title'>
										<h4>$title</h5>
									</td></tr>
									
									<tr><td>
										By: $author
									</td></tr>

									<tr><td>
										<B>$journalName</B> Volume: $journalIssue
									</td></tr>

									<tr><td>
										$date 
									</td></tr><tr><td>
										Categories: $categories 
									</td></tr>

									<tr><td>
										<div id = 'snippet_$index' style='display: block'>
										<a role='button' class='btn-sm btn-primary' href=\"javascript:read_more('snippet_$index', 'content_$index', '$WOS_Num', '$search_keyword');\" id='document_$index'>View Abstract</a>
										</div>
										<div id = 'content_$index' style='display: none'>
											<a role='button' class='btn-sm btn-primary' href=\"javascript:read_less('snippet_$index', 'content_$index', '$WOS_Num', '$search_keyword');\" id='document_$index'>Close Abstract</a>
											<h5>
												$content
											</h5>
										</div>
									</td></tr>

								</table>
							</td>
							<td class='search_results_data'>
								<b>Times Cited: </b>
							</td>

						</tr>

							
			
					</table>
				</div>
				";
				// echo "
				// <div>
				// 	<table class='table table-bordered table_color'>
				// 		<tr>
				// 			<td>
				// 				Author: <B>$author</B> (Post Date: $month/$day/$year $date_pieces[1])
				// 			</td>

				// 		</tr>
				// 		<tr>
				// 			<td width=80%>
				// 				<div id = 'snippet_$index' style='display: block'>
				// 					<h5>$title <a href=\"javascript:read_more('snippet_$index', 'content_$index', '$postID', '$search_keyword');\" id='document_$index'><B>read more</B></a></h5>
				// 				</div>
				// 				<div id = 'content_$index' style='display: none'>
				// 					<h5>
				// 						$content <a href=\"javascript:read_less('snippet_$index', 'content_$index','$postID', '$search_keyword');\"><B>read less</B></a>
				// 					</h5>
				// 				</div>
				// 				<!--<div id='result$index'></div>-->
				// 			</td>
				// 		</tr>
				// 	</table>
				// </div>
				// ";
			}
		}
		else{
			echo "<h4>No results found. New documents are now crawled. Please try again later.</h4>.";
		}

		?>

	</div>
	<center>
		<footer>
			<p>&copy; 2017 <a href='http://www.cs.virginia.edu/~hw5x/'>Prof. Hongning Wang</a> - <a href='http://www.cs.virginia.edu'>Department of Computer Science</a> - <a href='http://www.virginia.edu'>University of Virginia</a></p>
		</footer>
	</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="js/readMore.js"></script>

	<script src="js/ui.js"></script>
</body>
</html>
