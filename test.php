<?php

$test = array("version"=>"1","status"=>"0","data"=>array(array("pnr"=>"444","enamn"=>"kalle"),array("pnr"=>"445","enamn"=>"yalle")));

$enc = json_encode($test,JSON_FORCE_OBJECT);

echo $enc;

$dec = json_decode($enc,JSON_FORCE_OBJECT);

foreach ($dec as $val=>$key){
    if($val=="data"){
        foreach($key as $arrdata){
            foreach($arrdata as $rowdata){
                echo $rowdata;
            }
        }
    } else {
        echo $key;
    }

}

