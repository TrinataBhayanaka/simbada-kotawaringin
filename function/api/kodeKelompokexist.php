<?php
include "../../config/database.php";  
open_connection();  
	
	$kodeKelompok = $_POST['kodeKelompok'];
	$idus = $_POST['idus'];

	$sql = mysql_query("SELECT COUNT(idus) as total FROM usulan_rencana_pengadaaan_aset WHERE 
		idus ='{$idus}' and kodeKelompok = '{$kodeKelompok}'");

	//$sql2 ="SELECT COUNT(idus) as total FROM usulan_rencana_pengadaaan WHERE idus ='{$idus}'";
	//echo $sql2;	

	while ($row = mysql_fetch_assoc($sql)){
		$list = $row['total'];
	}

	//exit;
	// echo json_encode($list);
	if($list){
		echo 1;
	}else {
		echo 0;
	}
exit;

?>