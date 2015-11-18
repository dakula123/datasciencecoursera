<?php
if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')
{
	define("DBSERVER","localhost");
	define("DBUSER","root");
	define("DBPASSW","");
	define("DBNAME","eventseventos");
}
else
{
	define("DBSERVER","localhost");
	define("DBUSER","dakula1234");
	define("DBPASSW","Eventos1234");
	define("DBNAME","eventseventos");
}
?>