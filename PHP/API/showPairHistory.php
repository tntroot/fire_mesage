<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Building =SQL_Injection_Prevention('紅樓');
$Page=SQL_Injection_Prevention('0');
$Search=SQL_Injection_Prevention('');
*/
$DEBUG = false;

$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');
$Search= SQL_Injection_Prevention($_GET['Search'] ?? '');


function prepare_SQL($Num,$Search) {
    $SQL="SELECT a.ID,a.Fire_Past AS Fire_Num,a.Num AS Box_Num,a.`Time` AS UpTime, (case when b.`Time` IS NULL then '還在架上' ELSE b.`Time` END) AS DownTime, `Building`,`Floor`,`Location` FROM (SELECT Num,`Time`,Fire_Past,ID FROM box_hist WHERE `Pair`='上架' AND `Status`='正常')AS a LEFT JOIN (SELECT Num,`Time`,Fire_Past,ID FROM box_hist WHERE `Pair`='下架' AND `Status`='正常')AS b ON b.Num=a.Num AND b.Fire_Past=a.Fire_Past AND 	b.`Time`>a.`Time`  inner JOIN box_data ON a.Num=box_data.Num inner JOIN location ON box_data.Location_ID=location.ID ";
    
    switch($Num){
        case 1:
            $SQL=$SQL." WHERE `Building`= ? GROUP BY a.ID  ";
            break;
        case 2:
            $SQL=$SQL." GROUP BY a.ID ";
            break;
        case 3:
            $SQL=$SQL." WHERE `Building`= ? GROUP BY a.ID   ";
            break;
        case 4:
            $SQL=$SQL." GROUP BY a.ID   ";
            break;
    }

    if($Search){

        $SQL=$SQL."HAVING  CONCAT(`Building`,`Floor`,'樓',`Location`,`Fire_Num`,`Box_Num`) LIKE '%".$Search."%'  ";
        
    }
        
    if($Num==3 or$Num==4){
        $SQL=$SQL."LIMIT ? ,10 ";
    }


    return $SQL;
}


if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_SQL(1,$Search));
                    $stmt->execute([$Building]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    
                    $stmt = $pdo->prepare(prepare_SQL(2,$Search));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_SQL(3,$Search));
                    $stmt->execute(array($Building,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
            
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_SQL(4,$Search));
                    $stmt->execute(array($Page));                    
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
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