<?php
include "../../config/database.php";  
open_connection();  
	
	$kd_kegiatan = $_POST['kd_kegiatan'];
	$idp = $_POST['idp'];
	$tahun = $_POST['tahun'];
	$KodeSatker = $_POST['KodeSatker'];

	$sql = mysql_query("SELECT COUNT(*) as total FROM kegiatan WHERE 
		idp = '{$idp}' AND kd_kegiatan ='{$kd_kegiatan}' 
		AND KodeSatker = '{$KodeSatker}' AND tahun = '{$tahun}'");
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