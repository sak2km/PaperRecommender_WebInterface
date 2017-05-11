

function read_more(snippetId, contentId, WOS_Num, search_keyword, doc_position, userIP, luceneIndex) {

	// Handles UI changes in showing abstract field.
	var snip = document.getElementById(snippetId);
	snip.style.display = "none";
	var con = document.getElementById(contentId);
	con.style.display = "block";

	// Fire off the request to /save_click_log.php, which then makes a POST call to Servlet to save click log
	request = $.ajax({
	    url: "/web_interface/save_click_log.php",
	    type: "post",
	    data: {clicked_WOS:WOS_Num, position:doc_position, search_keyword:search_keyword, userId:userIP, luceneIndex:luceneIndex}

	});

	request.done(function (response, textStatus, jqXHR){	// success handler
	    console.log("New clickthrough data added to session!");
	});
	request.fail(function (jqXHR, textStatus, errorThrown){	//failure handler
	    console.error(
	        "The following error occured: "+
	        textStatus, errorThrown
	    );
	});

} 


function read_less(snippetId, contentId, WOS_Num, search_keyword) {

	var snip = document.getElementById(snippetId);
	snip.style.display = "block";

	var con = document.getElementById(contentId);
	con.style.display = "none";
}