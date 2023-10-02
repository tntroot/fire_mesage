<?php

require('cache.php');
require('database.php');
require('functions.php');
/*
$Type=SQL_Injection_Prevention('');
$State=SQL_Injection_Prevention('');
$Building=SQL_Injection_Prevention('');
$Sort_Object=SQL_Injection_Prevention('Date');
$Sort=SQL_Injection_Prevention('High');
$Fields=SQL_Injection_Prevention('Num,Type,State,Location,PowderEXP,');
$Filter_Object=SQL_Injection_Prevention('');
$Filter_Symbols=SQL_Injection_Prevention('');
$Filter_Number=SQL_Injection_Prevention('');
$Page=SQL_Injection_Prevention('0');
*/
$DEBUG = false;


$Type= SQL_Injection_Prevention($_GET['Type'] ?? '');
$State= SQL_Injection_Prevention($_GET['State'] ?? '');
$Building= SQL_Injection_Prevention($_GET['Building'] ?? '');
$Sort_Object= SQL_Injection_Prevention($_GET['Sort_Object'] ?? '');
$Sort= SQL_Injection_Prevention($_GET['Sort'] ?? '');
$Fields= SQL_Injection_Prevention($_GET['Fields'] ?? '');
$Filter_Object= SQL_Injection_Prevention($_GET['Filter_Object'] ?? '');
$Filter_Symbols= SQL_Injection_Prevention($_GET['Filter_Symbols'] ?? '');
$Filter_Number= SQL_Injection_Prevention($_GET['Filter_Number'] ?? '');
$Page= SQL_Injection_Prevention($_GET['Page'] ?? '');


$SQL="SELECT fire_data.Num,fire_data.`Type`,(case when fire_data.Pair='上架' AND fire_data.`Status`='正常' then '正常'	when fire_data.Pair='下架' AND fire_data.`Status`='正常' then '閒置' ELSE fire_data.`Status` END)AS  State,`Building`,`Floor`,`Location`,CONCAT(powder_hist.PowderEXP,'(',TIMESTAMPDIFF(DAY,CURDATE(),powder_hist.PowderEXP),')')AS 'PowderEXP',tt.`Date` ";



if($Fields){
    list($a,$b,$c,$d,$e,$f) = explode(",",$Fields);
    if($a!="Num"){
        $SQL=str_replace("fire_data.Num,","",$SQL);
    }
    if($b!="Type"){
        $SQL=str_replace(",fire_data.`Type`","",$SQL);
    }
    if($c!="State"){
        $SQL=str_replace(",(case when fire_data.Pair='上架' AND fire_data.`Status`='正常' then '正常'	when fire_data.Pair='下架' AND fire_data.`Status`='正常' then '閒置' ELSE fire_data.`Status` END)AS  State","",$SQL);
    }
    if($d!="Location"){
        $SQL=str_replace("`Building`,`Floor`,`Location`","",$SQL);
    }
    if($e!="PowderEXP"){
        $SQL=str_replace(",CONCAT(powder_hist.PowderEXP,'(',TIMESTAMPDIFF(DAY,CURDATE(),powder_hist.PowderEXP),')')AS 'PowderEXP'","",$SQL);
    }
    if($f!="Date"){
        $SQL=str_replace(",tt.`Date`","",$SQL);
    }

    $SQL=$SQL." FROM (SELECT * FROM fire_data WHERE `Status`!='已廢棄' and `Status`!='換藥中')AS fire_data LEFT JOIN pair_data ON fire_data.Num=pair_data.Fire_num LEFT JOIN box_data ON pair_data.Box_num=box_data.Num LEFT JOIN location ON box_data.Location_ID=location.ID LEFT JOIN (SELECT * FROM  (SELECT * FROM fire_hist WHERE `Status`='換藥' ORDER BY fire_hist.ID desc LIMIT 18446744073709551615)AS t GROUP BY t.Num)AS tt ON fire_data.Num=tt.Num LEFT JOIN powder_hist ON tt.ID=powder_hist.ID WHERE fire_data.`Status`='正常'  ";
}else{
    return_error_and_exit('fail', '3', 'Fields無參數',$DEBUG); 
}


if($Type){
    $SQL=$SQL." AND fire_data.`Type` = '$Type' ";
}


if($State){
    if($State=='正常'){
        $SQL=$SQL." AND fire_data.Pair!='下架 '";
    }else if($State=='閒置'){
        $SQL=$SQL." AND fire_data.Pair='下架 '";
    }else{
        return_error_and_exit('fail', '3', 'State無參數',$DEBUG); 
    }
}


if($Building){
    $SQL=$SQL." AND building =  '$Building'";
}


if($Filter_Object & $Filter_Symbols & $Filter_Number){

    if($Filter_Object=='ExcessTime'){
        $SQL=$SQL." AND TIMESTAMPDIFF(DAY,CURDATE(),powder_hist.PowderEXP) ";
    }else{
        return_error_and_exit('fail', '3', 'Filter_Object參數錯誤',$DEBUG); 
    }

    if($Filter_Symbols =='>' | $Filter_Symbols =='=' | $Filter_Symbols =='<'){
        $SQL=$SQL." $Filter_Symbols ";
    }else{
        return_error_and_exit('fail', '3', 'Filter_Symbols參數錯誤',$DEBUG); 
    }

    if(is_numeric($Filter_Number) & intval($Filter_Number)>=0){
        $SQL=$SQL." $Filter_Number ";
    }else{
        return_error_and_exit('fail', '3', 'Filter_Number參數錯誤',$DEBUG); 
    }
}


if($Sort_Object & $Sort){
    if($Sort_Object=='PowderEXP'){
        $SQL=$SQL." ORDER BY powder_hist.PowderEXP ";
    }else if($Sort_Object=='Date'){
        $SQL=$SQL." ORDER BY tt.`Date` ";
    }else{
        return_error_and_exit('fail', '3', 'Sort_Object參數錯誤',$DEBUG); 
    }

    if($Sort){
        if($Sort=='Low'){
            $SQL=$SQL." asc ";
        }else if($Sort=='High'){
            $SQL=$SQL." desc ";
        }else{
            return_error_and_exit('fail', '3', 'Sort參數錯誤',$DEBUG); 
        }
    }else{
        return_error_and_exit('fail', '3', 'Sort無參數',$DEBUG); 
    }


}else{
    return_error_and_exit('fail', '1', 'Sort相關參數為空值',$DEBUG); 
}


if(is_numeric($Page)){
    switch($Page){
        case "0":
            try{
                $stmt = $pdo->prepare($SQL);
                $stmt->execute();
                break;
            }catch(Exception $e){
                return_error_and_exit('fail', '3', $e,$DEBUG); 
            }
        default:
            try{
                $Page=(intval($Page)-1)*10;
                $stmt = $pdo->prepare($SQL." LIMIT ? ,10 ");
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
    return_success_and_exit('success',$data);
}else{
    return_error_and_exit('fail', '5', 'nodata',$DEBUG); 
}
