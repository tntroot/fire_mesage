<?php

require('cache.php');
require('database.php');
require('functions.php');

/*
//圖書資訊大樓  馨園學生宿舍 音樂館 x樓
$Building=SQL_Injection_Prevention('活動中心');
$New_Building=SQL_Injection_Prevention('');
$Build_Short=SQL_Injection_Prevention('SSR');
$Floor_L=SQL_Injection_Prevention('1');
$Floor_B=SQL_Injection_Prevention('0');
*/

$DEBUG = false;


$Building= SQL_Injection_Prevention($_POST['Building'] ?? '');
$New_Building= SQL_Injection_Prevention($_POST['New_Building'] ?? '');
$Build_Short= SQL_Injection_Prevention($_POST['Build_Short'] ?? '');
$Floor_L= SQL_Injection_Prevention($_POST['Floor_L'] ?? '');
$Floor_B= SQL_Injection_Prevention($_POST['Floor_B'] ?? '');

if($Building & $Build_Short & $Floor_L & $Floor_B){
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

try{
    $Short=$data[0]->Short;
    $Building_Name=$data[0]->Building;
    $Floor=$data[0]->Floor;
}catch(Exception $e){
    return_error_and_exit('fail', '3', $e,$DEBUG); 
}


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

if(intval($Floor_L)<$L | intval($Floor_B)*-1>$B){
    return_error_and_exit('fail', '6', '部分樓層還有資料，無法向下修改',$DEBUG); 
}



if($Floor_B !='0'){
    $Floor_=$Floor_L.'樓'.','.$Floor_B.'樓';
}else{
    $Floor_=$Floor_L.'樓';
}


if($Short==$Build_Short & $Building_Name==$Building & $Floor==$Floor_){   
    if($New_Building){
        try{
            $stmt = $pdo->prepare("UPDATE building SET `Building`= ? WHERE `Building`= ? ;");
            $stmt->execute(Array($New_Building,$Building));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }

    }else{
        return_error_and_exit('fail', '2', '修改的資料與與原始資料相同',$DEBUG); 
    }
    
}else{

    if($New_Building){
        try{
            $stmt = $pdo->prepare("UPDATE building SET `Building`= ? ,Short= ? ,`Floor`= ?  WHERE `Building`= ? ;");
            $stmt->execute(Array($New_Building,$Build_Short,$Floor_,$Building));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }
    
    }else{
        try{
            $stmt = $pdo->prepare("UPDATE building SET Short= ? ,`Floor`= ?  WHERE `Building`= ? ;");
            $stmt->execute(Array($Build_Short,$Floor_,$Building));
        }catch(Exception $e){
            return_error_and_exit('fail', '3', $e,$DEBUG); 
        }
            
        
    }
}




return_success_and_exit('success','');