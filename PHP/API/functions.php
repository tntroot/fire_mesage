<?php

function SQL_Injection_Prevention($data){ 
    $data = addslashes($data);
    $data = str_replace("_","\_",$data);
    $data = str_replace("%","\%",$data);
    return $data;
}

function return_error_and_exit($status_msg, $data_msg, $msg, $DEBUG) {
    $ret = array();
    $ret['status'] = $status_msg;
    $ret['data'] = $data_msg;
    echo json_encode($ret);
    if ($DEBUG) echo $msg;
    exit;    
}

function return_success_and_exit($status_msg, $data_msg) {
    $ret = array();
    $ret['status'] = $status_msg;
    $ret['data'] = $data_msg;
    echo json_encode($ret);
    exit;    
}

function Verification_Num($Num){                        //驗證序號格式是否正確
    if(strlen($Num)==6 ){
        if(strpos($Num,'B')===0 | strpos($Num,'F')===0){
            return True;    
        }
        else{
            return False;
        }
    }else{
        return False;
    }
}

function Verification_Fire_Data($Type,$MFG,$Powder_EXP,$Supplier,$Quantity){        //驗證滅火器格式是否正確
    $DEBUG = false;
    if($Type & $MFG & $Powder_EXP & $Supplier & $Quantity){
        if(intval($Quantity)>0){
            if(strtotime($MFG) & strtotime($Powder_EXP)){
                return True;    
            }else{
                return_error_and_exit('fail', '3', 'Time_error',$DEBUG); 
            }
        }else{
            return_error_and_exit('fail', '3', 'Quantity_error',$DEBUG); 
        }
    }else{
        return_error_and_exit('fail', '1', 'nodata',$DEBUG); 
    }


}

function add_Num($data,$DEBUG){
    try{
        for($i=0;$i<count($data);$i++){
            $data[$i]->id=$i;
        }
        return $data;
    }catch(Exception $e){
        return_error_and_exit('fail', '7', $e,$DEBUG); 
    }

}

//---------------------------SQL相關---------------------------------
/*
function execute_SQL($SQL,$execute,$data_msg,$DEBUG){
    try{
        $stmt=$pdo->prepare($SQL);
        $stmt->execute([$execute]);
        return $stmt;
    }catch(Exception $e){
        return_error_and_exit('fail', $data_msg, $e,$DEBUG); 
    }
}
*/

function prepare_showBox1($Num) {
    $SQL="SELECT box_data.Num,`Building`,`Floor`,`Location` FROM  Box_data INNER JOIN location ON box_data.Location_ID = location.ID  WHERE Pair ='下架' AND `Status`!='廢棄'";
    switch($Num){
        case 1:
            return $SQL."ORDER BY box_data.Num";
            break;
        case 2:
            return $SQL."ORDER BY box_data.Num LIMIT ? ,10";
            break;
        case 3:
            return $SQL."AND `Building`= ? ORDER BY box_data.Num";
            break;
        case 4:
            return $SQL."AND `Building`= ? ORDER BY box_data.Num LIMIT ? ,10";
            break;
        
    }
}

function prepare_showBox2($Num){
    $SQL="SELECT box_data.`Num`,(SELECT `Time` FROM box_hist a WHERE a.`Status`='廢棄' AND a.Num=box_hist.Num)AS `Date`,`Building`,`Floor`,`Location` FROM box_data LEFT JOIN location ON box_data.location_ID = location.ID LEFT JOIN box_hist ON box_data.Num = Box_hist.Num WHERE Box_data.`Status`='廢棄' AND box_hist.Pair='上架'";
    switch($Num){
        case 1:
            return $SQL."GROUP BY box_data.`Num`";
            break;
        case 2:
            return $SQL."GROUP BY box_data.`Num` LIMIT ? ,10";
            break;
        case 3:
            return $SQL."AND location.`Building`= ? GROUP BY box_data.`Num`";
            break;
        case 4:
            return $SQL."AND location.`Building`= ? GROUP BY box_data.`Num` LIMIT ? ,10";
            break;
    }
}

function target_SQL($target,$DEBUG){
    switch($target){
        case "換藥":
            $SQL="UPDATE fire_data SET `Status`= '換藥中' WHERE Num= ? ;";
            break;
        case "維修":
            $SQL="UPDATE fire_data SET `Status`= '維修中' WHERE Num= ? ;";
            break;
        default:
            return_error_and_exit('fail', '3', 'Target資料錯誤',$DEBUG); 
    }
    return $SQL;
}

function prepare_showBuilding($Num){
    $SQL="SELECT `Building`,Short,`Floor` ";
    switch($Num){
        case 1:
            return $SQL."FROM building";
            break;
        case 2:
            return $SQL."FROM building LIMIT ? ,10";
            break;

    }
}

function prepare_showSupplier($Num){
    $SQL="SELECT `ID` , `NAME`,`Type`,Phone ";
    switch($Num){
        case 1:
            return $SQL."FROM supplier_data WHERE `Type`= ? ";
            break;
        case 2:
            return $SQL."FROM supplier_data";
            break;
        case 3:
            return $SQL."FROM supplier_data WHERE `Type`= ? LIMIT ? ,10";
            break;
        case 4:
            return $SQL."FROM supplier_data LIMIT ? ,10";
            break;
    }
}



function prepare_showFire3($Num1,$Num2){
    $SQL="SELECT fire_data.Num,`Type`,Supplier,MAX(`Date`)AS Abandoned_date,TIMESTAMPDIFF(DAY,MFG,MAX(`Date`))AS Use_Date,(SELECT COUNT(*) FROM fire_hist f WHERE `Status`='換藥' AND f.Num=fire_hist.Num) AS Count FROM fire_data LEFT JOIN fire_hist ON fire_data.Num=fire_hist.Num WHERE fire_data.`Status`='已廢棄' ";
    switch($Num1){
        case 1:
            $SQL=$SQL." AND fire_data.`Type`= ? AND fire_data.Supplier= ? ";
            break;
        case 2:
            $SQL=$SQL." AND fire_data.`Type`= ? ";
            break;
        case 3:
            $SQL=$SQL." AND fire_data.Supplier= ? ";
            break;
        case 4:
            break;
    }
    $SQL=$SQL." GROUP BY fire_data.Num ";
    
    if($Num2==1){
        return $SQL." ORDER BY MAX(`Date`) DESC LIMIT ? ,10";
    }else{
        return $SQL." ORDER BY MAX(`Date`) DESC";
    }
    
}

function prepare_showFire4($Num1,$Num2){
    $SQL="SELECT fire_data.Num,`Type`,fire_data.Supplier,MFG,PowderEXP FROM fire_data LEFT JOIN (SELECT MAX(ID)AS maxid,Num FROM fire_hist WHERE `Status`='換藥' GROUP BY Num)fire_hist ON fire_data.Num=fire_hist.Num LEFT JOIN powder_hist on fire_hist.maxid=powder_hist.ID WHERE fire_data.Pair='下架' AND fire_data.`Status`='正常'  ";
    switch($Num1){
        case 1:
            $SQL=$SQL." AND fire_data.`Type`= ? AND fire_data.Supplier= ? ";
            break;
        case 2:
            $SQL=$SQL." AND fire_data.`Type`= ? ";
            break;
        case 3:
            $SQL=$SQL." AND fire_data.Supplier= ? ";
            break;
        case 4:
            break;
    }

    if($Num2==1){
        return $SQL." LIMIT ? ,10";
    }else{
        return $SQL;
    }
    
}

