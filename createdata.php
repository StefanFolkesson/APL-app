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
            create_table_data('elev','pnr','fnamn','enamn','klass','epost');
        }
        // Ny period
        elseif(all_request_set('periodnamn','start','nyperiod','slut')===true){
            create_table_data('period','periodnamn','start','slut');
        } 
        // Ny arbetsplats
        elseif(all_request_set('foretagsnamn','kontaktnummer','eport','nyttforetag')===true){
            create_table_data('arbetsplats','foretagsnamn','kontaktnummer','eport');
        }
        // Ny handledare
        elseif(all_request_set('anvandarnamn','losenord','fnamn','enamn','foretagid','nyhandledare')===true){
            create_table_data('anvandare','anvandarnamn','losenord','fnamn','enamn','foretagid');
        }
        // Ny placering
        elseif(all_request_set('pnr','period','foretagnamn','nyplacering')===true){
            create_table_data('placering','pnr','period','foretagnamn');
        } else {
            giveresponse($default_fail_response);
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
