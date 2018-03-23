<?php
//include "../../config/config.php";
include "../../config/database.php";

$link = mysqli_connect($CONFIG['default']['db_host'],$CONFIG['default']['db_user'],$CONFIG['default']['db_pass'],$CONFIG['default']['db_name']) or die("Error " . mysqli_error($link)); 

$time_start = microtime(true); 

$idUsulan = $argv[1];
echo "Id Usulan : ".$idUsulan."\n\n";

$param = $argv[2];
echo "param : ".$param."\n\n";

//revisi
$TAHUN_AKTIF = $argv[3];
echo "TAHUN_AKTIF : ".$TAHUN_AKTIF."\n\n";

$condition = '';
    //get param filter
    $exp = explode('-', $param);
    //print_r($exp);
    foreach ($exp as $value) {
        # code...
        $exp2 = explode('=', $value);
        //print_r($exp2);

        if($exp2['0'] == 'kodeSatker'){
            $param = "=";
            $ext ='';
            $val1 = "ast.".$exp2['0'];
            $val2 = $exp2['1'];
            //revisi
            $kodeSatker = $exp2['1'];
        }elseif($exp2['0'] == 'kodeLokasi'){
            $param = "like";
            $ext ="%";
            $val1 = "ast.".$exp2['0'];
            $val2 = $exp2['1'];
        }elseif($exp2['0'] == 'Tahun'){
            $param = "=";
            $ext ='';
            $val1 = "ast.".$exp2['0'];
            $val2 = $exp2['1'];
        }elseif($exp2['0'] == 'kodeKelompok'){
            $param = "=";
            $ext ='';
            $val1 = "ast.".$exp2['0'];
            $val2 = $exp2['1'];
        }elseif($exp2['0'] == 'TipeAset'){
            $param = "=";
            $ext ='';
            $val1 = "ast.".$exp2['0'];
            $val = $exp2['1'];
            if($val == '1'){
                $paramTipe = 'A';
                $val2 = 'A';
                $JoinTabel = 'tanah as a';
            }elseif($val == '2'){
                $paramTipe = 'B';
                $val2 = 'B';
                $JoinTabel = 'mesin as a';
            }elseif($val == '3'){
                $paramTipe = 'C';
                $val2 = 'C';
                $JoinTabel = 'bangunan as a';
            }elseif($val == '4'){
                $paramTipe = 'D';
                $val2 = 'D';
                $JoinTabel = 'jaringan as a';
            }elseif($val == '5'){
                $paramTipe = 'E';
                $val2 = 'E';
                $JoinTabel = 'asetlain as a';
            }elseif($val == '6'){
                $paramTipe = 'F';
                $val2 = 'F';
                $JoinTabel = 'kdp as a';
            }elseif($val == '7'){
                $paramTipe = 'G';
                $val2 = 'G';
                $JoinTabel = '';
            }
        }

        if($ext != ''){
            $condition .= " ".$val1." ".$param." '".$val2.$ext."' "."AND ";
        }else{  
            $condition .= " ".$val1." ".$param." '".$val2."' "." AND ";
        }    
        
        
    }
//print_r($condition);
//exit();
//update usulan
$quertUS = "UPDATE usulan SET FixUsulan = '0'
            WHERE Usulan_ID = '{$idUsulan}'"
            or die("Error in the consult.." . mysqli_error($link));  
$execUS = $link->query($quertUS);   
//echo "quertUS : ".$quertUS."\n\n";

//list usulan perTahunAktif dan perSatker
$sqlListIdUsulan = "SELECT Usulan_ID FROM usulan where Jenis_Usulan='MTS' AND SatkerUsul= '{$kodeSatker}' AND YEAR(TglUpdate) = '{$TAHUN_AKTIF}'";

$exeListIdUsulan = $link->query($sqlListIdUsulan);
$dataListIdUsul = array(); 
while($rows = mysqli_fetch_assoc($exeListIdUsulan)) {
  $dataListIdUsul[] = $rows['Usulan_ID'];
} 
if(!empty($dataListIdUsul)){
    $usulanList = implode(',', $dataListIdUsul);        
}
//get list aset dari usulan aset
$sql = "SELECT Aset_ID FROM usulanaset where Jenis_Usulan='MTS' AND StatusValidasi='0' AND (StatusKonfirmasi='0' OR StatusKonfirmasi='1') AND Usulan_ID in ($usulanList) ORDER BY Usulan_ID";

