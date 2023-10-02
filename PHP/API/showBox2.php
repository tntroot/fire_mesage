<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Building =SQL_Injection_Prevention('');
$Page=SQL_Injection_Prevention('0');
*/

$DEBUG = false;

$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_showBox2(3));
                    $stmt->execute([$Building]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    
                    $stmt = $pdo->prepare(prepare_showBox2(1));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_showBox2(4));
                    $stmt->execute(array($Building,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
            
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showBox2(2));
                    $stmt->execute(array($Page));                    
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

