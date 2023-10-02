<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Type =SQL_Injection_Prevention('');
$Building =SQL_Injection_Prevention('');
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

function prepare_showFire2($Num1,$Num2){
    $SQL="SELECT fire_data.Num,`Type`,bh2.`Time` 'Date',`Building`,`Floor`,`Location` FROM (SELECT * FROM (SELECT * FROM box_hist WHERE Pair='下架' ORDER BY box_hist.ID DESC LIMIT 18446744073709551615)AS bh1 GROUP BY bh1.Fire_Past)AS bh2 LEFT JOIN fire_data ON bh2.Fire_Past=fire_data.Num LEFT JOIN box_data ON bh2.Num=box_data.Num LEFT JOIN location ON box_data.Location_ID=location.ID WHERE fire_data.`Status`='換藥中' ";
    switch($Num1){
        case 1:
            $SQL=$SQL."AND Type= ? AND building= ? ";
            break;
        case 2:
            $SQL=$SQL."AND Type= ? ";
            break;
        case 3:
            $SQL=$SQL."AND building= ? ";
            break;
        case 4:
            break;
    }
    if($Num2==1){
        return $SQL."ORDER BY bh2.`Time` DESC LIMIT ? ,10";
    }else{
        return $SQL."ORDER BY bh2.`Time` DESC";
    }

}

if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Type & $Building){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(1,0));
                    $stmt->execute(array($Type,$Building));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(2,0));
                    $stmt->execute([$Type]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(3,0));
                    $stmt->execute([$Building]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(4,0));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Type & $Building){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(1,1));
                    $stmt->execute(array($Type,$Building,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(2,1));
                    $stmt->execute(array($Type,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Building){
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(3,1));
                    $stmt->execute(array($Building,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire2(4,1));
                    $stmt->execute([$Page]);
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
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}
