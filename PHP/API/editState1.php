<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Fire_Num =SQL_Injection_Prevention('F00015');
$Box_Num=SQL_Injection_Prevention('B00006');
*/
$DEBUG = false;

$Fire_Num= SQL_Injection_Prevention($_POST['Fire_Num'] ?? '');
$Box_Num= SQL_Injection_Prevention($_POST['Box_Num'] ?? '');

if(Verification_Num($Fire_Num)){
    try{
        $Fire = $pdo->prepare("SELECT Num FROM fire_data WHERE Num= ?  AND Pair='下架' AND `Status`='正常';");
        $Fire->execute([$Fire_Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '3', '',$DEBUG); 
}

$Fire = $Fire->fetchAll(PDO::FETCH_OBJ);

if(Verification_Num($Box_Num)){
    try{
        $Box = $pdo->prepare("SELECT Num FROM box_data WHERE Num= ? AND Pair='下架' AND `Status`='正常';");
        $Box->execute([$Box_Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '3', '',$DEBUG); 
}

$Box = $Box->fetchAll(PDO::FETCH_OBJ);

if(count($Fire)==1 & count($Box)==1){
    try{
        $Target = $pdo->prepare("SELECT `Data` FROM target_data WHERE  `Data`= ?  AND `STATUS`='F'");
        $Target->execute([$Box_Num]);
        $Target = $Target->fetchAll(PDO::FETCH_OBJ);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    if(count($Target)>0){
        try{
            $stmt = $pdo->prepare("UPDATE target_data SET `Status`='T' WHERE `Data`= ? ");
            $stmt->execute([$Box_Num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '4', $e,$DEBUG); 
        }
    }
    try{
        $stmt = $pdo->prepare("INSERT INTO pair_data VALUES( ? , ? ,'上架',CURDATE());");
        $stmt->execute(Array($Fire_Num,$Box_Num));
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    try{
        $stmt = $pdo->prepare("UPDATE box_data SET Pair='上架' WHERE Num= ? ;");
        $stmt->execute([$Box_Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    try{
        $stmt = $pdo->prepare("UPDATE fire_data SET Pair='上架' WHERE Num= ? ;");
        $stmt->execute([$Fire_Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    try{
        $stmt = $pdo->prepare("INSERT INTO box_hist(Num,Pair,Fire_past) VALUES( ? ,'上架', ? );");
        $stmt->execute(Array($Box_Num,$Fire_Num));
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }
    try{
        $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,Pair) VALUES( ? ,'上架')");
        $stmt->execute([$Fire_Num]);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }


}else{
    return_error_and_exit('fail', '6', '',$DEBUG);
}
try{
    $stmt = $pdo->prepare("SELECT pair_data.Fire_num,PowderEXP,pair_data.Box_num,pair_data.Pair,CONCAT(`Building`,`Floor`,'樓',`Location`)AS `Location` 
                           FROM pair_data inner JOIN fire_hist ON pair_data.Fire_num=fire_hist.Num 
                           inner JOIN(SELECT MAX(ID)AS maxid1,Num FROM fire_hist WHERE `Status`='換藥' GROUP BY Num)AS a1 ON fire_hist.Num=a1.Num AND fire_hist.ID=a1.maxid1
                           inner JOIN powder_hist ON a1.maxid1=powder_hist.ID 
                           inner JOIN box_data ON pair_data.Box_num=box_data.Num 
                           inner JOIN location ON box_data.Location_ID=location.ID
                           WHERE pair_data.Fire_num= ?  AND pair_data.Box_num= ? ");
    $stmt->execute(Array($Fire_Num,$Box_Num));
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}
$data = $stmt->fetchAll(PDO::FETCH_OBJ);
if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}

