<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;

$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

if(is_numeric($Page)){
    switch($Page){
        case "0":
            try{
                $stmt = $pdo->prepare("SELECT repair_hist.`ID`,Num,Pressure,Apperance,Item,`Use`,`Notes`,`Date` FROM fire_hist INNER JOIN repair_hist ON fire_hist.ID=repair_hist.ID ORDER BY `Date` DESC");
                $stmt->execute();
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            try{
                $stmt = $pdo->prepare("SELECT repair_hist.`ID`,Num,Pressure,Apperance,Item,`Use`,`Notes`,`Date` FROM fire_hist INNER JOIN repair_hist ON fire_hist.ID=repair_hist.ID ORDER BY `Date` DESC LIMIT ? ,10");
                $stmt->execute([$Page]);
            }catch(Exception $e){
                return_error_and_exit('fail', '3', $e,$DEBUG); 
            
            }         
    }
}else{
    return_error_and_exit('fail', '3', '沒有接受到參數或參數錯誤',$DEBUG); 
}


$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    add_Num($data,$DEBUG);
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}