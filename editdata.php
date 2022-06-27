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


if(isset($_REQUEST['hash']) && isset($_REQUEST['anvnamn'])){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
        // Ny elev
        if(isset($_REQUEST['editelev']) and isset($_REQUEST['originpnr'])){
            $paramarr=array();
            $sql="UPDATE elev SET ";
            if(isset($_REQUEST['pnr'])) {
                $sql=$sql."pnr=? ,";
                array_push($paramarr,$_REQUEST['pnr']);
            }
            if(isset($_REQUEST['fnamn'])) {
                array_push($paramarr,$_REQUEST['fnamn']);
                $sql=$sql."fnamn=? ,";
            } 
            if(isset($_REQUEST['enamn'])){
                array_push($paramarr,$_REQUEST['enamn']);
                $sql=$sql."enamn=? ,";
            }
            if(isset($_REQUEST['klass'])){
                array_push($paramarr,$_REQUEST['klass']);
                $sql=$sql."klass=? ,";
            }
            if(isset($_REQUEST['epost'])){
                array_push($paramarr,$_REQUEST['epost']);
                $sql=$sql."epost=? ,";
            } 
            array_push($paramarr,$_REQUEST['originpnr']);
            $sql=$sql.", WHERE pnr=?";
            $sql=str_replace(",,","",$sql);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
            $stmt->execute();
            // SEnd verification

        }
        // Edit period
        elseif(isset($_REQUEST['periodnamn']) and 
           isset($_REQUEST['editperiod'])){
            $paramarr=array();
            $sql="UPDATE peiod SET ";
            if(isset($_REQUEST['start'])){
                array_push($paramarr,$_REQUEST['start']);
                $sql=$sql."start=? ,";
            }
            if(isset($_REQUEST['slut'])){
                array_push($paramarr,$_REQUEST['slut']);
                $sql=$sql."slut=? ,";
            }
            array_push($paramarr,$_REQUEST['periodnamn']);
            $sql=$sql.", WHERE periodnamn=?";
            $sql=str_replace(",,","",$sql);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
            $stmt->execute();
            // SEnd verification
        } 
        // Edit arbetsplats
        elseif(isset($_REQUEST['foretagsnamn']) and 
           isset($_REQUEST['editforetag'])){
            $paramarr=array();
            $sql="UPDATE arbetsplats SET ";
            if(isset($_REQUEST['kontaktnummer'])){
                array_push($paramarr,$_REQUEST['kontaktnummer']);
                $sql=$sql."kontaktnummer=? ,";
            }
            if(isset($_REQUEST['eport'])){
                array_push($paramarr,$_REQUEST['eport']);
                $sql=$sql."eport=? ,";
            } 
            array_push($paramarr,$_REQUEST['foretagsnamn']);
            $sql=$sql.", WHERE foretagsnamn=?";
            $sql=str_replace(",,","",$sql);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
            $stmt->execute();
            // SEnd verification
        }
        // Edit handledare
        elseif(isset($_REQUEST['originanv']) and
           isset($_REQUEST['edithandledare'])  
           ){
            $paramarr=array();
            $sql="UPDATE anvandare SET ";
            if(isset($_REQUEST['anvandarnamn'])){
                array_push($paramarr,$_REQUEST['anvandarnamn']);
                $sql=$sql."anvnamn=? ,";

            } 
            if(isset($_REQUEST['losenord'])){
                array_push($paramarr,$_REQUEST['losenord']);
                $sql=$sql."losenord=? ,";

            } 
            if(isset($_REQUEST['fnamn'])){
                array_push($paramarr,$_REQUEST['fnamn']);
                $sql=$sql."fnamn=? ,";

            }  
            if(isset($_REQUEST['enamn'])){
                array_push($paramarr,$_REQUEST['enamn']);
                $sql=$sql."enamn=? ,";

            }  
            if(isset($_REQUEST['foretagid'])){
                array_push($paramarr,$_REQUEST['foretagid']);
                $sql=$sql."foretagid=? ,";

            }  
            array_push($paramarr,$_REQUEST['originanv']);
            $sql=$sql.", WHERE anvnamn=?";
            echo $sql;
            $sql=str_replace(",,","",$sql);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
            $stmt->execute();
            // SEnd verification

        }
        // Edit placering
        elseif(isset($_REQUEST['id']) and  
           isset($_REQUEST['editplacering'])  
           ){
            $paramarr=array();
            $sql="UPDATE placering SET ";
            if(isset($_REQUEST['personnummer'])){
                array_push($paramarr,$_REQUEST['personnummer']);
                $sql=$sql."personnummer=? ,";
            }
            if(isset($_REQUEST['period'])){
                array_push($paramarr,$_REQUEST['period']);
                $sql=$sql."period=? ,";
            } 
            if(isset($_REQUEST['foretagsnamn'])){
                array_push($paramarr,$_REQUEST['foretagsnamn']);
                $sql=$sql."foretagsnamn=? ,";
            }  
            array_push($paramarr,$_REQUEST['id']);
            $sql=$sql.", WHERE id=?";
            $sql=str_replace(",,","",$sql);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
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
