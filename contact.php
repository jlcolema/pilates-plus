<?php

/*

*/
//this is where you will edit your email address

$my_email = "mimi@pilates-plus.net";

//this is where the follow page will go e.g anything at wherever --blah blah blah
$continue = "index.html";



$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Check all fields for an email header.

function recursive_array_check_header($element_value)
{

global $set;

if(!is_array($element_value)){if(preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_header($value);}

}

}

recursive_array_check_header($_REQUEST);

if($set){$errors[] = "You cannot send an email header";}

unset($set);

// lets make sure everything works

if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{

if(preg_match("/(%0A|%0D|\n+|\r+|:)/i",$_REQUEST['email'])){$errors[] = "Email address may not contain a new line or a colon";}

$_REQUEST['email'] = trim($_REQUEST['email']);

if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check & make sure this is from the same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form or bot.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($_REQUEST);

if(!$set){$errors[] = "You cannot send a blank form please enter something";}

unset($set);

// Display any errors.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}

$message = build_message($_REQUEST);

$message = $message . PHP_EOL.PHP_EOL."".PHP_EOL."";

$message = stripslashes($message);

$subject = "Pilates Plus -- Contact Form";

$headers = "From: " . $_REQUEST['email'];
$headers .= PHP_EOL;
$headers .= "Return-Path: " . $_REQUEST['email'];
$headers .= PHP_EOL;
$headers .= "Reply-To: " . $_REQUEST['email'];

mail($my_email,$subject,$message,$headers);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="" />
	<meta name="description" content="Pilates Plus, a pilates studio in Noblesville, Indiana." />
	<meta http-equiv="refresh" content="5; url=http://www.pilates-plus.net/index.html">
	<meta name="keywords" content="pilates, workout, exercise, noblesville, carmel, fishers, westfield, indianapolis, hamilton county, hamilton" />
	<link rel="shortcut icon" type="image/ico" href="media/favicon.ico" />

	<title>Thank You for Contacting Pilates Plus!</title>

	<link type="text/css" href="css/screen.css" rel="stylesheet" media="screen" />
	<link type="text/css" href="css/print.css" rel="stylesheet" media="print" />

</head>

<body id="contact-page">

	<!-- Header -->

	<div id="header">

		<h1><a href="index.html" title="Pilates Plus">Pilates Plus</a></h1>

		<ul id="navigation">
			<li id="home"><a href="index.html" title="Home">Home</a></li>
			<li id="about"><a href="about.html" title="About Pilates +">About</a></li>
			<li id="services"><a href="services.html" title="Services">Services</a></li>
			<li id="contact"><a href="contact.html" title="Contact Us">Contact</a></li>
		</ul>

		<p id="login"><a href="#" title="">Located inside <em>of</em> Fitness Trainers &rarr;</a></p>

	</div>

	<!-- Body -->

	<div id="body">

		<div id="banner">

			<p>Thank You!</p>

		</div>

	</div>

	<!-- Footer -->

	<div id="footer">

		<ul>
			<li>Copyright 2009, Pilates +.</li>
			<li>14701 Cumberland Road</li>
			<li>Noblesville, IN 46060</li>
			<li>(317) 985-7326</li>
		</ul>

	</div>

	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-13048765-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>

</body>

</html>