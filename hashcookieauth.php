<?php
    // FUNCTIONS
function f1($a,$b){
   return  $a . "," . bin2hex($b);

}

function f2($a){
    return hash('sha256', $a);
}
?>