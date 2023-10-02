<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Num =SQL_Injection_Prevention('F00011');
*/

$DEBUG = false;


$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');


if(Verification_Num($Num)){
    try{
        $stmt = $pdo->prepare("SELECT Num FROM fire_data WHERE Num= ?  AND `Status` ='維修中'");
        $stmt->execute([$Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '3', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    
    try{
        $stmt = $pdo->prepare("UPDATE fire_data SET `Status`='正常' WHERE Num= ? ;");
        $stmt->execute([$Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    
}else{
    try{
        $stmt = $pdo->prepare("SELECT Num FROM fire_data WHERE Num= ?  AND `Status` !='維修中' AND Pair='下架'");
        $stmt->execute([$Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($data)!=1){
        return_error_and_exit('fail', '6', '狀態不符或序號錯誤',$DEBUG); 
    }
}

try{
    $stmt = $pdo->prepare("SELECT Num FROM report WHERE Num= ? ");
    $stmt->execute([$Num]);
}catch(Exception $e){
    return_error_and_exit('fail', '3', $e,$DEBUG); 
}


$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    try{
        $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,Pair,`Status`) VALUES( ? ,'下架','維修');");
        $stmt->execute([$Num]);
    
        $stmt = $pdo->prepare("INSERT INTO repair_hist SELECT (SELECT max(ID) FROM fire_hist WHERE Num= ? ),Pressure,Apperance,Item,`Use`,Notes FROM report WHERE Num= ? ;");
        $stmt->execute(Array($Num,$Num));
    
        $stmt = $pdo->prepare("DELETE FROM report WHERE Num= ? ");
        $stmt->execute([$Num]);
    
    }catch(Exception $e){
        return_error_and_exit('fail', '4', '',$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '4', 'report內沒有該筆序號的資料',$DEBUG); 
}

return_success_and_exit('success','');

