<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
//圖書資訊大樓  馨園學生宿舍 音樂館 x樓
$Building=SQL_Injection_Prevention('服設館');
$Floor_L=SQL_Injection_Prevention('6');
$Floor_B=SQL_Injection_Prevention('0');
*/

$DEBUG = false;

$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');
$Floor_L= SQL_Injection_Prevention($_GET['Floor_L'] ?? '');
$Floor_B= SQL_Injection_Prevention($_GET['Floor_B'] ?? '');

if($Building){
    try{
        $stmt = $pdo->prepare("SELECT * FROM building WHERE `Building`= ? ");
        $stmt->execute([$Building]);
    }catch(Exception $e){
        return_error_and_exit('fail', '3', $e,$DEBUG); 
    }
    
}else{
    return_error_and_exit('fail', '1', '',$DEBUG); 
}

$data = $stmt->fetchAll(PDO::FETCH_OBJ);
$Floor=$data[0]->Floor;
if(count($data)>0){                 //有地下室
    
    if(strpos($Floor,',')){
        list($L,$B) = explode(",",$Floor);
        $L=intval($L);
        $B=intval($B)*-1;
        if($L!=1){
            for($i=$L;$i>0;$i=$i-1){        
                $L=$i;
                $stmt = $pdo->prepare("SELECT * FROM location WHERE `Building`= ? and `Floor`= ? ");
                $stmt->execute(Array($Building,$i));
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                if(count($data)>0){
                    break;
                }
                
            }
        }
        if($B != 0){
            for($ii=$B;$ii<1;$ii++){
                $B=$ii;
                $stmt = $pdo->prepare("SELECT * FROM location WHERE `Building`= ? and `Floor`= ? ");
                $stmt->execute(Array($Building,$ii));
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                if(count($data)>0){
                    break;
                }
            }

        }
        

    }else{                      //沒地下室
        $L=intval($Floor);
        for($i=$L;$i>0;$i=$i-1){        
            $L=$i;
            $stmt = $pdo->prepare("SELECT * FROM location WHERE `Building`= ? and `Floor`= ? ");
            $stmt->execute(Array($Building,$i));
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            if(count($data)>0){
                break;
            }
        }
        $B=0;
    }

}else{
    return_error_and_exit('fail', '5', '找不到該大樓',$DEBUG); 
}

if(intval($Floor_L)<$L){
    $msg='因為'.$L.'樓有位置資料,所以樓層數無法低於'.$L.'樓';
    return_error_and_exit('6.1', $msg, '',$DEBUG); 
}

if(intval($Floor_B)*-1>$B){
    $msg='因為地下'.$B.'樓有位置資料,所以樓層數無法高於地下'.$B.'樓';
    return_error_and_exit('6.2', $msg, '',$DEBUG); 
}

return_success_and_exit('success','');