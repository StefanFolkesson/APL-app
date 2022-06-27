<?php
// användarid 
// password

// skickar tillbaka
// hash
require_once('db.php');
require_once('funktioner.php');

if(isset($_REQUEST['anv']) && isset($_REQUEST['pass'])){
    $anv = $_REQUEST['anv'];
    $pass= $_REQUEST['pass'];
    // Connect to DB
    $stmt = $conn->prepare("SELECT * FROM anvandare WHERE anvnamn=? and losenord=?");
    $stmt->bind_param("ss", $anv, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        // check ok
        $stmt = $conn->prepare("UPDATE anvandare SET hash=? , expire=? WHERE anvnamn=? and losenord=?");
        $hash ="tt";
        $expire = date('Y-m-d H:i:s', strtotime("+300 minutes"));
        $stmt->bind_param("ssss",$hash ,$expire, $anv, $pass);
        $stmt->execute();
        echo $hash;
        // Fixa till så den blir lite snitsigare. json eller liknande
    }
    else {
        echo "fel inlogg";
        // Fixa till så den blir lite snitsigare. json eller liknande
    }
} else {
    echo "fel indata";
    // Fixa till så den blir lite snitsigare. json eller liknande

}


?>