$result = $link->query($sql);
$resUsul = array(); 
while($row = mysqli_fetch_assoc($result)) {
  $resUsul[] = $row;
} 
//print_r($resUsul);
if($resUsul){
    $dataArrListUsul =array();
    foreach($resUsul as $asetidUsul){
        //list Aset_ID yang pernah diusulkan
        $dataArrListUsul[]=$asetidUsul['Aset_ID'];
    }
    //print_r($dataArrListUsul);
    //reverse array usulan 
    $ListUsul = array();
    foreach(array_values($dataArrListUsul) as $v){
        $ListUsul[$v] = 1;
    }
    //print_r($ListUsul);
    
    $condition .="ast.fixPenggunaan=1 AND ast.Status_Validasi_Barang=1 AND (ast.kondisi=0 OR ast.kondisi=1 OR ast.kondisi=2 OR ast.kondisi=3)";    
}else{
    $condition .="ast.fixPenggunaan=1 AND ast.Status_Validasi_Barang=1 AND (ast.kondisi=0 OR ast.kondisi=1 OR ast.kondisi=2 OR ast.kondisi=3)";
}
//print_r($condition);

//get list aset
if($paramTipe == 'G'){
    $sql2 = "SELECT ast.Aset_ID FROM aset as ast 
         where {$condition}
         GROUP BY ast.Aset_ID"; 
}else{
    $sql2 = "SELECT ast.Aset_ID FROM aset as ast 
         INNER JOIN {$JoinTabel} ON a.Aset_ID = ast.Aset_ID 
         where {$condition}
         GROUP BY ast.Aset_ID";   
}

//print_r($sql2);
$result2 = $link->query($sql2); 
$resAset = array();
while($row2 = mysqli_fetch_assoc($result2)) {
  $resAset[] = $row2;
} 

if($resAset){
    //list Usulan Aset
    if($ListUsul){
        //list Aset
        $dataArr = array();
        foreach($resAset as $asetidAset){
            //list Aset_ID yang pernah diusulkan
            $needle = $asetidAset['Aset_ID'];
            //matching
            if (!isset($ListUsul[$needle])){
                $dataArr[] = $needle;
                echo "Aset_ID : ".$needle."\n\n";
                $field = "Usulan_ID,Aset_ID,Jenis_Usulan";
                $value = "'{$idUsulan}','{$needle}','MTS'";
                $query = "INSERT INTO usulanaset ({$field}) VALUES ({$value})" or die("Error in the consult.." . mysqli_error($link));
                
                //echo "query : ".$query."\n\n";
                        
                $exec = $link->query($query);
            }                        
        }      
    }else{
        foreach ($resAset as $key => $value) {
            # code...
            $dataArr[] = $value['Aset_ID'];
            $needle = $value['Aset_ID'];
            echo "Aset_ID : ".$needle."\n\n";
            $field = "Usulan_ID,Aset_ID,Jenis_Usulan";
            $value = "'{$idUsulan}','{$needle}','MTS'";
            $query = "INSERT INTO usulanaset ({$field}) VALUES ({$value})" or die("Error in the consult.." . mysqli_error($link));
            
            //echo "query : ".$query."\n\n";
                    
            $exec = $link->query($query);
            
        }
    }    
}
//update usulan
$quertUS = "UPDATE usulan SET FixUsulan = '1', Jenis_Usulan = 'MTS'
            WHERE Usulan_ID = '{$idUsulan}'"
            or die("Error in the consult.." . mysqli_error($link));  
$execUS = $link->query($quertUS);   
//echo "quertUS : ".$quertUS."\n\n";
echo "Total Data List Aset : ".count($dataArr)."\n\n";

$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins\n\n';

echo "=================== Process Complete. Thank you ===================\n\n";

?>
