<?php

require('cache.php');
require('database.php');
require('functions.php');

$DEBUG = false;

try{
    $Fire = $pdo->prepare("SELECT fire_data.Num,fire_data.Pair,PowderEXP FROM fire_data INNER JOIN fire_hist ON fire_data.Num=fire_hist.Num INNER JOIN (SELECT MAX(ID)AS maxidd,Num FROM fire_hist WHERE `Status`='換藥' GROUP BY Num)AS a ON fire_hist.Num=a.Num AND fire_hist.ID=a.maxidd INNER JOIN powder_hist ON a.maxidd=powder_hist.ID;");
    $Fire->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

try{
    $Box = $pdo->prepare("SELECT Num,Pair,CONCAT(`Building`,`Floor`,`Location`)AS `Location` FROM box_data INNER JOIN location ON box_data.Location_ID=location.ID;");
    $Box->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

try{
    $Pair = $pdo->prepare("SELECT pair_data.Fire_num,pair_data.Box_num,CONCAT(`Building`,`Floor`,`Location`)AS `Location` FROM pair_data INNER JOIN box_data ON pair_data.Box_num=box_data.Num INNER JOIN location ON box_data.Location_ID=location.ID");
    $Pair->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

$Fire_Data = $Fire->fetchAll(PDO::FETCH_OBJ);
$Box_Data = $Box->fetchAll(PDO::FETCH_OBJ);
$Pair_Data = $Pair->fetchAll(PDO::FETCH_OBJ);

if(count($Fire_Data)>0 & count($Box_Data)>0 & count($Pair_Data)>0){
    $ret = array();
    $ret['status'] = 'success';
    $ret['fire'] = $Fire_Data;
    $ret['box'] = $Box_Data;
    $ret['pair'] = $Pair_Data;
    echo json_encode($ret);
    exit;   
}else{
    return_error_and_exit('fail', '5','nodata',$DEBUG); 
}