<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;


$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

$SQL="SELECT report.*,CONCAT(`Building`,`Floor`,'樓',`Location`) AS 位置 FROM fire_data INNER JOIN report ON fire_data.Num=report.Num INNER JOIN (SELECT *,MAX(`Time`)AS Maxtime FROM box_hist GROUP BY Fire_Past)r ON r.Fire_Past=report.Num INNER JOIN box_hist ON r.Fire_Past=box_hist.Fire_Past AND r.Maxtime=box_hist.`Time` INNER JOIN box_data ON box_hist.Num=box_data.Num INNER JOIN location ON box_data.Location_ID=location.ID WHERE fire_data.`Status`='維修中' ORDER BY report.`Time` DESC ";

if(is_numeric($Page)){
    switch($Page){
        case "0":
            try{
                $stmt = $pdo->prepare($SQL);
                $stmt->execute();
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            try{
                $stmt = $pdo->prepare($SQL." LIMIT ? ,10");
                $stmt->execute([$Page]);
            }catch(Exception $e){
                return_error_and_exit('fail', '3', $e,$DEBUG); 
            
            }         
    }
}else{
    return_error_and_exit('fail', '3', 'noNum',$DEBUG); 
}



$data = $stmt->fetchAll(PDO::FETCH_OBJ);

if(count($data)>0){
    add_Num($data,$DEBUG);
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}

