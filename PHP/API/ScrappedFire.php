<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Num =SQL_Injection_Prevention('F00010');
*/
$DEBUG = false;

$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');


if($Num){
    $get_data = $pdo->prepare("SELECT * FROM fire_data WHERE Num = ? AND Pair='下架';");
    $get_data->execute([$Num]);
    $data = $get_data->fetchAll(PDO::FETCH_OBJ);
    if(count($data)>0){
        try{
            $stmt = $pdo->prepare("UPDATE fire_data SET `Status`='已廢棄' WHERE Num= ? ");
            $stmt->execute([$Num]);
            $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,`Status`) SELECT Num,'廢棄' FROM fire_data WHERE Num= ? ");
            $stmt->execute([$Num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '4', $e,$DEBUG);  
        }
    }else{
        $get_data = $pdo->prepare("SELECT * FROM fire_data WHERE Num = ? ");
        $get_data->execute([$Num]);
        $data = $get_data->fetchAll(PDO::FETCH_OBJ);
        if(count($data)>0){
            return_error_and_exit('fail', '6', '',$DEBUG);  
        }else{
            return_error_and_exit('fail', '5', '',$DEBUG);  
        }
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG);  
}

return_success_and_exit('success','');