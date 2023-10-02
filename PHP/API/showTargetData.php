<?php

require('cache.php');
require('database.php');
require('functions.php');

$DEBUG = false;

try{
    $stmt = $pdo->prepare("SELECT case 
                           when target_data.`Data` LIKE 'B%' then CONCAT(location.`Building`,location.`Floor`,location.`Location`)
                           when target_data.`Data` LIKE 'F%' AND fire_data.Pair='上架' then CONCAT(aa.`Building`,aa.`Floor`,aa.`Location`)
                           else  target_data.`Data` 
                           END AS `Data`,target_data.`Status`,case
                           when target_data.Target = '維修' OR target_data.Target = '換藥' then '1'
                           else  '2' 
                           END AS `Target`
                           FROM target_data left JOIN fire_data ON target_data.`Data`=fire_data.Num
                           LEFT JOIN pair_data ON target_data.`Data`=pair_data.Fire_num
                           left JOIN box_data a ON pair_data.Box_num=a.Num
                           left JOIN location aa ON a.Location_ID=aa.ID
                           left JOIN box_data ON target_data.`Data`=box_data.Num
                           left JOIN location ON box_data.Location_ID=location.ID");
    $stmt->execute();
}catch(Exception $e){
    return_error_and_exit('fail', '4', $e,$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}