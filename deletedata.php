<?php
require_once('funktioner.php');
require_once('db.php');


if(all_request_set('hash','anvnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
        if(all_request_set('pnr','delelev')===true){
            deldata("elev","pnr",$_REQUEST['pnr']);
        }
        elseif(all_request_set('periodnamn','delperiod')===true){
            deldata("period","periodnamn",$_REQUEST['periodnamn']);
        }
        elseif(all_request_set('foretagsnamn','delforetag')===true){
            deldata("arbetsplats","foretagsnamn",$_REQUEST['foretagsnamn']);
        }
        elseif(all_request_set('anvandarnamn','delhandledare')===true){
            deldata("anvandare","anvandarnamn",$_REQUEST['anvandarnamn']);
        }
        elseif(all_request_set('id','delplacering')===true){
            deldata("placering","id",$_REQUEST['id']);
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
