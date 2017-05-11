<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors',1);


	session_start();

	$url = 'http://localhost:8080/IR_Base/SaveUserLog';

	$post_data = array(
			 'displayed_docs' => implode(",",$_SESSION['displayed_docs']),
			 'clicked_docs' => implode(",",$_SESSION['clicked_docs']),
			'number' => '1'
	);

	echo implode(",",$post_data);

	$ch = curl_init();
	echo count($_SESSION['displayed_docs']).'<br>';
	echo $_SESSION['displayed_docs'][0].'<br>';


	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	if(count($_SESSION['displayed_docs'])!=0){
		$displayed_docs = implode(",",$_SESSION['displayed_docs']);
		$clicked_docs = implode(",",$_SESSION['clicked_docs']);
		$search_keyword = $_SESSION['search_keyword'];
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'displayed_docs='.$displayed_docs.'&clicked_docs='.$clicked_docs.'&search_keyword='.$search_keyword);
	}
//
	$output = curl_exec($ch);

	if($output === FALSE){
		echo "cURL Error: ".curl_error($ch);
	}

	curl_close($ch);

	print_r($output);


?>

