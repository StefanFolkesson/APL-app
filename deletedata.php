<?php
require_once('funktioner.php');
require_once('db.php');

if(all_request_set('hash','loginnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['loginnamn'];
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
        elseif(all_request_set('anvnamn','delhandledare')===true){
            deldata("anvandare","anvnamn",$_REQUEST['anvnamn']);
        }
        elseif(all_request_set('pid','delplacering')===true){
            deldata("narvarande","pid",$_REQUEST['pid'],false);
            deldata("placering","pid",$_REQUEST['pid']);
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
