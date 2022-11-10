<?php
// a function to FLOOR decimal numbers with any provided precision.
function changePrecision($number,$prec){
    if (!is_int($number)){
    $num = $number * pow(10, $prec);
    
    $num = number_format((float)floor($num) / pow(10, $prec), $prec);
    }
    else{
        return number_format($number, $prec);
    }
    return $num;
}
    $numbers = array(
        array(1.01, 3),
        array(1, 2),
        array(1000000.8987390248, 5),
        array(4.532, 1),
        array(5.213, 0),
    );
    for($x = 0; $x<count($numbers); $x++){
        echo "The precision of ". $numbers[$x][0] . " to ". $numbers[$x][1] . " digits in decimals is " . changePrecision($numbers[$x][0],$numbers[$x][1]). "<br>"; 
    }

?>