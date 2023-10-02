<?php

require('cache.php');
require('database.php');
require('functions.php');

$DEBUG = false;

try{
    $ExpireSQL = $pdo->prepare("SELECT COUNT(*)AS Expire FROM (SELECT MAX(ID)AS maxid,Num FROM fire_hist WHERE `Status`='換藥' GROUP BY Num)AS f INNER JOIN fire_hist ON fire_hist.Num=f.Num AND fire_hist.ID=f.maxid INNER JOIN powder_hist ON fire_hist.ID=powder_hist.ID INNER JOIN fire_data ON fire_hist.Num=fire_data.Num WHERE PowderEXP BETWEEN  CURDATE() AND DATE_add(CURDATE(),INTERVAL 30 DAY) AND fire_data.`STATUS` !='換藥中'");
    $ExpireSQL->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

try{
    $RepairSQL = $pdo->prepare("SELECT COUNT(*) AS Repair FROM report");
    $RepairSQL->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

$Expire = $ExpireSQL->fetchAll(PDO::FETCH_OBJ);
$Repair = $RepairSQL->fetchAll(PDO::FETCH_OBJ);

if(count($Expire)>0 & count($Repair)>0){
    $Expire=$Expire[0]->Expire;
    $Repair=$Repair[0]->Repair;
}else{
    return_error_and_exit('fail', '4', 'nodata',$DEBUG); 
}


$ret = array();
$ret['status'] = 'success';
$ret['Expire'] = $Expire;
$ret['Repair'] = $Repair;
echo json_encode($ret);
exit;   

