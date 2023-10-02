<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('藥粉廠商');
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');



if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showSupplier(1));
                    $stmt->execute([$Type]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    
                    $stmt = $pdo->prepare(prepare_showSupplier(2));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showSupplier(3));
                    $stmt->execute(array($Type,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
            
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showSupplier(4));
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
    
    add_Num($data,$DEBUG);
    return_success_and_exit('success',$data);

}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}