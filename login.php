<?php
// användarid 
// password

// skickar tillbaka
// hash
require_once('db.php');
require_once('funktioner.php');
global $default_ok_response;
global $default_fail_response;
global $default_fail_hash_response;

if(isset($_REQUEST['anv']) && isset($_REQUEST['pass'])){
    $anv = $_REQUEST['anv'];
    $pass= $_REQUEST['pass'];
    // Connect to DB
    $stmt = $conn->prepare("SELECT admin FROM anvandare WHERE anvnamn=? and losenord=?");
    $stmt->bind_param("ss", $anv, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=$result->fetch_assoc();
    $admin = $row['admin'];

    if($result->num_rows == 1){
        // check ok
        $stmt = $conn->prepare("UPDATE anvandare SET hash=? , expire=? WHERE anvnamn=? and losenord=?");
        $hash ="tt";
        $expire = date('Y-m-d H:i:s', strtotime("+300 minutes"));
        $stmt->bind_param("ssss",$hash ,$expire, $anv, $pass);
        $stmt->execute();


        $arr=$default_ok_response;
        $data=[];
            $data[]=['hash'=>$hash,'user'=>$anv,'admin'=>$admin];
        $arr['data']=$data;
        echo json_encode($arr);
    }
    else {
        giveresponse($default_fail_hash_response);
    }
} else {
    giveresponse($default_fail_response);

}


?>