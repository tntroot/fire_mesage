<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('乾粉');
$MFG=SQL_Injection_Prevention('2022-05-16');
$Powder_EXP =SQL_Injection_Prevention('2028-01-01');
$Supplier=SQL_Injection_Prevention('十太');
$Quantity =SQL_Injection_Prevention('3');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_POST['Type'] ?? '');
$MFG= SQL_Injection_Prevention($_POST['MFG'] ?? '');
$Powder_EXP= SQL_Injection_Prevention($_POST['Powder_EXP'] ?? '');
$Supplier= SQL_Injection_Prevention($_POST['Supplier'] ?? '');
$Quantity= SQL_Injection_Prevention($_POST['Quantity'] ?? '');




if(Verification_Fire_Data($Type,$MFG,$Powder_EXP,$Supplier,$Quantity)){

    try{
        for($i=0;$i<intval($Quantity);$i++){
            $stmt = $pdo->prepare("INSERT INTO fire_data(`Type`,MFG,Supplier) VALUES( ? , ? , ? );");
            $stmt->execute(array($Type,$MFG,$Supplier));
        
            $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,`Status`,`Date`) SELECT Num,'換藥', ? FROM fire_data ORDER BY Num DESC LIMIT 1;");
            $stmt->execute([$MFG]);
        
            $stmt = $pdo->prepare("INSERT INTO powder_hist SELECT ID, ? , ?  FROM fire_hist ORDER BY ID DESC LIMIT 1;");
            $stmt->execute(array($Powder_EXP,$Supplier));
        }

        return_success_and_exit('success',"");
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '1', '有參數是空值',$DEBUG); 
}