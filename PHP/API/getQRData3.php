<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('固定架');
$Num=SQL_Injection_Prevention('B00001');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Num= SQL_Injection_Prevention($_GET['Num'] ?? '');



if(Verification_Num($Num)){
    if($Type =='固定架'){
        $stmt = $pdo->prepare("SELECT Box_num,Fire_num FROM box_data INNER JOIN pair_data ON box_data.Num=pair_data.Box_num WHERE box_data.Num= ?  AND box_data.`Pair`='上架';");
        $stmt->execute([$Num]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        if(count($data)>0){
            $Num=$data[0]->Fire_num;
        }else{
            return_error_and_exit('fail', '6', '找不到配對的滅火器',$DEBUG); 
        }
    }else if(($Type!='滅火器')){
        return_error_and_exit('fail', '3', 'Type_error',$DEBUG); 
    }

}else{
    return_error_and_exit('fail', '3', 'Num_error',$DEBUG); 
}
try{
    $stmt = $pdo->prepare("SELECT Num FROM report WHERE Num= ? ");
    $stmt->execute([$Num]);
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($data)>0){
        return_error_and_exit('fail', '2', '已報修過',$DEBUG); 
    }else{
        return_success_and_exit('success',$Num);
    }
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
} 
