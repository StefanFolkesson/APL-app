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
// visa elever som ej är rapporterade
// visa elever som ej är rapporterade idag...   --- Kanske samma

if(isset($_REQUEST['hash']) && isset($_REQUEST['anvnamn'])){
    $hash=$_REQUEST['hash'];
    $anv=$_REQUEST['anvnamn'];
    if (validadmin("tt",$anv)==1){
        echo "admin ok";      
        if(isset($_REQUEST['eid'])){  // TODO: broken?
            $eid=$_REQUEST['eid'];
            $sql="SELECT * FROM elev WHERE pnr=?";
            // skall ta med sin placering med. 
            $sql2="SELECT * FROM placering WHERE personnummer=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$eid);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("s",$eid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['elever'])){
            $sql="SELECT * FROM elev";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['pid'])){
            $pid=$_REQUEST['pid'];
            $sql="SELECT * FROM period WHERE periodnamn=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$pid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['perioder'])){
            $sql="SELECT * FROM period";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['arbid'])){
            $arbid=$_REQUEST['arbid'];
            $sql="SELECT * FROM arbetsplats WHERE foretagsnamn= ?";
            //skall ta med handledare med
            $sql2="SELECT * FROM anvandare WHERE foretagid=? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$arbid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("s",$arbid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['arbetsplatser'])){
            $sql="SELECT * FROM arbetsplats";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }

        if(isset($_REQUEST['handid'])){
            $handid=$_REQUEST['handid'];
            $sql="SELECT * FROM anvandare WHERE id= ?";
            $sql2="SELECT * FROM arbetsplats WHERE foretagsnamn=? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$handid);
            $stmt->execute();
            $result = $stmt->get_result();
            $ftg= "";
            while($row = $result->fetch_row()){
                $ftg=$row[6];
                printarr($row);
            }

            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("s",$ftg);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        if(isset($_REQUEST['handledare'])){
            $sql="SELECT * FROM anvandare WHERE admin=0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }
        
        if(isset($_REQUEST['rapporterade'])){
            // visa rapporterade elever   
            // WARNING___ NOT CHECKED!!!!
            $sql="SELECT * FROM närvarande WHERE dag=now()";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_row()){
                printarr($row);
            }
        }

        if(isset($_REQUEST['rappidag'])){
            // visa alla elever som skall rapporteras idag
            // TODO: Måste fixa till frågan så det inte ser ut såhär!!!!
            // Plocka ut vilken period det är just nu
            $sql="SELECT periodnamn FROM period WHERE start<now() and slut>now()";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
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
                        echo "<p>$row4[1], $row4[0] : Ej registrerad</p>";
                    }
                    else{
                        $row3 = $result3->fetch_row();
                        echo "<p>$row3[3], $row3[2] : $row3[0]</p>";
                    }
         
                }
    
            }

        }

        if(isset($_REQUEST['orapporterade'])){
            // visa ej rapporterade elever
            $sql="SELECT periodnamn FROM period WHERE start<now() and slut>now()";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
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
                    $sql3 = "SELECT status from narvarande  where pid=?";
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
                        echo "<p>$row4[1], $row4[0] : Ej registrerad</p>";
                    }
         
                }
    
            }
        }

        if(isset($_REQUEST['elev'])&& isset($_REQUEST['period'])){
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
                printarr($row);
                $begin = new DateTime($row[4]);
                $end   = new DateTime($row[5]);

                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    echo "<p>".$i->format("Y-m-d")."</p>";
                }

            }
            //Loopa igenom dagar
    }




    }
    else if(validhand($hash,$anv)){
        echo "du är handledare";


    }
    else {
        echo "hash expired";
    }
}
else {
    echo "invalid indata";
}