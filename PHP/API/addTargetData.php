<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Data =SQL_Injection_Prevention('B00005');
$Target=SQL_Injection_Prevention('上架');
*/
$DEBUG = false;

$Data= SQL_Injection_Prevention($_POST['Data'] ?? '');
$Target= SQL_Injection_Prevention($_POST['Target'] ?? '');


if(Verification_Num($Data) & ($Target=='換藥' | $Target=='維修' | $Target=='上架')){
        $stmt = $pdo->prepare("SELECT ID FROM target_data WHERE `Data`= ?  AND `STATUS` ='F'");
        $stmt->execute([$Data]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        if(count($data)==0){
            try{
                $stmt = $pdo->prepare("INSERT INTO target_data VALUES(ID, ? , ? ,'F')");
                $stmt->execute(Array($Data,$Target));
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }
    
        }else{
            return_error_and_exit('fail', '2', '',$DEBUG); 
        }
}else{
    return_error_and_exit('fail', '3', '',$DEBUG); 
}

return_success_and_exit('success','');