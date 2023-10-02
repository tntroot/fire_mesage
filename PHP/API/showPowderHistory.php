<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
$Type =SQL_Injection_Prevention('乾粉');
$Supplier =SQL_Injection_Prevention('巴泰');
$Page=SQL_Injection_Prevention('1');
$Search=SQL_Injection_Prevention('F00012');
*/
$DEBUG = false;


$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$Supplier= SQL_Injection_Prevention($_GET['Supplier'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');
$Search= SQL_Injection_Prevention($_GET['Search'] ?? '');

function prepare_showPowderHistory($Num1,$Num2,$Search){
    $SQL="SELECT fire_hist.ID AS firehistID, powder_hist.ID AS powderhistID, fire_hist.Num AS Num,fire_data.`Type`,fire_hist.`Date`,powder_hist.Supplier FROM fire_hist INNER JOIN fire_data ON fire_hist.Num=fire_data.Num INNER JOIN powder_hist ON fire_hist.ID=powder_hist.ID " ;
    switch($Num1){
        case 1:
            $SQL=$SQL."WHERE fire_data.`Type`= ? AND powder_hist.Supplier= ? ";
            break;
        case 2:
            $SQL=$SQL."WHERE fire_data.`Type`= ? ";
            break;
        case 3:
            $SQL=$SQL."WHERE powder_hist.Supplier= ?  ";
            break;
        case 4:
            break;
    }
    if($Search){
        if($Num1==4){
            $SQL=$SQL."WHERE fire_hist.Num LIKE '%".$Search."%'  ";
        }else{
            $SQL=$SQL."AND fire_hist.Num LIKE '%".$Search."%'  ";
        }
        
    }
    if($Num2==1){
        return $SQL."ORDER BY fire_hist.`Date` DESC LIMIT ? ,10";
    }else{
        return $SQL."ORDER BY fire_hist.`Date` DESC";
    }
    

}


if(is_numeric($Page)){
    switch($Page){
        case "0":
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(1,0,$Search));
                    $stmt->execute(array($Type,$Supplier));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(2,0,$Search));
                    $stmt->execute([$Type]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(3,0,$Search));
                    $stmt->execute([$Supplier]);
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(4,0,$Search));
                    $stmt->execute();
                }catch(Exception $e){
                    return_error_and_exit('fail', '4', $e,$DEBUG); 
                }   
            }
            break;
        default:
            $Page=(intval($Page)-1)*10;
            if($Type & $Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(1,1,$Search));
                    $stmt->execute(array($Type,$Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Type){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(2,1,$Search));
                    $stmt->execute(array($Type,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else if($Supplier){
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(3,1,$Search));
                    $stmt->execute(array($Supplier,$Page));
                }catch(Exception $e){
                    return_error_and_exit('fail', '3', $e,$DEBUG); 
                }
            }else{
                try{
                    $stmt = $pdo->prepare(prepare_showPowderHistory(4,1,$Search));
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
    add_Num($data,$DEBUG);
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}


