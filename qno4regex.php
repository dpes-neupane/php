<?php

function mailParser($string){
    $splitArray = preg_split("/@/", $string);
    return $splitArray;
}
$splitArray = mailParser("abc@grespr.com");
foreach($splitArray as $x ){
    echo $x . "\n";
}
?>
