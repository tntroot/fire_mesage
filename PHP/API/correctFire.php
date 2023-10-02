<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Num=SQL_Injection_Prevention('F00015');
$Type=SQL_Injection_Prevention('乾粉');
$MFG=SQL_Injection_Prevention('2001-03-30');
$Supplier=SQL_Injection_Prevention('四太');
$PowderEXP=SQL_Injection_Prevention('2025-05-29');
*/

$DEBUG = false;


$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');
$Type= SQL_Injection_Prevention($_POST['Type'] ?? '');
$MFG= SQL_Injection_Prevention($_POST['MFG'] ?? '');
$Supplier= SQL_Injection_Prevention($_POST['Supplier'] ?? '');
$PowderEXP= SQL_Injection_Prevention($_POST['PowderEXP'] ?? '');


if($Num & $Type & $MFG & $Supplier & $PowderEXP){
    if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $MFG) & preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $PowderEXP)){
        try{
            $stmt = $pdo->prepare("SELECT * FROM fire_data WHERE Num= ?  AND Pair='下架' AND `Status`='正常'");
            $stmt->execute([$Num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '4', $e,$DEBUG); 
        }

    }else{
        return_error_and_exit('fail', '3', '日期格式不符',$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}


$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    $stmt = $pdo->prepare("SELECT fire_data.Num,`Type`,MFG,PowderExp,fire_data.Supplier  FROM fire_data left JOIN (SELECT * FROM fire_hist WHERE `Status`='換藥')AS fire_hist on fire_data.Num=fire_hist.Num LEFT JOIN powder_hist ON fire_hist.ID=powder_hist.ID WHERE fire_data.Num= ? AND fire_data.Pair='下架' AND fire_data.`Status`='正常' AND powder_hist.ID=(SELECT max(ID) FROM fire_hist WHERE Num= ?  AND `Status`='換藥')");
    $stmt->execute(Array($Num,$Num));
}else{
    $stmt = $pdo->prepare("SELECT * FROM fire_data WHERE Num= ? ");
    $stmt->execute([$Num]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(count($data)>0){
        return_error_and_exit('fail', '6', '閒置狀態的滅火器才可做修改',$DEBUG); 
    }else{
        return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
    }
    
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);


$Num_=$data[0]->Num ?? '';
$Type_=$data[0]->Type ?? '';
$MFG_=$data[0]->MFG ?? '';
$Supplier_=$data[0]->Supplier ?? '';
$PowderExp_=$data[0]->PowderExp ?? '';


if($Num_==$Num & $Type_==$Type & $MFG_==$MFG & $Supplier_==$Supplier & $PowderExp_==$PowderEXP){
    return_error_and_exit('fail', '6', '閒置狀態的滅火器才可做修改',$DEBUG); 
}else{
    try{
        $stmt = $pdo->prepare("UPDATE fire_data SET `Type`= ? ,MFG= ? ,Supplier= ? WHERE Num= ? ");
        $stmt->execute(Array($Type,$MFG,$Supplier,$Num));

        $stmt = $pdo->prepare("UPDATE powder_hist SET PowderEXP= ? WHERE ID=(SELECT max(ID) FROM fire_hist WHERE Num= ?  AND `Status`='換藥')");
        $stmt->execute(Array($PowderEXP,$Num));
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
}

return_success_and_exit('success','');