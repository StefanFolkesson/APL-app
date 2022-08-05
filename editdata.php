<?php
require_once('funktioner.php');
require_once('db.php');
// admin
// ändra  elev
// ändra  period
// ändra företag
// ändra handledare
// ändra uteslutna dagar


//handledare
// ändra frånvaro

if(all_request_set('hash','loginnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['loginnamn'];
    if (validadmin($hash,$anv)==1){
        // Ny elev
        if(all_request_set('editelev','originpnr')===true){
            edit_table_data('elev','pnr','originpnr','pnr','fnamn','enamn','klass','epost');
        }
        // Edit period
        elseif(all_request_set('periodnamn','editperiod')===true){
            edit_table_data('period','periodnamn','periodnamn','periodnamn','start','slut');
        } 
        // Edit arbetsplats
        elseif(all_request_set('foretagsnamn','editforetag')===true){
            edit_table_data('arbetsplats','foretagsnamn','foretagsnamn','kontaktnummer','epost');
        }
        // Edit handledare
        elseif(all_request_set('originanv','edithandledare')===true){
            edit_table_data('anvandare','anvnamn','originanv','anvnamn','losenord','fnamn','enamn','foretagid');
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
        if(all_request_set('datum','status','pid','editpresens')===true){ 
            $datum=$_REQUEST['datum'];
            $status=$_REQUEST['status'];
            $pid=$_REQUEST['pid'];
            $sql="UPDATE narvarande SET status = $status WHERE narvarande.pid = $pid and dag='$datum'";
              $stmt = $conn->prepare($sql);
            if($stmt===false){
                giveresponse($default_fail_response);
            }
            $rc = $stmt->execute();
            if($rc===false){
                giveresponse($default_fail_response);
            }
            if($stmt->affected_rows==0){
                giveresponse($default_fail_response);
            }
            giveresponse($default_ok_response);
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
