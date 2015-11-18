<?php 
	error_reporting(0); 
	
	session_start();

	include('classes/call.php');

	if ( isset( $_GET['logout'] )) {
		// $_SESSION[CLIENT]==""; 
		// echo '<script type="text/javascript">           						
		// 	window.location = "http://eventseventos.info/"
		// </script>';	
		// die();
	 logout();
	}

	function logout() {
	 unset( $_SESSION[CLIENT] );
	 session_write_close();
	 header( "Location: http://www.eventseventos.info/" );
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Eventos Auditorium Management System</title>
	 <link href="bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
	 <link href="macro/animations.css" rel="stylesheet">
	 <link href="macro/main.css" rel="stylesheet">
	  <link href="macro/hover_pack.css" rel="stylesheet">
	  <!-- <link href="bootstrap_datepicker/css/bootstrap-datepicker.css" rel="stylesheet"> -->
	 <style>
	  body { position: relative; }
	  #section1 {padding-top:50px;height:auto;color: ffff; background-color: white;}
	  #section2 {padding-top:50px;height:auto;color: ffff; background-color:white;}
	  #section3 {padding-top:50px;height:auto;color: ffff; background-color: white;}
	  #section4 {padding-top:50px;height:auto;color: ffff; background-color:white;}
	  #section5 {padding-top:50px;height:auto;color: ffff; background-color: white;}
	  .error{background-color:red;}
	  </style>
     <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <!--  <script src="bootstrap_datepicker/js/bootstrap-datepicker.js"></script>	 -->
 </head>
 <body data-spy="scroll" data-target=".navbar" data-offset="50">