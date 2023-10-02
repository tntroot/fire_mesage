<?php

require('cache.php');
require('database.php');
require('functions.php');


//$Building =SQL_Injection_Prevention('圖書資訊大樓');

$DEBUG = false;

$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');


if($Building){
    try{
        $stmt = $pdo->prepare("SELECT `Building`,`Floor`,`location`,box_data.Pair,Num FROM location LEFT JOIN box_data ON location.ID=box_data.Location_ID WHERE `Status`!='廢棄' AND `Building`= ? ");
        $stmt->execute([$Building]);
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
}else{
    try{
        $stmt = $pdo->prepare("SELECT `Building`,`Floor`,`location`,box_data.Pair,Num FROM location LEFT JOIN box_data ON location.ID=box_data.Location_ID WHERE `Status`!='廢棄'");
        $stmt->execute();
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
}
$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}