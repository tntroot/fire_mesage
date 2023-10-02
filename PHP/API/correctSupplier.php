<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Name =SQL_Injection_Prevention('十太');
$New_Name=SQL_Injection_Prevention('偉倫太');
$Type =SQL_Injection_Prevention('瓶子廠商');
$Phone=SQL_Injection_Prevention('0912345678');
$ID=SQL_Injection_Prevention('1');
*/
$DEBUG = false;

$Name= SQL_Injection_Prevention($_POST['Name'] ?? '');
$New_Name= SQL_Injection_Prevention($_POST['New_Name'] ?? '');
$Type= SQL_Injection_Prevention($_POST['Type'] ?? '');
$Phone= SQL_Injection_Prevention($_POST['Phone'] ?? '');
$ID= SQL_Injection_Prevention($_POST['ID'] ?? '');

if($Name){
    try{
        $stmt = $pdo->prepare("UPDATE supplier_data SET `Type`= ? ,NAME= ? ,Phone= ?  WHERE NAME= ? And ID= ?");
        $stmt->execute(array($Type,$New_Name,$Phone,$Name,$ID));
    }catch(Exception $e){
        return_error_and_exit('fail', '1', $e,$DEBUG);     
    } 
}else{
    return_error_and_exit('fail', '1', '',$DEBUG);     
}

return_success_and_exit('success','');