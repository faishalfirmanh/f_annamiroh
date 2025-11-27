<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr-FR">
<head profile="http://gmpg.org/xfn/11">

<!-- HTML Title -->
<title>FaceBook Like - jQuery and autosuggest Search Engine</title>

<!-- META TAGS-->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="Web2, search engine, autosuggest, facebook, jquery, autocompletion, search" />

<!-- Loading jQuery Framework -->
<script type="text/javascript" src="lib/jquery-1.2.1.pack.js"></script>	
<link rel="stylesheet" href="/assets/css/example.css" type="text/css" media="screen" charset="utf-8">

<!-- Autosuggest module -->
<script type="text/javascript" src="lib/jquery.watermarkinput.js"></script>	
<script type="text/javascript" src="lib/autosuggest/bsn.AutoSuggest_2.1.3.js"></script>	
<link rel="stylesheet" href="lib/autosuggest/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8">	
 
<!-- SearchBar CSS -->
<style type='text/css'>		
	.search_example {
		margin:0px 20px 0px 10px;
	}
	.search_bar {
		position:relative;	
		color:#000000;
		font-weight:bold;
		margin:8px 0px;
		padding:0px 5px;
		height:20px;
	}
	.search_bar form {
		display:inline;
	}	
	.search_bar input {
		font-family:Arial,Helvetica,sans-serif;
		font-size:12px;
	}	
	.search_bar ul {
		line-height:19px;
		list-style-image:none;
		list-style-position:outside;
		list-style-type:none;
		margin:3px 0pt 0pt;
		padding:0pt;
		z-index:10000000;
	}	
	.search_bar li {
		color:#333333;
		float:left;
		font-family:Arial,Helvetica,sans-serif;
		font-size:12px;
		font-weight:bold;
		margin-left:5px;
		margin-right:0px;
		width:auto;
	}	
	.search_bar  input.search_txt {
		background:white url(img/searchglass.png) no-repeat scroll 3px 4px;
		border:1px solid #95A5C6;
		color:#000000;
		font-weight:normal;
		padding:2px 0px 2px 17px;
	}	
	.search_bar input.searchBtnOK {
		background:white none repeat scroll 0%;
		border:1px solid #95A5C6;
		color:#000000;
		font-weight:bold;
		padding:1px;
	}	
	
	.search_response {
		position:relative;
		border:2px solid #f8e89d;
		padding:10px;
		padding-left:50px;
		margin:0px;
		background:#ffffff url(img/kghostview.png) no-repeat 0px 10px;
	}
	
	/* 2.2.5 =Comments
	---------------------------------------------------------------------- */
	#comment_list {
		padding-bottom: 20px;
		margin: 0px 10px 0px 10px;
	}
	
	#comment_list h2 { margin: 50px 0 0; }
	#comment_list form input { margin-bottom: 4px; }
	#comment_list form textarea { width: 80%; padding: 7px 5px; margin-top:6px; }
	#comment_list form a {
		color: #555;
		text-decoration: none;
		border-bottom: 1px dotted #fff;
	}
	#comment_list form a:hover { color: #fff; }
	
	#comment_list ul {
		padding: 0;
		margin: 0;
	}
	#comment_list li {
		position: relative;
		display: block;
		padding: 10px 3px;
		margin: 10px 2px;
		background: #fefefe;
		font-family: Verdana;
		font-size: 13px;
		border: 1px solid #ccc;
		-webkit-box-shadow: 0px 0px 5px #000;
		-moz-box-shadow: 0px 0px 5px #000;
		box-shadow: 0px 0px 5px #000;
	}
	
	#comment_list li img.avatar {
		float: left;
		padding: 2px;
		background: #ccc;
		-webkit-box-shadow: 0 0 5px #000;
		-moz-box-shadow: 0 0 5px #000;
		box-shadow: 0 0 5px #000;
		margin: 3px 15px 3px 10px;
		width: 60px;
		height: 50px;
	}
	
	#comment_list li cite,
	#comment_list li cite a {
		font-weight: bold;
		color: #555;
		text-decoration: none;
		font-size: 14px;
	}
	
	#comment_list li p {
		font-size: 13px;
		line-height: 17px;
		padding: 7px 10px;
	}
	
	#comment_list li p a {
		color: #bf697f;
		text-decoration: none;
		border-bottom: 1px dotted #A839B2;
	}
	
	#comment_list li p a:visited { color: #9e3c80; }
	#comment_list li p a:hover { color: #A839B2; }
	
	#comment_list li p.date {
		position: absolute;
		top: 0px;
		right: 10px;
		text-transform: capitalize;
		font-size: 10px;
		padding: 2px 5px 0;
	}
	
	#comment_list li p.edit {
		position: absolute;
		bottom: 3px;
		right: 10px;
	}
	
	#comment_list li code, #comment_list li pre {
		position: relative;
		display: block;
		color: #262626;
		padding:  0 15px;
	}
	
	.pink { background-color:#d91e4e; color:#FFFFFF; border-color:#d91e4e; }
</style>

<!-- Init AutoSuggest -->
<script type="text/javascript">

/** Init autosuggest on Search Input **/
jQuery(function() {

	//==================== Search With all plugins =================================================
	// Unbind form submit
	$('.home_searchEngine').bind('submit', function() {return false;} ) ;
	
	// Set autosuggest options with all plugins activated & response in xml
	var options = {
		script:"AjaxSearch/_doAjaxSearch.action.php?limit=8&",
		varname:"input",
		shownoresults:true,				// If disable, display nothing if no results
		noresults:"Data tidak ada",			// String displayed when no results
		maxresults:8,					// Max num results displayed
		cache:false,					// To enable cache
		minchars:2,						// Start AJAX request with at leat 2 chars
		timeout:100000,					// AutoHide in XX ms
		callback: function (obj) { 		// Callback after click or selection
			// For example use :
						
			// Build HTML
			var html = "ID : " + obj.id + "<br>Main Text : " + obj.value + "<br>Info : " + obj.info + "<br>Currency : " + obj.currency;
			$('#input_search_all_response').html(html).show() ;
			
			// => TO submit form (general use)
			//$('#search_all_value').val(obj.id); 
			//$('#form_search_country').submit(); 
		}
	};
	// Init autosuggest
	var as_json = new bsn.AutoSuggest('input_search_all', options);
	
	// Display a little watermak	
	$("#input_search_all").Watermark("ex : France, FRA, Paris...");
	
	//==================== Search With "Country" plugin =================================================	
	// Set autosuggest options with all plugins activated
	var options = {
		script:"AjaxSearch/_doAjaxSearch.action.php?&json=true&plugin=country&limit=8&",
		varname:"input",
		json:true,						// Returned response type
		shownoresults:true,				// If disable, display nothing if no results
		noresults:"Data tidak ada",			// String displayed when no results
		maxresults:8,					// Max num results displayed
		cache:false,					// To enable cache
		minchars:2,						// Start AJAX request with at leat 2 chars
		timeout:100000,					// AutoHide in XX ms
		callback: function (obj) { 		// Callback after click or selection
			// For example use :
						
			// Build HTML
			var html = "ID : " + obj.id + "<br>Main Text : " + obj.value + "<br>Info : " + obj.info;
			$('#input_search_country_response').html(html).show() ;
			
			// => TO submit form (general use)
			//$('#search_country_value').val(obj.id); 
			//$('#form_search_country').submit(); 
		}
	};
	// Init autosuggest
	var as_json = new bsn.AutoSuggest('input_search_country', options);
	
	// Display a little watermak	
	$("#input_search_country").Watermark("ex : France, Canada...");	
});
</script>

<!-- Google Analytics Code by WP Goole Analytics Plugin -->
	<script src='http://www.google-analytics.com/urchin.js' type='text/javascript'></script>

	<script type='text/javascript'>
		_uacct = 'UA-12891268-1';
		urchinTracker();
	</script>
	
</head>
	<body>
	
	
		
		<br>
		<div class="search_example"> 
			<div class="search_bar">
			<form method="post" action="/search_engine/" class="home_searchEngine" id="form_search_all">
			<input type="hidden" id="search_all_value" name="search_value">
			<ul>			
			<li><input type="text" size="24" name="search_txt" id="input_search_all" class="search_txt"></li>
			<li><input type="submit" class="searchBtnOK" value="Ok"></li>
			</ul>
			</form>		
			</div>		
			
			<div style="clear:both;margin:0px 10px;height:5px;"></div>
					
			<div style="margin-top:0px;">
				<div class="search_response" style="display:none;" id="input_search_all_response"></div>
			</div>
		</div>
		

		
		
	</body>
</html>