<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Name =SQL_Injection_Prevention('瑋志樓2');
$Short=SQL_Injection_Prevention('wz1');
$Floor_L =SQL_Injection_Prevention('7');
$Floor_B=SQL_Injection_Prevention('');
*/
$DEBUG = false;

$Name= SQL_Injection_Prevention($_POST['Name'] ?? '');
$Short= SQL_Injection_Prevention($_POST['Short'] ?? '');
$Floor_L= SQL_Injection_Prevention($_POST['Floor_L'] ?? '');
$Floor_B= SQL_Injection_Prevention($_POST['Floor_B'] ?? '');



if($Name & $Short ){
    if(is_numeric($Floor_L) & intval($Floor_L) >0){
        if(is_numeric($Floor_B) & intval($Floor_B) >0){
            $Floor=$Floor_L.'樓,'.$Floor_B.'樓';
        }else{
            $Floor=$Floor_L.'樓';
        }
        try{
            $stmt = $pdo->prepare("INSERT INTO building
                                   VALUES ( ? , ? , ? );");
            $stmt->execute(array($Short,$Name,$Floor));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG);  
        }
    }else{
        return_error_and_exit('fail', '1', '',$DEBUG);  
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG);     
}

return_success_and_exit('success','');