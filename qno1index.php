
<!-- some examples for is_int(), is_numeric(), is_integer() -->
<!-- since the input value from text comes as a string, the value is numeric, but not of type int or an integer.  -->
<html>
    <body>
    <?php 
   $input = $_GET["str"];
   if (is_int($input)){
    echo $input . " is of type int <br>";
   }
   else{
    echo $input . " is not of type int <br>";
   }
   if (is_numeric($input)){
    echo $input . " is a number <br>";

   }
   else{
    echo $input . " is not numeric <br>";
   }
   if (is_integer($input)){
    echo $input . " is an integer <br>";
   }
   else{
    echo $input . " is not an integer <br>";
   }
?>  
    </body>
</html>



