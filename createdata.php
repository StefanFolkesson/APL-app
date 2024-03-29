<?php
require_once('funktioner.php');
require_once('db.php');
// admin 
// skapa elev
// skapa period
// skapa företag
// skapa handledare
// skapa placering

// handledare
// skapa registrering
if(all_request_set('hash','loginnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['loginnamn'];
    if (validadmin("tt",$anv)==1){
        // Ny elev
        if(all_request_set('pnr','fnamn','enamn','klass','epost','nyelev')===true){
            create_table_data('elev','pnr','fnamn','enamn','klass','epost');
        }
        // Ny period
        elseif(all_request_set('periodnamn','start','nyperiod','slut')===true){
            create_table_data('period','periodnamn','start','slut');
        } 
        // Ny arbetsplats
        elseif(all_request_set('foretagsnamn','kontaktnummer','epost','nyttforetag')===true){
            create_table_data('arbetsplats','foretagsnamn','kontaktnummer','epost');
        }
        // Ny handledare
        elseif(all_request_set('anvnamn','losenord','fnamn','enamn','foretagid','nyhandledare')===true){
            create_table_data('anvandare','anvnamn','losenord','fnamn','enamn','foretagid');
        }
        // Ny placering
        elseif(all_request_set('personnummer','period','foretagsnamn','nyplacering')===true){
            create_table_data('placering','personnummer','period','foretagsnamn');
        } else {
            giveresponse($default_fail_response);
        }
    }
    else if(validhand($hash,$anv)){
        // datumformat : YY-MM-DD
        if(all_request_set('datum','status','pid','nypresens')===true){
            $dag=$_REQUEST['datum'];

            // TODO: check special days!
            $dt1 = strtotime($dag);
            $dt2 = date("l", $dt1);
            $dt3 = strtolower($dt2);
            if(($dt3 == "saturday" )|| ($dt3 == "sunday"))
            {
                giveresponse($default_fail_response);
            } 
            else
            {
                $status=$_REQUEST['status'];
                $pid=$_REQUEST['pid'];
                $sql="INSERT INTO narvarande (pid,dag,status,registreratdatum) VALUES (?,?,?,now())";
                $stmt= $conn->prepare($sql);
                $stmt->bind_param("isi",$pid,$dag,$status);
                $stmt->execute();
                giveresponse($default_ok_response);
            }
        }
        else {
            giveresponse($default_fail_response);
        }
    }
    else {
        giveresponse($default_fail_hash_response);
    }
}
else {
    giveresponse($default_fail_response);
}
