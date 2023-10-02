<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Building =SQL_Injection_Prevention('圖書資訊大樓');
$Floor =SQL_Injection_Prevention('1');
$Location=SQL_Injection_Prevention('rg111rd111fg');
*/
$DEBUG = false;


$Building= SQL_Injection_Prevention($_POST['Building'] ?? '');
$Floor= SQL_Injection_Prevention($_POST['Floor'] ?? '');
$Location= SQL_Injection_Prevention($_POST['Location'] ?? '');


if($Building & $Floor & $Location){
    try{
        $stmt = $pdo->prepare("SELECT `Building`,`Floor`,`Location` FROM location WHERE `Building`= ?  AND `Floor`= ?  AND`Location`= ? ;");
        $stmt->execute(Array($Building,$Floor,$Location));
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    return_error_and_exit('fail', '6', '已新增過',$DEBUG); 
}else{
    //新增位置
    try{
        $stmt = $pdo->prepare("INSERT INTO location VALUES(ID, ? , ? , ? ); ");
        $stmt->execute(Array($Building,$Floor,$Location));
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e."/位置新增失敗",$DEBUG); 
    }
    //新增與位置配對的固定架序號
    try{
        $stmt = $pdo->prepare(" INSERT INTO box_data(`Location_ID`) SELECT ID FROM location ORDER BY ID DESC LIMIT 1; ");
        $stmt->execute();
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e."/固定架序號新增失敗",$DEBUG); 
    }
}

return_success_and_exit('success','');
