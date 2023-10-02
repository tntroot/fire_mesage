<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type =SQL_Injection_Prevention('滅火器');
$Num=SQL_Injection_Prevention('F00009');
*/
$DEBUG = false;

$Type= SQL_Injection_Prevention($_POST['Type'] ?? '');
$Num= SQL_Injection_Prevention($_POST['Num'] ?? '');


$idle=False;


if(Verification_Num($Num)){
    if($Type=='滅火器'){
        $stmt = $pdo->prepare("SELECT Box_num,Fire_num FROM box_data INNER JOIN pair_data ON box_data.Num=pair_data.Box_num WHERE Fire_num= ? AND box_data.`Pair`='上架';");
        $stmt->execute([$Num]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $pdo->prepare("SELECT Target FROM target_data WHERE `Data`= ? AND `Status`='F';");
        $stmt->execute([$Num]);
        $check = $stmt->fetchAll(PDO::FETCH_OBJ);
        $Target=$check[0]->Target ?? '';

        if(count($data)>0){
            $Box=$data[0]->Box_num;
            $Fire=$Num;
        }else{
            if(count($check)>0){
                $idle=True;
            }else{
                return_error_and_exit('fail', '6', '閒置但不是要換藥或維修的滅火器',$DEBUG); 
            }
        }
    }else if($Type =='固定架'){
        $stmt = $pdo->prepare("SELECT Box_num,Fire_num FROM box_data INNER JOIN pair_data ON box_data.Num=pair_data.Box_num WHERE box_data.Num= ?  AND box_data.`Pair`='上架';");
        $stmt->execute([$Num]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        if(count($data)>0){
            $Fire=$data[0]->Fire_num;
            $Box=$Num;
        }else{
            return_error_and_exit('fail', '6', '下架的固定架',$DEBUG); 
        }
    }else{
        return_error_and_exit('fail', '3', 'type_error',$DEBUG); 
    }
}else{
    return_error_and_exit('fail', '3', 'Num_error',$DEBUG); 
}



if($idle){
    try{
        $SQL=target_SQL($Target,$DEBUG);

        $stmt = $pdo->prepare($SQL);
        $stmt->execute([$Num]);
        $stmt = $pdo->prepare("UPDATE target_data SET `Status`='T' WHERE `Data`= ?  AND `Status`='F';");
        $stmt->execute([$Num]);

        return_success_and_exit('success1',"");
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    } 
}else{
    try{
        $stmt = $pdo->prepare("SELECT Target FROM target_data WHERE `Data`= ? AND `Status`='F';");
        $stmt->execute([$Fire]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        if(count($data)>0){
            $SQL=target_SQL($Target,$DEBUG);
    
            $stmt = $pdo->prepare($SQL);
            $stmt->execute([$Num]);
            $stmt = $pdo->prepare("UPDATE target_data SET `Status`='T' WHERE `Data`= ?  AND `Status`='F';");
            $stmt->execute([$Num]);
        }
        $stmt = $pdo->prepare("UPDATE box_data SET Pair='下架' WHERE Num= ? ;");
        $stmt->execute([$Box]);
    
        $stmt = $pdo->prepare("UPDATE fire_data SET Pair='下架' WHERE Num= ? ;");
        $stmt->execute([$Fire]);
    
        $stmt = $pdo->prepare("INSERT INTO box_hist(Num,Pair,Fire_past) VALUES( ? ,'下架', ? );");
        $stmt->execute(Array($Box,$Fire));
    
        $stmt = $pdo->prepare("INSERT INTO fire_hist(Num,Pair) VALUES( ? ,'下架');");
        $stmt->execute([$Fire]);
    
        $stmt = $pdo->prepare("DELETE FROM pair_data WHERE Fire_num= ?");
        $stmt->execute([$Fire]);
    
        $stmt = $pdo->prepare("SELECT box_hist.Fire_Past 'Fire_Num',PowderEXP,box_hist.Num 'Box_Num',box_hist.Pair,CONCAT(`Building`,`Floor`,`Location`)AS `Location` FROM box_hist 
                               INNER JOIN (SELECT MAX(ID)AS maxbox_hist,Num,Pair,Fire_Past FROM box_hist GROUP BY Num)AS a ON box_hist.Num=a.Num AND box_hist.ID=a.maxbox_hist
                               INNER JOIN fire_hist ON a.Fire_Past=fire_hist.Num
                               INNER JOIN (SELECT MAX(ID)AS maxfire_hist,Num FROM fire_hist WHERE `Status`='換藥' GROUP BY Num)AS b ON fire_hist.Num=b.Num AND fire_hist.ID=b.maxfire_hist
                               INNER JOIN powder_hist ON powder_hist.ID=b.maxfire_hist 
                               INNER JOIN fire_data ON b.Num=fire_data.Num
                               INNER JOIN box_data ON a.Num=box_data.Num
                               INNER JOIN location ON box_data.Location_ID=location.ID
                               WHERE box_data.Num= ? ");
        $stmt->execute([$Box]);            
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        return_success_and_exit('success1',$data);
    }catch(Exception $e){
        return_error_and_exit('fail', '4', $e,$DEBUG); 
    }

}

