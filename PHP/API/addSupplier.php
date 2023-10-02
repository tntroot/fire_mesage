<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Name =SQL_Injection_Prevention('圖書資訊大樓');
$Type=SQL_Injection_Prevention('瓶子廠商');
$Phone=SQL_Injection_Prevention('0912345678');
*/
$DEBUG = false;

$Name= SQL_Injection_Prevention($_POST['Name'] ?? '');
$Type= SQL_Injection_Prevention($_POST['Type'] ?? '');
$Phone= SQL_Injection_Prevention($_POST['Phone'] ?? '');


if($Name & $Type & $Phone){
    try{
        $stmt = $pdo->prepare("INSERT INTO supplier_data(`Type`,`Name`,Phone) 
                               VALUES ( ? , ? , ? );");
        $stmt->execute(array($Type,$Name,$Phone));
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '1', '',$DEBUG);   
}

return_success_and_exit('success','');
