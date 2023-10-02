<?php
require('cache.php');
require('database.php');
require('functions.php');


/*
$Type =SQL_Injection_Prevention('滅火器');
$Num=SQL_Injection_Prevention('F00001');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Num= SQL_Injection_Prevention($_GET['Num'] ?? '');


if(Verification_Num($Num)){
    if($Type=='滅火器'){
        try{
            $stmt = $pdo->prepare("SELECT fire_data.Num ,fire_data.Pair,PowderEXP
                                   FROM fire_data left JOIN (SELECT * FROM fire_hist WHERE `Status`='換藥')AS fire_hist
                                   on fire_data.Num=fire_hist.Num 
                                   LEFT JOIN powder_hist
                                   ON fire_hist.ID=powder_hist.ID
                                   WHERE fire_data.Num= ?
                                   ORDER BY fire_hist.ID DESC LIMIT 1;");
            $stmt->execute([$Num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG);  
        }
    }else if($Type =='固定架'){
        try{
            $stmt = $pdo->prepare("SELECT box_data.Num ,Pair,CONCAT(location.`Building`,location.`Floor`,location.`location`) AS Location
                                   FROM box_data,location WHERE box_data.Num= ? AND box_data.Location_ID=location.ID");
            $stmt->execute([$Num]);
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }
    }else{
        return_error_and_exit('fail', '3', 'type_error',$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '3', 'Num_error',$DEBUG); 
}



$data = $stmt->fetchAll(PDO::FETCH_OBJ);
if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}   