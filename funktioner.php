<?php
//error_reporting(E_ERROR);
require_once('db.php');
function validadmin($hash,$anvnamn){
    global $conn;
    $stmt = $conn->prepare("SELECT expire,admin FROM anvandare WHERE hash=? and anvnamn=?");
    $stmt->bind_param("ss", $hash, $anvnamn);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $first_date = new DateTime("now");
    $second_date = new DateTime($row[0]);
    if($second_date->getTimestamp() - $first_date->getTimestamp() > 0)
        return $row[1];
    else 
        return false;
}

function validhand($hash,$anvnamn){
    global $conn;
    $stmt = $conn->prepare("SELECT expire,admin FROM anvandare WHERE hash=? and anvnamn=?");
    $stmt->bind_param("ss", $hash, $anvnamn);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $first_date = new DateTime("now");
    $second_date = new DateTime($row[0]);
    if($second_date->getTimestamp() - $first_date->getTimestamp() > 0)
        return true;
    else 
        return false;
}

function printresult($result){
    global $default_ok_response;
    $arr=$default_ok_response;
    $data=[];
    while($row=$result->fetch_assoc())
        $data[]=$row;
    $arr['data']=$data;
    echo json_encode($arr);
}
function printarray($array){
    global $default_ok_response;
    $arr=$default_ok_response;
    $arr['data']=$array;
    echo json_encode($arr);
}

function printelever($result){
    global $default_ok_response;
    $arr=$default_ok_response;
    $data=[];
    while($row=$result->fetch_assoc())
        $data[]=$row;
    $arr['data']=$data;
    echo json_encode($arr);
}

function deldata($table,$attribute,$value){
    global $conn;
    global $default_fail_response;
    global $default_ok_response;
    $sql="DELETE FROM $table WHERE $attribute=$value";
    send_query($sql);
    giveresponse($default_ok_response);
}

function all_request_set(...$req){
    foreach ($req as $value) {
        if(isset($_REQUEST[$value])===false){
            return false;
        }
    }
    return true;
}


function edit_table_data($table,$where,$wherevalue, ...$dbfields){
    global $conn;
    global $default_fail_response;
    global $default_ok_response;
    $paramarr=[];
    $sqlarr=[];
    $sql="UPDATE $table SET ";
    foreach ($dbfields as $field) {
        if(!empty($_REQUEST[$field])){
            $sqlarr[]="$field=?";
            $paramarr[]=$_REQUEST[$field];
        }
    }
    $sql=$sql.implode(',',$sqlarr);
    $sql=$sql." WHERE $where=?";
    $paramarr[]=$_REQUEST[$where];
    $stmt = $conn->prepare($sql);
    if($stmt===false){
        giveresponse($default_fail_response);
    }
    $rc = $stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
    if($rc===false){
        giveresponse($default_fail_response);
    }
    $rc = $stmt->execute();
    if($rc===false){
        giveresponse($default_fail_response);
    }
    giveresponse($default_ok_response);

}

function create_table_data($table, ...$dbfields){
    global $conn;
    global $default_fail_response;
    global $default_ok_response;

    $paramarr=[];
    $sqlafter=[];
    $sqlarr=[];
    $sql="INSERT INTO $table (";
    foreach ($dbfields as $field) {
        if(!empty($_REQUEST[$field])){
            $sqlarr[]="$field";
            $sqlafter[]="?";
            $paramarr[]=$_REQUEST[$field];

        }
    }
    $sql=$sql.implode(',',$sqlarr).") VALUES (".implode(',',$sqlafter).")";
    $stmt = $conn->prepare($sql);
    if($stmt===false){
        giveresponse($default_fail_response);
    }
    $rc=$stmt->bind_param(str_repeat('s',count($paramarr)), ...$paramarr);
    if($rc===false){
        giveresponse($default_fail_response);
    }
    $rc = $stmt->execute();
    if($rc===false){
        giveresponse($default_fail_response);
    }
    giveresponse($default_ok_response);

}

function giveresponse($data){
    echo json_encode($data,JSON_FORCE_OBJECT);
    die(); // vi skall bara få en response.... eller?
}

function send_query($sql){
    global $conn;
    global $default_fail_response;
    $stmt = $conn->prepare($sql);
    if($stmt===false){
        giveresponse($default_fail_response);
    }
    $rc=$stmt->execute();
    if($rc===false){
        giveresponse($default_fail_response);
    }
    return $stmt->get_result();
}

function send_param_query($sql,$binds,$paramarr){
    global $conn;
    global $default_fail_response;
    $stmt = $conn->prepare($sql);
    if($stmt===false){
        giveresponse($default_fail_response);
    }
    $rc = $stmt->bind_param($binds,...$paramarr);
    if($rc===false){
        giveresponse($default_fail_response);
    }
    $rc = $stmt->execute();
    if($rc===false){
        giveresponse($default_fail_response);
    }
    return $stmt->get_result();
}
?>