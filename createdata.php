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
        // Ny elev
        if(isset($_REQUEST['pnr']) and 
           isset($_REQUEST['fnamn']) and 
           isset($_REQUEST['enamn']) and 
           isset($_REQUEST['klass']) and
           isset($_REQUEST['epost']) and 
           isset($_REQUEST['nyelev'])
           ){
            $pnr=$_REQUEST['pnr'];
            $fnamn=$_REQUEST['fnamn'];
            $enamn=$_REQUEST['enamn'];
            $klass=$_REQUEST['klass'];
            $epost=$_REQUEST['epost'];
            $sql="INSERT INTO elev (pnr,fnamn,enamn,klass,epost) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss",$pnr,$fnamn,$enamn,$klass,$epost);
            $stmt->execute();
            // SEnd verification

        }
        // Ny period
        elseif(isset($_REQUEST['periodnamn']) and 
           isset($_REQUEST['start']) and 
           isset($_REQUEST['nyperiod']) and  
           isset($_REQUEST['slut'])  
           ){
            $periodnamn=$_REQUEST['periodnamn'];
            $start=$_REQUEST['start'];
            $slut=$_REQUEST['slut'];
            $sql="INSERT INTO period (periodnamn,start,slut) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss",$periodnamn,$start,$slut);
            $stmt->execute();
            // SEnd verification

        } 
        // Ny arbetsplats
        elseif(isset($_REQUEST['foretagsnamn']) and 
           isset($_REQUEST['kontaktnummer']) and 
           isset($_REQUEST['eport']) and  
           isset($_REQUEST['nyttforetag'])  
           ){
            $foretagsnamn=$_REQUEST['foretagsnamn'];
            $kontaktnummer=$_REQUEST['kontaktnummer'];
            $epost=$_REQUEST['epost'];
            $sql="INSERT INTO arbetsplats (foretagsnamn,kontaktnummer,epost) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss",$foretagsnamn,$kontaktnummer,$epost);
            $stmt->execute();
            // SEnd verification

        }
        // Ny handledare
        elseif(isset($_REQUEST['anvandarnamn']) and 
           isset($_REQUEST['losenord']) and 
           isset($_REQUEST['fnamn']) and  
           isset($_REQUEST['enamn']) and  
           isset($_REQUEST['foretagid']) and  
           isset($_REQUEST['nyhandledare'])  
           ){
            $anvandarnamn=$_REQUEST['anvandarnamn'];
            $losenord=$_REQUEST['losenord'];
            $fnamn=$_REQUEST['fnamn'];
            $enamn=$_REQUEST['enamn'];
            $foretagid=$_REQUEST['foretagid'];
            $sql="INSERT INTO anvandare (admin,anvandarnamn,losenord,fnamn,enamn,foretagid) VALUES (0,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss",$anvandarnamn,$losenord,$fnamn,$enamn,$foretagid);
            $stmt->execute();
            // SEnd verification

        }
        // Ny placering
        elseif(isset($_REQUEST['pnr']) and 
           isset($_REQUEST['period']) and 
           isset($_REQUEST['foretagnamn']) and  
           isset($_REQUEST['nyplacering'])  
           ){
            $pnr=$_REQUEST['pnr'];
            $period=$_REQUEST['period'];
            $foretagnamn=$_REQUEST['foretagnamn'];
            $sql="INSERT INTO placering (personnemmer,period,foretagsnamn) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss",$pnr,$period,$foretagnamn);
            $stmt->execute();
            // SEnd verification

        }

        else {
            echo "wrong indata";
        }
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
