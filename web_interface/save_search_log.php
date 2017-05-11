<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors',1);
// Makes a POST call to Servlet to save search log
	function saveSearchLog($userId, $displayed_docs, $search_keyword, $luceneIndex){
		$url = 'http://localhost:8080/IR_Base/SaveSearchLog';
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if(count($displayed_docs)!=0){
			curl_setopt($ch, CURLOPT_POSTFIELDS, '&userId='.$userId.'&search_keyword='.$search_keyword.'&displayed_docs='.$displayed_docs.'&time='.date("Y-m-d H:i:s",time()).'&luceneIndex='.$luceneIndex);
		}
	//
		$output = curl_exec($ch);

		if($output === FALSE){
			echo "cURL Error: ".curl_error($ch);
		}

		curl_close($ch);

		// print_r($output);

	}


?>