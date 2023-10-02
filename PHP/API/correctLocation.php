<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Building=SQL_Injection_Prevention('美術系館');
$Floor=SQL_Injection_Prevention('5');
$Location=SQL_Injection_Prevention('辦你媽公室');
$New_Location=SQL_Injection_Prevention('辦公室');
*/

$DEBUG = false;


$Building= SQL_Injection_Prevention($_POST['Building'] ?? '');
$Floor= SQL_Injection_Prevention($_POST['Floor'] ?? '');
$Location= SQL_Injection_Prevention($_POST['Location'] ?? '');
$New_Location= SQL_Injection_Prevention($_POST['New_Location'] ?? '');


if($Building & $Floor & $Location & $New_Location){
    if($Location == $New_Location){
        return_error_and_exit('fail', '2', '修改的資料與與原始資料相同',$DEBUG); 
    }else{
        try{
            $stmt = $pdo->prepare("SELECT * FROM location WHERE `Building`= ?  AND `Floor`= ?  AND `Location`= ? ");
            $stmt->execute(Array($Building,$Floor,$Location));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }

    }

}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    try{
        $stmt = $pdo->prepare("UPDATE location SET `Location`= ?  WHERE `Building`= ?  AND `Floor`= ?  AND `Location`= ? ");
        $stmt->execute(Array($New_Location,$Building,$Floor,$Location));
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}

return_success_and_exit('success','');