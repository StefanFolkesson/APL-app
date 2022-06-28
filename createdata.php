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

if(all_request_set('hash','anvnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
    // skapa elev
    // skapa period
    // skapa företag
    // skapa handledare
        // Ny elev
        if(all_request_set('pnr','fnamn','enamn','klass','epost','nyelev')===true){
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
        elseif(all_request_set('periodnamn','start','nyperiod','slut')===true){
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
        elseif(all_request_set('foretagsnamn','kontaktnummer','eport','nyttforetag')===true){
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
        elseif(all_request_set('anvandarnamn','losenord','fnamn','enamn','foretagid','nyhandledare')===true){
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
        elseif(all_request_set('pnr','period','foretagnamn','nyplacering')===true){
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
        if(all_request_set('datum','status','pid')===true){
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

    }
    else {
        echo "hash expired";
    }
}
else {
    echo "invalid indata";
}
