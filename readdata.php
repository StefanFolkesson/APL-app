<?php
require_once('funktioner.php');
require_once('db.php');
// Read data skall ta indata och skicka ut relevant data.

// varje datarequest måste hantera en användares inlogging 
// hash - nyckeln till användaren
// id - användarens id
// varje gång som man gör något uppdateras hashens timestam (30min?)
// AMINONLY
// elevid - hämtar en elev   X
// periodid - hämtar en period X
// elever - hämtar alla elever  X
// perioder - hämtar en period  X
// handledarid -- skall det vara skillnad på användare och företag ( förmodligen)  X
// handledare X
// foretagsid X
// foretag X
// visa rapporterade elever
// visa alla elever som skall rapporteras idag
// visa ej rapporterade elever
// visa rapporterad period elev

// Handledare ( en annan fil?)
// visa elever som ej är rapporterade idag
// visa elever som ej är rapporterade tills idag...  

if(all_request_set('hash','loginnamn')===true){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['loginnamn'];
    $resp =validadmin("tt",$anv); 
    if ($resp==1){
        if(isset($_REQUEST['eid'])){  
            $eid=$_REQUEST['eid'];
            $sql="SELECT * FROM elev JOIN placering ON placering.personnummer=elev.pnr WHERE elev.pnr=?";
            $paramsarr=[$eid];
            $result = send_param_query($sql,"s",$paramsarr);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['elever'])){
            $sql="SELECT * FROM elev";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['pid'])){
            $pid=$_REQUEST['pid'];
            $sql="SELECT * FROM period WHERE periodnamn=?";
            $paramsarr=[$pid];
            $result = send_param_query($sql,"s",$paramsarr);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['perioder'])){
            $sql="SELECT * FROM period";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['arbid'])){
            $arbid=$_REQUEST['arbid'];
            $sql="SELECT * FROM arbetsplats JOIN anvandare ON arbetsplats.foretagsnamn=anvandare.foretagid WHERE foretagsnamn= ?";
            $paramsarr=[$arbid];
            $result = send_param_query($sql,"s",$paramsarr);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['arbetsplatser'])){
            $sql="SELECT * FROM arbetsplats";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }

        if(isset($_REQUEST['handid'])){
            $handid=$_REQUEST['handid'];
            $sql="SELECT * FROM anvandare JOIN arbetsplats ON arbetsplats.foretagsnamn=anvandare.foretagid WHERE id= ?";
            $paramsarr=[$handid];
            $result = send_param_query($sql,"s",$paramsarr);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['handledare'])){
            $sql="SELECT * FROM anvandare WHERE admin=0";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }
        
        if(isset($_REQUEST['rapporterade'])){
            // visa rapporterade elever   
            $sql="SELECT * FROM narvarande WHERE dag=now()";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }

        if(isset($_REQUEST['placerade'])){
            // visa placerade elever   
            $sql="SELECT * FROM placering";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }
        if(isset($_REQUEST['oplacerade'])){
            // visa oplacerade elever   
            $sql="SELECT pnr,fnamn,enamn,klass from elev left join placering on placering.personnummer = elev.pnr where placering.personnummer IS NULL;";
            $result = send_query($sql);
            if(!is_null($result))
            printresult($result);
        }


        if(isset($_REQUEST['rappidag'])){   // Ok, Fixa fråga? no errorcheck
            // visa alla elever som skall rapporteras idag
            // TODO: Måste fixa till frågan så det inte ser ut såhär!!!!
            // Plocka ut vilken period det är just nu
            $sql="SELECT periodnamn FROM period WHERE start<now() and slut>now()";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data=[];
            while($row = $result->fetch_row()){
                // kan vara fler perioder
                $period = $row[0];
                // plocka ut alla elever som är aktiva denna period
                $sql2 = "SELECT * from placering WHERE period=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("s",$period);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                // TODO: uteslut lö/sö och exkluderade dagar med.
                while($row2 = $result2->fetch_row()){
                    $pid=$row2[0];
                    // Plocka ut all registrerad data
                    $sql3 = "SELECT status,pid,elev.fnamn, elev.enamn from narvarande  join placering on pid = placering.id join elev on placering.personnummer=elev.pnr where pid=?";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param("s",$pid);
                    $stmt3->execute();
                    $result3 = $stmt3->get_result();
                    if($result3->num_rows!=1){
                        $pnr = $row2[1];
                        $sql4 = "SELECT fnamn,enamn from elev  where elev.pnr=?";
                        $stmt4 = $conn->prepare($sql4);
                        $stmt4->bind_param("s",$pnr);
                        $stmt4->execute();
                        $result4 = $stmt4->get_result();
                        $row4 = $result4->fetch_row();
                        $datarow=[$row4[1], $row4[0] , 'Ej registrerad'];
                        $data[]=$datarow;
                    }
                    else{
                        $row3 = $result3->fetch_row(); 
                        $datarow=[$row3[3], $row3[2] ,$row3[0]];
                        $data[]=$datarow;
                    }
         
                }
    
            }
            printarray($data);

        }

        if(isset($_REQUEST['orapporterade'])){  // OK
            // visa ej rapporterade elever
            $sql = "SELECT pid,pnr,fnamn,enamn FROM placering 
                    JOIN period ON placering.period=period.periodnamn 
                    JOIN elev ON elev.pnr=placering.personnummer
                    WHERE period.start<now() and period.slut>now()";
            $result = send_query($sql);
            if($result->num_rows==0){
                printresult($result);
            }
            else {
                $data=[];
                while($row = $result->fetch_row()){
                    $period = $row[0];
                    $pnr=$row[1];
                    $fnamn=$row[2];
                    $enamn=$row[3];
                    $dag = new DateTime("now");
                    $dag = $dag->format("Y-m-d");
                    $sql2 = "SELECT status FROM narvarande WHERE pid=$period and dag='$dag'";
                    $result2 = send_query($sql2);
                    if($result2->num_rows==0){
                        $data[]=[$pnr,$fnamn,$fnamn];
                    }
                }
                printarray($data);
            }
        }

        if(isset($_REQUEST['elev'])&& isset($_REQUEST['period'])){  // OK,no errorcheck
            $pnr = $_REQUEST['elev'];
            $period = $_REQUEST['period'];
            // visa rapporterad period elev
            $sql = "SELECT id,personnummer,period,placering.foretagsnamn,period.start,period.slut,elev.fnamn,elev.enamn,arbetsplats.foretagsnamn from placering
             join period on placering.period=period.periodnamn 
             join elev on personnummer=elev.pnr
             join arbetsplats on placering.foretagsnamn=arbetsplats.foretagsnamn
             WHERE period=? and personnummer=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss",$period,$pnr);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                $begin = new DateTime($row[4]);
                $end   = new DateTime($row[5]);
                $placering = $row[0];
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    $dag=$i->format("Y-m-d");
                    $status="Ej registrerad";
                    $sqldate="SELECT status FROM narvarande WHERE pid=$placering AND dag='$dag'";
                    $stmtdate = $conn->prepare($sqldate);
                    $stmtdate->execute();
                    $resultdate = $stmtdate->get_result();  
                    if($resultdate->num_rows>0){
                        $rowdate=$resultdate->fetch_row();
                        $status = $rowdate[0];
                    }
                    $datarow=[$row[1],$dag,$status];
                    $data[]=$datarow;
                }
            }
            printarray($data);
    }

    }
    else if($resp==0){  // OK,no errorcheck
        if(isset($_REQUEST['idag'])){
                // visa elever som ej är rapporterade
            // TODO: check if today is a valid day? well or not.. since an invalid day shouldnt have any records. but hey we have to do that since day isnt saved until its recorded 
            $sql = "SELECT placering.pid,elev.pnr,elev.fnamn,elev.enamn FROM anvandare 
                    JOIN placering ON anvandare.foretagid=placering.foretagsnamn 
                    JOIN period ON placering.period=period.periodnamn
                    JOIN elev ON placering.personnummer = elev.pnr
                    WHERE anvnamn=? AND start<now() AND slut>now()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$anv);
            $stmt->execute();
            $result= $stmt->get_result();
            $finalarr=[];
            $status="Ej registrerad";
            while($row=$result->fetch_assoc()){
                $sql2="SELECT status FROM narvarande WHERE pid=".$row["pid"]." AND dag=date(now())";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute();
                $result2= $stmt2->get_result();
                $dag=new DateTime("now");
                $dag=$dag->format("Y-m-d");
                if($result2->num_rows==0){
                    $finalarr[]=array_merge($row,['status'=>$status,'dag'=>$dag]);
                }
            }
            printarray($finalarr);
        }
        if(isset($_REQUEST['tillsnu'])){  //Ok,no errorcheck
            $sql = "SELECT placering.pid,period.start,period.slut,elev.pnr FROM anvandare 
                JOIN placering ON anvandare.foretagid=placering.foretagsnamn 
                JOIN period ON placering.period=period.periodnamn
                JOIN elev ON placering.personnummer = elev.pnr
                WHERE anvnamn=? AND start<now() AND slut>now()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$anv);
            $stmt->execute();
            $result= $stmt->get_result();
            $data=[];
            while($row=$result->fetch_assoc()){
                $begin = new DateTime($row['start']);
                $end   = new DateTime("now");
                $placering = $row['pid'];

                for($i = $begin; $i < $end; $i->modify('+1 day')){
                    $dag=$i->format("Y-m-d");
                    $status="Ej registrerad";
                    $sqldate="SELECT status FROM narvarande WHERE pid=$placering AND dag='$dag'";
                    $stmtdate = $conn->prepare($sqldate);
                    $stmtdate->execute();
                    $resultdate = $stmtdate->get_result();  
                    if($resultdate->num_rows>0){
                        $rowdate=$resultdate->fetch_row();
                        $status = $rowdate[0];
                    }
                    $datarow=['pnr'=>$row['pnr'],'dag'=>$dag,'status'=>$status,'pid'=>$placering];
                    $data[]=$datarow;
                }

            }
            printarray($data);
        }
    }
    else {
        giveresponse($default_fail_hash_response);
    }
}
else {
    giveresponse($default_fail_response);
}