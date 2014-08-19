<?php
#MAGIC QUOTES SUCK!
function mqs($string)
  {
  if (get_magic_quotes_gpc())
    return stripslashes($string);
  else
    return $string;
  }
function err($message)
  {
  require "header.php";
  echo "<h1>Error:</h1>";
  echo "<p>$message</p>";
  echo "<p>If you are receiving this error message you may instead RSVP via phone.</p>";
  require "footer.php";
  die();
  }
$global = true;
?>
