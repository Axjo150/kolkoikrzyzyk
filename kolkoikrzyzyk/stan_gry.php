<?php
session_start();

require "functions.php";

$sessionID = session_id();
$gra = find_or_create_game($sessionID);
$jsonOutput = json_encode($gra);
echo $jsonOutput;
?>
