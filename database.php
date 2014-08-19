<?php
require "global.php";

# set your infomation.
$host		=	'localhost';
$user		=	'rsvp';
$pass		=	'Its a secret to everybody';
$database	=	'rsvp';

# connect to the mysql database server.

try 
    {
    $db = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
    }
catch (PDOException $e)
    {
    err($e->getMessage());
    }
$pass = ""; #just for safety's sake
?>

