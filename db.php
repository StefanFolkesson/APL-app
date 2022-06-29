<?php
require_once('globalvariables.php');
require_once('funktioner.php');
// KOppla upp dig mpt databasen

$servername = "localhost";
$username = "root";
$password = "";
$db = "apl";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

if ($conn->connect_errno) {
    giveresponse($default_fail_response);
    exit();
}
?>