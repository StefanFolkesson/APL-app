<?php
require_once('funktioner.php');
require_once('db.php');
// admin 
// skapa elev
// skapa period
// skapa företag
// skapa handledare

// handledare
// skapa registrering

if(isset($_REQUEST['hash']) && isset($_REQUEST['anvnamn'])){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
    // skapa elev
    // skapa period
    // skapa företag
    // skapa handledare

    }
    else if(validhand($hash,$anv)){

        // datumformat : YY-MM-DD
        if(isset($_REQUEST['datum']) and isset($_REQUEST['status']) and isset($_REQUEST['pid'])){
            $dag=$_REQUEST['datum'];
            // TODO: check valid date before insert
            $status=$_REQUEST['status'];
            $pid=$_REQUEST['pid'];
            $sql="INSERT INTO narvarande (pid,dag,status,registreratdatum) VALUES (?,?,?,now())";
            $stmt= $conn->prepare($sql);
            $stmt->bind_param("isi",$pid,$dag,$status);
            $stmt->execute();
            // send verification back maby
        }
        else {
            echo "wrong indata";
        }




    // skapa registrering

    }
    else {
        echo "hash expired";
    }
}
else {
    echo "invalid indata";
}
