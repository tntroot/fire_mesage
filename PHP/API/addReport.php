<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Num = SQL_Injection_Prevention('F00001');
$Pressure = SQL_Injection_Prevention('正常');
$Apperance = SQL_Injection_Prevention('生鏽');
$Item = SQL_Injection_Prevention('噴管遺失');
$Use = SQL_Injection_Prevention('正常');
$Notes = SQL_Injection_Prevention('');
$Time = SQL_Injection_Prevention('2022-06-06');
$Status = SQL_Injection_Prevention('0');
*/
$DEBUG = false;

$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');
$Pressure= SQL_Injection_Prevention($_POST['Pressure'] ?? '');
$Apperance= SQL_Injection_Prevention($_POST['Apperance'] ?? '');
$Item= SQL_Injection_Prevention($_POST['Item'] ?? '');
$Use= SQL_Injection_Prevention($_POST['Use'] ?? '');
$Notes= SQL_Injection_Prevention($_POST['Notes'] ?? '');
$Time= SQL_Injection_Prevention($_POST['Time'] ?? '');
$Status= SQL_Injection_Prevention($_POST['Status'] ?? '');


if($Num & $Pressure & $Apperance & $Item & $Use & $Time & $Status){
    if($Pressure == '正常' & $Apperance == '正常' & $Item == '正常' & $Use == '未使用'){
        return_error_and_exit('fail', '6', '',$DEBUG);
    }else if(is_numeric($Status) & intval($Status)<=2){
        try{
            $stmt = $pdo->prepare("INSERT INTO report VALUES( ? , ? , ? , ? , ? , ? , ? );");
            $stmt->execute(array($Num,$Pressure,$Apperance,$Item,$Use,$Notes,$Time));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG);
        }
        switch($Status){
            case "0":
                try{
                    $stmt = $pdo->prepare("UPDATE fire_data SET `Status`='需維修' WHERE Num= ? ;");
                    $stmt->execute([$Num]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG);    
                }
                break;
            case "1":
                try{
                    $stmt = $pdo->prepare("UPDATE fire_data SET `Status`='維修中',`Pair`='下架' WHERE Num= ? ;");
                    $stmt->execute([$Num]);

                    $stmt = $pdo->prepare("UPDATE box_data SET Pair='下架',`Status`='正常' WHERE Num=(SELECT Box_num FROM pair_data WHERE Fire_num= ? );");
                    $stmt->execute([$Num]);

                    $stmt = $pdo->prepare("INSERT INTO box_hist(Num,Pair,Fire_past) SELECT Box_num,'下架',Fire_num FROM pair_data WHERE Fire_num= ? ;");
                    $stmt->execute([$Num]);

                    $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,Pair) VALUES( ? ,'下架');");
                    $stmt->execute([$Num]);

                    $stmt = $pdo->prepare("DELETE FROM pair_data WHERE Fire_num= ?;");
                    $stmt->execute([$Num]);

                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG);    
                }
                break;
            case "2":
                try{
                    $stmt = $pdo->prepare("UPDATE fire_data SET `Pair`='下架' WHERE Num= ? ;");
                    $stmt->execute([$Num]);
                    $stmt = $pdo->prepare("UPDATE box_data SET Pair='下架',`Status`='正常' WHERE Num=(SELECT Box_num FROM pair_data WHERE Fire_num= ? );");
                    $stmt->execute([$Num]);
                    $stmt = $pdo->prepare("INSERT INTO box_hist(Num,Pair,Fire_past) SELECT Box_num,'下架',Fire_num FROM pair_data WHERE Fire_num= ? ;");
                    $stmt->execute([$Num]);
                    $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,Pair) VALUES( ? ,'下架');");
                    $stmt->execute([$Num]);
                    $stmt = $pdo->prepare("DELETE FROM pair_data WHERE Fire_num= ? ;
                                           ");
                    $stmt->execute([$Num]);

                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG);   
                }
                break;
            default:
                return_error_and_exit('fail', '3', '',$DEBUG); 
        }
        
    }else{
        return_error_and_exit('fail', '6', '',$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG);  
}

return_success_and_exit('success','');
