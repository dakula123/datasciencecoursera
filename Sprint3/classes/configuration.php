<?php
// client
define("ACTIONNAME","manager");
define("URLPATH","index.php?".ACTIONNAME."=");
if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')
{
	define("SITEROOT","http://localhost/eventos/");	
	define("SITEROOTDOC",$_SERVER['DOCUMENT_ROOT']."/");
}
else
{
	define("SITEROOT","http://www.eventseventos.info/");
	define("SITEROOTDOC",$_SERVER['DOCUMENT_ROOT']."/");
}

?>