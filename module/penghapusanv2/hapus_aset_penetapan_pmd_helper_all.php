<?php
///include "../../../config/config.php";
include "../../config/database.php";

$link = mysqli_connect($CONFIG['default']['db_host'],$CONFIG['default']['db_user'],$CONFIG['default']['db_pass'],$CONFIG['default']['db_name']) or die("Error " . mysqli_error($link)); 

$idPenghapusan = $argv[1];
echo "Id Penghapusan : ".$idPenghapusan."\n\n";

//get list aset
$sqlAst = "SELECT Aset_ID FROM penghapusanaset where 
				Penghapusan_ID  = '{$idPenghapusan}'";
//echo "sqlAst : ".$sqlAst."\n\n";

$result = $link->query($sqlAst); 
$data = array();
while($row = mysqli_fetch_assoc($result)) {
	$data[] = $row['Aset_ID'];
} 
echo "Total Data List Aset : ".count($data)."\n\n";
//print_r($data);
$time_start = microtime(true); 

foreach ($data as $value) {
    # code...
    //counting process loop
    echo "Aset_ID : ".$value."\n\n";

    //HAPUS PENGHAPUSANASET
    $queryHPSAS = "DELETE FROM penghapusanaset where Penghapusan_ID = '{$idPenghapusan}' and Aset_ID = '{$value}'" or die("Error in the consult.." . mysqli_error($link));
    //echo "queryHPSAS : ".$queryHPSAS."\n\n";           
    $execHPSAS = $link->query($queryHPSAS);

    //UPDATE USULANASET
    $queryUSLAS = "UPDATE usulanaset SET StatusKonfirmasi = '2'
    	where Penetapan_ID = '{$idPenghapusan}' and Aset_ID = '{$value}'" or die("Error in the consult.." . mysqli_error($link));
    //echo "queryUSLAS : ".$queryUSLAS."\n\n";           
    $execUSLAS = $link->query($queryUSLAS);

    //UPDATE ASET
	$quertAST = "UPDATE aset SET Dihapus='0'
		WHERE Aset_ID = '{$value}'" or die("Error in the consult.." . mysqli_error($link));	
	$execAST = $link->query($quertAST);	
	//echo "quertAST : ".$quertAST."\n\n";

}

$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins\n\n';

echo "=================== Process Complete. Thank you ===================\n\n";

?>
