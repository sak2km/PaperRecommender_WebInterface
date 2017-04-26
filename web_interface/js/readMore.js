

function read_more(snippetId, contentId, str_postid, search_keyword) {

	var snip = document.getElementById(snippetId);
	snip.style.display = "none";

	var con = document.getElementById(contentId);
	con.style.display = "block";

	var tempStr= "{"+'"'+"WOS_Num"+'"'+":"+ str_postid + ","+'"'+"searchKeyword"+'"'+":" + search_keyword + "}";
	alert(tempStr);

	// fire off the request to /redirect.php
	request = $.ajax({
	    url: "/web_interface/doc_click.php",
	    type: "post",
	    data: {clickthrough:tempStr}
	});
	// callback handler that will be called on success
	request.done(function (response, textStatus, jqXHR){
	    // log a message to the console
	    console.log("New clickthrough data added to session!");
	});

	// callback handler that will be called on failure
	request.fail(function (jqXHR, textStatus, errorThrown){
	    // log the error to the console
	    console.error(
	        "The following error occured: "+
	        textStatus, errorThrown
	    );
	});
	/* save user action */	
	// var str_param = "docid=";
	// var str = str_param.concat(str_postid);
	// var url = 'save_action.php?type=D&search_keyword=<?php echo $search_keyword;?>';


 //    xmlReq=new XMLHttpRequest();
 //    xmlReq.open("POST",url,true);
 //    xmlReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 //    xmlReq.setRequestHeader("Content-length", str.length);
 //    xmlReq.setRequestHeader("Connection", "close");
 //    xmlReq.send(str);
    console.log("done");

	// if ($user_eval != -1)
	// 	$sql = "INSERT INTO action (COMP_ID, TIMESTAMP, ACTION, CONTENT) VALUES ('$ir_uid', now(), '$type', '$content')";

} 
function read_less(snippetId, contentId, str_postid, search_keyword) {

	var snip = document.getElementById(snippetId);
	snip.style.display = "block";

	var con = document.getElementById(contentId);
	con.style.display = "none";
} 



/*
function submit_evaluation (objForm)
{
	var returnStatus = 1;

	if (objForm.user_eval.selectedIndex == -1) {
		returnStatus = 0;
	};

	if (returnStatus) {
		var cur_time = new Date();
		var milisec_diff = (cur_time.getTime() - load_time.getTime())/1000;
		objForm.time_spent.value = milisec_diff;
		objForm.submit();
	}
}
*/