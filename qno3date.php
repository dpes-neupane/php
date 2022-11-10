<?php

//takes a valid date format and changes to YYYY-mm-dd and takes a 8 digit valid date (ddmmYYYY) and converts it to (Month-dd-YYYY)
function changeFormat($date){
    if (strtotime($date)){
        return date('Y-m-d', strtotime($date));
    }
    else{
        $pattern = "/[0-9]{8}/";
        if(preg_match($pattern, $date) and strlen($date) == 8){
            $newFormat = "";
          for($x = 0; $x < 4; $x+=2){
            $newFormat .= substr($date, $x, 2);
            $newFormat .= "-";

          }  
          $newFormat .= substr($date, $x);
          if (strtotime($newFormat)){
            return date("M-d-Y", strtotime($newFormat));
          }
          else{
            return $date. " is invalid";
          }
        }else{
          return $date . " is invalid";
        }
    }
    

}
echo changeFormat("Sep 20 2021")."<br>"; //valid
echo changeFormat("09092021")."<br>"; //valid
echo changeFormat("20210909")."<br>"; //valid
echo changeFormat("03321320")."<br>"; //invalid


?>