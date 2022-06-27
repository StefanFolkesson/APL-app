<?php
require_once('funktioner.php');
require_once('db.php');


if(isset($_REQUEST['hash']) && isset($_REQUEST['anvnamn'])){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
        if(isset($_REQUEST['pnr']) and 
           isset($_REQUEST['delelev'])
           ){
            deldata("elev","pnr",$_REQUEST['pnr']);
        }
        elseif(isset($_REQUEST['periodnamn']) and 
           isset($_REQUEST['delperiod'])
           ){
            deldata("period","periodnamn",$_REQUEST['periodnamn']);
        }
        elseif(isset($_REQUEST['foretagsnamn']) and 
           isset($_REQUEST['delforetag'])
           ){
            deldata("arbetsplats","foretagsnamn",$_REQUEST['foretagsnamn']);
        }
        elseif(isset($_REQUEST['anvandarnamn']) and 
           isset($_REQUEST['delhandledare'])
           ){
            deldata("anvandare","anvandarnamn",$_REQUEST['anvandarnamn']);
        }
        elseif(isset($_REQUEST['id']) and 
           isset($_REQUEST['delplacering'])
           ){
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
