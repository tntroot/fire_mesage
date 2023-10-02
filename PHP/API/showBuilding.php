<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Page=SQL_Injection_Prevention('2');
*/
$DEBUG = false;

$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

if(is_numeric($Page)){

    switch($Page){
        case "0":
            try{
                $stmt = $pdo->prepare(prepare_showBuilding(1));
                $stmt->execute();
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }   
            break;
        default:
            $Page=(intval($Page)-1)*10;
            try{
                $stmt = $pdo->prepare(prepare_showBuilding(2));
                $stmt->execute([$Page]);                    
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }        
    }

}else{
    return_error_and_exit('fail', '3', 'noPage',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '4','',$DEBUG); 
}