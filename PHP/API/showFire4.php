<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('乾粉');
$Supplier =SQL_Injection_Prevention('巴泰');
$Page=SQL_Injection_Prevention('1');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Supplier= SQL_Injection_Prevention($_GET['Supplier'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');



if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(1,0));
                    $stmt->execute(array($Type,$Supplier));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(2,0));
                    $stmt->execute([$Type]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(3,0));
                    $stmt->execute([$Supplier]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(4,0));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(1,1));
                    $stmt->execute(array($Type,$Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(2,1));
                    $stmt->execute(array($Type,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(3,1));
                    $stmt->execute(array($Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire4(4,1));
                    $stmt->execute([$Page]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
                    
    }
}else{
    return_error_and_exit('fail', '3', 'noNum',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}
