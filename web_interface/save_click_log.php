<?php

// Makes a POST call to Servlet to save click log
   $userId= $_POST["userId"];
   $search_keyword= $_POST["search_keyword"];
   $clicked_WOS= $_POST["clicked_WOS"];
   $position= $_POST["position"];
   $luceneIndex= $_POST["luceneIndex"];

    $url = 'http://localhost:8080/IR_Base/SaveClickLog';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '&userId='.$userId.'&search_keyword='.$search_keyword.'&clicked_WOS='.$clicked_WOS.'&position='.$position.'&time='.date("Y-m-d H:i:s",time()).'&luceneIndex='.$luceneIndex);
//
    $output = curl_exec($ch);

    if($output === FALSE){
        echo "cURL Error: ".curl_error($ch);
    }

    curl_close($ch);

    print_r($output);
?>

