<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Fire_num =SQL_Injection_Prevention('F00012');
$Powder_EXP=SQL_Injection_Prevention('2024-06-04');
$Powder_Supplier =SQL_Injection_Prevention('巴泰');
*/

$DEBUG = false;


$Fire_num= SQL_Injection_Prevention($_POST['Fire_num'] ?? '');
$Powder_EXP= SQL_Injection_Prevention($_POST['Powder_EXP'] ?? '');
$Powder_Supplier= SQL_Injection_Prevention($_POST['Powder_Supplier'] ?? '');


if($Fire_num & $Powder_EXP & $Powder_Supplier){
    if(Verification_Num($Fire_num) & preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$Powder_EXP)){
        try{
            $stmt = $pdo->prepare("SELECT fire_data.Num,`Type` FROM fire_data WHERE fire_data.`Status`='換藥中' AND fire_data.Num= ? ");
            $stmt->execute([$Fire_num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }
    }else{
        return_error_and_exit('fail', '3', '序號或日期格式錯誤',$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    try{
        $stmt = $pdo->prepare("UPDATE fire_data SET `Status`='正常' WHERE Num= ? ;");
        $stmt->execute([$Fire_num]);

        $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,`Status`,`Date`) VALUES( ? ,'換藥',CURDATE());");
        $stmt->execute([$Fire_num]);   

        $stmt = $pdo->prepare("INSERT INTO powder_hist SELECT MAX(ID), ? , ? FROM fire_hist WHERE Num= ?  AND `Status`='換藥' AND `Date`=CURDATE()");
        $stmt->execute(Array($Powder_EXP,$Powder_Supplier,$Fire_num));   
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '6', '狀態不是換藥中',$DEBUG); 
}

return_success_and_exit('success','');