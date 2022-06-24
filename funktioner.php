<?php
function validadmin($hash,$anvnamn){
    require('db.php');
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
    require('db.php');
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

function printarr($arr,$sep=" "){
    echo "<p>";
    foreach ($arr as $data) {
        echo $data.$sep;
    }
    echo "</p>";
 
}
?>