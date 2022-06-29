<?php
require_once('funktioner.php');
require_once('db.php');// admin
// ändra  elev
// ändra  period
// ändra företag
// ändra handledare
// ändra uteslutna dagar


//handledare
// ändra frånvaro
// TODO: denna procedur måste knna göras mycket snyggare med en funktion då allt arbete är mer eller mindre repetetivt. 


if(all_request_set('hash','anvnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
        // Ny elev
        if(all_request_set('editelev','originpnr')===true){
            edit_table_data('elev','pnr','originpnr','pnr','fnamn','enamn','klass','epost');
        }
        // Edit period
        elseif(all_request_set('periodnamn','editperiod')===true){
            edit_table_data('period','periodnamn','periodnamn','start','slut');
        } 
        // Edit arbetsplats
        elseif(all_request_set('foretagsnamn','editforetag')===true){
            edit_table_data('arbetsplats','foretagsnamn','foretagsnamn','kontaktnummer','epost');
        }
        // Edit handledare
        elseif(all_request_set('originanv','edithandledare')===true){
            edit_table_data('anvandare','anvandarnamn','originanv','anvandarnamn','losenord','fnamn','enamn','foretagid');
        }
        // Edit placering
        elseif(all_request_set('id','editplacering')===true){
            edit_table_data('placering','id','id','personnummer','period','foretagsnamn');

        }



        else {
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
