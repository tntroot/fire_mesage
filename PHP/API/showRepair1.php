<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;

$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');


if(is_numeric($Page)){
    switch($Page){
        case "0":
            try{
                $stmt = $pdo->prepare("SELECT report.*,CONCAT(`Building`,`Floor`,'樓',`Location`) AS Location FROM fire_data INNER JOIN report ON fire_data.Num=report.Num INNER JOIN pair_data ON report.Num=pair_data.Fire_num INNER JOIN box_data ON pair_data.Box_num=box_data.Num INNER JOIN location ON box_data.Location_ID=location.ID WHERE fire_data.`Status`='需維修' ORDER BY report.`Time` DESC ");
                $stmt->execute();
            }catch(Exception $e){
                return_error_and_exit('fail', '4', $e,$DEBUG); 
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            try{
                $stmt = $pdo->prepare("SELECT report.*,CONCAT(`Building`,`Floor`,'樓',`Location`) AS Location FROM fire_data INNER JOIN report ON fire_data.Num=report.Num INNER JOIN pair_data ON report.Num=pair_data.Fire_num INNER JOIN box_data ON pair_data.Box_num=box_data.Num INNER JOIN location ON box_data.Location_ID=location.ID WHERE fire_data.`Status`='需維修' ORDER BY report.`Time` DESC  LIMIT ? ,10");
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