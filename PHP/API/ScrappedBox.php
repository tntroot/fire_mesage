<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Num=SQL_Injection_Prevention('B00009');
*/
$DEBUG = false;


$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');


if($Num){
    try{
        $stmt = $pdo->prepare("SELECT Num FROM box_data WHERE Num= ?  AND Pair='下架' AND `Status`!='廢棄'");
        $stmt->execute([$Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    try{
        $stmt = $pdo->prepare("UPDATE box_data SET `Status`='廢棄' WHERE Num= ?  AND Pair='下架';");
        $stmt->execute([$Num]);

        $stmt = $pdo->prepare("INSERT INTO box_hist(Num,`Status`,Fire_Past) SELECT ? ,'廢棄',box_hist.Fire_Past FROM (SELECT MAX(ID)AS maxid,Num,Fire_Past FROM box_hist GROUP BY Num)AS b INNER JOIN box_hist ON box_hist.Num=b.Num AND box_hist.ID=b.maxid WHERE box_hist.Num= ? ");
        $stmt->execute(Array($Num,$Num));
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    
}else{
    $stmt = $pdo->prepare("SELECT Num FROM box_data WHERE Num= ? ");
    $stmt->execute([$Num]);
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($data)>0){
        return_error_and_exit('fail', '6', '狀態為上架或已經報廢',$DEBUG); 
        
    }else{
        return_error_and_exit('fail', '5', '無此固定架序號',$DEBUG); 
    }
    
}

return_success_and_exit('success','');