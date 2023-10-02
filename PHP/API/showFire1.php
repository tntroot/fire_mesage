<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('');
$Supplier =SQL_Injection_Prevention('');
$Page=SQL_Injection_Prevention('2');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Supplier= SQL_Injection_Prevention($_GET['Supplier'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');

function prepare_showFire1($Num1,$Num2){
    $SQL="SELECT Num,`Type`,Supplier,MFG 'Date' FROM fire_data  WHERE (case when fire_data.Pair='上架' AND fire_data.`Status`='正常' then '正常' when fire_data.Pair='下架' AND fire_data.`Status`='正常' then '閒置' ELSE fire_data.`Status` END)='閒置'";
    switch($Num1){
        case 1:
            $SQL=$SQL."AND Type= ? AND Supplier= ? ";
            break;
        case 2:
            $SQL=$SQL."AND Type= ? ";
            break;
        case 3:
            $SQL=$SQL."AND Supplier= ? ";
            break;
        case 4:
            break;
    }
    if($Num2==1){
        return $SQL."ORDER BY MFG DESC,Num DESC  LIMIT ? ,10";
    }else{
        return $SQL."ORDER BY MFG DESC";
    }

}

if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(1,0));
                    $stmt->execute(array($Type,$Supplier));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(2,0));
                    $stmt->execute([$Type]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(3,0));
                    $stmt->execute([$Supplier]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(4,0));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=intval($Page);
            if($Page==1){
                $Page=0;
            }else{
                $Page=($Page-1)*10;
            }
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(1,1));
                    $stmt->execute(array($Type,$Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(2,1));
                    $stmt->execute(array($Type,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(3,1));
                    $stmt->execute(array($Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showFire1(4,1));
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
